<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GudangSenjataModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GudangSenjataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ranks to exclude
        $excludedRanks = [
            'Kolonel Arm',
            'Mayor Arm',
            'Kapten Arm',
            'Kapten Ckm'
        ];

        // Get all eligible personnel
        $eligibleUsers = User::whereNotIn('pangkat', $excludedRanks)->get();

        if ($eligibleUsers->isEmpty()) {
            $this->command->warn('No eligible users found to seed Gudang Senjata.');
            return;
        }

        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2026, 4, 3);

        $dataToInsert = [];
        $batchSize = 500;

        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $isMonday = $currentDate->isMonday();
            $dateStr = $currentDate->toDateString();

            // Monday Schedule: ALL personnel
            if ($isMonday) {
                foreach ($eligibleUsers as $user) {
                    $keluarTime = $currentDate->copy()->setHour(6)->setMinute(rand(0, 30))->setSecond(rand(0, 59));
                    $masukTime = $currentDate->copy()->setHour(8)->setMinute(rand(15, 45))->setSecond(rand(0, 59));

                    $dataToInsert[] = [
                        'user_id' => $user->id,
                        'batrai_keluar' => 'Batrai A',
                        'batrai_masuk' => 'Batrai A',
                        'keluar' => $keluarTime->toDateTimeString(),
                        'masuk' => $masukTime->toDateTimeString(),
                        'created_at' => $dateStr . ' 06:00:00', // Set roughly to morning
                        'updated_at' => $dateStr . ' 08:45:00',
                    ];

                    if (count($dataToInsert) >= $batchSize) {
                        DB::table('gudang_senjata')->insert($dataToInsert);
                        $dataToInsert = [];
                    }
                }
            }

            // Daily Schedule: 2 groups of 12 members
            // We pick random members from the eligible list
            $dailyMembers = $eligibleUsers->random(min(24, $eligibleUsers->count()));

            $group1 = $dailyMembers->take(12);
            $group2 = $dailyMembers->slice(12, 12);

            // Group 1: 16:40 - 16:50
            foreach ($group1 as $user) {
                $keluarTime = $currentDate->copy()->setHour(16)->setMinute(rand(40, 43))->setSecond(rand(0, 59));
                $masukTime = $currentDate->copy()->setHour(16)->setMinute(rand(47, 50))->setSecond(rand(0, 59));

                $dataToInsert[] = [
                    'user_id' => $user->id,
                    'batrai_keluar' => 'Batrai A',
                    'batrai_masuk' => 'Batrai A',
                    'keluar' => $keluarTime->toDateTimeString(),
                    'masuk' => $masukTime->toDateTimeString(),
                    'created_at' => $dateStr . ' 16:40:00',
                    'updated_at' => $dateStr . ' 16:50:00',
                ];

                if (count($dataToInsert) >= $batchSize) {
                    DB::table('gudang_senjata')->insert($dataToInsert);
                    $dataToInsert = [];
                }
            }

            // Group 2: 17:20 - 17:30
            foreach ($group2 as $user) {
                $keluarTime = $currentDate->copy()->setHour(17)->setMinute(rand(20, 23))->setSecond(rand(0, 59));
                $masukTime = $currentDate->copy()->setHour(17)->setMinute(rand(27, 30))->setSecond(rand(0, 59));

                $dataToInsert[] = [
                    'user_id' => $user->id,
                    'batrai_keluar' => 'Batrai A',
                    'batrai_masuk' => 'Batrai A',
                    'keluar' => $keluarTime->toDateTimeString(),
                    'masuk' => $masukTime->toDateTimeString(),
                    'created_at' => $dateStr . ' 17:20:00',
                    'updated_at' => $dateStr . ' 17:30:00',
                ];

                if (count($dataToInsert) >= $batchSize) {
                    DB::table('gudang_senjata')->insert($dataToInsert);
                    $dataToInsert = [];
                }
            }

            $currentDate->addDay();
        }

        // Insert remaining data
        if (!empty($dataToInsert)) {
            DB::table('gudang_senjata')->insert($dataToInsert);
        }

        $this->command->info('Gudang Senjata seeder completed successfully.');
    }
}
