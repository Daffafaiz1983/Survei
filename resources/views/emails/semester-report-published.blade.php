<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Semester Dipublikasi</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        
        .info-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .info-item {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
        }
        
        .info-value {
            color: #6b7280;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
        }
        
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        
        .button:hover {
            background: #2563eb;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            background: #dcfce7;
            color: #166534;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Laporan Semester Telah Dipublikasi</h1>
        <p>{{ $semesterReport->title }}</p>
    </div>
    
    <div class="content">
        <div class="info-card">
            <h3 style="margin-top: 0; color: #1f2937;">Informasi Laporan</h3>
            
            <div class="info-item">
                <span class="info-label">Semester:</span>
                <span class="info-value">{{ $semesterReport->formatted_semester }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge">Dipublikasi</span>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Dibuat Oleh:</span>
                <span class="info-value">{{ $semesterReport->creator->name }}</span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Tanggal Dipublikasi:</span>
                <span class="info-value">{{ now()->format('d F Y, H:i') }}</span>
            </div>
        </div>
        
        @if($semesterReport->survey_statistics)
            <div class="info-card">
                <h3 style="margin-top: 0; color: #1f2937;">Statistik Survei</h3>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($semesterReport->survey_statistics['total_answers'] ?? 0) }}</div>
                        <div class="stat-label">Total Jawaban</div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($semesterReport->survey_statistics['total_users'] ?? 0) }}</div>
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
            </div>
        @endif
        
        @if($semesterReport->summary)
            <div class="info-card">
                <h3 style="margin-top: 0; color: #1f2937;">Ringkasan</h3>
                <p>{{ $semesterReport->summary }}</p>
            </div>
        @endif
        
        <div style="text-align: center;">
            <a href="{{ $url }}" class="button">Lihat Laporan Lengkap</a>
        </div>
        
        <div class="footer">
            <p>Laporan ini dibuat secara otomatis oleh Sistem Survei FISIP UI</p>
            <p>Jika Anda tidak ingin menerima notifikasi ini, silakan hubungi administrator.</p>
        </div>
    </div>
</body>
</html>
