<?php

namespace Tests\Feature;

use App\Models\ELearningModel;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ELearningActionsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('e_learning', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('path');
            $table->timestamps();
        });
    }

    public function test_admin_can_edit_and_delete_without_creating_duplicates(): void
    {
        $this->actingAs(new User(['role' => 1]));
        $learning = ELearningModel::create(['judul' => 'Awal', 'path' => 'https://example.com']);
        $this->patchJson(route('e-learning.update', $learning->id), ['judul' => 'Diubah', 'path' => 'https://example.com/materi'])->assertOk();
        $this->assertSame('Diubah', $learning->fresh()->judul);
        $this->assertSame(1, ELearningModel::count());
        $this->patchJson(route('e-learning.update', $learning->id), ['judul' => '', 'path' => 'javascript:alert(1)'])
            ->assertUnprocessable()->assertJsonValidationErrors(['judul', 'path']);
        $this->deleteJson(route('e-learning.destroy', $learning->id))->assertOk();
        $this->assertSame(0, ELearningModel::count());
        $this->patchJson(route('e-learning.update', $learning->id), ['judul' => 'Hilang', 'path' => 'https://example.com'])->assertNotFound();
    }

    public function test_guest_cannot_edit_or_delete(): void
    {
        $learning = ELearningModel::create(['judul' => 'Awal', 'path' => 'https://example.com']);
        $this->patchJson(route('e-learning.update', $learning->id), [])->assertUnauthorized();
        $this->deleteJson(route('e-learning.destroy', $learning->id))->assertUnauthorized();
        $this->assertSame(1, ELearningModel::count());
    }
}
