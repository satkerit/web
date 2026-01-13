<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Status Pengaduan</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f3f4f6; }
        .wrapper { padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0 0 10px 0; font-size: 24px; }
        .header p { margin: 0; opacity: 0.9; }
        .ticket-box { background: rgba(255,255,255,0.2); padding: 12px 20px; border-radius: 8px; display: inline-block; margin-top: 15px; font-family: monospace; font-size: 16px; letter-spacing: 1px; }
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .message { color: #4b5563; margin-bottom: 20px; }
        .status-change { background: #f8fafc; border-radius: 12px; padding: 25px; margin: 25px 0; text-align: center; }
        .status-arrow { display: flex; align-items: center; justify-content: center; gap: 20px; flex-wrap: wrap; }
        .status-badge { display: inline-block; padding: 10px 20px; border-radius: 25px; font-size: 14px; font-weight: bold; }
        .status-old { background: #f3f4f6; color: #6b7280; text-decoration: line-through; }
        .status-new { background: #dcfce7; color: #166534; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-in_review { background: #dbeafe; color: #1e40af; }
        .status-investigating { background: #fae8ff; color: #86198f; }
        .status-resolved { background: #dcfce7; color: #166534; }
        .status-closed { background: #f3f4f6; color: #4b5563; }
        .arrow { font-size: 24px; color: #9ca3af; }
        .admin-notes { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .admin-notes h3 { margin: 0 0 10px 0; color: #92400e; font-size: 14px; }
        .admin-notes p { margin: 0; color: #78350f; }
        .info-box { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-box h3 { margin: 0 0 15px 0; color: #0369a1; font-size: 14px; text-transform: uppercase; }
        .info-row { margin-bottom: 8px; }
        .info-label { color: #6b7280; font-size: 13px; }
        .info-value { color: #1f2937; font-size: 14px; font-weight: 500; }
        .footer { background: #f9fafb; padding: 20px 30px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { margin: 5px 0; font-size: 13px; color: #6b7280; }
        .company-name { font-weight: bold; color: #3b82f6; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>📋 Update Status Pengaduan</h1>
                <p>Ada perubahan status pada laporan Anda</p>
                <div class="ticket-box">{{ $complaint->ticket_number }}</div>
            </div>

            <div class="content">
                <div class="greeting">
                    Yth. {{ $complaint->is_anonymous ? 'Pelapor' : $complaint->name }},
                </div>

                <p class="message">
                    Kami ingin menginformasikan bahwa status pengaduan Anda telah diperbarui.
                </p>

                <div class="status-change">
                    <p style="margin: 0 0 15px 0; color: #6b7280; font-size: 13px;">PERUBAHAN STATUS</p>
                    <div class="status-arrow">
                        @php
                            $statusLabels = [
                                'pending' => 'Menunggu',
                                'in_review' => 'Dalam Review',
                                'investigating' => 'Investigasi',
                                'resolved' => 'Selesai',
                                'closed' => 'Ditutup',
                            ];
                        @endphp
                        <span class="status-badge status-old">{{ $statusLabels[$oldStatus] ?? $oldStatus }}</span>
                        <span class="arrow">→</span>
                        <span class="status-badge status-{{ $complaint->status }}">{{ $statusLabels[$complaint->status] ?? $complaint->status }}</span>
                    </div>
                </div>

                @if($adminNotes)
                <div class="admin-notes">
                    <h3>💬 Catatan dari Tim Kami</h3>
                    <p>{!! nl2br(e($adminNotes)) !!}</p>
                </div>
                @endif

                <div class="info-box">
                    <h3>📋 Detail Laporan</h3>
                    <div class="info-row">
                        <span class="info-label">Nomor Tiket:</span>
                        <span class="info-value">{{ $complaint->ticket_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Subjek:</span>
                        <span class="info-value">{{ $complaint->subject }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Laporan:</span>
                        <span class="info-value">{{ $complaint->created_at->format('d F Y') }}</span>
                    </div>
                    @if($complaint->resolved_at)
                    <div class="info-row">
                        <span class="info-label">Tanggal Selesai:</span>
                        <span class="info-value">{{ $complaint->resolved_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                    @endif
                </div>

                @if(in_array($complaint->status, ['resolved', 'closed']))
                <p class="message">
                    Laporan Anda telah selesai ditangani. Terima kasih atas kepercayaan Anda dalam melaporkan kepada kami. Kontribusi Anda sangat berarti dalam menjaga integritas perusahaan.
                </p>
                @else
                <p class="message">
                    Tim kami sedang menindaklanjuti laporan Anda. Anda akan menerima notifikasi email jika ada perkembangan lebih lanjut.
                </p>
                @endif
            </div>

            <div class="footer">
                <p class="company-name">{{ $companyInfo->name ?? 'BPR Syariah' }}</p>
                <p>{{ $companyInfo->address ?? '' }}</p>
                @if($companyInfo->phone)
                <p>Telp: {{ $companyInfo->phone }}</p>
                @endif
                <p style="margin-top: 15px; font-size: 11px; color: #9ca3af;">
                    Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
