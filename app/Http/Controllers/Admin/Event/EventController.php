<?php

namespace App\Http\Controllers\Admin\Event;

use App\Http\Controllers\Controller;
use App\Models\EventModel;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $data['event'] = EventModel::all();
        return view('event.index', $data);
    }
}
