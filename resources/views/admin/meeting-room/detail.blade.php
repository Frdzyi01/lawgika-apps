@extends('layouts-admin.admin')

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
    <div class="detail-header-section">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h1 class="detail-page-title">
                    <i class="fa-solid fa-people-roof text-primary"></i> Detail Reservasi Meeting Room
                </h1>
                <span class="order-pill-code">
                    {{ $booking->order_number ?? ('#MR-' . str_pad($booking->id, 5, '0', STR_PAD_LEFT)) }}
                </span>
            </div>
            <p class="text-muted small mb-0">Rincian lengkap paket kuota meeting room, informasi jadwal, data perusahaan, serta log penggunaan.</p>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ url('admin/meeting-room') }}" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-semibold">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Daftar
            </a>

            @if($booking->status === 'pending' || $booking->payment_status === 'pending')
                @if($booking->source_type === 'benefit')
                    <form action="{{ url('admin/meeting-room/'.$booking->id.'/benefit-approve') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" onclick="return confirm('Setujui reservasi kuota ini?')">
                            <i class="fa-solid fa-check me-1"></i> Setujui Pengajuan
                        </button>
                    </form>
                    <form action="{{ url('admin/meeting-room/'.$booking->id.'/benefit-reject') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3 fw-bold" onclick="return confirm('Tolak reservasi ini?')">
                            <i class="fa-solid fa-xmark me-1"></i> Tolak
                        </button>
                    </form>
                @else
                    <form action="{{ url('admin/meeting-room/'.$booking->id.'/approve-payment') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" onclick="return confirm('Setujui pembayaran dan aktifkan kuota?')">
                            <i class="fa-solid fa-check me-1"></i> Setujui Pembayaran
                        </button>
                    </form>
                    <form action="{{ url('admin/meeting-room/'.$booking->id.'/reject-payment') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-2 rounded-3 fw-bold" onclick="return confirm('Tolak pembayaran?')">
                            <i class="fa-solid fa-xmark me-1"></i> Tolak
                        </button>
                    </form>
                @endif
            @elseif(($booking->status === 'approved' || $booking->payment_status === 'approved') && $booking->status !== 'checkin' && $booking->status !== 'selesai' && $booking->status !== 'rejected')
                @if(empty($booking->start_time))
                    <button type="button" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#createSessionModalDetail">
                        <i class="fa-solid fa-calendar-check me-1"></i> Reservasi Check In
                    </button>
                @else
                    <button type="button" class="btn btn-success btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#checkinModalDetail">
                        <i class="fa-solid fa-door-open me-1"></i> Check In Ruangan
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm px-3 py-2 rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#createSessionModalDetail" title="Ubah Jadwal Reservasi">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Ubah Jadwal
                    </button>
                @endif
            @elseif($booking->status === 'checkin')
                <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm px-3 py-2 rounded-3 fw-bold text-dark shadow-sm" onclick="return confirm('Lakukan Check Out untuk sesi ini?')">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Selesaikan Sesi (Check Out)
                    </button>
                </form>
            @elseif($booking->status === 'selesai')
                <span class="badge bg-light text-dark border px-3 py-2 rounded-3 fw-semibold">
                    <i class="fa-solid fa-check-double text-success me-1"></i> Sesi Selesai (Sudah Check Out)
                </span>
                <a href="{{ route('admin.meeting-room.create') }}" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-plus me-1"></i> Reservasi Sesi Baru
                </a>
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
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-triangle-exclamation text-danger fs-5 me-2"></i>
                <div class="fw-semibold">{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 3-Stat Time Metrics Strip (Pemantauan Kuota) --}}
    @php
        $sisa = $booking->formatted_remaining_time ?? '0 menit';
        $isExhausted = ($booking->is_expired || $sisa === 'Waktu habis');
    @endphp
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
        <!-- Kolom Kiri: Informasi Pemesan & Paket Kuota -->
        <div class="col-lg-6 mb-4">
            {{-- Card Paket Kuota & Informasi Layanan --}}
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-solid fa-box-archive text-primary"></i> Rincian Paket Kuota &amp; Status Layanan
                    </h6>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold">
                        <i class="fa-solid fa-circle-check me-1"></i> Kuota Terdaftar
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
                                    <span class="badge bg-primary text-white px-2 py-0.5 rounded">Sedang Berjalan (Check-In)</span>
                                @else
                                    <span class="badge bg-success text-white px-2 py-0.5 rounded">Aktif &amp; Siap Digunakan</span>
                                @endif
                            </span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-calendar-plus text-muted"></i> Tanggal Registrasi</span>
                            <span class="kv-value">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }} WIB</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-calendar-xmark text-muted"></i> Masa Berlaku Kuota</span>
                            <span class="kv-value text-danger fw-bold">{{ \Carbon\Carbon::parse($booking->created_at)->addYear()->format('d M Y') }} (1 Tahun)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Informasi Pemesan & Perusahaan --}}
            <div class="detail-card">
                <div class="detail-card-header">
                    <h6 class="detail-card-title">
                        <i class="fa-solid fa-user-tie text-primary"></i> Informasi Pemesan &amp; Perusahaan
                    </h6>
                </div>

                <div class="detail-card-body">
                    <div class="kv-list">
                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-regular fa-user text-muted"></i> Nama Pemesan</span>
                            <span class="kv-value">{{ $booking->user ? $booking->user->name : ($booking->name ?? '-') }}</span>
                        </div>

                        <div class="kv-row">
                            <span class="kv-label"><i class="fa-brands fa-whatsapp text-muted"></i> No. WhatsApp</span>
                            <span class="kv-value">
                                @php
                                    $phone = $booking->phone ?? ($booking->user->phone ?? null);
                                @endphp
                                @if($phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" class="text-success text-decoration-none fw-bold">
                                        <i class="fa-brands fa-whatsapp me-1"></i> {{ $phone }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </span>
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
                            <span class="kv-label"><i class="fa-solid fa-clipboard-list text-muted"></i> Keperluan Meeting</span>
                            <span class="kv-value text-primary">{{ $booking->keperluan ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Jadwal Reservasi Sesi & Bukti Pembayaran -->
        <div class="col-lg-6 mb-4 d-flex flex-column gap-4">
            {{-- Card Jadwal Pengajuan & Ruangan --}}
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
                                    <span class="text-muted">Menyesuaikan Saat Check In</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    @if(!$booking->date)
                        <div class="info-callout-box">
                            <i class="fa-solid fa-circle-info"></i>
                            <div>
                                <strong>Tidak Ada Jadwal Sesi Aktif:</strong> Sesi sebelumnya telah selesai (Check Out). Client memiliki sisa kuota aktif <strong>{{ $booking->duration }} Jam</strong> yang dapat digunakan kembali. Client dapat mengajukan jadwal sesi baru dari halaman depan, atau Admin dapat langsung melakukan <strong>Check In</strong> sesi baru melalui tombol di atas.
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
                            <i class="fa-solid fa-check me-1"></i> Bebas Biaya (Benefit)
                        </span>
                    @elseif($proofPath)
                        <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold">
                            <i class="fa-solid fa-circle-check me-1"></i> Bukti Terunggah
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
                                <p class="small mb-0">Pemesanan ini dipotong langsung dari kuota benefit aktif client (tanpa pembayaran tunai).</p>
                            @else
                                <p class="small mb-0">Client belum mengunggah bukti transfer atau reservasi dibuat langsung oleh admin.</p>
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
                <i class="fa-solid fa-clock-rotate-left text-primary"></i> Riwayat Penggunaan Ruangan (Sesi Check In / Out)
            </h6>
            <span class="badge bg-light text-dark border px-2.5 py-1 fw-bold">{{ $logs->count() }} Entri Log</span>
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
                            <th>Durasi Pemakaian</th>
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
                                        <span class="badge bg-secondary text-white px-2.5 py-1 rounded"><i class="fa-solid fa-right-from-bracket me-1"></i> Check Out Selesai</span>
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

{{-- Modal Form Reservasi Check-In Meeting (for Detail page) --}}
@if(($booking->status === 'approved' || $booking->status === 'paused' || $booking->payment_status === 'approved') && $booking->status !== 'checkin' && $booking->status !== 'selesai' && $booking->status !== 'rejected')
@php
    $timeSlots = [];
    for($h = 0; $h <= 23; $h++) { $timeSlots[] = sprintf('%02d:00', $h); }
    $endTimeSlots = [];
    for($h = 1; $h <= 24; $h++) { $endTimeSlots[] = $h === 24 ? '24:00' : sprintf('%02d:00', $h); }
    $selStart = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:00') : '';
    $selEnd   = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:00') : '';
@endphp
<div class="modal fade" id="createSessionModalDetail" data-booking-id="{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <form action="{{ route('admin.meeting-room.create-session') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-calendar-plus me-2"></i> Form Reservasi Check-In Meeting Room
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body py-2 px-3 small">
                            <div class="row">
                                <div class="col-6"><strong>Client:</strong> {{ $booking->user->name ?? $booking->name }}</div>
                                <div class="col-6"><strong>Sisa Kuota:</strong> <span class="text-success fw-bold">{{ $booking->formatted_remaining_time }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Ruangan Meeting <span class="text-danger">*</span></label>
                        <select name="room_name" class="form-select" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $booking->id }}')">
                            <option value="Ruang Meetingroom Utama" {{ ($booking->room_name ?? '') === 'Ruang Meetingroom Utama' ? 'selected' : '' }}>Ruang Meetingroom Utama</option>
                            <option value="Ruang Meetingroom 1" {{ ($booking->room_name ?? '') === 'Ruang Meetingroom 1' ? 'selected' : '' }}>Ruang Meetingroom 1</option>
                            <option value="Ruang Meetingroom 2" {{ ($booking->room_name ?? '') === 'Ruang Meetingroom 2' ? 'selected' : '' }}>Ruang Meetingroom 2</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Tanggal Meeting <span class="text-danger">*</span></label>
                            <input type="date" name="date" class="form-control slot-date-input" value="{{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('Y-m-d') : date('Y-m-d') }}" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $booking->id }}')">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Jam Check-In / Mulai <span class="text-danger">*</span></label>
                            <select name="start_time" class="form-select slot-start-select" required onchange="handleStartTimeChange(this)">
                                <option value="" disabled {{ empty($selStart) ? 'selected' : '' }}>-- Pilih Jam Mulai --</option>
                                @foreach($timeSlots as $ts)
                                    <option value="{{ $ts }}" {{ $selStart === $ts ? 'selected' : '' }}>{{ $ts }} WIB</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Jam Check-Out <small class="text-muted fw-normal" style="font-size:0.72rem;">(Estimasi)</small></label>
                            <select name="end_time" class="form-select slot-end-select">
                                <option value="">-- Pilih Jam Selesai --</option>
                                @foreach($endTimeSlots as $ts)
                                    <option value="{{ $ts }}" {{ $selEnd === $ts ? 'selected' : '' }}>{{ $ts }} WIB</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jumlah Peserta (Opsional)</label>
                            <input type="number" name="participants" class="form-control" min="1" value="{{ $booking->participants ?? 1 }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Keperluan / Agenda (Opsional)</label>
                            <input type="text" name="keperluan" class="form-control" value="{{ $booking->keperluan ?? '' }}" placeholder="Misal: Rapat Koordinasi...">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan internal...">{{ $booking->notes ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fa-solid fa-check me-1"></i> Simpan Reservasi &amp; Aktifkan Check-In
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Check In Meeting Room (for Detail page) --}}
@php
    $ciStart = $booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->format('H:00') : '';
    $ciEnd   = $booking->end_time ? \Carbon\Carbon::parse($booking->end_time)->format('H:00') : ($booking->start_time ? \Carbon\Carbon::parse($booking->start_time)->addHour()->format('H:00') : '');
@endphp
<div class="modal fade" id="checkinModalDetail" data-booking-id="{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-start">
            <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkin') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fa-solid fa-door-open me-2"></i> Check In Meeting Room
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card bg-light border-0 mb-3">
                        <div class="card-body py-2 px-3 small">
                            <div class="row">
                                <div class="col-6"><strong>Client:</strong> {{ $booking->user->name ?? $booking->name }}</div>
                                <div class="col-6"><strong>Tanggal Order:</strong> {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Pilih Ruangan <span class="text-danger">*</span></label>
                        <select name="room_name" class="form-select" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $booking->id }}')">
                            @php
                                $mrRooms = ['Ruang Meetingroom 1', 'Ruang Meetingroom 2', 'Ruang Meetingroom Utama'];
                            @endphp
                            @foreach($mrRooms as $rm)
                                @php
                                    $isOccupied = \App\Models\MeetingRoomBooking::where('room_name', $rm)
                                        ->where('status', 'checkin')
                                        ->where('id', '!=', $booking->id)
                                        ->exists();
                                @endphp
                                <option value="{{ $rm }}" 
                                    {{ ($booking->room_name ?? '') === $rm ? 'selected' : '' }} 
                                    {{ $isOccupied ? 'disabled' : '' }}>
                                    {{ $rm }} {{ $isOccupied ? '(Sedang Dipakai / Check-In)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tanggal Meeting <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control slot-date-input" value="{{ $booking->date ? \Carbon\Carbon::parse($booking->date)->format('Y-m-d') : date('Y-m-d') }}" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $booking->id }}')">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                            <select name="start_time" class="form-select slot-start-select" required onchange="handleStartTimeChange(this)">
                                <option value="" disabled {{ empty($ciStart) ? 'selected' : '' }}>-- Pilih Jam Mulai --</option>
                                @foreach($timeSlots as $ts)
                                    <option value="{{ $ts }}" {{ $ciStart === $ts ? 'selected' : '' }}>{{ $ts }} WIB</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                            <select name="end_time" class="form-select slot-end-select" required>
                                <option value="" disabled {{ empty($ciEnd) ? 'selected' : '' }}>-- Pilih Jam Selesai --</option>
                                @foreach($endTimeSlots as $ts)
                                    <option value="{{ $ts }}" {{ $ciEnd === $ts ? 'selected' : '' }}>{{ $ts }} WIB</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i> WhatsApp notifikasi konfirmasi Check In akan otomatis dikirimkan ke client setelah disimpan.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success fw-bold">
                        <i class="fa-solid fa-paper-plane me-1"></i> Check In &amp; Kirim WhatsApp
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
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

    /* Dynamic Slot Availability Helpers */
    function refreshModalSlots(modalEl, endpointUrl, excludeId) {
        if (!modalEl) return;
        const dateInput = modalEl.querySelector('input[name="date"]');
        const roomSelect = modalEl.querySelector('select[name="room_name"]');
        const startSelect = modalEl.querySelector('.slot-start-select');
        const endSelect = modalEl.querySelector('.slot-end-select');

        if (!dateInput || !startSelect) return;
        const date = dateInput.value;
        const room = roomSelect ? roomSelect.value : '';

        if (!date) return;

        const effectiveExcludeId = excludeId || modalEl.getAttribute('data-booking-id') || '{{ $booking->id }}';

        fetch(`${endpointUrl}?date=${date}&room_name=${encodeURIComponent(room)}&exclude_id=${effectiveExcludeId}`)
            .then(r => r.json())
            .then(occupied => {
                modalEl._occupiedSlots = occupied;

                Array.from(startSelect.options).forEach(opt => {
                    if (!opt.value) return;
                    if (occupied.includes(opt.value)) {
                        opt.disabled = true;
                        opt.innerText = `${opt.value} (Sudah Terisi / Dibooking)`;
                        opt.style.color = '#dc2626';
                    } else {
                        opt.disabled = false;
                        opt.innerText = `${opt.value} WIB`;
                        opt.style.color = '';
                    }
                });

                updateEndTimeOptions(modalEl);
            })
            .catch(err => console.error('Error fetching booked slots:', err));
    }

    function updateEndTimeOptions(modalEl) {
        const startSelect = modalEl.querySelector('.slot-start-select');
        const endSelect = modalEl.querySelector('.slot-end-select');
        if (!startSelect || !endSelect) return;

        const occupied = modalEl._occupiedSlots || [];
        const startVal = startSelect.value;
        if (!startVal) {
            Array.from(endSelect.options).forEach(opt => {
                if (!opt.value) return;
                opt.disabled = false;
                opt.innerText = `${opt.value} WIB`;
                opt.style.color = '';
            });
            return;
        }

        const startH = parseInt(startVal.split(':')[0]);

        // Find the nearest occupied slot that begins after startH
        let nextOccupiedH = 25;
        for (let h = startH + 1; h <= 23; h++) {
            const slotStr = String(h).padStart(2, '0') + ':00';
            if (occupied.includes(slotStr)) {
                nextOccupiedH = h;
                break;
            }
        }

        Array.from(endSelect.options).forEach(opt => {
            if (!opt.value) return;
            const endH = opt.value === '24:00' ? 24 : parseInt(opt.value.split(':')[0]);

            if (endH <= startH) {
                opt.disabled = true;
                opt.innerText = `${opt.value} WIB`;
                opt.style.color = '#94a3b8';
            } else if (endH > nextOccupiedH) {
                opt.disabled = true;
                opt.innerText = `${opt.value} (Bentrok Jadwal)`;
                opt.style.color = '#dc2626';
            } else {
                opt.disabled = false;
                opt.innerText = `${opt.value} WIB`;
                opt.style.color = '';
            }
        });

        // Adjust end time only if current value is invalid
        const currentEndH = endSelect.value ? (endSelect.value === '24:00' ? 24 : parseInt(endSelect.value.split(':')[0])) : 0;
        if (!endSelect.value || currentEndH <= startH || currentEndH > nextOccupiedH) {
            const preferredEndH = Math.min(startH + 1, nextOccupiedH);
            const preferredVal = preferredEndH === 24 ? '24:00' : String(preferredEndH).padStart(2, '0') + ':00';
            endSelect.value = preferredVal;
        }
    }

    function handleStartTimeChange(selectEl) {
        const modalEl = selectEl.closest('.modal');
        if (!modalEl) return;
        updateEndTimeOptions(modalEl);
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', function() {
                const endpoint = "{{ url('admin/meeting-room/booked-slots') }}";
                const bookingId = modal.getAttribute('data-booking-id') || (modal.querySelector('input[name="booking_id"]') ? modal.querySelector('input[name="booking_id"]').value : '{{ $booking->id }}');
                refreshModalSlots(modal, endpoint, bookingId);
            });
        });
    });
</script>
@endpush
