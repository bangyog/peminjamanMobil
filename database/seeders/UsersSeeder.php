<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Units
        $unitGA = Unit::where('name', 'GA')->first();
        $unitAkuntansi = Unit::where('name', 'Akuntansi')->first();
        // $unitHR = Unit::where('name', 'HR')->first();
        $unitIT = Unit::where('name', 'IT')->first();
        // $unitFinance = Unit::where('name', 'Finance')->first();
        // $unitMarketing = Unit::where('name', 'Marketing')->first();
        // $unitProduksi = Unit::where('name', 'Produksi')->first();

        // ===========================================
        // ADMIN ACCOUNTS
        // ===========================================
        
        // Admin GA (Full Access - untuk login pertama kali)
        User::create([
            'unit_id' => $unitGA->id,
            'full_name' => 'Admin GA',
            'email' => 'admin.ga@company.com',
            'phone' => '081234567890',
            'password' => Hash::make('password'), // password: password
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Admin Akuntansi (Read-only untuk report)
        User::create([
            'unit_id' => $unitAkuntansi->id,
            'full_name' => 'Admin Akuntansi',
            'email' => 'admin.akuntansi@company.com',
            'phone' => '081234567891',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ===========================================
        // USER BIASA (untuk testing pengajuan)
        // ===========================================
        
        // HR Users
        // User::create([
        //     'unit_id' => $unitHR->id,
        //     'full_name' => 'Siti Nurhaliza',
        //     'email' => 'siti@company.com',
        //     'phone' => '081234567892',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // User::create([
        //     'unit_id' => $unitHR->id,
        //     'full_name' => 'Dewi Sartika',
        //     'email' => 'dewi@company.com',
        //     'phone' => '081234567893',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // IT Users
        User::create([
            'unit_id' => $unitIT->id,
            'full_name' => 'Ahmad Dahlan',
            'email' => 'ahmad@company.com',
            'phone' => '081234567894',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        User::create([
            'unit_id' => $unitIT->id,
            'full_name' => 'Budi Santoso',
            'email' => 'budi@company.com',
            'phone' => '081234567895',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

        // // Finance Users
        // User::create([
        //     'unit_id' => $unitFinance->id,
        //     'full_name' => 'Citra Kirana',
        //     'email' => 'citra@company.com',
        //     'phone' => '081234567896',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // // Marketing Users
        // User::create([
        //     'unit_id' => $unitMarketing->id,
        //     'full_name' => 'Eko Prasetyo',
        //     'email' => 'eko@company.com',
        //     'phone' => '081234567897',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // User::create([
        //     'unit_id' => $unitMarketing->id,
        //     'full_name' => 'Fitri Handayani',
        //     'email' => 'fitri@company.com',
        //     'phone' => '081234567898',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // // Produksi Users
        // User::create([
        //     'unit_id' => $unitProduksi->id,
        //     'full_name' => 'Gunawan Wibisono',
        //     'email' => 'gunawan@company.com',
        //     'phone' => '081234567899',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // User::create([
        //     'unit_id' => $unitProduksi->id,
        //     'full_name' => 'Hendra Setiawan',
        //     'email' => 'hendra@company.com',
        //     'phone' => '081234567800',
        //     'password' => Hash::make('password'),
        //     'role' => 'user',
        //     'is_active' => true,
        // ]);

        // User tidak aktif (untuk testing)
        User::create([
            'unit_id' => $unitIT->id,
            'full_name' => 'Inactive User',
            'email' => 'inactive@company.com',
            'phone' => '081234567801',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => false, // ← tidak aktif
        ]);

        $this->command->info('✅ Users seeded successfully!');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin GA      : admin.ga@company.com / password');
        $this->command->info('   Admin Akuntansi: admin.akuntansi@company.com / password');
        $this->command->info('   User (Siti)   : siti@company.com / password');
        $this->command->info('   User (Ahmad)  : ahmad@company.com / password');
    }
}
