<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\AbsensiModel;
use App\Models\GudangSenjataModel;
use App\Models\LogistikModel;
use App\Models\PerizinanKendaraanModel;
use App\Models\PerizinanModel;
use App\Models\PerizinanRanpurModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ReportController extends Controller
{
    public function absensi(Request $request)
    {
        try {
            $absensi = QueryBuilder::for(AbsensiModel::class)->orderBy('id', 'desc')->jsonPaginate(20)->appends($request->input());
            $response = [];
            foreach ($absensi as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'ket' => $val->ket ?? '-',
                    'latitude' => $val->latitude ?? '-',
                    'longitude' => $val->longitude ?? '-',
                    'created_at' => Carbon::make($val->created_at)->format('Y-M-d H:i:s'),
                    'updated_at' => Carbon::make($val->updated_at)->format('Y-M-d H:i:s'),
                ];
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

    public function perizinan()
    {
        try {
            $perizinan = PerizinanModel::orderBy('id', 'desc')->get();
            $response = [];
            foreach ($perizinan as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'keluar' => $val->keluar != null ? Carbon::make($val->keluar)->format('H:i:s') : '-',
                    'masuk' => $val->masuk != null ? Carbon::make($val->masuk)->format('H:i:s') : '-',
                    'tujuan' => $val->tujuan ?? '-',
                    'created_at' => $val->created_at != null ? Carbon::make($val->created_at)->format('Y-M-d') : '-',
                    'updated_at' => $val->updated_at != null ? Carbon::make($val->updated_at)->format('Y-M-d') : '-',
                ];
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

    public function ranpur()
    {
        try {
            $perizinan = PerizinanRanpurModel::orderBy('id', 'desc')->get();
            $response = [];
            foreach ($perizinan as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'keluar' => $val->keluar != null ? Carbon::make($val->keluar)->format('H:i:s') : '-',
                    'masuk' => $val->masuk != null ? Carbon::make($val->masuk)->format('H:i:s') : '-',
                    'tujuan' => $val->tujuan ?? '-',
                    'jenis_kendaraan' => $val->jenis_kendaraan ?? '-',
                    'created_at' => $val->created_at != null ? Carbon::make($val->created_at)->format('Y-M-d') : '-',
                    'updated_at' => $val->updated_at != null ? Carbon::make($val->updated_at)->format('Y-M-d') : '-',
                ];
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

    public function kendaraan()
    {
        try {
            $perizinan = PerizinanKendaraanModel::orderBy('id', 'desc')->get();
            $response = [];
            foreach ($perizinan as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'keluar' => $val->keluar != null ? Carbon::make($val->keluar)->format('H:i:s') : '-',
                    'masuk' => $val->masuk != null ? Carbon::make($val->masuk)->format('H:i:s') : '-',
                    'tujuan' => $val->tujuan ?? '-',
                    'jenis_kendaraan' => $val->jenis_kendaraan ?? '-',
                    'created_at' => $val->created_at != null ? Carbon::make($val->created_at)->format('Y-M-d') : '-',
                    'updated_at' => $val->updated_at != null ? Carbon::make($val->updated_at)->format('Y-M-d') : '-',
                ];
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

    public function gudang_senjata()
    {
        try {
            $gudang_senjata = GudangSenjataModel::orderBy('id', 'desc')->get();
            $response = [];
            foreach ($gudang_senjata as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'batrai_keluar' => $val->batrai_keluar ?? '-',
                    'batrai_masuk' => $val->batrai_masuk ?? '-',
                    'keluar' => $val->keluar != null ? Carbon::make($val->keluar)->format('H:i:s') : '-',
                    'masuk' => $val->masuk != null ? Carbon::make($val->masuk)->format('H:i:s') : '-',
                    'created_at' => Carbon::make($val->created_at)->format('Y-M-d'),
                    'updated_at' => Carbon::make($val->updated_at)->format('Y-M-d'),
                ];
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

    public function logistik()
    {
        try {
            $logistik = LogistikModel::orderBy('id', 'desc')->get();
            $response = [];
            foreach ($logistik as $val) {
                $response[] = [
                    'id' => $val->id,
                    'user_name' => $val->userModel->name ?? '-',
                    'keluar' => $val->keluar != null ? Carbon::make($val->keluar)->format('H:i:s') : '-',
                    'masuk' => $val->masuk != null ? Carbon::make($val->masuk)->format('H:i:s') : '-',
                    'created_at' => Carbon::make($val->created_at)->format('Y-M-d'),
                    'updated_at' => Carbon::make($val->updated_at)->format('Y-M-d'),
                ];
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
}
