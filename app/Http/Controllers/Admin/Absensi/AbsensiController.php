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
}
