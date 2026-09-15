<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Truncate tables to allow for a fresh start as requested
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gudang_senjata')->truncate();
        DB::table('perizinan_kendaraan')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            GudangSenjataSeeder::class,
            
            // Standard Perizinan (Kijang, etc)
            PerizinanKendaraanSeeder::class,
            PerizinanKendaraanQ2Seeder::class,
            PerizinanKendaraanQ3Seeder::class,
            PerizinanKendaraanQ4Seeder::class,
            
            // Extra/Taktis Perizinan (OZ, Matan, etc)
            PerizinanKendaraanQ1EkstraSeeder::class,
            PerizinanKendaraanQ1Batch2Seeder::class,
            PerizinanKendaraanQ2EkstraSeeder::class,
            PerizinanKendaraanQ3EkstraSeeder::class,
            PerizinanKendaraanQ4EkstraSeeder::class,
        ]);
    }
}
