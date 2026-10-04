<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\EventModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function index()
    {
        $events = EventModel::orderBy('tanggal')->orderBy('id')->get();
        $today = Carbon::today();

        return view('event.index', [
            'event' => $events,
            'summary' => [
                'total' => $events->count(),
                'this_month' => $events->filter(function ($event) use ($today) {
                    return Carbon::parse($event->tanggal)->isSameMonth($today);
                })->count(),
                'upcoming' => $events->filter(function ($event) use ($today) {
                    return Carbon::parse($event->tanggal)->greaterThanOrEqualTo($today);
                })->count(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $event = EventModel::create($this->validatedData($request));

        return response()->json([
            'message' => 'Kegiatan berhasil ditambahkan.',
            'data' => $event,
        ], 201);
    }

    public function update(Request $request, EventModel $event)
    {
        $event->update($this->validatedData($request));

        return response()->json([
            'message' => 'Kegiatan berhasil diperbarui.',
            'data' => $event->fresh(),
        ]);
    }

    public function destroy(EventModel $event)
    {
        $event->delete();

        return response()->json([
            'message' => 'Kegiatan berhasil dihapus.',
        ]);
    }

    private function validatedData(Request $request)
    {
        return $request->validate([
            'tanggal' => ['required', 'date_format:Y-m-d'],
            'event' => ['required', 'string', 'max:255'],
            'color' => ['required', Rule::in(['success', 'primary', 'danger', 'warning', 'dark'])],
        ]);
    }
}
