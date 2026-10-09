<?php

namespace App\Http\Controllers;

use App\Services\AlarmPushService;
use Illuminate\Http\Request;

class PushTestController extends Controller
{
    public function index()
    {
        return view('push_test');
    }

    public function send(Request $request, AlarmPushService $service)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        $data = $request->validate([
            'token' => 'required|string|max:4096',
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:1000',
            'siren' => 'sometimes|boolean',
        ]);
        try {
            $service->testDevice(trim($data['token']), $data['title'], $data['body'], $request->boolean('siren'));
            return redirect()->route('push-test')->with('success', 'Pesan diterima FCM untuk dikirim ke perangkat tujuan.');
        } catch (\Throwable $error) {
            return redirect()->route('push-test')->withInput($request->only('title', 'body', 'siren'))->with('error', 'Push gagal dikirim. Periksa token perangkat, kredensial server, dan izin FCM.');
        }
    }
}
