<?php

namespace App\Http\Controllers\Api;

use App\Models\Klaim;

class KlaimController extends BaseCrudController
{
    protected string $model = Klaim::class;

    protected function storeRules(): array
    {
        return [
            'nama_pengklaim' => ['required', 'string', 'max:255'],
            'bukti_kepemilikan' => ['required', 'string'],
        ];
    }

    protected function updateRules(): array
    {
        return [
            'nama_pengklaim' => ['sometimes', 'required', 'string', 'max:255'],
            'bukti_kepemilikan' => ['sometimes', 'required', 'string'],
        ];
    }
}
