<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pengaduan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">{{ $companyInfo->name ?? 'BPR Syariah' }}</h1>
    </div>

    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px;">Yth. <strong>{{ $complaint->name }}</strong>,</p>

        <p>Terima kasih telah menyampaikan pengaduan Anda. Kami telah menerima pengaduan dengan detail sebagai berikut:</p>

        <div style="background: #ecfdf5; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center;">
            <p style="margin: 0; color: #065f46; font-size: 14px;">Nomor Tiket Anda:</p>
            <p style="margin: 10px 0 0 0; color: #047857; font-size: 28px; font-weight: bold; font-family: monospace;">{{ $complaint->ticket_number }}</p>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; width: 35%;"><strong>Kategori:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->category_label }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Subjek:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->subject }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Tanggal:</strong></td>
                    <td style="padding: 8px 0;">{{ $complaint->created_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <p>Pengaduan Anda akan kami proses dan tindaklanjuti sesuai dengan ketentuan yang berlaku. Waktu penyelesaian maksimal adalah <strong>20 hari kerja</strong> sejak pengaduan diterima.</p>

        <p>Simpan nomor tiket di atas untuk keperluan pelacakan status pengaduan Anda.</p>

        <p>Jika ada pertanyaan lebih lanjut, silakan hubungi kami melalui:</p>
        <ul style="color: #4b5563;">
            @if($companyInfo->phone)
            <li>Telepon: {{ $companyInfo->phone }}</li>
            @endif
            @if($companyInfo->email_complaint ?? $companyInfo->email)
            <li>Email: {{ $companyInfo->email_complaint ?? $companyInfo->email }}</li>
            @endif
        </ul>

        <p style="margin-top: 30px;">Hormat kami,<br><strong>{{ $companyInfo->name ?? 'BPR Syariah' }}</strong></p>
    </div>

    <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
        Email ini dikirim secara otomatis. Mohon tidak membalas email ini.
    </p>
</body>
</html>
