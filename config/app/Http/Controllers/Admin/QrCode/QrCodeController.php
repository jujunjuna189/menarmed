<?php

namespace App\Http\Controllers\Admin\QrCode;

use App\Http\Controllers\Controller;
use App\Models\QrCodeModel;
use Illuminate\Http\Request;

class QrCodeController extends Controller
{
    //Code ket
    // 01-01 Izin Keluar
    // 01-02 Izin Masuk
    // 02-01-(A/B/C) Kembalikan Senjata
    // 02-02-(A/B/C) Ambil Senjata


    public function index()
    {
        $data['qrcode'] = QrCodeModel::orderBy('key', 'asc')->get();
        return view('generate_qrcode.qrcode', $data);
    }

    /* Generate code
    * @param code string
    */
    public function generate(Request $request)
    {
        $where['code'] = $request->code;
        $data['qrcode'] = QrCodeModel::where($where)->first();
        return view('generate_qrcode.generate', $data);
    }
}
