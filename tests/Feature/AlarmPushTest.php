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
        $service->shouldReceive('send')->once()->with(true, 2, true)->andReturn(true);
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

    public function test_admin_can_stop_alarm(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('send')->once()->with(false, 2, true)->andReturn(true);
        $request = Request::create('/', 'POST', ['status' => false, 'code' => 2]);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new AlarmController)->store($request, $service);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['push_sent']);
    }

    public function test_partial_push_failure_preserves_successful_alarm_activation(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('send')->once()->with(true, 0, true)->andReturn(false);
        $request = Request::create('/', 'POST', ['status' => true, 'code' => 0]);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new AlarmController)->store($request, $service);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['push_sent']);
    }

    public function test_status_is_loaded_from_shared_alarm_service(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('status')->once()->andReturn(['status' => true, 'code' => 1, 'started_at' => 1000]);
        $request = Request::create('/', 'GET');
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new AlarmController)->status($request, $service);
        $this->assertSame(['status' => true, 'code' => 1, 'started_at' => 1000], $response->getData(true));
    }

    public function test_invalid_alarm_code_is_rejected(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('send');
        $request = Request::create('/', 'POST', ['status' => true, 'code' => 5]);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new AlarmController)->store($request, $service);
    }

    public function test_siren_can_be_disabled(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('send')->once()->with(true, 1, false)->andReturn(true);
        $request = Request::create('/', 'POST', ['status' => true, 'code' => 1, 'siren' => false]);
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $this->assertSame(200, (new AlarmController)->store($request, $service)->getStatusCode());
    }

    public function test_status_failure_logs_safe_diagnostics_without_exposing_secret(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('status')->once()->andThrow(new \RuntimeException('secret-private-key'));
        \Illuminate\Support\Facades\Log::shouldReceive('warning')->once()
            ->with('Firebase alarm failed', ['operation' => 'status', 'exception' => \RuntimeException::class]);
        $request = Request::create('/', 'GET');
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        $response = (new AlarmController)->status($request, $service);
        $this->assertSame(502, $response->getStatusCode());
        $this->assertStringNotContainsString('secret-private-key', $response->getContent());
    }
}
