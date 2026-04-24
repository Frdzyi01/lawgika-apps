@extends('layouts-customer.app')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Histori Reservasi Ruang Podcast</h1>
        <a href="{{ url('/sewa-ruang-podcast') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Buat Reservasi Baru
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">🎙️ Reservasi Ruang Podcast Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No. Order</th>
                            <th>Waktu & Paket</th>
                            <th>Judul Podcast</th>
                            <th>Total</th>
                            <th>Status Pembayaran</th>
                            <th>Pemakaian</th>
                            <th>Status Ruangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookings as $b)
                        <tr>
                            <td><code style="font-size:.8rem;">{{ $b->order_number ?? '#'.$b->id }}</code></td>
                            <td>
                                {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                <small class="text-muted">{{ $b->duration }} Jam | {{ $b->participants }} Orang</small>
                            </td>
                            <td>{{ $b->podcast_title ?? '–' }}</td>
                            <td>Rp {{ number_format($b->total_price,0,',','.') }}</td>
                            <td>
                                @if($b->payment_status==='approved')
                                    <span class="badge bg-success">✅ Pembayaran diterima</span>
                                @elseif($b->payment_status==='rejected')
                                    <span class="badge bg-danger">❌ Pembayaran ditolak</span>
                                @else
                                    <span class="badge bg-warning text-dark">⏳ Menunggu konfirmasi</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status==='approved')
                                    <small class="d-block">Total: {{ $b->formatMinutes($b->duration*60) }}</small>
                                    <small class="d-block">Dipakai: {{ $b->formatMinutes($b->used_minutes) }}</small>
                                    @php $sisa=$b->formatted_remaining_time; $bc=$sisa==='Waktu habis'?'bg-danger':'bg-success'; @endphp
                                    <span class="badge {{ $bc }} mt-1">Sisa: {{ $sisa }}</span>
                                @else
                                    <span class="text-muted small">–</span>
                                @endif
                            </td>
                            <td>
                                @if($b->payment_status!=='approved')
                                    <span class="badge bg-secondary">Belum aktif</span>
                                @elseif($b->remaining_minutes<=0)
                                    <span class="badge bg-secondary">Selesai</span>
                                @elseif($b->status==='checkin')
                                    <span class="badge bg-primary">Sedang Digunakan</span>
                                @elseif($b->status==='paused'||$b->total_used_minutes>0)
                                    <span class="badge bg-warning text-dark">Berhenti sementara</span>
                                @else
                                    <span class="badge bg-info text-dark">Siap digunakan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">Anda belum memiliki reservasi ruang podcast.</td></tr>
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
