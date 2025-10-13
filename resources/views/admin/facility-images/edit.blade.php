<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Laporan Gambar Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.facility-images.update', $facilityImage) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Pilih Fasilitas -->
                        <div>
                            <x-input-label for="facility_id" :value="__('Fasilitas')" />
                            <select id="facility_id" name="facility_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach($facilities as $facility)
                                    <option value="{{ $facility->id }}" {{ old('facility_id', $facilityImage->facility_id) == $facility->id ? 'selected' : '' }}>
                                        {{ $facility->name }} 
                                        @if($facility->location)
                                            - {{ $facility->location }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('facility_id')" class="mt-2" />
                        </div>

                        <!-- Upload Gambar Baru (Opsional) -->
                        <div>
                            <x-input-label for="image" :value="__('Gambar Baru (Opsional)')" />
                            <input id="image" type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="mt-1 text-sm text-gray-500">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.</p>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                            
                            <!-- Tampilkan gambar saat ini -->
                            <div class="mt-2">
                                <label class="block text-sm font-medium text-gray-700">Gambar Saat Ini</label>
                                <img src="{{ $facilityImage->image_url }}" alt="Gambar saat ini" class="mt-1 h-32 w-auto rounded">
                            </div>
                        </div>

                        <!-- Tipe Masalah -->
                        <div>
                            <x-input-label for="issue_type" :value="__('Tipe Masalah')" />
                            <select id="issue_type" name="issue_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="damage" {{ old('issue_type', $facilityImage->issue_type) == 'damage' ? 'selected' : '' }}>Kerusakan</option>
                                <option value="maintenance" {{ old('issue_type', $facilityImage->issue_type) == 'maintenance' ? 'selected' : '' }}>Perawatan</option>
                                <option value="improvement" {{ old('issue_type', $facilityImage->issue_type) == 'improvement' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="other" {{ old('issue_type', $facilityImage->issue_type) == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            <x-input-error :messages="$errors->get('issue_type')" class="mt-2" />
                        </div>

                        <!-- Prioritas -->
                        <div>
                            <x-input-label for="priority" :value="__('Prioritas')" />
                            <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="low" {{ old('priority', $facilityImage->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority', $facilityImage->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority', $facilityImage->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority', $facilityImage->priority) == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="pending" {{ old('status', $facilityImage->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_review" {{ old('status', $facilityImage->status) == 'in_review' ? 'selected' : '' }}>Dalam Review</option>
                                <option value="resolved" {{ old('status', $facilityImage->status) == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                <option value="rejected" {{ old('status', $facilityImage->status) == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $facilityImage->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.facility-images.show', $facilityImage) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
