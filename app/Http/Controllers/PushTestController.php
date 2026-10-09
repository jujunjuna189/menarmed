<?php

namespace App\Http\Controllers;

use App\Services\AlarmPushService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PushTestController extends Controller
{
    public function index()
    {
        $recipients = DB::table('users')->join('push_devices', 'users.id', '=', 'push_devices.user_id')
            ->select('users.id', 'users.name', 'users.email', DB::raw('COUNT(push_devices.id) as device_count'))
            ->groupBy('users.id', 'users.name', 'users.email')->orderBy('users.name')->get();
        return view('push_test', compact('recipients'));
    }

    public function send(Request $request, AlarmPushService $service)
    {
        abort_unless((int) $request->user()->role === 1, 403);
        $data = $request->validate([
            'recipient_id' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'all' && (!is_scalar($value) || !ctype_digit((string) $value) || !DB::table('users')->where('id', $value)->exists())) {
                    $fail('Penerima tidak valid.');
                }
            }],
            'title' => 'required|string|max:120',
            'body' => 'required|string|max:1000',
            'siren' => 'sometimes|boolean',
        ]);
        if ($data['recipient_id'] === 'all') {
            try {
                $service->broadcast($data['title'], $data['body'], $request->boolean('siren'));
                return redirect()->route('push-test')->with('success', 'Pesan diterima FCM untuk dikirim ke semua aplikasi yang mengikuti notifikasi umum.');
            } catch (\Throwable $error) {
                $this->logPushFailure($error, 'topic');
                return redirect()->route('push-test')->withInput($request->only('recipient_id', 'title', 'body', 'siren'))
                    ->with('error', 'Pengiriman ke semua aplikasi gagal. Periksa koneksi dan konfigurasi Firebase server.');
            }
        }
        $tokens = DB::table('push_devices')->where('user_id', $data['recipient_id'])->pluck('token')->unique();
        if ($tokens->isEmpty()) {
            throw ValidationException::withMessages(['recipient_id' => 'Penerima belum memiliki perangkat terdaftar. Minta penerima login di aplikasi mobile.']);
        }
        $sent = 0;
        foreach ($tokens as $token) {
            try {
                $service->testDevice($token, $data['title'], $data['body'], $request->boolean('siren'));
                $sent++;
            } catch (\Throwable $error) {
                $this->logPushFailure($error, 'device');
                // Continue so one unavailable device does not prevent the others from receiving the message.
            }
        }
        $response = redirect()->route('push-test');
        if ($sent === $tokens->count()) {
            return $response->with('success', "Pesan diterima FCM untuk dikirim ke {$sent} perangkat penerima.");
        }
        return $response->withInput($request->only('recipient_id', 'title', 'body', 'siren'))
            ->with('error', "FCM menerima pesan untuk {$sent} dari {$tokens->count()} perangkat. Periksa koneksi server, kredensial Firebase, atau minta penerima membuka kembali aplikasi. Mengirim ulang dapat membuat notifikasi ganda pada perangkat yang sudah berhasil.");
    }
    private function logPushFailure(\Throwable $error, string $target): void
    {
        $context = ['target' => $target, 'exception' => get_class($error)];
        if ($error instanceof \Illuminate\Http\Client\RequestException) {
            $context['http_status'] = $error->response->status();
            $status = $error->response->json('error.status');
            if (is_string($status) && preg_match('/^[A-Z_]{1,80}$/', $status)) {
                $context['firebase_status'] = $status;
            }
        }
        Log::warning('Firebase push failed', $context);
    }
}

