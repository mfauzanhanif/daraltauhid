<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20)->unique();
            $table->date('start_date');
            $table->date('end_date');
            // Logic aplikasi harus memastikan hanya ada 1 yang true nanti
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();
            
            // Explicit index as per documentation
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};