<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiModel extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = ['user_id', 'ket', 'latitude', 'longitude', 'created_at'];

    public function scopePersonnel($query)
    {
        return $query->whereHas('userModel', function ($users) {
            $users->where('role', '!=', 1);
        });
    }

    public function userModel()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
