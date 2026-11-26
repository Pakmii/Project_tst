<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseCrudController extends Controller
{
    /**
     * The Eloquent model class managed by this controller.
     *
     * @var class-string<Model>
     */
    protected string $model;

    /**
     * Validation rules for creating a record.
     *
     * @return array<string, mixed>
     */
    abstract protected function storeRules(): array;

    /**
     * Validation rules for updating a record.
     *
     * @return array<string, mixed>
     */
    abstract protected function updateRules(): array;

    /**
     * Display a listing of the resource.
     * PUBLIC — no authentication required.
     */
    public function index(): JsonResponse
    {
        $items = $this->model::query()->latest('id')->get();

        return response()->json([
            'message' => 'Data berhasil diambil.',
            'data'    => $items,
        ]);
    }

    /**
     * Display the specified resource.
     * PUBLIC — no authentication required.
     */
    public function show(int $id): JsonResponse
    {
        $item = $this->model::find($id);

        if (! $item) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        return response()->json([
            'message' => 'Detail data berhasil diambil.',
            'data'    => $item,
        ]);
    }

    /**
     * Store a newly created resource.
     * AUTH required — user_id otomatis dari token.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate($this->storeRules());
        $validated['user_id'] = auth('api')->id();

        $item = $this->model::create($validated);

        return response()->json([
            'message' => 'Data berhasil ditambahkan.',
            'data'    => $item,
        ], 201);
    }

    /**
     * Update the specified resource.
     * AUTH required.
     * - Admin: boleh update data siapapun.
     * - User : hanya boleh update data milik sendiri (ownership).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $item = $this->model::find($id);

        if (! $item) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $authUser = auth('api')->user();

        // Ownership check: user biasa hanya boleh update miliknya sendiri
        if ($authUser->role !== 'admin' && $item->user_id !== $authUser->id) {
            return response()->json([
                'message' => 'Akses ditolak. Anda hanya dapat mengubah data milik Anda sendiri.',
            ], 403);
        }

        $validated = $request->validate($this->updateRules());
        $item->update($validated);

        return response()->json([
            'message' => 'Data berhasil diperbarui.',
            'data'    => $item->fresh(),
        ]);
    }

    /**
     * Remove the specified resource.
     * ADMIN only — user biasa tidak memiliki akses Delete.
     */
    public function destroy(int $id): JsonResponse
    {
        $item = $this->model::find($id);

        if (! $item) {
            return response()->json(['message' => 'Data tidak ditemukan.'], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
