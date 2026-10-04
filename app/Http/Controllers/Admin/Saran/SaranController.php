<?php

namespace App\Http\Controllers\Admin\Saran;

use App\Http\Controllers\Controller;
use App\Models\SaranModel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class SaranController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(['search' => 'nullable|string|max:255']);
        $search = trim((string) $request->input('search', ''));
        $pageSize = (int) $request->input('page.size', 10);
        $pageSize = in_array($pageSize, [10, 25, 50, 100], true) ? $pageSize : 10;

        $query = SaranModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('from_display', 'like', '%' . $search . '%')
                        ->orWhere('message', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc');
        $data['saran'] = QueryBuilder::for($query)
            ->paginate($pageSize, ['*'], 'page[number]', max(1, (int) $request->input('page.number', 1)))
            ->appends($request->input());
        $data['controller'] = $this;
        $data['search'] = $search;
        $data['page_size'] = $pageSize;

        return view('saran.index', $data);
    }
}
