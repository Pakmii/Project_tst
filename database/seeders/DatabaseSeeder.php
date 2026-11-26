<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Membuat 1 akun admin dan 1 akun user sesuai credentials.txt
     */
    public function run(): void
    {
        // ── Admin Account ─────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin',
                'password' => 'admin123',
                'role'     => 'admin',
            ],
        );

        // ── User Account ──────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            [
                'name'     => 'User',
                'password' => 'user123',
                'role'     => 'user',
            ],
        );
    }
}
