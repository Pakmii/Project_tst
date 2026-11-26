<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'nama_status', 'keterangan'])]
class StatusBarang extends Model
{
    protected $table = 'status_barang';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
