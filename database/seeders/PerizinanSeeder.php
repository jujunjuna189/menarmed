<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\PerizinanModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class PerizinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year = 2025;
        $destinations = [
            'Sadang', 'cikopak', 'cempaka', 'subang', 'pasar rebo', 
            'pasar jumat', 'Ciganea', 'pasawahan', 'maracang', 
            'sukarata', 'cipaisan'
        ];
        $vehicles = ['Motor Pribadi', 'Mobil Pribadi', 'Motor Dinas', 'Mobil Dinas'];

        $users = User::where('role', '!=', 1)->pluck('id')->toArray();
        if (empty($users)) {
            $this->command->info("No users found to generate perizinan.");
            return;
        }

        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create($year, 12, 31);
        $period = CarbonPeriod::create($startDate, $endDate);

        $this->command->info("Generating Perizinan for year $year...");

        $batchSize = 500;
        $dataToInsert = [];

        foreach ($period as $date) {
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            $dateString = $date->format('Y-m-d');
            
            // Randomly pick number of people (max 11)
            $count = rand(1, 11);
            
            // Pick random users for this day
            $dailyUsers = array_rand(array_flip($users), min($count, count($users)));
            if (!is_array($dailyUsers)) $dailyUsers = [$dailyUsers];

            foreach ($dailyUsers as $uid) {
                $keluarHour = rand(8, 16);
                $keluarMin = rand(0, 59);
                $durationHours = rand(1, 4);
                
                $keluar = $date->copy()->setTime($keluarHour, $keluarMin, 0);
                $masuk = $keluar->copy()->addHours($durationHours)->addMinutes(rand(0, 59));

                $dataToInsert[] = [
                    'user_id' => $uid,
                    'keluar' => $keluar->format('Y-m-d H:i:s'),
                    'masuk' => $masuk->format('Y-m-d H:i:s'),
                    'tujuan' => $destinations[array_rand($destinations)],
                    'jenis_kendaraan' => $vehicles[array_rand($vehicles)],
                    'created_at' => $keluar->format('Y-m-d H:i:s'),
                    'updated_at' => $keluar->format('Y-m-d H:i:s'),
                ];

                if (count($dataToInsert) >= $batchSize) {
                    PerizinanModel::insert($dataToInsert);
                    $dataToInsert = [];
                }
            }
        }

        if (!empty($dataToInsert)) {
            PerizinanModel::insert($dataToInsert);
        }

        $this->command->info("Perizinan data generated successfully.");
    }
}
