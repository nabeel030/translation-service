<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(TranslationSeeder::class);

        User::updateOrCreate([
            'email' => 'test@example.com',
        ],[
            'name' => 'Test User',
            'password' => bcrypt('123456')
        ]);
    }
}
