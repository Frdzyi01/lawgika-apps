@extends('layouts-customer.app')

@section('title', __('customer.dashboard.title'))

@section('content')
{{-- Success Flash --}}
@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 alert-dismissible fade show mb-4" role="alert">
    <ion-icon name="checkmark-circle-outline" style="font-size:1.4rem;"></ion-icon>
    <span>{{ session('success') }}</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Welcome Banner --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0" style="background:linear-gradient(135deg,#4e0616 0%,#b91c1c 100%); border-radius:16px;">
            <div class="card-body p-4 text-white">
                <h5 class="fw-bold mb-1">{{ __('customer.dashboard.welcome', ['name' => auth()->user()->name]) }}</h5>
                <p class="mb-3 opacity-75 small">{{ __('customer.dashboard.welcome_desc') }}</p>
                <a href="{{ url('/') }}" class="btn btn-light btn-sm fw-semibold">
                    <ion-icon name="add-circle-outline" class="me-1"></ion-icon>
                    {{ __('customer.dashboard.order_new') }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Active Room Benefits Banner (Meeting & Podcast Room) --}}
@if(isset($roomBenefits) && $roomBenefits->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($roomBenefits as $rb)
                @php
                    $isPodcast = $rb->type === 'podcast';
                    $themeColor = $isPodcast ? '#4e0516' : '#2563eb';
                    $btnRoute   = $isPodcast ? url('/sewa-ruang-podcast?book=true') : url('/sewa-meeting-room?book=true');
                    $indexRoute = $isPodcast ? route('customer.podcast-room.index') : route('customer.meeting-room.index');
                    $pct        = $rb->total_minutes > 0 ? ($rb->used_minutes / $rb->total_minutes) * 100 : 0;
                    $icon       = $isPodcast ? 'mic-outline' : 'business-outline';
                    $isCurrentlyActive = ($isPodcast && $activePodcast) || (!$isPodcast && $activeMeeting);
                    $activeSession     = $isPodcast ? $activePodcast : $activeMeeting;
                    $detailUrl         = $activeSession ? ($isPodcast ? route('customer.podcast-room.detail', $activeSession->id) : route('customer.meeting-room.detail', $activeSession->id)) : null;

                    $isBenefitFromPT = !empty($rb->order_id) || ($isPodcast ? ($rb->total_minutes == 12 * 60) : ($rb->total_minutes == 48 * 60));
                    if ($isPodcast) {
                        $badgeText = $isBenefitFromPT ? 'Paket Benefit Studio Podcast' : 'Paket Podcast Room (20 Jam)';
                    } else {
                        $badgeText = $isBenefitFromPT ? 'Paket Benefit Meeting Room' : 'Paket Meeting Room (60 Jam)';
                    }
                @endphp
                <div class="card border-0 shadow-sm mb-3" style="border-radius:16px; border-left: 5px solid {{ $themeColor }} !important;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <div>
                                <span class="badge {{ $isPodcast ? 'bg-danger' : 'bg-primary' }} text-white rounded-pill px-2.5 py-1 mb-1" style="font-size:0.72rem;">
                                    {{ $badgeText }}
                                </span>
                                @if($isCurrentlyActive)
                                    <span class="badge bg-danger text-white rounded-pill px-2 py-0.5 ms-1 shadow-sm" style="font-size:0.7rem;">
                                        <i class="fa-solid fa-circle-dot text-white me-1"></i> Sedang Digunakan
                                    </span>
                                @endif
                                <h5 class="fw-bold mb-0 text-dark">
                                    <ion-icon name="{{ $icon }}" class="me-1 align-middle" style="color:{{ $themeColor }};"></ion-icon> {{ $rb->paket }}
                                </h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                @if($rb->expired_at)
                                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2" style="font-size:0.78rem;">
                                        {{ __('customer.dashboard.valid_until') }} {{ \Carbon\Carbon::parse($rb->expired_at)->format('d M Y') }}
                                    </span>
                                @endif

                                @if($isCurrentlyActive && $activeSession)
                                    <a href="{{ $detailUrl }}" class="btn btn-sm btn-danger px-3.5 py-2 fw-bold rounded-pill shadow-sm d-flex align-items-center gap-2" style="background:#4e0516; border-color:#4e0516;">
                                        <span class="spinner-grow spinner-grow-sm text-white" role="status" style="width:0.65rem; height:0.65rem;"></span>
                                        <span>Waktu Pemakaian: <span class="active-session-timer" data-checkin-time="{{ $activeSession->checkin_at ? \Carbon\Carbon::parse($activeSession->checkin_at)->timestamp : now()->timestamp }}">0 jam 0 menit 0 detik</span></span>
                                    </a>
                                @else
                                    @if($rb->remaining_minutes <= 0)
                                        <button type="button" class="btn btn-sm text-white px-3 py-2 fw-bold rounded-pill shadow-sm" style="background:{{ $themeColor }}; border-color:{{ $themeColor }};" onclick="showNoQuotaAlert('{{ $isPodcast ? 'podcast' : 'meeting' }}')">
                                            <ion-icon name="calendar-outline" class="me-1 align-middle"></ion-icon> Ajukan Reservasi
                                        </button>
                                    @else
                                        <a href="{{ $btnRoute }}" class="btn btn-sm text-white px-3 py-2 fw-bold rounded-pill shadow-sm" style="background:{{ $themeColor }}; border-color:{{ $themeColor }};">
                                            <ion-icon name="calendar-outline" class="me-1 align-middle"></ion-icon> Ajukan Reservasi
                                        </a>
                                    @endif
                                @endif

                                <a href="{{ $indexRoute }}" class="btn btn-sm btn-outline-secondary px-3 py-2 rounded-pill">
                                    Riwayat
                                </a>
                            </div>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-sm-4">
                                <small class="text-muted d-block">{{ __('customer.dashboard.remaining_time') }}</small>
                                @if($isCurrentlyActive && $activeSession)
                                    <strong class="fs-5 text-success active-remaining-timer"
                                        data-total-quota="{{ $rb->total_minutes * 60 }}"
                                        data-base-used="{{ $rb->used_minutes * 60 }}"
                                        data-checkin-time="{{ $activeSession->checkin_at ? \Carbon\Carbon::parse($activeSession->checkin_at)->timestamp : now()->timestamp }}">
                                        {{ \App\Models\RoomBenefit::formatMinutes($rb->remaining_minutes) }}
                                    </strong>
                                @else
                                    <strong class="fs-5 text-success">{{ \App\Models\RoomBenefit::formatMinutes($rb->remaining_minutes) }}</strong>
                                @endif
                            </div>
                            <div class="col-sm-4">
                                <small class="text-muted d-block">{{ __('customer.dashboard.used_time') }}</small>
                                @if($isCurrentlyActive && $activeSession)
                                    <strong class="fs-5 text-primary active-used-timer"
                                        data-base-used="{{ $rb->used_minutes * 60 }}"
                                        data-checkin-time="{{ $activeSession->checkin_at ? \Carbon\Carbon::parse($activeSession->checkin_at)->timestamp : now()->timestamp }}">
                                        {{ \App\Models\RoomBenefit::formatMinutes($rb->used_minutes) }}
                                    </strong>
                                @else
                                    <strong class="fs-5 text-primary">{{ \App\Models\RoomBenefit::formatMinutes($rb->used_minutes) }}</strong>
                                @endif
                            </div>
                            <div class="col-sm-4 text-sm-end">
                                <small class="text-muted d-block">{{ __('customer.dashboard.total_quota') }}</small>
                                <strong class="fs-5 text-dark">{{ \App\Models\RoomBenefit::formatMinutes($rb->total_minutes) }}</strong>
                            </div>
                        </div>

                        <div class="progress" style="height: 10px; border-radius: 6px; background-color: #f1f5f9;">
                            <div class="progress-bar" role="progressbar" style="background-color: {{ $themeColor }}; width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- Legacy Shared Quota Banner (if exists) --}}
@if(isset($quota) && (!isset($roomBenefits) || $roomBenefits->count() == 0))
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px; border-left: 5px solid #be185d !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0" style="color:#be185d;"><ion-icon name="time-outline" class="me-2 align-middle"></ion-icon> {{ __('customer.dashboard.quota_shared') }}</h5>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2" style="color: white !important;">{{ __('customer.dashboard.valid_until') }} {{ \Carbon\Carbon::parse($quota->expired_at)->format('d M Y') }}</span>
                </div>

                <div class="mb-2 d-flex justify-content-between align-items-end">
                    <div>
                        <small class="text-muted d-block mb-1">{{ __('customer.dashboard.remaining_time') }}</small>
                        <strong class="fs-4 text-dark">{{ $quota->formatted_remaining_time }}</strong>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block mb-1">{{ __('customer.dashboard.total_quota') }}</small>
                        <strong class="text-dark">{{ $quota->formatted_total_time }}</strong>
                    </div>
                </div>

                @php
                $percent = $quota->total_seconds > 0 ? ($quota->used_seconds / $quota->total_seconds) * 100 : 0;
                @endphp
                <div class="progress" style="height: 12px; border-radius: 6px; background-color: #f1f5f9; overflow: hidden;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="background-color: #be185d; width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="mt-2 text-muted small">{{ __('customer.dashboard.used_time') }} <strong>{{ $quota->formatted_used_time }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-warning mb-1"><ion-icon name="time-outline" style="font-size:1.8rem;"></ion-icon></div>
            <div class="fw-bold fs-4">{{ $stats['pending'] }}</div>
            <small class="text-muted">{{ __('customer.dashboard.status.pending') }}</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-success mb-1"><ion-icon name="checkmark-circle-outline" style="font-size:1.8rem;"></ion-icon></div>
            <div class="fw-bold fs-4">{{ $stats['approved'] }}</div>
            <small class="text-muted">{{ __('customer.dashboard.status.approved') }}</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-primary mb-1"><ion-icon name="cart-outline" style="font-size:1.8rem;"></ion-icon></div>
            <div class="fw-bold fs-4">{{ $stats['total'] }}</div>
            <small class="text-muted">{{ __('customer.dashboard.status.total') }}</small>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="text-danger mb-1"><ion-icon name="close-circle-outline" style="font-size:1.8rem;"></ion-icon></div>
            <div class="fw-bold fs-4">{{ $stats['rejected'] }}</div>
            <small class="text-muted">{{ __('customer.dashboard.status.rejected') }}</small>
        </div>
    </div>
</div>

{{-- Recent Orders Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-bold mb-0">{{ __('customer.dashboard.recent_orders') }}</h6>
        <a href="{{ route('customer.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('customer.dashboard.view_all') }}</a>
    </div>
    <div class="card-body p-0">
        @if($orders->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">{{ __('customer.dashboard.order_no') }}</th>
                        <th>{{ __('customer.dashboard.service') }}</th>
                        <th>{{ __('customer.dashboard.status_label') }}</th>
                        <th>{{ __('customer.dashboard.date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td class="ps-4"><small class="text-primary fw-semibold">{{ $order->order_number }}</small></td>
                        <td>{{ $order->service_name ?? ($order->service->name ?? '—') }}</td>
                        <td>
                            @php
                            $badge = match($order->status) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'processing' => 'info',
                            'rejected' => 'danger',
                            'completed' => 'primary',
                            default => 'secondary',
                            };
                            @endphp
                            <span class="badge bg-{{ $badge }} px-3 py-2" style="border-radius: 6px; color: {{ in_array($badge, ['warning', 'light']) ? '#000' : '#fff' }} !important;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td><small>{{ $order->created_at->format('d M Y') }}</small></td>
                        <td>
                            <a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary">{{ __('customer.dashboard.detail') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5">
            <ion-icon name="cart-outline" style="font-size:3rem; color:#d1d5db;"></ion-icon>
            <p class="text-muted mt-2 mb-3">{{ __('customer.dashboard.no_orders') }}</p>
            <a href="{{ url('/') }}" class="btn btn-danger btn-sm px-4">Lihat {{ __('customer.dashboard.service') }} →</a>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showNoQuotaAlert(type = 'meeting') {
    const isPodcast = (type === 'podcast');
    const roomTitle = isPodcast ? 'Studio Podcast' : 'Meeting Room';
    const roomText = isPodcast ? 'studio podcast' : 'ruang meeting';
    const buyUrl = isPodcast 
        ? "{{ route('podcast-room.order', ['package' => 'paket']) }}"
        : "{{ route('meeting-room.order', ['package' => 'paket']) }}";
    const btnColor = isPodcast ? '#4e0516' : '#2563eb';

    Swal.fire({
        icon: 'warning',
        title: 'Belum Memiliki Paket / Kuota',
        html: `
            <div style="text-align: center; color: #475569; font-size: 0.95rem; line-height: 1.6;">
                <p class="mb-2">Anda belum memiliki <strong>Paket Benefit</strong> atau <strong>kuota ${roomTitle}</strong> yang aktif.</p>
                <p class="mb-0">Silakan membeli paket terlebih dahulu untuk menikmati fasilitas ${roomText}.</p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-cart-shopping me-1"></i> Beli Paket Sekarang',
        cancelButtonText: 'Batal',
        confirmButtonColor: btnColor,
        cancelButtonColor: '#64748b',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = buyUrl;
        }
    });
}

function formatLiveDuration(seconds) {
    if (seconds <= 0) return '0 jam 0 menit 0 detik';
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    return h + " jam " + m + " menit " + s + " detik";
}

function updateCustomerLiveTimers() {
    const nowTs = Math.floor(Date.now() / 1000);

    document.querySelectorAll('.active-session-timer').forEach(el => {
        const checkinTs = parseInt(el.dataset.checkinTime);
        if (checkinTs) {
            const elapsed = Math.max(0, nowTs - checkinTs);
            el.innerText = formatLiveDuration(elapsed);
        }
    });

    document.querySelectorAll('.active-used-timer').forEach(el => {
        const baseUsed = parseInt(el.dataset.baseUsed) || 0;
        const checkinTs = parseInt(el.dataset.checkinTime) || nowTs;
        const sessionElapsed = Math.max(0, nowTs - checkinTs);
        const currentUsed = baseUsed + sessionElapsed;
        el.innerText = formatLiveDuration(currentUsed);
    });

    document.querySelectorAll('.active-remaining-timer').forEach(el => {
        const total = parseInt(el.dataset.totalQuota) || 0;
        const baseUsed = parseInt(el.dataset.baseUsed) || 0;
        const checkinTs = parseInt(el.dataset.checkinTime) || nowTs;
        const sessionElapsed = Math.max(0, nowTs - checkinTs);
        const currentRemaining = Math.max(0, total - (baseUsed + sessionElapsed));
        el.innerText = formatLiveDuration(currentRemaining);
    });
}

setInterval(updateCustomerLiveTimers, 1000);
updateCustomerLiveTimers();
</script>
@endpush
@endsection