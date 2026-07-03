<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    protected $fillable = ['user_id', 'layanan_id', 'status', 'file_hasil_pdf', 'catatan_admin'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(DetailPermohonan::class);
    }
}


