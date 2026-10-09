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

    public function test_admin_can_reset_admin_and_personnel_passwords(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => Hash::make('original123'), 'role' => 1]);
        $person = User::create(['name' => 'Person', 'email' => 'person', 'password' => Hash::make('original123'), 'role' => 3]);
        $other = User::create(['name' => 'Other', 'email' => 'other', 'password' => Hash::make('original123'), 'role' => 3]);
        $original = $other->password;
        $this->actingAs($admin);
        foreach ([$admin, $person] as $target) {
            $this->postJson(route('pengguna.reset-password', $target), ['password' => 'ignored123'])
                ->assertOk()->assertJson(['status' => 'success']);
            $updated = $target->fresh();
            $this->assertTrue(Hash::check('Password123!', $updated->password));
            $this->assertFalse(Hash::check('original123', $updated->password));
            $this->assertNotSame('Password123!', $updated->password);
            $this->assertSame($target->email, $updated->email);
            $this->assertSame((int) $target->role, (int) $updated->role);
        }
        $this->assertSame($original, $other->fresh()->password);
        $this->postJson(route('pengguna.reset-password', 999))->assertNotFound();
        $unsupported = User::create(['name' => 'Unsupported', 'email' => 'unsupported', 'password' => 'test', 'role' => 2]);
        $this->postJson(route('pengguna.reset-password', $unsupported))->assertNotFound();
    }

    public function test_bulk_reset_only_changes_selected_accounts_and_rejects_invalid_selection(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => Hash::make('original123'), 'role' => 1]);
        $person = User::create(['name' => 'Person', 'email' => 'person', 'password' => Hash::make('original123'), 'role' => 3]);
        $other = User::create(['name' => 'Other', 'email' => 'other', 'password' => Hash::make('original123'), 'role' => 3]);
        $unsupported = User::create(['name' => 'Unsupported', 'email' => 'unsupported', 'password' => 'test', 'role' => 2]);
        $original = $person->password;
        $otherPassword = $other->password;
        $this->actingAs($admin);
        $this->getJson(route('pengguna.reset-password-options', ['role' => 3]))
            ->assertOk()->assertJsonCount(2, 'data')->assertJsonFragment(['id' => $person->id, 'name' => 'Person', 'email' => 'person'])->assertJsonMissing(['email' => 'admin']);
        $this->getJson(route('pengguna.reset-password-options', ['role' => 2]))->assertUnprocessable();
        foreach ([[], [$person->id, 999], [$person->id, $unsupported->id], [$person->id, $person->id], ['invalid']] as $ids) {
            $this->postJson(route('pengguna.reset-passwords'), ['ids' => $ids])->assertUnprocessable();
            $this->assertSame($original, $person->fresh()->password);
        }
        $this->postJson(route('pengguna.reset-passwords'), ['ids' => [$admin->id, $person->id]])
            ->assertOk()->assertJson(['message' => 'Password 2 akun berhasil direset ke Password123!']);
        foreach ([$admin, $person] as $target) {
            $this->assertTrue(Hash::check('Password123!', $target->fresh()->password));
        }
        $this->assertSame($otherPassword, $other->fresh()->password);
    }

    public function test_guest_and_personnel_cannot_reset_passwords(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => Hash::make('original123'), 'role' => 1]);
        $person = User::create(['name' => 'Person', 'email' => 'person', 'password' => Hash::make('original123'), 'role' => 3]);
        $original = $admin->password;
        $this->postJson(route('pengguna.reset-password', $admin))->assertUnauthorized();
        $this->postJson(route('pengguna.reset-passwords'), ['ids' => [$admin->id]])->assertUnauthorized();
        $this->actingAs($person)->postJson(route('pengguna.reset-password', $admin))->assertRedirect('/login');
        $this->actingAs($person)->postJson(route('pengguna.reset-passwords'), ['ids' => [$admin->id]])->assertRedirect('/login');
        $this->assertSame($original, $admin->fresh()->password);
    }

    public function test_personnel_delete_removes_all_history_and_preserves_other_personnel(): void
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
        $other = User::create(['name' => 'Other', 'email' => 'other', 'password' => 'test', 'role' => 3]);
        $tables = ['absensi', 'perizinan', 'perizinan_kendaraan', 'perizinan_ranpur', 'logistik', 'gudang_senjata', 'kemampuan'];
        foreach ($tables as $table) {
            DB::table($table)->insert([
                ['user_id' => $person->id],
                ['user_id' => $person->id],
                ['user_id' => $other->id],
            ]);
        }
        foreach ([$person, $other] as $account) {
            DB::table('personal_access_tokens')->insert(['tokenable_id' => $account->id, 'tokenable_type' => $account->getMorphClass()]);
        }

        // A failure while removing the account must roll back all history deletions.
        DB::unprepared("CREATE TRIGGER prevent_user_delete BEFORE DELETE ON users BEGIN SELECT RAISE(ABORT, 'Deletion failed'); END");
        $this->deleteJson(route('pengguna.personel.destroy', $person))->assertStatus(500);
        $this->assertNotNull($person->fresh());
        foreach ($tables as $table) {
            $this->assertSame(2, DB::table($table)->where('user_id', $person->id)->count());
        }
        $this->assertSame(2, DB::table('personal_access_tokens')->count());
        DB::unprepared('DROP TRIGGER prevent_user_delete');

        $this->deleteJson(route('pengguna.personel.destroy', $person))->assertOk();
        $this->assertNull($person->fresh());
        $this->assertNotNull($other->fresh());
        foreach ($tables as $table) {
            $this->assertSame(0, DB::table($table)->where('user_id', $person->id)->count());
            $this->assertSame(1, DB::table($table)->where('user_id', $other->id)->count());
        }
        $this->assertSame(0, $person->tokens()->count());
        $this->assertSame(1, $other->tokens()->count());
        $this->deleteJson(route('pengguna.personel.destroy', $admin))->assertNotFound();

        auth()->logout();
        $registration = [
            'name' => 'Personel Baru',
            'email' => $person->email,
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ];
        $this->postJson(route('register'), $registration)->assertRedirect();
        $replacement = User::where('email', $person->email)->firstOrFail();
        $this->assertNotEquals($person->id, $replacement->id);
        $this->assertSame('Personel Baru', $replacement->name);
        $this->assertSame(3, (int) $replacement->role);
        $this->assertTrue(Hash::check('newpass123', $replacement->password));
        foreach ($tables as $table) {
            $this->assertSame(0, DB::table($table)->where('user_id', $replacement->id)->count());
        }

        auth()->logout();
        $this->postJson(route('register'), $registration)->assertUnprocessable()->assertJsonValidationErrors('email');
        $this->assertSame(1, User::where('email', $person->email)->count());
    }
}
