<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code', 50)->unique()->nullable()
                  ->comment('Kode asset kendaraan');
            $table->string('brand', 50)->nullanble();
            $table->string('model', 100)->nullable();
            $table->string('plate_no', 20)->unique()
                  ->comment('Nomor polisi kendaraan');
            $table->integer('seat_capacity')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'retired'])
                  ->default('available')
                  ->comment('Status ketersediaan kendaraan');         
            $table->integer('odometer_km')->default(0);
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index('status');
            $table->index('plate_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
