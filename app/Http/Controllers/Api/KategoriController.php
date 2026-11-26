<?php

namespace App\Http\Controllers\Api;

use App\Models\Kategori;

class KategoriController extends BaseCrudController
{
    protected string $model = Kategori::class;

    protected function storeRules(): array
    {
        return [
            'nama_kategori' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nama_kategori' => ['sometimes', 'required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];
    }
}
