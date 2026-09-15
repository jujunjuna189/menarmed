<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogistikModel extends Model
{
    use HasFactory;

    protected $table = 'logistik';
    protected $fillable = ['user_id', 'keluar', 'masuk'];

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
