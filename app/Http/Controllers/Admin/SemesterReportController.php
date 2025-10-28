<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SemesterReport;
use App\Models\Answer;
use App\Models\FacilityReport;
use App\Models\Category;
use App\Models\User;
use App\Mail\SemesterReportPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SemesterReportController extends Controller
{
    /**
     * Display a listing of semester reports.
     */
    public function index()
    {
        $reports = SemesterReport::with('creator')
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester', 'desc')
            ->paginate(10);

        return view('admin.semester-reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new semester report.
     */
    public function create()
    {
        $semesterOptions = SemesterReport::getSemesterOptions();
        $academicYearOptions = SemesterReport::getAcademicYearOptions();
        
        return view('admin.semester-reports.create', compact('semesterOptions', 'academicYearOptions'));
    }

    /**
     * Store a newly created semester report.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|min:10',
            'semester' => 'required|in:Ganjil,Genap',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'summary' => 'nullable|string|max:2000|min:20',
            'recommendations' => 'nullable|string|max:2000|min:20',
            'conclusions' => 'nullable|string|max:2000|min:20',
        ], [
            'title.required' => 'Judul laporan wajib diisi.',
            'title.min' => 'Judul laporan minimal 10 karakter.',
            'title.max' => 'Judul laporan maksimal 255 karakter.',
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'academic_year.required' => 'Tahun akademik wajib dipilih.',
            'academic_year.integer' => 'Tahun akademik harus berupa angka.',
            'academic_year.min' => 'Tahun akademik minimal 2020.',
            'academic_year.max' => 'Tahun akademik maksimal 2030.',
            'summary.min' => 'Ringkasan minimal 20 karakter.',
            'summary.max' => 'Ringkasan maksimal 2000 karakter.',
            'recommendations.min' => 'Rekomendasi minimal 20 karakter.',
            'recommendations.max' => 'Rekomendasi maksimal 2000 karakter.',
            'conclusions.min' => 'Kesimpulan minimal 20 karakter.',
            'conclusions.max' => 'Kesimpulan maksimal 2000 karakter.',
        ]);

        // Check if report already exists for this semester and year
        $existingReport = SemesterReport::forSemester($request->semester, $request->academic_year)->first();
        
        if ($existingReport) {
            return redirect()->back()
                ->withErrors(['semester' => 'Laporan untuk semester ' . $request->semester . ' ' . $request->academic_year . '/' . ($request->academic_year + 1) . ' sudah ada.'])
                ->withInput();
        }

        $report = SemesterReport::create([
            'title' => $request->title,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'summary' => $request->summary,
            'recommendations' => $request->recommendations,
            'conclusions' => $request->conclusions,
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        // Generate statistics automatically
        $report->generateSurveyStatistics();
        $report->save();

        return redirect()->route('admin.semester-reports.show', $report)
            ->with('success', 'Laporan semester berhasil dibuat!');
    }

    /**
     * Display the specified semester report.
     */
    public function show(SemesterReport $semesterReport)
    {
        $semesterReport->load('creator');
        
        return view('admin.semester-reports.show', compact('semesterReport'));
    }

    /**
     * Show the form for editing the specified semester report.
     */
    public function edit(SemesterReport $semesterReport)
    {
        $semesterOptions = SemesterReport::getSemesterOptions();
        $academicYearOptions = SemesterReport::getAcademicYearOptions();
        
        return view('admin.semester-reports.edit', compact('semesterReport', 'semesterOptions', 'academicYearOptions'));
    }

    /**
     * Update the specified semester report.
     */
    public function update(Request $request, SemesterReport $semesterReport)
    {
        $request->validate([
            'title' => 'required|string|max:255|min:10',
            'semester' => 'required|in:Ganjil,Genap',
            'academic_year' => 'required|integer|min:2020|max:2030',
            'summary' => 'nullable|string|max:2000|min:20',
            'recommendations' => 'nullable|string|max:2000|min:20',
            'conclusions' => 'nullable|string|max:2000|min:20',
        ], [
            'title.required' => 'Judul laporan wajib diisi.',
            'title.min' => 'Judul laporan minimal 10 karakter.',
            'title.max' => 'Judul laporan maksimal 255 karakter.',
            'semester.required' => 'Semester wajib dipilih.',
            'semester.in' => 'Semester harus Ganjil atau Genap.',
            'academic_year.required' => 'Tahun akademik wajib dipilih.',
            'academic_year.integer' => 'Tahun akademik harus berupa angka.',
            'academic_year.min' => 'Tahun akademik minimal 2020.',
            'academic_year.max' => 'Tahun akademik maksimal 2030.',
            'summary.min' => 'Ringkasan minimal 20 karakter.',
            'summary.max' => 'Ringkasan maksimal 2000 karakter.',
            'recommendations.min' => 'Rekomendasi minimal 20 karakter.',
            'recommendations.max' => 'Rekomendasi maksimal 2000 karakter.',
            'conclusions.min' => 'Kesimpulan minimal 20 karakter.',
            'conclusions.max' => 'Kesimpulan maksimal 2000 karakter.',
        ]);

        // Check if another report exists for this semester and year (excluding current report)
        $existingReport = SemesterReport::forSemester($request->semester, $request->academic_year)
            ->where('id', '!=', $semesterReport->id)
            ->first();
        
        if ($existingReport) {
            return redirect()->back()
                ->withErrors(['semester' => 'Laporan untuk semester ' . $request->semester . ' ' . $request->academic_year . '/' . ($request->academic_year + 1) . ' sudah ada.'])
                ->withInput();
        }

        $semesterReport->update([
            'title' => $request->title,
            'semester' => $request->semester,
            'academic_year' => $request->academic_year,
            'summary' => $request->summary,
            'recommendations' => $request->recommendations,
            'conclusions' => $request->conclusions,
        ]);

        // Regenerate statistics if semester or year changed
        if ($semesterReport->wasChanged(['semester', 'academic_year'])) {
            $semesterReport->generateSurveyStatistics();
            $semesterReport->save();
        }

        return redirect()->route('admin.semester-reports.show', $semesterReport)
            ->with('success', 'Laporan semester berhasil diperbarui!');
    }

    /**
     * Remove the specified semester report.
     */
    public function destroy(SemesterReport $semesterReport)
    {
        $semesterReport->delete();

        return redirect()->route('admin.semester-reports.index')
            ->with('success', 'Laporan semester berhasil dihapus!');
    }

    /**
     * Publish the specified semester report.
     */
    public function publish(SemesterReport $semesterReport)
    {
        $semesterReport->update(['status' => 'published']);

        // Kirim email notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new SemesterReportPublished($semesterReport));
        }

        return redirect()->back()
            ->with('success', 'Laporan semester berhasil dipublikasi! Email notifikasi telah dikirim ke semua admin.');
    }

    /**
     * Unpublish the specified semester report.
     */
    public function unpublish(SemesterReport $semesterReport)
    {
        $semesterReport->update(['status' => 'draft']);

        return redirect()->back()
            ->with('success', 'Laporan semester berhasil diubah menjadi draft!');
    }

    /**
     * Regenerate statistics for the specified semester report.
     */
    public function regenerateStats(SemesterReport $semesterReport)
    {
        $semesterReport->generateSurveyStatistics();
        $semesterReport->save();

        return redirect()->back()
            ->with('success', 'Statistik laporan berhasil diperbarui!');
    }

    /**
     * Export semester report to PDF.
     */
    public function exportPdf(SemesterReport $semesterReport)
    {
        try {
            // Load necessary relationships
            $semesterReport->load('creator');
            
            // Generate HTML content for PDF
            $html = view('admin.semester-reports.pdf', compact('semesterReport'))->render();
            
            // Return HTML view with proper headers for PDF generation
            return response()->view('admin.semester-reports.pdf', compact('semesterReport'))
                ->header('Content-Type', 'text/html; charset=utf-8')
                ->header('Content-Disposition', 'inline; filename="laporan-semester-' . $semesterReport->semester . '-' . $semesterReport->academic_year . '.html"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengexport laporan: ' . $e->getMessage());
        }
    }

    /**
     * Get detailed statistics for a specific semester report.
     */
    public function getStatistics(SemesterReport $semesterReport)
    {
        $startDate = $this->getSemesterStartDate($semesterReport);
        $endDate = $this->getSemesterEndDate($semesterReport);

        // Detailed answer statistics
        $answerStats = Answer::with(['question.categoryRef', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('question.categoryRef.name')
            ->map(function($answers, $categoryName) {
                return [
                    'category' => $categoryName,
                    'total_answers' => $answers->count(),
                    'unique_users' => $answers->pluck('user_id')->unique()->count(),
                    'questions' => $answers->groupBy('question.question_text')->map(function($questionAnswers) {
                        return [
                            'question' => $questionAnswers->first()->question->question_text,
                            'answers' => $questionAnswers->pluck('answer_text')->toArray(),
                        ];
                    }),
                ];
            });

        // Facility report statistics
        $facilityStats = FacilityReport::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy('status')
            ->map(function($reports, $status) {
                return [
                    'status' => $status,
                    'count' => $reports->count(),
                    'reports' => $reports->map(function($report) {
                        return [
                            'title' => $report->title,
                            'location' => $report->location,
                            'user' => $report->user->name,
                            'created_at' => $report->created_at->format('d/m/Y'),
                        ];
                    }),
                ];
            });

        return response()->json([
            'answer_statistics' => $answerStats,
            'facility_statistics' => $facilityStats,
            'period' => [
                'start' => $startDate->format('d F Y'),
                'end' => $endDate->format('d F Y'),
            ],
        ]);
    }

    /**
     * Helper method to get semester start date.
     */
    private function getSemesterStartDate(SemesterReport $report)
    {
        if ($report->semester === 'Ganjil') {
            return \Carbon\Carbon::create($report->academic_year, 8, 1);
        } else {
            return \Carbon\Carbon::create($report->academic_year + 1, 1, 1);
        }
    }

    /**
     * Helper method to get semester end date.
     */
    private function getSemesterEndDate(SemesterReport $report)
    {
        if ($report->semester === 'Ganjil') {
            return \Carbon\Carbon::create($report->academic_year, 12, 31);
        } else {
            return \Carbon\Carbon::create($report->academic_year + 1, 6, 30);
        }
    }

    /**
     * Show analytics dashboard for semester reports.
     */
    public function analytics(Request $request)
    {
        try {
            $query = SemesterReport::query();
            
            // Filter by semester
            if ($request->filled('semester')) {
                $query->where('semester', $request->semester);
            }
            
            // Filter by year
            if ($request->filled('year')) {
                $query->where('academic_year', $request->year);
            }
            
            $reports = $query->orderBy('created_at', 'desc')->get();
            
            // Calculate statistics
            $totalReports = $reports->count();
            $publishedReports = $reports->where('status', 'published')->count();
            $draftReports = $reports->where('status', 'draft')->count();
            
            // Calculate average answers (safe access when survey_statistics is null)
            $totalAnswers = $reports->sum(function($report) {
                return data_get($report->survey_statistics, 'total_answers', 0);
            });
            $averageAnswers = $totalReports > 0 ? round($totalAnswers / $totalReports, 2) : 0;
            
            // Semester comparison
            $ganjilCount = $reports->where('semester', 'Ganjil')->count();
            $genapCount = $reports->where('semester', 'Genap')->count();
            
            // Year trends
            $yearData = $reports->groupBy('academic_year')->map(function($yearReports) {
                return $yearReports->count();
            });
            
            $yearLabels = $yearData->keys()->map(function($year) {
                return $year . '/' . ($year + 1);
            })->toArray();
            
            $yearData = $yearData->values()->toArray();
            
            // Recent reports (last 5)
            $recentReports = $reports->take(5);
            
            return view('admin.semester-reports.analytics', compact(
                'totalReports',
                'publishedReports', 
                'draftReports',
                'averageAnswers',
                'ganjilCount',
                'genapCount',
                'yearLabels',
                'yearData',
                'recentReports'
            ));
        } catch (\Exception $e) {
            // Fallback jika ada error
            return view('admin.semester-reports.analytics', [
                'totalReports' => 0,
                'publishedReports' => 0,
                'draftReports' => 0,
                'averageAnswers' => 0,
                'ganjilCount' => 0,
                'genapCount' => 0,
                'yearLabels' => [],
                'yearData' => [],
                'recentReports' => collect([])
            ]);
        }
    }
}
