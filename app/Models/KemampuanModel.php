<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KemampuanModel extends Model
{
    use HasFactory;

    protected $table = 'kemampuan';
    protected $fillable = ['user_id', 'lari', 'renang', 'jatri', 'jatrat', 'pistol', 'push_up', 'sit_up', 'pull_up', 'shutle_run'];
}
