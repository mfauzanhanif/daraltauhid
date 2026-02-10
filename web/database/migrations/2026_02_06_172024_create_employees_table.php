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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Link ke akun login

            // Identitas Nasional
            $table->string('nik', 16)->unique(); // Primary Identifier Personal
            $table->string('nip', 30)->nullable()->unique(); // NIP Yayasan/PNS
            $table->string('nuptk', 30)->nullable(); // Penting untuk sekolah
            $table->string('npwp', 30)->nullable();

            // Biodata
            $table->string('name');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->enum('gender', ['L', 'P']);
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email')->unique();

            // Data Pendidikan & Profesi
            $table->string('last_education')->nullable(); // S1, S2, DLL
            $table->string('major')->nullable(); // Jurusan
            $table->string('university')->nullable();

            // Data Bank (Satu rekening untuk transfer gaji total)
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_holder')->nullable();

            // File
            $table->string('photo_path')->nullable();
            $table->json('documents_path')->nullable(); // Scan KTP, Ijazah, KK

            $table->timestamps();
            $table->softDeletes();
        });

        // Tambahkan Foreign Key untuk headmaster_id di institutions
        Schema::table('institutions', function (Blueprint $table) {
            $table->foreign('headmaster_id')
                  ->references('id')
                  ->on('employees')
                  ->nullOnDelete();
        });

        Schema::create('employee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained('institutions')->cascadeOnDelete(); // Mengajar di MI atau Pondok

            // Detail Pekerjaan
            $table->string('position'); // Guru Kelas, Kepala Sekolah, Staff TU
            $table->enum('employment_status', ['PERMANENT', 'CONTRACT', 'HONORARY', 'INTERN']);
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Null jika permanent
            $table->boolean('is_active')->default(true); // Masih aktif di jabatan ini?

            // Gaji (Per Penugasan)
            // Guru bisa digaji X di MI, dan digaji Y di Pondok
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('allowance_fixed', 15, 2)->default(0); // Tunjangan Jabatan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assignments');
        Schema::dropIfExists('employees');
    }
};
