@extends('layouts-admin.admin')

@section('content')
@if(auth()->user()->isAdmin2())
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
{{-- ── DASHBOARD ADMIN KONTEN (ADMIN 2) ──────────────────────────────────── --}}
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><ion-icon name="color-palette-outline" class="align-middle"></ion-icon> Dashboard Manajemen Konten</h4>
        <p class="text-muted">Pusat pengawasan dan pengelolaan konten website Lawgika (Promo, Event UpComing, Peraturan KBLI, Berita &amp; Artikel).</p>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Top Stat Cards --}}
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Promo</p>
                        <h4 class="my-1 text-primary">{{ $totalPromo }}</h4>
                        <a href="{{ route('admin.promo.index') }}" class="small text-primary text-decoration-none fw-semibold">Kelola Promo &rarr;</a>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <ion-icon name="pricetag-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Event UpComing</p>
                        <h4 class="my-1 text-warning">{{ $totalEvents }}</h4>
                        <a href="{{ route('admin.event-upcoming.index') }}" class="small text-warning text-decoration-none fw-semibold">Kelola Event &rarr;</a>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Peraturan KBLI</p>
                        <h4 class="my-1 text-success">{{ $totalKbli }}</h4>
                        <a href="{{ route('admin.peraturan-kbli.index') }}" class="small text-success text-decoration-none fw-semibold">Kelola KBLI &rarr;</a>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                        <ion-icon name="library-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Berita &amp; Artikel</p>
                        <h4 class="my-1 text-danger">{{ $totalBerita }}</h4>
                        <a href="{{ route('admin.berita.index') }}" class="small text-danger text-decoration-none fw-semibold">Kelola Berita &rarr;</a>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                        <ion-icon name="newspaper-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Left Column: Tables --}}
    <div class="col-12 col-lg-8">
        {{-- Table Berita & Artikel Terbaru --}}
        <div class="card radius-10 mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><ion-icon name="newspaper-outline" class="align-middle"></ion-icon> Berita &amp; Artikel Terbaru (5 Terakhir)</h6>
                <a href="{{ route('admin.berita.create') }}" class="btn btn-sm btn-primary">
                    <ion-icon name="add-circle-outline" class="align-middle"></ion-icon> Tambah Berita
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Artikel</th>
                                <th>Kategori</th>
                                <th>Penulis</th>
                                <th>Tanggal Publish</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBeritas as $berita)
                            <tr>
                                <td>
                                    <span class="fw-semibold text-dark">{{ Str::limit($berita->judul, 40) }}</span>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $berita->kategori ?? 'Umum' }}</span></td>
                                <td><small class="text-muted">{{ $berita->penulis ?? 'Admin' }}</small></td>
                                <td><small class="text-muted">{{ $berita->published_at ? $berita->published_at->format('d M Y') : $berita->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-sm btn-outline-primary">
                                        <ion-icon name="pencil-outline"></ion-icon> Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada artikel berita.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Table Promo Terbaru --}}
        <div class="card radius-10">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><ion-icon name="pricetag-outline" class="align-middle"></ion-icon> Promo Terbaru (5 Terakhir)</h6>
                <a href="{{ route('admin.promo.create') }}" class="btn btn-sm btn-warning">
                    <ion-icon name="add-circle-outline" class="align-middle"></ion-icon> Tambah Promo
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Judul Promo</th>
                                <th>Diskon</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPromos as $promo)
                            <tr>
                                <td><span class="fw-semibold text-dark">{{ $promo->judul }}</span></td>
                                <td><span class="badge bg-success">{{ $promo->tipe_diskon === 'persen' ? $promo->diskon . '%' : 'Rp ' . number_format($promo->diskon, 0, ',', '.') }}</span></td>
                                <td><small class="text-muted">{{ $promo->tanggal_mulai ? $promo->tanggal_mulai->format('d M') : '-' }} s/d {{ $promo->tanggal_berakhir ? $promo->tanggal_berakhir->format('d M Y') : '-' }}</small></td>
                                <td>
                                    @if($promo->status)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Non-Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.promo.edit', $promo->id) }}" class="btn btn-sm btn-outline-warning">
                                        <ion-icon name="pencil-outline"></ion-icon> Edit
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">Belum ada promo aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Column: Quick Links & Actions --}}
    <div class="col-12 col-lg-4">
        {{-- Card Akses Modul Konten --}}
        <div class="card radius-10 mb-4">
            <div class="card-header py-3">
                <h6 class="mb-0"><ion-icon name="grid-outline" class="align-middle"></ion-icon> Akses Modul Konten</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ route('admin.promo.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <ion-icon name="pricetag-outline" class="align-middle me-2 text-primary fs-5"></ion-icon>
                            <strong>Promo</strong>
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $totalPromo }}</span>
                    </a>
                    <a href="{{ route('admin.event-upcoming.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <ion-icon name="calendar-outline" class="align-middle me-2 text-warning fs-5"></ion-icon>
                            <strong>Event UpComing</strong>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $totalEvents }}</span>
                    </a>
                    <a href="{{ route('admin.peraturan-kbli.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <ion-icon name="library-outline" class="align-middle me-2 text-success fs-5"></ion-icon>
                            <strong>Peraturan KBLI</strong>
                        </div>
                        <span class="badge bg-success rounded-pill">{{ $totalKbli }}</span>
                    </a>
                    <a href="{{ route('admin.berita.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                        <div>
                            <ion-icon name="newspaper-outline" class="align-middle me-2 text-danger fs-5"></ion-icon>
                            <strong>Berita &amp; Artikel</strong>
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $totalBerita }}</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Card Quick Action --}}
        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0"><ion-icon name="flash-outline" class="align-middle"></ion-icon> Action Cepat Konten</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">
                        <ion-icon name="newspaper-outline" class="align-middle me-1"></ion-icon> + Tambah Berita Baru
                    </a>
                    <a href="{{ route('admin.promo.create') }}" class="btn btn-warning text-dark">
                        <ion-icon name="pricetag-outline" class="align-middle me-1"></ion-icon> + Tambah Promo Baru
                    </a>
                    <a href="{{ route('admin.event-upcoming.create') }}" class="btn btn-outline-secondary">
                        <ion-icon name="calendar-outline" class="align-middle me-1"></ion-icon> + Tambah Event Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@else
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
{{-- ── DASHBOARD OPERASIONAL CRM (SPV & ADMIN 1) ──────────────────────────── --}}
{{-- ═════════════════════════════════════════════════════════════════════════ --}}
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><ion-icon name="speedometer-outline" class="align-middle"></ion-icon> Dashboard CRM Lawgika</h4>
        <p class="text-muted">Ringkasan operasional harian dan manajemen client.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Top Cards --}}
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Master Client</p>
                        <h4 class="my-1 text-info">{{ $totalCustomers }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Order Pending</p>
                        <h4 class="my-1 text-warning">{{ $pendingOrders }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Surat Masuk (Unread)</p>
                        <h4 class="my-1 text-danger">{{ $pendingSurat }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                        <ion-icon name="mail-unread-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Aktif / Sedang Check-in</p>
                        <h4 class="my-1 text-success">{{ $activeCheckins }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                        <ion-icon name="log-in-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0">Pesanan Masuk Terbaru (5 Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No Order</th>
                                <th>Client</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="fw-semibold text-primary small">{{ $order->order_number }}</span></td>
                                <td>
                                    {{ $order->user->name ?? '—' }}<br>
                                    <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $order->service_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan terbaru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0">Tindakan Menunggu (Pending)</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ url('admin/meeting-room') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="business-outline" class="align-middle me-1 text-primary"></ion-icon> Meeting Room
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $pendingMeetings }}</span>
                    </a>
                    <a href="{{ url('admin/podcast-room') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="mic-outline" class="align-middle me-1 text-info"></ion-icon> Podcast Room
                        </div>
                        <span class="badge bg-info text-dark rounded-pill">{{ $pendingPodcasts }}</span>
                    </a>
                    <a href="{{ route('admin.surat-menyurat.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="mail-unread-outline" class="align-middle me-1 text-danger"></ion-icon> Surat Menyurat
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $pendingSurat }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'waiting_verification']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="document-text-outline" class="align-middle me-1 text-warning"></ion-icon> Verifikasi Order
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $pendingOrders }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
