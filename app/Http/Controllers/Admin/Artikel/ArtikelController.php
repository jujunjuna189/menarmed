<?php

namespace App\Http\Controllers\Admin\Artikel;

use App\Http\Controllers\Controller;
use App\Models\ArtikelModel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index()
    {
        $data['artikel'] = ArtikelModel::get();
        $data['no'] = 1;
        return view('artikel.index', $data);
    }

    public function view(Request $request)
    {
        $artikel = ArtikelModel::find($request->artikel_id);

        $data['artikel'] = $artikel;

        if (empty($artikel)) {
            return view('error.404');
        } else {
            return view('artikel.view', $data);
        }
    }

    public function create(Request $request)
    {
        $data['artikel_id'] = $request->artikel_id;

        return view('artikel.form.create', $data);
    }
}
