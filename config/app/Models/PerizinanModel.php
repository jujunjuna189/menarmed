<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerizinanModel extends Model
{
    use HasFactory;

    protected $table = 'perizinan';
    protected $fillable = ['user_id', 'keluar', 'masuk', 'tujuan', 'jenis_kendaraan'];

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
