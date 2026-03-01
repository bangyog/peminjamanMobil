<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->unique()->constrained('loan_requests')->onDelete('cascade')
                  ->comment('1 peminjaman hanya boleh 1 pengembalian');
            
            $table->timestamp('returned_at')->comment('Tanggal & waktu pengembalian aktual');
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('Petugas yang menerima pengembalian');
            
            $table->integer('odometer_km_end')->nullable()->comment('Odometer saat dikembalikan');
            $table->text('return_note')->nullable()->comment('Catatan kondisi/pengembalian');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('returned_at');
            $table->index('received_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
