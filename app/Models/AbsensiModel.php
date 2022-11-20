<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = ['user_id', 'ket', 'latitude', 'longitude'];

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
