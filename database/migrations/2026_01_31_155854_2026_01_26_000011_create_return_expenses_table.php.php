<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('return_id')->constrained('returns')->onDelete('cascade');
            
            $table->enum('type', ['fuel', 'toll', 'parking', 'repair', 'other'])
                  ->comment('Jenis biaya: bensin, tol, parkir, tambal ban/servis, dll');
            $table->string('description', 255)->nullable()->comment('Deskripsi detail biaya');
            $table->decimal('amount', 14, 2)->default(0)->comment('Nominal biaya');
            $table->string('currency', 10)->default('IDR');
            
            $table->string('receipt_url', 500)->nullable()->comment('Bukti nota/struk');
            
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['return_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_expenses');
    }
};
