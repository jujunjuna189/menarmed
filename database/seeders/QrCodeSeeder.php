<?php

namespace Database\Seeders;

use App\Models\QrCodeModel;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QrCodeModel::truncate();

        $array = [
            [
                'title' => 'Perizinan',
                'key' => 1,
                'code' => '01-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Gudang Senjata A',
                'key' => 2,
                'code' => '02-A-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Gudang Senjata B',
                'key' => 2,
                'code' => '02-B-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Gudang Senjata C',
                'key' => 2,
                'code' => '02-C-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Gudang Senjata Ma',
                'key' => 2,
                'code' => '02-MA-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Logistik',
                'key' => 3,
                'code' => '01-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Ranpur',
                'key' => 4,
                'code' => '01-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Angkutan',
                'key' => 5,
                'code' => '01-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'title' => 'Absensi',
                'key' => 6,
                'code' => '06-qwerty',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
        ];

        QrCodeModel::insert($array);
    }
}
