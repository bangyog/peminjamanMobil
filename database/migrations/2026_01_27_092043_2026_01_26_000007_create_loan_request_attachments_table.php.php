<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_request_id')->constrained('loan_requests')->onDelete('cascade');
            $table->text('requester_signature')->nullable()->after('notes');
            $table->string('file_name', 255)->nullable();
            $table->string('file_url', 500)
                  ->comment('Path/URL file lampiran');
            $table->string('mime_type', 100)->nullable();
            $table->bigInteger('file_size_bytes')->nullable();
            
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade')
                  ->comment('User yang upload lampiran');
            $table->timestamp('uploaded_at')->useCurrent();
            
            $table->index('loan_request_id');
            $table->index('uploaded_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_request_attachments');
    }
};
