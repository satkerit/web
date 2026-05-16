<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengaduan Nasabah</title>
    @php $nonce = request()->attributes->get('csp_nonce'); @endphp
    <style nonce="{{ $nonce }}">
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            line-height: 1.5;
        }

        /* ── HEADER ── */
        .report-header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
            color: #fff;
            padding: 24px 32px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 0;
        }
        .header-logo {
            width: 64px;
            height: 64px;
            object-fit: contain;
            background: #fff;
            border-radius: 10px;
            padding: 6px;
            flex-shrink: 0;
        }
        .header-logo-placeholder {
            width: 64px;
            height: 64px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
        }
        .header-info { flex: 1; }
        .header-company { font-size: 18px; font-weight: 700; letter-spacing: 0.3px; }
        .header-tagline { font-size: 10px; opacity: 0.85; margin-top: 2px; }
        .header-title-block { text-align: right; }
        .header-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-subtitle { font-size: 10px; opacity: 0.85; margin-top: 3px; }

        /* ── DIVIDER ── */
        .header-divider {
            height: 4px;
            background: linear-gradient(90deg, #f59e0b, #ef4444, #8b5cf6, #2563eb);
        }

        /* ── META INFO ── */
        .meta-bar {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 10px;
            color: #64748b;
        }
        .meta-bar span { display: inline-flex; align-items: center; gap: 4px; }

        /* ── FILTER BADGE ── */
        .filter-section {
            padding: 10px 32px;
            background: #fffbeb;
            border-bottom: 1px solid #fde68a;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            font-size: 10px;
        }
        .filter-label { color: #92400e; font-weight: 600; }
        .filter-badge {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            color: #92400e;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 9.5px;
        }

        /* ── STATS ── */
        .stats-section {
            padding: 16px 32px;
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
        }
        .stat-card {
            text-align: center;
            padding: 10px 6px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .stat-card.total   { background: #eff6ff; border-color: #bfdbfe; }
        .stat-card.pending { background: #fffbeb; border-color: #fde68a; }
        .stat-card.progress{ background: #eff6ff; border-color: #bfdbfe; }
        .stat-card.resolved{ background: #f0fdf4; border-color: #bbf7d0; }
        .stat-card.high    { background: #fef2f2; border-color: #fecaca; }
        .stat-card.medium  { background: #fffbeb; border-color: #fde68a; }
        .stat-card.low     { background: #f0fdf4; border-color: #bbf7d0; }
        .stat-number { font-size: 22px; font-weight: 800; line-height: 1; }
        .stat-card.total   .stat-number { color: #1d4ed8; }
        .stat-card.pending .stat-number { color: #d97706; }
        .stat-card.progress .stat-number{ color: #2563eb; }
        .stat-card.resolved .stat-number{ color: #16a34a; }
        .stat-card.high    .stat-number { color: #dc2626; }
        .stat-card.medium  .stat-number { color: #d97706; }
        .stat-card.low     .stat-number { color: #16a34a; }
        .stat-label { font-size: 9px; color: #64748b; margin-top: 3px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.3px; }

        /* ── TABLE ── */
        .table-section { padding: 16px 32px 24px; }
        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid #2563eb;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 14px;
            background: #2563eb;
            border-radius: 2px;
        }

        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        thead tr {
            background: linear-gradient(135deg, #1e3a5f, #2563eb);
            color: #fff;
        }
        thead th {
            padding: 9px 10px;
            text-align: left;
            font-weight: 600;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            white-space: nowrap;
        }
        tbody tr { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #eff6ff; }
        tbody td { padding: 8px 10px; vertical-align: top; }

        .ticket-num { font-weight: 700; color: #1d4ed8; font-size: 10px; }
        .ticket-subject { color: #64748b; font-size: 9.5px; margin-top: 2px; }
        .nasabah-name { font-weight: 600; color: #1e293b; }
        .nasabah-contact { color: #64748b; font-size: 9.5px; }

        /* badges */
        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 600;
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

        /* ── FOOTER ── */
        .report-footer {
            margin: 0 32px;
            padding: 12px 0;
            border-top: 2px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 9.5px;
            color: #94a3b8;
        }
        .footer-left { line-height: 1.6; }
        .footer-right { text-align: right; line-height: 1.6; }
        .signature-block {
            margin-top: 40px;
            display: flex;
            justify-content: flex-end;
            padding: 0 32px;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            border-top: 1px solid #1e293b;
            margin-top: 50px;
            padding-top: 4px;
            font-size: 10px;
            font-weight: 600;
        }
        .signature-title { font-size: 9.5px; color: #64748b; }

        /* ── EMPTY ── */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #94a3b8;
        }
        .empty-state svg { width: 48px; height: 48px; margin: 0 auto 12px; display: block; opacity: 0.4; }

        /* ── PRINT ── */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }

            /*
             * Ukuran kertas : A4 Landscape (297mm × 210mm)
             * Margin standar dokumen resmi landscape:
             *   Atas    : 20mm
             *   Kanan   : 20mm
             *   Bawah   : 20mm
             *   Kiri    : 25mm  (sedikit lebih lebar untuk jilid)
             */
            @page {
                size: A4 landscape;
                margin-top: 10mm;
                margin-right: 10mm;
                margin-bottom: 10mm;
                margin-left: 12mm;
            }

            /* Pastikan header/footer tidak terpotong */
            .report-header  { break-inside: avoid; }
            .stats-section  { break-inside: avoid; }
            .report-footer  { break-inside: avoid; }
            .signature-block { break-inside: avoid; }
            tbody tr        { break-inside: avoid; }
        }

        /* ── PRINT BUTTON ── */
        .print-toolbar {
            position: fixed;
            top: 16px;
            right: 16px;
            display: flex;
            gap: 8px;
            z-index: 999;
        }
        .btn-print {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.4);
            transition: background 0.2s;
        }
        .btn-print:hover { background: #1d4ed8; }
        .btn-back {
            background: #fff;
            color: #475569;
            border: 1px solid #e2e8f0;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            text-decoration: none;
            transition: background 0.2s;
        }
        .btn-back:hover { background: #f8fafc; }
    </style>
</head>
<body>

{{-- Toolbar (tidak ikut cetak) --}}
<div class="print-toolbar no-print">
    <a href="{{ route('admin.customer-complaints.index') }}" class="btn-back">
        ← Kembali
    </a>
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
            {{ $companyInfo ? strtoupper(substr($companyInfo->name ?? 'C', 0, 1)) : 'C' }}
        </div>
    @endif
    <div class="header-info">
        <div class="header-company">{{ $companyInfo->name ?? config('app.name') }}</div>
        <div class="header-tagline">{{ $companyInfo->address ?? '' }}</div>
        @if($companyInfo && $companyInfo->phone)
            <div class="header-tagline">Telp: {{ $companyInfo->phone }}{{ $companyInfo->email ? ' · ' . $companyInfo->email : '' }}</div>
        @endif
    </div>
    <div class="header-title-block">
        <div class="header-title">Laporan Pengaduan Nasabah</div>
        <div class="header-subtitle">Periode: {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') : 'Semua' }} — {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') : 'Semua' }}</div>
        <div class="header-subtitle" style="margin-top:4px;">No. Dok: RPT-ADU-{{ now()->format('Ymd') }}</div>
    </div>
</div>
<div class="header-divider"></div>

{{-- Meta bar --}}
<div class="meta-bar">
    <div style="display:flex;gap:20px;">
        <span>📅 Dicetak: {{ $printedAt }}</span>
        <span>👤 Oleh: {{ $printedBy }}</span>
    </div>
    <span>Total Data: <strong>{{ $stats['total'] }} pengaduan</strong></span>
</div>

{{-- Filter aktif --}}
@if(array_filter($filters))
<div class="filter-section">
    <span class="filter-label">Filter Aktif:</span>
    @if($filters['status'])
        <span class="filter-badge">Status: {{ ['pending'=>'Menunggu','in_progress'=>'Diproses','resolved'=>'Selesai','closed'=>'Ditutup'][$filters['status']] ?? $filters['status'] }}</span>
    @endif
    @if($filters['category'])
        <span class="filter-badge">Kategori: {{ ['service'=>'Pelayanan','product'=>'Produk','transaction'=>'Transaksi','facility'=>'Fasilitas','staff'=>'Petugas','other'=>'Lainnya'][$filters['category']] ?? $filters['category'] }}</span>
    @endif
    @if($filters['priority'])
        <span class="filter-badge">Prioritas: {{ ['high'=>'Tinggi','medium'=>'Sedang','low'=>'Rendah'][$filters['priority']] ?? $filters['priority'] }}</span>
    @endif
    @if($filters['date_from'])
        <span class="filter-badge">Dari: {{ \Carbon\Carbon::parse($filters['date_from'])->format('d M Y') }}</span>
    @endif
    @if($filters['date_to'])
        <span class="filter-badge">Sampai: {{ \Carbon\Carbon::parse($filters['date_to'])->format('d M Y') }}</span>
    @endif
    @if($filters['search'])
        <span class="filter-badge">Pencarian: "{{ $filters['search'] }}"</span>
    @endif
</div>
@endif

{{-- Stats --}}
<div class="stats-section">
    <div class="stat-card total">
        <div class="stat-number">{{ $stats['total'] }}</div>
        <div class="stat-label">Total</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-number">{{ $stats['pending'] }}</div>
        <div class="stat-label">Menunggu</div>
    </div>
    <div class="stat-card progress">
        <div class="stat-number">{{ $stats['in_progress'] }}</div>
        <div class="stat-label">Diproses</div>
    </div>
    <div class="stat-card resolved">
        <div class="stat-number">{{ $stats['resolved'] }}</div>
        <div class="stat-label">Selesai</div>
    </div>
    <div class="stat-card high">
        <div class="stat-number">{{ $stats['high'] }}</div>
        <div class="stat-label">Prioritas Tinggi</div>
    </div>
    <div class="stat-card medium">
        <div class="stat-number">{{ $stats['medium'] }}</div>
        <div class="stat-label">Prioritas Sedang</div>
    </div>
    <div class="stat-card low">
        <div class="stat-number">{{ $stats['low'] }}</div>
        <div class="stat-label">Prioritas Rendah</div>
    </div>
</div>

{{-- Table --}}
<div class="table-section">
    <div class="section-title">Daftar Pengaduan Nasabah</div>

    @if($complaints->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p>Tidak ada data pengaduan yang sesuai filter.</p>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:30px;">No</th>
                    <th style="width:130px;">No. Tiket</th>
                    <th style="width:130px;">Nasabah</th>
                    <th style="width:80px;">Kategori</th>
                    <th style="width:70px;">Prioritas</th>
                    <th style="width:70px;">Status</th>
                    <th>Subjek Pengaduan</th>
                    <th style="width:80px;">Tgl. Masuk</th>
                    <th style="width:80px;">Tgl. Selesai</th>
                    <th style="width:90px;">Ditangani</th>
                </tr>
            </thead>
            <tbody>
                @foreach($complaints as $i => $c)
                <tr>
                    <td style="text-align:center;color:#94a3b8;">{{ $i + 1 }}</td>
                    <td>
                        <div class="ticket-num">{{ $c->ticket_number }}</div>
                        @if($c->account_number)
                            <div class="ticket-subject">Rek: {{ $c->account_number }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="nasabah-name">{{ $c->name }}</div>
                        <div class="nasabah-contact">{{ $c->phone }}</div>
                    </td>
                    <td>
                        <div class="flex flex-col gap-1">
                            <span class="badge badge-category">{{ $c->category_label }}</span>
                            @if($c->subcategory)
                                <div style="font-size:8.5px;color:#2563eb;font-weight:600;margin-top:2px;">• {{ $c->subcategory_label }}</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($c->priority === 'high')
                            <span class="badge badge-high">Tinggi</span>
                        @elseif($c->priority === 'medium')
                            <span class="badge badge-medium">Sedang</span>
                        @else
                            <span class="badge badge-low">Rendah</span>
                        @endif
                    </td>
                    <td>
                        @if($c->status === 'pending')
                            <span class="badge badge-pending">Menunggu</span>
                        @elseif($c->status === 'in_progress')
                            <span class="badge badge-progress">Diproses</span>
                        @elseif($c->status === 'resolved')
                            <span class="badge badge-resolved">Selesai</span>
                        @else
                            <span class="badge badge-closed">Ditutup</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:600;color:#1e293b;">{{ Str::limit($c->subject, 55) }}</div>
                        @if($c->branch_office)
                            <div style="font-size:9px;color:#94a3b8;">{{ $c->branch_office }}</div>
                        @endif
                    </td>
                    <td style="color:#475569;white-space:nowrap;">{{ $c->created_at->format('d M Y') }}</td>
                    <td style="color:#475569;white-space:nowrap;">{{ $c->resolved_at ? $c->resolved_at->format('d M Y') : '—' }}</td>
                    <td style="color:#475569;">{{ $c->handler?->name ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

{{-- Signature --}}
<div class="signature-block">
    <div class="signature-box">
        <div style="font-size:10px;color:#64748b;">{{ $companyInfo->address ? Str::before($companyInfo->address, ',') : '' }}, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
        <div class="signature-line">{{ $printedBy }}</div>
        <div class="signature-title">Petugas Pengaduan</div>
    </div>
</div>

{{-- Footer --}}
<div class="report-footer">
    <div class="footer-left">
        <div><strong>{{ $companyInfo->name ?? config('app.name') }}</strong></div>
        @if($companyInfo && $companyInfo->ojk_license)
            <div>{{ $companyInfo->ojk_license }}</div>
        @endif
    </div>
    <div class="footer-right">
        <div>Dokumen ini dicetak secara otomatis oleh sistem</div>
        <div>{{ config('app.url') }} · {{ $printedAt }}</div>
    </div>
</div>

</body>
</html>
