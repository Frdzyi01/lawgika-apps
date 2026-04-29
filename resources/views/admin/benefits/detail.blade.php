@extends('layouts-admin.admin')

@section('title', 'Detail Benefit Ruangan')

@section('content')
@php use App\Models\RoomBenefit; @endphp

<div class="mb-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div>
        <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-2">
            &larr; Kembali
        </a>
        <h4 class="mb-0">Detail Benefit Ruangan</h4>
        <small class="text-muted">
            Order: <code>{{ $benefit->order->order_number ?? '#'.$benefit->order_id }}</code>
        </small>
    </div>
    {{-- Dynamic Status Badge --}}
    @php
        $statusColors = [
            'Siap Digunakan'   => ['bg' => '#0dcaf0', 'text' => '#000'],
            'Sedang Digunakan' => ['bg' => '#fd7e14', 'text' => '#fff'],
            'Selesai'          => ['bg' => '#198754', 'text' => '#fff'],
            'Expired'          => ['bg' => '#dc3545', 'text' => '#fff'],
            'Nonaktif'         => ['bg' => '#6c757d', 'text' => '#fff'],
        ];
        $sc = $statusColors[$benefit->status_label] ?? ['bg' => '#6c757d', 'text' => '#fff'];
    @endphp
    <span class="badge fs-6 px-3 py-2"
          style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};">
        {{ $benefit->status_label }}
    </span>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- ── LEFT: Informasi Benefit ── --}}
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent border-bottom pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">
                    <i class="fa fa-id-card me-2 text-primary"></i>Informasi Benefit
                </h6>
            </div>
            <div class="card-body px-4 pb-4">
                <table class="table table-borderless mb-0" style="font-size:.93rem;">
                    <tr>
                        <td class="text-muted ps-0" width="45%">No Order</td>
                        <td class="fw-semibold">
                            <code>{{ $benefit->order->order_number ?? '#'.$benefit->order_id }}</code>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Nama Paket</td>
                        <td>
                            <span class="badge bg-primary">{{ $benefit->paket }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Customer</td>
                        <td class="fw-semibold">{{ $benefit->user->name ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Email</td>
                        <td>{{ $benefit->user->email ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Total Jam</td>
                        <td>{{ RoomBenefit::formatMinutes($benefit->total_minutes) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Dipakai</td>
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
                        <td class="text-muted ps-0">Sisa</td>
                        <td>
                            @if($benefit->remaining_minutes <= 0)
                                <span class="badge bg-danger">Habis</span>
                            @else
                                <strong class="text-success">
                                    {{ RoomBenefit::formatMinutes($benefit->remaining_minutes) }}
                                </strong>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Berlaku s/d</td>
                        <td>{{ $benefit->expired_at?->format('d M Y, H:i') ?? '–' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Disetujui</td>
                        <td>{{ $benefit->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Status</td>
                        <td>
                            <span class="badge"
                                  style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};">
                                {{ $benefit->status_label }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Riwayat Penggunaan ── --}}
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom pt-4 pb-2 px-4
                        d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">
                    <i class="fa fa-clock-rotate-left me-2 text-warning"></i>Riwayat Penggunaan
                </h6>
                <span class="badge bg-secondary">{{ $sessions->count() }} sesi</span>
            </div>
            <div class="card-body p-0">
                @if($sessions->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-history fa-2x mb-2 d-block opacity-30"></i>
                        Belum ada riwayat penggunaan.
                    </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" style="font-size:.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">#</th>
                                <th>Ruangan</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Durasi</th>
                                <th>Status Sesi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sessions as $i => $s)
                            <tr @if($s->is_active) style="background:#fff8e1;" @endif>
                                <td class="ps-4 text-muted">{{ $i + 1 }}</td>
                                <td>
                                    @if($s->room_type === 'meeting')
                                        <span class="badge bg-info text-dark">
                                            <i class="fa fa-users me-1"></i>Meeting Room
                                        </span>
                                    @else
                                        <span class="badge bg-purple" style="background:#7c3aed;">
                                            <i class="fa fa-microphone me-1"></i>Podcast Room
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->checkin_at)
                                        <span title="{{ $s->checkin_at->format('d M Y, H:i:s') }}">
                                            {{ $s->checkin_at->format('d M Y') }}<br>
                                            <strong>{{ $s->checkin_at->format('H:i') }}</strong>
                                        </span>
                                    @else
                                        <span class="text-muted">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->checkout_at)
                                        <span title="{{ $s->checkout_at->format('d M Y, H:i:s') }}">
                                            {{ $s->checkout_at->format('d M Y') }}<br>
                                            <strong>{{ $s->checkout_at->format('H:i') }}</strong>
                                        </span>
                                    @else
                                        <span class="text-warning fw-semibold">Sedang berlangsung…</span>
                                    @endif
                                </td>
                                <td>
                                    @if($s->is_active)
                                        <span class="badge bg-warning text-dark" id="live-duration-{{ $i }}"
                                              data-minutes="{{ $s->duration_minutes }}">
                                            ~{{ RoomBenefit::formatMinutes($s->duration_minutes) }}
                                        </span>
                                    @else
                                        {{ RoomBenefit::formatMinutes($s->duration_minutes) }}
                                    @endif
                                </td>
                                <td>
                                    @if($s->is_active)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fa fa-circle-dot fa-beat me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-success">Selesai</span>
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

        {{-- Raw log accordion (for debugging / transparency) --}}
        @if($logs->count())
        <div class="accordion mt-3" id="rawLogsAccordion">
            <div class="accordion-item border-0 shadow-sm">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 fs-sm" type="button"
                            data-bs-toggle="collapse" data-bs-target="#rawLogs" style="font-size:.85rem;">
                        <i class="fa fa-list me-2"></i>Log Mentah ({{ $logs->count() }} entri)
                    </button>
                </h2>
                <div id="rawLogs" class="accordion-collapse collapse">
                    <div class="accordion-body p-0">
                        <table class="table table-sm mb-0" style="font-size:.82rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Waktu</th>
                                    <th>Ruangan</th>
                                    <th>Aksi</th>
                                    <th>Durasi (menit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                <tr>
                                    <td class="ps-3">{{ $log->action_at->format('d M Y H:i:s') }}</td>
                                    <td>{{ $log->room_type }}</td>
                                    <td>
                                        @if($log->action === 'checkin')
                                            <span class="badge bg-success">checkin</span>
                                        @else
                                            <span class="badge bg-secondary">checkout</span>
                                        @endif
                                    </td>
                                    <td>{{ $log->duration_minutes }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Live-update the duration badge for any active sessions
    setInterval(() => {
        document.querySelectorAll('[id^="live-duration-"]').forEach(el => {
            let mins = parseInt(el.dataset.minutes);
            mins++;
            el.dataset.minutes = mins;
            const h = Math.floor(mins / 60);
            const m = mins % 60;
            el.textContent = '~' + h + ' jam ' + m + ' menit';
        });
    }, 60000); // update every minute
</script>
@endpush
