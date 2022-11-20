<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerizinanRanpurModel extends Model
{
    use HasFactory;

    protected $table = 'perizinan_ranpur';
    protected $fillable = ['user_id', 'keluar', 'masuk', 'tujuan', 'jenis_kendaraan'];

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
