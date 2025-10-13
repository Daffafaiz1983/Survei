<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Fasilitas: ') . $facility->name }}
            </h2>
            <div>
                <a href="{{ route('admin.facilities.edit', $facility) }}" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mr-2">
                    Edit
                </a>
                <a href="{{ route('admin.facilities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Informasi Fasilitas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Fasilitas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Fasilitas</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $facility->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $facility->location ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-gray-100 text-gray-800',
                                    'maintenance' => 'bg-yellow-100 text-yellow-800'
                                ];
                            @endphp
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$facility->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($facility->status) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Laporan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $facility->images->count() }} laporan</p>
                        </div>
                    </div>
                    @if($facility->description)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $facility->description }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Daftar Gambar/Laporan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Laporan Gambar Fasilitas</h3>
                    
                    @if($facility->images->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($facility->images as $image)
                                <div class="border rounded-lg p-4">
                                    <div class="mb-3">
                                        <img src="{{ $image->image_url }}" alt="Gambar fasilitas" class="w-full h-48 object-cover rounded">
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Tipe Masalah</label>
                                            <span class="text-sm text-gray-900">{{ ucfirst($image->issue_type) }}</span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Prioritas</label>
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-green-100 text-green-800',
                                                    'medium' => 'bg-yellow-100 text-yellow-800',
                                                    'high' => 'bg-orange-100 text-orange-800',
                                                    'urgent' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$image->priority] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($image->priority) }}
                                            </span>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Status</label>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                                    'in_review' => 'bg-blue-100 text-blue-800',
                                                    'resolved' => 'bg-green-100 text-green-800',
                                                    'rejected' => 'bg-red-100 text-red-800'
                                                ];
                                            @endphp
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$image->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $image->status)) }}
                                            </span>
                                        </div>
                                        
                                        @if($image->description)
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Deskripsi</label>
                                                <p class="text-sm text-gray-900">{{ Str::limit($image->description, 100) }}</p>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Dilaporkan oleh</label>
                                            <p class="text-sm text-gray-900">{{ $image->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $image->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        
                                        <div class="pt-2">
                                            <a href="{{ route('admin.facility-images.show', $image) }}" class="text-blue-600 hover:text-blue-900 text-sm">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada laporan gambar untuk fasilitas ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
