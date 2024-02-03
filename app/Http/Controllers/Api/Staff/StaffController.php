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
                    "url" => "https://drive.google.com/drive/folders/1pExpVtdzpQNrkRBY79lWOElLvD-c_53F",
                ],
                (object)[
                    "name" => "Staf Ops",
                    "url" => "https://drive.google.com/drive/folders/1LssJe7NUT8fsGbWIgLX-ioGdUVY7ZfvJ?usp=share_link",
                ],
                (object)[
                    "name" => "Staf Pers",
                    "url" => "https://drive.google.com/drive/folders/1Uy6q7nb36Kvg_giGSVVB8LNKwtfo0TlN?usp=share_link",
                ],
                (object)[
                    "name" => "Staf Log",
                    "url" => "https://drive.google.com/drive/folders/11cydmF4MfREMdaVe7toMZrXngBMFKYuK",
                ],
                (object)[
                    "name" => "Staf Ter",
                    "url" => "https://drive.google.com/drive/folders/1M90g5gI36S9QsIz0iH-puukTa-6T_1P5?usp=share_link",
                ],
                (object)[
                    "name" => "Staf Ren",
                    "url" => "https://drive.google.com/drive/folders/13Eva-aVIoL6rUjX9_bEM_xVcQI76d9VH?usp=share_link",
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
