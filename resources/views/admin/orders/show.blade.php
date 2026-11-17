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

    button[data-bs-target="#clientDataCollapse"] .client-toggle-chevron {
        transition: transform .2s ease;
    }

    button[data-bs-target="#clientDataCollapse"]:not(.collapsed) .client-toggle-chevron {
        transform: rotate(180deg);
    }

    button[data-bs-target="#clientDataCollapse"]:not(.collapsed) .client-toggle-label-closed {
        display: none;
    }

    button[data-bs-target="#clientDataCollapse"]:not(.collapsed) .client-toggle-label-open {
        display: inline !important;
    }
</style>

<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-4">
    <div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary mb-2">← Kembali</a>
        <h4 class="mb-0">Detail Pesanan &amp; Verifikasi Transaksi</h4>
        <small class="text-muted">Order: {{ $order->order_number }} &middot; Invoice: <strong>{{ $order->invoice_number ?? '-' }}</strong></small>
        @if($order->trashed())
            <span class="badge bg-dark ms-2">Terhapus (Soft Delete) pada {{ $order->deleted_at->format('d M Y H:i') }}</span>
        @endif
    </div>
    @if(!$order->trashed() && auth()->user()->canDirectlyModifyOrders())
    <div class="d-flex gap-2">
        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="fa fa-pen me-1"></i>Edit Transaksi
        </a>
        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteSingleOrderModal">
            <i class="fa fa-trash me-1"></i>Hapus Pesanan
        </button>
    </div>
    @endif
</div>

