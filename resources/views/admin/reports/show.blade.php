<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Laporan Fasilitas') }}
            </h2>
            <a href="{{ route('admin.reports.index') }}" class="px-3 py-2 rounded-md border border-gray-300 bg-white">Kembali</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-start gap-6">
                        @if($report->image_path)
                            <img src="{{ asset('storage/'.$report->image_path) }}" class="w-80 max-w-full rounded" />
                        @endif
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold">{{ $report->title }}</h3>
                            <p class="text-sm text-gray-600">Pelapor: {{ $report->user->name }} ({{ $report->user->email }})</p>
                            <p class="text-sm text-gray-600">Lokasi: {{ $report->location ?: '-' }}</p>
                            <p class="text-sm text-gray-600">Dibuat: {{ $report->created_at->format('d M Y H:i') }}</p>
                            <div class="mt-4">
                                <p class="text-gray-800 whitespace-pre-line">{{ $report->description }}</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.reports.update', $report->id) }}" method="POST" class="flex items-center gap-3">
                        @csrf
                        @method('PUT')
                        <label class="text-sm text-gray-700">Ubah Status</label>
                        <select name="status" class="rounded-md border-gray-300">
                            <option value="submitted" @selected($report->status==='submitted')>Submitted</option>
                            <option value="in_review" @selected($report->status==='in_review')>In Review</option>
                            <option value="resolved" @selected($report->status==='resolved')>Resolved</option>
                        </select>
                        <button class="px-3 py-2 rounded-md bg-indigo-600 text-white">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


