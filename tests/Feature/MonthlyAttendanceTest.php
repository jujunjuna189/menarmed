<?php

namespace Tests\Feature;

use App\Exports\MonthlyAbsensiExport;
use App\Models\User;
use App\Support\MonthlyAttendance;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MonthlyAttendanceTest extends TestCase
{
    public function test_monthly_records_export_and_page_preserve_all_daily_statuses(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('role', function (Blueprint $table) {
            $table->id(); $table->integer('key'); $table->string('role');
        });
        DB::table('role')->insert(['key' => 1, 'role' => 'Admin']);
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email'); $table->string('password');
            $table->integer('role'); $table->string('pangkat')->nullable(); $table->timestamps();
        });
        Schema::create('absensi', function (Blueprint $table) {
            $table->id(); $table->integer('user_id'); $table->string('ket'); $table->timestamps();
            $table->string('latitude')->nullable(); $table->string('longitude')->nullable();
        });
        $person = User::create(['name' => 'Personel', 'email' => 'person', 'password' => 'test', 'role' => 3]);
        foreach ([['HADIR', '2024-02-02'], ['IJIN', '2024-02-02'], ['DD', '2024-02-29'], ['CUTI', '2024-03-01']] as $entry) {
            DB::table('absensi')->insert(['user_id' => $person->id, 'ket' => $entry[0], 'created_at' => $entry[1] . ' 08:00:00']);
        }
        $month = Carbon::createFromFormat('!Y-m', '2024-02');
        DB::table('absensi')->where('ket', 'HADIR')->update(['latitude' => '-6.2', 'longitude' => '106.8']);
        $export = new MonthlyAbsensiExport($month, '');
        $this->assertCount(31, $export->headings());
        $row = $export->collection()->first();
        $this->assertSame('-', $row[2]);
        $this->assertSame('H/I', $row[3]);
        $this->assertSame('DD', $row[30]);
        $this->assertCount(2, MonthlyAttendance::records($month, [$person->id]));
        $this->assertSame('BP', MonthlyAttendance::code('BP'));
        $admin = User::create(['name' => 'Admin', 'email' => 'admin', 'password' => 'test', 'role' => 1]);
        $this->actingAs($admin)->get(route('report.absensi', ['month' => '2024-02']))
            ->assertOk()->assertSee('H/I')->assertSee('Personel')
            ->assertSee('-6.2')->assertSee('106.8')->assertSee('Buka Peta')->assertSee('Koordinat tidak tersedia.');
        DB::table('absensi')->where('ket', 'HADIR')->update(['latitude' => '0', 'longitude' => '0']);
        $this->get(route('report.absensi', ['month' => '2024-02']))->assertOk()->assertSee('query=0%2C0');
        DB::table('absensi')->where('ket', 'HADIR')->update(['latitude' => '999']);
        $this->get(route('report.absensi', ['month' => '2024-02']))->assertOk()->assertDontSee('Buka Peta');
        $this->get(route('report.absensi', ['month' => 'invalid']))->assertSessionHasErrors('month');
    }
}
