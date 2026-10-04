<?php

namespace App\Http\Controllers\Admin\ELearning;

use App\Http\Controllers\Controller;
use App\Models\ELearningModel;
use Illuminate\Http\Request;

class ELearningController extends Controller
{
    public function update(Request $request, ELearningModel $learning)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
            'path' => ['required', 'url', 'regex:/^https?:\/\//i', 'max:255'],
        ]);
        $learning->update($data);

        return response()->json(['status' => 'Success', 'data' => $learning]);
    }

    public function destroy(ELearningModel $learning)
    {
        $learning->delete();

        return response()->json(['status' => 'Success']);
    }

    public function index(Request $request)
    {
        $pageSize = (int) $request->input('page.size', 10);
        $pageSize = in_array($pageSize, [10, 25, 50, 100], true) ? $pageSize : 10;
        $search = trim((string) $request->input('search'));

        $query = ELearningModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $search . '%')
                        ->orWhere('path', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('id', 'desc');

        $data['learning'] = $query
            ->paginate($pageSize, ['*'], 'page[number]', max(1, (int) $request->input('page.number', 1)))
            ->appends($request->input());
        $data['controller'] = $this;
        $data['page_size'] = $pageSize;
        $data['search'] = $search;

        return view('e-learning.index', $data);
    }
}
