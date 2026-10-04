<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Pejabat\ArmedController;
use App\Http\Controllers\Api\Pejabat\KostradController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PositionPaginationTest extends TestCase
{
    public function test_both_categories_paginate_and_search_names_and_positions(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        foreach (['kostrad' => new KostradController(), 'armed' => new ArmedController()] as $table => $controller) {
            Schema::create($table, function (Blueprint $table) {
                $table->id(); $table->string('nama'); $table->string('jabatan'); $table->timestamps();
            });
            for ($id = 1; $id <= 23; $id++) {
                DB::table($table)->insert(['nama' => 'Personel ' . $id, 'jabatan' => $id === 1 ? 'Komandan' : 'Staf',
                    'created_at' => '2026-10-01 08:00:00', 'updated_at' => '2026-10-01 08:00:00']);
            }
            foreach ([1 => 10, 2 => 10, 3 => 3] as $page => $count) {
                $response = $controller->show(Request::create('/', 'POST', ['page' => $page, 'per_page' => 10]));
                $this->assertSame(200, $response->getStatusCode());
                $body = $response->getData(true);
                $this->assertCount($count, $body['data']);
                $this->assertSame(23 - ($page - 1) * 10, $body['data'][0]['id']);
                $this->assertSame($page < 3, $body['pagination']['has_more']);
            }
            foreach (['Komandan', 'Personel 23'] as $search) {
                $response = $controller->show(Request::create('/', 'POST', ['page' => 1, 'search' => $search]));
                $this->assertCount(1, $response->getData(true)['data']);
                $this->assertFalse($response->getData(true)['pagination']['has_more']);
            }
        }
    }
}
