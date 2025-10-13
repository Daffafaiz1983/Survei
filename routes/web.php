<?php
// routes/web.php
use Illuminate\Support\Facades\Route;

// Controller untuk Admin
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FacilityImageController;

// Controller untuk User Biasa
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Awal (Landing Page)
Route::get('/', function () {
    return view('landing');
});

// ROUTE UNTUK SEMUA USER (Mahasiswa, Dosen, Staff)
// Middleware 'auth' memastikan hanya user yang sudah login bisa akses
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SurveyController::class, 'index'])->name('dashboard');
    Route::post('/survei/simpan', [SurveyController::class, 'store'])->name('survei.store');

        // Laporan Fasilitas oleh user
        Route::get('/reports', [\App\Http\Controllers\FacilityReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [\App\Http\Controllers\FacilityReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [\App\Http\Controllers\FacilityReportController::class, 'store'])->name('reports.store');

        // Upload gambar fasilitas untuk saran
        Route::get('/facility-images/create', [FacilityImageController::class, 'create'])->name('facility-images.create');
        Route::post('/facility-images', [FacilityImageController::class, 'store'])->name('facility-images.store');

    // Route untuk Profile bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// === ROUTE KHUSUS UNTUK ADMIN ===
// Middleware 'auth' & 'admin' memastikan hanya admin yang login bisa akses
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Users
    Route::resource('users', UserController::class);

    // Kelola Pertanyaan
    Route::resource('questions', QuestionController::class);

    // Lihat Statistik
    Route::get('/statistics', [StatisticController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export/raw', [StatisticController::class, 'exportRaw'])->name('statistics.export.raw');
    Route::get('/statistics/export/summary', [StatisticController::class, 'exportSummary'])->name('statistics.export.summary');

        // Laporan Fasilitas (Admin)
        Route::get('/reports', [\App\Http\Controllers\Admin\FacilityReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/{id}', [\App\Http\Controllers\Admin\FacilityReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{id}', [\App\Http\Controllers\Admin\FacilityReportController::class, 'update'])->name('reports.update');

        // Kelola Fasilitas (Admin)
        Route::resource('facilities', FacilityController::class);
        
        // Kelola Gambar Fasilitas (Admin)
        Route::resource('facility-images', FacilityImageController::class);
        Route::put('/facility-images/{facilityImage}/status', [FacilityImageController::class, 'updateStatus'])->name('facility-images.update-status');
});


// Auth routes dari Breeze
require __DIR__.'/auth.php';