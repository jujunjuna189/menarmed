<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AlarmPushService;
use Illuminate\Http\Request;

class AlarmController extends Controller
{
    public function store(Request $request, AlarmPushService $alarms)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        $data = $request->validate(['status' => 'required|boolean', 'code' => 'required|integer|between:0,4']);
        try {
            $pushSent = $alarms->send((bool) $data['status'], (int) $data['code']);
            return response()->json(['status' => 'Success', 'push_sent' => $pushSent]);
        } catch (\Throwable $error) {
            // Do not expose credentials, OAuth assertions or upstream response bodies.
            return response()->json(['message' => 'Pengiriman alarm gagal. Periksa konfigurasi Firebase server.'], 502);
        }
    }
}
