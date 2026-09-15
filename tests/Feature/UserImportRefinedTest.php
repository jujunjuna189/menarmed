<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Hash;

class UserImportRefinedTest extends TestCase
{
    // use RefreshDatabase; // Use only if you have a separate test DB

    public function test_import_users_with_all_fields()
    {
        Excel::fake();
        
        $admin = User::factory()->create(['role' => 1]);

        // We can't easily mock the content of the file for Excel import validation 
        // without actually creating a file or using complex mocking.
        // However, we can assert that the import logic itself handles the data correctly
        // by manually instantiating the Import class and calling model().
        
        $import = new UsersImport();
        
        $row = [
            'nama' => 'Test Lengkap',
            'email' => 'lengkap@test.com',
            'password' => 'password123',
            'role_id' => 3,
            'pangkat' => 'Serda',
            'korp' => 'CPL',
            'satuan' => 'Satuan A',
            'jabatan' => 'Anggota',
            'tempat_lahir' => 'Jakarta',
            'tgl_lahir' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel('1990-01-01'), // Excel stores dates as numbers
            'agama' => 'Islam',
            'gol_darah' => 'O',
            'sumber_pa' => 'Akamil',
            'senjata' => 'M16',
        ];

        $user = $import->model($row);

        $this->assertEquals('Test Lengkap', $user->name);
        $this->assertEquals('lengkap@test.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertEquals(3, $user->role);
        $this->assertEquals('Serda', $user->pangkat);
        $this->assertEquals('CPL', $user->korp);
        $this->assertEquals('Satuan A', $user->satuan);
        $this->assertEquals('Anggota', $user->jabatan);
        $this->assertEquals('Jakarta', $user->tempat_lahir);
        
        // Note: The date transformation happens in the model() method provided in the previous step
        // We need to verify if the logic inside model() correctly handles the date.
        // Since we are calling model() directly, we are testing the logic.
        $this->assertEquals('1990-01-01', $user->tgl_lahir); 
        
        $this->assertEquals('Islam', $user->agama);
        $this->assertEquals('O', $user->gol_darah);
        $this->assertEquals('Akamil', $user->sumber_pa);
        $this->assertEquals('M16', $user->senjata);
    }
}
