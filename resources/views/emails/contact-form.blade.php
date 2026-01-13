<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesan dari Form Hubungi Kami</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981, #14b8a6); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #374151; font-size: 12px; text-transform: uppercase; }
        .value { margin-top: 5px; padding: 10px; background: white; border-radius: 4px; border: 1px solid #e5e7eb; }
        .footer { padding: 15px; text-align: center; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">Pesan Baru dari Form Hubungi Kami</h2>
        </div>
        <div class="content">
            <div class="field">
                <div class="label">Nama</div>
                <div class="value">{{ $data['name'] }}</div>
            </div>
            <div class="field">
                <div class="label">Email</div>
                <div class="value">{{ $data['email'] }}</div>
            </div>
            <div class="field">
                <div class="label">Telepon</div>
                <div class="value">{{ $data['phone'] }}</div>
            </div>
            <div class="field">
                <div class="label">Subjek</div>
                <div class="value">{{ $data['subject'] }}</div>
            </div>
            <div class="field">
                <div class="label">Pesan</div>
                <div class="value">{!! nl2br(e($data['message'])) !!}</div>
            </div>
        </div>
        <div class="footer">
            Email ini dikirim otomatis dari website. Harap tidak membalas email ini.
        </div>
    </div>
</body>
</html>
