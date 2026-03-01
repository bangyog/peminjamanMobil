<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('🌱 Starting Database Seeding...');
        $this->command->info('================================');
        $this->command->info('');

        // Jalankan seeder sesuai urutan (dependency)
        $this->call([
            UnitsSeeder::class,       // 1. Units dulu (karena user butuh unit_id)
            UsersSeeder::class,       // 2. Users (butuh units)
            VehiclesSeeder::class,    // 3. Vehicles (independent)
        ]);

        $this->command->info('');
        $this->command->info('================================');
        $this->command->info('✅ Database seeding completed!');
        $this->command->info('');
        $this->command->info('🚀 Next Steps:');
        $this->command->info('   1. Test login: admin.ga@company.com / password');
        $this->command->info('   2. Run: php artisan serve');
        $this->command->info('   3. Access: http://localhost:8000');
        $this->command->info('');
    }
}
