<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ2EkstraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMapping = [
            ['tujuan' => 'Giat anggota Basipers Sosialisasi TWP ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat anggota Basiren rapat Sun LK ke Pusdikku', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Kasipers cek pers BP ke Divif 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Kasiter pratugas Swasembada ke Divif 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Kasiter pratugas Swasembada ke Pusdikpasus', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Danru Provost koordinasi ke Denpom Cirebon', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basipers pembuatan KTA ke Ajen Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Pengantaran Caraka pengantaran surat ke Divif 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Kasiintel Litpers Pers ke Yonarmed 13', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Kasiops Pengecekan Kesiapsiagaan ke Yonarmed 10', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Anggota keuangan sosialisasi SAKTI ke Makostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Bamu Pengajuan Munisi MKK ke Kodam III/SLW', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Dansiwat pengambilan Ban Accu ke Denpal Divif 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Danmenarmed Rapat Evaluasi ke Divisi 1 Kostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basipers Pengajuan SL ke Ditajenad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Anggota Staf Intel pengantaran produk ke Makostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Pasiops Rapat Kecabangan ke Pussenarmed', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bamu Pengantaran Selongsong ke Gudmurah', 'kendaraan' => 'Strada'],
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

        $startDate = Carbon::create(2025, 4, 1);
        $endDate = Carbon::create(2025, 6, 30);
        
        $this->command->info("Adding Extra Perizinan Kendaraan for Apr-Jun 2025...");

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

        $this->command->info('Extra Perizinan Kendaraan Q2 seeder completed successfully.');
    }
}
