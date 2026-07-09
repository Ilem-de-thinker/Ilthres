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
        $this->call([
            PlanSeeder::class,
            CategorySeeder::class,
            ThemeSeeder::class,
        ]);

        $password = bcrypt('password');

        User::firstOrCreate(['email' => 'test@example.com'], [
            'name' => 'Test User',
            'password' => $password,
        ]);

        User::firstOrCreate(['email' => 'admin@example.com'], [
            'name' => 'Admin',
            'password' => $password,
            'is_admin' => true,
            'onboarding_completed' => true,
        ]);
    }
}
