<?php

namespace Tests\Feature;

use App\Services\AlarmPushService;
use App\Http\Controllers\PushTestController;
use Illuminate\Http\Request;
use Tests\TestCase;

class PushTestTest extends TestCase
{
    public function test_siren_selection_is_forwarded(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('testDevice')->once()->with('device-token', 'Siaga', 'Pesan siaga', true);
        $service->shouldNotReceive('send');
        $request = Request::create('/', 'POST', ['token' => 'device-token', 'title' => 'Siaga', 'body' => 'Pesan siaga', 'siren' => '1']);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $this->assertSame(302, (new PushTestController())->send($request, $service)->getStatusCode());
    }

    public function test_empty_message_is_rejected_before_sending(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('testDevice');
        $request = Request::create('/', 'POST', ['token' => 'device-token', 'title' => 'Judul']);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new PushTestController())->send($request, $service);
    }

    public function test_admin_test_targets_only_requested_device(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('testDevice')->once()->with('device-token', 'Pengumuman', 'Apel pukul 07.00', false);
        $service->shouldNotReceive('send');
        $request = Request::create('/', 'POST', ['token' => 'device-token', 'title' => 'Pengumuman', 'body' => 'Apel pukul 07.00']);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new PushTestController())->send($request, $service);
        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(route('push-test'), $response->getTargetUrl());
    }
}
