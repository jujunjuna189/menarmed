<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PushDeviceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        Schema::create('users', function ($table) { $table->id(); });
        require_once database_path('migrations/2026_10_09_000001_create_push_devices_table.php');
        (new \CreatePushDevicesTable)->up();
        \Illuminate\Support\Facades\DB::table('users')->insert([['id' => 1], ['id' => 2]]);
    }

    private function loginAs(int $id): void
    {
        $user = new User;
        $user->id = $id;
        Sanctum::actingAs($user);
    }

    public function test_unauthenticated_registration_is_rejected(): void
    {
        $this->postJson('/api/push-device', [])->assertUnauthorized();
    }

    public function test_refresh_replaces_token_and_account_switch_reassigns_device(): void
    {
        $this->loginAs(1);
        $id = str_repeat('a', 64);
        $this->postJson('/api/push-device', ['installation_id' => $id, 'token' => 'old'])->assertOk();
        $this->postJson('/api/push-device', ['installation_id' => $id, 'token' => 'new'])->assertOk();
        $this->assertDatabaseCount('push_devices', 1);
        $this->assertDatabaseHas('push_devices', ['user_id' => 1, 'token' => 'new']);
        $this->loginAs(2);
        $this->postJson('/api/push-device', ['installation_id' => $id, 'token' => 'new'])->assertOk();
        $this->assertDatabaseHas('push_devices', ['user_id' => 2, 'token' => 'new']);
    }

    public function test_logout_only_removes_own_device(): void
    {
        $this->loginAs(1);
        $id = str_repeat('b', 64);
        $this->postJson('/api/push-device', ['installation_id' => $id, 'token' => 'token'])->assertOk();
        $this->loginAs(2);
        $this->deleteJson('/api/push-device', ['installation_id' => $id])->assertNoContent();
        $this->assertDatabaseCount('push_devices', 1);
        $this->loginAs(1);
        $this->deleteJson('/api/push-device', ['installation_id' => $id])->assertNoContent();
        $this->assertDatabaseCount('push_devices', 0);
    }
}
