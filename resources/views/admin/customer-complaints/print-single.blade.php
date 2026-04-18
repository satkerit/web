<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengaduan {{ $customerComplaint->ticket_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11.5px;
            color: #1e293b;
            background: #fff;
            line-height: 1.6;
        }

        /* ── HEADER ── */
        .report-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #fff;
            padding: 22px 32px;
            display: flex;
            align-items: center;
            gap: 18px;
        }
        .header-logo {
            width: 60px; height: 60px;
            object-fit: contain;
            background: #fff;
            border-radius: 10px;
            padding: 6px;
            flex-shrink: 0;
        }
        .header-logo-placeholder {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 800; color: #fff; flex-shrink: 0;
        }
        .header-info { flex: 1; }
        .header-company { font-size: 17px; font-weight: 700; }
        .header-tagline { font-size: 10px; opacity: 0.8; margin-top: 2px; }
        .header-right { text-align: right; }
        .header-doc-title { font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .header-doc-num { font-size: 10px; opacity: 0.85; margin-top: 4px; }

        .header-divider { height: 4px; background: linear-gradient(90deg, #f59e0b, #ef4444, #8b5cf6, #2563eb); }

        /* ── TICKET BANNER ── */
        .ticket-banner {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .ticket-number { font-size: 20px; font-weight: 800; color: #1d4ed8; letter-spacing: 1px; }
        .ticket-date { font-size: 10px; color: #64748b; margin-top: 2px; }
        .ticket-badges { display: flex; gap: 8px; align-items: center; }

        /* ── BADGE ── */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            white-space: nowrap;
        }
        .badge-pending  { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-progress { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .badge-resolved { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-closed   { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }
        .badge-high     { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-medium   { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
        .badge-low      { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .badge-category { background: #ede9fe; color: #5b21b6; border: 1px solid #c4b5fd; }

        /* ── BODY ── */
        .body-content { padding: 20px 32px; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 16px; }

        /* ── CARD ── */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        .card-header {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: #fff;
            padding: 8px 14px;
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .card-header-green  { background: linear-gradient(135deg, #065f46, #059669); }
        .card-header-purple { background: linear-gradient(135deg, #4c1d95, #7c3aed); }
        .card-header-amber  { background: linear-gradient(135deg, #78350f, #d97706); }
        .card-header-slate  { background: linear-gradient(135deg, #1e293b, #475569); }

        .card-body { padding: 14px; }

        /* ── FIELD ── */
        .field { margin-bottom: 10px; }
        .field:last-child { margin-bottom: 0; }
        .field-label { font-size: 9.5px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 3px; }
        .field-value { font-size: 11.5px; color: #1e293b; }
        .field-value.large { font-size: 13px; font-weight: 600; }
        .field-value.description {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 11px;
            line-height: 1.7;
            white-space: pre-wrap;
            color: #334155;
        }
        .field-value.resolution {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 11px;
            line-height: 1.7;
            white-space: pre-wrap;
            color: #166534;
        }
        .field-value.notes {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 11px;
            line-height: 1.7;
            white-space: pre-wrap;
            color: #78350f;
        }

        /* ── TIMELINE ── */
        .timeline { padding: 4px 0; }
        .timeline-item { display: flex; gap: 10px; margin-bottom: 10px; }
        .timeline-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #2563eb;
            flex-shrink: 0;
            margin-top: 3px;
        }
        .timeline-dot.green { background: #16a34a; }
        .timeline-dot.amber { background: #d97706; }
        .timeline-content { flex: 1; }
        .timeline-title { font-weight: 600; font-size: 11px; }
        .timeline-time { font-size: 9.5px; color: #94a3b8; }

        /* ── FOOTER ── */
        .report-footer {
            margin: 0 32px;
            padding: 12px 0;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 9.5px;
            color: #94a3b8;
        }

        .signature-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            padding: 0 32px;
            margin-bottom: 24px;
        }
        .signature-box { text-align: center; }
        .signature-title-top { font-size: 10px; color: #64748b; margin-bottom: 2px; }
        .signature-line { border-top: 1px solid #1e293b; margin-top: 50px; padding-top: 4px; font-size: 10.5px; font-weight: 700; }
        .signature-role { font-size: 9.5px; color: #64748b; }

        /* ── PRINT ── */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }

            /*
             * Ukuran kertas : A4 Portrait (210mm × 297mm)
             * Margin standar dokumen resmi Indonesia (SNI/Permenpan):
             *   Atas    : 30mm
             *   Kiri    : 40mm  (lebih lebar untuk jilid/arsip)
             *   Kanan   : 30mm
             *   Bawah   : 30mm
             */
            @page {
                size: A4 portrait;
                margin-top: 10mm;
                margin-left: 12mm;
                margin-right: 10mm;
                margin-bottom: 10mm;
            }

            /* Cegah elemen terpotong antar halaman */
            .report-header   { break-inside: avoid; }
            .ticket-banner   { break-inside: avoid; }
            .card            { break-inside: avoid; }
            .grid-2          { break-inside: avoid; }
            .signature-row   { break-inside: avoid; }
            .report-footer   { break-inside: avoid; }
        }

        .print-toolbar {
            position: fixed; top: 16px; right: 16px;
            display: flex; gap: 8px; z-index: 999;
        }
        .btn-print {
            background: #2563eb; color: #fff; border: none;
            padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
        }
        .btn-back {
            background: #fff; color: #475569; border: 1px solid #e2e8f0;
            padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600;
            cursor: pointer; display: flex; align-items: center; gap: 6px;
            text-decoration: none; box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .divider { height: 1px; background: #e2e8f0; margin: 0 0 16px; }
        .text-muted { color: #94a3b8; }
        .inline-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    </style>
</head>
<body>

{{-- Toolbar --}}
<div class="print-toolbar no-print">
    <a href="{{ route('admin.customer-complaints.show', $customerComplaint) }}" class="btn-back">← Kembali</a>
    <button class="btn-print" onclick="window.print()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
        </svg>
        Cetak / Simpan PDF
    </button>
</div>

{{-- Header --}}
<div class="report-header">
    @if($companyInfo && $companyInfo->logo)
        <img src="{{ Storage::url($companyInfo->logo) }}" alt="Logo" class="header-logo">
    @else
        <div class="header-logo-placeholder">
            {{ strtoupper(substr($companyInfo->name ?? 'C', 0, 1)) }}
        </div>
    @endif
    <div class="header-info">
        <div class="header-company">{{ $companyInfo->name ?? config('app.name') }}</div>
        <div class="header-tagline">{{ $companyInfo->address ?? '' }}</div>
        @if($companyInfo?->phone)
            <div class="header-tagline">Telp: {{ $companyInfo->phone }}{{ $companyInfo->email ? ' · ' . $companyInfo->email : '' }}</div>
        @endif
    </div>
    <div class="header-right">
        <div class="header-doc-title">Formulir Pengaduan Nasabah</div>
        <div class="header-doc-num">No. Dok: {{ $customerComplaint->ticket_number }}</div>
        <div class="header-doc-num">Tgl. Cetak: {{ now()->format('d M Y') }}</div>
    </div>
</div>
<div class="header-divider"></div>

{{-- Ticket Banner --}}
<div class="ticket-banner">
    <div>
        <div class="ticket-number">{{ $customerComplaint->ticket_number }}</div>
        <div class="ticket-date">Diterima: {{ $customerComplaint->created_at->locale('id')->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm') }}</div>
    </div>
    <div class="ticket-badges">
        <span class="badge badge-category">{{ $customerComplaint->category_label }}</span>
        @if($customerComplaint->priority === 'high')
            <span class="badge badge-high">⚠ Prioritas Tinggi</span>
        @elseif($customerComplaint->priority === 'medium')
            <span class="badge badge-medium">Prioritas Sedang</span>
        @else
            <span class="badge badge-low">Prioritas Rendah</span>
        @endif
        @if($customerComplaint->status === 'pending')
            <span class="badge badge-pending">● Menunggu</span>
        @elseif($customerComplaint->status === 'in_progress')
            <span class="badge badge-progress">● Diproses</span>
        @elseif($customerComplaint->status === 'resolved')
            <span class="badge badge-resolved">✓ Selesai</span>
        @else
            <span class="badge badge-closed">Ditutup</span>
        @endif
    </div>
</div>

{{-- Body --}}
<div class="body-content">

    {{-- Row 1: Data Nasabah + Info Pengaduan --}}
    <div class="grid-2">
        {{-- Data Nasabah --}}
        <div class="card">
            <div class="card-header card-header-green">
                👤 Data Nasabah
            </div>
            <div class="card-body">
                <div class="inline-grid">
                    <div class="field">
                        <div class="field-label">Nama Lengkap</div>
                        <div class="field-value large">{{ $customerComplaint->name }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">No. Rekening</div>
                        <div class="field-value large">{{ $customerComplaint->account_number ?? '—' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Email</div>
                        <div class="field-value">{{ $customerComplaint->email }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">No. Telepon</div>
                        <div class="field-value">{{ $customerComplaint->phone }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Pengaduan --}}
        <div class="card">
            <div class="card-header card-header-purple">
                📋 Informasi Pengaduan
            </div>
            <div class="card-body">
                <div class="inline-grid">
                    <div class="field">
                        <div class="field-label">Kategori</div>
                        <div class="field-value">{{ $customerComplaint->category_label }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Prioritas</div>
                        <div class="field-value">{{ $customerComplaint->priority_label }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Kantor Terkait</div>
                        <div class="field-value">{{ $customerComplaint->branch_office ?? '—' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Tanggal Kejadian</div>
                        <div class="field-value">{{ $customerComplaint->incident_date ? $customerComplaint->incident_date->format('d M Y') : '—' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subjek & Deskripsi --}}
    <div class="card">
        <div class="card-header">
            📝 Detail Pengaduan
        </div>
        <div class="card-body">
            <div class="field">
                <div class="field-label">Subjek Pengaduan</div>
                <div class="field-value large">{{ $customerComplaint->subject }}</div>
            </div>
            <div class="field" style="margin-top:10px;">
                <div class="field-label">Uraian Pengaduan</div>
                <div class="field-value description">{{ $customerComplaint->description }}</div>
            </div>
        </div>
    </div>

    {{-- Penanganan --}}
    <div class="grid-2">
        {{-- Timeline Status --}}
        <div class="card">
            <div class="card-header card-header-amber">
                🕐 Riwayat Penanganan
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">Pengaduan Diterima</div>
                            <div class="timeline-time">{{ $customerComplaint->created_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</div>
                        </div>
                    </div>
                    @if($customerComplaint->handled_by)
                    <div class="timeline-item">
                        <div class="timeline-dot amber"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">Ditangani oleh {{ $customerComplaint->handler?->name }}</div>
                            <div class="timeline-time">Status: Diproses</div>
                        </div>
                    </div>
                    @endif
                    @if($customerComplaint->resolved_at)
                    <div class="timeline-item">
                        <div class="timeline-dot green"></div>
                        <div class="timeline-content">
                            <div class="timeline-title">Pengaduan Diselesaikan</div>
                            <div class="timeline-time">{{ $customerComplaint->resolved_at->locale('id')->isoFormat('D MMM YYYY, HH:mm') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="divider"></div>
                <div class="inline-grid">
                    <div class="field">
                        <div class="field-label">Petugas</div>
                        <div class="field-value">{{ $customerComplaint->handler?->name ?? '—' }}</div>
                    </div>
                    <div class="field">
                        <div class="field-label">Status Akhir</div>
                        <div class="field-value">{{ $customerComplaint->status_label }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resolusi & Catatan --}}
        <div class="card">
            <div class="card-header card-header-slate">
                ✅ Resolusi & Catatan
            </div>
            <div class="card-body">
                <div class="field">
                    <div class="field-label">Resolusi / Penyelesaian</div>
                    @if($customerComplaint->resolution)
                        <div class="field-value resolution">{{ $customerComplaint->resolution }}</div>
                    @else
                        <div class="field-value text-muted" style="font-style:italic;">Belum ada resolusi</div>
                    @endif
                </div>
                <div class="field" style="margin-top:10px;">
                    <div class="field-label">Catatan Internal</div>
                    @if($customerComplaint->admin_notes)
                        <div class="field-value notes">{{ $customerComplaint->admin_notes }}</div>
                    @else
                        <div class="field-value text-muted" style="font-style:italic;">Tidak ada catatan</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Tanda Tangan --}}
<div class="signature-row">
    <div class="signature-box">
        <div class="signature-title-top">Nasabah / Pelapor</div>
        <div class="signature-line">{{ $customerComplaint->name }}</div>
        <div class="signature-role">Nasabah</div>
    </div>
    <div class="signature-box">
        <div class="signature-title-top">Dicetak oleh</div>
        <div class="signature-line">{{ $printedBy }}</div>
        <div class="signature-role">Petugas Pengaduan</div>
    </div>
</div>

{{-- Footer --}}
<div class="report-footer">
    <div>
        <strong>{{ $companyInfo->name ?? config('app.name') }}</strong>
        @if($companyInfo?->ojk_license) · {{ $companyInfo->ojk_license }} @endif
    </div>
    <div>Dicetak: {{ $printedAt }} · {{ config('app.url') }}</div>
</div>

</body>
</html>
