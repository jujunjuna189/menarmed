<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ4EkstraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMapping = [
            ['tujuan' => 'Giat Takkom pengambilan perbaikan Sound ke Denhub Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basilog peminjaman tenda dan jaring samaran ke Yonarhanud 1', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basipers pengantaran surat ke Ditajenad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog pengantaran perlengkapan HUT TNI ke Monas', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Baang pengambilan Ban ke Denpal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Kasipers rapat evaluasi HUT TNI ke Monas', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Bapen Sosialisasi Dron ke Hub Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Penjemputan personel setelah HUT TNI ke Brigif 17', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bintara Keuangan pengantaran wabku ke Vermat Itjenad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bamu pengajuan Munisi MKK ke Gudmurah', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basilog Pengambilan dukungan Kaporlap ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basipers pengambilan KPI ke Ajen Divisi 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Paku rapat evaluasi budang keuangan ke Makostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Pasipers pendampingan asistensi divisi ke Yonarmed 13', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Pasipers sosialisasi TWP ke Yonarmed 10', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Bapen penataran bidang penerangan ke Mabesad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Tajurlis Log pengambilan dukugan sparepart ke Puspalad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Baurtrajuang pengambilan skep ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Danruprov sosialisasi PAM ke Denpom Cirebon', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Kasiren rapat ZI ke Mabes TNI', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat pengantaran jaga Makostrad ke Makostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Kasiintel pengecekan administrasi litpers ke Yonarmed 10', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Basilog pengajuan kendaraan ke Paldam III/Slw', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basipers pengambilan skep ke Ajen Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basilog rapat evaluasi bidang log ke Puspalad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Tajurlis Log pengambilan dukungan Ransum ke Pusbekangad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bakkes sosialisasi bidang kesehatan ke Puskesad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Takkom pengambilan perbaikan Sound dan HT ke Hub Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Kasiops rapat bidang ops ke Mabesad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Dansimin rapat bidang pembinaan personel ke Ajendam III/Slw', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Dansiwat pengajuan Tenda Kendaraan ke Pal Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basipers pengantaran surat dik sesko ke Ditajenad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Danmen rapat evaluasi progja ke Divisi 1 Kostrad', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Babekal Kelas II pengajuan Alsatri ke Bekang Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Bakkes pengambilan duk alkes ke Gudkesrah', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Takkom peminjaman Reapiter latihan ke Denhub Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basilog pengajuan ADK kendaraan ke Gudpalrah', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Dansiwat pengiriman berkas PNBP ke Zidam III/Slw', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Kasiops rapat bidang ops latgab ke Brigif 17', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Danmen pengecekan kesiapsiagaan ke Yonarmed 13', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Baang pengajuan perbaikan Mobil ke Pal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Dansikes pengambilan bujuk kesehatan ke Pusdikkes', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Tajurlis Pers penataran operator Sisfopers ke Rindam III/Slw', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat pengantaran jaga makostrad ke Makostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bafurir pengambilan dukungan ransum ke Bekang Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Pasilog rapat evaluasi bidang log ke Divisi 1 kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Pasipers rapat bidang dik dan spers ke Ditajenad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Paku rapat rekon LK ke Ditkuad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Divisi 1 Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Basilog pengambilan sucad ke Puspalad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Pasilat sosialisasi bid ops ke Pusdik Armed', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Danmen pengecekan pasukan ke Yonarmed 10', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Tajurlis pers pengantaran berkas ke Ajen Divisi 1 Kostrad', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Bafurier pengajuan kaporlap ke Bekang Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Bamu pengantaran selongsong ke Paldam III/Slw', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Takkes penataran bidang kesehatan ke Pusdikkes', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Dansimin pengantaran berkas ke Makostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Dansikkes pengambilan duk Alkes ke Gudkesrah', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Pasiter sosialisasi ketahanan pangan ke Yonarmed 13', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Ba Bekal penganjuan pemeriksaan Randis ke Denpal Divisi 1 Kostrad', 'kendaraan' => 'NPS'],
            ['tujuan' => 'Giat Basilog pengajuan ADK kendaraan ke Gudpalrah', 'kendaraan' => 'Strada'],
            ['tujuan' => 'Giat Pasilat evaluasi bidang ops ke Pssenarmed', 'kendaraan' => 'Matan'],
            ['tujuan' => 'Giat Basipers pengantaran berkas pendidikan ke Ajen Kostrad', 'kendaraan' => 'OZ'],
            ['tujuan' => 'Giat Danmen rapat evaluasi semester II ke Mabesad', 'kendaraan' => 'Strada'],
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

        $startDate = Carbon::create(2025, 10, 1);
        $endDate = Carbon::create(2025, 12, 31);
        
        $this->command->info("Adding Extra Perizinan Kendaraan for Oct-Dec 2025...");

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

        $this->command->info('Extra Perizinan Kendaraan Q4 seeder completed successfully.');
    }
}
