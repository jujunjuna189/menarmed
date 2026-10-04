<?php

namespace App\Http\Controllers\Api\Perizinan;

use App\Http\Controllers\Api\MovementController;
use App\Models\PerizinanModel;

class PerizinanController extends MovementController
{
    protected $movementModel = PerizinanModel::class;
    protected $movementFields = ['tujuan'];
}
