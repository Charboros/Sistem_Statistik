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
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@dukcapil.go.id'],
            [
                'name' => 'Admin Dukcapil',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            RincianLayananSeeder::class,
        ]);
    }
}
