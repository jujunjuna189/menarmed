<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AbsensiModel;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use App\Imports\AbsensiImport;

class AbsensiImportTest extends TestCase
{
    // use RefreshDatabase; 

    public function test_download_template_absensi()
    {
        $admin = User::factory()->create(['role' => 1]); 

        $response = $this->actingAs($admin)
                         ->get(route('absensi.template'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=template_import_absensi.xlsx');
    }

    public function test_import_absensi()
    {
        Excel::fake();
        
        $admin = User::factory()->create(['role' => 1]);

        $file = UploadedFile::fake()->create('absensi.xlsx');

        $response = $this->actingAs($admin)
                         ->post(route('absensi.import'), [
                             'file' => $file,
                         ]);

        $response->assertStatus(200);
        
        Excel::assertImported('absensi.xlsx', function(AbsensiImport $import) {
            return true;
        });
    }

    public function test_absensi_import_logic()
    {
        // Test logic mapping
        $import = new AbsensiImport();
        
        $row = [
            'user_id' => 100,
            'keterangan' => 'HADIR',
            'latitude' => '-6.200',
            'longitude' => '106.800',
            'tanggal' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel('2023-10-27 08:00:00'),
        ];

        $absensi = $import->model($row);

        $this->assertEquals(100, $absensi->user_id);
        $this->assertEquals('HADIR', $absensi->ket);
        $this->assertEquals('-6.200', $absensi->latitude);
        $this->assertEquals('106.800', $absensi->longitude);
        $this->assertEquals('2023-10-27 08:00:00', $absensi->created_at);
    }
}
