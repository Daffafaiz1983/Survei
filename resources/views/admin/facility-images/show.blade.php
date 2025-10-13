<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan Gambar Fasilitas') }}
            </h2>
            <div>
                <a href="{{ route('admin.facility-images.edit', $facilityImage) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.facility-images.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Gambar -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Gambar Fasilitas</h3>
                        <img src="{{ $facilityImage->image_url }}" alt="Gambar fasilitas" class="w-full h-auto rounded-lg">
                    </div>
                </div>

                <!-- Detail Laporan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Laporan</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $facilityImage->facility->name }}</p>
                                @if($facilityImage->facility->location)
                                    <p class="text-sm text-gray-500">{{ $facilityImage->facility->location }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipe Masalah</label>
                                <p class="mt-1 text-sm text-gray-900">{{ ucfirst($facilityImage->issue_type) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Prioritas</label>
                                @php
                                    $priorityColors = [
                                        'low' => 'bg-green-100 text-green-800',
                                        'medium' => 'bg-yellow-100 text-yellow-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'urgent' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$facilityImage->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($facilityImage->priority) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_review' => 'bg-blue-100 text-blue-800',
                                        'resolved' => 'bg-green-100 text-green-800',
                                        'rejected' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$facilityImage->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $facilityImage->status)) }}
                                </span>
                            </div>

                            @if($facilityImage->description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $facilityImage->description }}</p>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Dilaporkan oleh</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $facilityImage->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $facilityImage->user->email }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Laporan</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $facilityImage->created_at->format('d F Y, H:i') }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terakhir Diupdate</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $facilityImage->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>

                        <!-- Update Status -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Update Status</h4>
                            <form method="POST" action="{{ route('admin.facility-images.update-status', $facilityImage) }}" class="flex items-center space-x-3">
                                @csrf
                                @method('PUT')
                                <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="pending" {{ $facilityImage->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="in_review" {{ $facilityImage->status == 'in_review' ? 'selected' : '' }}>Dalam Review</option>
                                    <option value="resolved" {{ $facilityImage->status == 'resolved' ? 'selected' : '' }}>Selesai</option>
                                    <option value="rejected" {{ $facilityImage->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Update
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
