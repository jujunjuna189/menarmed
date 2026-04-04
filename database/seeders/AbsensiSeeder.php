<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AbsensiModel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AbsensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Configuration
        $year = 2025;
        $users = User::where('role', '!=', 1)->pluck('id')->toArray(); // Exclude admin if needed, or include all

        if (empty($users)) {
            $this->command->info("No users found to generate absensi.");
            return;
        }

        $startDate = Carbon::create($year, 1, 1);
        $endDate = Carbon::create(2026, 4, 3);
        $period = CarbonPeriod::create($startDate, $endDate);

        $this->command->info("Generating Absensi for year $year...");

        // Pre-calculate long-term statuses to ensure consistency
        // Map: 'YYYY-MM-DD' => [user_id => status]
        $dailyAssignments = [];

        // 1. DIK: 2 users every 3 months
        // Quarters: Jan-Mar, Apr-Jun, Jul-Sep, Oct-Dec
        for ($q = 1; $q <= 4; $q++) {
            $quarterStart = Carbon::create($year, ($q - 1) * 3 + 1, 1);
            $quarterEnd = $quarterStart->copy()->addMonths(3)->subDay();

            $picked = $this->pickRandom($users, 2);
            $this->assignPeriod($dailyAssignments, $picked, 'DIK', $quarterStart, $quarterEnd);
        }

        // 2. CUTI: 6 users every month
        // 3. DL: 5 users every month
        for ($m = 1; $m <= 12; $m++) {
            $monthStart = Carbon::create($year, $m, 1);
            $monthEnd = $monthStart->copy()->endOfMonth();

            // Cuti
            $pickedCuti = $this->pickRandom($users, 6);
            $this->assignPeriod($dailyAssignments, $pickedCuti, 'CUTI', $monthStart, $monthEnd);

            // DL
            $pickedDL = $this->pickRandom($users, 5);
            $this->assignPeriod($dailyAssignments, $pickedDL, 'DL', $monthStart, $monthEnd);
        }

        // 4. SAKIT: 2 users every week
        // Iterate weeks
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $weekStart = $currentDate->copy()->startOfWeek();
            $weekEnd = $currentDate->copy()->endOfWeek();

            // Adjust to year boundaries
            if ($weekStart->year < $year)
                $weekStart = Carbon::create($year, 1, 1);
            if ($weekEnd->year > $year)
                $weekEnd = Carbon::create($year, 12, 31);

            $pickedSakit = $this->pickRandom($users, 2);
            $this->assignPeriod($dailyAssignments, $pickedSakit, 'SAKIT', $weekStart, $weekEnd);

            $currentDate->addWeek();
        }

        // Generate Daily Data
        $dataToInsert = [];
        $batchSize = 1000; // Insert in chunks

        foreach ($period as $date) {
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            $dateString = $date->format('Y-m-d');

            // ... (rest of logic for identified users)

            // Identify who is already assigned (Long Term)
            $assignedToday = $dailyAssignments[$dateString] ?? [];
            $alreadyAssignedIds = array_keys($assignedToday);

            // Available users for daily shuffle
            $availableUsers = array_diff($users, $alreadyAssignedIds);
            shuffle($availableUsers);

            // Daily Quotas
            // DD: 12, DK: 3, BP: 6, Ijin: 2
            $dailyQuotas = [
                'DD' => 12,
                'DK' => 3,
                'BP' => 6,
                'IJIN' => 2
            ];

            $dailyStatus = [];

            foreach ($dailyQuotas as $status => $count) {
                $picked = array_splice($availableUsers, 0, $count);
                foreach ($picked as $uid) {
                    $dailyStatus[$uid] = $status;
                }
            }

            // Remainder -> HADIR
            foreach ($availableUsers as $uid) {
                $dailyStatus[$uid] = 'HADIR'; // or 'Hadir' title case if preferred
            }

            // Merge Long Term + Daily
            $finalStatus = $dailyStatus + $assignedToday;

            // Prepare Insert Data
            foreach ($finalStatus as $uid => $ket) {
                // Random time between 08:00 and 10:00
                $randomTime = $date->copy()->setTime(rand(8, 9), rand(0, 59), rand(0, 59))->format('Y-m-d H:i:s');

                $dataToInsert[] = [
                    'user_id' => $uid,
                    'ket' => strtoupper($ket), // Ensure uppercase
                    'latitude' => '-6.200000', // Dummy coords
                    'longitude' => '106.816666',
                    'created_at' => $randomTime,
                    'updated_at' => $randomTime,
                ];

                if (count($dataToInsert) >= $batchSize) {
                    AbsensiModel::insert($dataToInsert);
                    $dataToInsert = [];
                }
            }
        }

        // Insert remaining
        if (!empty($dataToInsert)) {
            AbsensiModel::insert($dataToInsert);
        }

        $this->command->info("Absensi data generated successfully.");
    }

    private function pickRandom($allUsers, $count)
    {
        // Simple random pick. 
        // Note: In a real consistent schedule, we might want to ensure 'Cuti' doesn't overlap 'Dik', etc.
        // But for this request "random per user", naive random is acceptable. 
        // Overlaps in logic (e.g. someone picked for Cuti AND DL in same month) are possible if we pick independently.
        // However, the `assignPeriod` logic below overwrites.
        // For better results, one should exclude people already picked for concurrent long-term statuses.
        // But simpler logic is to just overwrite or let the last one win. 
        // My implementation of `dailyAssignments` uses user_id as key, so last assignment wins.
        // To be safer, we should check availability, but for data dumping this is usually fine.

        $keys = array_rand($allUsers, min($count, count($allUsers)));
        if (!is_array($keys))
            $keys = [$keys];

        $picked = [];
        foreach ($keys as $key) {
            $picked[] = $allUsers[$key];
        }
        return $picked;
    }

    private function assignPeriod(&$schedule, $userIds, $status, $start, $end)
    {
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            $d = $date->format('Y-m-d');
            foreach ($userIds as $uid) {
                // If already assigned a status for this day (e.g. DIK overwrites Cuti?), 
                // we can decide priority or just overwrite. 
                // Let's assume DIK > Cuti > DL > Sakit.
                // Since we iterate sequentially in run(), later assignments overwrite earlier ones.
                // Order in run(): DIK -> Cuti -> DL -> Sakit.
                // So Sakit might overwrite DIK effectively if picked.
                // Ideally we should check `isset`.
                if (!isset($schedule[$d][$uid])) {
                    $schedule[$d][$uid] = $status;
                }
            }
        }
    }
}
