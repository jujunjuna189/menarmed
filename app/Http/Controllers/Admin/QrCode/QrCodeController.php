<?php

namespace App\Http\Controllers\Admin\QrCode;

use App\Http\Controllers\Controller;
use App\Models\QrCodeModel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QrCodeController extends Controller
{
    //Code ket
    // 01-01 Izin Keluar
    // 01-02 Izin Masuk
    // 02-01-(A/B/C) Kembalikan Senjata
    // 02-02-(A/B/C) Ambil Senjata


    public function index()
    {
        $qrcodes = QrCodeModel::orderBy('key')->orderBy('title')->get();

        return view('generate_qrcode.qrcode', [
            'qrcode' => $qrcodes,
            'categoryCount' => $qrcodes->groupBy('key')->count(),
        ]);
    }

    public function update(Request $request, QrCodeModel $qrcode)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:255',
                'regex:/^[A-Za-z0-9._-]+$/',
                Rule::unique('qrcode', 'code')->ignore($qrcode->id),
            ],
        ]);

        $qrcode->update($validated);

        return response()->json([
            'message' => 'QR Code berhasil diperbarui.',
            'data' => $qrcode->fresh(),
        ]);
    }

    public function print(QrCodeModel $qrcode)
    {
        return view('generate_qrcode.generate', compact('qrcode'));
    }

    /* Generate code
    * @param code string
    */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'exists:qrcode,code'],
        ]);
        $qrcode = QrCodeModel::where('code', $validated['code'])->firstOrFail();

        return redirect()->route('qrcode.print', $qrcode);
    }
}
