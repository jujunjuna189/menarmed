<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerizinanKendaraanModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kendaraan = ['Kijang', 'L 300', 'Feroza'];
        $tujuan = [
            'Giat Pengantaran Surat Caraka',
            'Giat kasi ops dan dandenma rapat',
            'Giat doketer melaksanakan pratugas',
            'Giat anggota nasrani ibadah danal & tahun baru',
            'Giat Basilog sosialisasi aplikasi sehati',
            'Giat anggota ranprogar rapat halaman 3 Dipa',
            'Giat Kasi ops rapat evaluasi bid lat',
            'Giat kasi intel pengekan litspers',
            'Giat kasi pers rapat ZI WBK',
            'Giat Kasipers rapat Zi WBK',
            'Giat Pasipers dan anggota korsik lomba korsik',
            'Giat Pasipers Pembuatan KTA',
            'Giat dansiwat perpanjang BNKB',
            'Giat anggota Ren Log dan Ku Reviuw LK',
            'Giat Kasipers Pengecekan Apel BP',
            'Giat anggota renprogar rapat rakernis',
            'Giat anggota keuangan sosialisasi aplikasi sakti',
            'Giat Caraka pengantaran surat',
            'Giat densiwat pengajuan nominatif kaporlap',
            'Giat Kasiops Pengecekan Kesiapsiagaan',
            'Giat Kasipers Pengecekan Apel BP',
            'Giat anggota ren sosialisasi aplikasi sisforen',
            'Giat Basipers Pembuatan KPI',
            'Giat Basiops Penyamaan Data Ecco',
            'Giat Dansiren Rapat PPPA',
            'Giat anggota staf ops pengantaran produk',
            'Giat dasilog pengajuan sparepat',
            'Giat anggota basilog pengajuan munisi',
            'Giat pasilter cek kesiapan MBG',
            'Giat dansiwat pengajuan sparpat',
            'Giat pasilog rapat hibah barang',
            'Giat basiren rapat ZI'
        ];

        $excludedRanks = [
            'Kolonel Arm',
            'Mayor Arm',
            'Kapten Arm',
            'Kapten Ckm'
        ];

        $users = User::whereNotIn('pangkat', $excludedRanks)->pluck('id')->toArray();
        if (empty($users)) {
            $this->command->warn('No users found to seed Perizinan Kendaraan.');
            return;
        }

        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2025, 3, 31);
        
        $currentDate = $startDate->copy();
        $this->command->info("Seeding Perizinan Kendaraan from 1 Jan 2025 to 31 Mar 2025...");

        while ($currentDate->lte($endDate)) {
            // Find the start and end of the current week (Monday to Sunday)
            $weekStart = $currentDate->copy()->startOfWeek();
            $weekEnd = $currentDate->copy()->endOfWeek();
            
            // Adjust week limits to the overall start/end dates
            $effectiveStart = $weekStart->lt($startDate) ? $startDate : $weekStart;
            $effectiveEnd = $weekEnd->gt($endDate) ? $endDate : $weekEnd;

            // Collect all days in this week's effective range
            $daysInWeek = [];
            $tempDate = $effectiveStart->copy();
            while ($tempDate->lte($effectiveEnd)) {
                $daysInWeek[] = $tempDate->copy();
                $tempDate->addDay();
            }

            // Randomly select 6 occurrences per week (some days might have more than 1)
            for ($i = 0; $i < 6; $i++) {
                if (empty($daysInWeek)) break;
                
                $chosenDay = $daysInWeek[array_rand($daysInWeek)];
                
                $keluarHour = rand(7, 10);
                $durationHours = rand(2, 6);
                
                $keluar = $chosenDay->copy()->setHour($keluarHour)->setMinute(rand(0, 59))->setSecond(0);
                $masuk = $keluar->copy()->addHours($durationHours)->addMinutes(rand(0, 59));

                DB::table('perizinan_kendaraan')->insert([
                    'user_id' => $users[array_rand($users)],
                    'keluar' => $keluar->toDateTimeString(),
                    'masuk' => $masuk->toDateTimeString(),
                    'tujuan' => $tujuan[array_rand($tujuan)],
                    'jenis_kendaraan' => $kendaraan[array_rand($kendaraan)],
                    'created_at' => $keluar->toDateTimeString(),
                    'updated_at' => $masuk->toDateTimeString(),
                ]);
            }

            $currentDate->addWeek();
        }

        $this->command->info('Perizinan Kendaraan seeder completed successfully.');
    }
}
