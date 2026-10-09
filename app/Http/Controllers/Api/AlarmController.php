<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlarmPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AlarmController extends Controller
{
    public function status(Request $request, AlarmPushService $alarms)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        try {
            return response()->json($alarms->status());
        } catch (\Throwable $error) {
            $this->logFailure($error, 'status');
            return response()->json(['message' => 'Status alarm gagal dimuat. Coba lagi.'], 502);
        }
    }

    public function store(Request $request, AlarmPushService $alarms)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        $data = $request->validate(['status' => 'required|boolean', 'code' => 'required|integer|between:0,4', 'siren' => 'sometimes|boolean']);
        try {
            $pushSent = $alarms->send((bool) $data['status'], (int) $data['code'], $request->boolean('siren', true));
            return response()->json(['status' => 'Success', 'push_sent' => $pushSent]);
        } catch (\Throwable $error) {
            $this->logFailure($error, 'store');
            // Do not expose credentials, OAuth assertions or upstream response bodies.
            return response()->json(['message' => 'Pengiriman alarm gagal. Periksa konfigurasi Firebase server.'], 502);
        }
    }
    private function logFailure(\Throwable $error, string $operation): void
    {
        $context = ['operation' => $operation, 'exception' => get_class($error)];
        $safeReasons = [
            'Firebase credentials file missing or unreadable',
            'Firebase credentials incomplete',
            'Firebase project mismatch',
            'Firebase signing failed',
        ];
        if (in_array($error->getMessage(), $safeReasons, true)) {
            $context['reason'] = $error->getMessage();
        }
        if ($error instanceof \JsonException) {
            $context['reason'] = 'Invalid Firebase credential JSON';
        }
        if (preg_match('/cURL error (\d+)/', $error->getMessage(), $matches)) {
            $context['curl_code'] = (int) $matches[1];
        }
        if ($error instanceof \Illuminate\Http\Client\RequestException) {
            $context['http_status'] = $error->response->status();
            $status = $error->response->json('error.status');
            if (is_string($status) && preg_match('/^[A-Z_]{1,80}$/', $status)) {
                $context['firebase_status'] = $status;
            }
            $oauth = $error->response->json('error');
            if (is_string($oauth) && in_array($oauth, ['invalid_grant', 'invalid_client', 'unauthorized_client', 'invalid_scope'], true)) {
                $context['oauth_error'] = $oauth;
            }
        }
        Log::warning('Firebase alarm failed', $context);
    }
}

