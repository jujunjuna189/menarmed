<?php

namespace App\Http\Controllers\Admin\Artikel;

use App\Http\Controllers\Controller;
use App\Models\ArtikelModel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = (int) $request->input('page.size', 10);
        $pageSize = in_array($pageSize, [10, 25, 50, 100], true) ? $pageSize : 10;
        $search = trim((string) $request->input('search'));

        $query = ArtikelModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('artikel', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc');

        $data['artikel'] = $query
            ->paginate($pageSize, ['*'], 'page[number]', max(1, (int) $request->input('page.number', 1)))
            ->appends($request->input());
        $data['controller'] = $this;
        $data['page_size'] = $pageSize;
        $data['search'] = $search;

        return view('artikel.index', $data);
    }

    public function view(Request $request)
    {
        $artikel = ArtikelModel::findOrFail($request->artikel_id);

        $data['artikel'] = $artikel;

        return view('artikel.view', $data);
    }

    public function create(Request $request)
    {
        $data['artikel_id'] = $request->artikel_id;
        $data['artikel'] = $request->filled('artikel_id')
            ? ArtikelModel::findOrFail($request->artikel_id) : null;

        return view('artikel.form.create', $data);
    }

    public function destroy(Request $request, ArtikelModel $artikel)
    {
        $artikel->delete();

        return redirect()->route('artikel', $request->only(['search', 'page']))
            ->with('success', 'Artikel berhasil dihapus.');
    }
}
