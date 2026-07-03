<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_berkas'); // KTP / KK / dll
            $table->string('nomor_kk')->nullable();
            $table->string('nik_pemohon')->nullable();

            // Berkas dari masyarakat
            $table->string('foto_bukti');

            // Status Alur Berkas
            // 'pending' (baru), 'diproses_pusat' (oleh kecamatan), 'selesai' (sudah ada hasil), 'ditolak'
            $table->string('status')->default('pending');

            // Hasil dari Admin Kecamatan
            $table->string('foto_hasil')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};
