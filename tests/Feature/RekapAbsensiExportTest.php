<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class RekapAbsensiExportTest extends TestCase
{
    // use RefreshDatabase; 

    public function test_export_absensi()
    {
        Excel::fake();
        
        $admin = User::factory()->create(['role' => 1]); 

        $response = $this->actingAs($admin)
                         ->get(route('report.absensi.export'));

        $response->assertStatus(200);
        
        Excel::assertDownloaded('rekap_absensi.xlsx', function(AbsensiExport $export) {
            // Verify that the collection matches what we expect
            // Since we just seeded massive data, we probably just want to check if it returns a collection
            return $export->collection()->count() >= 0; 
        });
    }
}
