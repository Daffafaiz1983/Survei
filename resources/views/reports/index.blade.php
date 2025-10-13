<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Laporan Fasilitas Saya') }}
            </h2>
            <a href="{{ route('reports.create') }}" class="inline-flex px-3 py-2 rounded-lg bg-indigo-600 text-white">Buat Laporan</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 text-green-800 border border-green-200 px-3 py-2 rounded">{{ session('success') }}</div>
                    @endif

                    <div class="space-y-4">
                        @forelse($reports as $report)
                            <div class="p-4 rounded-lg border border-gray-200 flex items-start gap-4">
                                @if($report->image_path)
                                    <img src="{{ asset('storage/'.$report->image_path) }}" alt="lampiran" class="h-20 w-28 object-cover rounded">
                                @else
                                    <div class="h-20 w-28 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-sm">No Image</div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <h3 class="font-semibold text-gray-800">{{ $report->title }}</h3>
                                        @php
                                            $statusStyles = [
                                                'submitted' => 'text-amber-700 bg-amber-50 border-amber-200',
                                                'in_review' => 'text-blue-700 bg-blue-50 border-blue-200',
                                                'resolved' => 'text-green-700 bg-green-50 border-green-200',
                                            ];
                                            $badgeClass = $statusStyles[$report->status] ?? 'text-gray-700 bg-gray-50 border-gray-200';
                                        @endphp
                                        <span class="text-xs px-2 py-1 rounded-full border {{ $badgeClass }}">{{ ucfirst(str_replace('_',' ',$report->status)) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Lokasi: {{ $report->location ?: '-' }}</p>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $report->description }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada laporan.</p>
                        @endforelse
                    </div>

                    <div class="mt-4">{{ $reports->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


