<?php

namespace App\Http\Controllers\Api\Pejabat;

use App\Http\Controllers\Controller;
use App\Models\KostradModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KostradController extends Controller
{
    /**
     * Function validasi
     */
    private function validation($request, $rule)
    {
        $rule = [
            'store' => $rule['store'] ?? false,
            'update' => $rule['update'] ?? false,
            'delete' => $rule['delete'] ?? false,
        ];

        // Rules
        if ($rule['store'] || $rule['update']) {
            $rules['nama'] = 'required';
            $rules['nrp'] = 'required';
        }

        if ($rule['update'] || $rule['delete']) {
            $rules['id'] = 'required';
        }
        // Message
        $message['required'] = 'Harus diisi';

        $validate = Validator::make($request->all(), $rules, $message);
        return $validate;
    }

    public function show()
    {
        try {
            $response = KostradModel::orderBy('created_at', 'desc')->get();
            foreach ($response as $val) {
                $val['lates'] = !empty($val->created_at) && Carbon::parse($val->created_at)->addDays(2) > Carbon::now() ? 'Baru' : null;
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    /**
     * Simpan Pejabat Armed
     */
    public function store(Request $request)
    {
        try {
            $validate = $this->validation($request, ['store' => true]);
            if ($validate->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $validate->errors()], 400);
            }

            // Data
            $data['nama'] = $request->nama;
            $data['pangkat'] = $request->pangkat;
            $data['nrp'] = $request->nrp;
            $data['jabatan'] = $request->jabatan;

            $response = KostradModel::create($data);

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
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    /**
     * Update Pejabat Kostrad
     */
    public function update(Request $request)
    {
        try {
            $validate = $this->validation($request, ['update' => true]);
            if ($validate->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $validate->errors()], 400);
            }

            // Initialize
            $kostrad_id = $request->id;
            // Data
            $data['nama'] = $request->nama;
            $data['pangkat'] = $request->pangkat;
            $data['nrp'] = $request->nrp;
            $data['jabatan'] = $request->jabatan;

            $response = KostradModel::find($kostrad_id);

            if (!empty($response)) {
                $response->update($data);
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
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    /**
     * Delete Pejabat Kostrad
     */
    public function delete(Request $request)
    {
        try {
            $validate = $this->validation($request, ['delete' => true]);
            if ($validate->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $validate->errors()], 400);
            }

            // Initialize
            $kostrad_id = $request->id;
            // find data
            $response = KostradModel::find($kostrad_id);

            if (!empty($response)) {
                $response->delete();
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
        } catch (\Exception $th) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
