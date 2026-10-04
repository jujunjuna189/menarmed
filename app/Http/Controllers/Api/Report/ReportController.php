<?php

namespace App\Http\Controllers\Api\Report;

use App\Http\Controllers\Controller;
use App\Models\AbsensiModel;
use App\Models\GudangSenjataModel;
use App\Models\LogistikModel;
use App\Models\PerizinanKendaraanModel;
use App\Models\PerizinanModel;
use App\Models\PerizinanRanpurModel;
use App\Models\SaranModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private function reportQuery(string $model, Request $request, ?int $defaultLimit = null)
    {
        $query = $model::orderBy('id', 'desc');
        if ($request->filled('user_id') && $model !== SaranModel::class) {
            $request->validate(['user_id' => 'required|integer|min:1']);
            $query->where('user_id', $request->input('user_id'));
        }
        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            if ($model === SaranModel::class) {
                $query->where('from_display', 'like', '%' . $search . '%');
            } else {
                $query->whereHas('userModel', function ($users) use ($search) {
                    $users->where('name', 'like', '%' . $search . '%');
                });
            }
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }
        if ($request->has('page')) {
            $page = max(1, (int) $request->input('page', 1));
            $perPage = min(100, max(1, (int) $request->input('per_page', 10)));
            return $query->paginate($perPage, ['*'], 'page', $page);
        } elseif ($defaultLimit !== null) {
            $query->take($defaultLimit);
        }

        return $query->get();
    }

    public function absensi(Request $request)
    {
        try {
            $absensi = $this->reportQuery(AbsensiModel::class, $request, 20);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $absensi->currentPage(),
                        'last_page' => $absensi->lastPage(),
                        'per_page' => $absensi->perPage(),
                        'total' => $absensi->total(),
                        'has_more' => $absensi->hasMorePages(),
                    ] : null,
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

    public function perizinan(Request $request)
    {
        try {
            $perizinan = $this->reportQuery(PerizinanModel::class, $request);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $perizinan->currentPage(),
                        'last_page' => $perizinan->lastPage(),
                        'per_page' => $perizinan->perPage(),
                        'total' => $perizinan->total(),
                        'has_more' => $perizinan->hasMorePages(),
                    ] : null,
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

    public function ranpur(Request $request)
    {
        try {
            $perizinan = $this->reportQuery(PerizinanRanpurModel::class, $request);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $perizinan->currentPage(),
                        'last_page' => $perizinan->lastPage(),
                        'per_page' => $perizinan->perPage(),
                        'total' => $perizinan->total(),
                        'has_more' => $perizinan->hasMorePages(),
                    ] : null,
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

    public function kendaraan(Request $request)
    {
        try {
            $perizinan = $this->reportQuery(PerizinanKendaraanModel::class, $request);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $perizinan->currentPage(),
                        'last_page' => $perizinan->lastPage(),
                        'per_page' => $perizinan->perPage(),
                        'total' => $perizinan->total(),
                        'has_more' => $perizinan->hasMorePages(),
                    ] : null,
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

    public function gudang_senjata(Request $request)
    {
        try {
            $gudang_senjata = $this->reportQuery(GudangSenjataModel::class, $request);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $gudang_senjata->currentPage(),
                        'last_page' => $gudang_senjata->lastPage(),
                        'per_page' => $gudang_senjata->perPage(),
                        'total' => $gudang_senjata->total(),
                        'has_more' => $gudang_senjata->hasMorePages(),
                    ] : null,
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

    public function logistik(Request $request)
    {
        try {
            $logistik = $this->reportQuery(LogistikModel::class, $request);
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

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $logistik->currentPage(),
                        'last_page' => $logistik->lastPage(),
                        'per_page' => $logistik->perPage(),
                        'total' => $logistik->total(),
                        'has_more' => $logistik->hasMorePages(),
                    ] : null,
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

    public function saran(Request $request)
    {
        try {
            $saran = $this->reportQuery(SaranModel::class, $request);
            $response = [];
            foreach ($saran as $val) {
                $response[] = [
                    'from_display' => $val->from_display ?? '-',
                    'id' => $val->id,
                    'message' => $val->message ?? '-',
                    'created_at' => Carbon::make($val->created_at)->format('Y-M-d H:i:s'),
                    'updated_at' => Carbon::make($val->updated_at)->format('Y-M-d H:i:s'),
                ];
            }

            if ($response || $request->has('page')) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $response,
                    'pagination' => $request->has('page') ? [
                        'current_page' => $saran->currentPage(),
                        'last_page' => $saran->lastPage(),
                        'per_page' => $saran->perPage(),
                        'total' => $saran->total(),
                        'has_more' => $saran->hasMorePages(),
                    ] : null,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Data kosong',
                    'data' => [],
                ], 404);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
