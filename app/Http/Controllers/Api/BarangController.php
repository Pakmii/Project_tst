<?php

namespace App\Http\Controllers\Api;

use App\Models\Barang;

class BarangController extends BaseCrudController
{
    protected string $model = Barang::class;

    protected function storeRules(): array
    {
        return [
            'nama_barang' => ['required', 'string', 'max:255'],
            'kondisi' => ['required', 'string', 'max:255'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nama_barang' => ['sometimes', 'required', 'string', 'max:255'],
            'kondisi' => ['sometimes', 'required', 'string', 'max:255'],
        ];
    }
}
