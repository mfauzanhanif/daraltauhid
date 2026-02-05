<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique(); // Contoh: 2026
            $table->date('start_date');
            $table->date('end_date'); 
            $table->boolean('is_active')->default(false)->index();
            // Status Audit
            $table->enum('status', ['OPEN', 'CLOSED', 'AUDITED'])
                  ->default('OPEN')
                  ->index();
            
            $table->timestamps();
            // Sesuai spec, fiscal_periods tidak memiliki softDeletes            
            // Explicit index as per documentation
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fiscal_periods');
    }
};