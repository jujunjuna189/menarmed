<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\EventModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{

    public function show(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal' => ['required_without:date_from', 'date_format:Y-m-d'],
                'date_from' => ['required_without:tanggal', 'date_format:Y-m-d'],
                'date_to' => ['required_with:date_from', 'date_format:Y-m-d', 'after_or_equal:date_from'],
            ]);

            $query = EventModel::query();
            if (isset($validated['date_from'])) {
                $query->whereBetween('tanggal', [$validated['date_from'], $validated['date_to']]);
            } else {
                $query->where('tanggal', $validated['tanggal']);
            }
            $event = $query
                ->orderBy('id')
                ->get();

            if ($event) {
                return response()->json([
                    'status' => 'Success',
                    'data' => $event,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'tanggal' => ['required', 'date_format:Y-m-d'],
                'event' => ['required', 'string', 'max:255'],
                'color' => ['required', Rule::in(['success', 'primary', 'danger', 'warning', 'dark'])],
            ]);

            $event = EventModel::create($data);

            if ($event) {
                return response()->json([
                    'status' => 'Success',
                    'data' => [$event],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => ['required', 'integer', 'exists:event,id'],
            ]);
            $event = EventModel::findOrFail($validated['id']);
            $event->delete();

            if ($event) {
                return response()->json([
                    'status' => 'Success',
                    'data' => [$event],
                ], 200);
            } else {
                return response()->json([
                    'status' => 'Failed',
                    'data' => [],
                ], 300);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
