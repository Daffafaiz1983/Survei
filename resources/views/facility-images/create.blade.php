<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Gambar Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Laporkan Kondisi Fasilitas
                        </h3>
                        <p class="text-sm text-gray-600">
                            Upload foto fasilitas yang memerlukan perhatian atau saran perbaikan. 
                            Foto ini akan membantu tim administrasi untuk mengidentifikasi masalah dan memberikan solusi yang tepat.
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('facility-images.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Pilih Fasilitas -->
                        <div>
                            <x-input-label for="facility_id" :value="__('Pilih Fasilitas')" />
                            <select id="facility_id" name="facility_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Fasilitas --</option>
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }} 
                                        @if($facility->location)
                                            - {{ $facility->location }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('facility_id')" class="mt-2" />
                        </div>

                        <!-- Upload Gambar -->
                        <div>
                            <x-input-label for="image" :value="__('Upload Gambar')" />
                            <input id="image" type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <!-- Tipe Masalah -->
                        <div>
                            <x-input-label for="issue_type" :value="__('Tipe Masalah')" />
                            <select id="issue_type" name="issue_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">-- Pilih Tipe Masalah --</option>
                                <option value="damage" {{ old('issue_type') == 'damage' ? 'selected' : '' }}>Kerusakan</option>
                                <option value="maintenance" {{ old('issue_type') == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                <option value="improvement" {{ old('issue_type') == 'improvement' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="other" {{ old('issue_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('issue_type')" class="mt-2" />
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <x-input-label for="priority" :value="__('Prioritas')" />
                            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi (Opsional)')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Jelaskan kondisi fasilitas atau saran perbaikan yang diperlukan...">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Upload Gambar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
