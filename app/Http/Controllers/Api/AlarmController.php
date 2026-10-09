<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlarmPushService;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    public function status(Request $request, AlarmPushService $alarms)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        try {
            return response()->json($alarms->status());
        } catch (\Throwable $error) {
            return response()->json(['message' => 'Status alarm gagal dimuat. Coba lagi.'], 502);
        }
    }

    public function store(Request $request, AlarmPushService $alarms)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        $data = $request->validate(['status' => 'required|boolean', 'code' => 'required|integer|between:0,4', 'siren' => 'sometimes|boolean']);
        try {
            $pushSent = $alarms->send((bool) $data['status'], (int) $data['code'], $request->boolean('siren', true));
            return response()->json(['status' => 'Success', 'push_sent' => $pushSent]);
        } catch (\Throwable $error) {
            // Do not expose credentials, OAuth assertions or upstream response bodies.
            return response()->json(['message' => 'Pengiriman alarm gagal. Periksa konfigurasi Firebase server.'], 502);
        }
    }
}
