<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;
use App\Models\Syarat;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // --- 1. LAYANAN KARTU KELUARGA ---
        $kk = Layanan::create([
            'nama_layanan' => 'Pembuatan Kartu Keluarga (KK) Baru',
            'deskripsi' => 'Pengajuan kartu keluarga baru karena pernikahan, pecah KK, atau hilang/rusak.',
        ]);

        Syarat::create(['layanan_id' => $kk->id, 'nama_syarat' => 'Foto Buku Nikah / Akta Perkawinan']);
        Syarat::create(['layanan_id' => $kk->id, 'nama_syarat' => 'Foto KTP Suami & Istri']);
        Syarat::create(['layanan_id' => $kk->id, 'nama_syarat' => 'Foto KK Lama (Jika Ada) / Surat Kehilangan']);

        // --- 2. LAYANAN KTP ELEKTRONIK ---
        $ktp = Layanan::create([
            'nama_layanan' => 'Penerbitan KTP-el Baru / Rusak',
            'deskripsi' => 'Pengajuan cetak KTP baru untuk usia 17 tahun atau penggantian KTP rusak/hilang.',
        ]);

        Syarat::create(['layanan_id' => $ktp->id, 'nama_syarat' => 'Foto Kartu Keluarga (KK) Terbaru']);
        Syarat::create(['layanan_id' => $ktp->id, 'nama_syarat' => 'Foto KTP Lama yang Rusak / Surat Kehilangan KTP']);

        // --- 3. LAYANAN AKTA KELAHIRAN ---
        $akta = Layanan::create([
            'nama_layanan' => 'Pembuatan Akta Kelahiran Anak',
            'deskripsi' => 'Pencatatan kelahiran anak baru untuk mendapatkan Akta Kelahiran resmi.',
        ]);

        Syarat::create(['layanan_id' => $akta->id, 'nama_syarat' => 'Foto Surat Keterangan Lahir dari Bidan/RS']);
        Syarat::create(['layanan_id' => $akta->id, 'nama_syarat' => 'Foto Buku Nikah Orang Tua']);
        Syarat::create(['layanan_id' => $akta->id, 'nama_syarat' => 'Foto KTP Saksi Kelahiran (2 Orang)']);
    }
}
