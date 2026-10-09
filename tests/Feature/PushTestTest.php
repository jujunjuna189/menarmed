<?php

namespace Tests\Feature;

use App\Services\AlarmPushService;
use App\Http\Controllers\PushTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PushTestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function ($table) { $table->id(); $table->string('name'); $table->string('email'); });
        require_once database_path('migrations/2026_10_09_000001_create_push_devices_table.php');
        (new \CreatePushDevicesTable)->up();
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Penerima', 'email' => '111'],
            ['id' => 2, 'name' => 'Lain', 'email' => '222'],
            ['id' => 3, 'name' => 'Tanpa perangkat', 'email' => '333'],
        ]);
        foreach ([1 => 1, 2 => 1, 3 => 2] as $device => $user) {
            DB::table('push_devices')->insert(['user_id' => $user, 'installation_id' => str_repeat((string) $device, 64), 'token' => "token-{$device}", 'token_hash' => hash('sha256', "token-{$device}")]);
        }
    }

    private function request(array $values = []): Request
    {
        $request = Request::create('/', 'POST', array_merge(['recipient_id' => 1, 'title' => 'Siaga', 'body' => 'Pesan', 'siren' => '1'], $values));
        $request->setUserResolver(function () { return (object) ['role' => 1]; });
        return $request;
    }

    public function test_only_accounts_with_devices_are_listed_without_exposing_tokens(): void
    {
        $recipients = (new PushTestController)->index()->getData()['recipients'];
        $this->assertEqualsCanonicalizing([1, 2], $recipients->pluck('id')->all());
        $this->assertEquals(2, $recipients->firstWhere('id', 1)->device_count);
        $this->assertFalse(property_exists($recipients->first(), 'token'));
    }

    public function test_all_devices_of_selected_recipient_receive_message(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('testDevice')->once()->with('token-1', 'Siaga', 'Pesan', true);
        $service->shouldReceive('testDevice')->once()->with('token-2', 'Siaga', 'Pesan', true);
        $service->shouldNotReceive('send');
        $this->assertSame(302, (new PushTestController)->send($this->request(), $service)->getStatusCode());
        $this->assertStringContainsString('2 perangkat', session('success'));
    }

    public function test_failed_device_does_not_stop_other_devices(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('testDevice')->once()->with('token-1', 'Siaga', 'Pesan', true)->andThrow(new \RuntimeException);
        $service->shouldReceive('testDevice')->once()->with('token-2', 'Siaga', 'Pesan', true);
        (new PushTestController)->send($this->request(), $service);
        $this->assertStringContainsString('1 dari 2', session('error'));
    }

    public function test_recipient_without_device_is_rejected(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('testDevice');
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new PushTestController)->send($this->request(['recipient_id' => 3]), $service);
    }

    public function test_non_admin_cannot_send(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('testDevice');
        $request = $this->request();
        $request->setUserResolver(function () { return (object) ['role' => 3]; });
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        (new PushTestController)->send($request, $service);
    }

    public function test_broadcast_uses_topic_even_without_registered_devices(): void
    {
        DB::table('push_devices')->delete();
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('broadcast')->once()->with('Siaga', 'Pesan', true);
        $service->shouldNotReceive('testDevice');
        $service->shouldNotReceive('send');
        (new PushTestController)->send($this->request(['recipient_id' => 'all']), $service);
        $this->assertStringContainsString('semua aplikasi', session('success'));
    }

    public function test_broadcast_failure_returns_error(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldReceive('broadcast')->once()->andThrow(new \RuntimeException);
        (new PushTestController)->send($this->request(['recipient_id' => 'all']), $service);
        $this->assertStringContainsString('gagal', session('error'));
    }

    public function test_invalid_recipient_is_rejected(): void
    {
        $service = \Mockery::mock(AlarmPushService::class);
        $service->shouldNotReceive('testDevice');
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        (new PushTestController)->send($this->request(['recipient_id' => 'invalid']), $service);
    }
}
