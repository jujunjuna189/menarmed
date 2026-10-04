<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MovementReturnTest extends TestCase
{
    public function test_return_keeps_outbound_fields_in_database_and_response(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
        });
        DB::table('users')->insert(['id' => 7]);
        Schema::create('qrcode', function (Blueprint $table) {
            $table->id();
            $table->string('code');
        });
        DB::table('qrcode')->insert(['code' => 'valid']);
        $controllers = [
            'perizinan' => new \App\Http\Controllers\Api\Perizinan\PerizinanController(),
            'logistik' => new \App\Http\Controllers\Api\Logistik\LogistikController(),
            'gudang_senjata' => new \App\Http\Controllers\Api\GudangSenjata\GudangSenjataController(),
            'perizinan_ranpur' => new \App\Http\Controllers\Api\Perizinan\PerizinanRanpurController(),
            'perizinan_kendaraan' => new \App\Http\Controllers\Api\Perizinan\PerizinanKendaraanController(),
        ];
        $date = now()->format('Y-m-d');
        foreach ($controllers as $table => $controller) {
            Schema::create($table, function (Blueprint $schema) {
                $schema->id();
                $schema->integer('user_id');
                foreach (['keluar', 'masuk', 'tujuan', 'jenis_kendaraan', 'batrai_keluar', 'batrai_masuk'] as $field) {
                    $schema->string($field)->nullable();
                }
                $schema->timestamps();
            });
            $outbound = $controller->store(Request::create('/', 'POST', [
                'user_id' => 7, 'qrcode' => 'valid', 'keluar' => "$date 08:00:00",
                'masuk' => '', 'tujuan' => 'Latihan', 'jenis_kendaraan' => 'Panser',
                'batrai_keluar' => 'A', 'batrai_masuk' => '',
            ]));
            $this->assertSame(200, $outbound->getStatusCode(), $table);
            $outTime = $outbound->getData(true)['data'][0]['keluar'];
            // Simulate an open transaction from yesterday.
            DB::table($table)->update(['created_at' => now()->subDay()]);
            $duplicate = $controller->store(Request::create('/', 'POST', [
                'user_id' => 7, 'qrcode' => 'valid', 'keluar' => "$date 08:00:00",
            ]));
            $this->assertSame(409, $duplicate->getStatusCode());
            $response = $controller->store(Request::create('/', 'POST', [
                'user_id' => 7, 'qrcode' => 'valid', 'keluar' => '',
                'masuk' => "$date 09:00:00", 'tujuan' => '', 'jenis_kendaraan' => '',
                'batrai_keluar' => '', 'batrai_masuk' => 'B',
            ]));
            $this->assertSame(200, $response->getStatusCode(), $table);
            $row = $response->getData(true)['data'][0];
            $this->assertSame($outTime, $row['keluar'], $table);
            $this->assertNotEmpty($row['masuk']);
            $stored = (array) DB::table($table)->first();
            $this->assertSame(\Carbon\Carbon::parse($stored['keluar'])->timestamp, \Carbon\Carbon::parse($row['keluar'])->timestamp);
            if (in_array($table, ['perizinan_ranpur', 'perizinan_kendaraan'])) {
                $this->assertSame('Panser', $row['jenis_kendaraan']);
                $this->assertSame('Panser', $stored['jenis_kendaraan']);
                $this->assertSame('Latihan', $row['tujuan']);
            }
            if ($table === 'gudang_senjata') {
                $this->assertSame('A', $row['batrai_keluar']);
                $this->assertSame('B', $row['batrai_masuk']);
            }
            $repeatReturn = $controller->store(Request::create('/', 'POST', [
                'user_id' => 7, 'qrcode' => 'valid', 'masuk' => "$date 09:00:00",
            ]));
            $this->assertSame(409, $repeatReturn->getStatusCode());
            $next = $controller->store(Request::create('/', 'POST', [
                'user_id' => 7, 'qrcode' => 'valid', 'keluar' => "$date 10:00:00",
            ]));
            $this->assertSame(200, $next->getStatusCode());
            $this->assertSame(2, DB::table($table)->count());
            $active = $controller->show(Request::create('/', 'POST', ['user_id' => 7]))->getData(true)['data'];
            $this->assertCount(1, $active);
            $this->assertSame($next->getData(true)['data'][0]['id'], $active[0]['id']);
        }
    }
}
