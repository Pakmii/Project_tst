<?php

namespace App\Http\Controllers\Api;

use App\Models\Lokasi;

class LokasiController extends BaseCrudController
{
    protected string $model = Lokasi::class;

    protected function storeRules(): array
    {
        return [
            'nama_lokasi' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nama_lokasi' => ['sometimes', 'required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
