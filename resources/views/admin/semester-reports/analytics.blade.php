<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analisis Laporan Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Periode -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Analisis</h3>
                    <form method="GET" action="{{ route('admin.semester-reports.analytics') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                            <select name="semester" id="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Semester</option>
                                <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>
                        <div>
                            <label for="year" class="block text-sm font-medium text-gray-700 mb-2">Tahun Akademik</label>
                            <select name="year" id="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Tahun</option>
                                @for($year = date('Y') - 5; $year <= date('Y') + 2; $year++)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}/{{ $year + 1 }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistik Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-blue-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-blue-600">{{ $totalReports ?? 0 }}</div>
                            <div class="text-sm text-blue-800">Total Laporan</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-green-600">{{ $publishedReports ?? 0 }}</div>
                            <div class="text-sm text-green-800">Laporan Dipublikasi</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-6 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-edit text-yellow-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-yellow-600">{{ $draftReports ?? 0 }}</div>
                            <div class="text-sm text-yellow-800">Laporan Draft</div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-lg border border-purple-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-purple-600 text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <div class="text-2xl font-bold text-purple-600">{{ $averageAnswers ?? 0 }}</div>
                            <div class="text-sm text-purple-800">Rata-rata Jawaban</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Analisis -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chart Semester Comparison -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Perbandingan Semester</h4>
                        <div class="relative" style="height: 300px;">
                            <canvas id="semesterComparisonChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Chart Year Trends -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Trend Tahunan</h4>
                        <div class="relative" style="height: 300px;">
                            <canvas id="yearTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Laporan Terbaru -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Laporan Terbaru</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentReports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->title }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->formatted_semester }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($report->status === 'published')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Dipublikasi
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.semester-reports.show', $report) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada laporan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
            // Chart Semester Comparison
            const semesterComparisonCanvas = document.getElementById('semesterComparisonChart');
            if (semesterComparisonCanvas) {
                const ganjilCount = {{ $ganjilCount ?? 0 }};
                const genapCount = {{ $genapCount ?? 0 }};
                
                if (ganjilCount > 0 || genapCount > 0) {
                    try {
                        const semesterComparisonCtx = semesterComparisonCanvas.getContext('2d');
                        new Chart(semesterComparisonCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Ganjil', 'Genap'],
                                datasets: [{
                                    data: [ganjilCount, genapCount],
                                    backgroundColor: [
                                        'rgba(59, 130, 246, 0.8)',
                                        'rgba(16, 185, 129, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgba(59, 130, 246, 1)',
                                        'rgba(16, 185, 129, 1)'
                                    ],
                                    borderWidth: 2
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        semesterComparisonCanvas.parentElement.innerHTML = '<div class="text-center py-8 text-gray-500">Gagal memuat grafik</div>';
                    }
                } else {
                    // Show "No data" message
                    semesterComparisonCanvas.parentElement.innerHTML = '<div class="text-center py-8 text-gray-500">Belum ada data laporan</div>';
                }
            }
            
            // Chart Year Trends
            const yearTrendsCanvas = document.getElementById('yearTrendsChart');
            if (yearTrendsCanvas) {
                const yearLabels = @json($yearLabels ?? []);
                const yearData = @json($yearData ?? []);
                
                if (yearLabels.length > 0 && yearData.length > 0) {
                    try {
                        const yearTrendsCtx = yearTrendsCanvas.getContext('2d');
                        new Chart(yearTrendsCtx, {
                            type: 'line',
                            data: {
                                labels: yearLabels,
                                datasets: [{
                                    label: 'Jumlah Laporan',
                                    data: yearData,
                                    borderColor: 'rgba(139, 92, 246, 1)',
                                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
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
                                }
                            }
                        });
                    } catch (e) {
                        yearTrendsCanvas.parentElement.innerHTML = '<div class="text-center py-8 text-gray-500">Gagal memuat grafik</div>';
                    }
                } else {
                    // Show "No data" message
                    yearTrendsCanvas.parentElement.innerHTML = '<div class="text-center py-8 text-gray-500">Belum ada data laporan</div>';
                }
            }
        });
    </script>
    @endpush
</x-admin-layout>
