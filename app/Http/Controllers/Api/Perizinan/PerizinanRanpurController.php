<?php

namespace App\Http\Controllers\Api\Perizinan;

use App\Http\Controllers\Controller;
use App\Models\PerizinanRanpurModel;
use App\Models\QrCodeModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class PerizinanRanpurController extends Controller
{
    public function store(Request $request)
    {
        try {
            $qrcode = $request->qrcode;

            $data['user_id'] = $request->user_id;
            if (isset($request->keluar) || $request->keluar != "") {
                $data['keluar'] = $request->keluar;
            }
            if (isset($request->masuk) || $request->masuk != "") {
                $data['masuk'] = $request->masuk;
            }
            if (isset($request->tujuan) || $request->tujuan != "") {
                $data['tujuan'] = $request->tujuan;
            }

            if (isset($request->jenis_kendaraan) || $request->jenis_kendaraan != "") {
                $data['jenis_kendaraan'] = $request->jenis_kendaraan;
            }

            $where['code'] = $qrcode;
            $codeLength = QrCodeModel::where($where)->count();
            if ($codeLength > 0) {
                $wherePer['user_id'] = $data['user_id'];
                $checkData = PerizinanRanpurModel::where($wherePer)->whereDate('created_at', Carbon::now())->get();
                if ($checkData->count() > 0) {
                    PerizinanRanpurModel::where($wherePer)->whereDate('created_at', Carbon::now())->update($data);
                    $data['keluar'] = isset($request->keluar) || $request->keluar != "" ? $data['keluar'] : $checkData[0]->keluar;
                    $data['masuk'] = isset($request->masuk) || $request->masuk != "" ? $data['masuk'] : $checkData[0]->masuk;
                    $data['tujuan'] = isset($request->tujuan) || $request->tujuan != "" ? $data['tujuan'] : $checkData[0]->tujuan;
                    $data['created_at'] = Carbon::parse($checkData[0]->created_at)->format('Y-m-d H:i:s'); //add created
                    $data['updated_at'] = Carbon::parse($checkData[0]->updated_at)->format('Y-m-d H:i:s'); //add updated
                    $response = $data;
                } else {
                    $response = PerizinanRanpurModel::create($data);
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
