@extends('layouts-admin.admin')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><ion-icon name="mic-outline" class="align-middle"></ion-icon> Manajemen Ruang Podcast</h1>
        <a href="{{ route('admin.podcast-room.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <ion-icon name="add-circle-outline" class="align-middle"></ion-icon> Tambah Reservasi
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @if(isset($pendingReservations) && $pendingReservations->count() > 0)
    <!-- Tabel Notifikasi Pengajuan Reservasi Baru (Tampil Hanya Jika Ada Client Yang Mengajukan Reservasi) -->
    <div class="card border-warning shadow mb-4" style="border-left: 5px solid #ffc107;">
        <div class="card-header py-3 bg-warning text-dark d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-dark">
                <i class="fas fa-bell me-2"></i> 🔔 NOTIFIKASI: Ada {{ $pendingReservations->count() }} Pengajuan Reservasi Baru!
            </h6>
            <span class="badge bg-dark text-white">Membutuhkan Konfirmasi Admin</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Client</th>
                            <th>Tanggal & Waktu yang Diajukan</th>
                            <th>Durasi & Judul</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingReservations as $p)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $p->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $p->name ?? ($p->user ? $p->user->name : '-') }}</div>
                                <div class="small text-muted">{{ $p->email ?? '-' }}</div>
                            </td>
                            <td>
                                @if($p->date)
                                    <div class="fw-bold text-dark"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($p->date)->format('d M Y') }}</div>
                                    <div class="small text-primary"><i class="fas fa-clock me-1"></i> {{ substr($p->start_time, 0, 5) }} WIB</div>
                                @else
                                    <span class="badge bg-secondary">Belum Memilih Tanggal</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $p->duration }} Jam</div>
                                <div class="small text-muted">{{ $p->podcast_title ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <form action="{{ url('admin/podcast-room/' . $p->id . '/benefit-approve') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success fw-bold" onclick="return confirm('Setujui pengajuan reservasi ini?')">
                                            <i class="fas fa-check me-1"></i> Setujui Reservasi
                                        </button>
                                    </form>
                                    <a href="{{ url('admin/podcast-room/' . $p->id . '/detail') }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary"><ion-icon name="list-outline" class="align-middle"></ion-icon> Semua Reservasi Podcast</h6>
            
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
                            <th>Studio</th>
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
                                {{ $b->room_name ?? 'Podcast Room Utama' }}
                                @if($b->podcast_title)
                                <br><small class="text-muted">Judul: {{ $b->podcast_title }}</small>
                                @endif
                            </td>
                            <td>
                                @if (!empty($b->date))
                                    {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                    @if($b->start_time)
                                        <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                    @endif
                                @elseif ($b->source_type === 'benefit')
                                    <span class="badge bg-info text-dark" style="font-size:0.85rem;"><i class="fas fa-gift me-1"></i> Benefit PT (Belum Terjadwal)</span>
                                @else
                                    <span class="badge bg-primary" style="font-size:0.85rem;"><i class="fas fa-box me-1"></i> Pembelian Paket ({{ $b->duration }} Jam)</span>
                                @endif
                            </td>
                            <td>
                                @if (empty($b->date))
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
                                @elseif($b->status === 'approved' || $b->payment_status === 'approved')
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
                                    <a href="{{ url('admin/podcast-room/'.$b->id.'/detail') }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Detail</a>

                                    @if($b->source_type === 'benefit' && $b->status === 'pending')
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/benefit-approve') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui reservasi ini?')">Approve Benefit</button>
                                        </form>
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/benefit-reject') }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-danger w-100" onclick="return confirm('Tolak reservasi ini?')">Reject Benefit</button>
                                        </form>
                                    @endif

                                    @if($b->source_type !== 'benefit' && $b->payment_status === 'pending')
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/approve-payment') }}" method="POST">
                                            @csrf<button class="btn btn-sm btn-success w-100" onclick="return confirm('Setujui Pembayaran?')">Approve Bayar</button>
                                        </form>
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/reject-payment') }}" method="POST">
                                            @csrf<button class="btn btn-sm btn-danger w-100" onclick="return confirm('Tolak Pembayaran?')">Reject Bayar</button>
                                        </form>
                                    @endif

                                    @if(($b->status === 'approved' || $b->status === 'paused' || $b->payment_status === 'approved' || $b->source_type === 'benefit') && $b->status !== 'checkin' && $b->status !== 'selesai' && $b->status !== 'rejected')
                                        <button type="button" class="btn btn-sm btn-success w-100 fw-bold" data-bs-toggle="modal" data-bs-target="#checkinModalPR{{ $b->id }}">
                                            <i class="fa-solid fa-right-to-bracket me-1"></i> Check In
                                        </button>

                                        <!-- Modal Check In Podcast Room -->
                                        <div class="modal fade" id="checkinModalPR{{ $b->id }}" tabindex="-1" aria-labelledby="checkinModalLabelPR{{ $b->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content text-start">
                                                    <form action="{{ url('admin/podcast-room/'.$b->id.'/checkin') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-header bg-success text-white">
                                                            <h5 class="modal-title fw-bold" id="checkinModalLabelPR{{ $b->id }}">
                                                                <i class="fa-solid fa-microphone me-2"></i> Check In Ruang Podcast
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Info Client & Tanggal Booking -->
                                                            <div class="card bg-light border-0 mb-3">
                                                                <div class="card-body py-2 px-3 small">
                                                                    <div class="row">
                                                                        <div class="col-6"><strong>Client:</strong> {{ $b->user->name ?? $b->name }}</div>
                                                                        <div class="col-6"><strong>Tanggal Booking:</strong> {{ $b->created_at ? \Carbon\Carbon::parse($b->created_at)->format('d M Y') : ($b->date ? \Carbon\Carbon::parse($b->date)->format('d M Y') : date('d M Y')) }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- 1. Ruangan -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Ruangan</label>
                                                                <input type="text" name="room_name" class="form-control bg-light" value="Podcast Studio Lawgika Office, World Capital Tower Lt. 38 Unit 6-7" readonly>
                                                            </div>

                                                            <!-- 2. Tanggal Meeting / Penggunaan -->
                                                            <div class="mb-3">
                                                                <label class="form-label fw-bold">Tanggal Meeting <span class="text-danger">*</span></label>
                                                                <input type="date" name="date" class="form-control" value="{{ $b->date ? \Carbon\Carbon::parse($b->date)->format('Y-m-d') : date('Y-m-d') }}" required>
                                                            </div>

                                                            <!-- 3. Waktu Pemakaian (Mulai & Selesai) -->
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label fw-bold">Jam Mulai <span class="text-danger">*</span></label>
                                                                    <input type="time" name="start_time" class="form-control" value="{{ $b->start_time ? \Carbon\Carbon::parse($b->start_time)->format('H:i') : date('H:i') }}" required>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label fw-bold">Jam Selesai <span class="text-danger">*</span></label>
                                                                    <input type="time" name="end_time" class="form-control" value="{{ $b->end_time ? \Carbon\Carbon::parse($b->end_time)->format('H:i') : date('H:i', strtotime('+1 hour')) }}" required>
                                                                </div>
                                                            </div>

                                                            <div class="alert alert-info py-2 small mb-0">
                                                                <i class="fa-solid fa-circle-info me-1"></i> WhatsApp notifikasi konfirmasi Check In akan otomatis dikirimkan ke client setelah disimpan.
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                            <button type="submit" class="btn btn-success fw-bold">
                                                                <i class="fa-solid fa-paper-plane me-1"></i> Check In & Kirim WhatsApp
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($b->status === 'checkin')
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/checkout') }}" method="POST">
                                            @csrf<button class="btn btn-sm btn-warning text-dark w-100" onclick="return confirm('Check Out?')">Check Out</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data reservasi ruang podcast.</td>
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