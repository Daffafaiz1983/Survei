<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mahasiswa (default state pada factory)
        User::factory()->count(50)->create();

        // Dosen
        User::factory()->dosen()->count(15)->create();

        // Staff
        User::factory()->staff()->count(15)->create();
    }
}


