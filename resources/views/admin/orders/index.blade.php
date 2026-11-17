@extends('layouts-admin.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
    <div>
        <h4 class="fw-bold mb-1">Manajemen Pesanan &amp; Transaksi</h4>
        <p class="text-muted small mb-0">Daftar transaksi layanan, status approval Admin 3 / SPV, dan revisi pesanan.</p>
    </div>

    @if(auth()->user()->isAdmin1() || auth()->user()->isSPV() || auth()->user()->isAdmin3())
    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary px-3 py-2 fw-semibold shadow-sm text-nowrap d-inline-flex align-items-center gap-2" style="border-radius: 8px; font-size: .875rem;">
        <i class="fa-solid fa-circle-plus"></i>
        <span>Buat Pesanan Baru</span>
    </a>
    @endif
</div>

{{-- Filter & Pencarian --}}
<div class="card border-0 shadow-sm mb-3" style="border-radius: 10px;">
    <div class="card-body py-2 px-3">
        <form method="GET" class="row g-2 align-items-center m-0">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="col-12 col-lg-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control border-start-0 ps-0"
                        placeholder="Cari No. Order, Invoice, atau Nama Client...">
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(['pending_approval' => 'Pending Approval', 'approved' => 'Approved', 'rejected' => 'Ditolak', 'pending' => 'Pending (Legacy)', 'processing' => 'Diproses', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'] as $sVal => $sLbl)
                        <option value="{{ $sVal }}" {{ request('status') == $sVal ? 'selected' : '' }}>{{ $sLbl }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-6 col-md-5 col-lg-3">
                <select name="service" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">Semua Layanan</option>
                    @foreach($services as $sKey => $sInfo)
                        <option value="{{ $sKey }}" {{ request('service') == $sKey ? 'selected' : '' }}>{{ $sInfo['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 col-lg-1 d-flex gap-1">
                <button type="submit" class="btn btn-sm btn-dark w-100 fw-semibold">Filter</button>
                @if(request()->filled('search') || request()->filled('status') || request()->filled('service'))
                <a href="{{ route('admin.orders.index', ['tab' => $tab]) }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Workflow Tabs --}}
<ul class="nav nav-pills mb-3 gap-2 flex-wrap" style="font-size: .85rem;">
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'all' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'all']) }}">
            Semua <span class="badge bg-secondary ms-1">{{ $counts['all'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'pending_approval' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'pending_approval']) }}">
            Pending Approval <span class="badge bg-warning text-dark ms-1">{{ $counts['pending_approval'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'rejected' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'rejected']) }}">
            Ditolak / Perlu Revisi <span class="badge bg-danger ms-1">{{ $counts['rejected'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'approved' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'approved']) }}">
            Approved <span class="badge bg-success ms-1">{{ $counts['approved'] }}</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'change_requests' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'change_requests']) }}">
            Pengajuan Edit/Hapus <span class="badge bg-info text-dark ms-1">{{ $counts['change_requests'] }}</span>
        </a>
    </li>
    @if(auth()->user()->canApproveOrders())
    <li class="nav-item">
        <a class="nav-link {{ $tab === 'trashed' ? 'active' : 'bg-white text-dark border' }}" href="{{ route('admin.orders.index', ['tab' => 'trashed']) }}">
            Terhapus <span class="badge bg-dark ms-1">{{ $counts['trashed'] }}</span>
        </a>
    </li>
    @endif
</ul>

