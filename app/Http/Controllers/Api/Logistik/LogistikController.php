<?php

namespace App\Http\Controllers\Api\Logistik;

use App\Http\Controllers\Api\MovementController;
use App\Models\LogistikModel;

class LogistikController extends MovementController
{
    protected $movementModel = LogistikModel::class;
    protected $movementFields = [];
}
