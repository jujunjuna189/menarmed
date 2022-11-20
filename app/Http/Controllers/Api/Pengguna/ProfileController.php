<?php

namespace App\Http\Controllers\Api\Pengguna;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private function validation($request)
    {
        // Rules
        $rules['user_id'] = 'required';
        $rules['name'] = 'required';
        $rules['gol_darah'] = 'max:2';
        // Message
        $message['required'] = 'Harus diisi';
        $message['max'] = 'Maximal 2 karakter';

        $validate = Validator::make($request->all(), $rules, $message);
        return $validate;
    }

    public function update(Request $request)
    {
        try {
            if ($this->validation($request)->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $this->validation($request)->errors()], 400);
            }

            $user_id = $request->user_id;
            // Data
            $data['name'] = $request->name;
            $data['pangkat'] = $request->pangkat;
            $data['korp'] = $request->korp;
            $data['satuan'] = $request->satuan;
            $data['tempat_lahir'] = $request->tempat_lahir;
            $data['tgl_lahir'] = $request->tgl_lahir;
            $data['agama'] = $request->agama;
            $data['gol_darah'] = $request->gol_darah;
            $data['sumber_pa'] = $request->sumber_pa;
            $data['jabatan'] = $request->jabatan;
            $data['senjata'] = $request->senjata;

            $update = User::find($user_id);

            if ($update) {
                $update->update($data);
                return response()->json([
                    'status' => 'Success',
                    'data' => [$update],
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
