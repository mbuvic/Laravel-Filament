<?php

namespace Database\Seeders;

use App\Models\User;
use Filament\Tables\Columns\Summarizers\Count;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //run countries seeder
        $this->call(CountrySeeder::class);

        //run counties seeder
        $this->call(CountySeeder::class);

        //run cities seeder
        $this->call(CitySeeder::class);
    }
}
