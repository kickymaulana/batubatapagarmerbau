<?php

// database/migrations/xxxx_xx_xx_create_layanan_and_permohonan_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Master Layanan
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan'); // Contoh: Pembuatan KTP, Cetak KK
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 2. Master Syarat Berdasarkan Layanan
        Schema::create('syarats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained()->onDelete('cascade');
            $table->string('nama_syarat'); // Contoh: Foto Copy KTP, Foto Copy Ijazah
            $table->timestamps();
        });

        // 3. Data Permohonan Utama
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('layanan_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, diproses_siak, selesai, ditolak
            $table->string('file_hasil_pdf')->nullable(); // File PDF dari SIAK
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });

        // 4. File Syarat yang DI-UPLOAD oleh Warga
        Schema::create('detail_permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id')->constrained()->onDelete('cascade');
            $table->foreignId('syarat_id')->constrained()->onDelete('cascade');
            $table->string('file_foto_syarat'); // Path foto syarat yang di-upload warga
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_permohonans');
        Schema::dropIfExists('permohonans');
        Schema::dropIfExists('syarats');
        Schema::dropIfExists('layanans');
    }
};
