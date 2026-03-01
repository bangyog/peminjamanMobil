<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->unique()->constrained('loan_requests')->onDelete('cascade')
                  ->comment('1 pengajuan hanya perlu 1 approval dari Admin GA');
            
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade')
                  ->comment('Admin GA yang approve/reject');
            
            $table->enum('decision', ['approved', 'rejected'])
                  ->comment('Keputusan approval');
            $table->text('reason')->nullable()
                  ->comment('Alasan reject (opsional)');
            $table->timestamp('decided_at')
                  ->comment('Waktu keputusan dibuat');
            
            $table->timestamps();
            
            $table->index('approver_id');
            $table->index(['loan_request_id', 'decision']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_approvals');
    }
};
