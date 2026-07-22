@extends('layouts-admin.admin')

@section('title', 'Profil Client - ' . ($user->company_name ?: $user->name))

@section('content')
<!--start breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">Master Client</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ $user->company_name ?: $user->name }}
                </li>
            </ol>
        </nav>
    </div>
    @if(auth()->user()->canManageClients())
    <div class="ms-auto">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
            <ion-icon name="pencil-outline"></ion-icon> Edit Client
        </a>
    </div>
    @endif
</div>
<!--end breadcrumb-->

{{-- ── Client Info Card ──────────────────────────────────────────────────── --}}
<div class="row">
    <div class="col-lg-4">
        <div class="card radius-10">
            <div class="card-body text-center">
                <div class="d-flex justify-content-center align-items-center rounded-circle bg-primary bg-gradient mx-auto mb-3" style="width:80px;height:80px;">
                    <ion-icon name="business-outline" style="font-size:36px;color:#fff"></ion-icon>
                </div>
                <h5 class="mb-1">{{ $user->company_name ?: $user->name }}</h5>
                @if($user->company_name)
                <p class="text-muted mb-1">{{ $user->name }}</p>
                @endif
                @php
                    $badgeClass = match($user->role) {
                        'admin', 'spv' => 'bg-gradient-purple',
                        'admin1'       => 'bg-gradient-info',
                        'admin2'       => 'bg-gradient-success',
                        default        => 'bg-secondary',
                    };
                @endphp
                <span class="badge {{ $badgeClass }} text-white mb-3">{{ $user->roleLabel() }}</span>
                
                <div class="text-start">
                    <hr>
                    <div class="mb-2">
                        <small class="text-muted">PIC / Contact Person</small>
                        <p class="mb-0 fw-bold">{{ $user->pic_name ?: $user->name }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Telepon / WA</small>
                        <p class="mb-0">{{ $user->phone ?: '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Alamat</small>
                        <p class="mb-0">{{ $user->address ?: '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Bidang Usaha</small>
                        <p class="mb-0">{{ $user->business_type ?: '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">NPWP</small>
                        <p class="mb-0">{{ $user->npwp ?: '-' }}</p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Terdaftar</small>
                        <p class="mb-0">{{ $user->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($user->notes)
                    <div class="mb-2">
                        <small class="text-muted">Catatan Internal</small>
                        <p class="mb-0 text-info">{{ $user->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Quota Summary ──────────────────────────────────────────────── --}}
        @php
            $mq = $user->meeting_quota_summary;
            $pq = $user->podcast_quota_summary;
        @endphp
        <div class="card radius-10">
            <div class="card-header py-2">
                <h6 class="mb-0"><ion-icon name="time-outline" class="align-middle"></ion-icon> Quota Ruangan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong class="d-block mb-1">📋 Meeting Room</strong>
                    <div class="d-flex justify-content-between small">
                        <span>Total: {{ round($mq['total'] / 60) }} jam</span>
                        <span>Dipakai: {{ round($mq['used'] / 60) }} jam</span>
                        <span class="text-success fw-bold">Sisa: {{ round($mq['remaining'] / 60) }} jam</span>
                    </div>
                    @if($mq['total'] > 0)
                    <div class="progress mt-1" style="height:6px;">
                        <div class="progress-bar bg-primary" style="width: {{ $mq['total'] > 0 ? round(($mq['used'] / $mq['total']) * 100) : 0 }}%"></div>
                    </div>
                    @endif
                </div>
                <div>
                    <strong class="d-block mb-1">🎙️ Podcast Room</strong>
                    <div class="d-flex justify-content-between small">
                        <span>Total: {{ round($pq['total'] / 60) }} jam</span>
                        <span>Dipakai: {{ round($pq['used'] / 60) }} jam</span>
                        <span class="text-success fw-bold">Sisa: {{ round($pq['remaining'] / 60) }} jam</span>
                    </div>
                    @if($pq['total'] > 0)
                    <div class="progress mt-1" style="height:6px;">
                        <div class="progress-bar bg-info" style="width: {{ $pq['total'] > 0 ? round(($pq['used'] / $pq['total']) * 100) : 0 }}%"></div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        {{-- ── Tabs ──────────────────────────────────────────────────────── --}}
        <div class="card radius-10">
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tab-orders" role="tab">
                            <ion-icon name="cart-outline"></ion-icon> Pesanan
                            <span class="badge bg-primary ms-1">{{ $user->orders->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-meeting" role="tab">
                            <ion-icon name="business-outline"></ion-icon> Meeting
                            <span class="badge bg-info ms-1">{{ $user->meetingRoomBookings->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-podcast" role="tab">
                            <ion-icon name="mic-outline"></ion-icon> Podcast
                            <span class="badge bg-success ms-1">{{ $user->podcastRoomBookings->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-surat" role="tab">
                            <ion-icon name="mail-outline"></ion-icon> Surat
                            <span class="badge bg-warning ms-1">{{ $user->correspondences->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#tab-benefit" role="tab">
                            <ion-icon name="gift-outline"></ion-icon> Benefit
                            <span class="badge bg-danger ms-1">{{ $user->roomBenefits->count() }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    {{-- ── Tab: Pesanan ──────────────────────────────────────── --}}
                    <div class="tab-pane fade show active" id="tab-orders" role="tabpanel">
                        @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order #</th>
                                        <th>Layanan</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->sortByDesc('created_at') as $order)
                                    <tr>
                                        <td><a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary fw-bold">{{ $order->order_number }}</a></td>
                                        <td>{{ $order->service_name ?: ($order->service->name ?? '-') }}</td>
                                        <td>
                                            @php
                                                $statusBadge = match($order->status) {
                                                    'approved' => 'bg-success',
                                                    'processing' => 'bg-info',
                                                    'completed' => 'bg-primary',
                                                    'rejected' => 'bg-danger',
                                                    'cancelled' => 'bg-secondary',
                                                    default => 'bg-warning',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusBadge }}">{{ ucfirst($order->status) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $payBadge = match($order->payment_status) {
                                                    'verified' => 'bg-success',
                                                    'pending_verification' => 'bg-warning',
                                                    'rejected' => 'bg-danger',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $payBadge }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}</span>
                                        </td>
                                        <td><small>{{ $order->created_at->format('d M Y') }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <ion-icon name="cart-outline" style="font-size:40px"></ion-icon>
                            <p class="mt-2">Belum ada pesanan</p>
                        </div>
                        @endif
                    </div>

                    {{-- ── Tab: Meeting Room ─────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-meeting" role="tabpanel">
                        @if($user->meetingRoomBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Durasi</th>
                                        <th>Peserta</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->meetingRoomBookings->sortByDesc('date') as $mb)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($mb->date)->format('d M Y') }}</td>
                                        <td>{{ $mb->start_time }}</td>
                                        <td>{{ $mb->duration }} jam</td>
                                        <td>{{ $mb->participants }} orang</td>
                                        <td>
                                            @php
                                                $mStatusBadge = match($mb->status) {
                                                    'checkin' => 'bg-info',
                                                    'checkout', 'selesai' => 'bg-success',
                                                    default => 'bg-warning',
                                                };
                                            @endphp
                                            <span class="badge {{ $mStatusBadge }}">{{ ucfirst($mb->status) }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <ion-icon name="business-outline" style="font-size:40px"></ion-icon>
                            <p class="mt-2">Belum ada reservasi meeting room</p>
                        </div>
                        @endif
                    </div>

                    {{-- ── Tab: Podcast Room ──────────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-podcast" role="tabpanel">
                        @if($user->podcastRoomBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Paket</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->podcastRoomBookings->sortByDesc('date') as $pb)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($pb->date)->format('d M Y') }}</td>
                                        <td>{{ $pb->start_time }}</td>
                                        <td>{{ ucfirst($pb->package) }}</td>
                                        <td>
                                            @php
                                                $pStatusBadge = match($pb->status) {
                                                    'checkin' => 'bg-info',
                                                    'selesai' => 'bg-success',
                                                    default => 'bg-warning',
                                                };
                                            @endphp
                                            <span class="badge {{ $pStatusBadge }}">{{ ucfirst($pb->status) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $pb->payment_status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                                {{ ucfirst($pb->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <ion-icon name="mic-outline" style="font-size:40px"></ion-icon>
                            <p class="mt-2">Belum ada reservasi podcast room</p>
                        </div>
                        @endif
                    </div>

                    {{-- ── Tab: Surat Menyurat ───────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-surat" role="tabpanel">
                        @if($user->correspondences->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Judul</th>
                                        <th>Status</th>
                                        <th>Balasan</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->correspondences as $corr)
                                    <tr>
                                        <td>{{ $corr->title }}</td>
                                        <td>
                                            @php
                                                $corrBadge = match($corr->status) {
                                                    'replied' => 'bg-success',
                                                    'done' => 'bg-primary',
                                                    default => 'bg-warning',
                                                };
                                            @endphp
                                            <span class="badge {{ $corrBadge }}">{{ ucfirst($corr->status) }}</span>
                                        </td>
                                        <td>{{ $corr->replies->count() }} balasan</td>
                                        <td><small>{{ $corr->created_at->format('d M Y') }}</small></td>
                                        <td>
                                            <a href="{{ route('admin.surat-menyurat.show', $corr->id) }}" class="btn btn-sm btn-outline-primary">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <ion-icon name="mail-outline" style="font-size:40px"></ion-icon>
                            <p class="mt-2">Belum ada surat menyurat</p>
                        </div>
                        @endif
                    </div>

                    {{-- ── Tab: Benefit Ruangan ───────────────────────────────── --}}
                    <div class="tab-pane fade" id="tab-benefit" role="tabpanel">
                        @if($user->roomBenefits->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order</th>
                                        <th>Tipe</th>
                                        <th>Total</th>
                                        <th>Terpakai</th>
                                        <th>Sisa</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->roomBenefits as $benefit)
                                    <tr>
                                        <td>{{ $benefit->order->order_number ?? '-' }}</td>
                                        <td>{{ ucfirst($benefit->type) }}</td>
                                        <td>{{ \App\Models\RoomBenefit::formatMinutes($benefit->total_minutes) }}</td>
                                        <td>{{ \App\Models\RoomBenefit::formatMinutes($benefit->used_minutes) }}</td>
                                        <td class="text-success fw-bold">{{ \App\Models\RoomBenefit::formatMinutes($benefit->remaining_minutes) }}</td>
                                        <td>
                                            <span class="badge {{ $benefit->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $benefit->is_active ? 'Aktif' : 'Expired' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.benefits.detail', $benefit->id) }}" class="btn btn-sm btn-outline-info">
                                                <ion-icon name="list-outline"></ion-icon> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center text-muted py-4">
                            <ion-icon name="gift-outline" style="font-size:40px"></ion-icon>
                            <p class="mt-2">Belum ada benefit ruangan</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
