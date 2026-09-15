<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\PerizinanKendaraanModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PerizinanKendaraanQ3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataMapping = [
            ['tujuan' => 'Giat Caraka pengantaran surat ke Makostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Dansiwat pengajuan Sparepart ke Paldam III/SLW', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basipers pengambilan blangko KTA ke Ajen Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Bamu pengembalian selongsong ke Gudmurah', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Pasilog pengecekan batas satuan dan pangkalan ke Yonarmed 13', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basilog pengajuan Manset ke Zidam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasilog pengecekan batas satuan dan pangkalan ke Yonarmed 10', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Forier koordinasi pengajuan Alkapsatlap ke Bekang Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat batisiops tar hirbak ke Rindam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Danru Provost koordinasi pengamanan ke Denpom Cirebon', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basilog pengajuan sparepart latihan Latbakjatrat ke Gudpusran', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Baang perpanjang BNKB ke Pal Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Tajurlis Pers Juan SL ke Ajendam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basiren Rapat ZI ke Makostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basilog pengajuan kebutuhan Alsatri ke Pusbekangad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasiops sosialisasi bidang latihan ke Mabes TNI', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Tajurlis sosialisasi E Katalog ke Mabesad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Kasipers apel pengecekan pers BP ke Divisi 1 Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Dansikes sosialisasi pencegahan penyakit ke Kesdam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Dansiwat koordinasi dukungan Munisi MKB ke Puspalad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiops sosialisasi aplikasi Ecco ke Kodiklatad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Anggota denma Tar Hirbak ke Brigif 17', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat anggota kom perbaikan HT ke Hub Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basilog sosialisasi aplikasi Sakti ke Makostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiren rapat paparan Skala Prioritas ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Paku sosialisasi My Intres ke Pusdikku', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Kasiops mendampingi pengecekan kesiapsigaan ke Yonarmed 13', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Kasiintel pengecekan litpers satuan ke Yonarmed 10', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansimin sosialisasi aplikasi sisfopers ke Ditajenad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Bamu pengantaran renlat munisi TW ke Kodam III', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat basilog sosialisasi aplikasi Sehati ke Mabesad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Bakkes pengajuan obat ke Gudkesrah', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Takkes sosialisasi medical exercise ke Pusdikkes', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansiwat pengajuan kaporlap ke Bekang Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basilog pengajuan bentuk 10 ke Bekangdam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basipers pengambilan Skep Jabatan ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Batuud sosialisasi aplikasi gaji ke Ditkuad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansiren rapat ZI ke Mabes TNI', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Dansiwat singkronisasi Hibah ke Pusbekangad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basipers usul pembuatan KPI ke Ajen Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat bamu koordinasi pengajuan munisi MKB ke Mabesad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basilog pengambilan BNKB ke Palkostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Dansikkes pengambilan dukungan obat ke Gupus II Puskesad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Baang koordinasi perbaikan kendaraan ke Bengpuspal Puspalad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Ba Rikdkk bel peg sosialisai aplikasi Sakti ke Pusdikku', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basilog pengambilan dukungan Accu ke Denpal Divisi', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansiwat pengambilan persyaratan Manset ke Zidam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Makostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Takom peminjaman Sound System giat latihan ke Hub Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basipers rapat giat pendidikan ke Ajendam III', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Kasiops tinjau medan ke Pusdikpassus', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Bamu pengajuan munisi MKK ke Paldam III', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasilog rapat pengajuan kendaraan ke Puspalad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Divisi 1 Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiops Tar Hirbak ke Rindam III', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basiintel rapat giat PAM ke Makostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasiops rapat kecabangan ke Pussenarmed', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basipers pengajuan Dikbangspers ke Kodam III', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Mabesad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basilog pengambilan dukungan Alsatri ke Bekangdam III', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Talog pengambilan Ransum ke Bekang Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Dansiwat koordinasi pengajuan Tenda Lapangan ke Pusbekangad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Kasipers pengecekan apel BP ke Divisi 1 Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basilog pengajuan Sucad Randis ke Pal Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Makostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basilog pengambilan surat bentuk 10 ke Paldam III/Slw', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Pasiter pengecekan ketahanan pangan ke Yonarmed 10', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Mabesad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basipers pengambilan KEP UKP ke Divisi 1 Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Pasiops tinjau medan ke Menlatpur', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiintel pengantaran lapbul dan lapming ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Bintara keuangan rapat evaluasi ke Makostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Pasilog giat pengecekan pangkalan ke Yonarmed 13', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Dansimin pengantaran surat pendidikan ke Kodam III/Slw', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Tajurlis Ops sosialisasi aplikasi ecco ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Tajurlis Log sosialisasi aplikasi E Katalog ke Makostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Baang pengambilan sparepart ke Pal Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Kasiter kesiapan pengeboran air bersih ke Yonarmed 10', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasiops rapat kecabagan ke Kodiklatad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Bakes sosialisasi kesehatan militer ke Puskesad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansiter rapat giat air bersih ke Mabesad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basipers penganjuan SL ke Ajen Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Baang koordinasi perbaikan mobil ke Paldam III/Slw', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Tamtama Radio perbaikan Sound System ke Denhub Divisi 1', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Pussenarmed', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Tajurlis Log pengajuan surat bentuk 10 ke Zidam III/Slw', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Caraka Pengantaran surat ke Ditajenad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basilog pengantaran surat Rikmat ke Bengpuspal', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Batih penataran penyelenggaraan BDM ke Rindam III/Slw', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Makostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basipers pengambilan SL ke Ajendam III/Slw', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Pasiops tinjau medan ke Menlatpur', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansimin pengantaran Hanmin MPP ke Ajen Divisi', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basilog pengambilan foto copy sertifikat ke Zidam III/Slw', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Kasiops menghadiri penutupan Taipur ke Menlatpur', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansiintel melaksanakan pengecekan litpers ke Yonarmed 10', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Baang pembuatan BNKB baru ke Pal Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Paku sosialisasi aplikasi ke Ditkuad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Bintara keuangan pengantaran wabku ke Vermat Itjenad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Pasipers pengecekan personel BP ke Divisi 1 Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Dansimin pengantaran Hanmin Seskoad ke Kodam III/Slw', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Dansimin pengambilan KPI ke Ajen Kostrad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiren sosialisasi RB ke Mabes TNI', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Bakes mengambil dukungan alkes ke Gudkesrah', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Pasilog surat juan sparepart meriam ke Bengpuspal', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Bagudjat pengantaran peminjaman senjata ke Rindam III/Slw', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Bakkes pengambilan bujuk kesehatan ke Pusdikkes', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Kasiintel pengecekan litpers personel ke Yonarmed 13', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basipers pengantaran Hanmin UKP ke Divisi 1 Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Takom pengajuan bentuk 10 ke Hubdam III/Slw', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Caraka pengantaran surat ke Mabes TNI', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Bafurier pengambilan dukungan kaporlap ke Bekang Kostrad', 'kendaraan' => 'L 300'],
            ['tujuan' => 'Giat Basilog sosialisasi aplikasi Sehati ke Pusbekangad', 'kendaraan' => 'Feroza'],
            ['tujuan' => 'Giat Basiintel pengiriman Lapbul dan Lapming ke Divisi 1 Kostrad', 'kendaraan' => 'Kijang'],
            ['tujuan' => 'Giat Basiops pengambilan bujuk alutsista ke Pusdikpasus', 'kendaraan' => 'L 300'],
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
        
        $currentDate = $startDate->copy();
        $this->command->info("Seeding Perizinan Kendaraan from 1 Jul 2025 to 30 Sep 2025...");

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

        $this->command->info('Perizinan Kendaraan Q3 seeder completed successfully.');
    }
}
