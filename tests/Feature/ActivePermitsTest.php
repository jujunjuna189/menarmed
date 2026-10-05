<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ActivePermitsTest extends TestCase
{
    public function test_list_includes_all_categories_and_old_active_permits_only(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) { $table->id(); $table->string('name'); });
        DB::table('users')->insert(['id' => 1, 'name' => 'Personel']);
        foreach (['perizinan', 'perizinan_ranpur', 'perizinan_kendaraan'] as $table) {
            Schema::create($table, function (Blueprint $schema) {
                $schema->id(); $schema->integer('user_id'); $schema->dateTime('keluar');
                $schema->dateTime('masuk')->nullable(); $schema->string('tujuan')->nullable();
                $schema->string('jenis_kendaraan')->nullable();
            });
            DB::table($table)->insert([
                ['user_id' => 1, 'keluar' => now()->subDays(2), 'masuk' => null],
                ['user_id' => 1, 'keluar' => now()->subDay(), 'masuk' => now()->subHour()],
            ]);
        }
        $view = (new HomeController())->activePermits(Request::create('/home/izin-aktif'));
        $permits = $view->getData()['permits'];
        $this->assertSame(3, $permits->total());
        foreach ($permits as $permit) {
            $this->assertArrayHasKey('masuk', $permit->getAttributes());
            $this->assertNull($permit->masuk);
        }
        $this->assertEqualsCanonicalizing(['Personel', 'Ranpur', 'Angkutan'], $permits->pluck('category')->all());
    }
}
