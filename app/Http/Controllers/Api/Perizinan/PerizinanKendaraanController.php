<?php

namespace App\Http\Controllers\Api\Perizinan;

use App\Http\Controllers\Api\MovementController;
use App\Models\PerizinanKendaraanModel;

class PerizinanKendaraanController extends MovementController
{
    protected $movementModel = PerizinanKendaraanModel::class;
    protected $movementFields = ['tujuan', 'jenis_kendaraan'];
}
