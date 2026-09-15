<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KostradModel extends Model
{
    use HasFactory;

    protected $table = 'kostrad';
    protected $fillable = ['nama', 'pangkat', 'nrp', 'jabatan'];
}
