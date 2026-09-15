<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaranModel extends Model
{
    use HasFactory;

    protected $table = 'saran';
    protected $guarded = ['id'];
}
