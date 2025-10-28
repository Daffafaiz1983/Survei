<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header dengan tombol aksi -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $semesterReport->title }}</h3>
                    <p class="text-gray-600">{{ $semesterReport->formatted_semester }}</p>
                </div>
                <div class="flex space-x-2">
                    @if($semesterReport->status === 'draft')
                        <form action="{{ route('admin.semester-reports.publish', $semesterReport) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Yakin ingin memublikasi laporan ini?')">
                                <i class="fas fa-publish mr-2"></i>Publikasi
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.semester-reports.unpublish', $semesterReport) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                    onclick="return confirm('Yakin ingin mengubah status menjadi draft?')">
                                <i class="fas fa-unpublish mr-2"></i>Ubah ke Draft
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.semester-reports.edit', $semesterReport) }}" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('admin.semester-reports.regenerate-stats', $semesterReport) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded"
                                onclick="return confirm('Yakin ingin memperbarui statistik?')">
                            <i class="fas fa-sync mr-2"></i>Perbarui Statistik
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informasi Utama -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Informasi Laporan</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Status</label>
                                    <div class="mt-1">
                                        @if($semesterReport->status === 'published')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Dipublikasi
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Draft
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Dibuat Oleh</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->creator->name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Tanggal Dibuat</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Terakhir Diperbarui</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->updated_at->format('d F Y, H:i') }}</p>
                                </div>
                            </div>

                            @if($semesterReport->summary)
                                <div class="mb-6">
                                    <label class="text-sm font-medium text-gray-500">Ringkasan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->summary }}</p>
                                </div>
                            @endif

                            @if($semesterReport->recommendations)
                                <div class="mb-6">
                                    <label class="text-sm font-medium text-gray-500">Rekomendasi</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->recommendations }}</p>
                                </div>
                            @endif

                            @if($semesterReport->conclusions)
                                <div class="mb-6">
                                    <label class="text-sm font-medium text-gray-500">Kesimpulan</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $semesterReport->conclusions }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Statistik Survei -->
                    @if($semesterReport->survey_statistics)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Statistik Survei</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-comments text-blue-600 text-2xl"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-2xl font-bold text-blue-600">
                                                    {{ number_format($semesterReport->survey_statistics['total_answers'] ?? 0) }}
                                                </div>
                                                <div class="text-sm text-blue-800">Total Jawaban</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-users text-green-600 text-2xl"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-2xl font-bold text-green-600">
                                                    {{ number_format($semesterReport->survey_statistics['total_users'] ?? 0) }}
                                                </div>
                                                <div class="text-sm text-green-800">Total Responden</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-purple-600">
                                                    {{ data_get($semesterReport->survey_statistics, 'period.start') ? \Carbon\Carbon::parse(data_get($semesterReport->survey_statistics, 'period.start'))->format('d M Y') : 'N/A' }}
                                                </div>
                                                <div class="text-sm text-purple-800">Periode Mulai</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 p-4 rounded-lg border border-orange-200">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-calendar-check text-orange-600 text-2xl"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-bold text-orange-600">
                                                    {{ data_get($semesterReport->survey_statistics, 'period.end') ? \Carbon\Carbon::parse(data_get($semesterReport->survey_statistics, 'period.end'))->format('d M Y') : 'N/A' }}
                                                </div>
                                                <div class="text-sm text-orange-800">Periode Selesai</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Analisis Per Kategori -->
                                @if($semesterReport->category_analysis && count($semesterReport->category_analysis) > 0)
                                    <h5 class="text-md font-medium text-gray-900 mb-3">Analisis Per Kategori</h5>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pertanyaan</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Jawaban</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Penyelesaian</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($semesterReport->category_analysis as $category)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            <div class="flex items-center">
                                                                <div class="flex-shrink-0 h-8 w-8">
                                                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                                                        <i class="fas fa-folder text-blue-600 text-sm"></i>
                                                                    </div>
                                                                </div>
                                                                <div class="ml-3">
                                                                    {{ $category['category_name'] }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                {{ $category['total_questions'] }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ $category['total_answers'] }}
                                                            </span>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            <div class="flex items-center">
                                                                <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $category['completion_rate'] ?? 0 }}%"></div>
                                                                </div>
                                                                <span class="text-xs text-gray-600 font-medium">{{ $category['completion_rate'] ?? 0 }}%</span>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                            @if(($category['completion_rate'] ?? 0) >= 80)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <i class="fas fa-check-circle mr-1"></i>Baik
                                                                </span>
                                                            @elseif(($category['completion_rate'] ?? 0) >= 50)
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    <i class="fas fa-exclamation-triangle mr-1"></i>Cukup
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    <i class="fas fa-times-circle mr-1"></i>Kurang
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <i class="fas fa-chart-bar text-gray-400 text-4xl mb-4"></i>
                                        <p class="text-gray-500">Belum ada data kategori untuk periode ini</p>
                                    </div>
                                @endif

                                <!-- Grafik Visual -->
                                @if($semesterReport->category_analysis && count($semesterReport->category_analysis) > 0)
                                    <div class="mt-6">
                                        <h5 class="text-md font-medium text-gray-900 mb-3">Visualisasi Data</h5>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Chart Completion Rate -->
                                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                                <h6 class="text-sm font-medium text-gray-700 mb-3">Tingkat Penyelesaian per Kategori</h6>
                                                <div class="relative" style="height: 240px;">
                                                    <canvas id="completionChart"></canvas>
                                                </div>
                                            </div>
                                            
                                            <!-- Chart Total Answers -->
                                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                                <h6 class="text-sm font-medium text-gray-700 mb-3">Distribusi Jawaban per Kategori</h6>
                                                <div class="relative" style="height: 240px;">
                                                    <canvas id="answersChart"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- Analisis Mendalam -->
                                @if(isset($semesterReport->survey_statistics['daily_trends']) || isset($semesterReport->survey_statistics['hourly_activity']))
                                    <div class="mt-6">
                                        <h5 class="text-md font-medium text-gray-900 mb-3">Analisis Mendalam</h5>
                                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                            <!-- Chart Daily Trends -->
                                            @if(isset($semesterReport->survey_statistics['daily_trends']))
                                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                                    <h6 class="text-sm font-medium text-gray-700 mb-3">Trend Harian Survei</h6>
                                                    <div class="relative" style="height: 240px;">
                                                        <canvas id="dailyTrendsChart"></canvas>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            <!-- Chart Hourly Activity -->
                                            @if(isset($semesterReport->survey_statistics['hourly_activity']))
                                                <div class="bg-white p-4 rounded-lg border border-gray-200">
                                                    <h6 class="text-sm font-medium text-gray-700 mb-3">Aktivitas per Jam</h6>
                                                    <div class="relative" style="height: 240px;">
                                                        <canvas id="hourlyActivityChart"></canvas>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Statistik Tambahan -->
                                        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-2xl font-bold text-blue-600">
                                                            {{ $semesterReport->survey_statistics['average_answers_per_user'] ?? 0 }}
                                                        </div>
                                                        <div class="text-sm text-blue-800">Rata-rata Jawaban per User</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-clock text-green-600 text-2xl"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-2xl font-bold text-green-600">
                                                            {{ data_get($semesterReport->survey_statistics, 'peak_hour') !== null ? data_get($semesterReport->survey_statistics, 'peak_hour') . ':00' : 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-green-800">Jam Puncak Aktivitas</div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-calendar-day text-purple-600 text-2xl"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-bold text-purple-600">
                                                            {{ data_get($semesterReport->survey_statistics, 'peak_day') ? \Carbon\Carbon::parse(data_get($semesterReport->survey_statistics, 'peak_day'))->format('d M Y') : 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-purple-800">Hari Puncak Aktivitas</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Statistik Fasilitas -->
                    @if($semesterReport->facility_statistics)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Statistik Laporan Fasilitas</h4>
                                
                                @if(isset($semesterReport->facility_statistics['by_status']))
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                        @foreach($semesterReport->facility_statistics['by_status'] as $status => $count)
                                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        @if($status === 'resolved')
                                                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                                        @elseif($status === 'pending')
                                                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                                                        @elseif($status === 'in_progress')
                                                            <i class="fas fa-tools text-blue-600 text-2xl"></i>
                                                        @else
                                                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                                                        @endif
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-2xl font-bold text-gray-600">{{ number_format($count) }}</div>
                                                        <div class="text-sm text-gray-800 capitalize">{{ ucfirst($status) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Statistik Tambahan -->
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-lg border border-blue-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-alt text-blue-600 text-2xl"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-2xl font-bold text-blue-600">
                                                        {{ number_format($semesterReport->facility_statistics['total_reports'] ?? 0) }}
                                                    </div>
                                                    <div class="text-sm text-blue-800">Total Laporan</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-lg border border-green-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-check-double text-green-600 text-2xl"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-2xl font-bold text-green-600">
                                                        {{ number_format($semesterReport->facility_statistics['resolved_reports'] ?? 0) }}
                                                    </div>
                                                    <div class="text-sm text-green-800">Laporan Selesai</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-4 rounded-lg border border-purple-200">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-percentage text-purple-600 text-2xl"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-2xl font-bold text-purple-600">
                                                        {{ $semesterReport->facility_statistics['resolution_rate'] ?? 0 }}%
                                                    </div>
                                                    <div class="text-sm text-purple-800">Tingkat Penyelesaian</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- Fallback untuk format lama -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                        @foreach($semesterReport->facility_statistics as $status => $count)
                                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="fas fa-chart-bar text-gray-600 text-2xl"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-2xl font-bold text-gray-600">{{ number_format($count) }}</div>
                                                        <div class="text-sm text-gray-800 capitalize">{{ ucfirst($status) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="text-center py-8">
                                    <i class="fas fa-building text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-500">Belum ada data laporan fasilitas untuk periode ini</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Tombol Aksi -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Aksi</h4>
                            <div class="space-y-2">
                                <a href="{{ route('admin.semester-reports.edit', $semesterReport) }}" 
                                   class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    <i class="fas fa-edit mr-2"></i>Edit Laporan
                                </a>
                                <a href="{{ route('admin.semester-reports.export-pdf', $semesterReport) }}" 
                                   class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                                </a>
                                <form action="{{ route('admin.semester-reports.destroy', $semesterReport) }}" 
                                      method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                        <i class="fas fa-trash mr-2"></i>Hapus Laporan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Periode -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Periode Survei</h4>
                            @if($semesterReport->survey_statistics)
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Mulai:</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ data_get($semesterReport->survey_statistics, 'period.start') ? \Carbon\Carbon::parse(data_get($semesterReport->survey_statistics, 'period.start'))->format('d M Y') : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Selesai:</span>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ data_get($semesterReport->survey_statistics, 'period.end') ? \Carbon\Carbon::parse(data_get($semesterReport->survey_statistics, 'period.end'))->format('d M Y') : 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if($semesterReport->category_analysis && count($semesterReport->category_analysis) > 0)
                // Data untuk grafik
                const categoryData = @json($semesterReport->category_analysis);
                
                // Chart Completion Rate
                const completionCtx = document.getElementById('completionChart');
                if (completionCtx) {
                    new Chart(completionCtx, {
                        type: 'bar',
                        data: {
                            labels: categoryData.map(item => item.category_name),
                            datasets: [{
                                label: 'Tingkat Penyelesaian (%)',
                                data: categoryData.map(item => item.completion_rate || 0),
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(139, 92, 246, 0.8)',
                                    'rgba(236, 72, 153, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(139, 92, 246, 1)',
                                    'rgba(236, 72, 153, 1)'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: function(value) {
                                            return value + '%';
                                        }
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.parsed.y + '%';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
                
                // Chart Total Answers
                const answersCtx = document.getElementById('answersChart');
                if (answersCtx) {
                    new Chart(answersCtx, {
                        type: 'doughnut',
                        data: {
                            labels: categoryData.map(item => item.category_name),
                            datasets: [{
                                data: categoryData.map(item => item.total_answers || 0),
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.8)',
                                    'rgba(16, 185, 129, 0.8)',
                                    'rgba(245, 158, 11, 0.8)',
                                    'rgba(239, 68, 68, 0.8)',
                                    'rgba(139, 92, 246, 0.8)',
                                    'rgba(236, 72, 153, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(59, 130, 246, 1)',
                                    'rgba(16, 185, 129, 1)',
                                    'rgba(245, 158, 11, 1)',
                                    'rgba(239, 68, 68, 1)',
                                    'rgba(139, 92, 246, 1)',
                                    'rgba(236, 72, 153, 1)'
                                ],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            @endif
            
            // Chart Daily Trends
            @if(isset($semesterReport->survey_statistics['daily_trends']))
                const dailyTrendsCtx = document.getElementById('dailyTrendsChart');
                if (dailyTrendsCtx) {
                    const dailyData = @json($semesterReport->survey_statistics['daily_trends']);
                    new Chart(dailyTrendsCtx, {
                        type: 'line',
                        data: {
                            labels: dailyData.map(item => new Date(item.date).toLocaleDateString('id-ID')),
                            datasets: [{
                                label: 'Jumlah Jawaban',
                                data: dailyData.map(item => item.count),
                                borderColor: 'rgba(59, 130, 246, 1)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            @endif
            
            // Chart Hourly Activity
            @if(isset($semesterReport->survey_statistics['hourly_activity']))
                const hourlyActivityCtx = document.getElementById('hourlyActivityChart');
                if (hourlyActivityCtx) {
                    const hourlyData = @json($semesterReport->survey_statistics['hourly_activity']);
                    new Chart(hourlyActivityCtx, {
                        type: 'bar',
                        data: {
                            labels: hourlyData.map(item => item.hour + ':00'),
                            datasets: [{
                                label: 'Aktivitas per Jam',
                                data: hourlyData.map(item => item.count),
                                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                                borderColor: 'rgba(16, 185, 129, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            @endif
        });
    </script>
    @endpush

</x-admin-layout>
