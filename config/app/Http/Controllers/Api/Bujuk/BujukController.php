<?php

namespace App\Http\Controllers\Api\Bujuk;

use App\Http\Controllers\Controller;
use App\Models\BujukModel;
use Exception;
use Illuminate\Http\Request;

class BujukController extends Controller
{
    public function show()
    {
        try {
            $response = BujukModel::orderBy('created_at', 'desc')->get();

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
