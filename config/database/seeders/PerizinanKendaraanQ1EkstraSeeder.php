<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ1EkstraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMapping = [
            ['tujuan' => 'Giat Pengurusan Manset ke Zidam III/SLW', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Pasilog Pengecekan Batas Satuan ke Yonarmed 10 Bogor', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Dansiwat Pengajuan Sparepart Radis ke Paldam III/SLW', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Danmenarmed 1 Rapat ke Divif 1 Kostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Bamu Penganataran Selongsong Ke Gudmurah ke Banjaran', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Anggota Staf Ren Sosialisai Sisforen ke Divif 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Anggota Staf OPS Rapat Makostrad ke Jakarta', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Pengantaran Anggota TC Menembak ke Divif 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog Pengambilan Sucad ke Puspalad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basilog Pengambilan Kaporlap ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Danmenarmed 1 Rapat ke Divif 1 Kostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Bamu Pengambilan TPM Munisi ke Kodam III/SLW', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Anggota Staf Intel Pengantaran Produk ke Divif 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Anggota Staf Pers Pengantaran Berkas DIK ke Sesko AD', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bamu Pengantaran Selongsong ke Gudmurah', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basilog Pengantaran Motor Dan Mobil B-10 ke Paldam III/SLW', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Anggota Staf Pers Pengajuan SL ke Ditajenad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Pengantaran Petembak Menarmed 1 ke Divif 1 Kostrad', 'kendaraan' => 'NPS'],
        ];

        $excludedRanks = [
            'Kolonel Arm',
            'Mayor Arm',
            'Kapten Arm',
            'Kapten Ckm'
        ];

        $users = User::whereNotIn('pangkat', $excludedRanks)->pluck('id')->toArray();
        if (empty($users)) {
            $this->command->warn('No eligible users found to seed Perizinan Kendaraan.');
            return;
        }

        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 3, 31);
        
        $this->command->info("Adding Extra Perizinan Kendaraan for Jan-Mar 2025...");

        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $weekStart = $currentDate->copy()->startOfWeek();
            $weekEnd = $currentDate->copy()->endOfWeek();
            
            $effectiveStart = $weekStart->lt($startDate) ? $startDate : $weekStart;
            $effectiveEnd = $weekEnd->gt($endDate) ? $endDate : $weekEnd;

            $daysInWeek = [];
            $tempDate = $effectiveStart->copy();
            while ($tempDate->lte($effectiveEnd)) {
                $daysInWeek[] = $tempDate->copy();
                $tempDate->addDay();
            }

            for ($i = 0; $i < 6; $i++) {
                if (empty($daysInWeek)) break;
                
                $chosenDay = $daysInWeek[array_rand($daysInWeek)];
                $chosenGiat = $dataMapping[array_rand($dataMapping)];
                
                $keluarHour = rand(7, 10);
                $durationHours = rand(2, 6);
                
                $keluar = $chosenDay->copy()->setHour($keluarHour)->setMinute(rand(0, 59))->setSecond(0);
                $masuk = $keluar->copy()->addHours($durationHours)->addMinutes(rand(0, 59));

                DB::table('perizinan_kendaraan')->insert([
                    'user_id' => $users[array_rand($users)],
                    'keluar' => $keluar->toDateTimeString(),
                    'masuk' => $masuk->toDateTimeString(),
                    'tujuan' => $chosenGiat['tujuan'],
                    'jenis_kendaraan' => $chosenGiat['kendaraan'],
                    'created_at' => $keluar->toDateTimeString(),
                    'updated_at' => $masuk->toDateTimeString(),
                ]);
            }

            $currentDate->addWeek();
        }

        $this->command->info('Extra Perizinan Kendaraan Q1 seeder completed successfully.');
    }
}
