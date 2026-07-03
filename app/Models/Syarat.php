<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Syarat extends Model
{
    protected $fillable = ['layanan_id', 'nama_syarat'];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }
}

