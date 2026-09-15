<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $array = [
            [
                'name' => 'Admin',
                'email' => 'admin',
                'password' => Hash::make('admin'),
                'role' => 1,
                'pangkat' => 'Admin',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Sertu Budi Santoso',
                'email' => 'budi',
                'password' => Hash::make('password'),
                'role' => 2,
                'pangkat' => 'Sertu Arm',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Lettu Andi Wijaya',
                'email' => 'andi',
                'password' => Hash::make('password'),
                'role' => 2,
                'pangkat' => 'Lettu Arm',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Serda Iwan',
                'email' => 'iwan',
                'password' => Hash::make('password'),
                'role' => 2,
                'pangkat' => 'Serda Arm',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Kopda Ahmad',
                'email' => 'ahmad',
                'password' => Hash::make('password'),
                'role' => 2,
                'pangkat' => 'Kopda Arm',
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s'),
            ],
        ];

        User::insert($array);
    }
}
