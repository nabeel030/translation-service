<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Translation;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $faker = Faker::create();

        
        $locales = ['en', 'fr', 'es'];
        $chunkSize = 1000;
        
        for ($i = 0; $i < 10000; $i += $chunkSize) {
            $translations = Translation::factory()->count($chunkSize)->create();

            foreach ($translations as $translation) {
                $contents = [];

                foreach ($locales as $locale) {
                    $contents[] = [
                        'translation_id' => $translation->id,
                        'locale' => $locale,
                        'content' => $faker->sentence(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                DB::table('translation_contents')->insert($contents);
            }
        }
    }
}
