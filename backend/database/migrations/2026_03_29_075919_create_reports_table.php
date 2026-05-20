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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Relasi ke pelapor (masyarakat)
            $table->text('description')->nullable(); // Deskripsi opsional dari pelapor

            // Foto & Lokasi (dikirim dari Flutter via REST API)
            $table->string('photo_path'); // Path foto asli kerusakan dari mobile
            $table->decimal('latitude', 10, 8); // Koordinat GPS untuk Leaflet map
            $table->decimal('longitude', 11, 8); // Koordinat GPS untuk Leaflet map

            // Hasil Deteksi AI (YOLO) — hanya label, tanpa confidence score
            $table->string('damage_type')->nullable(); // Hasil deteksi: 'Pothole' / 'Crack' / null (belum diproses)
            $table->string('ai_photo_path')->nullable(); // Path foto dengan bounding box hasil AI

            // Status & Manajemen Operator
            // Alur: Dilaporkan -> Disurvey -> Diproses -> Selesai
            // Atau: Dilaporkan -> Tidak Valid (foto bukan kerusakan jalan)
            $table->enum('status', ['Dilaporkan', 'Disurvey', 'Tidak Valid', 'Diproses', 'Selesai'])->default('Dilaporkan');
            $table->foreignId('operator_id')->nullable()->constrained('users')->nullOnDelete(); // Operator yang menangani
            $table->text('rejection_note')->nullable(); // Alasan penolakan oleh operator

            // Bukti Perbaikan (Closed-Loop Validation)
            $table->string('evidence_photo_path')->nullable(); // Foto bukti jalan sudah diperbaiki

            // Timestamp
            $table->timestamp('verified_at')->nullable(); // Waktu operator memverifikasi
            $table->timestamp('completed_at')->nullable(); // Waktu status diubah ke 'Selesai'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
