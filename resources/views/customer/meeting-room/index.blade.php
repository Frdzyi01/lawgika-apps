@extends('layouts-customer.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Histori Reservasi Meeting Room</h1>
        <a href="{{ route('meeting-room.order') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Reservasi Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Reservasi Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Waktu & Peserta</th>
                            <th>Status Pembayaran</th>
                            <th>Pemakaian Waktu</th>
                            <th>Status Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->id }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                    <small class="d-block">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</small>
                                    <small class="text-muted">{{ $booking->participants }} Orang | {{ $booking->duration }} Jam</small>
                                </td>
                                <td>
                                    @if($booking->payment_status === 'approved')
                                        <span class="badge bg-success">✅ Pembayaran diterima</span>
                                    @elseif($booking->payment_status === 'rejected')
                                        <span class="badge bg-danger">❌ Pembayaran ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">⏳ Menunggu konfirmasi admin</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status === 'approved')
                                        <small class="d-block">Total: {{ $booking->formatSeconds($booking->duration * 3600) }}</small>
                                        <small class="d-block">Dipakai: <span class="used-time-display" data-status="{{ $booking->status }}" data-used="{{ $booking->used_seconds }}">{{ $booking->formatted_used_time }}</span></small>
                                        @php
                                            $sisa = $booking->formatted_remaining_time;
                                            $bc = 'bg-success';
                                            if ($booking->is_expired || $sisa === 'Waktu habis') {
                                                $bc = 'bg-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $bc }} mt-1">Sisa: <span class="remaining-time-display" data-status="{{ $booking->status }}" data-remaining="{{ $booking->remaining_seconds }}">{{ $sisa }}</span></span>
                                    @else
                                        <span class="text-muted small">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status !== 'approved')
                                        <span class="badge bg-secondary">Belum aktif</span>
                                    @elseif($booking->is_expired)
                                        <span class="badge bg-danger" title="Expired setelah 1 tahun dari reservasi dibuat">❌ Expired</span>
                                    @elseif($booking->remaining_seconds <= 0)
                                        <span class="badge bg-secondary">Selesai</span>
                                    @elseif($booking->status === 'checkin')
                                        <span class="badge bg-primary">Sedang Digunakan</span>
                                    @elseif($booking->status === 'paused' || $booking->used_seconds > 0)
                                        <span class="badge bg-warning text-dark">Berhenti sementara</span>
                                    @else
                                        <span class="badge bg-info text-dark">Siap digunakan</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status !== 'approved')
                                        <span class="text-muted small">Tunggu konfirmasi pembayaran</span>
                                    @elseif($booking->is_expired)
                                        <span class="text-muted small text-danger fw-bold">Expired</span>
                                    @elseif($booking->remaining_seconds <= 0)
                                        <span class="text-muted small text-danger fw-bold">Waktu Habis</span>
                                    @elseif($booking->status === 'checkin')
                                        <button class="btn btn-sm btn-primary" disabled style="cursor: not-allowed;">Sedang Checkin</button>
                                        <small class="d-block text-muted mt-1" style="font-size: 11px;">*Dikelola Admin</small>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled style="cursor: not-allowed;">Sedang Checkout</button>
                                        <small class="d-block text-muted mt-1" style="font-size: 11px;">*Dikelola Admin</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Anda belum memiliki histori reservasi meeting room.</td>
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