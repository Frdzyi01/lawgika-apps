@extends('layouts-customer.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

<style>
/* Header Styling */
.detail-header-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 16px;
}
.detail-page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.order-pill-code {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.85rem;
    font-weight: 700;
    background: #f1f5f9;
    color: #1e293b;
    border: 1px solid #cbd5e1;
    padding: 4px 10px;
    border-radius: 8px;
}

/* 3-Stat Time Metrics Strip */
.time-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.time-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
}
.time-stat-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.time-stat-value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.2;
}
.time-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.stat-icon-blue   { background: #eff6ff; color: #2563eb; }
.stat-icon-amber  { background: #fffbeb; color: #d97706; }
.stat-icon-green  { background: #f0fdf4; color: #16a34a; }
.stat-icon-red    { background: #fef2f2; color: #dc2626; }

/* Dashboard Cards */
.detail-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    overflow: hidden;
    margin-bottom: 24px;
}
.detail-card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.detail-card-title {
    font-size: 0.96rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.detail-card-body {
    padding: 20px 22px;
}

/* Key-Value Item Rows */
.kv-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.kv-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding-bottom: 11px;
    border-bottom: 1px dashed #f1f5f9;
    gap: 16px;
}
.kv-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.kv-label {
    font-size: 0.86rem;
    color: #64748b;
    font-weight: 500;
    width: 40%;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.kv-value {
    font-size: 0.88rem;
    font-weight: 600;
    color: #1e293b;
    text-align: right;
    flex: 1;
    word-break: break-word;
}

/* Informational Callout Box */
.info-callout-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 0.84rem;
    color: #475569;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 14px;
}
.info-callout-box i {
    color: #2563eb;
    font-size: 1.1rem;
    margin-top: 2px;
    flex-shrink: 0;
}

/* Proof Image */
.proof-img-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 14px;
    text-align: center;
}
.proof-img-preview {
    max-height: 240px;
    max-width: 100%;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    object-fit: contain;
    background: #ffffff;
}

/* Modern Table for Logs */
.table-log {
    width: 100%;
    margin-bottom: 0;
    vertical-align: middle;
}
.table-log thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 11px 16px;
    border-bottom: 1px solid #e2e8f0;
    white-space: nowrap;
}
.table-log tbody td {
    padding: 12px 16px;
    font-size: 0.86rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
}
</style>

<div class="container-fluid py-2">
    {{-- Header Section --}}
    @php
        $sisa = $booking->formatted_remaining_time ?? '0 menit';
        $isExhausted = ($booking->is_expired || $sisa === 'Waktu habis');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                <h1 class="detail-page-title">
                    <i class="fa-solid fa-people-roof text-primary"></i> Detail Reservasi Meeting Room
                </h1>
                <span class="order-pill-code">
                    {{ $booking->order_number ?? ('#MR-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)) }}
                </span>
            </div>
            <p class="text-muted small mb-0">Informasi penggunaan kuota meeting room, rincian jadwal, dan riwayat sesi ruangan Anda.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ url('dashboard/meeting-room') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
            @if($booking->status === 'selesai')
                @if(!$isExhausted)
                    <a href="{{ url('/sewa-meeting-room?book=true') }}" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Reservasi Sesi Baru
                    </a>
                @else
                    <button type="button" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" onclick="showNoQuotaAlert()">
                        <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Reservasi Sesi Baru
                    </button>
                @endif
            @endif
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check text-success fs-5 me-2"></i>
                <div class="fw-semibold">{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 3-Stat Time Metrics Strip --}}
    <div class="time-stat-grid">
        <div class="time-stat-card">
            <div>
                <div class="time-stat-label">Total Alokasi Kuota</div>
                <div class="time-stat-value">{{ $booking->formatSeconds($booking->duration * 3600) }}</div>
            </div>
            <div class="time-stat-icon stat-icon-blue">
                <i class="fa-solid fa-hourglass-start"></i>
            </div>
        </div>

        <div class="time-stat-card">
            <div>
                <div class="time-stat-label">Durasi Terpakai</div>
                <div class="time-stat-value text-primary used-time-display" data-status="{{ $booking->status }}" data-used="{{ $booking->used_seconds }}">
                    {{ $booking->formatted_used_time ?? '0 menit' }}
                </div>
            </div>
            <div class="time-stat-icon stat-icon-amber">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
        </div>

        <div class="time-stat-card">
            <div>
                <div class="time-stat-label">Sisa Kuota Tersedia</div>
                <div class="time-stat-value {{ $isExhausted ? 'text-danger' : 'text-success' }} remaining-time-display" data-status="{{ $booking->status }}" data-remaining="{{ $booking->remaining_seconds }}">
                    {{ $sisa }}
                </div>
            </div>
            <div class="time-stat-icon {{ $isExhausted ? 'stat-icon-red' : 'stat-icon-green' }}">
                <i class="fa-solid {{ $isExhausted ? 'fa-hourglass-end' : 'fa-hourglass-half' }}"></i>
            </div>
        </div>
    </div>

    {{-- Main Row Layout --}}
    <div class="row">
        <!-- Kolom Kiri: Rincian Kuota & Data Pemesan -->
        <div class="col-lg-6 mb-4">
            {{-- Card Paket Kuota & Informasi Layanan --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-solid fa-box-archive text-primary"></i> Rincian Paket Kuota Anda
                    </h6>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Kuota Aktif
                    </span>
                </div>
                <div class="detail-card-body">
                    <div class="kv-list">
                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-tag text-muted"></i> Nama Paket</span>
                            <span class="kv-value text-primary fw-bold">
                                @if($booking->benefit)
                                    {{ $booking->benefit->paket }}
                                @elseif($booking->duration >= 10)
                                    Paket Meeting Room ({{ $booking->duration }} Jam / Tahun)
                                @else
                                    Sewa Meeting Room Reguler ({{ $booking->duration }} Jam)
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-gift text-muted"></i> Jenis Alokasi</span>
                            <span class="kv-value">
                                @if($booking->source_type === 'benefit' || $booking->benefit_id)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 rounded-pill fw-bold">
                                        <i class="fa-solid fa-gift me-1"></i> Kuota Benefit PT (Bundling Layanan)
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill fw-bold">
                                        <i class="fa-solid fa-credit-card me-1"></i> Pembelian Paket / Mandiri
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-clock text-muted"></i> Alokasi Kuota Total</span>
                            <span class="kv-value fw-bold text-dark">{{ $booking->duration }} Jam ({{ $booking->duration * 60 }} Menit)</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-battery-half text-muted"></i> Status Kuota</span>
                            <span class="kv-value">
                                @if($booking->is_expired)
                                    <span class="badge bg-danger text-white px-2 py-0.5 rounded">Masa Berlaku Expired</span>
                                @elseif($booking->remaining_seconds <= 0)
                                    <span class="badge bg-danger text-white px-2 py-0.5 rounded">Kuota Telah Habis Terpakai</span>
                                @elseif($booking->status === 'checkin')
                                    <span class="badge bg-primary text-white px-2 py-0.5 rounded">Sedang Digunakan (Check-In)</span>
                                @else
                                    <span class="badge bg-success text-white px-2 py-0.5 rounded">Aktif &amp; Siap Digunakan</span>
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-calendar-plus text-muted"></i> Tanggal Transaksi</span>
                            <span class="kv-value">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }} WIB</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-calendar-xmark text-muted"></i> Masa Berlaku Kuota</span>
                            <span class="kv-value text-danger fw-bold">{{ \Carbon\Carbon::parse($booking->created_at)->addYear()->format('d M Y') }} (1 Tahun)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Data Pemesan & Perusahaan --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-solid fa-user-tie text-primary"></i> Data Pemesan &amp; Perusahaan
                    </h6>
                </div>

                <div class="detail-card-body">
                    <div class="kv-list">
                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-user text-muted"></i> Nama Pemesan</span>
                            <span class="kv-value">{{ $booking->user ? $booking->user->name : ($booking->name ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-envelope text-muted"></i> Alamat Email</span>
                            <span class="kv-value">{{ $booking->email ?? ($booking->user->email ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-building text-muted"></i> Nama Perusahaan</span>
                            <span class="kv-value">{{ $booking->nama_perusahaan ?? ($booking->user->company_name ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-briefcase text-muted"></i> Bidang Usaha</span>
                            <span class="kv-value">{{ $booking->bidang_usaha ?? ($booking->user->business_type ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-map-location-dot text-muted"></i> Alamat Usaha</span>
                            <span class="kv-value">{{ $booking->alamat_usaha ?? ($booking->user->address ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-clipboard-list text-muted"></i> Keperluan</span>
                            <span class="kv-value text-primary">{{ $booking->keperluan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Jadwal Reservasi Sesi & Status Pembayaran -->
        <div class="col-lg-6 mb-4 d-flex flex-column gap-4">
            {{-- Card Jadwal Reservasi Sesi --}}
            <div class="detail-card mb-0">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-regular fa-calendar-check text-primary"></i> Jadwal Sesi Reservasi &amp; Ruangan
                    </h6>
                    @if($booking->room_name && $booking->date)
                        <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold">
                            <i class="fa-solid fa-door-open text-primary me-1"></i> {{ $booking->room_name }}
                        </span>
                    @else
                        <span class="badge bg-light text-muted border px-2.5 py-1">
                            Ruangan Belum Ditugaskan
                        </span>
                    @endif
                </div>
                <div class="detail-card-body">
                    <div class="kv-list">
                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-calendar text-muted"></i> Tanggal Penggunaan</span>
                            <span class="kv-value">
                                @if($booking->date)
                                    <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->date)->translatedFormat('l, d F Y') }}</span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary border px-2.5 py-1 rounded fw-semibold">
                                        <i class="fa-solid fa-calendar-xmark me-1"></i> Belum Memilih Jadwal Sesi
                                    </span>
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-clock text-muted"></i> Jam Sesi</span>
                            <span class="kv-value">
                                @if($booking->start_time && $booking->date)
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold" style="font-size:0.84rem;">
                                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} WIB 
                                        @if($booking->end_time)
                                            - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                                        @endif
                                    </span>
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-users text-muted"></i> Jumlah Peserta</span>
                            <span class="kv-value">{{ ($booking->date) ? ($booking->participants ?? 1) . ' Orang' : '–' }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-solid fa-door-open text-muted"></i> Ruangan Ditugaskan</span>
                            <span class="kv-value">
                                @if($booking->room_name && $booking->date)
                                    <span class="fw-bold text-dark">{{ $booking->room_name }}</span>
                                @else
                                    <span class="text-muted">Menyesuaikan Saat Check In di Kantor</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    @if(!$booking->date)
                        <div class="info-callout-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <div>
                                <strong>Tidak Ada Jadwal Sesi Aktif:</strong> Sesi sebelumnya telah selesai (Check Out). Anda memiliki sisa kuota aktif <strong>{{ $booking->duration }} Jam</strong> yang dapat digunakan kapan saja. Silakan klik tombol <strong>[Ajukan Reservasi Sesi Baru]</strong> di atas untuk memesan jadwal berikutnya.
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card Bukti Pembayaran / Billing --}}
            @php
                $proofPath = $booking->payment_proof ?? null;
            @endphp
            <div class="detail-card mb-0">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-solid fa-receipt text-primary"></i> Status Tagihan &amp; Pembayaran
                    </h6>
                    @if($booking->source_type === 'benefit' && (!$booking->total_price || $booking->total_price == 0))
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold">
                            <i class="fa-solid fa-check me-1"></i> Bebas Biaya (Benefit PT)
                        </span>
                    @elseif($proofPath)
                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold">
                            <i class="fa-solid fa-circle-check me-1"></i> Bukti Terverifikasi
                        </span>
                    @else
                        <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill">
                            Tanpa Bukti Transfer
                        </span>
                    @endif
                </div>

                <div class="detail-card-body">
                    @if($booking->total_price > 0)
                        <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 mb-3 border">
                            <span class="text-muted fw-semibold">Total Tagihan:</span>
                            <span class="fs-5 fw-bold text-primary">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if($proofPath)
                        <div class="proof-img-box">
                            <a href="{{ asset('storage/' . $proofPath) }}" target="_blank">
                                <img src="{{ asset('storage/' . $proofPath) }}" alt="Bukti Pembayaran" class="proof-img-preview mb-2">
                            </a>
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-outline-primary btn-sm px-3 rounded-2 fw-semibold">
                                    <i class="fa-solid fa-up-right-from-square me-1"></i> Buka Gambar Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-3 text-muted">
                            <i class="fa-solid fa-circle-info fs-4 text-muted mb-1 d-block opacity-50"></i>
                            @if($booking->source_type === 'benefit')
                                <p class="small mb-0">Pemesanan ini dipotong dari kuota benefit aktif Anda tanpa biaya tambahan.</p>
                            @else
                                <p class="small mb-0">Pembayaran diproses melalui transfer atau diproses langsung oleh Admin Lawgika.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Riwayat Sesi Check In / Check Out --}}
    <div class="detail-card">
        <div class="detail-card-header">
            <h6 class="detail-card-title">
                <i class="fa-solid fa-clock-rotate-left text-primary"></i> Riwayat Sesi Penggunaan Ruangan
            </h6>
            <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold">{{ $logs->count() }} Entri Sesi</span>
        </div>
        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-log">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Aktivitas Sesi</th>
                            <th>Tanggal</th>
                            <th>Waktu (WIB)</th>
                            <th>Durasi Sesi</th>
                            <th class="pe-4">Catatan / Fasilitas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td class="ps-4 fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    @if($log->type === 'checkin')
                                        <span class="badge bg-success text-white px-2.5 py-1 rounded"><i class="fa-solid fa-door-open me-1"></i> Check In Ruangan</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-2.5 py-1 rounded"><i class="fa-solid fa-right-from-bracket me-1"></i> Sesi Selesai (Check Out)</span>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($log->timestamp)->translatedFormat('d M Y') }}</td>
                                <td class="font-monospace text-primary fw-bold">{{ \Carbon\Carbon::parse($log->timestamp)->format('H:i:s') }} WIB</td>
                                <td>
                                    @if($log->type === 'checkout' && $index > 0 && $logs[$index-1]->type === 'checkin')
                                        @php
                                            $diff = \Carbon\Carbon::parse($logs[$index-1]->timestamp)->diffInSeconds($log->timestamp);
                                        @endphp
                                        <span class="badge bg-light text-dark border">{{ $booking->formatSeconds($diff) }}</span>
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    @php
                                        $logNotes = $log->notes ?: ($log->type === 'checkout' && $index > 0 ? $logs[$index-1]->notes : null);
                                    @endphp
                                    @if($logNotes)
                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size:0.72rem;">{{ $logNotes }}</span>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-0.5" style="font-size:0.72rem;">Standar Ruangan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-inbox fs-4 mb-2 d-block opacity-50"></i>
                                    Belum ada catatan riwayat sesi penggunaan meeting room.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showNoQuotaAlert() {
        Swal.fire({
            icon: 'warning',
            title: 'Belum Memiliki Paket / Kuota',
            html: `
                <div style="text-align: center; color: #475569; font-size: 0.95rem; line-height: 1.6;">
                    <p class="mb-2">Anda belum memiliki <strong>Paket Benefit</strong> atau <strong>kuota Meeting Room</strong> yang aktif.</p>
                    <p class="mb-0">Silakan membeli paket terlebih dahulu untuk menikmati fasilitas ruang meeting.</p>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-cart-shopping me-1"></i> Beli Paket Sekarang',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#64748b',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('meeting-room.order', ['package' => 'paket']) }}";
            }
        });
    }

    function formatSecs(seconds) {
        if (seconds <= 0) return 'Waktu habis';
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = Math.floor(seconds % 60);
        return h + " jam " + m + " menit " + s + " detik";
    }

    setInterval(() => {
        document.querySelectorAll('.used-time-display').forEach(el => {
            if (el.dataset.status === 'checkin') {
                let used = parseInt(el.dataset.used);
                used++;
                el.dataset.used = used;
                el.innerText = formatSecs(used);
            }
        });
        document.querySelectorAll('.remaining-time-display').forEach(el => {
            if (el.dataset.status === 'checkin') {
                let rem = parseInt(el.dataset.remaining);
                if (rem > 0) {
                    rem--;
                    el.dataset.remaining = rem;
                    el.innerText = formatSecs(rem);
                    if (rem === 0) location.reload();
                }
            }
        });
    }, 1000);
</script>
@endpush
