@extends('layouts-customer.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">{{ __('customer.mr.index.title') }}</h1>
        </div>
        @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    {{-- Room Benefit / Quota Overview Card --}}
    @if(isset($benefits) && $benefits->count() > 0)
        @foreach($benefits as $ben)
            @php
                $totH = round($ben->total_minutes / 60, 1);
                $usdH = round($ben->used_minutes / 60, 1);
                $remH = round($ben->remaining_minutes / 60, 1);
                $pct  = $ben->total_minutes > 0 ? ($ben->used_minutes / $ben->total_minutes) * 100 : 0;
            @endphp
            <div class="card shadow-sm border-0 mb-4" style="border-radius:16px; border-left: 5px solid #2563eb !important; background:#ffffff;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            @php
                                $isBenefitFromPT = !empty($ben->order_id) || ($ben->total_minutes == 48 * 60);
                                $badgeLabel = $isBenefitFromPT ? 'Paket Benefit Meeting Room (48 Jam)' : 'Paket Meeting Room (60 Jam)';
                            @endphp
                            <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill mb-1" style="font-size:0.75rem;">
                                <i class="fa-solid {{ $isBenefitFromPT ? 'fa-gift' : 'fa-box-archive' }} me-1"></i> {{ $badgeLabel }}
                            </span>
                            <h5 class="fw-bold mb-0 text-dark" style="font-size:1.15rem;">
                                <i class="fa-solid fa-door-open me-2 text-primary"></i> {{ $ben->paket }}
                            </h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            @if($ben->expired_at)
                                <span class="badge bg-light text-muted border rounded-pill px-3 py-2" style="font-size:0.8rem;">
                                    <i class="fa-regular fa-calendar-check me-1 text-primary"></i> Berlaku s.d. {{ \Carbon\Carbon::parse($ben->expired_at)->format('d M Y') }}
                                </span>
                            @endif
                            <a href="{{ route('meeting-room.order') }}" class="btn btn-sm btn-primary px-3 py-2 fw-bold rounded-pill shadow-sm">
                                <i class="fa-solid fa-calendar-plus me-1"></i> Ajukan Reservasi Baru
                            </a>
                        </div>
                    </div>

                    <div class="row g-3 my-2">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block mb-1 font-weight-bold text-uppercase" style="font-size:0.72rem;">Sisa Kuota Waktu</small>
                                <div class="fs-4 fw-bold text-success">{{ $ben->remaining_minutes > 0 ? \App\Models\RoomBenefit::formatMinutes($ben->remaining_minutes) : 'Habis' }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block mb-1 font-weight-bold text-uppercase" style="font-size:0.72rem;">Kuota Terpakai</small>
                                <div class="fs-4 fw-bold text-primary">{{ \App\Models\RoomBenefit::formatMinutes($ben->used_minutes) }}</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3 border">
                                <small class="text-muted d-block mb-1 font-weight-bold text-uppercase" style="font-size:0.72rem;">Total Kuota Paket</small>
                                <div class="fs-4 fw-bold text-dark">{{ \App\Models\RoomBenefit::formatMinutes($ben->total_minutes) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between text-muted small mb-1">
                            <span>Progress Penggunaan Kuota</span>
                            <span class="fw-bold">{{ round($pct) }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 6px; background-color: #f1f5f9;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $pct }}%;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 px-3 mt-3 mb-0 small rounded-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-info fs-5 text-info"></i>
                        <div>
                            <strong>Alur Penggunaan:</strong> Klik tombol <strong>"Ajukan Reservasi Baru"</strong> untuk memilih jadwal dan durasi. Setelah disetujui admin, sesi berstatus <em>Siap Check In</em> dan kuota otomatis dipotong saat Check Out selesai.
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('meeting-room.order') }}" class="btn btn-sm btn-primary px-3 py-2 fw-bold rounded-pill shadow-sm">
                <i class="fa-solid fa-calendar-plus me-1"></i> Buat Reservasi Meeting Room
            </a>
        </div>
    @endif

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
                                <th>Tipe Paket / Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $b)
                            <tr>
                                <td>
                                    <code style="font-size:.8rem;">{{ $b->order_number ?? '#MR-'.str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</code>
                                </td>
                                <td>
                                    {{ $b->room_name ?? 'Ruang Meeting Utama' }}
                                    @if($b->keperluan)
                                    <br><small class="text-muted">Keperluan: {{ $b->keperluan }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($b->date)
                                        {{ \Carbon\Carbon::parse($b->date)->format('d M Y') }}
                                        @if($b->start_time)
                                            <small class="d-block">{{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark border">Paket Kuota (Fleksibel)</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $b->duration }} Jam
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $b->participants ?? 1 }} Orang</span>
                                </td>
                                <td>
                                    @if($b->source_type === 'benefit' || $b->benefit_id || $b->duration == 48)
                                        <span class="badge bg-info text-dark">Paket Benefit Meeting Room (48 Jam)</span>
                                        @if($b->benefit)
                                        <br><small class="text-muted">{{ $b->benefit->paket }}</small>
                                        @endif
                                    @elseif($b->duration == 60)
                                        <span class="badge bg-primary">Paket Meeting Room (60 Jam)</span>
                                    @else
                                        <span class="badge bg-secondary">Paket Meeting Room ({{ $b->duration }} Jam)</span>
                                        @if($b->payment_method)
                                        <br><small class="text-muted fw-bold">{{ $b->payment_method }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($b->is_expired)
                                        <span class="badge bg-danger">❌ Expired</span>
                                    @elseif($b->source_type === 'manual' && $b->remaining_seconds <= 0 && $b->status !== 'selesai' && $b->payment_status === 'approved')
                                        <span class="badge bg-danger">⛔ Kuota Habis</span>
                                    @elseif($b->status === 'checkin')
                                        <span class="badge bg-primary live-checkin-badge d-inline-flex align-items-center gap-1 shadow-sm">
                                            <span class="spinner-grow spinner-grow-sm text-white" style="width:0.6rem; height:0.6rem;"></span>
                                            <span>Digunakan (<span class="row-live-timer" data-checkin="{{ $b->checkin_at ? \Carbon\Carbon::parse($b->checkin_at)->timestamp : now()->timestamp }}">00:00</span>)</span>
                                        </span>
                                    @elseif($b->status === 'paused')
                                        <span class="badge bg-warning text-dark">⏳ Berhenti sementara</span>
                                    @elseif($b->status === 'selesai')
                                        <span class="badge bg-dark">✔ Selesai</span>
                                    @elseif($b->status === 'pending')
                                        <span class="badge bg-warning text-dark">⏳ Menunggu Approval Admin</span>
                                    @elseif($b->status === 'rejected' || $b->payment_status === 'rejected')
                                        <span class="badge bg-danger">❌ Ditolak</span>
                                    @elseif($b->status === 'approved')
                                        @if(!empty($b->start_time))
                                            <span class="badge bg-success">✅ Siap Check In</span>
                                        @else
                                            <span class="badge bg-info text-dark">ℹ Kuota Aktif</span>
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
        function formatLiveBadgeTime(seconds) {
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = Math.floor(seconds % 60);
            if (h > 0) {
                return h + "j " + (m < 10 ? '0' : '') + m + "m " + (s < 10 ? '0' : '') + s + "d";
            }
            return (m < 10 ? '0' : '') + m + ":" + (s < 10 ? '0' : '') + s;
        }

        function tickRowTimers() {
            const nowTs = Math.floor(Date.now() / 1000);
            document.querySelectorAll('.row-live-timer').forEach(el => {
                const cTs = parseInt(el.dataset.checkin);
                if (cTs) {
                    const elapsed = Math.max(0, nowTs - cTs);
                    el.innerText = formatLiveBadgeTime(elapsed);
                }
            });
        }
        setInterval(tickRowTimers, 1000);
        tickRowTimers();
    </script>
@endpush
