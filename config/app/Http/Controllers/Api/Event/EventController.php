<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Models\EventModel;
use Exception;
use Illuminate\Http\Request;

class EventController extends Controller
{

    public function show(Request $request)
    {
        try {
            if (isset($request->tanggal) || $request->tanggal != "") {
                $where['tanggal'] = $request->tanggal;
            }

            $event = EventModel::where($where)->get();

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
            $data['tanggal'] = $request->tanggal;
            $data['event'] = $request->event;
            $data['color'] = $request->color;

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
            $event = EventModel::find($request->id);
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
