<?php

namespace App\Http\Controllers\Api\Perizinan;

use App\Http\Controllers\Api\MovementController;
use App\Models\PerizinanRanpurModel;

class PerizinanRanpurController extends MovementController
{
    protected $movementModel = PerizinanRanpurModel::class;
    protected $movementFields = ['tujuan', 'jenis_kendaraan'];
}
