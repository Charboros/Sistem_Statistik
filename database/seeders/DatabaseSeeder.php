<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Admin Dukcapil',
                'password' => bcrypt('password12345678'),
            ]
        );

        $this->call([
            RincianLayananSeeder::class,
        ]);
    }
}
