@extends('layouts-customer.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('customer.mr.index.title') }}</h1>
            <a href="{{ route('meeting-room.order') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> {{ __('customer.mr.index.new_res') }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ============================================================ --}}
        {{-- TABLE 1 (NEW): Benefit dari Paket PT                        --}}
        {{-- ============================================================ --}}
        @include('partials.room-benefit-table', [
            'benefits' => $benefits,
            'roomLabel' => 'Meeting Room',
            'isAdmin' => false,
            'roomType' => 'meeting',
        ])



        {{-- ============================================================ --}}
        {{-- TABLE 3 (EXISTING): Reservasi Manual — unchanged query/data --}}
        {{-- ============================================================ --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">📋 {{ __('customer.mr.index.manual_title') }}</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('customer.mr.index.id') }}</th>
                                <th>{{ __('customer.mr.index.time_participants') }}</th>
                                <th>{{ __('customer.mr.index.payment_status') }}</th>
                                <th>{{ __('customer.mr.index.time_usage') }}</th>
                                <th>{{ __('customer.mr.index.room_status') }}</th>
                                <th>{{ __('customer.mr.index.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($manualBookings as $booking)
                                @php
                                    $isPackage = !is_null($booking->meeting_room_package_id) || $booking->type === 'meeting_room_package';
                                @endphp
                                <tr>
                                    @if($isPackage)
                                        @php
                                            $package = $booking->meetingRoomPackage;
                                        @endphp
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            <span class="badge bg-success" style="font-size:0.9rem;">📦 {{ __('customer.mr.index.package_badge') }}</span>
                                            <small class="d-block text-muted mt-1">{{ __('customer.mr.index.package_hours') }}</small>
                                            @if($package && isset($package->last_used_date))
                                                <small class="d-block text-muted mt-1">{{ __('customer.mr.index.last_used', ['date' => \Carbon\Carbon::parse($package->last_used_date)->format('d M Y')]) }}</small>
                                                <small class="d-block text-muted">{{ __('customer.mr.index.participants_count', ['count' => $package->last_participant_count ?? 1]) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'pending' => '⏳ ' . __('customer.mr.index.payment.pending'),
                                                    'approved' => '✅ ' . __('customer.mr.index.payment.approved'),
                                                    'rejected' => '❌ ' . __('customer.mr.index.payment.rejected'),
                                                ];
                                                $paymentStatusBadge = 'bg-secondary';
                                                if ($booking->payment_status === 'approved') $paymentStatusBadge = 'bg-success';
                                                elseif ($booking->payment_status === 'rejected') $paymentStatusBadge = 'bg-danger';
                                                elseif ($booking->payment_status === 'pending') $paymentStatusBadge = 'bg-warning text-dark';
                                            @endphp
                                            <span class="badge {{ $paymentStatusBadge }}">{{ $statusMap[$booking->payment_status] ?? $booking->payment_status }}</span>
                                        </td>
                                        <td>
                                            @if($package)
                                                <small class="d-block">{{ __('customer.mr.index.usage.total', ['hours' => $package->total_hours]) }}</small>
                                                <small class="d-block">{{ __('customer.mr.index.usage.used', ['hours' => $package->total_hours - $package->remaining_hours]) }}</small>
                                                <span class="badge bg-success mt-1">{{ __('customer.mr.index.usage.remaining', ['hours' => max(0, $package->remaining_hours)]) }}</span>
                                            @else
                                                <span class="text-muted small">–</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($package)
                                                @php
                                                    $remaining = max(0, $package->remaining_hours);
                                                    if (isset($package->expired_at) && now()->greaterThan($package->expired_at)) {
                                                        $status = "{{ __('customer.mr.index.room.expired') }}";
                                                        $statusBadge = "bg-danger";
                                                    } elseif ($remaining == $package->total_hours) {
                                                        $status = "{{ __('customer.mr.index.room.not_used') }}";
                                                        $statusBadge = "bg-info text-dark";
                                                    } elseif ($remaining > 0) {
                                                        $status = "{{ __('customer.mr.index.room.paused') }}";
                                                        $statusBadge = "bg-warning text-dark";
                                                    } else {
                                                        $status = "{{ __('customer.mr.index.room.exhausted') }}";
                                                        $statusBadge = "bg-secondary";
                                                    }
                                                @endphp
                                                <span class="badge {{ $statusBadge }}">{{ $status }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('customer.mr.index.room.unknown') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$package)
                                                <span class="text-muted small">{{ __('customer.mr.index.btn.awaiting_payment') }}</span>
                                            @elseif(isset($package->expired_at) && now()->greaterThan($package->expired_at))
                                                <span class="text-muted small text-danger fw-bold">❌ Paket {{ __('customer.mr.index.room.expired') }}</span>
                                            @elseif($package->remaining_hours <= 0)
                                                <span class="text-muted small text-danger fw-bold">❌ Paket {{ __('customer.mr.index.room.exhausted') }}</span>
                                            @elseif($booking->status === 'checkin')
                                                <button class="btn btn-sm btn-secondary" disabled style="cursor: not-allowed;">{{ __('customer.mr.index.btn.checking_out') }}</button>
                                                <small class="d-block text-muted mt-1" style="font-size: 11px;">{{ __('customer.mr.index.btn.managed_admin') }}</small>
                                            @else
                                                <a href="/meeting-room/reserve?type=package&package_id={{ $package->id }}" class="btn btn-sm btn-primary">
                                                    {{ __('customer.mr.index.btn.use_package') }}
                                                </a>
                                            @endif
                                            
                                            <div class="mt-2">
                                                <a href="{{ route('customer.meeting-room.detail', $booking->id) }}" class="btn btn-sm btn-info text-white w-100">
                                                    <i class="fas fa-eye"></i> {{ __('customer.mr.index.btn.detail_order') }}
                                                </a>
                                            </div>
                                        </td>
                                    @else
                                        <td>{{ $booking->id }}</td>
                                        <td>
                                            @php
                                                $isPackageOld = empty($booking->date) && empty($booking->start_time);
                                            @endphp

                                            @if ($isPackageOld)
                                                <span class="badge bg-success" style="font-size:0.9rem;">📦 {{ __('customer.mr.index.package_badge') }}</span>
                                                <small class="d-block text-muted mt-1">{{ __('customer.mr.index.package_hours') }}</small>
                                            @else
                                                {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                                <small class="d-block">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</small>
                                                <small class="text-muted">{{ __('customer.mr.index.participants_count', ['count' => $booking->participants]) }} | {{ __('customer.mr.index.hours_count', ['count' => $booking->duration]) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($booking->payment_status === 'approved')
                                                <span class="badge bg-success">✅ {{ __('customer.mr.index.payment.approved') }}</span>
                                            @elseif($booking->payment_status === 'rejected')
                                                <span class="badge bg-danger">❌ {{ __('customer.mr.index.payment.rejected') }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">⏳ {{ __('customer.mr.index.payment.awaiting_confirmation') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($booking->payment_status === 'approved')
                                                <small class="d-block">{{ __('customer.mr.index.usage.total', ['hours' => $booking->formatSeconds($booking->duration * 3600)]) }}</small>
                                                <small class="d-block">{{ __('customer.mr.index.usage.used_secs', ['time' => '']) }} <span class="used-time-display" data-status="{{ $booking->status }}" data-used="{{ $booking->used_seconds }}">{{ $booking->formatted_used_time }}</span></small>
                                                @php
                                                    $sisa = $booking->formatted_remaining_time;
                                                    $bc = 'bg-success';
                                                    if ($booking->is_expired || $sisa === 'Waktu habis') {
                                                        $bc = 'bg-danger';
                                                    }
                                                @endphp
                                                <span class="badge {{ $bc }} mt-1">{{ __('customer.mr.index.usage.remaining_secs', ['time' => '']) }} <span class="remaining-time-display" data-status="{{ $booking->status }}" data-remaining="{{ $booking->remaining_seconds }}">{{ $sisa }}</span></span>
                                            @else
                                                <span class="text-muted small">–</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($booking->payment_status !== 'approved')
                                                <span class="badge bg-secondary">{{ __('customer.mr.index.room.not_active') }}</span>
                                            @elseif($booking->is_expired)
                                                <span class="badge bg-danger" title="{{ __('customer.mr.index.room.expired') }} setelah 1 tahun dari reservasi dibuat">❌ {{ __('customer.mr.index.room.expired') }}</span>
                                            @elseif($booking->remaining_seconds <= 0)
                                                <span class="badge bg-secondary">{{ __('customer.mr.index.room.finished') }}</span>
                                            @elseif($booking->status === 'checkin')
                                                <span class="badge bg-primary">{{ __('customer.mr.index.room.in_use') }}</span>
                                            @elseif($booking->status === 'paused' || $booking->used_seconds > 0)
                                                <span class="badge bg-warning text-dark">{{ __('customer.mr.index.room.paused') }}</span>
                                            @else
                                                <span class="badge bg-info text-dark">{{ __('customer.mr.index.room.ready') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('customer.meeting-room.detail', $booking->id) }}" class="btn btn-sm btn-info text-white mb-2">
                                                <i class="fas fa-eye"></i> {{ __('customer.mr.index.btn.detail') }}
                                            </a>
                                            <br>
                                            @if ($booking->payment_status !== 'approved')
                                                <span class="text-muted small">{{ __('customer.mr.index.btn.awaiting_payment') }}</span>
                                            @elseif($booking->is_expired)
                                                <span class="text-muted small text-danger fw-bold">{{ __('customer.mr.index.room.expired') }}</span>
                                            @elseif($booking->remaining_seconds <= 0)
                                                <span class="text-muted small text-danger fw-bold">Waktu {{ __('customer.mr.index.room.exhausted') }}</span>
                                            @elseif($booking->status === 'checkin')
                                                <button class="btn btn-sm btn-primary" disabled style="cursor: not-allowed;">{{ __('customer.mr.index.btn.checking_in') }}</button>
                                                <small class="d-block text-muted mt-1" style="font-size: 11px;">*{{ __('customer.mr.index.btn.managed_admin') }}</small>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled style="cursor: not-allowed;">{{ __('customer.mr.index.btn.checking_out') }}</button>
                                                <small class="d-block text-muted mt-1" style="font-size: 11px;">*{{ __('customer.mr.index.btn.managed_admin') }}</small>
                                            @endif

                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('customer.mr.index.no_history') }}</td>
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
