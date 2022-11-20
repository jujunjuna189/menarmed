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
                $column['aksi'] = false;
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
        $data['role'] = $pengguna[0]->roleModel;
        $data['table'] = $this->tableSetting($role_key);
        $data['no'] = 1;

        return view('pengguna.index', $data);
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
}
