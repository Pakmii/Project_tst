<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'nama_pengklaim', 'bukti_kepemilikan'])]
class Klaim extends Model
{
    protected $table = 'klaim';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
