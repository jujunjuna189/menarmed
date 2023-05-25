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
                    "url" => "https://google.com",
                ],
                (object)[
                    "name" => "Staf Ops",
                    "url" => "https://google.com",
                ],
                (object)[
                    "name" => "Staf Pers",
                    "url" => "https://google.com",
                ],
                (object)[
                    "name" => "Staf Log",
                    "url" => "https://google.com",
                ],
                (object)[
                    "name" => "Staf Ter",
                    "url" => "https://google.com",
                ],
                (object)[
                    "name" => "Staf Ren",
                    "url" => "https://google.com",
                ],
            ];

            return response()->json([
                'status' => 'Success',
                'data' => $response,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'Server Error',
                'data' => [],
            ], 500);
        }
    }
}
