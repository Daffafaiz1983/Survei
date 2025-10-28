<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('semester_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul laporan semester
            $table->string('semester'); // Ganjil/Genap
            $table->year('academic_year'); // Tahun akademik
            $table->text('summary')->nullable(); // Ringkasan laporan
            $table->json('survey_statistics')->nullable(); // Statistik survei dalam format JSON
            $table->json('facility_statistics')->nullable(); // Statistik fasilitas dalam format JSON
            $table->json('category_analysis')->nullable(); // Analisis per kategori dalam format JSON
            $table->text('recommendations')->nullable(); // Rekomendasi
            $table->text('conclusions')->nullable(); // Kesimpulan
            $table->string('status')->default('draft'); // draft, published
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // Admin yang membuat
            $table->timestamps();
            
            // Index untuk pencarian berdasarkan semester dan tahun
            $table->index(['semester', 'academic_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_reports');
    }
};
