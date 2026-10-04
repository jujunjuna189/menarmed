<?php

namespace Tests\Feature;

use App\Models\ArtikelModel;
use App\Models\User;
use App\Support\ArtikelContent;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ArtikelTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        Schema::create('artikel', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi');
            $table->text('artikel');
            $table->timestamps();
        });
    }

    private function admin(): void
    {
        $this->actingAs(new User(['role' => 1]), 'web');
    }

    private function payload(): array
    {
        return ['judul' => 'Judul', 'deskripsi' => 'Ringkasan', 'artikel' => json_encode('<p>Isi artikel</p>')];
    }

    public function test_store_requires_admin(): void
    {
        $this->postJson('/api/artikel/store', $this->payload())->assertUnauthorized();
        $this->actingAs(new User(['role' => 2]), 'web');
        $this->postJson('/api/artikel/store', $this->payload())->assertForbidden();
        $this->assertSame(0, ArtikelModel::count());
    }

    public function test_validation_rejects_missing_fields_and_empty_content(): void
    {
        $this->admin();
        $this->postJson('/api/artikel/store', [])->assertUnprocessable()->assertJsonValidationErrors(['judul', 'deskripsi', 'artikel']);
        $this->postJson('/api/artikel/store', array_replace($this->payload(), ['artikel' => '"<p><br></p>"']))
            ->assertUnprocessable()->assertJsonValidationErrors('artikel');
    }

    public function test_create_update_and_missing_id(): void
    {
        $this->admin();
        $response = $this->postJson('/api/artikel/store', $this->payload())->assertOk();
        $id = $response->json('data.id');
        $this->postJson('/api/artikel/store', array_replace($this->payload(), ['artikel_id' => $id, 'judul' => 'Diubah']))->assertOk();
        $this->assertSame('Diubah', ArtikelModel::find($id)->judul);
        $this->postJson('/api/artikel/store', array_replace($this->payload(), ['artikel_id' => 999]))->assertNotFound();
        $this->assertSame(1, ArtikelModel::count());
    }

    public function test_sanitizes_html_and_preserves_editor_content(): void
    {
        $this->admin();
        $html = '<p style="text-align:center">Halo <strong>dunia</strong></p><script>alert(1)</script><img src="https://example.com/image.png" onerror="alert(1)"><a href="javascript:alert(1)">Link</a>';
        $this->postJson('/api/artikel/store', array_replace($this->payload(), ['artikel' => json_encode($html)]))->assertOk();
        $saved = json_decode(ArtikelModel::first()->artikel);
        $this->assertStringNotContainsString('<script', $saved);
        $this->assertStringNotContainsString('onerror', $saved);
        $this->assertStringNotContainsString('javascript:', $saved);
        $this->assertStringContainsString('<strong>dunia</strong>', $saved);
        $this->assertStringContainsString('text-align:center', $saved);
        $this->assertStringContainsString('data:image/png;base64,', ArtikelContent::sanitize('<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+aZ1sAAAAASUVORK5CYII=">'));
        $this->assertStringContainsString('youtube.com/embed/', ArtikelContent::sanitize('<iframe src="https://www.youtube.com/embed/test"></iframe>'));
        $blockedFrame = ArtikelContent::sanitize('<iframe src="https://example.com/evil"></iframe>');
        $this->assertStringNotContainsString('example.com', $blockedFrame);
        $this->assertTrue(ArtikelContent::isEmpty($blockedFrame));
        $this->assertStringNotContainsString('data:text/html', ArtikelContent::sanitize('<a href="data:text/html;base64,PHNjcmlwdD4=">Link</a>'));
    }

    public function test_preview_missing_article_returns_404_and_delete_works(): void
    {
        $this->get('/artikel/view?artikel_id=999')->assertNotFound();
        $this->admin();
        $article = ArtikelModel::create($this->payload());
        $this->delete(route('artikel.destroy', $article))->assertRedirect(route('artikel'));
        $this->assertSame(0, ArtikelModel::count());
    }
}
