<?php

namespace App\Http\Controllers\Admin\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\SliderModel;
use App\Models\TextMarqueeModel;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $dashboard_marquee = TextMarqueeModel::first();
        $dashboard_slider = SliderModel::dashboardSlider();

        $data['dashboard_marquee'] = $dashboard_marquee;
        $data['dashboard_slider'] = $dashboard_slider;
        $data['dashboard_slider_number'] = 0;
        $data['dashboard_slider_indicator'] = 0;
        $data['dashboard_slider_item'] = 1;

        return view('pengaturan.index', $data);
    }
}
