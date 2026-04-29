@extends('layouts-customer.app')

@section('content')
@php use App\Models\RoomBenefit; @endphp

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary mb-2">
                &larr; Kembali
            </a>
            <h1 class="h3 mb-0 text-gray-800">Detail Benefit Ruangan</h1>
            <small class="text-muted">
                Order: <code>{{ $benefit->order->order_number ?? '#'.$benefit->order_id }}</code>
            </small>
        </div>
        @php
            $statusColors = [
                'Siap Digunakan'   => 'info',
                'Sedang Digunakan' => 'warning',
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
                <div class="card-header py-3" style="background:linear-gradient(135deg,#d4edda,#c3e6cb);">
                    <h6 class="m-0 font-weight-bold" style="color:#155724;">
                        🎁 Informasi Benefit
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0" style="font-size:.93rem;">
                        <tr>
                            <td class="text-muted ps-0" width="45%">No Order</td>
                            <td class="fw-semibold">
                                <code>{{ $benefit->order->order_number ?? '#'.$benefit->order_id }}</code>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Nama Paket</td>
                            <td><span class="badge bg-primary">{{ $benefit->paket }}</span></td>
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
                            <td>{{ $benefit->expired_at?->format('d M Y') ?? '–' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-0">Status</td>
                            <td>
                                <span class="badge bg-{{ $sc }}">{{ $benefit->status_label }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: Riwayat Penggunaan ── --}}
        <div class="col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        🕐 Riwayat Penggunaan
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
                                    <th>Waktu Check-in</th>
                                    <th>Waktu Check-out</th>
                                    <th>Durasi</th>
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
                                            <span class="badge" style="background:#7c3aed;color:#fff;">
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
                                                Sedang berlangsung…
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
                Check-in dan Check-out dikelola oleh admin. Durasi dihitung otomatis saat checkout.
            </div>
        </div>

    </div>
</div>
@endsection
