@extends('layouts-admin.admin')

@section('title', 'Detail Virtual Office - ' . ($vo->user->company_name ?? 'Client'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="{{ route('admin.virtual-office.index') }}" class="btn btn-sm btn-light border mb-2">
            <ion-icon name="arrow-back-outline" class="align-middle"></ion-icon> Kembali
        </a>
        <h4 class="mb-0">Detail Virtual Office</h4>
        <small class="text-muted">No Order: <span class="fw-semibold text-primary">{{ $vo->order_number }}</span></small>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-primary" onclick="alert('Fitur perpanjangan paket bisa diarahkan ke form pembuatan order baru atau langsung mereset tanggal expired benefit.')">
            <ion-icon name="refresh-circle-outline" class="align-middle"></ion-icon> Perpanjang Paket
        </button>
    </div>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Info Client & Paket --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold"><ion-icon name="business-outline" class="me-2 text-primary"></ion-icon>Informasi Perusahaan</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Nama PT / Perusahaan</div>
                    <div class="col-sm-8 fw-semibold">{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">PIC (Penanggung Jawab)</div>
                    <div class="col-sm-8">{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Email</div>
                    <div class="col-sm-8">{{ $vo->user->email ?? ($vo->form_data['company_email'] ?? '—') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Nomor WhatsApp</div>
                    <div class="col-sm-8">
                        @php $phone = $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? ''); @endphp
                        {{ $phone ?: '—' }}
                        @if($phone)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $phone) }}" target="_blank" class="text-success ms-2" title="Chat via WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Alamat Operasional</div>
                    <div class="col-sm-8">{{ $vo->user->address ?? ($vo->form_data['operational_address'] ?? '—') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">NPWP</div>
                    <div class="col-sm-8">{{ $vo->user->npwp ?? '—' }}</div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small">Bidang Usaha</div>
                    <div class="col-sm-8">{{ $vo->user->business_type ?? ($vo->form_data['business_field'] ?? '—') }}</div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold"><ion-icon name="cube-outline" class="me-2 text-primary"></ion-icon>Detail Paket Virtual Office</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Nama Paket</div>
                    <div class="col-sm-8 fw-bold text-primary">{{ $vo->vo_package }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Harga</div>
                    <div class="col-sm-8">Rp {{ number_format($vo->total_price, 0, ',', '.') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Status</div>
                    <div class="col-sm-8">
                        <span class="badge bg-{{ $vo->vo_status_color }} px-3 py-1">{{ $vo->vo_status }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Tanggal Aktif</div>
                    <div class="col-sm-8">
                        {{ $vo->tanggal_aktif ? \Carbon\Carbon::parse($vo->tanggal_aktif)->format('d F Y') : '—' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 text-muted small">Tanggal Expired</div>
                    <div class="col-sm-8">
                        {{ $vo->tanggal_expired ? \Carbon\Carbon::parse($vo->tanggal_expired)->format('d F Y') : '—' }}
                    </div>
                </div>
                <div class="row mb-0">
                    <div class="col-sm-4 text-muted small">Sisa Masa Aktif</div>
                    <div class="col-sm-8 fw-semibold {{ $vo->sisa_hari <= 30 ? 'text-danger' : 'text-success' }}">
                        {{ $vo->sisa_hari }} Hari
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Benefit & Surat Menyurat --}}
    <div class="col-md-5">
        @if(in_array(auth()->user()->role, ['admin', 'spv', 'admin1']))
        {{-- Update Status --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="fw-bold mb-0"><ion-icon name="create-outline" class="me-2 text-primary"></ion-icon>Update Status Order</h6>
            </div>
            <div class="card-body">
                @php
                $allStatuses = ['draft','waiting_verification','revision','verified','pending','approved','processing','completed','cancelled','rejected'];
                $badge = App\Models\Order::STATUS_MAP[$vo->status]['color'] ?? 'secondary';
                $label = App\Models\Order::STATUS_MAP[$vo->status]['label'] ?? ucfirst($vo->status);
                @endphp
                <p>Status Master: <span class="badge bg-{{ $badge }}" style="color: {{ in_array($badge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">{{ $label }}</span></p>
                <form action="{{ route('admin.orders.update', $vo->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status</label>
                        <select name="status" class="form-select">
                            @foreach($allStatuses as $s)
                            <option value="{{ $s }}" {{ $vo->status == $s ? 'selected' : '' }}>
                                {{ App\Models\Order::STATUS_MAP[$s]['label'] ?? ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Pesan untuk customer...">{{ $vo->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><ion-icon name="gift-outline" class="me-2 text-primary"></ion-icon>Benefit Ruangan</h6>
                <a href="{{ route('admin.orders.show', $vo->id) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Order Induk">Lihat Detail Benefit</a>
            </div>
            <div class="card-body">
                
                {{-- Meeting Room --}}
                <div class="mb-4">
                    <h6 class="fw-semibold mb-2">Meeting Room</h6>
                    @if($vo->benefit_meeting)
                        @php
                            $totalM = $vo->benefit_meeting->total_minutes / 60;
                            $usedM = $vo->benefit_meeting->used_minutes / 60;
                            $remainM = $vo->benefit_meeting->remaining_minutes / 60;
                            $percentM = $totalM > 0 ? ($usedM / $totalM) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Total: {{ $totalM }} Jam / Tahun</span>
                            <span class="fw-semibold text-primary">Terpakai: {{ $usedM }} Jam</span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentM }}%;"></div>
                        </div>
                        <div class="text-end small">
                            Sisa Kuota: <strong class="text-success">{{ $remainM }} Jam</strong>
                        </div>
                    @else
                        <div class="text-muted small">Tidak ada benefit Meeting Room</div>
                    @endif
                </div>

                <hr class="border-light">

                {{-- Podcast Room --}}
                <div class="mb-0">
                    <h6 class="fw-semibold mb-2">Podcast Room</h6>
                    @if($vo->benefit_podcast)
                        @php
                            $totalP = $vo->benefit_podcast->total_minutes / 60;
                            $usedP = $vo->benefit_podcast->used_minutes / 60;
                            $remainP = $vo->benefit_podcast->remaining_minutes / 60;
                            $percentP = $totalP > 0 ? ($usedP / $totalP) * 100 : 0;
                        @endphp
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted">Total: {{ $totalP }} Jam / Tahun</span>
                            <span class="fw-semibold text-primary">Terpakai: {{ $usedP }} Jam</span>
                        </div>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percentP }}%;"></div>
                        </div>
                        <div class="text-end small">
                            Sisa Kuota: <strong class="text-success">{{ $remainP }} Jam</strong>
                        </div>
                    @else
                        <div class="text-muted small">Tidak ada benefit Podcast Room</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold"><ion-icon name="mail-unread-outline" class="me-2 text-primary"></ion-icon>Histori Surat Menyurat</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Subjek</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($correspondences as $surat)
                            <tr>
                                <td class="ps-3">
                                    <a href="{{ route('admin.surat-menyurat.show', $surat->id) }}" class="text-decoration-none fw-semibold">
                                        {{ \Illuminate\Support\Str::limit($surat->subject, 30) }}
                                    </a>
                                </td>
                                <td><small class="text-muted">{{ $surat->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $surat->status_badge }}">{{ $surat->status_label }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted small">Belum ada histori surat menyurat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
