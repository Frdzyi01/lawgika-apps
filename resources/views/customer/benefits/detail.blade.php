@extends('layouts-customer.app')

@section('content')
@php use App\Models\RoomBenefit; @endphp

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-2">
                &larr; {{ __('customer.mr.detail.btn.back') }}
            </a>
            <h1 class="h3 mb-0 text-gray-800">{{ __('customer.benefit.detail.title') }}</h1>
            <small class="text-muted">
                {{ __('customer.benefit.detail.order') }} <code>{{ $benefit->order->order_number ?? '{{ __('customer.benefit.detail.usage.no') }}'.$benefit->order_id }}</code>
            </small>
        </div>
        @php
            $statusColors = [
                'Siap Digunakan'   => 'info',
                '{{ __('customer.benefit.detail.res.status.in_use') }}' => 'warning',
                'Selesai'          => 'success',
                'Expired'          => 'danger',
                'Nonaktif'         => 'secondary',
            ];
            $sc = $statusColors[$benefit->status_label] ?? 'secondary';
        @endphp
        <span class="badge bg-{{ $sc }} fs-6 px-3 py-2">{{ $benefit->status_label }}</span>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">

        {{-- ── LEFT: Informasi Benefit ── --}}
        <div class="col-lg-5">
            <div class="card shadow mb-4 border-start border-success border-4">
                <div class="card-header py-3" style="background:linear-gradient(135deg,{{ __('customer.benefit.detail.usage.no') }}d4edda,{{ __('customer.benefit.detail.usage.no') }}c3e6cb);">
                    <h6 class="m-0 font-weight-bold" style="color:{{ __('customer.benefit.detail.usage.no') }}155724;">
                        🎁 {{ __('customer.benefit.detail.info_title') }}
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $ptData = $benefit->order ? $benefit->order->form_data : [];
                    @endphp
                    <table class="table table-borderless mb-0" style="font-size:.93rem;">
                        <tr>
                            <td class="text-muted ps-0" width="45%">{{ __('customer.benefit.detail.order_no') }}</td>
                            <td class="fw-semibold">
                                <code>{{ $benefit->order->order_number ?? '{{ __('customer.benefit.detail.usage.no') }}'.$benefit->order_id }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.pic_name') }}</td>
                            <td class="fw-semibold">{{ $ptData['pic_name'] ?? ($benefit->user->name ?? '-') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.company_name') }}</td>
                            <td class="fw-semibold">{{ $ptData['company_name'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.email') }}</td>
                            <td class="fw-semibold">{{ $ptData['company_email'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.address') }}</td>
                            <td class="fw-semibold">{{ $ptData['operational_address'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.business_field') }}</td>
                            <td class="fw-semibold">{{ $ptData['business_field'] ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.package_name') }}</td>
                            <td><span class="badge bg-primary">{{ $benefit->paket }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.total_time') }}</td>
                            <td>{{ RoomBenefit::formatMinutes($benefit->total_minutes) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.used_time') }}</td>
                            <td>
                                {{ RoomBenefit::formatMinutes($benefit->used_minutes) }}
                                <div class="progress mt-1" style="height:5px;">
                                    @php
                                        $pct = $benefit->total_minutes > 0
                                            ? round(($benefit->used_minutes / $benefit->total_minutes) * 100)
                                            : 0;
                                    @endphp
                                    <div class="progress-bar bg-warning" style="width:{{ $pct }}%"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.remaining_time') }}</td>
                            <td>
                                @if($benefit->remaining_minutes <= 0)
                                    <span class="badge bg-danger">{{ __('customer.benefit.detail.exhausted') }}</span>
                                @else
                                    <strong class="text-success">
                                        {{ RoomBenefit::formatMinutes($benefit->remaining_minutes) }}
                                    </strong>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.order_time') }}</td>
                            <td class="fw-semibold">
                                {{ $benefit->order ? \Carbon\Carbon::parse($benefit->order->created_at)->format('d M Y H:i:s') : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.expired_time') }}</td>
                            <td class="fw-semibold text-danger">
                                {{ $benefit->expired_at ? $benefit->expired_at->format('d M Y H:i:s') : ($benefit->order ? \Carbon\Carbon::parse($benefit->order->created_at)->addYear()->format('d M Y H:i:s') : '-') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">{{ __('customer.benefit.detail.status') }}</td>
                            <td>
                                <span class="badge bg-{{ $sc }}">{{ $benefit->status_label }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Riwayat Reservasi Benefit & Riwayat Penggunaan ── --}}
        <div class="col-lg-7">
            {{-- 🎟️ {{ __('customer.benefit.detail.res_history') }} --}}
            @if ($benefitBookings->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">🎟️ {{ __('customer.benefit.detail.res_history') }}</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle" style="font-size:.88rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">{{ __('customer.benefit.detail.res.id') }}</th>
                                        <th>{{ __('customer.benefit.detail.res.time') }}</th>
                                        <th>{{ __('customer.benefit.detail.status') }} Pengajuan</th>
                                        <th class="pe-4">{{ __('customer.benefit.detail.res.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($benefitBookings as $booking)
                                        <tr>
                                            <td class="ps-4 text-muted">{{ $booking->id }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                                <small class="d-block"><strong>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</strong></small>
                                            </td>
                                            <td>
                                                @if ($booking->status === 'pending_approval' || $booking->status === 'pending')
                                                    <span class="badge bg-warning text-dark">⏳ {{ __('customer.benefit.detail.res.status.pending') }}</span>
                                                @elseif($booking->status === 'approved')
                                                    <span class="badge bg-success">✅ {{ __('customer.benefit.detail.res.status.approved') }}</span>
                                                @elseif($booking->status === 'rejected')
                                                    <span class="badge bg-danger">❌ {{ __('customer.benefit.detail.res.status.rejected') }}</span>
                                                @elseif($booking->status === 'checkin')
                                                    <span class="badge bg-primary">{{ __('customer.benefit.detail.res.status.in_use') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('customer.benefit.detail.res.status.finished') }}</span>
                                                @endif
                                            </td>
                                            <td class="pe-4">
                                                <a href="{{ route('customer.meeting-room.detail', $booking->id) }}" class="btn btn-sm btn-info text-white">
                                                    <i class="fas fa-eye"></i> {{ __('customer.benefit.detail.res.action') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        🕐 {{ __('customer.benefit.detail.usage_history') }}
                    </h6>
                    <span class="badge bg-secondary">{{ __('customer.benefit.detail.sessions', ['count' => $sessions->count()]) }}</span>
                </div>
                <div class="card-body p-0">
                    @if($sessions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="fa fa-history fa-2x mb-2 d-block opacity-30"></i>
                            {{ __('customer.benefit.detail.no_usage') }}
                        </div>
                    @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle" style="font-size:.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">{{ __('customer.benefit.detail.usage.no') }}</th>
                                    <th>{{ __('customer.benefit.detail.usage.room') }}</th>
                                    <th>{{ __('customer.benefit.detail.res.time') }} Check-in</th>
                                    <th>{{ __('customer.benefit.detail.res.time') }} Check-out</th>
                                    <th>{{ __('customer.benefit.detail.usage.duration') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $i => $s)
                                <tr @if($s->is_active) style="background:{{ __('customer.benefit.detail.usage.no') }}fff8e1;" @endif>
                                    <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                    <td>
                                        @if($s->room_type === 'meeting')
                                            <span class="badge bg-info text-dark">
                                                <i class="fa fa-users me-1"></i>Meeting Room
                                            </span>
                                        @else
                                            <span class="badge" style="background:{{ __('customer.benefit.detail.usage.no') }}7c3aed;color:{{ __('customer.benefit.detail.usage.no') }}fff;">
                                                <i class="fa fa-microphone me-1"></i>Podcast Room
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($s->checkin_at)
                                            {{ $s->checkin_at->format('d M Y') }}<br>
                                            <strong>{{ $s->checkin_at->format('H:i') }}</strong>
                                        @else
                                            <span class="text-muted">–</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($s->checkout_at)
                                            {{ $s->checkout_at->format('d M Y') }}<br>
                                            <strong>{{ $s->checkout_at->format('H:i') }}</strong>
                                        @else
                                            <span class="text-warning fw-semibold small">
                                                {{ __('customer.benefit.detail.usage.ongoing') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($s->is_active)
                                            <span class="badge bg-warning text-dark">
                                                ~{{ RoomBenefit::formatMinutes($s->duration_minutes) }}
                                            </span>
                                        @else
                                            {{ RoomBenefit::formatMinutes($s->duration_minutes) }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            <div class="alert alert-info py-2 px-3" style="font-size:.85rem;">
                <i class="fa fa-info-circle me-1"></i>
                Check-in dan Check-out dikelola oleh admin. {{ __('customer.benefit.detail.usage.duration') }} dihitung otomatis saat checkout.
            </div>
        </div>

    </div>
</div>
@endsection
