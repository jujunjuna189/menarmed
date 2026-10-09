<?php

namespace App\Http\Controllers\Admin\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            case 3: // Personel
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
        $pageSize = (int) $request->input('page.size', 10);
        $pageSize = in_array($pageSize, [10, 25, 50, 100], true) ? $pageSize : 10;
        $sort = $request->input('sort', 'name');
        $sort = in_array($sort, ['name', '-name', 'email', '-email'], true) ? $sort : 'name';

        $pengguna = QueryBuilder::for(User::class)
            ->where('role', $role_key)
            ->orderBy(ltrim($sort, '-'), strpos($sort, '-') === 0 ? 'desc' : 'asc')
            ->orderBy('id', 'asc')
            ->allowedFilters('name')
            ->paginate($pageSize, ['*'], 'page[number]', max(1, (int) $request->input('page.number', 1)))
            ->appends($request->input());

        $data['pengguna'] = $pengguna;
        $data['role'] = \App\Models\RoleModel::where('key', $role_key)->first();
        $data['table'] = $this->tableSetting($role_key);
        $data['controller'] = $this;
        $data['page_size'] = $pageSize;
        $data['search_name'] = $request->input('filter.name', '');
        $data['sort'] = $sort;

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
        $data['user'] = User::findOrFail($request->user_id);

        return view('pengguna.view', $data);
    }

    /**
     * pengguna update role only function
     */
    public function updateRole(Request $request)
    {
        $request->validate(['id' => ['required', 'integer', 'exists:users,id'], 'role' => ['required', 'integer', 'in:1,3']]);
        if ((int) $request->id === (int) auth()->id() && (int) $request->role !== 1) {
            return response()->json(['message' => 'Anda tidak dapat mencabut akses admin sendiri.'], 422);
        }
        $authUser = auth()->user();

        Log::info('Masuk update role', [
            'auth_id' => auth()->id(),
            'auth_email' => $authUser ? $authUser->email : null,
            'auth_role' => $authUser ? $authUser->role : null,
            'request_id' => $request->id,
            'request_role' => $request->role,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $pengguna = User::find($request->id);

        if (!$pengguna) {
            Log::warning('User target update role tidak ditemukan', [
                'request_id' => $request->id,
                'request_role' => $request->role,
            ]);

            return response()->json([
                "status" => "error",
                "message" => "User tidak ditemukan",
            ], 404);
        }

        $pengguna->role = $request->role ?? 1;
        $pengguna->save();

        return response()->json([
            "status" => "success",
            "message" => "Berhasil mengubah user",
            "data" => $pengguna,
        ]);
    }

    public function updateAdmin(Request $request, User $user)
    {
        abort_unless((int) $user->role === 1, 404);
        return $this->updateAccount($request, $user);
    }

    public function updatePersonel(Request $request, User $user)
    {
        abort_unless((int) $user->role === 3, 404);
        return $this->updateAccount($request, $user);
    }

    private function updateAccount(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        if (!empty($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $user->update($data);

        return response()->json(['status' => 'success', 'message' => 'Pengguna berhasil diperbarui.']);
    }

    public function resetPasswordOptions(Request $request)
    {
        $data = $request->validate(['role' => ['required', 'integer', 'in:1,3']]);
        return response()->json(['data' => User::where('role', $data['role'])->orderBy('name')->orderBy('id')->get(['id', 'name', 'email'])]);
    }

    public function resetPasswords(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'distinct'],
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $users = User::whereIn('id', $data['ids'])->orderBy('id')->lockForUpdate()->get();
            if ($users->count() !== count($data['ids']) || $users->contains(function ($user) {
                return !in_array((int) $user->role, [1, 3], true);
            })) {
                throw \Illuminate\Validation\ValidationException::withMessages(['ids' => 'Pilihan akun tidak valid. Muat ulang daftar pengguna.']);
            }
            foreach ($users as $user) {
                $user->update(['password' => \Illuminate\Support\Facades\Hash::make('Password123!')]);
            }

            return response()->json(['status' => 'success', 'message' => 'Password ' . $users->count() . ' akun berhasil direset ke Password123!']);
        });
    }

    public function resetPassword(User $user)
    {
        abort_unless(in_array((int) $user->role, [1, 3], true), 404);
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make('Password123!')]);

        return response()->json(['status' => 'success', 'message' => 'Password berhasil direset ke Password123!']);
    }

    public function destroyPersonel(User $user)
    {
        abort_unless((int) $user->role === 3, 404);
        return \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            $user = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            abort_unless((int) $user->role === 3, 404);
            foreach (['absensi', 'perizinan', 'perizinan_kendaraan', 'perizinan_ranpur', 'logistik', 'gudang_senjata'] as $table) {
                \Illuminate\Support\Facades\DB::table($table)->where('user_id', $user->id)->delete();
            }
            $user->tokens()->delete();
            \App\Models\KemampuanModel::where('user_id', $user->id)->delete();
            $user->delete();
            return response()->json(['status' => 'success', 'message' => 'Personel beserta seluruh riwayatnya berhasil dihapus.']);
        });
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
