<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $semesterReport->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin: 0;
        }
        
        .header h2 {
            color: #6b7280;
            font-size: 18px;
            margin: 10px 0 0 0;
            font-weight: normal;
        }
        
        .info-section {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .info-item {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #6b7280;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-published {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-draft {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #2563eb;
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 14px;
            color: #6b7280;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }
        
        .progress-bar {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #2563eb;
            border-radius: 4px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>{{ $semesterReport->title }}</h1>
        <h2>{{ $semesterReport->formatted_semester }}</h2>
    </div>

    <!-- Informasi Laporan -->
    <div class="info-section">
        <div class="info-grid">
            <div>
                <div class="info-item">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <span class="status-badge {{ $semesterReport->status === 'published' ? 'status-published' : 'status-draft' }}">
                            {{ $semesterReport->status === 'published' ? 'Dipublikasi' : 'Draft' }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Dibuat Oleh:</div>
                    <div class="info-value">{{ $semesterReport->creator->name }}</div>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <div class="info-label">Tanggal Dibuat:</div>
                    <div class="info-value">{{ $semesterReport->created_at->format('d F Y, H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Terakhir Diperbarui:</div>
                    <div class="info-value">{{ $semesterReport->updated_at->format('d F Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    @if($semesterReport->summary)
        <div class="section">
            <div class="section-title">Ringkasan</div>
            <p>{{ $semesterReport->summary }}</p>
        </div>
    @endif

    <!-- Statistik Survei -->
    @if($semesterReport->survey_statistics)
        <div class="section">
            <div class="section-title">Statistik Survei</div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $semesterReport->survey_statistics['total_answers'] ?? 0 }}</div>
                    <div class="stat-label">Total Jawaban</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">{{ $semesterReport->survey_statistics['total_users'] ?? 0 }}</div>
                    <div class="stat-label">Total Responden</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        {{ \Carbon\Carbon::parse($semesterReport->survey_statistics['period']['start'])->format('d M Y') }}
                    </div>
                    <div class="stat-label">Periode Mulai</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">
                        {{ \Carbon\Carbon::parse($semesterReport->survey_statistics['period']['end'])->format('d M Y') }}
                    </div>
                    <div class="stat-label">Periode Selesai</div>
                </div>
            </div>

            <!-- Analisis Per Kategori -->
            @if($semesterReport->category_analysis && count($semesterReport->category_analysis) > 0)
                <div class="section-title">Analisis Per Kategori</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Total Pertanyaan</th>
                            <th>Total Jawaban</th>
                            <th>Tingkat Penyelesaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semesterReport->category_analysis as $category)
                            <tr>
                                <td>{{ $category['category_name'] }}</td>
                                <td>{{ $category['total_questions'] }}</td>
                                <td>{{ $category['total_answers'] }}</td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $category['completion_rate'] ?? 0 }}%"></div>
                                    </div>
                                    {{ $category['completion_rate'] ?? 0 }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            <!-- Analisis Mendalam -->
            @if(isset($semesterReport->survey_statistics['daily_trends']) || isset($semesterReport->survey_statistics['hourly_activity']))
                <div class="section-title">Analisis Mendalam</div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $semesterReport->survey_statistics['average_answers_per_user'] ?? 0 }}</div>
                        <div class="stat-label">Rata-rata Jawaban per User</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $semesterReport->survey_statistics['peak_hour'] ?? 'N/A' }}:00</div>
                        <div class="stat-label">Jam Puncak Aktivitas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">
                            {{ $semesterReport->survey_statistics['peak_day'] ? \Carbon\Carbon::parse($semesterReport->survey_statistics['peak_day'])->format('d M Y') : 'N/A' }}
                        </div>
                        <div class="stat-label">Hari Puncak Aktivitas</div>
                    </div>
                </div>
                
                <!-- Trend Harian -->
                @if(isset($semesterReport->survey_statistics['daily_trends']))
                    <div class="section-title">Trend Harian Survei</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Jawaban</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semesterReport->survey_statistics['daily_trends'] as $trend)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($trend['date'])->format('d F Y') }}</td>
                                    <td>{{ $trend['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
                
                <!-- Aktivitas per Jam -->
                @if(isset($semesterReport->survey_statistics['hourly_activity']))
                    <div class="section-title">Aktivitas per Jam</div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Jumlah Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($semesterReport->survey_statistics['hourly_activity'] as $activity)
                                <tr>
                                    <td>{{ $activity['hour'] }}:00</td>
                                    <td>{{ $activity['count'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endif
        </div>
    @endif

    <!-- Statistik Fasilitas -->
    @if($semesterReport->facility_statistics)
        <div class="section">
            <div class="section-title">Statistik Laporan Fasilitas</div>
            
            @if(isset($semesterReport->facility_statistics['by_status']))
                <div class="stats-grid">
                    @foreach($semesterReport->facility_statistics['by_status'] as $status => $count)
                        <div class="stat-card">
                            <div class="stat-number">{{ $count }}</div>
                            <div class="stat-label">{{ ucfirst($status) }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $semesterReport->facility_statistics['total_reports'] ?? 0 }}</div>
                        <div class="stat-label">Total Laporan</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $semesterReport->facility_statistics['resolved_reports'] ?? 0 }}</div>
                        <div class="stat-label">Laporan Selesai</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $semesterReport->facility_statistics['resolution_rate'] ?? 0 }}%</div>
                        <div class="stat-label">Tingkat Penyelesaian</div>
                    </div>
                </div>
            @else
                <!-- Fallback untuk format lama -->
                <div class="stats-grid">
                    @foreach($semesterReport->facility_statistics as $status => $count)
                        <div class="stat-card">
                            <div class="stat-number">{{ $count }}</div>
                            <div class="stat-label">{{ ucfirst($status) }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- Rekomendasi -->
    @if($semesterReport->recommendations)
        <div class="section">
            <div class="section-title">Rekomendasi</div>
            <p>{{ $semesterReport->recommendations }}</p>
        </div>
    @endif

    <!-- Kesimpulan -->
    @if($semesterReport->conclusions)
        <div class="section">
            <div class="section-title">Kesimpulan</div>
            <p>{{ $semesterReport->conclusions }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis pada {{ now()->format('d F Y, H:i') }}</p>
        <p>Sistem Survei FISIP UI</p>
    </div>

    <script>
        // Auto print when loaded
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>