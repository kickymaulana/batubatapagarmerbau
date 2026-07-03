<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    protected $fillable = ['nama_layanan', 'deskripsi'];

    public function syarats(): HasMany
    {
        return $this->hasMany(Syarat::class);
    }
}

