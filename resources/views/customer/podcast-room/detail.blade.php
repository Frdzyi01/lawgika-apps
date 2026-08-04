@extends('layouts-customer.app')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Detail Reservasi Podcast Room</h1>
        <a href="{{ url('dashboard/podcast-room') }}" class="btn btn-sm btn-secondary shadow-sm"><i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali</a>
    </div>

    <div class="row">
        <!-- Informasi Order -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Order</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted" style="width: 40%">Nama Pemesan</td>
                            <td class="fw-bold">{{ $booking->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama User</td>
                            <td class="fw-bold">{{ $booking->user ? $booking->user->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Judul Podcast</td>
                            <td class="fw-bold">{{ $booking->podcast_title ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Order</td>
                            <td class="fw-bold">{{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Expired</td>
                            <td class="fw-bold text-danger">{{ \Carbon\Carbon::parse($booking->created_at)->addYear()->format('d M Y H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Ruangan</td>
                            <td>
                                @if($booking->is_expired)
                                    <span class="badge bg-danger">❌ Expired</span>
                                @elseif($booking->remaining_seconds <= 0)
                                    <span class="badge bg-danger">⛔ Waktu Habis</span>
                                @elseif($booking->status === 'checkin')
                                    <span class="badge bg-primary">🔵 Aktif (Sedang Digunakan)</span>
                                @elseif($booking->status === 'paused' || $booking->used_seconds > 0)
                                    <span class="badge bg-warning text-dark">⏳ Berhenti sementara</span>
                                @elseif($booking->status === 'approved' || $booking->payment_status === 'approved')
                                    <span class="badge bg-success">✅ Menunggu Check In</span>
                                @elseif($booking->status === 'rejected' || $booking->payment_status === 'rejected')
                                    <span class="badge bg-danger">❌ Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Pending</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pemakaian Waktu & Bukti Pembayaran -->
        <div class="col-lg-6 mb-4 d-flex flex-column gap-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pemakaian Waktu</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Total Waktu:</span>
                        <span class="fw-bold">{{ $booking->formatSeconds($booking->duration * 3600) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                        <span class="text-muted">Sudah Dipakai:</span>
                        <span class="fw-bold text-primary used-time-display" data-status="{{ $booking->status }}" data-used="{{ $booking->used_seconds }}">{{ $booking->formatted_used_time }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Sisa Waktu:</span>
                        @php 
                            $sisa = $booking->formatted_remaining_time; 
                            $text_class = ($booking->is_expired || $sisa === 'Waktu habis') ? 'text-danger' : 'text-success';
                        @endphp
                        <span class="fw-bold {{ $text_class }} remaining-time-display" data-status="{{ $booking->status }}" data-remaining="{{ $booking->remaining_seconds }}">{{ $sisa }}</span>
                    </div>
                </div>
            </div>

            <!-- Bukti Pembayaran Card -->
            @php
                $proofPath = $booking->payment_proof ?? ($booking->order ? $booking->order->payment_proof : null);
            @endphp
            <div class="card shadow">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-receipt me-1"></i> Bukti Pembayaran</h6>
                    @if($proofPath)
                        <span class="badge bg-success" style="color:#fff !important;"><i class="fas fa-check-circle me-1"></i> Terunggah</span>
                    @else
                        <span class="badge bg-secondary" style="color:#fff !important;">Order via WA / Direct Approval</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($proofPath)
                        <div class="text-center p-3 bg-light rounded border">
                            <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" title="Klik untuk membuka gambar ukuran penuh">
                                <img src="{{ asset('storage/' . $proofPath) }}" alt="Bukti Pembayaran"
                                    class="img-fluid rounded shadow-sm"
                                    style="max-height: 260px; object-fit: contain; border: 1px solid #e2e8f0; background: #fff;">
                            </a>
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $proofPath) }}" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-external-link-alt me-1"></i> Lihat Gambar Ukuran Penuh
                                </a>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center my-3"><i class="fas fa-info-circle me-1"></i> Bukti pembayaran tidak diunggah langsung (Order via WA / Diproses langsung oleh Admin).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Check In / Out -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Check In / Check Out</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Durasi Sesi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($log->type === 'checkin')
                                        <span class="badge bg-success"><i class="fas fa-sign-in-alt"></i> Check In</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-sign-out-alt"></i> Check Out</span>
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
                                <td colspan="5" class="text-center">Belum ada riwayat penggunaan.</td>
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
