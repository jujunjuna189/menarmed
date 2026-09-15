<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LogViewerController extends Controller
{
    public function index(Request $request)
    {
        $files = $this->logFiles();
        $selectedFile = $request->get('file', $files[0]['name'] ?? null);
        $selectedPath = $this->resolveLogPath($selectedFile);

        if (!$selectedPath || !File::exists($selectedPath)) {
            $selectedFile = $files[0]['name'] ?? null;
            $selectedPath = $this->resolveLogPath($selectedFile);
        }

        return view('log-viewer.index', [
            'files' => $files,
            'selectedFile' => $selectedFile,
            'content' => $selectedPath ? $this->readTail($selectedPath) : '',
        ]);
    }

    public function download(Request $request)
    {
        $selectedPath = $this->resolveLogPath($request->get('file'));

        abort_unless($selectedPath && File::exists($selectedPath), 404);

        return response()->download($selectedPath);
    }

    private function logFiles()
    {
        $logPath = storage_path('logs');

        if (!File::isDirectory($logPath)) {
            File::makeDirectory($logPath, 0755, true);
        }

        return collect(File::files($logPath))
            ->filter(function ($file) {
                return Str::endsWith($file->getFilename(), '.log');
            })
            ->sortByDesc(function ($file) {
                return $file->getMTime();
            })
            ->map(function ($file) {
                return [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                ];
            })
            ->values()
            ->all();
    }

    private function resolveLogPath($file)
    {
        if (!$file || $file !== basename($file) || !Str::endsWith($file, '.log')) {
            return null;
        }

        return storage_path('logs/' . $file);
    }

    private function readTail($path, $bytes = 262144)
    {
        $size = File::size($path);
        $handle = fopen($path, 'rb');

        if (!$handle) {
            return '';
        }

        if ($size > $bytes) {
            fseek($handle, -$bytes, SEEK_END);
            fgets($handle);
        }

        $content = stream_get_contents($handle);
        fclose($handle);

        return $content ?: '';
    }

    private function formatBytes($bytes)
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }
}
