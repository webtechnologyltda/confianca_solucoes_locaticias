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
        // User::factory(10)->create();

        $adminUser = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        if (config('app.env') === 'local') {
            $this->call([
                UserSeeder::class,
                PropertySeeder::class,
                RealEstateAgentSeeder::class,
                RentalAnalysisSeeder::class,
                ShieldSeeder::class,
            ]);
        } else {
            $this->call([
                ShieldSeeder::class,
            ]);
        }

        $adminUser->assignRole('super_admin');
    }
}
