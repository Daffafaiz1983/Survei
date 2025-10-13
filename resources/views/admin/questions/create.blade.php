<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Pertanyaan Baru') }}
            </h2>
            <a href="{{ route('admin.questions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.questions.store') }}" method="POST">
                        @csrf
                        
                        <div class="space-y-6">
                            <div>
                                <label for="question_text" class="block text-sm font-medium text-gray-700">Teks Pertanyaan</label>
                                <textarea name="question_text" id="question_text" rows="4" 
                                          class="@class(['mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500', 'border-red-500' => $errors->has('question_text'), 'border-gray-300' => !$errors->has('question_text')])" 
                                          placeholder="Masukkan teks pertanyaan di sini..." required>{{ old('question_text') }}</textarea>
                                @error('question_text')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="question_type" class="block text-sm font-medium text-gray-700">Tipe Pertanyaan</label>
                                <select name="question_type" id="question_type" 
                                        class="@class(['mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500', 'border-red-500' => $errors->has('question_type'), 'border-gray-300' => !$errors->has('question_type')])" 
                                        required>
                                    <option value="">Pilih Tipe Pertanyaan</option>
                                    <option value="text" {{ old('question_type') == 'text' ? 'selected' : '' }}>Text (Jawaban Bebas)</option>
                                    <option value="multiple_choice" {{ old('question_type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice (Pilihan Ganda)</option>
                                    <option value="rating" {{ old('question_type') == 'rating' ? 'selected' : '' }}>Rating (Skala 1-5)</option>
                                </select>
                                @error('question_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Pelayanan, Fasilitas, Dosen">
                                @error('category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_required" id="is_required" value="1" 
                                           {{ old('is_required') ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_required" class="ml-2 block text-sm text-gray-900">
                                        Pertanyaan Wajib Diisi
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Centang jika pertanyaan ini wajib diisi oleh responden</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.questions.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
