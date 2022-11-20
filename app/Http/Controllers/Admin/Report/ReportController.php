<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\AbsensiModel;
use App\Models\GudangSenjataModel;
use App\Models\LogistikModel;
use App\Models\PerizinanKendaraanModel;
use App\Models\PerizinanModel;
use App\Models\PerizinanRanpurModel;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function absensi()
    {
        $data['report'] = AbsensiModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.absensi', $data);
    }

    public function perizinan()
    {
        $data['report'] = PerizinanModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.perizinan', $data);
    }

    public function ranpur()
    {
        $data['report'] = PerizinanRanpurModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.ranpur', $data);
    }

    public function kendaraan()
    {
        $data['report'] = PerizinanKendaraanModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.kendaraan', $data);
    }


    public function gudang_senjata()
    {
        $data['report'] = GudangSenjataModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.gudang_senjata', $data);
    }

    public function logistik()
    {
        $data['report'] = LogistikModel::orderBy('id', 'desc')->get();
        $data['no'] = 1;
        return view('rekap_data.logistik', $data);
    }
}
