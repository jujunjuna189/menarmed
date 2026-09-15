<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function show()
    {
        try {
            $response = [
                (object)[
                    "name" => "Staf Intel",
                    "url" => "https://drive.google.com/drive/folders/14uIl9ksDHHdQuRyGb68lrDzcGuzJ5uTI",
                ],
                (object)[
                    "name" => "Staf Ops",
                    "url" => "https://drive.google.com/drive/folders/14f40m1EZa_ULKkza_vcmBtuCOAzfzHIC",
                ],
                (object)[
                    "name" => "Staf Pers",
                    "url" => "https://drive.google.com/drive/folders/1KGlTqo0RKLB3SJT8ZclqGjUe4IiOYh_f",
                ],
                (object)[
                    "name" => "Staf Log",
                    "url" => "https://drive.google.com/drive/folders/1BFb3uSoRlj3MjTvhj5cg4iaf4KWNpGZc",
                ],
                (object)[
                    "name" => "Staf Ter",
                    "url" => "https://drive.google.com/drive/folders/1af8z5f9ujiYwlxUxPL4TiSknMyyqagZb",
                ],
                (object)[
                    "name" => "Staf Ren",
                    "url" => "https://drive.google.com/drive/folders/1pSFKsqeQZJ1DuJb9kgesnVwpU_y37Zwh",
                ],
            ];

            return response()->json([
                'status' => 'Success',
                'data' => $response,
            ], 200);
        }
        catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
