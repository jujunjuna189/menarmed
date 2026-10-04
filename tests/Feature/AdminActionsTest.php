<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role');
            $table->timestamps();
        });
    }

    public function test_admin_edit_validates_and_preserves_optional_password(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => Hash::make('original123'), 'role' => 1]);
        $this->actingAs($admin);
        $original = $admin->password;
        $this->patchJson(route('pengguna.admin.update', $admin), ['name' => 'Updated', 'email' => 'admin'])->assertOk();
        $this->assertSame($original, $admin->fresh()->password);
        $this->patchJson(route('pengguna.admin.update', $admin), ['name' => 'Updated', 'email' => 'admin', 'password' => 'newpass123', 'password_confirmation' => 'newpass123'])->assertOk();
        $this->assertTrue(Hash::check('newpass123', $admin->fresh()->password));
        $this->patchJson(route('pengguna.admin.update', $admin), ['name' => '', 'email' => 'admin'])->assertUnprocessable();
        $this->postJson(route('pengguna.update_role'), ['id' => $admin->id, 'role' => 3])->assertUnprocessable();
        $this->assertSame(1, (int) $admin->fresh()->role);
    }

    public function test_remove_admin_retains_personnel_account(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => 'test', 'role' => 1]);
        $target = User::create(['name' => 'Target', 'email' => 'target', 'password' => 'test', 'role' => 1]);
        $this->actingAs($admin)->postJson(route('pengguna.update_role'), ['id' => $target->id, 'role' => 3])->assertOk();
        $this->assertSame(3, (int) $target->fresh()->role);
        $this->patchJson(route('pengguna.admin.update', $target), ['name' => 'Target', 'email' => 'target'])->assertNotFound();
    }

    public function test_personnel_edit_and_delete_preserve_operational_history(): void
    {
        foreach (['absensi', 'perizinan', 'perizinan_kendaraan', 'perizinan_ranpur', 'logistik', 'gudang_senjata', 'kemampuan'] as $table) {
            Schema::create($table, function (Blueprint $table) { $table->integer('user_id'); });
        }
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
        });
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => 'test', 'role' => 1]);
        $person = User::create(['name' => 'Person', 'email' => 'person', 'password' => 'test', 'role' => 3]);
        $this->actingAs($admin);
        $this->patchJson(route('pengguna.personel.update', $person), ['name' => 'Updated', 'email' => 'person'])->assertOk();
        $this->assertSame('Updated', $person->fresh()->name);
        DB::table('absensi')->insert(['user_id' => $person->id]);
        $this->deleteJson(route('pengguna.personel.destroy', $person))->assertUnprocessable();
        $this->assertNotNull($person->fresh());
        DB::table('absensi')->delete();
        DB::table('kemampuan')->insert(['user_id' => $person->id]);
        $this->deleteJson(route('pengguna.personel.destroy', $person))->assertOk();
        $this->assertNull($person->fresh());
        $this->assertSame(0, DB::table('kemampuan')->count());
        $this->deleteJson(route('pengguna.personel.destroy', $admin))->assertNotFound();
    }
}
