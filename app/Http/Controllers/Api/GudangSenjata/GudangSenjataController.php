<?php

namespace App\Http\Controllers\Api\GudangSenjata;

use App\Http\Controllers\Api\MovementController;
use App\Models\GudangSenjataModel;

class GudangSenjataController extends MovementController
{
    protected $movementModel = GudangSenjataModel::class;
    protected $movementFields = ['batrai_keluar', 'batrai_masuk'];
}
