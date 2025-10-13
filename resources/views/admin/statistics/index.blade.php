<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hasil Statistik Survei') }}
        </h2>
    </x-slot>

    <!-- Chart.js & Plugins (load terlebih dahulu sebelum inisialisasi chart) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        (function(){
            if (!window.__chartGlobalsInitialized) {
                if (window.Chart && window.ChartDataLabels) {
                    Chart.register(ChartDataLabels);
                }
                window.__chartGlobalsInitialized = true;
            }
        })();
    </script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl ring-1 ring-gray-100">
                <div class="p-6 text-gray-900">
                    <div class="mb-6 flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Hasil Statistik Survei</h3>
                        <div class="flex items-center gap-3">
                            <form method="GET" action="{{ route('admin.statistics.index') }}">
                                <div class="flex items-center gap-2">
                                    <label for="role" class="text-sm text-gray-600">Filter Peran</label>
                                    <select id="role" name="role" class="rounded-md border-gray-300 text-sm">
                                        <option value="" @selected(($role ?? null)===null)>Semua</option>
                                        <option value="mahasiswa" @selected(($role ?? null)==='mahasiswa')>Mahasiswa</option>
                                        <option value="dosen" @selected(($role ?? null)==='dosen')>Dosen</option>
                                        <option value="staff" @selected(($role ?? null)==='staff')>Staff</option>
                                    </select>
                                    <button class="px-3 py-2 rounded-md bg-gray-100 border border-gray-200 text-sm">Terapkan</button>
                                </div>
                            </form>
                            <a href="{{ route('admin.statistics.export.summary', ['role' => $role]) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">
                                Unduh Ringkasan (CSV)
                            </a>
                            <a href="{{ route('admin.statistics.export.raw', ['role' => $role]) }}" class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-700 text-white hover:bg-gray-800">
                                Unduh Data Mentah (CSV)
                            </a>
                        </div>
                    </div>

                    <div class="space-y-10">
                        @php
                            $grouped = $statistics->groupBy(fn($q) => $q->category ?? 'Lainnya');
                        @endphp
                        @foreach ($grouped as $category => $items)
                            <h3 class="text-md font-semibold text-gray-700 border-l-4 border-indigo-500 pl-3">Kategori: {{ $category }}</h3>
                            @foreach ($items as $stat)
                            @php
                                $labels = $stat->answers->pluck('answer_text');
                                $data = $stat->answers->pluck('total');
                                $type = $stat->question_type;
                            @endphp
                            <div class="p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                                <div class="mb-3">
                                    <h4 class="text-base font-semibold text-gray-800">{{ $stat->question_text }}</h4>
                                    <p class="text-xs text-gray-500">Total Responden: {{ $stat->answers_count }}</p>
                                </div>
                                <div class="relative">
                                    <canvas id="chart-{{ $stat->id }}" height="220"></canvas>
                                </div>
                                <script>
                                    (function(){
                                        const ctx = document.getElementById('chart-{{ $stat->id }}').getContext('2d');
                                        let labels = @json($labels);
                                        let data = @json($data);
                                        const colors = ['#6366F1','#22C55E','#F59E0B','#EF4444','#06B6D4','#84CC16','#A855F7','#FB7185','#0EA5E9','#10B981'];
                                        const qType = @json($type);

                                        if (qType === 'rating') {
                                            // sort labels numerik 1..5 untuk rating
                                            const pairs = labels.map((l, i) => [Number(l), data[i]]).sort((a,b)=>a[0]-b[0]);
                                            labels = pairs.map(p=>String(p[0]));
                                            data = pairs.map(p=>p[1]);
                                        }

                                        const chartType = qType === 'multiple_choice' ? 'doughnut' : (qType === 'rating' ? 'line' : 'bar');

                                        const datasetBase = {
                                            label: 'Jumlah Suara',
                                            data,
                                        };

                                        const dataset = (function(){
                                            if (chartType === 'doughnut') {
                                                return Object.assign({}, datasetBase, {
                                                    backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                                                    borderWidth: 1,
                                                });
                                            }
                                            if (chartType === 'line') {
                                                return Object.assign({}, datasetBase, {
                                                    borderColor: '#6366F1',
                                                    backgroundColor: '#6366F133',
                                                    fill: true,
                                                    tension: 0.3,
                                                });
                                            }
                                            return Object.assign({}, datasetBase, {
                                                backgroundColor: labels.map((_, i) => colors[i % colors.length]),
                                                borderRadius: 6,
                                            });
                                        })();

                                        const options = {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: { display: chartType === 'doughnut' },
                                                tooltip: { enabled: true },
                                                datalabels: {
                                                    display: true,
                                                    color: chartType === 'doughnut' ? '#111827' : '#374151',
                                                    anchor: chartType === 'doughnut' ? 'center' : 'end',
                                                    align: chartType === 'doughnut' ? 'center' : 'top',
                                                    offset: chartType === 'doughnut' ? 0 : 4,
                                                    font: { weight: '600' },
                                                    formatter: (value, ctx) => {
                                                        if (chartType === 'doughnut') {
                                                            const sum = ctx.chart.data.datasets[0].data.reduce((a,b)=>a+b,0) || 1;
                                                            const pct = Math.round((value/sum)*100);
                                                            return value + ' (' + pct + '%)';
                                                        }
                                                        return value;
                                                    }
                                                }
                                            },
                                        };

                                        if (chartType !== 'doughnut') {
                                            options.scales = {
                                                y: {
                                                    beginAtZero: true,
                                                    ticks: { precision:0 },
                                                    grid: { color: '#F1F5F9' }
                                                },
                                                x: {
                                                    ticks: { autoSkip: false, maxRotation: 0, minRotation: 0 },
                                                    grid: { display: false }
                                                }
                                            };
                                        }

                                        new Chart(ctx, {
                                            type: chartType,
                                            data: { labels, datasets: [dataset] },
                                            options
                                        });
                                    })();
                                </script>
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>