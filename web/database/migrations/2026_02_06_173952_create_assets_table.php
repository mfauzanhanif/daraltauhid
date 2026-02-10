<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Kategori Aset
        Schema::create('asset_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('name'); // Elektronik, Mebel, Kendaraan
            $table->boolean('is_depreciable')->default(true); // Bisa disusutkan?
            $table->timestamps();
        });

        // 2. Gedung (Buildings)
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Gedung Al-Fatih
            $table->string('code')->nullable(); // BLD-01
            $table->integer('total_floors')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Ruangan (Rooms)
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Kelas 1A
            $table->string('code')->nullable(); // R-101
            $table->integer('floor_number')->default(1);
            $table->integer('capacity')->default(0);
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete(); // PIC Ruangan

            $table->timestamps();
            $table->softDeletes();
        });
        // 4. Assets
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_category_id')->constrained();

            // Lokasi Saat Ini (Bisa berubah lewat Mutasi)
            $table->foreignId('room_id')->constrained();

            // Identitas
            $table->string('name'); // Laptop Asus ROG
            $table->string('code')->unique(); // INV/2026/MI/001 (Barcode/QR Content)
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();

            // Nilai Aset
            $table->date('purchase_date');
            $table->decimal('purchase_price', 15, 2);
            $table->string('funding_source')->nullable(); // BOS, Yayasan, Wakaf
            $table->integer('useful_life_years')->default(5);

            // Status Lifecycle
            $table->enum('condition', ['GOOD', 'SLIGHTLY_DAMAGED', 'HEAVILY_DAMAGED']);
            $table->enum('status', [
                'ACTIVE',       // Ada di tempat, siap pakai
                'BORROWED',     // Sedang dipinjam
                'MAINTENANCE',  // Sedang diperbaiki
                'LOST',         // Hilang (tunggu keputusan audit)
                'DISPOSED',      // Sudah dihapus/dijual
            ])->default('ACTIVE');

            $table->timestamps();
            $table->softDeletes(); // Data tidak hilang fisik, cuma flag deleted

            $table->index('code');
            $table->index(['institution_id', 'status']);
        });
        // 5. Mutasi (Perpindahan Lokasi)
        Schema::create('asset_mutations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();

            $table->foreignId('from_room_id')->constrained('rooms');
            $table->foreignId('to_room_id')->constrained('rooms');

            $table->foreignId('moved_by_user_id')->constrained('users'); // Siapa yang memindah
            $table->dateTime('moved_at');
            $table->text('reason')->nullable(); // "Pindah ke kelas baru", "Disimpan di gudang"

            $table->timestamps();
        });

        // 6. Peminjaman (Lending)
        Schema::create('asset_lendings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();

            $table->foreignId('borrower_user_id')->constrained('users'); // Santri/Guru
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users'); // Admin Sarpras

            $table->dateTime('borrowed_at')->nullable();
            $table->dateTime('expected_return_at'); // Janji kembali
            $table->dateTime('returned_at')->nullable(); // Realisasi kembali

            $table->enum('status', ['REQUESTED', 'APPROVED', 'ON_LOAN', 'RETURNED', 'OVERDUE', 'REJECTED'])
                ->default('REQUESTED');

            $table->text('purpose')->nullable(); // "Untuk Presentasi Kelompok 5"
            $table->text('notes_condition_after')->nullable(); // Catatan saat kembali (misal: lecet)

            $table->timestamps();
        });
        // 7. Pemeliharaan (Maintenance)
        Schema::create('asset_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->foreignId('reported_by_user_id')->constrained('users'); // Pelapor
            
            // Workflow Ticket
            $table->string('ticket_number')->unique(); // MNT/2026/001
            $table->enum('status', ['REPORTED', 'IN_REVIEW', 'IN_REPAIR', 'RESOLVED', 'IRREPARABLE'])
                  ->default('REPORTED');
            
            // Detail Kerusakan
            $table->text('issue_description');
            $table->string('evidence_photo_path')->nullable(); // Foto bukti kerusakan
            
            // Eksekusi Perbaikan
            $table->dateTime('repair_started_at')->nullable();
            $table->dateTime('repair_finished_at')->nullable();
            $table->string('technician_name')->nullable(); // Vendor atau Staff Internal
            
            // Integrasi Finance (Biaya)
            $table->decimal('repair_cost', 15, 2)->default(0); 
            $table->text('repair_notes')->nullable(); // Apa yang diperbaiki/diganti

            $table->timestamps();
        });
        // 8. Stock Opname (Header)
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('auditor_user_id')->constrained('users');
            
            $table->string('title'); // "Audit Tahunan 2026"
            $table->date('audit_date');
            $table->enum('status', ['DRAFT', 'COMPLETED'])->default('DRAFT');
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });

        // 9. Stock Opname (Detail Items)
        // Hanya mencatat barang yang bermasalah/hilang/salah tempat saat audit
        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained();
            
            $table->enum('actual_status', ['MATCH', 'MISSING', 'WRONG_LOCATION', 'DAMAGED']);
            $table->text('auditor_note')->nullable();
            
            $table->timestamps();
        });

        // 10. Asset Disposal (Penghapusan)
        Schema::create('asset_disposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users');
            
            $table->date('disposal_date');
            $table->enum('reason', ['DAMAGED', 'LOST', 'SOLD', 'DONATED']);
            
            // Keuangan (Nilai Residu)
            $table->decimal('sale_price', 15, 2)->default(0); // Jika dijual
            $table->text('notes')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('asset_categories');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('asset_mutations');
        Schema::dropIfExists('asset_lendings');
        Schema::dropIfExists('asset_maintenances');
        Schema::dropIfExists('stock_opnames');
        Schema::dropIfExists('stock_opname_items');
        Schema::dropIfExists('asset_disposals');
    }
};
