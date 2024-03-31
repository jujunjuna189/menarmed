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
                    "url" => "https://drive.google.com/drive/folders/1NzO_3bNkPrHHv_D3_YWKJSpaV5UM1Blh",
                ],
                (object)[
                    "name" => "Staf Ops",
                    "url" => "https://drive.google.com/drive/folders/1LtEcH2fxFKGSCZfQQF1ZHeoUvtD_YOYp?usp=drive_link",
                ],
                (object)[
                    "name" => "Staf Pers",
                    "url" => "https://drive.google.com/drive/folders/164o-nOwVOVZWltMC3qKfe66DnplziLac",
                ],
                (object)[
                    "name" => "Staf Log",
                    "url" => "https://drive.google.com/drive/folders/11cydmF4MfREMdaVe7toMZrXngBMFKYuK?usp=sharing",
                ],
                (object)[
                    "name" => "Staf Ter",
                    "url" => "https://drive.google.com/drive/folders/1SWrPfz166bTb9aTyvYJp3vzYI6izNARr?usp=drive_link",
                ],
                (object)[
                    "name" => "Staf Ren",
                    "url" => "https://drive.google.com/drive/folders/1mEgxgOBZvh0R2K0G-DcmtCEhFN63_Gnd",
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