@foreach(['success','error'] as $msg)
@if(session($msg))
<div class="alert alert-{{ $msg==='success'?'success':'danger' }} alert-dismissible fade show">
    {{ session($msg) }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@endforeach

@if(auth()->user()->canApproveOrders())
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
@endif

@if(!$order->trashed())
@php
    $docTypeOptions = [];
    if ($documentSummary) {
        foreach ($documentSummary as $dtKey => $dtSummary) {
            $docTypeOptions[$dtKey] = $dtSummary['requirement']->label . ' (' . $dtKey . ')';
        }
    }
    foreach ($order->documents as $existingDoc) {
        $existingKey = $existingDoc->document_type ?: $existingDoc->type;
        if ($existingKey && !array_key_exists($existingKey, $docTypeOptions)) {
            $docTypeOptions[$existingKey] = $existingKey;
        }
    }
@endphp
{{-- Edit / Ganti Dokumen Modal --}}
<div class="modal fade" id="editDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fa fa-pen-to-square text-primary me-2"></i>Edit / Ganti Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editDocumentForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p class="text-muted small mb-3">File saat ini: <strong id="editDocCurrentName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori / Tipe Dokumen</label>
                        <select name="document_type" id="editDocTypeSelect" class="form-select">
                            @foreach($docTypeOptions as $typeKey => $typeLabel)
                            <option value="{{ $typeKey }}">{{ $typeLabel }}</option>
                            @endforeach
                            @if(!array_key_exists('TAMBAHAN', $docTypeOptions))
                            <option value="TAMBAHAN">Dokumen Tambahan (TAMBAHAN)</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload File Pengganti</label>
                        <input type="file" name="file" id="editDocFileInput" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">Biarkan kosong jika hanya mengubah kategori dokumen. Format: PDF, JPG, PNG, DOC, DOCX (maks. 10 MB).</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan Perubahan (Opsional)</label>
                        <textarea name="reason" id="editDocReasonInput" class="form-control" rows="2" placeholder="Contoh: Revisi scan KTP lebih jelas..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Upload Dokumen Persyaratan Modal --}}
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold"><i class="fa fa-upload text-primary me-2"></i>Upload Dokumen Persyaratan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.documents.store', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori / Tipe Dokumen <span class="text-danger">*</span></label>
                        <select name="document_type" id="uploadDocTypeSelect" class="form-select" required>
                            @foreach($docTypeOptions as $typeKey => $typeLabel)
                            <option value="{{ $typeKey }}">{{ $typeLabel }}</option>
                            @endforeach
                            @if(!array_key_exists('TAMBAHAN', $docTypeOptions))
                            <option value="TAMBAHAN">Dokumen Tambahan (TAMBAHAN)</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="uploadDocFileInput" class="form-control" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                        <small class="text-muted">Format: PDF, JPG, PNG, DOC, DOCX (maks. 10 MB).</small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Catatan (Opsional)</label>
                        <textarea name="reason" id="uploadDocReasonInput" class="form-control" rows="2" placeholder="Catatan penambahan dokumen..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload me-1"></i>Upload Dokumen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    {{-- LEFT --}}
    <div class="col-lg-8">

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
                        <td class="text-muted">Nomor Invoice</td>
                        <td class="fw-bold text-primary">{{ $order->invoice_number ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status Pesanan</td>
                        <td>
                            @php
                            $infoBadge = App\Models\Order::STATUS_MAP[$order->status]['color'] ?? 'secondary';
                            $infoLabel = App\Models\Order::STATUS_MAP[$order->status]['label'] ?? ucfirst($order->status);
                            @endphp
                            <span class="badge bg-{{ $infoBadge }}" style="color: {{ in_array($infoBadge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">{{ $infoLabel }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Transaksi</td>
                        <td class="fw-semibold">{{ $order->effective_transaction_date->format('d M Y') }} <small class="text-muted">(Acuan aktivasi benefit)</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Submit</td>
                        <td>{{ ($order->submitted_at ?? $order->created_at)->format('d M Y, H:i') }} @if($order->creator) <small class="text-muted">oleh {{ $order->creator->name }}</small> @endif</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Durasi Paket</td>
                        <td class="fw-semibold">{{ $order->duration_breakdown['summary'] }}</td>
                    </tr>
                    @if(!empty($order->payment_method))
                    <tr>
                        <td class="text-muted">Metode Pembayaran</td>
                        <td class="fw-semibold">{{ $order->payment_method }}</td>
                    </tr>
                    @endif
                    @if($order->approved_at)
                    <tr>
                        <td class="text-muted">Disetujui Oleh</td>
                        <td class="text-success fw-semibold">{{ $order->approver->name ?? 'Admin 3 / SPV' }} pada {{ $order->approved_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
                    @if($order->rejected_at)
                    <tr>
                        <td class="text-muted">Ditolak Terakhir Oleh</td>
                        <td class="text-danger fw-semibold">{{ $order->rejector->name ?? 'Admin 3 / SPV' }} pada {{ $order->rejected_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @endif
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
                        <td class="text-muted">Dibuat di Sistem</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($order->total_price > 0)
                    @php $ppnData = \App\Helpers\PpnHelper::calculate($order->total_price); @endphp
                    <tr>
                        <td class="text-muted">Subtotal</td>
                        <td class="fw-semibold">Rp {{ number_format($ppnData['subtotal'],0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">PPN 11%</td>
                        <td class="fw-semibold">Rp {{ number_format($ppnData['ppn_amount'],0,',','.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-bold">Total Pembayaran</td>
                        <td class="fw-bold text-primary">Rp {{ number_format($ppnData['grand_total'],0,',','.') }}</td>
                    </tr>
                    @endif
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
            <div class="card-header bg-transparent border-0 pt-3 pb-3 px-4">
                <button type="button"
                    class="btn btn-link text-decoration-none text-body p-0 w-100 d-flex align-items-center justify-content-between collapsed"
                    data-bs-toggle="collapse"
                    data-bs-target="#clientDataCollapse"
                    aria-expanded="false"
                    aria-controls="clientDataCollapse"
                    style="box-shadow:none;">
                    <span class="fw-bold d-flex align-items-center flex-wrap gap-2" style="font-size: 1rem;">
                        <span><i class="fa fa-building me-2" style="color:#4e0516"></i>Data Client</span>
                        <span class="badge bg-light text-muted border fw-normal" style="font-size:.72rem;">
                            {{ $order->form_data['company_name'] ?? $order->form_data['director_name'] }}
                        </span>
                    </span>
                    <span class="text-muted small d-flex align-items-center gap-1">
                        <span class="client-toggle-label-closed">Tampilkan</span>
                        <span class="client-toggle-label-open d-none">Sembunyikan</span>
                        <i class="fa fa-chevron-down client-toggle-chevron"></i>
                    </span>
                </button>
            </div>
            <div id="clientDataCollapse" class="collapse">
                <div class="card-body px-4 pb-4 pt-2 border-top">
                    <table class="table table-borderless text-body order-detail-table mb-0">
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
                <div class="mb-3 text-center p-3 bg-light rounded border">
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" title="Klik untuk membuka gambar ukuran penuh">
                        <img src="{{ asset('storage/' . $order->payment_proof) }}" alt="Bukti Pembayaran"
                            class="img-fluid rounded shadow-sm mb-2"
                            style="max-height: 250px; object-fit: contain; border: 1px solid #e2e8f0; background: #fff;">
                    </a>
                    <div>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-external-link-alt me-1"></i>Lihat Gambar Ukuran Penuh
                        </a>
                    </div>
                </div>
                @else
                <p class="text-muted mb-3"><i class="fas fa-info-circle me-1"></i>Belum ada bukti pembayaran.</p>
                @endif
                <form action="{{ route('admin.orders.payment-status', $order->id) }}" method="POST" id="payment_status_form">
                    @csrf
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <label class="fw-semibold mb-0" style="white-space:nowrap">Ubah Status:</label>
                        <select name="payment_status" id="payment_status_select" class="form-select form-select-sm" style="max-width:230px">
                            @foreach(App\Models\Order::PAYMENT_STATUSES as $key => $data)
                            <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $data['label'] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>

                        {{-- Button: Reminder to WA Client (Tampil jika dropdown = 'unpaid', sembunyi jika 'verified'/'rejected') --}}
                        <button type="button" id="payment_reminder_btn" class="btn btn-sm btn-warning text-dark fw-semibold"
                            data-bs-toggle="modal" data-bs-target="#paymentReminderModal" title="Kirim Reminder Pembayaran via WhatsApp"
                            style="{{ ($order->payment_status === 'unpaid') ? '' : 'display:none;' }}">
                            <i class="fa-brands fa-whatsapp me-1"></i>Reminder to WA Client
                        </button>
                    </div>

                    {{-- Form Note saat Pembayaran Ditolak --}}
                    <div id="payment_rejection_note_container" class="mt-3" style="{{ ($order->payment_status === 'rejected' || old('payment_status') === 'rejected') ? '' : 'display:none;' }}">
                        <label class="form-label fw-semibold text-danger small mb-1">
                            <i class="fa fa-pen me-1"></i>Catatan Penolakan Pembayaran:
                        </label>
                        <textarea name="payment_rejection_reason" id="payment_rejection_reason_input" class="form-control form-control-sm" rows="2" placeholder="Contoh: Nominal transfer salah / tidak sesuai, bukti transfer tidak terbaca...">{{ old('payment_rejection_reason', $order->payment_rejection_reason) }}</textarea>
                    </div>
                </form>

                @if($order->payment_status === 'rejected' && $order->payment_rejection_reason)
                <div class="alert alert-danger py-2 px-3 mt-3 mb-0" style="font-size:.85rem; border-radius:8px">
                    <strong><i class="fa fa-circle-xmark me-1"></i>Catatan Penolakan:</strong> {{ $order->payment_rejection_reason }}
                </div>
                @endif
            </div>
        </div>

        {{-- ── DOKUMEN (grouped by document_type, dynamic) ── --}}
        @if($documentSummary && count($documentSummary) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0"><i class="fa fa-file-shield me-2 text-muted"></i>Dokumen Persyaratan</h6>
                @php
                $fulfilledCount = collect($documentSummary)->filter(fn($d) => $d['is_fulfilled'])->count();
                $totalRequired = count($documentSummary);
                @endphp
                <div class="d-flex align-items-center gap-2">
                    @if(!$order->trashed())
                    <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.76rem;"
                        onclick="openUploadDocumentModal('')"
                        title="Upload / Tambah Dokumen Persyaratan">
                        <i class="fa fa-plus me-1"></i>Upload
                    </button>
                    @endif
                    <span class="badge {{ $fulfilledCount === $totalRequired ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ $fulfilledCount }}/{{ $totalRequired }} Terpenuhi
                    </span>
                </div>
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
                    <div class="doc-type-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-dot" style="background:{{ $headerColor }}"></span>
                            <strong style="font-size:.9rem">{{ $req->label }}</strong>
                            <code style="font-size:.72rem;color:#6b7280">{{ $req->document_type }}</code>
                        </div>
                        <span class="badge {{ $fulfilled ? 'bg-success' : 'bg-secondary' }}" style="font-size:.72rem; color: #fff !important;">
                            {{ $approved }}/{{ $req->min_required }} Disetujui
                        </span>
                    </div>

                    @if($docs->count() > 0)
                    @foreach($docs as $doc)
                    <div class="doc-file-row">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            {{-- File info --}}
                            <div class="d-flex align-items-center gap-2">
                                <i class="fa {{ match($doc->status) { 'approved','verified' => 'fa-circle-check text-success', 'rejected' => 'fa-circle-xmark text-danger', default => 'fa-clock text-warning' } }}"></i>
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

                            {{-- Professional Action Buttons & Badges --}}
                            @include('admin.orders.partials.document-actions', ['doc' => $doc, 'order' => $order])
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
                    <div class="doc-file-row d-flex align-items-center justify-content-between text-muted small">
                        <span><i class="fa fa-folder-open me-1"></i>Belum ada dokumen untuk tipe ini.</span>
                        @if(!$order->trashed())
                        <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.74rem;"
                            onclick="openUploadDocumentModal('{{ addslashes($req->document_type) }}')"
                            title="Upload {{ $req->label }}">
                            <i class="fa fa-upload me-1"></i>Upload
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
            
            @php
            $additionalDocs = $order->documents->filter(function($d) use ($documentSummary) {
                return !array_key_exists($d->document_type, $documentSummary);
            });
            @endphp
            @if($additionalDocs->count() > 0)
            <div class="card-header bg-transparent border-top border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="fa fa-folder-plus me-2 text-muted"></i>Dokumen Tambahan (Admin)</h6>
                <span class="badge bg-secondary" style="color: #fff !important;">{{ $additionalDocs->count() }} file</span>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="d-flex flex-column gap-3">
                    @foreach($additionalDocs as $doc)
                    @php
                    $sc = match($doc->status) { 'approved','verified'=>'success','rejected'=>'danger',default=>'warning' };
                    $si = match($doc->status) { 'approved','verified'=>'fa-circle-check','rejected'=>'fa-circle-xmark',default=>'fa-clock' };
                    @endphp
                    <div class="border border-{{ $sc }} rounded-3 p-3 bg-{{ $sc }} bg-opacity-10">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary text-uppercase" style="font-size:.7rem; color: #fff !important;">{{ $doc->document_type ?? 'UPLOAD' }}</span>
                                <span class="fw-semibold" style="font-size:.9rem">{{ $doc->original_name }}</span>
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank" class="text-muted" style="font-size:.8rem">
                                    <i class="fa fa-eye"></i> Lihat
                                </a>
                            </div>
                            @include('admin.orders.partials.document-actions', ['doc' => $doc, 'order' => $order])
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
            @endif
        </div>

        {{-- Fallback: order tanpa service requirement --}}
        @elseif($order->documents->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between flex-wrap gap-2">
                <h6 class="fw-bold mb-0">Dokumen yang Diunggah</h6>
                <div class="d-flex align-items-center gap-2">
                    @if(!$order->trashed())
                    <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size:.76rem;"
                        onclick="openUploadDocumentModal('')"
                        title="Upload / Tambah Dokumen">
                        <i class="fa fa-upload me-1"></i>Upload Dokumen
                    </button>
                    @endif
                    <span class="badge bg-secondary" style="color: #fff !important;">{{ $order->documents->count() }} file</span>
                </div>
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
                            @include('admin.orders.partials.document-actions', ['doc' => $doc, 'order' => $order, 'useTypeFallback' => true])
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

    @if($order->isRejected())
    <div class="alert alert-danger border-0 shadow-sm mb-4" id="rejectionReasonBox">
        <h6 class="fw-bold mb-1"><i class="fa fa-circle-xmark me-1"></i>Transaksi Ditolak — Alasan Penolakan:</h6>
        <p class="mb-1">{{ $order->rejection_reason }}</p>
        <small class="text-muted">Ditolak oleh {{ $order->rejector->name ?? 'Admin 3 / SPV' }} pada {{ $order->rejected_at?->format('d M Y, H:i') ?? '-' }}.</small>
    </div>

    @if(!$order->trashed() && (auth()->user()->isAdmin1() || auth()->user()->isSPV()))
    <div class="card border-0 shadow-sm mb-4" id="revisionSection" style="border-left: 4px solid #f59e0b !important;">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
            <h6 class="fw-bold mb-0 text-warning-emphasis"><i class="fa fa-rotate-left me-2"></i>Revisi Pesanan &amp; Submit Ulang (Admin 1)</h6>
            <small class="text-muted">Perbaiki data pesanan yang ditolak lalu kirim ulang. Status akan kembali menjadi Pending Approval dan riwayat penolakan sebelumnya tetap tersimpan.</small>
        </div>
        <div class="card-body px-4 pb-4">
            <form action="{{ route('admin.orders.resubmit', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Invoice <span class="text-danger">*</span></label>
                        <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $order->invoice_number) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Transaksi <span class="text-danger">*</span></label>
                        <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $order->effective_transaction_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Layanan <span class="text-danger">*</span></label>
                        <select name="service" class="form-select" required>
                            @foreach($services as $sKey => $svc)
                                <option value="{{ $sKey }}" {{ old('service', $order->form_data['service'] ?? '') === $sKey ? 'selected' : '' }}>{{ $svc['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Paket <span class="text-danger">*</span></label>
                        <select name="package" class="form-select" required>
                            @foreach($packages as $pKey => $pLbl)
                                <option value="{{ $pKey }}" {{ old('package', $order->form_data['package'] ?? '') === $pKey ? 'selected' : '' }}>{{ $pLbl }} ({{ $pKey }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Durasi Paket (Bulan) <span class="text-danger">*</span></label>
                        <input type="number" name="duration_months" class="form-control" value="{{ old('duration_months', $order->form_data['duration_months'] ?? 12) }}" min="1" max="120" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Total Harga <span class="text-danger">*</span></label>
                        <input type="number" name="total_price" class="form-control" value="{{ old('total_price', (int) $order->total_price) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Pembayaran <span class="text-danger">*</span></label>
                        <select name="payment_status" class="form-select" required>
                            @foreach(\App\Models\Order::ADMIN_PAYMENT_OPTIONS as $psKey => $psInfo)
                                <option value="{{ $psKey }}" {{ old('payment_status', $order->payment_status) === $psKey ? 'selected' : '' }}>{{ $psInfo['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Upload Bukti Pembayaran Baru (Opsional)</label>
                        <input type="file" name="payment_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Catatan Pesanan</label>
                        <input type="text" name="notes" class="form-control" value="{{ old('notes', $order->notes) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Catatan Revisi untuk Admin 3</label>
                        <input type="text" name="revision_note" class="form-control" value="{{ old('revision_note') }}" placeholder="Jelaskan poin perbaikan yang telah dilakukan...">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-warning fw-semibold px-4">
                            <i class="fa fa-paper-plane me-1"></i>Submit Ulang Revisi (Kembali ke Pending Approval)
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif

    {{-- Riwayat Approval, Penolakan & Revisi --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
            <h6 class="fw-bold mb-0"><i class="fa fa-clock-rotate-left me-2 text-primary"></i>Riwayat Approval, Penolakan &amp; Revisi</h6>
        </div>
        <div class="card-body px-4 pb-4">
            @forelse($order->histories as $hist)
            <div class="border-start border-3 ps-3 pb-3 mb-2 {{ $hist->action === 'rejected' ? 'border-danger' : ($hist->action === 'approved' ? 'border-success' : 'border-primary') }}">
                <div class="d-flex justify-content-between align-items-center">
                    <strong>{{ $hist->action_label }}</strong>
                    <small class="text-muted">{{ $hist->created_at->format('d M Y, H:i') }}</small>
                </div>
                <div class="small text-muted">
                    Oleh: {{ $hist->actor->name ?? 'Sistem' }} ({{ $hist->actor_role ?? '-' }})
                    @if($hist->from_status || $hist->to_status)
                        &middot; Status: <code>{{ $hist->from_status ?? 'baru' }}</code> → <code>{{ $hist->to_status }}</code>
                    @endif
                </div>
                @if($hist->notes)
                    <div class="small mt-1 bg-light p-2 rounded">{{ $hist->notes }}</div>
                @endif
            </div>
            @empty
            <p class="text-muted small mb-0">Belum ada riwayat approval/revisi.</p>
            @endforelse
        </div>
    </div>

    {{-- Pengajuan Perubahan (Admin 1) & Persetujuan (Admin 3 / SPV) --}}
    @if($order->changeRequests->isNotEmpty() || (!$order->trashed() && (auth()->user()->isAdmin1() || auth()->user()->isSPV())))
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="fa fa-code-pull-request me-2 text-info"></i>Pengajuan Edit / Hapus Pesanan</h6>
        </div>
        <div class="card-body px-4 pb-4">
            @foreach($order->changeRequests as $cr)
            <div class="border rounded p-3 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge bg-{{ $cr->request_type === 'delete' ? 'danger' : 'info text-dark' }}">{{ strtoupper($cr->request_type) }}</span>
                    <span class="badge bg-{{ $cr->status === 'approved' ? 'success' : ($cr->status === 'rejected' ? 'danger' : 'warning text-dark') }}">{{ ucfirst($cr->status) }}</span>
                </div>
                <p class="small mb-1 mt-2"><strong>Alasan:</strong> {{ $cr->reason }}</p>
                <small class="text-muted d-block">Diajukan oleh {{ $cr->requester->name ?? '-' }} pada {{ $cr->created_at->format('d M Y H:i') }}</small>
                @if(!empty($cr->proposed_data))
                    <div class="mt-2 small bg-light p-2 rounded">
                        @foreach($cr->proposed_data as $pk => $pv)
                            <div><strong>{{ $pk }}:</strong> {{ $pv }}</div>
                        @endforeach
                    </div>
                @endif
                @if($cr->reviewer)
                    <small class="text-muted d-block mt-1">Ditinjau oleh {{ $cr->reviewer->name }} ({{ $cr->reviewed_at?->format('d M Y H:i') }}): {{ $cr->review_note }}</small>
                @endif
                @if($cr->isPending() && auth()->user()->canApproveOrders())
                    <div class="mt-2 d-flex gap-2">
                        <form action="{{ route('admin.change-requests.approve', $cr->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Setujui pengajuan {{ $cr->request_type }} ini?')">Setujui</button>
                        </form>
                        <form action="{{ route('admin.change-requests.reject', $cr->id) }}" method="POST" class="d-flex gap-1">
                            @csrf
                            <input type="text" name="review_note" class="form-control form-control-sm" placeholder="Alasan penolakan..." required minlength="3">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Tolak</button>
                        </form>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    </div>{{-- /col-lg-8 --}}

    {{-- RIGHT --}}
    <div class="col-lg-4">

        {{-- QR Code Layanan --}}
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #1a2b5a !important;">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-qrcode text-dark fs-5"></i>
                    <h6 class="fw-bold mb-0">QR Code Layanan</h6>
                </div>
                @if($order->qr_token)
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1" style="font-size: 0.72rem;">
                        <i class="fa-solid fa-circle-check me-1"></i>Aktif
                    </span>
                @else
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1" style="font-size: 0.72rem;">
                        Belum Dibuat
                    </span>
                @endif
            </div>
            <div class="card-body px-4 pb-4">
                @if($order->qr_token)
                    <div class="text-center p-3 bg-light rounded-3 mb-3 border">
                        <div class="d-inline-block bg-white p-2 rounded shadow-sm">
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(170)->margin(1)->generate($order->qr_url) !!}
                        </div>
                        <div class="mt-2 text-truncate small">
                            <span class="text-muted d-block" style="font-size: 0.75rem;">Link Resmi QR:</span>
                            <a href="{{ $order->qr_url }}" target="_blank" class="text-primary fw-semibold text-decoration-none" title="{{ $order->qr_url }}">
                                {{ $order->qr_url }} <i class="fa-solid fa-arrow-up-right-from-square small ms-1"></i>
                            </a>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('admin.orders.download-qr', $order->id) }}" class="btn btn-outline-dark btn-sm w-100 fw-semibold">
                            <i class="fa-solid fa-download me-1"></i>Download QR Code (SVG)
                        </a>
                        <a href="{{ route('qr.show', $order->qr_token) }}" target="_blank" class="btn btn-primary btn-sm w-100 fw-semibold">
                            <i class="fa-solid fa-eye me-1"></i>Buka Halaman Layanan
                        </a>
                        <form action="{{ route('admin.orders.generate-qr', $order->id) }}" method="POST" onsubmit="return confirm('Regenerate QR token? Link QR lama tidak akan berfungsi lagi.')">
                            @csrf
                            <button type="submit" class="btn btn-link btn-sm text-muted w-100 text-decoration-none pt-2" style="font-size: 0.8rem;">
                                <i class="fa-solid fa-rotate-right me-1"></i>Regenerate Token Baru
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-muted small mb-3">
                        Generate QR Code unik untuk order ini agar klien dapat memindai dan mengakses informasi layanan secara publik.
                    </p>
                    <form action="{{ route('admin.orders.generate-qr', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark w-100 fw-semibold">
                            <i class="fa-solid fa-qrcode me-1"></i>Generate QR Code Layanan
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Verifikasi & Approval Transaksi (Admin 3 / SPV) --}}
        @if(!$order->trashed() && auth()->user()->canApproveOrders())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Status &amp; Approval Transaksi</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @php
                $badge = App\Models\Order::STATUS_MAP[$order->status]['color'] ?? 'secondary';
                $label = App\Models\Order::STATUS_MAP[$order->status]['label'] ?? ucfirst($order->status);
                @endphp
                <p>Status Saat Ini: <span class="badge bg-{{ $badge }}" style="color: {{ in_array($badge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">{{ $label }}</span></p>

                @if($order->needsTransactionApproval())
                <div class="d-grid gap-2 mb-3">
                    <form action="{{ route('admin.orders.approve', $order->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100 fw-semibold"
                            onclick="return confirm('Setujui transaksi {{ $order->invoice_number ?? $order->order_number }}? Benefit akan diaktifkan menggunakan tanggal transaksi {{ $order->effective_transaction_date->format('d M Y') }}.')">
                            <i class="fa fa-check-circle me-1"></i>Approve Transaksi
                        </button>
                    </form>
                    <button type="button" class="btn btn-danger w-100 fw-semibold" data-bs-toggle="modal" data-bs-target="#rejectOrderDetailModal">
                        <i class="fa fa-xmark-circle me-1"></i>Reject Transaksi (Alasan Wajib)
                    </button>
                </div>
                @endif

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf @method('PUT')
                    @php
                    $selectStatuses = [
                        'pending_approval' => 'Pending Approval',
                        'approved'         => 'Approved (Aktif Sesuai Tgl Transaksi)',
                        'draft'            => 'Dokumen Belum Lengkap',
                        'completed'        => 'Dokumen Terverifikasi',
                        'cancelled'        => 'Dibatalkan',
                    ];
                    @endphp
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status</label>
                        <select name="status" class="form-select">
                            @foreach($selectStatuses as $val => $lbl)
                            <option value="{{ $val }}" {{ ($order->status == $val) ? 'selected' : '' }}>
                                {{ $lbl }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="2"
                            placeholder="Pesan untuk customer...">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Status &amp; Catatan</button>
                </form>
            </div>
        </div>
        @endif

        {{-- Room Benefit --}}
        @php
        use App\Models\RoomBenefit;
        $packageName     = $order->service_name ?? ($order->service?->name ?? '');
        $existingBenefits = RoomBenefit::where('order_id', $order->id)->get();
        $hasAnyBenefit    = $existingBenefits->isNotEmpty();
        $isEligible = RoomBenefit::isEligibleForOrder($order);
        @endphp
        @if($isEligible)
        <div class="card border-0 shadow-sm" style="border-left:4px solid #198754!important">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2">
                <i class="fa fa-door-open text-success"></i>
                <h6 class="fw-bold mb-0">Benefit Ruangan</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @php
                    $benefitType = \App\Models\RoomBenefit::benefitTypeForOrder($order);
                    $years = $order->benefit_years;
                    $benefitDesc = $benefitType === 'meeting_only_12'
                        ? '<strong>Meeting Room ' . (12 * $years) . ' Jam</strong>'
                        : '<strong>Meeting Room ' . (48 * $years) . ' Jam</strong> &amp; <strong>Podcast Room ' . (12 * $years) . ' Jam</strong>';
                @endphp
                <p class="text-muted small mb-3">Paket <strong>{{ $packageName }}</strong> ({{ $order->duration_breakdown['summary'] }}) termasuk benefit ruangan sejak tanggal transaksi <strong>{{ $order->effective_transaction_date->format('d M Y') }}</strong>: {!! $benefitDesc !!}.</p>
                @if($hasAnyBenefit)
                @php $firstBenefit = $existingBenefits->first(); @endphp
                <div class="alert alert-{{ $firstBenefit->is_expired ? 'danger' : 'success' }} py-3 px-3 mb-0" style="font-size:.88rem">
                    <i class="fa fa-circle-check me-1"></i><strong>Benefit {{ $firstBenefit->is_expired ? 'Tercatat (Expired)' : 'Sudah Diaktifkan' }}</strong><br>
                    <div class="mt-2 d-flex flex-column gap-2">
                        @foreach($existingBenefits as $eb)
                        <div class="d-flex align-items-center justify-content-between border-top pt-2">
                            <span class="text-muted">
                                <i class="fa fa-{{ $eb->type === 'meeting' ? 'door-open' : 'microphone' }} me-1"></i>
                                <strong>{{ $eb->type === 'meeting' ? 'Meeting Room' : 'Podcast Room' }}</strong>
                            </span>
                            <span>
                                Sisa: <strong class="{{ $eb->is_expired ? 'text-danger' : 'text-success' }}">{{ RoomBenefit::formatMinutes($eb->remaining_minutes) }}</strong>
                                / {{ RoomBenefit::formatMinutes($eb->total_minutes) }}
                            </span>
                        </div>
                        @endforeach
                        <div class="text-muted pt-1" style="font-size:.82rem">
                            Mulai Benefit (Tgl Transaksi): <strong>{{ $firstBenefit->effective_start_date->format('d M Y') }}</strong><br>
                            Berlaku hingga: <strong>{{ $firstBenefit->expired_at?->format('d M Y') ?? '–' }}</strong><br>
                            @if($firstBenefit->approved_at)
                                Di-approve pada: {{ $firstBenefit->approved_at->format('d M Y, H:i') }}
                            @endif
                        </div>
                    </div>
                </div>
                @elseif(!$order->isValidated())
                <div class="alert alert-warning py-2 px-3 mb-0" style="font-size:.85rem; border-radius:8px">
                    <i class="fa fa-hourglass-half me-1"></i><strong>Belum Aktif</strong><br>
                    Benefit baru akan aktif setelah pesanan disetujui (<strong>Approved</strong>) oleh Admin 3 / SPV.
                </div>
                @elseif(auth()->user()->canApproveOrders())
                <form action="{{ route('admin.orders.approve-benefit', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success w-100">
                        <i class="fa fa-unlock me-1"></i>Aktifkan Benefit Sesuai Tgl Transaksi
                    </button>
                </form>
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
                    Benefit ruangan hanya untuk <strong>Business Package</strong> (semua layanan Pendirian Badan Usaha).
                </p>
            </div>
        </div>
        @endif

    </div>
</div>

{{-- Modals for Show Page Actions --}}
@if(!$order->trashed() && auth()->user()->canApproveOrders())
<div class="modal fade" id="rejectOrderDetailModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.orders.reject', $order->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Tolak Transaksi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-semibold">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="rejection_reason" class="form-control" rows="4" required minlength="3" placeholder="Jelaskan alasan penolakan agar Admin 1 dapat merevisi..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteSingleOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Hapus Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="small">Pesanan akan dihapus (soft-delete) dan benefit terkait dinonaktifkan. Riwayat pesanan tetap tersimpan.</p>
                    <label class="form-label fw-semibold">Alasan Penghapusan</label>
                    <textarea name="delete_reason" class="form-control" rows="3" placeholder="Masukkan alasan penghapusan..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal Konfirmasi Reminder WA Pembayaran --}}
@php
    $modalClientName  = $order->user->company_name ?? ($order->form_data['company_name'] ?? ($order->user->name ?? 'Client'));
    $modalClientPhone = $order->user->phone ?? ($order->form_data['pic_phone'] ?? '-');
    $modalServiceName = $order->service_name ?? ($order->service->name ?? '-');
    $modalCreatedAt   = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : now();
    $modalDueDate     = $modalCreatedAt->addDays(3)->translatedFormat('d F Y');
    $modalPpnData     = \App\Helpers\PpnHelper::calculate($order->total_price);
    $modalGrandTotal  = 'Rp ' . number_format($modalPpnData['grand_total'], 0, ',', '.');
@endphp
<div class="modal fade" id="paymentReminderModal" tabindex="-1" aria-labelledby="paymentReminderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning bg-opacity-10 border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold text-dark" id="paymentReminderModalLabel">
                    <i class="fa-brands fa-whatsapp text-success me-2"></i>Reminder Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <p class="text-muted small mb-3">Anda akan mengirimkan Reminder Pembayaran via WhatsApp kepada client berikut:</p>
                <div class="bg-light rounded-3 p-3 mb-3 border">
                    <table class="table table-borderless table-sm mb-0 text-body small">
                        <tr>
                            <td class="text-muted" width="40%">Nama Client</td>
                            <td class="fw-bold">: {{ $modalClientName }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor WhatsApp</td>
                            <td class="fw-bold">: {{ $modalClientPhone }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nomor Invoice</td>
                            <td class="fw-bold">: {{ $order->invoice_number ?? $order->order_number }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nama Layanan</td>
                            <td class="fw-bold">: {{ $modalServiceName }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tanggal Jatuh Tempo</td>
                            <td class="fw-bold">: {{ $modalDueDate }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total Tagihan</td>
                            <td class="fw-bold text-primary">: {{ $modalGrandTotal }}</td>
                        </tr>
                    </table>
                </div>
                <div class="alert alert-info py-2 px-3 mb-0 small" style="font-size:.8rem;">
                    <i class="fa fa-circle-info me-1"></i>Pesan WhatsApp akan dikirimkan otomatis menggunakan Botcake Official WABA API.
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 pb-4 pt-2">
                <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.orders.send-payment-reminder', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm fw-bold text-dark px-3">
                        <i class="fa fa-paper-plane me-1"></i>Kirim Reminder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(url, docName) {
        document.getElementById('rejectForm').action = url;
        document.getElementById('rejectDocName').textContent = docName;
        new bootstrap.Modal(document.getElementById('rejectModal')).show();
    }

    function openEditDocumentModal(url, docName, docType) {
        document.getElementById('editDocumentForm').action = url;
        document.getElementById('editDocCurrentName').textContent = docName;
        document.getElementById('editDocFileInput').value = '';
        document.getElementById('editDocReasonInput').value = '';
        const typeSelect = document.getElementById('editDocTypeSelect');
        if (typeSelect && docType) {
            if (!Array.from(typeSelect.options).some(opt => opt.value === docType)) {
                const opt = document.createElement('option');
                opt.value = docType;
                opt.textContent = docType;
                typeSelect.appendChild(opt);
            }
            typeSelect.value = docType;
        }
        new bootstrap.Modal(document.getElementById('editDocumentModal')).show();
    }

    function openUploadDocumentModal(docType) {
        document.getElementById('uploadDocFileInput').value = '';
        document.getElementById('uploadDocReasonInput').value = '';
        const typeSelect = document.getElementById('uploadDocTypeSelect');
        if (typeSelect && docType) {
            if (!Array.from(typeSelect.options).some(opt => opt.value === docType)) {
                const opt = document.createElement('option');
                opt.value = docType;
                opt.textContent = docType;
                typeSelect.appendChild(opt);
            }
            typeSelect.value = docType;
        }
        new bootstrap.Modal(document.getElementById('uploadDocumentModal')).show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.getElementById('payment_status_select');
        const rejectionContainer = document.getElementById('payment_rejection_note_container');
        const rejectionInput = document.getElementById('payment_rejection_reason_input');
        const waReminderBtn = document.getElementById('payment_reminder_btn');

        if (paymentSelect) {
            function updatePaymentUI() {
                const val = paymentSelect.value;

                // Rejection note container
                if (rejectionContainer) {
                    if (val === 'rejected') {
                        rejectionContainer.style.display = 'block';
                        if (rejectionInput) rejectionInput.focus();
                    } else {
                        rejectionContainer.style.display = 'none';
                    }
                }

                // WA Reminder button (Tampil jika Belum Bayar/unpaid, sembunyi jika terverifikasi/ditolak/lainnya)
                if (waReminderBtn) {
                    if (val === 'unpaid') {
                        waReminderBtn.style.display = 'inline-block';
                    } else {
                        waReminderBtn.style.display = 'none';
                    }
                }
            }

            paymentSelect.addEventListener('change', updatePaymentUI);
            updatePaymentUI(); // Initial run on page load
        }
    });
</script>
@endpush
@endsection