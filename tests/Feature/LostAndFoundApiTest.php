<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LostAndFoundApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_view_master_data_and_create_laporan(): void
    {
        $registerResponse = $this->postJson('/api/register', [
            'email' => 'user@gmail.com',
            'password' => '123456',
        ]);

        $registerResponse->assertCreated()
            ->assertJsonPath('data.email', 'user@gmail.com')
            ->assertJsonPath('data.role', 'user');

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'user@gmail.com',
            'password' => '123456',
        ]);

        $loginResponse->assertOk();
        $token = $loginResponse->json('token');

        $this->withToken($token)->getJson('/api/barang')->assertOk();
        $this->withToken($token)->getJson('/api/kategori')->assertOk();
        $this->withToken($token)->getJson('/api/lokasi')->assertOk();

        $this->withToken($token)->postJson('/api/laporan', [
            'judul_laporan' => 'Kehilangan Laptop',
            'deskripsi' => 'Laptop hilang di ruang kelas',
        ])->assertCreated();

        // User role should be forbidden from admin-only endpoints
        $this->withToken($token)->postJson('/api/barang', [
            'nama_barang' => 'Laptop ASUS',
            'kondisi' => 'Baik',
        ])->assertForbidden();

        $this->withToken($token)->getJson('/api/klaim')->assertForbidden();
        $this->withToken($token)->getJson('/api/status-barang')->assertForbidden();
    }

    public function test_admin_can_crud_all_resources(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => '123456',
            'role' => 'admin',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $barang = $this->withToken($token)->postJson('/api/barang', [
            'nama_barang' => 'Laptop ASUS',
            'kondisi' => 'Baik',
        ])->assertCreated()->json('data');

        $this->withToken($token)->putJson("/api/barang/{$barang['id']}", [
            'kondisi' => 'Rusak Ringan',
        ])->assertOk()->assertJsonPath('data.kondisi', 'Rusak Ringan');

        $this->withToken($token)->postJson('/api/kategori', [
            'nama_kategori' => 'Elektronik',
            'deskripsi' => 'Barang elektronik',
        ])->assertCreated();

        $this->withToken($token)->postJson('/api/lokasi', [
            'nama_lokasi' => 'Gedung A',
            'keterangan' => 'Lantai 2',
        ])->assertCreated();

        $klaim = $this->withToken($token)->postJson('/api/klaim', [
            'nama_pengklaim' => 'Budi',
            'bukti_kepemilikan' => 'Memiliki stiker merah',
        ])->assertCreated()->json('data');

        $this->withToken($token)->getJson("/api/klaim/{$klaim['id']}")->assertOk();
        $this->withToken($token)->deleteJson("/api/klaim/{$klaim['id']}")->assertOk();

        $status = $this->withToken($token)->postJson('/api/status-barang', [
            'nama_status' => 'Ditemukan',
            'keterangan' => 'Barang sudah ditemukan',
        ])->assertCreated()->json('data');

        $this->withToken($token)->putJson("/api/status-barang/{$status['id']}", [
            'nama_status' => 'Dikembalikan',
        ])->assertOk()->assertJsonPath('data.nama_status', 'Dikembalikan');
    }
}
