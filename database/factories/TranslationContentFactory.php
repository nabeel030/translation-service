<?php

namespace Database\Factories;

use App\Models\Translation;
use App\Models\TranslationContent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranslationContent>
 */
class TranslationContentFactory extends Factory
{
    protected $model = TranslationContent::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => $this->faker->languageCode,  // Generate random language code like "en", "es"
            'content' => $this->faker->sentence,  // Generate random content text
            'translation_id' => Translation::factory(),  // Make sure this relates to an existing Translation model
        ];
    }
}
