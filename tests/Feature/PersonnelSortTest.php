<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Pengguna\PenggunaController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PersonnelSortTest extends TestCase
{
    public function test_name_sort_is_applied_before_pagination(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('role');
            $table->timestamps();
        });
        foreach (['Zain', 'Budi', 'Andi'] as $name) {
            DB::table('users')->insert(['name' => $name, 'role' => 3, 'created_at' => '2026-10-05 08:00:00']);
        }
        foreach (['name_asc' => ['Andi', 'Budi', 'Zain'], 'name_desc' => ['Zain', 'Budi', 'Andi']] as $sort => $names) {
            foreach ($names as $index => $name) {
                $response = (new PenggunaController())->show(Request::create('/', 'POST', [
                    'role' => '[3]', 'sort' => $sort, 'page' => $index + 1, 'per_page' => 1,
                ]));
                $this->assertSame(200, $response->getStatusCode());
                $body = $response->getData(true);
                $this->assertSame($name, $body['data'][0]['name']);
                $this->assertSame($index < 2, $body['pagination']['has_more']);
            }
        }
    }
}
