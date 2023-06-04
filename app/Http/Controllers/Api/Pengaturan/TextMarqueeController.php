<?php

namespace App\Http\Controllers\Api\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\TextMarqueeModel;
use Exception;
use Illuminate\Http\Request;

class TextMarqueeController extends Controller
{
    // Show
    public function show()
    {
        try {
            $response = TextMarqueeModel::first();

            if ($response) {
                return response()->json([
                    'status' => 'Success',
                    'data' => [$response],
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

    // Store
    public function store(Request $request)
    {
        try {
            // Data
            $response = TextMarqueeModel::first();
            $response->text = $request->text;
            $response->save();

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
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
