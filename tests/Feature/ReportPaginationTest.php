<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Report\ReportController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ReportPaginationTest extends TestCase
{
    public function test_personal_history_filters_before_paginating(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('role');
        });
        DB::table('users')->insert([['id' => 7, 'role' => 3], ['id' => 8, 'role' => 1]]);
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->timestamps();
        });
        for ($id = 1; $id <= 25; $id++) {
            DB::table('absensi')->insert(['user_id' => $id <= 12 ? 7 : 8]);
        }
        $method = new \ReflectionMethod(ReportController::class, 'reportQuery');
        $method->setAccessible(true);
        foreach ([1 => 10, 2 => 2, 3 => 0] as $page => $count) {
            $result = $method->invoke(new ReportController(), \App\Models\AbsensiModel::class,
                Request::create('/', 'POST', ['user_id' => 7, 'page' => $page, 'per_page' => 10]));
            $this->assertSame(12, $result->total());
            $this->assertCount($count, $result->items());
            foreach ($result as $row) {
                $this->assertSame(7, (int) $row->user_id);
            }
        }
    }

    public function test_reports_return_separate_pages_and_success_when_exhausted(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('saran', function (Blueprint $table) {
            $table->id();
            $table->string('from_display');
            $table->string('message');
            $table->timestamps();
        });
        for ($id = 1; $id <= 23; $id++) {
            DB::table('saran')->insert([
                'from_display' => $id <= 10 ? 'Personel' : 'Pengirim lain',
                'message' => 'Message ' . $id,
                'created_at' => $id > 20 ? '2026-10-02 08:00:00' : '2026-10-01 08:00:00',
                'updated_at' => '2026-10-01 08:00:00',
            ]);
        }
        $controller = new ReportController();
        foreach ([1 => 10, 2 => 10, 3 => 3, 4 => 0] as $page => $count) {
            $response = $controller->saran(Request::create('/api/report/saran', 'POST', [
                'page' => $page,
                'per_page' => 10,
            ]));
            $this->assertSame(200, $response->getStatusCode());
            $rows = $response->getData(true)['data'];
            $pagination = $response->getData(true)['pagination'];
            $this->assertSame($page, $pagination['current_page']);
            $this->assertSame(3, $pagination['last_page']);
            $this->assertSame(23, $pagination['total']);
            $this->assertSame($page < 3, $pagination['has_more']);
            $this->assertCount($count, $rows);
            if ($count) {
                $this->assertSame('Message ' . (23 - ($page - 1) * 10), $rows[0]['message']);
            }
        }
        $response = $controller->saran(Request::create('/api/report/saran', 'POST'));
        $this->assertCount(23, $response->getData(true)['data']);
        $response = $controller->saran(Request::create('/api/report/saran', 'POST', [
            'page' => 1, 'per_page' => 10, 'search' => 'Personel',
        ]));
        $this->assertCount(10, $response->getData(true)['data']);
        $this->assertFalse($response->getData(true)['pagination']['has_more']);
        $this->assertSame(10, $response->getData(true)['pagination']['total']);
        $response = $controller->saran(Request::create('/api/report/saran', 'POST', [
            'page' => 1, 'per_page' => 10,
            'date_from' => '2026-10-02', 'date_to' => '2026-10-02',
        ]));
        $this->assertCount(3, $response->getData(true)['data']);
        $this->assertSame(3, $response->getData(true)['pagination']['total']);
        $response = $controller->saran(Request::create('/api/report/saran', 'POST', [
            'page' => 1, 'search' => 'Personel', 'date_from' => '2026-10-02',
        ]));
        $this->assertCount(0, $response->getData(true)['data']);
    }
}
