@extends('layouts-customer.app')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('customer.mr.detail.title') }}</h1>
        <a href="{{ url('dashboard/meeting-room') }}" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> {{ __('customer.mr.detail.btn.back') }}</a>
    </div>

    <div class="row">
        <!-- {{ __('customer.mr.detail.info_title') }} -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('customer.mr.detail.info_title') }}</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.user_name') }}</td>
                            <td class="fw-bold">{{ $booking->user ? $booking->user->name : $booking->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.company_name') }}</td>
                            <td class="fw-bold">{{ $booking->nama_perusahaan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.email') }}</td>
                            <td class="fw-bold">{{ $booking->email ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.address') }}</td>
                            <td class="fw-bold">{{ $booking->alamat_usaha ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.business_field') }}</td>
                            <td class="fw-bold">{{ $booking->bidang_usaha ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.purpose') }}</td>
                            <td class="fw-bold">{{ $booking->keperluan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.order_date') }}</td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.expired_date') }}</td>
                            <td class="fw-bold text-danger">{{ \Carbon\Carbon::parse($booking->created_at)->addYear()->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">{{ __('customer.mr.detail.room_status') }}</td>
                            <td>
                                @if($booking->is_expired)
                                    <span class="badge bg-danger">❌ {{ __('customer.mr.detail.status.expired') }}</span>
                                @elseif($booking->remaining_seconds <= 0)
                                    <span class="badge bg-danger">⛔ {{ __('customer.mr.detail.status.exhausted') }}</span>
                                @elseif($booking->status === 'checkin')
                                    <span class="badge bg-success">✅ {{ __('customer.mr.detail.status.active') }}</span>
                                @elseif($booking->status === 'paused' || $booking->used_seconds > 0)
                                    <span class="badge bg-warning text-dark">⏳ {{ __('customer.mr.detail.status.paused') }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ __('customer.mr.detail.status.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        @if($booking->benefit_id)
        <!-- Riwayat {{ __('customer.mr.detail.logs.checkin') }} / Out (for benefit bookings) -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ __('customer.mr.detail.logs_title') }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('customer.mr.detail.logs.no') }}</th>
                                    <th>{{ __('customer.mr.detail.logs.type') }}</th>
                                    <th>{{ __('customer.mr.detail.logs.date') }}</th>
                                    <th>{{ __('customer.mr.detail.logs.time') }}</th>
                                    <th>{{ __('customer.mr.detail.logs.duration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($log->type === 'checkin')
                                                <span class="badge bg-success"><i class="fas fa-sign-in-alt"></i> {{ __('customer.mr.detail.logs.checkin') }}</span>
                                            @else
                                                <span class="badge bg-danger"><i class="fas fa-sign-out-alt"></i> {{ __('customer.mr.detail.logs.checkout') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('H:i:s') }}</td>
                                        <td>
                                            @if($log->type === 'checkout' && $index > 0 && $logs[$index-1]->type === 'checkin')
                                                @php
                                                    $diff = \Carbon\Carbon::parse($logs[$index-1]->timestamp)->diffInSeconds($log->timestamp);
                                                    $billedHours = $booking->calculateBillingHours($diff);
                                                @endphp
                                                <span class="text-muted">{{ $billedHours }} jam</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">{{ __('customer.mr.detail.logs.no_history') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Pemakaian Waktu -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pemakaian Waktu</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">{{ __('customer.mr.detail.usage.total_time') }}</span>
                        <span class="fw-bold">{{ $booking->formatSeconds($booking->duration * 3600) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">{{ __('customer.mr.detail.usage.used_time') }}</span>
                        <span class="fw-bold text-primary used-time-display" data-status="{{ $booking->status }}" data-used="{{ $booking->used_seconds }}">{{ $booking->formatted_used_time }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">{{ __('customer.mr.detail.usage.remaining_time') }}</span>
                        @php 
                            $sisa = $booking->formatted_remaining_time; 
                            $text_class = ($booking->is_expired || $sisa === 'Waktu habis') ? 'text-danger' : 'text-success';
                        @endphp
                        <span class="fw-bold {{ $text_class }} remaining-time-display" data-status="{{ $booking->status }}" data-remaining="{{ $booking->remaining_seconds }}">{{ $sisa }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(!$booking->benefit_id)
    <!-- Riwayat {{ __('customer.mr.detail.logs.checkin') }} / Out -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('customer.mr.detail.logs_title') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('customer.mr.detail.logs.no') }}</th>
                            <th>{{ __('customer.mr.detail.logs.type') }}</th>
                            <th>{{ __('customer.mr.detail.logs.date') }}</th>
                            <th>{{ __('customer.mr.detail.logs.time') }}</th>
                            <th>{{ __('customer.mr.detail.logs.duration') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($log->type === 'checkin')
                                        <span class="badge bg-success"><i class="fas fa-sign-in-alt"></i> {{ __('customer.mr.detail.logs.checkin') }}</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-sign-out-alt"></i> {{ __('customer.mr.detail.logs.checkout') }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->timestamp)->format('H:i:s') }}</td>
                                <td>
                                    @if($log->type === 'checkout' && $index > 0 && $logs[$index-1]->type === 'checkin')
                                        @php
                                            $diff = \Carbon\Carbon::parse($logs[$index-1]->timestamp)->diffInSeconds($log->timestamp);
                                        @endphp
                                        <span class="text-muted">{{ $booking->formatSeconds($diff) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('customer.mr.detail.logs.no_history') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
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
</script>
@endpush
