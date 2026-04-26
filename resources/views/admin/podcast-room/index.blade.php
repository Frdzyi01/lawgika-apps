@extends('layouts-admin.admin')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manajemen Ruang Podcast</h1>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">🎙️ Daftar Reservasi Ruang Podcast</h6>
            <span class="badge bg-primary">{{ $bookings->count() }} Reservasi</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Pemesan</th>
                            <th>Judul Podcast</th>
                            <th>Waktu & Durasi</th>
                            <th>Peserta</th>
                            <th>Total</th>
                            <th>Bukti Bayar</th>
                            <th>Status Bayar</th>
                            <th>Pemakaian</th>
                            <th>Status Ruangan</th>
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
                            <td>{{ $b->podcast_title ?? '–' }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                <small class="text-muted">{{ $b->duration }} Jam</small>
                            </td>
                            <td>{{ $b->participants }} Orang</td>
                            <td>Rp {{ number_format($b->total_price,0,',','.') }}</td>
                            <td>
                                @if($b->payment_proof)
                                <a href="{{ asset('storage/'.$b->payment_proof) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$b->payment_proof) }}" style="width:65px;height:65px;object-fit:cover;border-radius:8px;border:1px solid #e2e8f0;">
                                </a>
                                @else<span class="text-muted small">–</span>@endif
                            </td>
                            <td>
                                @if($b->payment_status==='approved')
                                    <span class="badge bg-success">✅ Disetujui</span>
                                @elseif($b->payment_status==='rejected')
                                    <span class="badge bg-danger">❌ Ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Pending</span>
                                @endif
                                @if($b->payment_status==='pending')
                                <div class="mt-1 d-flex gap-1">
                                    <form action="{{ url('admin/podcast-room/'.$b->id.'/approve-payment') }}" method="POST">
                                        @csrf<button class="btn btn-xs btn-success py-0 px-2" style="font-size:.72rem;" onclick="return confirm('Setujui?')">Approve</button>
                                    </form>
                                    <form action="{{ url('admin/podcast-room/'.$b->id.'/reject-payment') }}" method="POST">
                                        @csrf<button class="btn btn-xs btn-danger py-0 px-2" style="font-size:.72rem;" onclick="return confirm('Tolak?')">Reject</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                            <td>
                                <small class="d-block">Total: {{ $b->formatSeconds($b->duration*3600) }}</small>
                                <small class="d-block">Dipakai: <span class="used-time-display" data-status="{{ $b->status }}" data-used="{{ $b->used_seconds }}">{{ $b->formatted_used_time }}</span></small>
                                @php 
                                    $sisa=$b->formatted_remaining_time; 
                                    $bc='bg-success';
                                    if ($b->is_expired || $sisa==='Waktu habis') $bc='bg-danger';
                                @endphp
                                <span class="badge {{ $bc }} mt-1">Sisa: <span class="remaining-time-display" data-status="{{ $b->status }}" data-remaining="{{ $b->remaining_seconds }}">{{ $sisa }}</span></span>
                            </td>
                            <td>
                                @if($b->is_expired)
                                    <span class="badge bg-danger" title="Expired setelah 1 tahun dari reservasi dibuat">❌ Expired</span>
                                @elseif($b->remaining_seconds<=0)
                                    <span class="badge bg-danger">⛔ Waktu Habis</span>
                                @elseif($b->status==='checkin')
                                    <span class="badge bg-success">✅ Aktif</span>
                                @elseif($b->status==='paused'||$b->used_seconds>0)
                                    <span class="badge bg-warning text-dark">⏳ Berhenti sementara</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ url('admin/podcast-room/'.$b->id.'/detail') }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Lihat Data</a>
                                    @if($b->payment_status!=='approved')
                                        <span class="text-muted small text-center mt-1">Tunggu konfirmasi</span>
                                    @elseif($b->is_expired)
                                        <span class="text-muted small text-danger fw-bold text-center mt-1">Expired</span>
                                    @elseif($b->remaining_seconds<=0)
                                        <span class="text-muted small text-danger fw-bold text-center mt-1">Waktu Habis</span>
                                    @elseif($b->status==='checkin')
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/checkout') }}" method="POST">
                                            @csrf<button class="btn btn-sm btn-warning text-dark w-100" onclick="return confirm('Check Out?')">Check Out</button>
                                        </form>
                                    @else
                                        <form action="{{ url('admin/podcast-room/'.$b->id.'/checkin') }}" method="POST">
                                            @csrf<button class="btn btn-sm btn-success w-100" onclick="return confirm('Check In?')">Check In</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="11" class="text-center">Belum ada data reservasi ruang podcast.</td></tr>
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
