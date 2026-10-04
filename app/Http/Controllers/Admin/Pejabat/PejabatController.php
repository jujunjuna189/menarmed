<?php

namespace App\Http\Controllers\Admin\Pejabat;

use App\Http\Controllers\Controller;
use App\Models\ArmedModel;
use App\Models\KostradModel;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = in_array($request->tab, ['armed', 'kostrad'], true)
            ? $request->tab
            : 'armed';

        $search = trim((string) $request->input('search', ''));
        $sort = $request->input('sort', '-created_at');
        $sort = in_array($sort, ['nama', '-nama', 'nrp', '-nrp', '-created_at', 'created_at'], true) ? $sort : '-created_at';
        $query = function ($model) use ($search, $sort) {
            return $model::query()->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    foreach (['nama', 'pangkat', 'nrp', 'jabatan'] as $field) {
                        $query->orWhere($field, 'like', '%' . $search . '%');
                    }
                });
            })->orderBy(ltrim($sort, '-'), strpos($sort, '-') === 0 ? 'desc' : 'asc')->orderBy('id');
        };

        return view('pejabat.index', [
            'active_tab' => $activeTab,
            'search' => $search,
            'sort' => $sort,
            'armed' => $query(ArmedModel::class)->paginate(10, ['*'], 'armed_page')->appends($request->only(['search', 'sort'])),
            'kostrad' => $query(KostradModel::class)->paginate(10, ['*'], 'kostrad_page')->appends($request->only(['search', 'sort'])),
        ]);
    }
}
