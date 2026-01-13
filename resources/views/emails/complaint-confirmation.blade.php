<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Konfirmasi Pengaduan</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background: #f3f4f6; }
        .wrapper { padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #059669, #10b981); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0 0 10px 0; font-size: 24px; }
        .header p { margin: 0; opacity: 0.9; }
        .ticket-box { background: rgba(255,255,255,0.2); padding: 15px 25px; border-radius: 8px; display: inline-block; margin-top: 15px; }
        .ticket-label { font-size: 12px; text-transform: uppercase; opacity: 0.8; }
        .ticket-number { font-size: 20px; font-weight: bold; font-family: monospace; letter-spacing: 1px; }
        .content { padding: 30px; }
        .greeting { font-size: 18px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .message { color: #4b5563; margin-bottom: 20px; }
        .info-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-box h3 { margin: 0 0 15px 0; color: #166534; font-size: 14px; text-transform: uppercase; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { width: 140px; color: #6b7280; font-size: 14px; }
        .info-value { flex: 1; color: #1f2937; font-size: 14px; font-weight: 500; }
        .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 13px; font-weight: bold; background: #fef3c7; color: #92400e; }
        .next-steps { background: #eff6ff; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .next-steps h3 { margin: 0 0 15px 0; color: #1e40af; font-size: 14px; }
        .next-steps ul { margin: 0; padding-left: 20px; color: #3b82f6; }
        .next-steps li { margin-bottom: 8px; color: #4b5563; }
        .footer { background: #f9fafb; padding: 20px 30px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { margin: 5px 0; font-size: 13px; color: #6b7280; }
        .company-name { font-weight: bold; color: #059669; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1>✓ Pengaduan Anda Telah Diterima</h1>
                <p>Terima kasih telah melaporkan kepada kami</p>
                <div class="ticket-box">
                    <div class="ticket-label">Nomor Tiket</div>
                    <div class="ticket-number">{{ $complaint->ticket_number }}</div>
                </div>
            </div>

            <div class="content">
                <div class="greeting">
                    Yth. {{ $complaint->is_anonymous ? 'Pelapor' : $complaint->name }},
                </div>

                <p class="message">
                    Kami telah menerima laporan pengaduan Anda. Tim kami akan segera meninjau dan menindaklanjuti laporan ini sesuai dengan prosedur yang berlaku.
                </p>

                <div class="info-box">
                    <h3>📋 Ringkasan Laporan</h3>
                    <div class="info-row">
                        <span class="info-label">Nomor Tiket:</span>
                        <span class="info-value">{{ $complaint->ticket_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Laporan:</span>
                        <span class="info-value">{{ $complaint->created_at->format('d F Y, H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jenis Laporan:</span>
                        <span class="info-value">{{ $complaint->type_label }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Subjek:</span>
                        <span class="info-value">{{ $complaint->subject }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status:</span>
                        <span class="info-value"><span class="status-badge">Menunggu Review</span></span>
                    </div>
                </div>

                <div class="next-steps">
                    <h3>📌 Langkah Selanjutnya</h3>
                    <ul>
                        <li>Simpan nomor tiket <strong>{{ $complaint->ticket_number }}</strong> untuk referensi</li>
                        <li>Tim kami akan meninjau laporan Anda dalam 1-3 hari kerja</li>
                        <li>Anda akan menerima notifikasi email saat ada update status</li>
                        <li>Jika diperlukan, tim kami mungkin akan menghubungi Anda untuk informasi tambahan</li>
                    </ul>
                </div>

                <p class="message">
                    Kami menjamin kerahasiaan identitas dan informasi yang Anda sampaikan. Laporan Anda sangat berarti bagi kami dalam menjaga integritas dan tata kelola perusahaan yang baik.
                </p>
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
