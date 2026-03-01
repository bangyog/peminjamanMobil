<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_requests', function (Blueprint $table) {
            $table->id();    
            // Header form pengajuan
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade')
                  ->comment('User yang mengajukan peminjaman');
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade')
                  ->comment('Unit peminjam (snapshot saat pengajuan)');
            $table->string('request_city', 100)->nullable()
                  ->comment('Lokasi pengajuan, mis: Gresik/Tuban');    
            // Isi form (sesuai FORMULIR-PENGAJUAN-KENDARAAN-DINAS)
            $table->string('purpose', 255)
                  ->comment('Keperluan peminjaman');
            $table->foreignId('preferred_vehicle_id')->nullable()->after('unit_id')->constrained('vehicles');
            $table->string('destination', 255)->nullable()
                  ->comment('Tujuan perjalanan');
            $table->string('requested_vehicle_text', 255)->nullable()
                  ->comment('Kendaraan yang diminta (text/keterangan)');
            $table->text('other_notes')->nullable()
                  ->comment('Lain-lain / keterangan tambahan');           
            // DIISI PEMAKAI - Jadwal Berangkat
            $table->string('depart_ready_at_place', 255)->nullable()
                  ->comment('Siap di (lokasi siap berangkat)');
            $table->timestamp('depart_at')->nullable()
                  ->comment('Jam & Tanggal berangkat');       
            // DIISI PEMAKAI - Jadwal Kembali
            $table->string('return_ready_at_place', 255)->nullable()
                  ->comment('Siap di (lokasi pengembalian)');
            $table->timestamp('expected_return_at')->nullable()
                  ->comment('Jam & Tanggal rencana kembali');           
            // Status proses peminjaman
            $table->enum('status', [
                'submitted',        // Baru diajukan, nunggu Admin GA approve
                'approved',         // Disetujui Admin GA, nunggu assign kendaraan
                'rejected',         // Ditolak Admin GA
                'canceled',         // Dibatalkan oleh user
                'assigned',         // Sudah di-assign kendaraan & driver oleh Admin GA
                'in_use',           // Kendaraan sedang dipakai
                'pending_return',   // User sudah input pengembalian, nunggu konfirmasi Admin GA
                'returned'          // Sudah dikembalikan & diterima Admin GA
            ])->default('submitted');
            
            $table->timestamps();
            
            $table->index(['requester_id', 'status']);
            $table->index(['unit_id', 'status']);
            $table->index(['depart_at', 'expected_return_at']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_requests');
    }
};
