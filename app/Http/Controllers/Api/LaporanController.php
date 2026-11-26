<?php

namespace App\Http\Controllers\Api;

use App\Models\Laporan;

class LaporanController extends BaseCrudController
{
    protected string $model = Laporan::class;

    protected function storeRules(): array
    {
        return [
            'judul_laporan' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'judul_laporan' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['sometimes', 'required', 'string'],
        ];
    }
}
