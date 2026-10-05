<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Report\ReportController;
use App\Http\Controllers\Api\Absensi\AbsensiController;
use App\Models\AbsensiModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AttendancePersonnelFilterTest extends TestCase
{
    public function test_admin_attendance_is_excluded_from_reports_and_live_monitor(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->integer('role');
        });
        Schema::create('absensi', function (Blueprint $table) {
            $table->id(); $table->integer('user_id'); $table->string('ket');
            $table->string('latitude')->nullable(); $table->string('longitude')->nullable(); $table->timestamps();
        });
        DB::table('users')->insert([
            ['id' => 1, 'name' => 'Admin', 'role' => 1],
            ['id' => 2, 'name' => 'Personel', 'role' => 3],
            ['id' => 3, 'name' => 'Petugas', 'role' => 2],
        ]);
        foreach ([1, 2, 3] as $id) {
            DB::table('absensi')->insert(['user_id' => $id, 'ket' => 'Hadir', 'created_at' => now(), 'updated_at' => now()]);
        }
        $this->assertSame(2, AbsensiModel::personnel()->count());
        $report = (new ReportController())->absensi(Request::create('/', 'POST', ['page' => 1]))->getData(true);
        $this->assertSame(2, $report['pagination']['total']);
        $this->assertSame(['Petugas', 'Personel'], array_column($report['data'], 'user_name'));
        $monitor = (new AbsensiController())->showTodayPresence()->getData(true);
        $this->assertCount(2, $monitor['data']['today_presence']);
        $this->assertNotContains(1, array_column($monitor['data']['today_presence'], 'user_id'));
    }
}
