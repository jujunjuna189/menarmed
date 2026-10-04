<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\SliderModel;
use App\Models\TextMarqueeModel;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function updateSlider(Request $request, $id)
    {
        $request->validate(['file' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120']);
        $slider = SliderModel::where('key', 1)->findOrFail($id);
        $file = $request->file('file');
        $path = 'storage/slider/dashboard/' . \Illuminate\Support\Str::uuid() . '.' . $file->extension();
        $file->move(public_path('storage/slider/dashboard'), basename($path));
        try {
            $slider->update(['path' => $path]);
        } catch (\Throwable $error) {
            \Illuminate\Support\Facades\File::delete(public_path($path));
            throw $error;
        }

        return redirect()->route('pengaturan')->with('success', 'Gambar slider berhasil diperbarui.');
    }

    public function index()
    {
        $dashboard_marquee = TextMarqueeModel::first();
        $dashboard_slider = SliderModel::dashboardSlider();
        $sliderSizes = [];
        $sliderSizeWarnings = [];
        $publicRoot = realpath(public_path());
        foreach ($dashboard_slider as $slider) {
            $path = realpath(public_path($slider->getRawOriginal('path')));
            $bytes = false;
            if ($publicRoot && $path && strpos($path, $publicRoot . DIRECTORY_SEPARATOR) === 0 && is_file($path) && is_readable($path)) {
                $bytes = filesize($path);
            }
            $sliderSizes[$slider->id] = $bytes !== false
                ? number_format($bytes / (1024 * 1024), 2, ',', '.') . ' MB'
                : 'Ukuran tidak tersedia';
            $sliderSizeWarnings[$slider->id] = $bytes !== false && $bytes > 1024 * 1024;
        }

        $data['dashboard_marquee'] = $dashboard_marquee;
        $data['dashboard_slider'] = $dashboard_slider;
        $data['slider_sizes'] = $sliderSizes;
        $data['slider_size_warnings'] = $sliderSizeWarnings;
        $data['dashboard_slider_number'] = 0;
        $data['dashboard_slider_indicator'] = 0;
        $data['dashboard_slider_item'] = 1;

        return view('pengaturan.index', $data);
    }
}
