<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushDeviceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'installation_id' => ['required', 'string', 'size:64', 'regex:/^[a-f0-9]+$/'],
            'token' => ['required', 'string', 'max:4096'],
        ]);
        $hash = hash('sha256', $data['token']);
        DB::transaction(function () use ($request, $data, $hash) {
            DB::table('push_devices')->where('token_hash', $hash)
                ->where('installation_id', '!=', $data['installation_id'])->delete();
            $existing = DB::table('push_devices')->where('installation_id', $data['installation_id'])->first();
            DB::table('push_devices')->updateOrInsert(
                ['installation_id' => $data['installation_id']],
                ['user_id' => $request->user()->id, 'token' => $data['token'],
                    'token_hash' => $hash, 'created_at' => $existing ? $existing->created_at : now(),
                    'updated_at' => now()]
            );
        });
        return response()->json(['message' => 'Token FCM tersimpan.']);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate(['installation_id' => ['required', 'string', 'size:64']]);
        DB::table('push_devices')->where('user_id', $request->user()->id)
            ->where('installation_id', $data['installation_id'])->delete();
        return response()->noContent();
    }
}
