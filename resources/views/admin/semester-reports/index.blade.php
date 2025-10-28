<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header dengan tombol tambah -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Laporan Semester</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.semester-reports.analytics') }}" 
                               class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-chart-bar mr-2"></i>Analisis
                            </a>
                            <a href="{{ route('admin.semester-reports.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-plus mr-2"></i>Tambah Laporan
                            </a>
                        </div>
                    </div>

                    <!-- Filter dan pencarian -->
                    <div class="mb-6 flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" 
                                   placeholder="Cari berdasarkan judul..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Semester</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Semua Tahun</option>
                            @for($year = date('Y') - 5; $year <= date('Y') + 2; $year++)
                                <option value="{{ $year }}">{{ $year }}/{{ $year + 1 }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Tabel laporan -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul Laporan
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Semester
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Dibuat Oleh
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal Dibuat
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reports as $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $report->title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $report->formatted_semester }}
                                            </div>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $report->creator->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $report->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.semester-reports.show', $report) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-blue-600 text-blue-600 rounded hover:bg-blue-600 hover:text-white transition-colors"
                                                   title="Lihat detail">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    <span>Detail</span>
                                                </a>
                                                <a href="{{ route('admin.semester-reports.edit', $report) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($report->status === 'draft')
                                                    <form action="{{ route('admin.semester-reports.publish', $report) }}" 
                                                          method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-green-600 hover:text-green-900"
                                                                onclick="return confirm('Yakin ingin memublikasi laporan ini?')">
                                                            <i class="fas fa-publish"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.semester-reports.unpublish', $report) }}" 
                                                          method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="text-yellow-600 hover:text-yellow-900"
                                                                onclick="return confirm('Yakin ingin mengubah status menjadi draft?')">
                                                            <i class="fas fa-unpublish"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.semester-reports.destroy', $report) }}" 
                                                      method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Belum ada laporan semester.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[placeholder="Cari berdasarkan judul..."]');
            const semesterFilter = document.querySelector('select');
            const yearFilter = document.querySelectorAll('select')[1];
            
            // Implement search and filter functionality
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase();
                const semesterValue = semesterFilter.value;
                const yearValue = yearFilter.value;
                
                const rows = document.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const titleCell = row.querySelector('td:first-child').textContent.toLowerCase();
                    const semesterCell = row.querySelector('td:nth-child(2)').textContent;
                    const yearCell = row.querySelector('td:nth-child(2)').textContent;
                    
                    let showRow = true;
                    
                    // Search filter
                    if (searchTerm && !titleCell.includes(searchTerm)) {
                        showRow = false;
                    }
                    
                    // Semester filter
                    if (semesterValue && !semesterCell.includes(semesterValue)) {
                        showRow = false;
                    }
                    
                    // Year filter
                    if (yearValue && !yearCell.includes(yearValue)) {
                        showRow = false;
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
            }
            
            searchInput.addEventListener('input', applyFilters);
            semesterFilter.addEventListener('change', applyFilters);
            yearFilter.addEventListener('change', applyFilters);
        });
    </script>
    @endpush
</x-admin-layout>
