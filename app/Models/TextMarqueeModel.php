<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextMarqueeModel extends Model
{
    use HasFactory;

    protected $table = 'text_marquee';
    protected $guarded = ['id'];
}
