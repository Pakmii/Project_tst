<?php

use App\Http\Controllers\Api\BarangController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\KlaimController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\StatusBarangController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — Lost & Found
|--------------------------------------------------------------------------
|
| Hak Akses:
|   PUBLIC  : Dapat diakses tanpa login (GET index & show semua resource)
|   USER    : Create + Read + Update data milik sendiri (ownership)
|   ADMIN   : CRUD penuh atas seluruh data
|
*/

// ═══════════════════════════════════════════════════════════════════
// PUBLIC — Tanpa login
// ═══════════════════════════════════════════════════════════════════
Route::post('/register', [UserController::class, 'register']);
Route::post('/login',    [UserController::class, 'login']);

// Read-only publik (siapa pun bisa melihat data)
Route::get('/barang',          [BarangController::class,       'index']);
Route::get('/barang/{id}',     [BarangController::class,       'show']);
Route::get('/kategori',        [KategoriController::class,     'index']);
Route::get('/kategori/{id}',   [KategoriController::class,     'show']);
Route::get('/lokasi',          [LokasiController::class,       'index']);
Route::get('/lokasi/{id}',     [LokasiController::class,       'show']);
Route::get('/laporan',         [LaporanController::class,      'index']);
Route::get('/laporan/{id}',    [LaporanController::class,      'show']);
Route::get('/klaim',           [KlaimController::class,        'index']);
Route::get('/klaim/{id}',      [KlaimController::class,        'show']);
Route::get('/status-barang',   [StatusBarangController::class, 'index']);
Route::get('/status-barang/{id}', [StatusBarangController::class, 'show']);

// ═══════════════════════════════════════════════════════════════════
// AUTHENTICATED — Perlu login JWT
// ═══════════════════════════════════════════════════════════════════
Route::middleware('auth:api')->group(function () {

    // Auth utilities
    Route::post('/logout',  [UserController::class, 'logout']);
    Route::post('/refresh', [UserController::class, 'refresh']);
    Route::get('/me',       [UserController::class, 'me']);

    // ───────────────────────────────────────────────────────────────
    // USER & ADMIN — CRU (Create, Read, Update)
    // Update dengan ownership check ada di BaseCrudController
    // ───────────────────────────────────────────────────────────────
    Route::post('/barang',        [BarangController::class,       'store']);
    Route::put('/barang/{id}',    [BarangController::class,       'update']);

    Route::post('/kategori',      [KategoriController::class,     'store']);
    Route::put('/kategori/{id}',  [KategoriController::class,     'update']);

    Route::post('/lokasi',        [LokasiController::class,       'store']);
    Route::put('/lokasi/{id}',    [LokasiController::class,       'update']);

    Route::post('/laporan',       [LaporanController::class,      'store']);
    Route::put('/laporan/{id}',   [LaporanController::class,      'update']);

    Route::post('/klaim',         [KlaimController::class,        'store']);
    Route::put('/klaim/{id}',     [KlaimController::class,        'update']);

    Route::post('/status-barang',      [StatusBarangController::class, 'store']);
    Route::put('/status-barang/{id}',  [StatusBarangController::class, 'update']);

    // ───────────────────────────────────────────────────────────────
    // ADMIN ONLY — Delete + Users management
    // ───────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {

        // Users management
        Route::get('/users',         [UserController::class, 'index']);
        Route::get('/users/{id}',    [UserController::class, 'show']);
        Route::put('/users/{id}',    [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Delete resource (hanya admin)
        Route::delete('/barang/{id}',      [BarangController::class,       'destroy']);
        Route::delete('/kategori/{id}',    [KategoriController::class,     'destroy']);
        Route::delete('/lokasi/{id}',      [LokasiController::class,       'destroy']);
        Route::delete('/laporan/{id}',     [LaporanController::class,      'destroy']);
        Route::delete('/klaim/{id}',       [KlaimController::class,        'destroy']);
        Route::delete('/status-barang/{id}', [StatusBarangController::class, 'destroy']);
    });
});
