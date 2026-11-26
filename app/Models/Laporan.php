<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'judul_laporan', 'deskripsi'])]
class Laporan extends Model
{
    protected $table = 'laporan';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
