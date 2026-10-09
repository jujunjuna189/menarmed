<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class AlarmPushService
{
    private function token(array $credentials): string
    {
        return Cache::remember('firebase_alarm_oauth_' . $credentials['client_email'], 3000, function () use ($credentials) {
            $encode = function ($value) { return rtrim(strtr(base64_encode($value), '+/', '-_'), '='); };
            $now = time();
            $jwt = $encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])) . '.' . $encode(json_encode([
                'iss' => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/firebase.messaging https://www.googleapis.com/auth/firebase.database https://www.googleapis.com/auth/userinfo.email',
                'aud' => 'https://oauth2.googleapis.com/token', 'iat' => $now, 'exp' => $now + 3600,
            ]));
            if (!openssl_sign($jwt, $signature, $credentials['private_key'], OPENSSL_ALGO_SHA256)) {
                throw new RuntimeException('Firebase signing failed');
            }
            return Http::asForm()->timeout(15)->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $jwt . '.' . $encode($signature),
            ])->throw()->json('access_token');
        });
    }

    public function status(): array
    {
        $credentials = json_decode(file_get_contents(config('firebase.credentials')), true, 512, JSON_THROW_ON_ERROR);
        if (($credentials['project_id'] ?? '') !== 'menarmed-708d2') throw new RuntimeException('Firebase project mismatch');
        return Http::withToken($this->token($credentials))->timeout(15)
            ->get(rtrim(config('firebase.database_url'), '/') . '/alarm/demo.json')->throw()->json() ?? [];
    }

    public function send(bool $status, int $code, bool $siren = true): bool
    {
        $credentials = json_decode(file_get_contents(config('firebase.credentials')), true, 512, JSON_THROW_ON_ERROR);
        if (($credentials['project_id'] ?? '') !== 'menarmed-708d2') throw new RuntimeException('Firebase project mismatch');
        $token = $this->token($credentials);
        $names = ['Awan Jingga', 'Awan Kuning', 'Awan Biru', 'Angin Gunung', 'Angin Puyuh'];
        $levels = ['Siaga Tingkat I', 'Siaga Tingkat II', 'Siaga Tingkat III', 'Pencabutan Siaga', 'Siap Digerakan Sewaktu Waktu'];
        Http::withToken($token)->timeout(15)->put(rtrim(config('firebase.database_url'), '/') . '/alarm/demo.json', [
            'status' => $status, 'alarm' => $names[$code], 'code' => $code,
            'siren' => $siren,
            'started_at' => $status ? (int) round(microtime(true) * 1000) : null,
        ])->throw();
        if (!$status) return true;
        try {
        Http::withToken($token)->timeout(15)->post(
            'https://fcm.googleapis.com/v1/projects/' . $credentials['project_id'] . '/messages:send',
            ['message' => [
                'topic' => 'stelling_alarm',
                'notification' => ['title' => $names[$code], 'body' => $levels[$code]],
                'data' => ['type' => 'stelling_alarm', 'code' => (string) $code, 'siren' => $siren ? '1' : '0'],
                'android' => [
                    'priority' => 'high', 'ttl' => '60s',
                    'notification' => ['channel_id' => $siren ? 'stelling_siren_v1' : 'menarmed_messages_v1', 'sound' => $siren ? 'alarm' : 'default', 'tag' => 'stelling_alarm'],
                ],
            ]]
        )->throw();
            return true;
        } catch (\Throwable $error) {
            // The realtime alarm is already active: let the operator stop it.
            return false;
        }
    }

    public function broadcast(string $title, string $body, bool $siren = false): void
    {
        $this->sendMessage(['topic' => 'menarmed_all'], $title, $body, $siren);
    }

    public function testDevice(string $deviceToken, string $title, string $body, bool $siren = false): void
    {
        $this->sendMessage(['token' => $deviceToken], $title, $body, $siren);
    }

    private function sendMessage(array $target, string $title, string $body, bool $siren): void
    {
        $credentials = json_decode(file_get_contents(config('firebase.credentials')), true, 512, JSON_THROW_ON_ERROR);
        if (($credentials['project_id'] ?? '') !== 'menarmed-708d2') throw new RuntimeException('Firebase project mismatch');
        Http::withToken($this->token($credentials))->timeout(15)->post(
            'https://fcm.googleapis.com/v1/projects/' . $credentials['project_id'] . '/messages:send',
            ['message' => array_merge($target, [
                'notification' => ['title' => $title, 'body' => $body],
                'data' => ['type' => 'push_message', 'title' => $title, 'body' => $body, 'siren' => $siren ? '1' : '0'],
                'android' => [
                    'priority' => 'high', 'ttl' => '60s',
                    'notification' => ['channel_id' => $siren ? 'stelling_siren_v1' : 'menarmed_messages_v1', 'sound' => $siren ? 'alarm' : 'default'],
                ],
            ])]
        )->throw();
    }
}
