<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\KemampuanModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KemampuanController extends Controller
{
    /**
     * Function validasi
     */
    private function validation($request)
    {
        // Rules
        $rules['user_id'] = 'required';
        // Message
        $message['required'] = 'Harus diisi';

        $validate = Validator::make($request->all(), $rules, $message);
        return $validate;
    }

    public function show(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $response = KemampuanModel::where('user_id', $user_id)->first();

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

    /**
     * Simpan Kemampuan Pengguna
     */
    public function store(Request $request)
    {
        try {
            if ($this->validation($request)->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $this->validation($request)->errors()], 400);
            }

            $user_id = $request->user_id;
            // Data
            $data['user_id'] = $user_id;
            $data['lari'] = $request->lari;
            $data['renang'] = $request->renang;
            $data['jatri'] = $request->jatri;
            $data['jatrat'] = $request->jatrat;
            $data['pistol'] = $request->pistol;
            $data['push_up'] = $request->push_up;
            $data['sit_up'] = $request->sit_up;
            $data['pull_up'] = $request->pull_up;
            $data['shutle_run'] = $request->shutle_run;

            $kemampuan = KemampuanModel::where('user_id', $user_id)->first();
            if (empty($kemampuan)) {
                $response = KemampuanModel::create($data);
            } else {
                $response = $kemampuan->update($data);
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
