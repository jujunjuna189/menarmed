<?php

namespace App\Http\Controllers\Api\Artikel;

use App\Http\Controllers\Controller;
use App\Models\ArtikelModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArtikelController extends Controller
{
    /**
     * 
     */
    private function validation($request)
    {
        // Rules
        $rules['artikel'] = 'required';
        // Message
        $message['required'] = 'Harus diisi';

        $validate = Validator::make($request->all(), $rules, $message);
        return $validate;
    }

    public function show(Request $request)
    {
        try {
            $response = ArtikelModel::orderBy('created_at', 'desc')->get();

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

    /**
     * Simpan Artikel
     */
    public function store(Request $request)
    {
        try {
            if ($this->validation($request)->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => Str::length($request->artikel)], 400);
            }

            $artikel_id = $request->artikel_id;
            // Data
            $data['judul'] = $request->judul;
            $data['deskripsi'] = $request->deskripsi;
            $data['artikel'] = $request->artikel;

            $artikel = ArtikelModel::where('id', $artikel_id)->first();
            if (empty($artikel)) {
                $response = ArtikelModel::create($data);
            } else {
                $artikel->update($data);
                $response = $artikel;
            }

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
