<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArmedModel extends Model
{
    use HasFactory;

    protected $table = 'armed';
    protected $fillable = ['nama', 'pangkat', 'nrp', 'jabatan'];
}
