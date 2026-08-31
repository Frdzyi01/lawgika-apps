@extends('layouts-admin.admin')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

<style>
/* ===== Enterprise Dashboard Styling ===== */
.admin-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 22px;
    flex-wrap: wrap;
    gap: 16px;
}
.admin-page-title {
    font-size: 1.35rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.admin-page-subtitle {
    font-size: 0.84rem;
    color: #64748b;
    margin: 4px 0 0 0;
}

/* KPI Summary Cards */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.kpi-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.02);
}
.kpi-info-label {
    font-size: 0.76rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.kpi-info-val {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1;
}
.kpi-icon-box {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
}
.kpi-warning { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }
.kpi-primary { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
.kpi-indigo  { background: #fdf2f8; color: #db2777; border: 1px solid #fce7f3; }

/* Table Container Card */
.table-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    margin-bottom: 26px;
    overflow: hidden;
}
.table-card-header {
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.table-card-header.pending-style {
    background: #fffdfb;
    border-bottom: 1px solid #fed7aa;
}
.table-card-title {
    font-size: 0.96rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.table-modern {
    width: 100%;
    margin-bottom: 0;
    vertical-align: middle;
    border-collapse: separate;
    border-spacing: 0;
}
.table-modern thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 0.74rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 12px 16px;
    border-bottom: 1px solid #e2e8f0;
    border-top: none;
    white-space: nowrap;
}
.table-modern tbody td {
    padding: 13px 16px;
    font-size: 0.86rem;
    color: #334155;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}
.table-modern tbody tr:last-child td {
    border-bottom: none;
}
.table-modern tbody tr:hover td {
    background: #f8fafc;
}

/* User & Schedule formatters */
.client-name-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.88rem;
    display: block;
}
.client-sub-detail {
    font-size: 0.76rem;
    color: #64748b;
}
.wa-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #16a34a;
    font-weight: 600;
    font-size: 0.78rem;
    text-decoration: none;
    transition: color 0.15s;
}
.wa-link-btn:hover {
    color: #15803d;
    text-decoration: underline;
}
.order-code-badge {
    font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
    font-size: 0.8rem;
    font-weight: 700;
    background: #f1f5f9;
    color: #1e293b;
    border: 1px solid #e2e8f0;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
}

/* Buttons in Table */
.btn-action-group {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 6px;
}
.btn-approve {
    background: #16a34a;
    color: #ffffff;
    border: none;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 5px 12px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: background 0.15s;
}
.btn-approve:hover {
    background: #15803d;
    color: #ffffff;
}
.btn-reject {
    background: #ffffff;
    color: #dc2626;
    border: 1px solid #fca5a5;
    font-size: 0.78rem;
    font-weight: 700;
    padding: 5px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s;
}
.btn-reject:hover {
    background: #fef2f2;
    border-color: #ef4444;
    color: #b91c1c;
}
.btn-table-detail {
    background: #ffffff;
    color: #475569;
    border: 1px solid #cbd5e1;
    font-size: 0.78rem;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-table-detail:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #94a3b8;
}

/* Empty State */
.empty-box-state {
    padding: 38px 20px;
    text-align: center;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
</style>

<div class="container-fluid py-2">
    {{-- Page Header --}}
    <div class="admin-page-header">
        <div>
            <h1 class="admin-page-title">
                <i class="fa-solid fa-people-roof text-primary"></i> Manajemen Meeting Room
            </h1>
            <p class="admin-page-subtitle">Kelola pengajuan reservasi masuk dari client, verifikasi kuota, dan pantau sesi penggunaan ruangan.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ url('admin/meeting-room/calendar') }}" class="btn btn-outline-primary btn-sm px-3 py-2 rounded-3 fw-bold" style="font-size:0.85rem;">
                <i class="fa-solid fa-calendar-days me-1"></i> Kalender Reservasi
            </a>
            <a href="{{ route('admin.meeting-room.create') }}" class="btn btn-primary btn-sm px-3 py-2 rounded-3 fw-bold shadow-sm" style="font-size:0.85rem;">
                <i class="fa-solid fa-plus me-1"></i> Tambah Reservasi Manual
            </a>
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

    {{-- Top Summary Stats (KPI) --}}
    @php
        $pendingCount = isset($pendingReservations) ? $pendingReservations->count() : 0;
        $activeCheckinCount = \App\Models\MeetingRoomBooking::where('status', 'checkin')->count();
        $totalBookingsCount = $bookings->total();
    @endphp
    <div class="kpi-grid">
        <div class="kpi-card">
            <div>
                <div class="kpi-info-label">Menunggu Approval</div>
                <div class="kpi-info-val {{ $pendingCount > 0 ? 'text-warning' : 'text-dark' }}">{{ $pendingCount }} <small class="text-muted fw-normal fs-6">Pengajuan</small></div>
            </div>
            <div class="kpi-icon-box kpi-warning">
                <i class="fa-solid fa-bell"></i>
            </div>
        </div>

        <div class="kpi-card">
            <div>
                <div class="kpi-info-label">Sedang Digunakan</div>
                <div class="kpi-info-val text-success">{{ $activeCheckinCount }} <small class="text-muted fw-normal fs-6">Sesi Aktif</small></div>
            </div>
            <div class="kpi-icon-box kpi-primary">
                <i class="fa-solid fa-door-open"></i>
            </div>
        </div>

        <div class="kpi-card">
            <div>
                <div class="kpi-info-label">Total Kuota / Reservasi</div>
                <div class="kpi-info-val text-dark">{{ $totalBookingsCount }} <small class="text-muted fw-normal fs-6">Terdaftar</small></div>
            </div>
            <div class="kpi-icon-box kpi-indigo">
                <i class="fa-solid fa-folder-open"></i>
            </div>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- TABEL 1: PERMINTAAN RESERVASI BARU (MENUNGGU APPROVAL ADMIN)             --}}
    {{-- ========================================================================= --}}
    <div class="table-card" style="{{ $pendingCount > 0 ? 'border-color: #fbd38d;' : '' }}">
        <div class="table-card-header {{ $pendingCount > 0 ? 'pending-style' : '' }}">
            <h6 class="table-card-title">
                <i class="fa-solid fa-inbox text-warning"></i>
                <span>1. Permintaan Reservasi Baru <small class="text-muted fw-normal">(Menunggu Approval Admin)</small></span>
            </h6>
            <div>
                @if($pendingCount > 0)
                    <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">
                        <i class="fa-solid fa-clock me-1"></i> {{ $pendingCount }} Pengajuan Masuk
                    </span>
                @else
                    <span class="badge bg-light text-muted border px-2.5 py-1 rounded-pill fw-normal" style="font-size:0.78rem;">
                        <i class="fa-solid fa-check text-success me-1"></i> Tidak ada antrean pending
                    </span>
                @endif
            </div>
        </div>

        <div class="p-0">
            @if($pendingCount > 0)
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th class="ps-4">No. Order</th>
                                <th>Client / Pemesan</th>
                                <th>Tipe Order</th>
                                <th>Jadwal Diajukan</th>
                                <th>Durasi &amp; Peserta</th>
                                <th>Biaya / Tagihan</th>
                                <th class="pe-4 text-end">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingReservations as $p)
                            <tr>
                                <td class="ps-4">
                                    <span class="order-code-badge">
                                        {{ $p->order_number ?? ('#MR-' . str_pad($p->id, 5, '0', STR_PAD_LEFT)) }}
                                    </span>
                                    <div class="client-sub-detail mt-1">
                                        {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y H:i') }} WIB
                                    </div>
                                </td>
                                <td>
                                    <span class="client-name-title">{{ $p->name ?? ($p->user ? $p->user->name : '-') }}</span>
                                    @if($p->nama_perusahaan || ($p->user && $p->user->company_name))
                                        <div class="client-sub-detail"><i class="fa-regular fa-building me-1"></i> {{ $p->nama_perusahaan ?? $p->user->company_name }}</div>
                                    @endif
                                    @php
                                        $phone = $p->phone ?? ($p->user->phone ?? null);
                                    @endphp
                                    @if($phone)
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" class="wa-link-btn mt-0.5">
                                            <i class="fa-brands fa-whatsapp"></i> {{ $phone }}
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($p->source_type === 'benefit')
                                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded fw-semibold" style="font-size:0.78rem;">
                                            <i class="fa-solid fa-gift me-1"></i> Kuota Benefit PT
                                        </span>
                                        @if($p->benefit)
                                            <div class="client-sub-detail mt-1">{{ $p->benefit->paket }}</div>
                                        @endif
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded fw-semibold" style="font-size:0.78rem;">
                                            <i class="fa-solid fa-credit-card me-1"></i> Bayar Mandiri / Paket
                                        </span>
                                    @endif
                                    @if($p->keperluan)
                                        <div class="client-sub-detail mt-1" style="max-width:190px;">
                                            <em>"{{ Str::limit($p->keperluan, 40) }}"</em>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($p->date)
                                        <div>
                                            <span class="fw-bold text-dark"><i class="fa-regular fa-calendar text-primary me-1"></i> {{ \Carbon\Carbon::parse($p->date)->translatedFormat('d M Y') }}</span>
                                            <div class="client-sub-detail text-primary fw-semibold"><i class="fa-regular fa-clock me-1"></i> {{ substr($p->start_time, 0, 5) }} WIB</div>
                                        </div>
                                    @else
                                        <span class="badge bg-light text-muted border px-2 py-0.5">Belum Memilih Jadwal</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $p->duration }} Jam</div>
                                    <div class="client-sub-detail">{{ $p->participants ?? 1 }} Orang</div>
                                </td>
                                <td>
                                    @if($p->source_type === 'benefit' && (!$p->total_price || $p->total_price == 0))
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded" style="font-size:0.76rem;">
                                            <i class="fa-solid fa-check me-1"></i> Bebas Biaya (Benefit)
                                        </span>
                                    @else
                                        @if($p->total_price > 0)
                                            <div class="fw-bold text-primary">Rp {{ number_format($p->total_price, 0, ',', '.') }}</div>
                                        @endif
                                        @php
                                            $proof = $p->payment_proof ?? null;
                                        @endphp
                                        @if($proof)
                                            <a href="{{ asset('storage/' . $proof) }}" target="_blank" class="badge bg-light text-primary border px-2 py-0.5 text-decoration-none mt-1 d-inline-block">
                                                <i class="fa-solid fa-image me-1"></i> Bukti Bayar
                                            </a>
                                        @endif
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="btn-action-group">
                                        @if($p->source_type === 'benefit')
                                            {{-- Approve Benefit --}}
                                            <form action="{{ url('admin/meeting-room/' . $p->id . '/benefit-approve') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-approve" onclick="return confirm('Setujui pengajuan reservasi benefit ini?')">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            {{-- Reject Benefit --}}
                                            <form action="{{ url('admin/meeting-room/' . $p->id . '/benefit-reject') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-reject" onclick="return confirm('Tolak pengajuan reservasi ini?')">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        @else
                                            {{-- Approve Manual Payment --}}
                                            <form action="{{ url('admin/meeting-room/' . $p->id . '/approve-payment') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-approve" onclick="return confirm('Setujui pembayaran & reservasi ini? Kuota akan otomatis aktif.')">
                                                    <i class="fa-solid fa-check"></i> Setujui
                                                </button>
                                            </form>
                                            {{-- Reject Manual Payment --}}
                                            <form action="{{ url('admin/meeting-room/' . $p->id . '/reject-payment') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-reject" onclick="return confirm('Tolak pembayaran ini?')">
                                                    <i class="fa-solid fa-xmark"></i> Tolak
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ url('admin/meeting-room/' . $p->id . '/detail') }}" class="btn-table-detail" title="Lihat Detail">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-box-state">
                    <i class="fa-solid fa-circle-check text-success fs-3 mb-2 d-block opacity-75"></i>
                    <h6 class="fw-bold text-dark mb-1" style="font-size:0.92rem;">Tidak Ada Pengajuan Reservasi yang Menunggu Approval</h6>
                    <p class="text-muted small mb-0">Reservasi baru yang diajukan oleh client melalui form front-end akan otomatis tampil di sini.</p>
                </div>
            @endif
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- TABEL 2: SEMUA RESERVASI & KUOTA TERDAFTAR (ACTIVE / RIWAYAT)             --}}
    {{-- ========================================================================= --}}
    <div class="table-card">
        <div class="table-card-header">
            <h6 class="table-card-title">
                <i class="fa-solid fa-list-check text-primary"></i>
                <span>2. Semua Reservasi &amp; Kuota Terdaftar</span>
            </h6>
            
            <div class="d-flex align-items-center gap-2">
                <form action="{{ url()->current() }}" method="GET" class="d-flex">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm border-start-0 ps-0" placeholder="Cari client, order, ruangan..." value="{{ request('search') }}" style="min-width:210px;">
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary" title="Reset"><i class="fa-solid fa-xmark"></i></a>
                        @endif
                    </div>
                </form>
                <span class="badge bg-light text-dark border px-2.5 py-1.5 rounded-pill fw-semibold" style="font-size:0.78rem;">
                    {{ $bookings->total() }} Total
                </span>
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-modern">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Order</th>
                            <th>Client / Kontak</th>
                            <th>Ruangan</th>
                            <th>Jadwal / Status Kuota</th>
                            <th>Peserta</th>
                            <th>Pemakaian Waktu</th>
                            <th>Pembayaran</th>
                            <th>Status Ruangan</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td class="ps-4">
                                <span class="order-code-badge">
                                    {{ $b->order_number ?? ('#MR-' . str_pad($b->id, 5, '0', STR_PAD_LEFT)) }}
                                </span>
                            </td>
                            <td>
                                <span class="client-name-title">{{ $b->name }}</span>
                                @if($b->user)<div class="client-sub-detail">{{ $b->user->email }}</div>@endif
                            </td>
                            <td>
                                @if(!empty($b->start_time) && !empty($b->room_name))
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-semibold">
                                        <i class="fa-solid fa-door-open text-primary me-1"></i> {{ $b->room_name }}
                                    </span>
                                    @if($b->keperluan)
                                        <div class="client-sub-detail mt-0.5">"{{ $b->keperluan }}"</div>
                                    @endif
                                @else
                                    <span class="text-muted">–</span>
                                @endif
                            </td>
                            <td>
                                @if (!empty($b->start_time))
                                    <div>
                                        <span class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($b->date ?: now())->translatedFormat('d M Y') }}</span>
                                        <div class="client-sub-detail text-primary fw-bold">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }} WIB</div>
                                    </div>
                                @elseif ($b->source_type === 'benefit')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-0.5 rounded" style="font-size:0.76rem;">
                                        <i class="fa-solid fa-gift me-1"></i> Benefit Kuota PT
                                    </span>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-0.5 rounded" style="font-size:0.76rem;">
                                        <i class="fa-solid fa-calendar-xmark me-1 text-secondary"></i> Belum Reservasi
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if (empty($b->start_time))
                                    <span class="text-muted">–</span>
                                @else
                                    <span class="badge bg-light text-dark border">{{ $b->participants ?? 1 }} Orang</span>
                                @endif
                            </td>
                            <td>
                                <div class="client-sub-detail">Total: <strong class="text-dark">{{ $b->formatSeconds($b->duration * 3600) }}</strong></div>
                                <div class="client-sub-detail">Dipakai: <span class="used-time-display text-primary fw-bold"
                                        data-status="{{ $b->status }}"
                                        data-used="{{ $b->used_seconds }}">{{ $b->formatted_used_time ?? '0 menit' }}</span></div>
                                @php
                                    $sisa = $b->formatted_remaining_time ?? '0 menit';
                                    $bc = ($b->is_expired || $sisa === 'Waktu habis') ? 'bg-danger' : 'bg-success';
                                @endphp
                                <span class="badge {{ $bc }} mt-1" style="font-size:0.72rem;">Sisa: <span class="remaining-time-display" data-status="{{ $b->status }}" data-remaining="{{ $b->remaining_seconds }}">{{ $sisa }}</span></span>
                            </td>
                            <td>
                                @if($b->source_type === 'benefit')
                                    <span class="badge bg-light text-dark border">Paket PT</span>
                                    @if($b->benefit)
                                        <div class="client-sub-detail mt-0.5">{{ $b->benefit->paket }}</div>
                                    @endif
                                @else
                                    <span class="badge bg-light text-secondary border">Manual</span>
                                    @if($b->payment_method)
                                        <div class="client-sub-detail fw-semibold mt-0.5">{{ $b->payment_method }}</div>
                                    @endif
                                @endif
                                @if($b->total_price > 0)
                                    <div class="client-sub-detail text-primary fw-bold mt-0.5">Rp {{ number_format($b->total_price, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td>
                                @if($b->is_expired)
                                    <span class="badge bg-danger text-white px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-ban me-1"></i> Expired</span>
                                @elseif($b->source_type === 'manual' && $b->remaining_seconds <= 0 && $b->status !== 'selesai' && $b->payment_status === 'approved')
                                    <span class="badge bg-danger text-white px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-hourglass-end me-1"></i> Habis</span>
                                @elseif($b->status === 'checkin')
                                    <span class="badge bg-primary text-white px-2 py-1 rounded shadow-sm d-inline-flex align-items-center gap-1" style="font-size:0.75rem;"><span class="spinner-grow spinner-grow-sm text-white" style="width:0.5rem; height:0.5rem;"></span> Digunakan (<span class="row-live-timer" data-checkin="{{ $b->checkin_at ? \Carbon\Carbon::parse($b->checkin_at)->timestamp : now()->timestamp }}">00:00</span>)</span>
                                @elseif($b->status === 'paused')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-pause me-1"></i> Berhenti</span>
                                @elseif($b->status === 'selesai')
                                    <span class="badge bg-dark text-white px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-check-double me-1"></i> Selesai</span>
                                @elseif(empty($b->start_time) && ($b->status === 'approved' || $b->payment_status === 'approved'))
                                    <span class="badge bg-info text-dark px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-clock me-1"></i> Paket Kuota Aktif</span>
                                @elseif(!empty($b->start_time) && ($b->status === 'approved' || $b->payment_status === 'approved'))
                                    <span class="badge bg-success text-white px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-circle-check me-1"></i> Siap Check In</span>
                                @elseif($b->status === 'rejected')
                                    <span class="badge bg-danger text-white px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-xmark me-1"></i> Ditolak</span>
                                @elseif($b->status === 'pending')
                                    <span class="badge bg-warning text-dark px-2 py-1 rounded" style="font-size:0.75rem;"><i class="fa-solid fa-clock me-1"></i> Pending</span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <div class="btn-action-group">
                                    <a href="{{ url('admin/meeting-room/'.$b->id.'/detail') }}" class="btn-table-detail">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>

                                    {{-- 1. Tombol Reservasi Check In (muncul jika belum ada jam reservasi aktif) --}}
                                    @if(empty($b->start_time) && $b->remaining_seconds > 0 && ($b->status === 'approved' || $b->payment_status === 'approved'))
                                        <button type="button" class="btn btn-sm btn-primary text-white px-2.5 py-1 fw-bold rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createSessionModalMR{{ $b->id }}" style="font-size:0.78rem;">
                                            <i class="fa-solid fa-calendar-plus me-1"></i> Reservasi Check In
                                        </button>
                                    @endif

                                    <!-- Modal Form Reservasi Check-In Meeting -->
                                    <div class="modal fade" id="createSessionModalMR{{ $b->id }}" data-booking-id="{{ $b->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content text-start">
                                                <form action="{{ route('admin.meeting-room.create-session') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="booking_id" value="{{ $b->id }}">
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
                                                                    <div class="col-6"><strong>Client:</strong> {{ $b->user->name ?? $b->name }}</div>
                                                                    <div class="col-6"><strong>Sisa Kuota:</strong> <span class="text-success fw-bold">{{ $b->formatted_remaining_time }}</span></div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Pilih Ruangan Meeting <span class="text-danger">*</span></label>
                                                            <select name="room_name" class="form-select" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $b->id }}')">
                                                                <option value="Ruang Meetingroom Utama" {{ ($b->room_name ?? '') === 'Ruang Meetingroom Utama' ? 'selected' : '' }}>Ruang Meetingroom Utama</option>
                                                                <option value="Ruang Meetingroom 1" {{ ($b->room_name ?? '') === 'Ruang Meetingroom 1' ? 'selected' : '' }}>Ruang Meetingroom 1</option>
                                                                <option value="Ruang Meetingroom 2" {{ ($b->room_name ?? '') === 'Ruang Meetingroom 2' ? 'selected' : '' }}>Ruang Meetingroom 2</option>
                                                            </select>
                                                        </div>

                                                        @php
                                                            $timeSlots = [];
                                                            for($h = 0; $h <= 23; $h++) { $timeSlots[] = sprintf('%02d:00', $h); }
                                                            $endTimeSlots = [];
                                                            for($h = 1; $h <= 24; $h++) { $endTimeSlots[] = $h === 24 ? '24:00' : sprintf('%02d:00', $h); }
                                                            $selStart = $b->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:00') : '';
                                                            $selEnd   = $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:00') : '';
                                                        @endphp
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label fw-bold">Tanggal Meeting <span class="text-danger">*</span></label>
                                                                <input type="date" name="date" class="form-control slot-date-input" value="{{ $b->date ? \Carbon\Carbon::parse($b->date)->format('Y-m-d') : date('Y-m-d') }}" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $b->id }}')">
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
                                                                <input type="number" name="participants" class="form-control" min="1" value="{{ $b->participants ?? 1 }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-bold">Keperluan / Agenda (Opsional)</label>
                                                                <input type="text" name="keperluan" class="form-control" value="{{ $b->keperluan ?? '' }}" placeholder="Misal: Rapat Koordinasi...">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Catatan (Opsional)</label>
                                                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan internal...">{{ $b->notes ?? '' }}</textarea>
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

                                    {{-- 2. Tombol Check In (muncul setelah reservasi check in disimpan) --}}
                                    @if(!empty($b->start_time) && ($b->status === 'approved' || $b->status === 'paused' || $b->payment_status === 'approved') && $b->status !== 'checkin' && $b->status !== 'selesai' && $b->status !== 'rejected' && $b->status !== 'pending')
                                        <button type="button" class="btn btn-sm btn-success px-2.5 py-1 fw-bold rounded-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#checkinModalMR{{ $b->id }}" style="font-size:0.78rem;">
                                            <i class="fa-solid fa-door-open me-1"></i> Check In
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary px-2 py-1 rounded-2" data-bs-toggle="modal" data-bs-target="#createSessionModalMR{{ $b->id }}" title="Ubah Jadwal Reservasi" style="font-size:0.75rem;">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <!-- Modal Check In Meeting Room -->
                                        <div class="modal fade" id="checkinModalMR{{ $b->id }}" data-booking-id="{{ $b->id }}" tabindex="-1" aria-labelledby="checkinModalLabelMR{{ $b->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-start">
                                                    <form action="{{ url('admin/meeting-room/'.$b->id.'/checkin') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="booking_id" value="{{ $b->id }}">
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title fw-bold" id="checkinModalLabelMR{{ $b->id }}">
                                                                <i class="fa-solid fa-door-open me-2"></i> Check In Meeting Room
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Info Client & Tanggal Booking -->
                                                            <div class="card bg-light border-0 mb-3">
                                                                <div class="card-body py-2 px-3 small">
                                                                    <div class="row">
                                                                        <div class="col-6"><strong>Client:</strong> {{ $b->user->name ?? $b->name }}</div>
                                                                        <div class="col-6"><strong>Tanggal Booking:</strong> {{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d M Y') : ($b->date ? \Carbon\Carbon::parse($b->date)->format('d M Y') : date('d M Y')) }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- 1. Pilih Ruangan -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Pilih Ruangan <span class="text-danger">*</span></label>
                                                                <select name="room_name" class="form-select" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $b->id }}')">
                                                                    @php
                                                                        $mrRooms = ['Ruang Meetingroom 1', 'Ruang Meetingroom 2', 'Ruang Meetingroom Utama'];
                                                                    @endphp
                                                                    @foreach($mrRooms as $rm)
                                                                        @php
                                                                            $isOccupiedByOther = \App\Models\MeetingRoomBooking::where('room_name', $rm)
                                                                                ->where('status', 'checkin')
                                                                                ->where('id', '!=', $b->id)
                                                                                ->exists();
                                                                        @endphp
                                                                        <option value="{{ $rm }}" 
                                                                            {{ ($b->room_name ?? '') === $rm ? 'selected' : '' }} 
                                                                            {{ $isOccupiedByOther ? 'disabled' : '' }}>
                                                                            {{ $rm }} {{ $isOccupiedByOther ? '(Sedang Dipakai / Check-In)' : '' }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <!-- 2. Tanggal Meeting -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Tanggal Meeting <span class="text-danger">*</span></label>
                                                                <input type="date" name="date" class="form-control slot-date-input" value="{{ $b->date ? \Carbon\Carbon::parse($b->date)->format('Y-m-d') : date('Y-m-d') }}" required onchange="refreshModalSlots(this.closest('.modal'), '{{ url('admin/meeting-room/booked-slots') }}', '{{ $b->id }}')">
                                                            </div>

                                                            @php
                                                                $ciStart = $b->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:00') : '';
                                                                $ciEnd   = $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:00') : ($b->start_time ? \Carbon\Carbon::parse($b->start_time)->addHour()->format('H:00') : '');
                                                            @endphp
                                                            <!-- 3. Waktu Pemakaian (Mulai & Selesai) -->
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
                                                                <i class="fa-solid fa-paper-plane me-1"></i> Check In & Kirim WhatsApp
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($b->status === 'checkin')
                                        <form action="{{ url('admin/meeting-room/'.$b->id.'/checkout') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-warning text-dark px-2.5 py-1 fw-bold rounded-2" onclick="return confirm('Lakukan Check Out untuk sesi ruangan ini?')" style="font-size:0.78rem;">
                                                <i class="fa-solid fa-right-from-bracket me-1"></i> Check Out
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Belum ada data reservasi meeting room.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-3 border-top d-flex justify-content-end">
                {{ $bookings->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function formatLiveBadgeTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            if (h > 0) {
                return h + "j " + (m < 10 ? '0' : '') + m + "m " + (s < 10 ? '0' : '') + s + "d";
            }
            return (m < 10 ? '0' : '') + m + ":" + (s < 10 ? '0' : '') + s;
        }

        function tickRowTimers() {
            const nowTs = Math.floor(Date.now() / 1000);
            document.querySelectorAll('.row-live-timer').forEach(el => {
                const cTs = parseInt(el.dataset.checkin);
                if (cTs) {
                    const elapsed = Math.max(0, nowTs - cTs);
                    el.innerText = formatLiveBadgeTime(elapsed);
                }
            });
        }
        setInterval(tickRowTimers, 1000);
        tickRowTimers();

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

            const effectiveExcludeId = excludeId || modalEl.getAttribute('data-booking-id') || '';

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
                    const bookingId = modal.getAttribute('data-booking-id') || (modal.querySelector('input[name="booking_id"]') ? modal.querySelector('input[name="booking_id"]').value : '');
                    refreshModalSlots(modal, endpoint, bookingId);
                });
            });
        });
    </script>
@endpush