@foreach(['success', 'error'] as $msg)
    @if(session($msg))
    <div class="alert alert-{{ $msg === 'success' ? 'success' : 'danger' }} alert-dismissible fade show" role="alert">
        {{ session($msg) }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
@endforeach

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($tab === 'change_requests')
{{-- TAB PENGAJUAN EDIT / HAPUS --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Pesanan / Invoice</th>
                        <th>Jenis</th>
                        <th>Pemohon</th>
                        <th>Alasan &amp; Usulan Perubahan</th>
                        <th>Status</th>
                        <th>Reviewer</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($changeRequests as $cr)
                    <tr>
                        <td class="ps-4">
                            @if($cr->order)
                                <a href="{{ route('admin.orders.show', $cr->order->id) }}" class="fw-semibold text-primary">
                                    {{ $cr->order->order_number }}
                                </a><br>
                                <small class="text-muted">INV: {{ $cr->order->invoice_number ?? '-' }}</small>
                            @else
                                <span class="text-muted">Order #{{ $cr->order_id }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $cr->request_type === 'delete' ? 'danger' : 'info text-dark' }}">
                                {{ $cr->request_type === 'delete' ? 'Pengajuan Hapus' : 'Pengajuan Edit' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $cr->requester->name ?? '-' }}</div>
                            <small class="text-muted">{{ $cr->created_at->format('d M Y H:i') }}</small>
                        </td>
                        <td style="max-width: 320px;">
                            <div class="small fw-semibold">{{ $cr->reason }}</div>
                            @if(!empty($cr->proposed_data))
                                <div class="text-muted small mt-1">
                                    @foreach($cr->proposed_data as $pKey => $pVal)
                                        <span class="badge bg-light text-dark border me-1">{{ $pKey }}: {{ is_numeric($pVal) && $pKey === 'total_price' ? 'Rp ' . number_format($pVal, 0, ',', '.') : $pVal }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $cr->status === 'approved' ? 'success' : ($cr->status === 'rejected' ? 'danger' : 'warning text-dark') }}">
                                {{ ucfirst($cr->status) }}
                            </span>
                        </td>
                        <td>
                            @if($cr->reviewer)
                                <small>{{ $cr->reviewer->name }}</small><br>
                                <small class="text-muted">{{ $cr->reviewed_at?->format('d M Y H:i') }}</small>
                                @if($cr->review_note)<br><small class="text-muted fst-italic">"{{ $cr->review_note }}"</small>@endif
                            @else
                                <span class="text-muted small">Menunggu Admin 3 / SPV</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($cr->isPending() && auth()->user()->canApproveOrders())
                            <div class="d-flex justify-content-center gap-1">
                                <form action="{{ route('admin.change-requests.approve', $cr->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui dan jalankan pengajuan {{ $cr->request_type }} ini?')">Setujui</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rejectCrModal{{ $cr->id }}">Tolak</button>
                            </div>

                            <div class="modal fade" id="rejectCrModal{{ $cr->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.change-requests.reject', $cr->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tolak Pengajuan #{{ $cr->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label fw-semibold">Catatan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="review_note" class="form-control" rows="3" required minlength="3"></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @elseif($cr->order)
                                <a href="{{ route('admin.orders.show', $cr->order->id) }}" class="btn btn-sm btn-outline-primary">Lihat Order</a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada pengajuan perubahan dari Admin 1.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    {{ $changeRequests->appends(request()->query())->links() }}
</div>
@else
{{-- TAB DAFTAR PESANAN --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Order &amp; Invoice</th>
                        <th>Customer</th>
                        <th>Layanan &amp; Paket</th>
                        <th>Tgl Transaksi / Benefit</th>
                        <th>Status &amp; Pembayaran</th>
                        <th>Kuota Ruangan</th>
                        <th>Total</th>
                        <th class="text-center" style="min-width: 230px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php
                        $pkgSlug  = $order->form_data['package'] ?? '';
                        $pkgLabel = \App\Http\Controllers\UniversalOrderController::$packages[$pkgSlug] ?? ucfirst($pkgSlug) ?: '—';
                        $statusBadge = App\Models\Order::STATUS_MAP[$order->status]['color'] ?? 'secondary';
                        $statusLabel = App\Models\Order::STATUS_MAP[$order->status]['label'] ?? ucfirst($order->status);
                        $podcastB = $order->roomBenefits->where('type', 'podcast')->first();
                        $meetingB = $order->roomBenefits->where('type', 'meeting')->first();
                        $firstBenefit = $order->roomBenefits->first();
                    @endphp
                    <tr>
                        <td class="ps-4">
                            <div class="fw-semibold text-primary small">{{ $order->order_number }}</div>
                            <small class="text-dark fw-semibold">INV: {{ $order->invoice_number ?? '—' }}</small>
                            @if(($order->revision_count ?? 0) > 0)
                                <div><span class="badge bg-info text-dark">Revisi #{{ $order->revision_count }}</span></div>
                            @endif
                            @if(!$order->trashed() && $order->pendingChangeRequests->count() > 0)
                                <div><span class="badge bg-warning text-dark">Ada Pengajuan Edit/Hapus</span></div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name ?? '—' }}</div>
                            <small class="text-muted">{{ $order->user->company_name ?? $order->user->email ?? '' }}</small>
                        </td>
                        <td>
                            <div>{{ $order->service_name ?? ($order->service->name ?? '—') }}</div>
                            <span class="badge bg-light text-dark border">{{ $pkgLabel }}</span>
                            @if($order->effective_duration_months > 12 || $order->bonus_months > 0)
                                <span class="badge bg-info bg-opacity-10 text-primary border border-primary border-opacity-25">
                                    {{ $order->benefit_years }} Thn{{ $order->bonus_months > 0 ? ' + ' . $order->bonus_months . ' Bln Bonus' : '' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <strong>Tgl Transaksi:</strong> {{ $order->effective_transaction_date->format('d M Y') }}
                            </div>
                            @if($order->isValidated())
                                <div class="small text-muted">
                                    Mulai: {{ ($firstBenefit?->effective_start_date ?? $order->benefit_start_date)->format('d M Y') }}<br>
                                    Exp: {{ ($firstBenefit?->expired_at ?? $order->benefit_end_date)->format('d M Y') }}
                                    @if(($firstBenefit?->expired_at ?? $order->benefit_end_date)->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @endif
                                </div>
                                @if($order->approved_at)
                                    <div class="small text-success">Approved: {{ $order->approved_at->format('d M Y H:i') }}</div>
                                @endif
                            @elseif($order->needsTransactionApproval())
                                <small class="text-warning fw-semibold">Benefit belum aktif (Pending Approval)</small>
                            @elseif($order->isRejected())
                                <small class="text-danger fw-semibold">Ditolak {{ $order->rejected_at?->format('d M Y') }}</small>
                            @endif
                        </td>
                        <td>
                            @if($order->trashed())
                                <span class="badge bg-dark">Dihapus</span>
                                <div class="small text-muted">{{ $order->deleted_at->format('d M Y H:i') }}</div>
                                <div class="small text-danger">{{ $order->delete_reason }}</div>
                            @else
                                <span class="badge bg-{{ $statusBadge }} px-2 py-1 fw-semibold" style="color: {{ in_array($statusBadge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
                                    {{ $statusLabel }}
                                </span>
                                <div class="mt-1">
                                    <span class="badge bg-{{ $order->payment_status_color }} bg-opacity-75">{{ $order->payment_status_label }}</span>
                                    @if($order->payment_method)
                                        <small class="text-muted d-block">{{ $order->payment_method }}</small>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($order->isValidated())
                                <div class="small">
                                    M: @if($meetingB && $meetingB->is_active) <strong class="{{ $meetingB->is_expired ? 'text-danger' : 'text-success' }}">{{ round($meetingB->remaining_minutes / 60, 1) }} Jam</strong> @else – @endif
                                </div>
                                <div class="small">
                                    P: @if($podcastB && $podcastB->is_active) <strong class="{{ $podcastB->is_expired ? 'text-danger' : 'text-success' }}">{{ round($podcastB->remaining_minutes / 60, 1) }} Jam</strong> @else – @endif
                                </div>
                            @else
                                <span class="text-muted small">Belum Aktif</span>
                            @endif
                        </td>
                        <td>
                            @if($order->total_price > 0)
                                @php $ppnData = \App\Helpers\PpnHelper::calculate($order->total_price); @endphp
                                <span class="fw-semibold">Rp {{ number_format($ppnData['grand_total'], 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php
                                $hasVoOrBenefit = $order->isVirtualOffice() || $order->effective_benefit_expiry !== null;
                                $daysUntilExpiry = $order->days_until_expiry;
                                $isRenewable = $order->isRenewable();
                                $voCompany = $order->user->company_name ?? ($order->form_data['company_name'] ?? ($order->user->name ?? '—'));
                                $voPic = $order->user->pic_name ?? ($order->form_data['pic_name'] ?? ($order->user->name ?? '—'));
                                $voPhone = $order->user->phone ?? ($order->form_data['pic_phone'] ?? '—');
                            @endphp
                            <div class="d-flex flex-wrap align-items-center justify-content-center gap-1">
                                @if(!$order->trashed() && $hasVoOrBenefit)
                                    <button type="button"
                                        class="btn btn-sm btn-info text-white btn-mail-notification px-2 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                                        style="font-size: .76rem; border-radius: 6px;"
                                        data-id="{{ $order->id }}"
                                        data-company="{{ $voCompany }}"
                                        data-pic="{{ $voPic }}"
                                        data-phone="{{ $voPhone }}"
                                        data-url="{{ route('admin.virtual-office.mail-notification.store', $order->id) }}">
                                        <i class="fa-solid fa-envelope"></i>
                                        <span>Surat Masuk</span>
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-warning text-dark btn-guest-notification px-2 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                                        style="font-size: .76rem; border-radius: 6px;"
                                        data-id="{{ $order->id }}"
                                        data-company="{{ $voCompany }}"
                                        data-pic="{{ $voPic }}"
                                        data-phone="{{ $voPhone }}"
                                        data-url="{{ route('admin.virtual-office.guest-notification.store', $order->id) }}">
                                        <i class="fa-solid fa-user-group"></i>
                                        <span>Tamu Datang</span>
                                    </button>

                                    @if($isRenewable)
                                        <a href="{{ route('admin.orders.create', ['service' => $order->form_data['service'] ?? 'virtual-office', 'renew_order_id' => $order->id]) }}"
                                           class="btn btn-sm btn-success px-2 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                                           style="font-size: .76rem; border-radius: 6px;"
                                           title="Perpanjang Layanan (H-30 / Expired)">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            <span>Renew</span>
                                        </a>
                                    @else
                                        @php
                                            $renewDisabledTitle = !$order->isValidated()
                                                ? 'Renew hanya tersedia setelah pesanan disetujui (Approved)'
                                                : 'Renew hanya dapat digunakan jika masa berlaku H-30 atau sudah Expired (Saat ini sisa ' . max(0, (int) $daysUntilExpiry) . ' hari)';
                                        @endphp
                                        <button type="button"
                                                class="btn btn-sm btn-secondary opacity-50 px-2 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                                                style="font-size: .76rem; border-radius: 6px;"
                                                disabled
                                                title="{{ $renewDisabledTitle }}">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                            <span>Renew</span>
                                        </button>
                                    @endif
                                @endif

                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary px-2 py-1 text-nowrap d-inline-flex align-items-center gap-1"
                                   style="font-size: .76rem; border-radius: 6px;">
                                    <i class="fa-solid fa-eye"></i>
                                    <span>Detail</span>
                                </a>

                                @if(!$order->trashed())
                                    @if($order->isRejected())
                                        <button type="button" class="btn btn-sm btn-outline-danger px-2" data-bs-toggle="modal" data-bs-target="#viewRejectReasonModal{{ $order->id }}">
                                            Lihat Alasan Penolakan
                                        </button>
                                        @if(auth()->user()->isAdmin1() || auth()->user()->isSPV())
                                            <a href="{{ route('admin.orders.show', $order->id) }}#revisionSection" class="btn btn-sm btn-warning px-2 fw-semibold">
                                                Revisi Pesanan
                                            </a>
                                        @endif
                                    @endif

                                    @if($order->needsTransactionApproval() && auth()->user()->canApproveOrders())
                                        <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success px-2" onclick="return confirm('Approve pesanan {{ $order->invoice_number ?? $order->order_number }}? Benefit akan diaktifkan sesuai tanggal transaksi {{ $order->effective_transaction_date->format('d M Y') }}.')">
                                                Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger px-2" data-bs-toggle="modal" data-bs-target="#rejectOrderModal{{ $order->id }}">
                                            Reject
                                        </button>
                                    @endif

                                    @if(auth()->user()->canDirectlyModifyOrders())
                                        <button type="button" class="btn btn-sm btn-outline-danger px-2" data-bs-toggle="modal" data-bs-target="#deleteOrderModal{{ $order->id }}">Hapus</button>
                                    @endif

                                    @if($order->qr_token)
                                        <a href="{{ url('/qr/' . $order->qr_token) }}" target="_blank" class="btn btn-sm btn-outline-dark px-2">QR</a>
                                    @endif
                                @endif
                            </div>

                            {{-- Modal Lihat Alasan Penolakan --}}
                            @if($order->isRejected() || $order->rejection_reason)
                            <div class="modal fade" id="viewRejectReasonModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title">Alasan Penolakan — {{ $order->invoice_number ?? $order->order_number }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-2"><strong>Ditolak oleh:</strong> {{ $order->rejector->name ?? 'Admin 3 / SPV' }} ({{ $order->rejected_at?->format('d M Y H:i') ?? '-' }})</p>
                                            <div class="p-3 bg-light border rounded text-dark">{{ $order->rejection_reason ?? '-' }}</div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            @if($order->isRejected() && (auth()->user()->isAdmin1() || auth()->user()->isSPV()))
                                            <a href="{{ route('admin.orders.show', $order->id) }}#revisionSection" class="btn btn-warning fw-semibold">Revisi Pesanan Sekarang</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Modal Reject Order (Admin 3 / SPV) --}}
                            @if(!$order->trashed() && auth()->user()->canApproveOrders())
                            <div class="modal fade" id="rejectOrderModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Tolak Transaksi — {{ $order->invoice_number ?? $order->order_number }}</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="rejection_reason" class="form-control" rows="4" required minlength="3" placeholder="Jelaskan alasan penolakan transaksi agar Admin 1 dapat merevisi..."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Delete Order (Admin 3 / SPV) --}}
                            <div class="modal fade" id="deleteOrderModal{{ $order->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content text-start">
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Hapus Pesanan</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="small">Pesanan <strong>{{ $order->order_number }}</strong> (Invoice: <code>{{ $order->invoice_number }}</code>) akan di-soft-delete dan benefit terkait dinonaktifkan. Riwayat pesanan tetap tersimpan.</p>
                                                <label class="form-label fw-semibold">Alasan Penghapusan</label>
                                                <textarea name="delete_reason" class="form-control" rows="2" placeholder="Alasan penghapusan transaksi..."></textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Hapus Pesanan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Belum ada pesanan pada kategori ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $orders->appends(request()->query())->links() }}
</div>

@include('admin.partials.vo-notification-modals')
@endif
@endsection
