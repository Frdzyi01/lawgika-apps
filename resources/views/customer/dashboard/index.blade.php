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

{{-- Shared Quota Banner --}}
@if(isset($quota))
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

@endsection