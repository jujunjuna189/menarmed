<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'pangkat',
        'korp',
        'email',
        'password',
        'satuan',
        'tempat_lahir',
        'tgl_lahir',
        'agama',
        'gol_darah',
        'sumber_pa',
        'jabatan',
        'thumbnail',
        'senjata',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected function roleModel()
    {
        return $this->hasOne(RoleModel::class, 'key', 'role');
    }

    protected function kemampuanModel()
    {
        return $this->hasOne(KemampuanModel::class, 'user_id', 'id');
    }
}
