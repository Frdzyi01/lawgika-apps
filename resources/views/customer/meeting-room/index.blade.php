@extends('layouts-customer.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('customer.mr.index.title') }}</h1>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><ion-icon name="list-outline" class="align-middle"></ion-icon> Riwayat Reservasi Meeting Room</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Ruangan</th>
                                <th>Tanggal & Jam Booking</th>
                                <th>Durasi</th>
                                <th>Peserta</th>
                                <th>Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                            <tr>
                                <td><code style="font-size:.8rem;">{{ $b->order_number ?? '#'.$b->id }}</code></td>
                                <td>
                                    {{ $b->room_name ?? 'Meeting Room Utama' }}
                                </td>
                                <td>
                                    @if(empty($b->date) && empty($b->start_time))
                                        <span class="badge bg-success" style="font-size:0.9rem;">📦 Paket Meeting Room</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                        <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $b->duration }} Jam
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $b->participants ?? 1 }} Orang</span>
                                </td>
                                <td>
                                    @if($b->source_type === 'benefit')
                                        <span class="badge bg-info text-dark">Paket PT</span>
                                        @if($b->benefit)
                                        <br><small class="text-muted">{{ $b->benefit->paket }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Manual</span>
                                        @if($b->payment_method)
                                        <br><small class="text-muted fw-bold">{{ $b->payment_method }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($b->is_expired)
                                        <span class="badge bg-danger">❌ Expired</span>
                                    @elseif($b->source_type === 'manual' && $b->remaining_seconds <= 0 && $b->status !== 'selesai' && $b->payment_status === 'approved')
                                        <span class="badge bg-danger">⛔ Waktu Habis</span>
                                    @elseif($b->status === 'checkin')
                                        <span class="badge bg-primary">🔵 Sedang Digunakan</span>
                                    @elseif($b->status === 'paused')
                                        <span class="badge bg-warning text-dark">⏳ Berhenti sementara</span>
                                    @elseif($b->status === 'selesai')
                                        <span class="badge bg-dark">✔ Selesai</span>
                                    @elseif($b->status === 'approved')
                                        <span class="badge bg-success">✅ Menunggu Check In</span>
                                    @elseif($b->status === 'rejected')
                                        <span class="badge bg-danger">❌ Ditolak</span>
                                    @elseif($b->status === 'pending')
                                        @if($b->source_type === 'benefit')
                                            <span class="badge bg-warning text-dark">⏳ Menunggu Approval</span>
                                        @else
                                            @if($b->payment_status === 'pending')
                                                <span class="badge bg-warning text-dark">⏳ Pending Pembayaran</span>
                                            @else
                                                <span class="badge bg-warning text-dark">⏳ Pending</span>
                                            @endif
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('dashboard/meeting-room/'.$b->id.'/detail') }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">{{ __('customer.mr.index.no_history') }}</td>
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
