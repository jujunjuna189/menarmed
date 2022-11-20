<?php

namespace App\Http\Controllers\Api\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\GlobalModel;
use App\Models\SliderModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    private function validation($request)
    {
        // Rules
        $rules['file'] = 'required';
        // Message
        $message['required'] = 'Harus diisi';

        $validate = Validator::make($request->all(), $rules, $message);
        return $validate;
    }

    public function show()
    {
        try {
            $response = SliderModel::dashboardSlider();

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

    public function store(Request $request)
    {
        try {
            if ($this->validation($request)->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $this->validation($request)->errors()], 400);
            }

            // Upload image to local folder
            $path = GlobalModel::upload_image($request, 'slider/dashboard');

            // Data
            $data['key'] = 1;
            $data['komentar'] = 'Slider Dashboard';
            $data['path'] = $path;

            $response = SliderModel::create($data);

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

    public function delete(Request $request)
    {
        try {
            // Rules
            $rules['slider_id'] = 'required';
            // Message
            $message['required'] = 'Harus diisi';
            $validate = Validator::make($request->all(), $rules, $message);
            if ($validate->fails()) {
                return response()->json(['status' => 'Bad Request', 'message' => $this->validation($request)->errors()], 400);
            }

            DB::beginTransaction();
            // Initialize
            $slider_id = $request->slider_id;
            // Upload image to local folder
            $slider = SliderModel::find($slider_id);
            if (!empty($slider)) {
                $originalPath = $slider->getRawOriginal('path');
                unlink($originalPath);
                $slider->delete();
            }

            if ($slider) {
                DB::commit();
                return response()->json([
                    'status' => 'Success',
                    'data' => [],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
