<?php

namespace App\Http\Controllers\Admin\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PenggunaController extends Controller
{
    /**
     * Setting kolom table untuk admin atau pun role yang lain
     */
    private function tableSetting($role)
    {
        $column = [];
        switch ($role) {
            case 1: // Admin
                $column['kemampuan'] = false;
                $column['aksi'] = true;
                break;
            case 3: // Personil
                $column['kemampuan'] = true;
                $column['aksi'] = true;
                break;
            default: // Default
                $column['kemampuan'] = false;
                $column['aksi'] = false;
                break;
        }

        return (object) $column;
    }

    /**
     * pengguna first page function
     * @return view
     * @var key<int> exp: 1, 1
     */
    public function index(Request $request)
    {
        $role_key = $request->key;
        $pengguna = QueryBuilder::for(User::class)
            ->where('role', $role_key)
            ->orderBy('name', 'asc')
            ->allowedFilters('name')
            ->get();

        $data['pengguna'] = $pengguna;
        $data['role'] = \App\Models\RoleModel::where('key', $role_key)->first();
        $data['table'] = $this->tableSetting($role_key);
        $data['no'] = 1;

        return view('pengguna.index', $data);
    }

    /**
     * pengguna first page function
     * @return view
     * @var key<int> exp: 1, 1
     */
    public function indexJson(Request $request)
    {
        $role_key = $request->key;
        $pengguna = QueryBuilder::for(User::class)
            ->where('role', $role_key)
            ->orderBy('name', 'asc')
            ->allowedFilters('name')
            ->get();

        $data['pengguna'] = $pengguna;
        $data['role'] = \App\Models\RoleModel::where('key', $role_key)->first();
        $data['table'] = $this->tableSetting($role_key);
        $data['no'] = 1;

        return response()->json([
            "status" => "success",
            "message" => "Berhasil mengambil user",
            "data" => $data,
        ]);
    }

    /**
     * view pengguna
     * @var user_id int require
     */
    public function view(Request $request)
    {
        $data['user'] = User::find($request->user_id);

        return view('pengguna.view', $data);
    }

    /**
     * pengguna update role only function
     */
    public function updateRole(Request $request)
    {

        $pengguna = User::find($request->id);

        $pengguna->role = $request->role ?? 1;
        $pengguna->save();

        return response()->json([
            "status" => "success",
            "message" => "Berhasil mengubah user",
            "data" => $pengguna,
        ]);
    }

    /**
     * Import user from excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,excel,xls'
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('file'));

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil import data pengguna'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal import data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download template for user import
     */
    public function downloadTemplate()
    {
        return response()->streamDownload(function() {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $headers = ['nama', 'email', 'password', 'role_id', 'pangkat', 'korp', 'satuan', 'jabatan', 'tempat_lahir', 'tgl_lahir', 'agama', 'gol_darah', 'sumber_pa', 'senjata'];
            $sheet->fromArray([$headers], NULL, 'A1');

            // Sample Data
            $sample = ['Contoh User', 'user@example.com', 'password123', '3', 'Serda', 'CPL', 'Satuan A', 'Anggota', 'Jakarta', '1990-01-01', 'Islam', 'A', 'Akamil', 'M16'];
            $sheet->fromArray([$sample], NULL, 'A2');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
        }, 'template_import_user.xlsx');
    }
}
