<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderModel extends Model
{
    use HasFactory;

    protected $table = 'slider';
    protected $fillable = ['key', 'komentar', 'path'];

    protected function getPathAttribute($val)
    {
        return asset($val);
    }

    protected static function dashboardSlider()
    {
        $result = SliderModel::where('key', 1)->get();
        return $result;
    }
}
