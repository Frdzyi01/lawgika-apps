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
                                <small class="d-block">Total: {{ $b->formatMinutes($b->duration*60) }}</small>
                                <small class="d-block">Dipakai: {{ $b->formatMinutes($b->used_minutes) }}</small>
                                @php $sisa=$b->formatted_remaining_time; $bc=$sisa==='Waktu habis'?'bg-danger':'bg-success'; @endphp
                                <span class="badge {{ $bc }} mt-1">Sisa: {{ $sisa }}</span>
                            </td>
                            <td>
                                @if($b->remaining_minutes<=0)
                                    <span class="badge bg-secondary">Selesai</span>
                                @elseif($b->status==='checkin')
                                    <span class="badge bg-primary">Sedang digunakan</span>
                                @elseif($b->status==='paused'||$b->total_used_minutes>0)
                                    <span class="badge bg-warning text-dark">Berhenti sementara</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status!=='approved')
                                    <span class="text-muted small">Tunggu konfirmasi</span>
                                @elseif($b->remaining_minutes<=0)
                                    <span class="text-muted small">Waktu Habis</span>
                                @elseif($b->status==='checkin')
                                    <form action="{{ url('admin/podcast-room/'.$b->id.'/checkout') }}" method="POST">
                                        @csrf<button class="btn btn-sm btn-warning text-dark" onclick="return confirm('Check Out?')">Check Out</button>
                                    </form>
                                @else
                                    <form action="{{ url('admin/podcast-room/'.$b->id.'/checkin') }}" method="POST">
                                        @csrf<button class="btn btn-sm btn-success" onclick="return confirm('Check In?')">Check In</button>
                                    </form>
                                @endif
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
<script>setInterval(()=>location.reload(),30000);</script>
@endpush
