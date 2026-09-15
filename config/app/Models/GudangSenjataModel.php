<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangSenjataModel extends Model
{
    use HasFactory;

    protected $table = 'gudang_senjata';
    protected $fillable = ['user_id', 'batrai_keluar', 'batrai_masuk', 'keluar', 'masuk'];

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
