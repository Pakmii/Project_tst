<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    /**
     * Register a new user (role otomatis: 'user').
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['nullable', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name'     => $validated['name'] ?? null,
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => 'user',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message'    => 'Registrasi berhasil.',
            'data'       => $user,
            'token'      => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Login dan kembalikan JWT token.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        return response()->json([
            'message'    => 'Login berhasil.',
            'data'       => auth('api')->user(),
            'token'      => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }

    /**
     * Logout (invalidate token JWT).
     */
    public function logout(): JsonResponse
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout berhasil. Token telah diinvalidasi.',
        ]);
    }

    /**
     * Refresh JWT token.
     */
    public function refresh(): JsonResponse
    {
        $newToken = auth('api')->refresh();

        return response()->json([
            'message'    => 'Token berhasil diperbarui.',
            'token'      => $newToken,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }

    /**
     * Profil user yang sedang login.
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'message' => 'Data profil berhasil diambil.',
            'data'    => auth('api')->user(),
        ]);
    }

    /**
     * Daftar semua user (admin only).
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Data users berhasil diambil.',
            'data'    => User::query()->latest('id')->get(),
        ]);
    }

    /**
     * Detail user berdasarkan ID (admin only).
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json([
            'message' => 'Detail user berhasil diambil.',
            'data'    => $user,
        ]);
    }

    /**
     * Update user (admin only).
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $validated = $request->validate([
            'name'     => ['nullable', 'string', 'max:255'],
            'email'    => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['sometimes', 'required', 'string', 'min:6'],
            'role'     => ['sometimes', 'required', Rule::in(['user', 'admin'])],
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User berhasil diperbarui.',
            'data'    => $user->fresh(),
        ]);
    }

    /**
     * Hapus user (admin only).
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus.',
        ]);
    }
}
