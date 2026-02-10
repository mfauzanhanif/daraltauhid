<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==============================
        // 1. ACADEMIC YEARS (Tahun Ajaran)
        // ==============================
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique();
            $table->date('start_date');
            $table->date('end_date');
            // Logic aplikasi harus memastikan hanya ada 1 yang true
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            // Explicit index as per documentation
            $table->index('name');
        });

        // ==============================
        // 2. ACADEMIC PERIODS (Semester)
        // ==============================
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            // Relasi ke academic_years
            $table->foreignId('academic_year_id')
                ->constrained('academic_years')
                ->cascadeOnDelete();
            $table->enum('name', ['Ganjil', 'Genap']);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            // Constraint: Dalam satu tahun ajaran, tidak boleh ada nama semester yang sama
            $table->unique(['academic_year_id', 'name']);
        });

        // ==============================
        // 3. FISCAL PERIODS (Tahun Buku)
        // ==============================
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_periods');
        Schema::dropIfExists('academic_periods');
        Schema::dropIfExists('academic_years');
    }
};
