<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Pertanyaan') }}
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
                    <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <div>
                                <label for="question_text" class="block text-sm font-medium text-gray-700">Teks Pertanyaan</label>
                                <textarea name="question_text" id="question_text" rows="4" 
                                          class="@class(['mt-1 block w-full rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500', 'border-red-500' => $errors->has('question_text'), 'border-gray-300' => !$errors->has('question_text')])" 
                                          placeholder="Masukkan teks pertanyaan di sini..." required>{{ old('question_text', $question->question_text) }}</textarea>
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
                                    <option value="text" {{ old('question_type', $question->question_type) == 'text' ? 'selected' : '' }}>Text (Jawaban Bebas)</option>
                                    <option value="multiple_choice" {{ old('question_type', $question->question_type) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice (Pilihan Ganda)</option>
                                    <option value="rating" {{ old('question_type', $question->question_type) == 'rating' ? 'selected' : '' }}>Rating (Skala 1-5)</option>
                                </select>
                                @error('question_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <div class="flex gap-2">
                                    <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $question->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" id="btn-open-category-modal" class="mt-1 inline-flex items-center px-3 rounded-md border border-gray-300 text-sm hover:bg-gray-50">Tambah</button>
                                    <button type="button" id="btn-delete-category" class="mt-1 inline-flex items-center px-3 rounded-md border border-red-300 text-sm text-red-700 hover:bg-red-50">Hapus</button>
                                </div>
                                @error('category_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Modal Tambah Kategori -->
                            <div id="category-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                                <div class="bg-white rounded-md shadow-lg w-full max-w-md p-6">
                                    <h3 class="text-lg font-semibold mb-4">Tambah Kategori</h3>
                                    <div class="space-y-3">
                                        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                                        <input type="text" id="new-category-name" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Mis. Pelayanan" />
                                        <p id="new-category-error" class="text-sm text-red-600 hidden"></p>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-2">
                                        <button type="button" id="btn-cancel-category" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Batal</button>
                                        <button type="button" id="btn-save-category" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Simpan</button>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_required" id="is_required" value="1" 
                                           {{ old('is_required', $question->is_required) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_required" class="ml-2 block text-sm text-gray-900">
                                        Pertanyaan Wajib Diisi
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Centang jika pertanyaan ini wajib diisi oleh responden</p>
                            </div>

                            <div>
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" 
                                           {{ old('is_active', $question->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Pertanyaan Aktif (buka/tutup)
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">Nonaktifkan untuk menutup pertanyaan.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.questions.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Pertanyaan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
