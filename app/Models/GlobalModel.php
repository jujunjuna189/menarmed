<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalModel extends Model
{
    use HasFactory;

    protected static function upload_image($request, $path)
    {
        if ($request->file() != null) {
            $file = $request->file('file');

            $file_name = time() . str_replace(' ', '-', $file->getClientOriginalName());

            $tujuan_upload = 'storage/' . $path;
            $file->move($tujuan_upload, $file_name);
            $file = $tujuan_upload . '/' . $file_name;
        } else {
            $file = '';
        }

        return $file;
    }
}
