<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Translation;
use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationExportTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(TranslationRepository::class);
    }

    public function test_export_endpoint_returns_json_stream_successfully()
    {
        Translation::factory()->hasContents(3)->count(10)->create();

        $response = $this->get('/api/export-translations');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Accept', 'application/json');

        $body = $response->streamedContent();
        $this->assertStringStartsWith('[', $body);
        $this->assertStringEndsWith(']', trim($body));
    }

    public function test_export_is_fast_enough()
    {
        Translation::factory()->hasContents(3)->count(10)->create();

        $start = microtime(true);

        $response = $this->get('/api/export-translations');

        $duration = microtime(true) - $start;
        $this->assertLessThan(0.5, $duration, "Export took longer than 500ms");
    }
}
