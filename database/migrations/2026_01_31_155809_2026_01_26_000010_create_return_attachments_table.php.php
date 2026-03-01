<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            
            $table->enum('type', ['request_attachment', 'return_proof', 'expense_receipt', 'other'])
                  ->default('return_proof')
                  ->comment('Jenis lampiran');
            $table->string('file_name', 255)->nullable();
            $table->string('file_url', 500)->comment('Path/URL file');
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size_bytes')->nullable();
            
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('uploaded_at')->useCurrent();
            
            $table->index(['return_id', 'type']);
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_attachments');
    }
};
