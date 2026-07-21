@extends('layouts-admin.admin')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><ion-icon name="business-outline" class="align-middle"></ion-icon> Manajemen Meeting Room</h1>
            <a href="{{ route('admin.meeting-room.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <ion-icon name="add-circle-outline" class="align-middle"></ion-icon> Tambah Reservasi
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

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary"><ion-icon name="list-outline" class="align-middle"></ion-icon> Semua Reservasi Meeting Room</h6>
                
                <div class="d-flex align-items-center">
                    <form action="{{ url()->current() }}" method="GET" class="d-flex me-2">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari client/order..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit"><ion-icon name="search-outline" class="align-middle"></ion-icon></button>
                            @if(request('search'))
                                <a href="{{ url()->current() }}" class="btn btn-outline-danger" title="Reset"><ion-icon name="close-outline" class="align-middle"></ion-icon></a>
                            @endif
                        </div>
                    </form>
                    <span class="badge bg-primary">{{ $bookings->total() }} Reservasi</span>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Client</th>
                                <th>Ruangan</th>
                                <th>Tanggal & Waktu Booking</th>
                                <th>Peserta</th>
                                <th>Pemakaian Waktu</th>
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
                                    {{ $b->name }}
                                    @if($b->user)<br><small class="text-muted">{{ $b->user->email }}</small>@endif
                                </td>
                                <td>
                                    {{ $b->room_name ?? 'Meeting Room Utama' }}
                                </td>
                                <td>
                                    @php
                                        $isPackage = empty($b->date) && empty($b->start_time);
                                    @endphp

                                    @if ($isPackage)
                                        <span class="badge bg-success" style="font-size:0.9rem;">📦 Paket Meeting Room</span>
                                    @else
                                        {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                        <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($isPackage)
                                        -
                                    @else
                                        <span class="badge bg-secondary">{{ $b->participants ?? 1 }} Orang</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="d-block">Total: {{ $b->formatSeconds($b->duration * 3600) }}</small>
                                    <small class="d-block">Dipakai: <span class="used-time-display"
                                            data-status="{{ $b->status }}"
                                            data-used="{{ $b->used_seconds }}">{{ $b->formatted_used_time ?? '0 menit' }}</span></small>
                                    @php
                                        $sisa = $b->formatted_remaining_time ?? '0 menit';
                                        $bc = ($b->is_expired || $sisa === 'Waktu habis') ? 'bg-danger' : 'bg-success';
                                    @endphp
                                    <span class="badge {{ $bc }} mt-1">Sisa: <span class="remaining-time-display" data-status="{{ $b->status }}" data-remaining="{{ $b->remaining_seconds }}">{{ $sisa }}</span></span>
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
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ url('admin/meeting-room/'.$b->id.'/detail') }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Detail</a>

                                        @if($b->source_type === 'benefit' && $b->status === 'pending')
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/benefit-approve') }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui reservasi ini?')">Approve Benefit</button>
                                            </form>
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/benefit-reject') }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-danger w-100" onclick="return confirm('Tolak reservasi ini?')">Reject Benefit</button>
                                            </form>
                                        @endif

                                        @if($b->source_type !== 'benefit' && $b->payment_status === 'pending')
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/approve-payment') }}" method="POST">
                                                @csrf<button class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui Pembayaran?')">Approve Bayar</button>
                                            </form>
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/reject-payment') }}" method="POST">
                                                @csrf<button class="btn btn-sm btn-danger w-100" onclick="return confirm('Tolak Pembayaran?')">Reject Bayar</button>
                                            </form>
                                        @endif

                                        @if($b->status === 'approved' || $b->status === 'paused')
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/checkin') }}" method="POST">
                                                @csrf<button class="btn btn-sm btn-success w-100" onclick="return confirm('Check In?')">Check In</button>
                                            </form>
                                        @elseif($b->status === 'checkin')
                                            <form action="{{ url('admin/meeting-room/'.$b->id.'/checkout') }}" method="POST">
                                                @csrf<button class="btn btn-sm btn-warning text-dark w-100" onclick="return confirm('Check Out?')">Check Out</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">Belum ada data reservasi meeting room.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $bookings->links('pagination::bootstrap-5') }}
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
