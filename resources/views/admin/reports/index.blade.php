<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Fasilitas') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-4">
                        @forelse($reports as $report)
                            <a href="{{ route('admin.reports.show', $report->id) }}" class="block p-4 rounded-lg border border-gray-200 hover:bg-gray-50">
                                <div class="flex items-start gap-4">
                                    @if($report->image_path)
                                        <img src="{{ asset('storage/'.$report->image_path) }}" class="h-16 w-24 object-cover rounded" />
                                    @endif
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold">{{ $report->title }}</h3>
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
                                        <p class="text-sm text-gray-600">Pelapor: {{ $report->user->name }} • Lokasi: {{ $report->location ?: '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $report->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </a>
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


