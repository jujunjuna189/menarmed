<?php

namespace App\Http\Controllers\Admin\Absensi;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AbsensiController extends Controller
{
    //
    public function index()
    {
        $data['user'] = QueryBuilder::for(User::class)->allowedFilters(['name'])->get();

        return view('monitor.absensi', $data);
    }

    public function track_maps()
    {
        $data['user'] = User::all();
        return view('monitor.track_maps', $data);
    }

    /**
     * Import absensi from excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,excel,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\AbsensiImport, $request->file('file'));

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil import data absensi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template for absensi import
     */
    public function downloadTemplate()
    {
        return response()->streamDownload(function() {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $headers = ['user_id', 'keterangan', 'latitude', 'longitude', 'tanggal'];
            $sheet->fromArray([$headers], NULL, 'A1');

            // Sample Data
            $sample = ['1', 'HADIR', '-6.2088', '106.8456', '2023-10-27 08:00:00'];
            $sheet->fromArray([$sample], NULL, 'A2');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'template_import_absensi.xlsx');
    }
}
