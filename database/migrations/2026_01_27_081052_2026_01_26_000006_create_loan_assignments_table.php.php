<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->unique()->constrained('loan_requests')->onDelete('cascade');
            // "Diperintahkan kepada Sdr … dengan kendaraan …"
            $table->string('assigned_driver_name', 150)->nullable()
                  ->comment('Nama driver/petugas yang ditugaskan');
            $table->foreignId('assigned_vehicle_id')->nullable()->constrained('vehicles')->onDelete('set null')
                  ->comment('Kendaraan aktual yang dipakai');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('Pimpinan kendaraan yang assign');
            $table->timestamp('assigned_at')->nullable();
            
            $table->timestamps();
            
            $table->index('assigned_vehicle_id');
            $table->index('assigned_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_assignments');
    }
};
