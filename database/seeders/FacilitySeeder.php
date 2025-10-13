<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Laboratorium Komputer',
                'description' => 'Laboratorium komputer untuk praktikum mahasiswa',
                'location' => 'Gedung A Lantai 2',
                'status' => 'active',
            ],
            [
                'name' => 'Perpustakaan',
                'description' => 'Perpustakaan dengan koleksi buku dan ruang baca',
                'location' => 'Gedung B Lantai 1',
                'status' => 'active',
            ],
            [
                'name' => 'Ruang Kelas 101',
                'description' => 'Ruang kelas untuk perkuliahan',
                'location' => 'Gedung A Lantai 1',
                'status' => 'active',
            ],
            [
                'name' => 'Ruang Kelas 102',
                'description' => 'Ruang kelas untuk perkuliahan',
                'location' => 'Gedung A Lantai 1',
                'status' => 'active',
            ],
            [
                'name' => 'Laboratorium Bahasa',
                'description' => 'Laboratorium untuk pembelajaran bahasa asing',
                'location' => 'Gedung C Lantai 2',
                'status' => 'active',
            ],
            [
                'name' => 'Ruang Seminar',
                'description' => 'Ruang seminar dan presentasi',
                'location' => 'Gedung B Lantai 3',
                'status' => 'active',
            ],
            [
                'name' => 'Kantin',
                'description' => 'Kantin mahasiswa dan dosen',
                'location' => 'Gedung D Lantai 1',
                'status' => 'active',
            ],
            [
                'name' => 'Parkir Motor',
                'description' => 'Area parkir untuk kendaraan bermotor',
                'location' => 'Depan Gedung A',
                'status' => 'active',
            ],
            [
                'name' => 'Parkir Mobil',
                'description' => 'Area parkir untuk kendaraan roda empat',
                'location' => 'Depan Gedung B',
                'status' => 'active',
            ],
            [
                'name' => 'Toilet Laki-laki',
                'description' => 'Fasilitas toilet untuk laki-laki',
                'location' => 'Setiap lantai gedung',
                'status' => 'active',
            ],
            [
                'name' => 'Toilet Perempuan',
                'description' => 'Fasilitas toilet untuk perempuan',
                'location' => 'Setiap lantai gedung',
                'status' => 'active',
            ],
            [
                'name' => 'Lift',
                'description' => 'Fasilitas lift untuk akses antar lantai',
                'location' => 'Gedung A, B, C',
                'status' => 'active',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::create($facility);
        }
    }
}
