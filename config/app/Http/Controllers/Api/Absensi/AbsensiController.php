<?php

namespace App\Http\Controllers\Api\Absensi;

use App\Http\Controllers\Controller;
use App\Models\AbsensiModel;
use App\Models\QrCodeModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{

    public function show()
    {
    }

    public function showTodayPresence()
    {
        try {
            $response['today_presence'] = AbsensiModel::whereDate('created_at', Carbon::now())->get();

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
            $qrcode = $request->qrcode;

            $data['user_id'] = $request->user_id;
            $data['ket'] = $request->ket;
            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;

            // Check qrcode
            $where['code'] = $qrcode;
            $codeLength = QrCodeModel::where($where)->count();
            if ($codeLength > 0) {
                // Check curren day exist or not
                $whereAb['user_id'] = $data['user_id'];
                $checkData = AbsensiModel::where($whereAb)->whereDate('created_at', Carbon::now())->get();
                if ($checkData->count() > 0) {
                    // If data exist then data update
                    AbsensiModel::where($whereAb)->whereDate('created_at', Carbon::now())->update($data); //Update
                    $data['created_at'] = Carbon::parse($checkData[0]->created_at)->format('Y-m-d H:i:s'); //add created
                    $data['updated_at'] = Carbon::parse($checkData[0]->updated_at)->format('Y-m-d H:i:s'); //add updated
                    $response = $data;
                } else {
                    //Else data not exist create new data
                    $response = AbsensiModel::create($data);
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
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    public function update()
    {
    }

    public function delete()
    {
    }
}
