<?php

namespace Tests\Feature;

use App\Exports\MonthlyAbsensiExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class Php84CompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_existing_bcrypt_password_can_log_in(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password123'), 'role' => 1]);

        $this->post('/login', ['email' => $user->email, 'password' => 'password123'])
            ->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_persisted_sanctum_token_authenticates_api_requests(): void
    {
        $user = User::factory()->create(['role' => 3]);
        $token = $user->createToken('compatibility', ['*'], now()->addHour());

        $this->assertNotNull($token->accessToken->fresh()->expires_at);
        $this->withToken($token->plainTextToken)->getJson('/api/user')
            ->assertOk()->assertJsonPath('id', $user->id)->assertJsonMissingPath('password');
    }

    public function test_monthly_excel_and_pdf_are_generated_by_real_libraries(): void
    {
        User::factory()->create(['name' => 'Personel Uji', 'role' => 3]);
        $month = Carbon::createFromFormat('!Y-m', '2024-02');
        $export = new MonthlyAbsensiExport($month, '');

        $xlsx = Excel::raw($export, \Maatwebsite\Excel\Excel::XLSX);
        $this->assertStringStartsWith('PK', $xlsx);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rekap_data.absensi_monthly_pdf', [
            'month' => $month, 'rows' => $export->collection(), 'headings' => $export->headings(),
        ])->setPaper('a3', 'landscape')->output();
        $this->assertStringStartsWith('%PDF-', $pdf);
    }
}
