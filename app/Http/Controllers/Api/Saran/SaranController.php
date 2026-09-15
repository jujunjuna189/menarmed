<?php

namespace App\Http\Controllers\Api\Saran;

use App\Http\Controllers\Controller;
use App\Models\SaranModel;
use Exception;
use Illuminate\Http\Request;

class SaranController extends Controller
{
    public function store(Request $request)
    {
        try {
            $model = new SaranModel();
            $model->fill($request->except('id'));
            $model->save();

            if ($model) {
                return response()->json([
                    'status' => 'Success',
                    'data' => [$model],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
