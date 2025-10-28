<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SemesterReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'semester',
        'academic_year',
        'summary',
        'survey_statistics',
        'facility_statistics',
        'category_analysis',
        'recommendations',
        'conclusions',
        'status',
        'created_by',
    ];

    protected $casts = [
        'survey_statistics' => 'array',
        'facility_statistics' => 'array',
        'category_analysis' => 'array',
        'academic_year' => 'integer',
    ];

    /**
     * Get the admin user who created this report.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk filter berdasarkan semester dan tahun akademik.
     */
    public function scopeForSemester($query, $semester, $academicYear)
    {
        return $query->where('semester', $semester)
                    ->where('academic_year', $academicYear);
    }

    /**
     * Scope untuk laporan yang sudah dipublikasi.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope untuk laporan draft.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Get semester options.
     */
    public static function getSemesterOptions()
    {
        return [
            'Ganjil' => 'Ganjil',
            'Genap' => 'Genap',
        ];
    }

    /**
     * Get academic year options (5 tahun terakhir dan 2 tahun ke depan).
     */
    public static function getAcademicYearOptions()
    {
        $currentYear = date('Y');
        $years = [];
        
        for ($i = $currentYear - 5; $i <= $currentYear + 2; $i++) {
            $years[$i] = $i . '/' . ($i + 1);
        }
        
        return $years;
    }

    /**
     * Generate report statistics from survey data.
     */
    public function generateSurveyStatistics()
    {
        try {
            $startDate = $this->getSemesterStartDate();
            $endDate = $this->getSemesterEndDate();

            // Statistik survei berdasarkan periode semester
            $totalAnswers = Answer::whereBetween('created_at', [$startDate, $endDate])->count();
            $totalUsers = Answer::whereBetween('created_at', [$startDate, $endDate])
                              ->distinct('user_id')
                              ->count('user_id');
            
            // Analisis trend harian
            $dailyTrends = Answer::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Analisis jam aktif
            $hourlyActivity = Answer::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as count')
                ->groupBy('hour')
                ->orderBy('hour')
                ->get();
        
        // Statistik per kategori dengan data yang lebih detail
        $categoryStats = Category::with(['questions' => function($query) {
            $query->where('is_active', true);
        }])->get()->map(function($category) use ($startDate, $endDate) {
            $totalAnswers = 0;
            $totalQuestions = $category->questions->count();
            
            foreach ($category->questions as $question) {
                $totalAnswers += Answer::where('question_id', $question->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->count();
            }
            
            return [
                'category_name' => $category->name,
                'total_questions' => $totalQuestions,
                'total_answers' => $totalAnswers,
                'completion_rate' => $totalQuestions > 0 ? round(($totalAnswers / $totalQuestions) * 100, 2) : 0,
            ];
        })->filter(function($category) {
            return $category['total_questions'] > 0; // Hanya kategori yang memiliki pertanyaan
        });

        // Statistik fasilitas dengan detail lebih lengkap
        $facilityStats = FacilityReport::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Tambahkan statistik tambahan
        $totalFacilityReports = FacilityReport::whereBetween('created_at', [$startDate, $endDate])->count();
        $resolvedReports = FacilityReport::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'resolved')
            ->count();
        $resolutionRate = $totalFacilityReports > 0 ? round(($resolvedReports / $totalFacilityReports) * 100, 2) : 0;

            $this->survey_statistics = [
                'total_answers' => $totalAnswers,
                'total_users' => $totalUsers,
                'period' => [
                    'start' => $startDate->format('Y-m-d'),
                    'end' => $endDate->format('Y-m-d'),
                ],
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'daily_trends' => $dailyTrends->toArray(),
                'hourly_activity' => $hourlyActivity->toArray(),
                'average_answers_per_user' => $totalUsers > 0 ? round($totalAnswers / $totalUsers, 2) : 0,
                'peak_hour' => $hourlyActivity->sortByDesc('count')->first()->hour ?? null,
                'peak_day' => $dailyTrends->sortByDesc('count')->first()->date ?? null,
            ];

            $this->category_analysis = $categoryStats->values()->toArray();
            $this->facility_statistics = [
                'by_status' => $facilityStats->toArray(),
                'total_reports' => $totalFacilityReports,
                'resolved_reports' => $resolvedReports,
                'resolution_rate' => $resolutionRate,
            ];
        } catch (\Exception $e) {
            // Fallback jika ada error
            $this->survey_statistics = [
                'total_answers' => 0,
                'total_users' => 0,
                'period' => [
                    'start' => $this->getSemesterStartDate()->format('Y-m-d'),
                    'end' => $this->getSemesterEndDate()->format('Y-m-d'),
                ],
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'daily_trends' => [],
                'hourly_activity' => [],
                'average_answers_per_user' => 0,
                'peak_hour' => null,
                'peak_day' => null,
            ];
            $this->category_analysis = [];
            $this->facility_statistics = [
                'by_status' => [],
                'total_reports' => 0,
                'resolved_reports' => 0,
                'resolution_rate' => 0,
            ];
        }
    }

    /**
     * Get semester start date based on semester and academic year.
     */
    private function getSemesterStartDate()
    {
        if ($this->semester === 'Ganjil') {
            return \Carbon\Carbon::create($this->academic_year, 8, 1); // Agustus
        } else {
            return \Carbon\Carbon::create($this->academic_year + 1, 1, 1); // Januari
        }
    }

    /**
     * Get semester end date based on semester and academic year.
     */
    private function getSemesterEndDate()
    {
        if ($this->semester === 'Ganjil') {
            return \Carbon\Carbon::create($this->academic_year, 12, 31); // Desember
        } else {
            return \Carbon\Carbon::create($this->academic_year + 1, 6, 30); // Juni
        }
    }

    /**
     * Get formatted semester and academic year.
     */
    public function getFormattedSemesterAttribute()
    {
        return $this->semester . ' ' . $this->academic_year . '/' . ($this->academic_year + 1);
    }
}
