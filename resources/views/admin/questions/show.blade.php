<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pertanyaan') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.questions.edit', $question->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit Pertanyaan
                </a>
                <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pertanyaan</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-1">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Teks Pertanyaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $question->question_text }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tipe Pertanyaan</dt>
                                    <dd class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $question->question_type === 'text' ? 'bg-blue-100 text-blue-800' : 
                                               ($question->question_type === 'multiple_choice' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst(str_replace('_', ' ', $question->question_type)) }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status Wajib</dt>
                                    <dd class="mt-1">
                                        @if($question->is_required)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Wajib Diisi
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Opsional
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $question->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $question->updated_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Pertanyaan</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <label class="block font-medium text-sm text-gray-700 mb-2">
                                    {{ $question->question_text }}
                                    @if($question->is_required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                
                                @if($question->question_type === 'text')
                                    <textarea rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" 
                                              placeholder="Masukkan jawaban Anda di sini..." disabled></textarea>
                                
                                @elseif($question->question_type === 'multiple_choice')
                                    <div class="mt-2 space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" disabled class="rounded border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Sangat Setuju</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" disabled class="rounded border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Setuju</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" disabled class="rounded border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Netral</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" disabled class="rounded border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Tidak Setuju</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" disabled class="rounded border-gray-300">
                                            <span class="ml-2 text-sm text-gray-700">Sangat Tidak Setuju</span>
                                        </label>
                                    </div>
                                
                                @elseif($question->question_type === 'rating')
                                    <div class="mt-2">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-sm text-gray-600">Sangat Buruk</span>
                                            <div class="flex space-x-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <label class="flex items-center">
                                                        <input type="radio" disabled class="rounded border-gray-300">
                                                        <span class="ml-1 text-sm">{{ $i }}</span>
                                                    </label>
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-600">Sangat Baik</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex justify-between">
                            <form action="{{ route('admin.questions.destroy', $question->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Hapus Pertanyaan
                                </button>
                            </form>
                            
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.questions.edit', $question->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Edit Pertanyaan
                                </a>
                                <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
