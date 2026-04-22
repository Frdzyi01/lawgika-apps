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
                                    <small class="d-block">Total: {{ $booking->formatMinutes($booking->duration * 60) }}</small>
                                    <small class="d-block">Dipakai: {{ $booking->formatMinutes($booking->used_minutes) }}</small>
                                    @php
                                        $sisa = $booking->formatted_remaining_time;
                                        $bc = $sisa === 'Waktu habis' ? 'bg-danger' : ((!str_contains($sisa,'jam') && (int)$sisa < 15) ? 'bg-warning text-dark' : 'bg-success');
                                    @endphp
                                    <span class="badge {{ $bc }} mt-1">Sisa: {{ $sisa }}</span>
                                </td>
                                <td>
                                    @if($booking->remaining_minutes <= 0)
                                        <span class="badge bg-secondary">Selesai</span>
                                    @elseif($booking->status === 'checkin')
                                        <span class="badge bg-primary">Sedang digunakan</span>
                                    @elseif($booking->status === 'paused' || $booking->total_used_minutes > 0)
                                        <span class="badge bg-warning text-dark">Berhenti sementara</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->payment_status !== 'approved')
                                        <span class="text-muted small">Tunggu konfirmasi bayar</span>
                                    @elseif($booking->remaining_minutes <= 0)
                                        <span class="text-muted small">Waktu Habis</span>
                                    @elseif($booking->status === 'checkin')
                                        <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkout') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-warning text-dark" onclick="return confirm('Check Out?')">Check Out</button>
                                        </form>
                                    @else
                                        <form action="{{ url('admin/meeting-room/'.$booking->id.'/checkin') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success" onclick="return confirm('Check In?')">Check In</button>
                                        </form>
                                    @endif
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
    setInterval(() => location.reload(), 30000);
</script>
@endpush
