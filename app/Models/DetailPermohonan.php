<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPermohonan extends Model
{
    protected $fillable = ['permohonan_id', 'syarat_id', 'file_foto_syarat'];

    public function syarat(): BelongsTo
    {
        return $this->belongsTo(Syarat::class);
    }
}
