<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\Event\EventController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CalendarRangeTest extends TestCase
{
    public function test_calendar_range_and_single_date_queries(): void
    {
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('event', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('event');
            $table->string('color');
        });
        foreach (['2026-10-01', '2026-10-15', '2026-11-01'] as $date) {
            DB::table('event')->insert(['tanggal' => $date, 'event' => 'Kegiatan', 'color' => 'primary']);
        }
        $controller = new EventController();
        $response = $controller->show(Request::create('/api/event/show', 'POST', [
            'date_from' => '2026-10-01', 'date_to' => '2026-10-31',
        ]));
        $this->assertSame(200, $response->getStatusCode());
        $this->assertCount(2, $response->getData(true)['data']);
        $response = $controller->show(Request::create('/api/event/show', 'POST', ['tanggal' => '2026-10-15']));
        $this->assertCount(1, $response->getData(true)['data']);
        $response = $controller->show(Request::create('/api/event/show', 'POST', ['tanggal' => '2026-10-16']));
        $this->assertCount(0, $response->getData(true)['data']);
    }
}
