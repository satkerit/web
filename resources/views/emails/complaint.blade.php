<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengaduan Baru</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #ef4444, #f97316); color: white; padding: 20px; border-radius: 8px 8px 0 0; }
        .ticket { background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 4px; display: inline-block; margin-top: 10px; font-family: monospace; }
        .content { background: #f9fafb; padding: 20px; border: 1px solid #e5e7eb; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #374151; font-size: 12px; text-transform: uppercase; }
        .value { margin-top: 5px; padding: 10px; background: white; border-radius: 4px; border: 1px solid #e5e7eb; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-fraud { background: #fef2f2; color: #dc2626; }
        .badge-violation { background: #fef3c7; color: #d97706; }
        .badge-ethics { background: #f3e8ff; color: #9333ea; }
        .badge-other { background: #f3f4f6; color: #4b5563; }
        .footer { padding: 15px; text-align: center; font-size: 12px; color: #6b7280; }
        .warning { background: #fef3c7; border: 1px solid #fcd34d; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">Laporan Pengaduan Baru</h2>
            <div class="ticket">{{ $complaint->ticket_number }}</div>
        </div>
        <div class="content">
            @if($complaint->is_anonymous)
            <div class="warning">
                <strong>⚠️ Laporan Anonim</strong><br>
                Pelapor memilih untuk tidak mengungkapkan identitasnya.
            </div>
            @endif

            <div class="field">
                <div class="label">Jenis Pelanggaran</div>
                <div class="value">
                    @php
                        $typeLabels = [
                            'fraud' => ['Kecurangan (Fraud)', 'badge-fraud'],
                            'violation' => ['Pelanggaran Peraturan', 'badge-violation'],
                            'ethics' => ['Pelanggaran Kode Etik', 'badge-ethics'],
                            'abuse' => ['Penyalahgunaan Wewenang', 'badge-violation'],
                            'safety' => ['Keselamatan Kerja', 'badge-other'],
                            'other' => ['Lainnya', 'badge-other'],
                        ];
                        $type = $typeLabels[$complaint->type] ?? ['Lainnya', 'badge-other'];
                    @endphp
                    <span class="badge {{ $type[1] }}">{{ $type[0] }}</span>
                </div>
            </div>

            @if(!$complaint->is_anonymous)
            <div class="field">
                <div class="label">Nama Pelapor</div>
                <div class="value">{{ $complaint->name }}</div>
            </div>
            <div class="field">
                <div class="label">Email</div>
                <div class="value">{{ $complaint->email }}</div>
            </div>
            @if($complaint->phone)
            <div class="field">
                <div class="label">Telepon</div>
                <div class="value">{{ $complaint->phone }}</div>
            </div>
            @endif
            @endif

            <div class="field">
                <div class="label">Subjek</div>
                <div class="value">{{ $complaint->subject }}</div>
            </div>

            <div class="field">
                <div class="label">Deskripsi</div>
                <div class="value">{!! nl2br(e($complaint->description)) !!}</div>
            </div>

            @if($complaint->reported_person)
            <div class="field">
                <div class="label">Pihak yang Dilaporkan</div>
                <div class="value">{{ $complaint->reported_person }}</div>
            </div>
            @endif

            @if($complaint->reported_department)
            <div class="field">
                <div class="label">Departemen</div>
                <div class="value">{{ $complaint->reported_department }}</div>
            </div>
            @endif

            @if($complaint->incident_date)
            <div class="field">
                <div class="label">Tanggal Kejadian</div>
                <div class="value">{{ \Carbon\Carbon::parse($complaint->incident_date)->format('d F Y') }}</div>
            </div>
            @endif

            @if($complaint->incident_location)
            <div class="field">
                <div class="label">Lokasi Kejadian</div>
                <div class="value">{{ $complaint->incident_location }}</div>
            </div>
            @endif

            @if($complaint->attachments && count($complaint->attachments) > 0)
            <div class="field">
                <div class="label">Lampiran</div>
                <div class="value">{{ count($complaint->attachments) }} file terlampir</div>
            </div>
            @endif
        </div>
        <div class="footer">
            Silakan login ke panel admin untuk menindaklanjuti laporan ini.<br>
            Email ini dikirim otomatis dari sistem Whistleblowing.
        </div>
    </div>
</body>
</html>
