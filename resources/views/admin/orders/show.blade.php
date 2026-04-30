@extends('layouts-admin.admin')

@section('title', 'Detail Pesanan')

@section('content')
<style>
    .doc-type-card {
        border-radius: 12px;
        border: 1.5px solid #e5e7eb;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    .doc-type-header {
        background: #f8fafc;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
    }

    .doc-file-row {
        padding: 10px 16px;
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s;
    }

    .doc-file-row:last-child {
        border-bottom: 0;
    }

    .doc-file-row:hover {
        background: #f8fafc;
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .order-detail-table td {
        color: inherit !important;
    }
</style>

<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3">← Kembali</a>
    <h4 class="mb-0">Detail Pesanan</h4>
    <small class="text-muted">{{ $order->order_number }}</small>
</div>

@foreach(['success','error'] as $msg)
@if(session($msg))
<div class="alert alert-{{ $msg==='success'?'success':'danger' }} alert-dismissible fade show">
    {{ session($msg) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@endforeach

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-danger"><i class="fa fa-circle-xmark me-2"></i>Tolak Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Dokumen: <strong id="rejectDocName"></strong></p>
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4"
                        placeholder="Jelaskan mengapa dokumen ini ditolak..." required minlength="5"></textarea>
                    <small class="text-muted">Wajib diisi. Alasan ini akan ditampilkan ke client.</small>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-xmark me-1"></i>Tolak Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-md-8">

        {{-- Order Info --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Informasi Pesanan</h6>
            </div>
            <div class="card-body px-4">
                <table class="table table-borderless text-body order-detail-table">
                    <tr>
                        <td width="40%" class="text-muted">No. Pesanan</td>
                        <td class="fw-semibold">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Layanan</td>
                        <td class="fw-semibold">{{ $order->service_name ?? ($order->service->name ?? '—') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Customer</td>
                        <td>{{ $order->user->name ?? '—' }}<br>
                            <small class="text-muted">{{ $order->user->email ?? '' }}</small><br>
                            <small class="text-muted">{{ $order->user->phone ?? '' }}</small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($order->notes)
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td>{{ $order->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- PT Professional data block --}}
        @if(!empty($order->form_data['director_name']))
        @php
        $ptProLabels = ['director_name'=>'Nama Direktur','director_phone'=>'No. HP Direktur',
        'company_name'=>'Nama Perusahaan','company_email'=>'Email Perusahaan',
        'pic_name'=>'Nama PIC','pic_phone'=>'No. HP PIC',
        'business_field'=>'Bidang Usaha','operational_address'=>'Alamat Operasional'];
        @endphp
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #4e0516!important">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0"><i class="fa fa-building me-2" style="color:#4e0516"></i>Data PT Perorangan Professional</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <table class="table table-borderless text-body order-detail-table">
                    @foreach($ptProLabels as $key => $label)
                    @if(!empty($order->form_data[$key]))
                    <tr>
                        <td width="40%" class="text-muted">{{ $label }}</td>
                        <td class="fw-semibold">{{ $order->form_data[$key] }}</td>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        {{-- Payment Proof --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Bukti Pembayaran</h6>
                <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
            </div>
            <div class="card-body px-4 pb-4">
                @if($order->payment_proof)
                <div class="mb-3">
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-eye me-1"></i>Lihat Bukti Pembayaran
                    </a>
                </div>
                @else
                <p class="text-muted mb-3">Belum ada bukti pembayaran.</p>
                @endif
                <form action="{{ route('admin.orders.payment-status', $order->id) }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <label class="fw-semibold mb-0" style="white-space:nowrap">Ubah Status:</label>
                    <select name="payment_status" class="form-select form-select-sm" style="max-width:200px">
                        @foreach(App\Models\Order::PAYMENT_STATUSES as $key => $data)
                        <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $data['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        {{-- ── DOKUMEN (grouped by document_type, dynamic) ── --}}
        @if($documentSummary && count($documentSummary) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="fa fa-file-shield me-2 text-muted"></i>Dokumen Persyaratan</h6>
                @php
                $fulfilledCount = collect($documentSummary)->filter(fn($d) => $d['is_fulfilled'])->count();
                $totalRequired = count($documentSummary);
                @endphp
                <span class="badge {{ $fulfilledCount === $totalRequired ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $fulfilledCount }}/{{ $totalRequired }} Terpenuhi
                </span>
            </div>
            <div class="card-body px-4 pb-4 pt-3">
                @foreach($documentSummary as $docType => $summary)
                @php
                $req = $summary['requirement'];
                $docs = $summary['documents'];
                $approved = $summary['approved_count'];
                $fulfilled= $summary['is_fulfilled'];
                $headerColor = $fulfilled ? '#10b981' : ($docs->where('status','rejected')->count() > 0 ? '#ef4444' : '#f59e0b');
                @endphp
                <div class="doc-type-card">
                    <div class="doc-type-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-dot" style="background:{{ $headerColor }}"></span>
                            <strong style="font-size:.9rem">{{ $req->label }}</strong>
                            <code style="font-size:.72rem;color:#6b7280">{{ $req->document_type }}</code>
                        </div>
                        <span class="badge {{ $fulfilled ? 'bg-success' : 'bg-secondary' }}" style="font-size:.72rem; color: #fff !important;">
                            {{ $approved }}/{{ $req->min_required }} approved
                        </span>
                    </div>

                    @if($docs->count() > 0)
                    @foreach($docs as $doc)
                    @php
                    $sc = match($doc->status) {
                    'approved','verified' => 'success',
                    'rejected' => 'danger',
                    default => 'warning',
                    };
                    $sl = match($doc->status) {
                    'approved','verified' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    default => 'Menunggu',
                    };
                    $si = match($doc->status) {
                    'approved','verified' => 'fa-circle-check',
                    'rejected' => 'fa-circle-xmark',
                    default => 'fa-clock',
                    };
                    @endphp
                    <div class="doc-file-row">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            {{-- File info --}}
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa {{ $si }} text-{{ $sc }}"></i>
                                <div>
                                    <a href="{{ asset('storage/' . $doc->path) }}" target="_blank"
                                        class="fw-semibold text-decoration-none text-body" style="font-size:.88rem">
                                        {{ $doc->original_name }}
                                    </a>
                                    <div class="text-muted" style="font-size:.72rem">
                                        {{ $doc->created_at->format('d M Y, H:i') }}
                                        @if($doc->approved_at)
                                        · Diproses: {{ $doc->approved_at->format('d M Y, H:i') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            {{-- Actions --}}
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-{{ $sc }}" style="font-size:.72rem; color: {{ $sc === 'warning' ? '#000' : '#fff' }} !important;">
                                    <i class="fa {{ $si }} me-1"></i>{{ $sl }}
                                </span>

                                {{-- Approve --}}
                                @if(!in_array($doc->status, ['approved','verified']))
                                <form action="{{ route('admin.documents.approve', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"
                                        onclick="return confirm('Approve dokumen ini?')" title="Approve">
                                        <i class="fa fa-check me-1"></i>Approve
                                    </button>
                                </form>
                                @endif

                                {{-- Reject (modal) --}}
                                @if($doc->status !== 'rejected')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="openRejectModal('{{ route('admin.documents.reject', $doc->id) }}', '{{ addslashes($doc->original_name) }}')"
                                    title="Reject">
                                    <i class="fa fa-xmark me-1"></i>Reject
                                </button>
                                @endif

                                {{-- Reset --}}
                                @if(in_array($doc->status, ['approved','verified','rejected']))
                                <form action="{{ route('admin.documents.reset', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm"
                                        onclick="return confirm('Reset ke pending?')" title="Reset">
                                        <ion-icon name="arrow-undo-outline" style="font-size: 1.1rem; vertical-align: middle;"></ion-icon>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        {{-- Rejection reason --}}
                        @if($doc->status === 'rejected' && $doc->rejection_reason)
                        <div class="alert alert-danger py-2 px-3 mt-2 mb-0" style="font-size:.82rem;border-radius:8px">
                            <i class="fa fa-comment-dots me-1"></i>
                            <strong>Alasan:</strong> {{ $doc->rejection_reason }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div class="doc-file-row text-muted small">
                        <i class="fa fa-folder-open me-1"></i>Belum ada dokumen untuk tipe ini.
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- Fallback: order tanpa service requirement --}}
        @elseif($order->documents->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Dokumen yang Diunggah</h6>
                <span class="badge bg-secondary" style="color: #fff !important;">{{ $order->documents->count() }} file</span>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex flex-column gap-3">
                    @foreach($order->documents as $doc)
                    @php
                    $sc = match($doc->status) { 'approved','verified'=>'success','rejected'=>'danger',default=>'warning' };
                    $si = match($doc->status) { 'approved','verified'=>'fa-circle-check','rejected'=>'fa-circle-xmark',default=>'fa-clock' };
                    @endphp
                    <div class="border border-{{ $sc }} rounded-3 p-3 bg-{{ $sc }} bg-opacity-10">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary text-uppercase" style="font-size:.7rem; color: #fff !important;">{{ $doc->document_type ?? $doc->type }}</span>
                                <span class="fw-semibold" style="font-size:.9rem">{{ $doc->original_name }}</span>
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="text-muted" style="font-size:.8rem">
                                    <i class="fa fa-eye"></i> Lihat
                                </a>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $sc }}" style="font-size:.78rem; color: {{ $sc === 'warning' ? '#000' : '#fff' }} !important;">
                                    <i class="fa {{ $si }} me-1"></i>{{ ucfirst($doc->status) }}
                                </span>
                                @if(!in_array($doc->status, ['approved','verified']))
                                <form action="{{ route('admin.documents.approve', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf<button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve?')"><i class="fa fa-check me-1"></i>Approve</button>
                                </form>
                                @endif
                                @if($doc->status !== 'rejected')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="openRejectModal('{{ route('admin.documents.reject', $doc->id) }}', '{{ addslashes($doc->original_name) }}')">
                                    <i class="fa fa-xmark me-1"></i>Reject
                                </button>
                                @endif
                            </div>
                        </div>
                        @if($doc->status === 'rejected' && $doc->rejection_reason)
                        <div class="alert alert-danger py-2 px-3 mt-2 mb-0" style="font-size:.82rem;border-radius:8px">
                            <strong>Alasan:</strong> {{ $doc->rejection_reason }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /col-md-8 --}}

    {{-- RIGHT --}}
    <div class="col-md-4">

        {{-- Update Status --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Update Status Order</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @php
                $allStatuses = ['draft','waiting_verification','revision','verified','pending','approved','processing','completed','cancelled','rejected'];
                $badge = App\Models\Order::STATUS_MAP[$order->status]['color'] ?? 'secondary';
                $label = App\Models\Order::STATUS_MAP[$order->status]['label'] ?? ucfirst($order->status);
                @endphp
                <p>Status: <span class="badge bg-{{ $badge }}" style="color: {{ in_array($badge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">{{ $label }}</span></p>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status</label>
                        <select name="status" class="form-select">
                            @foreach($allStatuses as $s)
                            <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>
                                {{ App\Models\Order::STATUS_MAP[$s]['label'] ?? ucfirst($s) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="3"
                            placeholder="Pesan untuk customer...">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        {{-- Room Benefit --}}
        @php
        use App\Models\RoomBenefit;
        $packageName = $order->service_name ?? ($order->service?->name ?? '');
        $existingBenefit = RoomBenefit::where('order_id', $order->id)->first();

        // ✅ FIXED: use isEligibleForOrder() which validates BOTH service AND package.
        // Previously only checked package slug (causing CV Eksklusif to pass).
        $isEligible = RoomBenefit::isEligibleForOrder($order);
        @endphp
        @if($isEligible)
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754!important">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2">
                <i class="fa fa-door-open text-success"></i>
                <h6 class="fw-bold mb-0">Approve Benefit Ruangan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <p class="text-muted small mb-3">Paket <strong>{{ $packageName }}</strong> berhak mendapatkan <strong>60 jam</strong> akses ruangan (Meeting + Podcast) berlaku 1 tahun.</p>
                @if($existingBenefit)
                <div class="alert alert-success py-2 px-3 mb-0" style="font-size:.88rem">
                    <i class="fa fa-circle-check me-1"></i><strong>Benefit sudah diaktifkan</strong><br>
                    <span class="text-muted">
                        Disetujui: {{ $existingBenefit->created_at->format('d M Y, H:i') }}<br>
                        Dipakai: {{ RoomBenefit::formatMinutes($existingBenefit->used_minutes) }}<br>
                        Sisa: <strong>{{ RoomBenefit::formatMinutes($existingBenefit->remaining_minutes) }}</strong><br>
                        Berlaku hingga: {{ $existingBenefit->expired_at?->format('d M Y') ?? '–' }}
                    </span>
                </div>
                @else
                <form action="{{ route('admin.orders.approve-benefit', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100"
                        onclick="return confirm('Aktifkan benefit 60 jam untuk customer ini?\nTindakan ini tidak dapat dibatalkan.')">
                        <i class="fa fa-unlock me-1"></i>Approve Benefit
                    </button>
                </form>
                <p class="text-muted small mt-2 mb-0">⚠️ Satu pesanan hanya bisa di-approve satu kali.</p>
                @endif
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2">
                <i class="fa fa-door-closed text-secondary"></i>
                <h6 class="fw-bold mb-0 text-secondary">Benefit Ruangan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <p class="text-muted small mb-0">
                    Paket <strong>{{ $packageName ?: '–' }}</strong> tidak termasuk benefit ruangan.<br>
                    Hanya paket <b> Pendirian PT</b> <strong>Eksklusif</strong> dan <strong>Enterprise</strong> yang berhak.
                </p>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(url, docName) {
        document.getElementById('rejectForm').action = url;
        document.getElementById('rejectDocName').textContent = docName;
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }
</script>
@endpush
@endsection