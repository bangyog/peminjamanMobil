<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'name' => 'GA',
                'is_trial' => false,
                'trial_expires_at' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Akuntansi',
                'is_trial' => false,
                'trial_expires_at' => null,
                'is_active' => true,
            ],
            // [
            //     'name' => 'HR',
            //     'is_trial' => false,
            //     'trial_expires_at' => null,
            //     'is_active' => true,
            // ],
            [
                'name' => 'IT',
                'is_trial' => false,
                'trial_expires_at' => null,
                'is_active' => true,
            ],
            // [
            //     'name' => 'Finance',
            //     'is_trial' => false,
            //     'trial_expires_at' => null,
            //     'is_active' => true,
            // ],
            // [
            //     'name' => 'Marketing',
            //     'is_trial' => false,
            //     'trial_expires_at' => null,
            //     'is_active' => true,
            // ],
            // [
            //     'name' => 'Produksi',
            //     'is_trial' => false,
            //     'trial_expires_at' => null,
            //     'is_active' => true,
            // ],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }

        $this->command->info('✅ Units seeded successfully!');
    }
}
