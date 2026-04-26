@extends('layouts-admin.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Meeting Room</h1>
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
            <h6 class="m-0 font-weight-bold text-primary">Daftar Reservasi</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama Pemesan</th>
                            <th>Waktu & Peserta</th>
                            <th>Bukti Bayar</th>
                            <th>Status Bayar</th>
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
                                    {{ $booking->name }}
                                    @if($booking->user)
                                        <br><small class="text-muted">User ID: {{ $booking->user_id }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                                    <small class="d-block">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}</small>
                                    <small class="text-muted">{{ $booking->participants }} Orang</small>
                                </td>
                                <td>
                                    @if($booking->payment_proof)
                                        <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                                                 alt="Bukti Bayar"
                                                 style="width:70px;height:70px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                                        </a>
                                    @else
                                        <span class="text-muted small">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status === 'approved')
                                        <span class="badge bg-success">✅ Disetujui</span>
                                    @elseif($booking->payment_status === 'rejected')
                                        <span class="badge bg-danger">❌ Ditolak</span>
                                    @else
                                        <span class="badge bg-warning text-dark">⏳ Pending</span>
                                    @endif

                                    {{-- Approve/Reject buttons --}}
                                    @if($booking->payment_status === 'pending')
                                        <div class="mt-1 d-flex gap-1">
                                            <form action="{{ url('admin/meeting-room/'.$booking->id.'/approve-payment') }}" method="POST">
                                                @csrf
                                                <button class="btn btn-xs btn-success py-0 px-2" style="font-size:0.75rem;" onclick="return confirm('Setujui pembayaran ini?')">Approve</button>
                                            </form>
                                            <form action="{{ url('admin/meeting-room/'.$booking->id.'/reject-payment') }}" method="POST">
                                                @csrf
                                                <button class="btn btn-xs btn-danger py-0 px-2" style="font-size:0.75rem;" onclick="return confirm('Tolak pembayaran ini?')">Reject</button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    @if($booking->is_expired)
                                        <span class="badge bg-danger" title="Expired setelah 1 tahun dari reservasi dibuat">❌ Expired</span>
                                    @elseif($booking->remaining_seconds <= 0)
                                        <span class="badge bg-danger">⛔ Waktu Habis</span>
                                    @elseif($booking->status === 'checkin')
                                        <span class="badge bg-success">✅ Aktif</span>
                                    @elseif($booking->status === 'paused' || $booking->used_seconds > 0)
                                        <span class="badge bg-warning text-dark">⏳ Berhenti sementara</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ url('admin/meeting-room/'.$booking->id.'/detail') }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Lihat Data</a>
                                    @if($booking->payment_status !== 'approved')
                                        <span class="text-muted small text-center mt-1">Tunggu konfirmasi bayar</span>
                                    @elseif($booking->is_expired)
                                        <span class="text-muted small text-danger fw-bold text-center mt-1">Expired</span>
                                    @elseif($booking->remaining_seconds <= 0)
                                        <span class="text-muted small text-danger fw-bold text-center mt-1">Waktu Habis</span>
                                    @elseif($booking->status === 'checkin')
                                        <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkout') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-warning text-dark w-100" onclick="return confirm('Check Out?')">Check Out</button>
                                        </form>
                                    @else
                                        <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkin') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success w-100" onclick="return confirm('Check In?')">Check In</button>
                                        </form>
                                    @endif
                                </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Belum ada data reservasi meeting room.</td>
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
                    if (rem === 0) location.reload(); // auto refresh when exactly 0
                }
            }
        });
    }, 1000);
</script>
@endpush
