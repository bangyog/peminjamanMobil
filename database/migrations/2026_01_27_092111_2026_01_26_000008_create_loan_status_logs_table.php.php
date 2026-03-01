<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->constrained('loan_requests')->onDelete('cascade');
            
            $table->enum('from_status', [
                'submitted', 'approved', 'rejected', 'canceled', 
                'assigned', 'in_use', 'pending_return', 'returned'
            ])->nullable()
              ->comment('Status sebelumnya (null untuk record pertama)');
            
            $table->enum('to_status', [
                'submitted', 'approved', 'rejected', 'canceled', 
                'assigned', 'in_use', 'pending_return', 'returned'
            ])->comment('Status baru');
            
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null')
                  ->comment('User yang melakukan perubahan status');
            $table->text('change_note')->nullable()
                  ->comment('Catatan/keterangan perubahan');
            $table->timestamp('changed_at')->useCurrent()
                  ->comment('Waktu perubahan status');
            
            $table->index(['loan_request_id', 'changed_at']);
            $table->index('to_status');
            $table->index('changed_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_status_logs');
    }
};
