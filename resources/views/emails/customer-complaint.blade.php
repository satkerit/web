<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Nasabah Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Pengaduan Nasabah Baru</h1>
    </div>

    <div style="background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
            <h2 style="color: #0f766e; margin-top: 0; font-size: 18px;">Nomor Tiket: {{ $complaint->ticket_number }}</h2>

            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb; width: 35%;"><strong>Nama:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Email:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Telepon:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->phone }}</td>
                </tr>
                @if($complaint->account_number)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>No. Rekening:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->account_number }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Kategori:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->category_label }}</td>
                </tr>
                @if($complaint->branch_office)
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Kantor:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->branch_office }}</td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;"><strong>Subjek:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #e5e7eb;">{{ $complaint->subject }}</td>
                </tr>
            </table>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px;">
            <h3 style="color: #374151; margin-top: 0;">Deskripsi Pengaduan:</h3>
            <p style="white-space: pre-wrap; color: #4b5563;">{{ $complaint->description }}</p>
        </div>

        <p style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 20px;">
            Silakan login ke panel admin untuk menindaklanjuti pengaduan ini.
        </p>
    </div>
</body>
</html>
