<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function show(Request $request)
    {
        try {
            $role = [];
            if (isset($request->role)) {
                $role = json_decode($request->role);
            }

            $query = User::whereIn('role', $role);
            switch ($request->input('sort')) {
                case 'name_asc':
                    $query->orderBy('name', 'asc')->orderBy('id', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc')->orderBy('id', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
            }
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . trim($request->input('search')) . '%');
            }
            if ($request->has('page')) {
                $response = $query->paginate(
                    min(100, max(1, (int) $request->input('per_page', 10))),
                    ['*'], 'page', max(1, (int) $request->input('page', 1))
                );
                return response()->json([
                    'status' => 'Success',
                    'data' => $response->items(),
                    'pagination' => [
                        'current_page' => $response->currentPage(),
                        'last_page' => $response->lastPage(),
                        'total' => $response->total(),
                        'per_page' => $response->perPage(),
                        'has_more' => $response->hasMorePages(),
                    ],
                ]);
            }
            $response = $query->get();

            if ($response) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
