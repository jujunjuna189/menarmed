<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ELearningModel extends Model
{
    use HasFactory;

    protected $table = 'e_learning';
    protected $fillable = ['judul', 'deskripsi', 'path'];
}
