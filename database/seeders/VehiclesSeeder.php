<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehiclesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicles = [
            // Toyota Avanza - Available
            [
                'unit_code' => 'GA-001',
                'plate_no' => 'L 1234 AB',
                'brand' => 'Toyota',
                'model' => 'Avanza',
                'seat_capacity' => 7,
                'status' => 'available',
                'odometer_km' => 15000,
                'notes' => 'Kondisi baik, AC dingin',
            ],
            
            // Honda Brio - Available
            [
                'unit_code' => 'GA-002',
                'plate_no' => 'L 5678 CD',
                'brand' => 'Honda',
                'model' => 'Brio',
                'seat_capacity' => 5,
                'status' => 'available',
                'odometer_km' => 8000,
                'notes' => 'Mobil baru, hemat BBM',
            ],
            
            // Daihatsu Xenia - Maintenance
            [
                'unit_code' => 'GA-003',
                'plate_no' => 'L 9012 EF',
                'brand' => 'Daihatsu',
                'model' => 'Xenia',
                'seat_capacity' => 7,
                'status' => 'maintenance',
                'odometer_km' => 25000,
                'notes' => 'Service rutin bulanan, estimasi selesai 3 hari',
            ],
            
            // Toyota Innova - Available
            [
                'unit_code' => 'GA-004',
                'plate_no' => 'L 3456 GH',
                'brand' => 'Toyota',
                'model' => 'Innova Reborn',
                'seat_capacity' => 7,
                'status' => 'available',
                'odometer_km' => 45000,
                'notes' => 'Diesel, cocok untuk perjalanan jauh',
            ],
            
            // Suzuki Ertiga - Available
            [
                'unit_code' => 'GA-005',
                'plate_no' => 'L 7890 IJ',
                'brand' => 'Suzuki',
                'model' => 'Ertiga',
                'seat_capacity' => 7,
                'status' => 'available',
                'odometer_km' => 18000,
                'notes' => 'Irit, nyaman untuk dalam kota',
            ],
            
            // Mitsubishi Xpander - In Use (untuk testing)
            [
                'unit_code' => 'GA-006',
                'plate_no' => 'L 2345 KL',
                'brand' => 'Mitsubishi',
                'model' => 'Xpander',
                'seat_capacity' => 7,
                'status' => 'in_use',
                'odometer_km' => 5000,
                'notes' => 'Sedang digunakan oleh unit Marketing',
            ],
            
            // Honda Jazz - Available
            [
                'unit_code' => 'GA-007',
                'plate_no' => 'L 6789 MN',
                'brand' => 'Honda',
                'model' => 'Jazz',
                'seat_capacity' => 5,
                'status' => 'available',
                'odometer_km' => 12000,
                'notes' => 'Lincah, cocok untuk dalam kota',
            ],
            
            // Toyota Fortuner - Available (VIP)
            [
                'unit_code' => 'GA-008',
                'plate_no' => 'L 1111 OP',
                'brand' => 'Toyota',
                'model' => 'Fortuner',
                'seat_capacity' => 7,
                'status' => 'available',
                'odometer_km' => 3000,
                'notes' => 'Mobil VIP untuk tamu penting atau direksi',
            ],
            
            // Daihatsu Gran Max (Pick Up) - Available
            [
                'unit_code' => 'GA-009',
                'plate_no' => 'L 4567 QR',
                'brand' => 'Daihatsu',
                'model' => 'Gran Max Pick Up',
                'seat_capacity' => 3,
                'status' => 'available',
                'odometer_km' => 60000,
                'notes' => 'Untuk angkut barang/equipment',
            ],
            
            // Toyota Hiace - Retired (untuk testing)
            [
                'unit_code' => 'GA-010',
                'plate_no' => 'L 8888 ST',
                'brand' => 'Toyota',
                'model' => 'Hiace',
                'seat_capacity' => 14,
                'status' => 'retired',
                'odometer_km' => 150000,
                'notes' => 'Sudah tidak layak pakai, menunggu lelang',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }

        $this->command->info('✅ Vehicles seeded successfully!');
        $this->command->info('');
        $this->command->info('📊 Vehicle Status:');
        $this->command->info('   Available  : ' . Vehicle::available()->count() . ' units');
        $this->command->info('   In Use     : ' . Vehicle::inUse()->count() . ' units');
        $this->command->info('   Maintenance: ' . Vehicle::maintenance()->count() . ' units');
        $this->command->info('   Retired    : ' . Vehicle::where('status', 'retired')->count() . ' units');
    }
}
