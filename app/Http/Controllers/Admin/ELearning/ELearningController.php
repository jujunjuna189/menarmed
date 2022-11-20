<?php

namespace App\Http\Controllers\Admin\ELearning;

use App\Http\Controllers\Controller;
use App\Models\ELearningModel;
use Illuminate\Http\Request;

class ELearningController extends Controller
{
    public function index()
    {
        $data['learning'] = ELearningModel::all();
        $data['no'] = 1;

        return view('e-learning.index', $data);
    }
}
