<?php

namespace App\Http\Controllers\Api\Artikel;

use App\Http\Controllers\Controller;
use App\Models\ArtikelModel;
use App\Support\ArtikelContent;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ArtikelController extends Controller
{
    public function show(Request $request)
    {
        $articles = ArtikelModel::orderBy('created_at', 'desc')->get();
        $articles->each(function ($article) {
            $article->artikel = json_encode(ArtikelContent::sanitize($article->artikel), JSON_UNESCAPED_UNICODE);
        });

        return response()->json(['status' => 'Success', 'data' => $articles]);
    }

    public function store(Request $request)
    {
        abort_unless($request->user() && (int) $request->user()->role === 1, 403);

        $data = $request->validate([
            'artikel_id' => ['nullable', 'integer', 'min:1'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'artikel' => ['required', 'string'],
        ]);
        $article = !empty($data['artikel_id'])
            ? ArtikelModel::findOrFail($data['artikel_id']) : new ArtikelModel();
        $html = ArtikelContent::sanitize($data['artikel']);
        if (ArtikelContent::isEmpty($html)) {
            throw ValidationException::withMessages(['artikel' => 'Isi artikel harus diisi.']);
        }

        $article->fill([
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'artikel' => json_encode($html, JSON_UNESCAPED_UNICODE),
        ]);
        $article->save();

        return response()->json(['status' => 'Success', 'data' => $article]);
    }
}
