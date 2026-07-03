<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permohonan extends Model
{
    use HasFactory;

    // Daftarkan field yang boleh diisi (mass assignable) sesuai skema migration
    protected $fillable = [
        'user_id',
        'jenis_berkas',
        'nomor_kk',
        'nik_pemohon',
        'foto_bukti',
        'status',
        'foto_hasil',
        'catatan'
    ];

    /**
     * Relasi balik ke model User (Setiap permohonan dimiliki oleh satu User/Warga)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
