<?php

namespace App\Http\Controllers\Api;

use App\Models\StatusBarang;

class StatusBarangController extends BaseCrudController
{
    protected string $model = StatusBarang::class;

    protected function storeRules(): array
    {
        return [
            'nama_status' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nama_status' => ['sometimes', 'required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
