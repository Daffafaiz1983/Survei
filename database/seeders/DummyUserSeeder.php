<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // === BUAT USER MAHASISWA ===
        // 1. Satu user mahasiswa spesifik
        User::updateOrCreate([
            'email' => 'mahasiswa@example.com',
        ], [
            'name' => 'Budi Mahasiswa',
            'password' => bcrypt('password123'),
            'role' => 'mahasiswa',
        ]);

        // 2. 10 user mahasiswa dummy (default state factory: mahasiswa)
        User::factory()->count(10)->create();

        // === BUAT USER DOSEN ===
        // 1. Satu user dosen spesifik
        User::updateOrCreate([
            'email' => 'dosen@example.com',
        ], [
            'name' => 'Prof. Dr. Sutomo',
            'password' => bcrypt('password123'),
            'role' => 'dosen',
        ]);

        // 2. 5 user dosen dummy
        User::factory()->dosen()->count(5)->create();

        // === BUAT USER STAFF ===
        // 1. Satu user staff spesifik
         User::updateOrCreate([
            'email' => 'staff@example.com',
        ], [
            'name' => 'Andi Staff',
            'password' => bcrypt('password123'),
            'role' => 'staff',
        ]);

        // 2. 5 user staff dummy
        User::factory()->staff()->count(5)->create();
    }
}


