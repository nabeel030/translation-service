<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Translation;
use App\Repositories\TranslationRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TranslationRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = app(TranslationRepository::class);
    }

    public function test_getAllTranslations_returns_paginated_results_with_contents()
    {
        Translation::factory()->hasContents(3)->count(5)->create();

        $result = $this->repo->getAllTranslations();
        $this->assertInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class, $result);

        $this->assertCount(5, $result->items());

        foreach ($result->items() as $translation) {
            $this->assertNotEmpty($translation->contents);
        }
    }

    public function test_getTranslationById_returns_translation_with_contents()
    {
        $translation = Translation::factory()->hasContents(3)->create();

        $result = $this->repo->getTranslationById($translation->id);

        $this->assertInstanceOf(Translation::class, $result);

        $this->assertNotEmpty($result->contents);
    }

    public function test_store_translation_creates_translation_with_contents()
    {
        $data = [
            'key' => 'greeting',
            'tag' => 'welcome',
            'content' => [
                ['locale' => 'en', 'content' => 'Hello'],
                ['locale' => 'fr', 'content' => 'Bonjour'],
            ]
        ];

        $translation = $this->repo->storeTranslation($data);

        $this->assertDatabaseHas('translations', ['key' => 'greeting']);
        $this->assertCount(2, $translation->contents);
    }

    public function test_update_translation_updates_contents()
    {
        $translation = Translation::factory()->create();
        $translation->contents()->create(['locale' => 'en', 'content' => 'Hi']);

        $updated = $this->repo->updateTranslation($translation->id, [
            'key' => 'greeting_updated',
            'tag' => 'updated_tag',
            'content' => [
                ['locale' => 'en', 'content' => 'Hello there!'],
                ['locale' => 'fr', 'content' => 'Salut!'],
            ]
        ]);

        $this->assertEquals('greeting_updated', $updated->key);
        $this->assertCount(2, $updated->contents);
    }

    public function test_delete_translation_removes_related_contents()
    {
        $data = [
            'key' => 'greeting',
            'tag' => 'welcome',
            'content' => [
                ['locale' => 'en', 'content' => 'Hello'],
                ['locale' => 'fr', 'content' => 'Bonjour'],
            ]
        ];

        $translation = $this->repo->storeTranslation($data);

        $this->repo->deleteTranslation($translation->id);

        $this->assertDatabaseMissing('translations', ['id' => $translation->id]);
        $this->assertDatabaseCount('translation_contents', 0);
    }

    public function test_exportAsJSON_returns_lazy_collection()
    {
        $stream = $this->repo->exportAsJSON();
        $this->assertInstanceOf(\Illuminate\Support\LazyCollection::class, $stream);
        $this->assertCount(5, $stream->all());
    }
}
