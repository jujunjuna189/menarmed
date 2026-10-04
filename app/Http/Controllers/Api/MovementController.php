<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QrCodeModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DomainException;

abstract class MovementController extends Controller
{
    protected $movementModel;
    protected $movementFields = [];

    private function activeQuery($userId)
    {
        return $this->movementModel::where('user_id', $userId)
            ->whereNotNull('keluar')->where('keluar', '!=', '')
            ->where(function ($query) {
                $query->whereNull('masuk')->orWhere('masuk', '');
            })->orderBy('id', 'desc');
    }

    public function show(Request $request)
    {
        $request->validate(['user_id' => 'required|integer|min:1']);
        return response()->json(['status' => 'Success',
            'data' => $this->activeQuery($request->user_id)->limit(1)->get()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|min:1',
            'qrcode' => 'required|string',
            'keluar' => 'nullable|date',
            'masuk' => 'nullable|date',
        ]);
        if ($request->filled('keluar') === $request->filled('masuk')) {
            return response()->json(['status' => 'Failed', 'message' => 'Pilih satu aksi keluar atau masuk', 'data' => []], 422);
        }
        if (!QrCodeModel::where('code', $request->qrcode)->exists()) {
            return response()->json(['status' => 'Failed', 'message' => 'Kode tidak sesuai', 'data' => []], 422);
        }
        try {
            $record = DB::transaction(function () use ($request) {
                // Serialize actions for a person, including the first transaction.
                if (!User::whereKey($request->user_id)->lockForUpdate()->first()) {
                    throw new DomainException('Personil tidak ditemukan');
                }
                $active = $this->activeQuery($request->user_id)->lockForUpdate()->first();
                if ($request->filled('keluar')) {
                    if ($active) throw new DomainException('Masih ada aktivitas yang belum selesai');
                    $data = ['user_id' => $request->user_id, 'keluar' => now()];
                    foreach ($this->movementFields as $field) {
                        if ($request->filled($field)) $data[$field] = $request->input($field);
                    }
                    return $this->movementModel::create($data)->fresh();
                }
                if (!$active) throw new DomainException('Belum ada aktivitas keluar atau pengambilan yang dapat diselesaikan');
                $data = ['masuk' => now()];
                if (in_array('batrai_masuk', $this->movementFields) && $request->filled('batrai_masuk')) {
                    $data['batrai_masuk'] = $request->batrai_masuk;
                }
                $active->update($data);
                return $active->fresh();
            });
            return response()->json(['status' => 'Success', 'data' => [$record]]);
        } catch (DomainException $error) {
            return response()->json(['status' => 'Failed', 'message' => $error->getMessage(), 'data' => []], 409);
        }
    }
}
