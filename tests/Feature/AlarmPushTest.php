<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\AlarmController;
use App\Services\AlarmPushService;
use Illuminate\Http\Request;
use Tests\TestCase;

class AlarmPushTest extends TestCase
{
    public function test_admin_can_send_alarm_without_real_network_requests(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('send')->once()->with(true, 2)->andReturn(true);
        $request = Request::create('/', 'POST', ['status' => true, 'code' => 2]);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new AlarmController())->store($request, $service);
        $this->assertSame(200, $response->getStatusCode());
    }

    public function test_non_admin_cannot_send_alarm(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('send');
        $request = Request::create('/', 'POST', ['status' => true, 'code' => 0]);
        $request->setUserResolver(function () { return (object) ['role' => 3]; });
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        (new AlarmController())->store($request, $service);
    }
}
