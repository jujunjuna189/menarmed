<?php

namespace App\Http\Controllers\Admin\Pejabat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PejabatController extends Controller
{
    public function index()
    {
        return view('pejabat.index');
    }
}
