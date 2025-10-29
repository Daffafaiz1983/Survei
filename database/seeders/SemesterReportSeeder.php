<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SemesterReport;
use App\Models\User;

class SemesterReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari admin user
        $admin = User::where('email', 'admin@fisip.ui.ac.id')->first();
        
        if (!$admin) {
            $this->command->warn('Admin user tidak ditemukan. Membuat admin user...');
            $admin = User::create([
                'name' => 'Admin SurveiV2',
                'email' => 'admin@fisip.ui.ac.id',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]);
        }

        // Data laporan semester dummy
        $reports = [
            [
                'title' => 'Laporan Survei Semester Ganjil 2024/2025',
                'semester' => 'Ganjil',
                'academic_year' => 2024,
                'summary' => 'Laporan survei semester ganjil tahun akademik 2024/2025 menunjukkan hasil yang sangat positif dengan tingkat kepuasan mahasiswa yang meningkat signifikan. Responden memberikan feedback yang konstruktif mengenai fasilitas kampus dan proses pembelajaran.',
                'recommendations' => '1. Meningkatkan kapasitas laboratorium komputer dengan penambahan 20 unit PC terbaru\n2. Memperluas koleksi buku referensi di perpustakaan dengan fokus pada literatur terbaru\n3. Memperbaiki sistem pendingin udara di ruang kuliah utama\n4. Menambah titik akses WiFi di area parkir dan kantin\n5. Membuat jadwal maintenance rutin untuk fasilitas multimedia',
                'conclusions' => 'Secara keseluruhan, kepuasan mahasiswa terhadap fasilitas kampus menunjukkan tren positif dengan skor rata-rata 8.2/10. Peningkatan terbesar terlihat pada aspek teknologi informasi dan fasilitas pembelajaran. Rekomendasi yang diberikan akan menjadi prioritas untuk semester berikutnya.',
                'status' => 'published',
            ],
            [
                'title' => 'Laporan Survei Semester Genap 2023/2024',
                'semester' => 'Genap',
                'academic_year' => 2023,
                'summary' => 'Laporan survei semester genap tahun akademik 2023/2024 menunjukkan perbaikan yang signifikan dibanding semester sebelumnya. Implementasi rekomendasi dari laporan sebelumnya memberikan dampak positif.',
                'recommendations' => '1. Melanjutkan program perbaikan infrastruktur yang sudah dimulai\n2. Meningkatkan program pelatihan untuk staf teknisi\n3. Membuat sistem monitoring real-time untuk fasilitas kritis\n4. Mengembangkan aplikasi mobile untuk laporan fasilitas\n5. Membentuk tim khusus untuk evaluasi berkala fasilitas',
                'conclusions' => 'Terdapat peningkatan kepuasan sebesar 15% dibanding semester ganjil sebelumnya. Implementasi rekomendasi sebelumnya berhasil meningkatkan kualitas layanan. Perlu konsistensi dalam maintenance dan pengembangan berkelanjutan.',
                'status' => 'published',
            ],
            [
                'title' => 'Laporan Survei Semester Ganjil 2023/2024',
                'semester' => 'Ganjil',
                'academic_year' => 2023,
                'summary' => 'Laporan survei semester ganjil tahun akademik 2023/2024 sebagai baseline untuk perbandingan semester berikutnya. Survei ini memberikan gambaran awal kondisi fasilitas kampus sebelum implementasi perbaikan.',
                'recommendations' => '1. Prioritas utama: perbaikan sistem pendingin udara\n2. Upgrade fasilitas laboratorium dengan peralatan terbaru\n3. Peningkatan kapasitas ruang kuliah\n4. Pengembangan sistem informasi akademik\n5. Pelatihan staf untuk penggunaan teknologi baru',
                'conclusions' => 'Hasil survei menunjukkan kebutuhan mendesak akan perbaikan infrastruktur kampus. Skor kepuasan rata-rata 6.8/10 menunjukkan masih banyak area yang perlu diperbaiki. Laporan ini menjadi dasar untuk perencanaan perbaikan di semester berikutnya.',
                'status' => 'draft',
            ],
        ];

        foreach ($reports as $reportData) {
            $report = SemesterReport::create([
                'title' => $reportData['title'],
                'semester' => $reportData['semester'],
                'academic_year' => $reportData['academic_year'],
                'summary' => $reportData['summary'],
                'recommendations' => $reportData['recommendations'],
                'conclusions' => $reportData['conclusions'],
                'status' => $reportData['status'],
                'created_by' => $admin->id,
            ]);

            // Generate statistik otomatis
            $report->generateSurveyStatistics();
            $report->save();

            $this->command->info("Laporan semester '{$report->title}' berhasil dibuat dengan ID: {$report->id}");
        }

        $this->command->info('SemesterReportSeeder selesai dijalankan!');
    }
}
