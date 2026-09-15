<?php

namespace App\Http\Controllers\Api\GudangSenjata;

use App\Http\Controllers\Controller;
use App\Models\GudangSenjataModel;
use App\Models\QrCodeModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

use function PHPSTORM_META\map;

class GudangSenjataController extends Controller
{
    public function show()
    {
    }

    public function store(Request $request)
    {
        try {
            $qrcode = $request->qrcode;

            $data['user_id'] = $request->user_id;
            if (isset($request->keluar) || $request->keluar != "") {
                $data['batrai_keluar'] = $request->batrai_keluar;
                $data['keluar'] = $request->keluar;
            }
            if (isset($request->masuk) || $request->masuk != "") {
                $data['batrai_masuk'] = $request->batrai_masuk;
                $data['masuk'] = $request->masuk;
            }

            $where['code'] = $qrcode;
            $codeLength = QrCodeModel::where($where)->count();
            if ($codeLength > 0) {
                $wherePer['user_id'] = $data['user_id'];
                $checkData = GudangSenjataModel::where($wherePer)->whereDate('created_at', Carbon::now())->get();
                if ($checkData->count() > 0) {
                    GudangSenjataModel::where($wherePer)->whereDate('created_at', Carbon::now())->update($data);
                    $data['batrai_masuk'] = isset($request->batrai_masuk) || $request->batrai_masuk != "" ? $data['batrai_masuk'] : $checkData[0]->batrai_masuk;
                    $data['batrai_keluar'] = isset($request->batrai_keluar) || $request->batrai_keluar != "" ? $data['batrai_keluar'] : $checkData[0]->batrai_keluar;
                    $data['keluar'] = isset($request->keluar) || $request->keluar != "" ? $data['keluar'] : $checkData[0]->keluar;
                    $data['masuk'] = isset($request->masuk) || $request->masuk != "" ? $data['masuk'] : $checkData[0]->masuk;
                    $data['created_at'] = Carbon::parse($checkData[0]->created_at)->format('Y-m-d H:i:s'); //add created
                    $data['updated_at'] = Carbon::parse($checkData[0]->updated_at)->format('Y-m-d H:i:s'); //add updated
                    $response = $data;
                } else {
                    $response = GudangSenjataModel::create($data);
                }
            } else {
                $response = false;
            }

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
}
