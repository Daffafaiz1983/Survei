<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Laporan Semester') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.semester-reports.store') }}" method="POST">
                        @csrf
                        
                        <!-- Judul Laporan -->
                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Laporan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('title'), 'border-gray-300' => ! $errors->has('title')])"
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
                                        class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('semester'), 'border-gray-300' => ! $errors->has('semester')])"
                                        required>
                                    <option value="">Pilih Semester</option>
                                    @foreach($semesterOptions as $value => $label)
                                        <option value="{{ $value }}" {{ old('semester') == $value ? 'selected' : '' }}>
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
                                        class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('academic_year'), 'border-gray-300' => ! $errors->has('academic_year')])"
                                        required>
                                    <option value="">Pilih Tahun Akademik</option>
                                    @foreach($academicYearOptions as $year => $label)
                                        <option value="{{ $year }}" {{ old('academic_year') == $year ? 'selected' : '' }}>
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
                                <span class="text-gray-500 text-xs">(Minimal 20 karakter)</span>
                            </label>
                            <textarea id="summary" 
                                      name="summary" 
                                      rows="4"
                                      minlength="20"
                                      maxlength="2000"
                                      class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('summary'), 'border-gray-300' => ! $errors->has('summary')])"
                                      placeholder="Masukkan ringkasan singkat tentang laporan semester ini...">{{ old('summary') }}</textarea>
                            <div class="mt-1 flex justify-between text-xs text-gray-500">
                                <span id="summary-error" class="text-red-600 hidden">Minimal 20 karakter</span>
                                <span id="summary-count">0/2000 karakter</span>
                            </div>
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
                                      class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('recommendations'), 'border-gray-300' => ! $errors->has('recommendations')])"
                                      placeholder="Masukkan rekomendasi berdasarkan hasil survei...">{{ old('recommendations') }}</textarea>
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
                                      class="@class(['w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500', 'border-red-500' => $errors->has('conclusions'), 'border-gray-300' => ! $errors->has('conclusions')])"
                                      placeholder="Masukkan kesimpulan dari analisis survei...">{{ old('conclusions') }}</textarea>
                            @error('conclusions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Informasi -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Informasi Penting
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Statistik survei akan dihitung otomatis berdasarkan periode semester yang dipilih</li>
                                            <li>Laporan akan dibuat dalam status "Draft" dan dapat dipublikasi setelah selesai</li>
                                            <li>Pastikan tidak ada laporan untuk semester dan tahun yang sama</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.semester-reports.index') }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>Simpan Laporan
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

            // Character count and validation for textareas
            const textareas = ['summary', 'recommendations', 'conclusions'];
            
            textareas.forEach(function(fieldName) {
                const textarea = document.getElementById(fieldName);
                const errorElement = document.getElementById(fieldName + '-error');
                const countElement = document.getElementById(fieldName + '-count');
                
                if (textarea && countElement) {
                    function updateCount() {
                        const length = textarea.value.length;
                        countElement.textContent = length + '/2000 karakter';
                        
                        if (length < 20 && length > 0) {
                            errorElement.classList.remove('hidden');
                            textarea.classList.add('border-red-500');
                        } else {
                            errorElement.classList.add('hidden');
                            textarea.classList.remove('border-red-500');
                        }
                    }
                    
                    textarea.addEventListener('input', updateCount);
                    updateCount(); // Initial count
                }
            });

            // Form validation before submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                let isValid = true;
                
                // Check title length
                if (titleInput.value.length < 10) {
                    isValid = false;
                    alert('Judul laporan minimal 10 karakter');
                }
                
                // Check textarea lengths
                textareas.forEach(function(fieldName) {
                    const textarea = document.getElementById(fieldName);
                    if (textarea && textarea.value.length > 0 && textarea.value.length < 20) {
                        isValid = false;
                        alert(fieldName.charAt(0).toUpperCase() + fieldName.slice(1) + ' minimal 20 karakter');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>
    @endpush
</x-admin-layout>
