<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        $faker = Faker::create();
        $locales = ['en', 'fr', 'es'];
        $translationsChunk = [];
        $translationContentChunk = [];

        $lastId = DB::table('translations')->max('id') ?? 0;

        for($i=1; $i<=100_000; $i++) {
            $lastId++;
            $translationsChunk[] = [
                'id' => $lastId,
                'key' => $faker->unique()->slug(),
                'tag' => $faker->randomElement(['web', 'mobile', 'desktop']),
            ];

            foreach ($locales as $locale) {
                $translationContentChunk[] = [
                    'translation_id' => $lastId,
                    'locale' => $locale,
                    'content' => $faker->sentence(),
                ];
            }

            if ($i % 5000 == 0) {
                DB::table('translations')->insert($translationsChunk);
                DB::table('translation_contents')->insert($translationContentChunk);
                $translationsChunk = [];
                $translationContentChunk = [];
            }
        }

        if (!empty($translationsChunk)) {
            DB::table('translations')->insert($translationsChunk);
            DB::table('translation_contents')->insert($translationContentChunk);
        }
    }
}