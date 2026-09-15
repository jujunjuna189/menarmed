<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use App\Imports\UsersImport;

class UserImportTest extends TestCase
{
    // use RefreshDatabase; 

    public function test_download_template()
    {
        $admin = User::factory()->create(['role' => 1]); // Adjust role logic as needed

        $response = $this->actingAs($admin)
                         ->get(route('pengguna.template'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition', 'attachment; filename=template_import_user.xlsx');
    }

    public function test_import_users()
    {
        Excel::fake();
        
        $admin = User::factory()->create(['role' => 1]);

        $file = UploadedFile::fake()->create('users.xlsx');

        $response = $this->actingAs($admin)
                         ->post(route('pengguna.import'), [
                             'file' => $file,
                         ]);

        $response->assertStatus(200);
        
        Excel::assertImported('users.xlsx', function(UsersImport $import) {
            return true;
        });
    }
}
