<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Survei') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur overflow-hidden shadow-sm sm:rounded-xl ring-1 ring-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 p-5 rounded-xl bg-gradient-to-r from-indigo-50 to-white border border-indigo-100">
                        <h3 class="text-lg font-semibold text-gray-800">Selamat datang di Dashboard Pengisian Survei Akademika FISIP UI</h3>
                        <p class="mt-1 text-sm text-gray-600">Silakan isi pertanyaan berikut sesuai peran Anda (Mahasiswa, Dosen, atau Tenaga Kependidikan).</p>
                        
                        <!-- Quick Action untuk Upload Gambar Fasilitas -->
                        <div class="mt-4 p-4 bg-white rounded-lg border border-indigo-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Laporkan Kondisi Fasilitas</h4>
                                    <p class="text-sm text-gray-600">Upload foto fasilitas yang memerlukan perhatian atau perbaikan</p>
                                </div>
                                <a href="{{ route('facility-images.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Upload Gambar
                                </a>
                            </div>
                        </div>
                    </div>

                    @if (session('success'))
                        <div id="survey-success-alert" class="mb-6 flex items-start gap-3 p-4 rounded-lg border border-green-200 bg-green-50 text-green-800">
                            <svg class="h-5 w-5 mt-0.5 text-green-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.172 7.707 8.879a1 1 0 10-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div class="flex-1 text-sm">
                                <p class="font-medium">{{ session('success') }}</p>
                                <p class="text-green-700/80">Terima kasih, jawaban Anda telah direkam. Anda dapat meninjau atau mengubah jawaban kapan saja.</p>
                            </div>
                            <button type="button" onclick="document.getElementById('survey-success-alert').remove()" class="shrink-0 text-green-700/70 hover:text-green-900">&times;</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 font-medium text-sm text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (!isset($hasSubmitted) || !$hasSubmitted)
                    <form action="{{ route('survei.store') }}" method="POST" class="space-y-5">
                        @csrf
                        @forelse ($questions as $question)
                            <div class="p-5 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow ring-1 ring-transparent hover:ring-indigo-100 transition">
                                <label for="question_{{ $question->id }}" class="flex items-start gap-2 mb-3">
                                    <span class="inline-flex items-center justify-center h-7 w-7 rounded-full bg-indigo-50 text-indigo-600 text-sm font-semibold">{{ $loop->iteration }}</span>
                                    <span class="text-gray-800 font-medium leading-6">{{ $question->question_text }}</span>
                                    @if($question->is_required)
                                        <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">Wajib</span>
                                    @endif
                                </label>
                                
                                @if($question->question_type === 'text')
                                    <textarea 
                                        name="answers[{{ $question->id }}]" 
                                        id="question_{{ $question->id }}" 
                                        rows="3" 
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        @if($question->is_required) required @endif
                                        placeholder="Masukkan jawaban Anda di sini..."></textarea>
                                
                                @elseif($question->question_type === 'multiple_choice')
                                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-3 gap-2">
                                        @php($choices=['Sangat Setuju','Setuju','Netral','Tidak Setuju','Sangat Tidak Setuju'])
                                        @foreach($choices as $choice)
                                        <label class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 hover:border-indigo-300 cursor-pointer">
                                            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <span class="text-sm text-gray-700">{{ $choice }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                
                                @elseif($question->question_type === 'rating')
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                                            <span>Sangat Buruk</span>
                                            <span>Sangat Baik</span>
                                        </div>
                                        <div class="grid grid-cols-5 gap-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <label class="group inline-flex items-center justify-center px-3 py-2 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition">
                                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $i }}" class="peer hidden">
                                                    <span class="text-sm text-gray-700 peer-checked:font-semibold peer-checked:text-indigo-700">{{ $i }}</span>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                @endif
                                
                                @error('answers.' . $question->id)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="text-gray-500 text-lg mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-500 text-lg">Belum ada pertanyaan survei yang tersedia saat ini.</p>
                                <p class="text-gray-400 text-sm mt-2">Silakan hubungi administrator untuk informasi lebih lanjut.</p>
                            </div>
                        @endforelse

                        @if($questions->count() > 0)
                        <div class="sticky bottom-0 left-0 right-0 mt-8 bg-white/80 backdrop-blur border-t border-gray-200">
                            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="text-sm text-gray-600">
                                    <span class="text-red-500">*</span> Menandakan pertanyaan wajib diisi
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" onclick="clearForm()" class="px-4 py-2 bg-white text-gray-700 rounded-lg border border-gray-300 hover:bg-gray-50">
                                        {{ __('Reset Form') }}
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 focus:outline-none">
                                        {{ __('Simpan Jawaban') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                    @else
                        <div class="mt-4 p-6 rounded-xl border border-amber-200 bg-amber-50 text-amber-800">
                            <div class="flex items-start gap-3">
                                <svg class="h-5 w-5 mt-0.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V6.5zM10 14a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="font-medium">Terima kasih, Anda sudah mengisi survei yang tersedia.</p>
                                    <p class="text-amber-700/90 text-sm">Silakan menunggu survei berikutnya. Jika ada survei baru, form akan muncul kembali di halaman ini.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function clearForm() {
            if (confirm('Apakah Anda yakin ingin mengosongkan semua jawaban?')) {
                document.querySelector('form').reset();
            }
        }
    </script>
</x-app-layout>