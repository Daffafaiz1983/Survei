<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.semester-reports.update', $semesterReport) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Judul Laporan -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Laporan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $semesterReport->title) }}"
                                   class="w-full px-3 py-2 border {{ $errors->has('title') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Contoh: Laporan Survei Semester Ganjil 2024/2025"
                                   required>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Semester dan Tahun Akademik -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="semester" class="block text-sm font-medium text-gray-700 mb-2">
                                    Semester <span class="text-red-500">*</span>
                                </label>
                                <select id="semester" 
                                        name="semester" 
                                        class="w-full px-3 py-2 border {{ $errors->has('semester') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <option value="">Pilih Semester</option>
                                    @foreach($semesterOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('semester', $semesterReport->semester) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('semester')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tahun Akademik <span class="text-red-500">*</span>
                                </label>
                                <select id="academic_year" 
                                        name="academic_year" 
                                        class="w-full px-3 py-2 border {{ $errors->has('academic_year') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <option value="">Pilih Tahun Akademik</option>
                                    @foreach($academicYearOptions as $year => $label)
                                        <option value="{{ $year }}" {{ old('academic_year', $semesterReport->academic_year) == $year ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academic_year')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Ringkasan -->
                        <div class="mb-6">
                            <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">
                                Ringkasan Laporan
                            </label>
                            <textarea id="summary" 
                                      name="summary" 
                                      rows="4"
                                      class="w-full px-3 py-2 border {{ $errors->has('summary') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Masukkan ringkasan singkat tentang laporan semester ini...">{{ old('summary', $semesterReport->summary) }}</textarea>
                            @error('summary')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rekomendasi -->
                        <div class="mb-6">
                            <label for="recommendations" class="block text-sm font-medium text-gray-700 mb-2">
                                Rekomendasi
                            </label>
                            <textarea id="recommendations" 
                                      name="recommendations" 
                                      rows="4"
                                      class="w-full px-3 py-2 border {{ $errors->has('recommendations') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Masukkan rekomendasi berdasarkan hasil survei...">{{ old('recommendations', $semesterReport->recommendations) }}</textarea>
                            @error('recommendations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kesimpulan -->
                        <div class="mb-6">
                            <label for="conclusions" class="block text-sm font-medium text-gray-700 mb-2">
                                Kesimpulan
                            </label>
                            <textarea id="conclusions" 
                                      name="conclusions" 
                                      rows="4"
                                      class="w-full px-3 py-2 border {{ $errors->has('conclusions') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Masukkan kesimpulan dari analisis survei...">{{ old('conclusions', $semesterReport->conclusions) }}</textarea>
                            @error('conclusions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi Status -->
                        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-gray-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-gray-800">
                                        Status Laporan
                                    </h3>
                                    <div class="mt-2 text-sm text-gray-700">
                                        <p>Status saat ini: 
                                            @if($semesterReport->status === 'published')
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Dipublikasi
                                                </span>
                                            @else
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Draft
                                                </span>
                                            @endif
                                        </p>
                                        <p class="mt-1">Jika Anda mengubah semester atau tahun akademik, statistik akan diperbarui otomatis.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.semester-reports.show', $semesterReport) }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>Perbarui Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-generate title based on semester and year selection
            const semesterSelect = document.getElementById('semester');
            const yearSelect = document.getElementById('academic_year');
            const titleInput = document.getElementById('title');

            function updateTitle() {
                const semester = semesterSelect.value;
                const year = yearSelect.value;
                
                if (semester && year) {
                    const yearLabel = year + '/' + (parseInt(year) + 1);
                    titleInput.value = `Laporan Survei Semester ${semester} ${yearLabel}`;
                }
            }

            semesterSelect.addEventListener('change', updateTitle);
            yearSelect.addEventListener('change', updateTitle);
        });
    </script>
    @endpush
</x-admin-layout>
