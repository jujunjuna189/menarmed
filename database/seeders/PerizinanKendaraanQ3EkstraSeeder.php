<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ3EkstraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMapping = [
            ['tujuan' => 'Giat Basilog pengambilan sukucadang ke Denpal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Pengantaran jaga makostrad ke Makostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Dansiwat pengambilan Ban ke Gupusran', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basipers pengajuan SL ke Ditajenad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Kasiintel litpers personel ke Yonarmed 13', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Penataran Anggota Provost ke Denpom Cirebon', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basiintel pengantaran Lapbul dan Lapming ke Makostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basipers pengantaran Hanmin SL ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Dansiwat pengambilan Sucad ke Puspalad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bafurir pengambilan kaporlap ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bintara Keungan sosialisasi aplikasi ke Ditkuad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Mabes TNI', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Kasiops pengecekan kesiapsiagaan ke Yonarmed 13', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog pengambilan dukungan ban ke Denpal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bamu pengajuan munisi MKK ke Paldam III/Slw', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basipers pengantaran hanmin MPP ke Ajen Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog sosialisasi aplikasi Sisfolog ke Mabesad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basiren sosialisasi Aplikasi Simponi PNBP ke Divisi 1 Kostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Tajurlis staf pers pengantaran berkas Inaktif ke Ajen Divisi 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Pasilog rapat giat Hibah barang ke Makostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Dansiwat peminjaman tenda dan jaring samaran ke Yonarhanud 1', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bafurir pengambilan kaporlap ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Takom Peminjaman Repiter ke Hub Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Batisiops rapat bidang latihan ke Brigif 17', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Kasiintel rapat bidang intel ke Makostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Paku rapat ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Kasiops jaumed persiapan TNI AD Fair ke Monas', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Baang koordinasi perbaikan mobil ke Pal Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog pengantaran berkas Manset ke Zidam III/Slw', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Bafurir pengambilan kaporlap ke Bekangdam III/Slw', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basilog pengantaran tenda ke Monas', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bamu pengantaran surat MKK ke Gudmurah', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Pasilog pengecekan batas satuan ke Yonarmed 13', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basilog pengantaran perlengkapan TNI AD Fair ke Monas', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Dansiwat pengajuan sucad ke Paldam III/Slw', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Baang pengambilan Sucad Randis ke Denpal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Kasiops apel kesiapan TNI AD Fair ke Monas', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Takom peminjman HT ke Hub Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog pengantaran surat penghapusan rumah ke Zidam III/Slw', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bamu pengembalian selongsong ke Gudmurah', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basilog pengambilan Alkapsus ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basipers pengantaran Hanmin UKP ke Ajen Divisi 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Danruprov rapat tentang keselatan berkendara ke Denpom Cirebon', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Batisiops rapat giat latihan gabungan ke Yonif 330', 'kendaraan' => 'Matan'],
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

        $startDate = Carbon::create(2025, 7, 1);
        $endDate = Carbon::create(2025, 9, 30);
        
        $this->command->info("Adding Extra Perizinan Kendaraan for Jul-Sep 2025...");

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

        $this->command->info('Extra Perizinan Kendaraan Q3 seeder completed successfully.');
    }
}
