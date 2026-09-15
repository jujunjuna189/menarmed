<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BujukModel extends Model
{
    use HasFactory;
    protected $table = 'bujuk';
    protected $fillable = ['judul', 'deskripsi', 'path'];
}
