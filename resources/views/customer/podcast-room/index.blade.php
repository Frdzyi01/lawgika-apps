@extends('layouts-customer.app')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('customer.podcast.index.title') }}</h1>
        <a href="{{ url('/sewa-ruang-podcast') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> {{ __('customer.podcast.index.new_res') }}
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- ============================================================ --}}
    {{-- TABLE 1 (NEW): Benefit dari Paket PT                        --}}
    {{-- ============================================================ --}}
    @include('partials.room-benefit-table', [
        'benefits'  => $benefits,
        'roomLabel' => 'Podcast Room',
        'isAdmin'   => false,
        'roomType'  => 'podcast',
    ])

    {{-- ============================================================ --}}
    {{-- TABLE 2 (NEW): Riwayat Reservasi Benefit                    --}}
    {{-- ============================================================ --}}
    @if($benefitBookings->count() > 0)
    <div class="card shadow mb-4 border-left-info">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">🎟️ {{ __('customer.podcast.index.benefit_title') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('customer.podcast.index.order_no') }}</th>
                            <th>{{ __('customer.podcast.index.podcast_title') }}</th>
                            <th>{{ __('customer.podcast.index.time') }}</th>
                            <th>{{ __('customer.podcast.index.duration_applied') }}</th>
                            <th>{{ __('customer.podcast.index.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($benefitBookings as $booking)
                            <tr>
                                <td><code style="font-size:.8rem;">{{ $booking->order_number ?? '#'.$booking->id }}</code></td>
                                <td>{{ $booking->podcast_title ?? '–' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                    <small class="d-block">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</small>
                                </td>
                                <td>{{ $booking->duration }} {{ __('customer.mr.index.hours_count', ['count' => '']) }}</td>
                                <td>
                                    @if($booking->status === 'pending_approval')
                                        <span class="badge bg-warning text-dark">⏳ {{ __('customer.benefit.detail.res.status.pending') }}</span>
                                    @elseif($booking->status === 'approved')
                                        <span class="badge bg-success">✅ {{ __('customer.benefit.detail.res.status.approved') }}</span>
                                    @elseif($booking->status === 'rejected')
                                        <span class="badge bg-danger">❌ {{ __('customer.benefit.detail.res.status.rejected') }}</span>
                                    @elseif($booking->status === 'checkin')
                                        <span class="badge bg-primary">{{ __('customer.benefit.detail.res.status.in_use') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('customer.benefit.detail.res.status.pending') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- TABLE 3 (EXISTING): {{ __('customer.podcast.index.manual_title') }} — unchanged query/data --}}
    {{-- ============================================================ --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">🎙️ {{ __('customer.podcast.index.manual_title') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('customer.podcast.index.order_no') }}</th>
                            <th>{{ __('customer.podcast.index.time') }} & Paket</th>
                            <th>{{ __('customer.podcast.index.podcast_title') }}</th>
                            <th>{{ __('customer.podcast.index.manual.total') }}</th>
                            <th>{{ __('customer.podcast.index.manual.proof') }}</th>
                            <th>{{ __('customer.podcast.index.manual.payment_status') }}</th>
                            <th>{{ __('customer.podcast.index.manual.usage') }}</th>
                            <th>{{ __('customer.podcast.index.manual.room_status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($manualBookings as $b)
                        <tr>
                            <td><code style="font-size:.8rem;">{{ $b->order_number ?? '#'.$b->id }}</code></td>
                            <td>
                                {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                <small class="text-muted">{{ $b->duration }} {{ __('customer.mr.index.hours_count', ['count' => '']) }}</small>
                            </td>
                            <td>{{ $b->podcast_title ?? '–' }}</td>
                            <td>Rp {{ number_format($b->total_price,0,',','.') }}</td>
                            {{-- {{ __('customer.podcast.index.manual.proof') }} (Customer View) --}}
                            <td>
                                @if($b->payment_proof)
                                    <a href="{{ asset('storage/'.$b->payment_proof) }}" target="_blank">
                                        <img src="{{ asset('storage/'.$b->payment_proof) }}"
                                            style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;"
                                            title="Klik untuk melihat bukti bayar">
                                    </a>
                                    <div style="font-size:.75rem;margin-top:4px;">
                                        @if($b->payment_status==='approved')
                                            <span class="text-success fw-bold">✅ {{ __('customer.podcast.index.manual.approved') }}</span>
                                        @elseif($b->payment_status==='rejected')
                                            <span class="text-danger fw-bold">❌ {{ __('customer.podcast.index.manual.rejected') }}</span>
                                        @else
                                            <span class="text-warning fw-bold">⏳ {{ __('customer.podcast.index.manual.pending') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted small">{{ __('customer.podcast.index.manual.not_uploaded') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status==='approved')
                                    <span class="badge bg-success">✅ {{ __('customer.mr.index.payment.approved') }}</span>
                                @elseif($b->payment_status==='rejected')
                                    <span class="badge bg-danger">❌ {{ __('customer.mr.index.payment.rejected') }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ {{ __('customer.podcast.index.manual.pending') }} konfirmasi</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status==='approved')
                                    <small class="d-block">{{ __('customer.podcast.index.manual.total') }}: {{ $b->formatSeconds($b->duration * 3600) }}</small>
                                    <small class="d-block">Dipakai: {{ $b->formatted_used_time }}</small>
                                    @php $sisa=$b->formatted_remaining_time; $bc=$sisa==='{{ __('customer.podcast.index.time') }} habis'?'bg-danger':'bg-success'; @endphp
                                    <span class="badge {{ $bc }} mt-1">Sisa:  {{ $sisa }}</span>
                                @else
                                    <span class="text-muted small">–</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status!=='approved')
                                    <span class="badge bg-secondary">{{ __('customer.mr.index.room.not_active') }}</span>
                                @elseif($b->remaining_seconds<=0)
                                    <span class="badge bg-secondary">{{ __('customer.mr.index.room.finished') }}</span>
                                @elseif($b->status==='checkin')
                                    <span class="badge bg-primary">{{ __('customer.benefit.detail.res.status.in_use') }}</span>
                                @elseif($b->status==='paused'||$b->total_used_minutes>0)
                                    <span class="badge bg-warning text-dark">{{ __('customer.mr.index.room.paused') }}</span>
                                @else
                                    <span class="badge bg-info text-dark">{{ __('customer.mr.index.room.ready') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">{{ __('customer.podcast.index.manual.no_history') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>setInterval(()=>location.reload(),30000);</script>
@endpush
