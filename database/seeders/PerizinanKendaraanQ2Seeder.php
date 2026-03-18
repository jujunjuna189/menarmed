<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerizinanKendaraanModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ2Seeder extends Seeder
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
            'Giat dansiren rapat progja',
            'Giat bamu pengajuan munisi',
            'Giat basipers pembuatan KPI',
            'Giat kasiops tinjau medan',
            'Giat kasiintel rapat',
            'Giat basipers sosialisasi apk sisfopers',
            'Giat anggota ranprogar bimtek apk sisforen',
            'Giat pasimat cek batas satuan/pangkalan',
            'Giat kasipers rapat ZI WBK',
            'Giat densirat rapat evaluasi',
            'Giat basilog pengurusan manset',
            'Giat bamu pengembalian selongsong',
            'Giat pasimas cek batas satuan/pangkalan',
            'Paldam III/SLW',
            'Giat kasiops tinjau medan',
            'Giat basiren rapat evaluasi progja',
            'Giat basiops sosialisasi apk ECCO',
            'Giat pasimas rapat hibah barang',
            'Giat pasiops rapat',
            'Giat anggota ZI rapat',
            'Giat kasiintel pengecekan personel litpers',
            'Giat kasiops rapat bid latihan',
            'Giat basipers pembuatan KTA',
            'Giat pasipers sosialisasi bid lat',
            'Giat dansiwat pembuatan BNKB',
            'Giat pasiops rapat bid lat',
            'Giat caraka pengantaran produk',
            'Giat anggota sosialisasi pembutan LK'
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
        
        $currentDate = $startDate->copy();
        $this->command->info("Seeding Perizinan Kendaraan from 1 Apr 2025 to 30 Jun 2025...");

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

        $this->command->info('Perizinan Kendaraan Q2 seeder completed successfully.');
    }
}
