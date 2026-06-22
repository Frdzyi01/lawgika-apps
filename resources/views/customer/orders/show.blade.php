@extends('layouts-customer.app')

@section('title', __('customer.orders.show.title') . $order->order_number)

@section('content')
<style>
    .doc-card {
        border-radius: 12px;
        border: 1.5px solid #e5e7eb;
        transition: box-shadow .2s;
        overflow: hidden;
    }
    .doc-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.08); }

    /* Status bars */
    .status-bar { height: 4px; width: 100%; border-radius: 0; }
    .status-bar.pending  { background: #f59e0b; }
    .status-bar.approved { background: #10b981; }
    .status-bar.rejected { background: #ef4444; }

    /* Progress pill */
    .progress-pill {
        height: 8px;
        border-radius: 99px;
        overflow: hidden;
        background: #e5e7eb;
    }
    .progress-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }

    /* Upload zone */
    .upload-zone {
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        padding: 18px 16px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .upload-zone:hover { border-color: #6366f1; background: #f5f3ff; }

    /* Status badge */
    .doc-status-badge {
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .5px;
        padding: 4px 10px;
        border-radius: 99px;
    }

    /* Order status progress */
    .order-status-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }
    .order-status-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 14px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #e5e7eb;
        z-index: 0;
    }
    .order-status-step.active::after,
    .order-status-step.done::after { background: #10b981; }
    .step-circle {
        width: 28px; height: 28px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem;
        z-index: 1;
        position: relative;
    }
    .step-circle.active { background: #6366f1; color: white; }
    .step-circle.done   { background: #10b981; color: white; }
    .step-label { font-size: .68rem; margin-top: 5px; text-align: center; color: #6b7280; }
    .step-label.active { color: #6366f1; font-weight: 700; }
    .step-label.done   { color: #10b981; font-weight: 600; }
</style>

{{-- Flash messages --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="fa fa-circle-check me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    @foreach($errors->all() as $e)<div><i class="fa fa-triangle-exclamation me-1"></i>{{ $e }}</div>@endforeach
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <a href="{{ route('customer.orders.index') }}" class="text-muted small mb-1 d-inline-block">
            {{ __('customer.orders.show.back') }}
        </a>
        <h5 class="mb-0 fw-bold">{{ __('customer.orders.show.detail_title') }}</h5>
        <span class="text-muted small">{{ $order->order_number }}</span>
    </div>
    @php
        $sc = $order->status_color ?? 'secondary';
        $sl = $order->status_label ?? ucfirst($order->status);
    @endphp
    <span class="badge bg-{{ $sc }} fs-6 px-3 py-2">{{ $sl }}</span>
</div>

{{-- ── Order Status Stepper ─────────────────────────────────────────────── --}}
@php
    $steps = [
        'draft'                => ['icon' => 'fa-file-circle-plus',  'label' => __('customer.orders.show.stepper.draft')],
        'waiting_verification' => ['icon' => 'fa-hourglass-half',    'label' => __('customer.orders.show.stepper.waiting')],
        'revision'             => ['icon' => 'fa-rotate-left',       'label' => __('customer.orders.show.stepper.revision')],
        'verified'             => ['icon' => 'fa-circle-check',      'label' => __('customer.orders.show.stepper.verified')],
    ];
    $stepKeys    = array_keys($steps);
    $currentStep = $order->status;
    $currentIdx  = array_search($currentStep, $stepKeys);
    // Jika status adalah legacy status, tampilkan di step pertama saja
    if ($currentIdx === false) $currentIdx = -1;
@endphp
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-4 px-4">
        <p class="text-muted small mb-3 fw-semibold">{{ __('customer.orders.show.stepper.title') }}</p>
        <div class="d-flex align-items-flex-start">
            @foreach($steps as $key => $step)
                @php
                    $idx   = array_search($key, $stepKeys);
                    $state = 'inactive';
                    if ($currentIdx !== false) {
                        if ($key === $currentStep)       $state = 'active';
                        elseif ($idx < $currentIdx)      $state = 'done';
                    }
                @endphp
                <div class="order-status-step {{ $state !== 'inactive' ? $state : '' }}">
                    <div class="step-circle {{ $state }}">
                        @if($state === 'done')
                            <i class="fa fa-check"></i>
                        @else
                            <i class="fa {{ $step['icon'] }} fa-xs"></i>
                        @endif
                    </div>
                    <span class="step-label {{ $state }}">{{ $step['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- ── LEFT COL ── --}}
    <div class="col-lg-7">

        {{-- {{ __('customer.orders.show.info.title') }} --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 px-4 border-0">
                <h6 class="fw-bold mb-0"><i class="fa fa-receipt me-2 text-muted"></i>{{ __('customer.orders.show.info.title') }}</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <table class="table table-borderless table-sm mb-0" style="font-size:.9rem">
                    <tr><td class="text-muted" width="38%">{{ __('customer.orders.show.info.service') }}</td>
                        <td class="fw-semibold">{{ $order->service?->name ?? $order->service_name ?? '–' }}</td></tr>
                    <tr><td class="text-muted">{{ __('customer.orders.show.info.total') }}</td>
                        <td class="fw-semibold">{{ $order->total_price > 0 ? 'Rp ' . number_format($order->total_price,0,',','.') : '–' }}</td></tr>
                    <tr><td class="text-muted">{{ __('customer.orders.show.info.date') }}</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td></tr>
                    @if($order->admin_notes)
                    <tr>
                        <td class="text-muted align-top">{{ __('customer.orders.show.info.admin_notes') }}</td>
                        <td>
                            <div class="alert alert-warning py-2 px-3 mb-0" style="font-size:.88rem">
                                <i class="fa fa-comment-dots me-1"></i>{{ $order->admin_notes }}
                            </div>
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- ── DOKUMEN PERSYARATAN (Dynamic dari DB) ── --}}
        @if($documentSummary && count($documentSummary) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 px-4 border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="fa fa-file-shield me-2 text-muted"></i>{{ __('customer.orders.show.req.title') }}</h6>
                @php
                    $fulfilledCount = collect($documentSummary)->filter(fn($d) => $d['is_fulfilled'])->count();
                    $totalRequired  = count($documentSummary);
                @endphp
                <span class="badge {{ $fulfilledCount === $totalRequired ? 'bg-success' : 'bg-warning text-dark' }}">
                    {{ $fulfilledCount }} / {{ $totalRequired }} {{ __('customer.orders.show.req.fulfilled') }}
                </span>
            </div>

            @php
                // Hitung kondisi global untuk semua document types
                $hasAnyRejected = collect($documentSummary)->contains(fn($d) => $d['documents']->where('status','rejected')->count() > 0);
                $hasAnyPending  = collect($documentSummary)->contains(fn($d) => $d['documents']->where('status','pending')->count() > 0);
                $allHaveDocs    = collect($documentSummary)->every(fn($d) => $d['total_count'] > 0);
            @endphp

            @if($hasAnyRejected)
            <div class="alert alert-danger mx-4 py-2 px-3 mb-0" style="font-size:.85rem; border-radius:8px">
                <i class="fa fa-circle-xmark me-1"></i>
                <strong>Ada dokumen yang ditolak.</strong> Silakan upload ulang dokumen yang ditolak admin di bawah ini.
            </div>
            @elseif(!$allHaveDocs && $fulfilledCount < $totalRequired)
            <div class="alert alert-warning mx-4 py-2 px-3 mb-0" style="font-size:.85rem; border-radius:8px">
                <i class="fa fa-triangle-exclamation me-1"></i>
                <strong>Dokumen belum lengkap.</strong> Harap upload semua dokumen yang diperlukan.
            </div>
            @elseif($hasAnyPending && !$hasAnyRejected)
            <div class="alert alert-info mx-4 py-2 px-3 mb-0" style="font-size:.85rem; border-radius:8px; background:#eff6ff; border:1px solid #bfdbfe; color:#1e40af">
                <i class="fa fa-hourglass-half me-1"></i>
                <strong>Dokumen sedang ditinjau admin.</strong> Tidak perlu upload lagi saat ini. Kami akan notifikasi Anda jika ada yang perlu diperbaiki.
            </div>
            @endif

            <div class="card-body px-4 pb-4 pt-3">
                @foreach($documentSummary as $docType => $summary)
                    @php
                        $req         = $summary['requirement'];
                        $docs        = $summary['documents'];
                        $approved    = $summary['approved_count'];
                        $total       = $summary['total_count'];
                        $fulfilled   = $summary['is_fulfilled'];
                        $canUpload   = $summary['can_upload_more'];
                        $progress    = $req->min_required > 0 ? min(100, round(($approved / $req->min_required) * 100)) : 100;
                    @endphp

                    <div class="doc-card mb-3">
                        {{-- Status bar di atas --}}
                        <div class="status-bar {{ $fulfilled ? 'approved' : ($docs->where('status','rejected')->count() > 0 ? 'rejected' : ($total > 0 ? 'pending' : 'pending')) }}"></div>

                        <div class="p-3">
                            {{-- Header document type --}}
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div>
                                    <span class="fw-bold" style="font-size:.92rem">{{ $req->label }}</span>
                                    <small class="text-muted ms-2 font-monospace" style="font-size:.72rem">{{ $req->document_type }}</small>
                                </div>
                                @if($fulfilled)
                                    <span class="doc-status-badge bg-success text-white">✓ {{ __('customer.orders.show.req.fulfilled') }}</span>
                                @elseif($docs->where('status','rejected')->count() > 0)
                                    <span class="doc-status-badge bg-danger text-white">{{ __('customer.orders.show.doc.rejected') }}</span>
                                @elseif($total > 0)
                                    <span class="doc-status-badge bg-warning text-dark">{{ __('customer.orders.show.doc.pending') }}</span>
                                @else
                                    <span class="doc-status-badge bg-secondary text-white">{{ __('customer.orders.show.doc.not_uploaded') }}</span>
                                @endif
                            </div>

                            {{-- Progress bar --}}
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="progress-pill flex-grow-1">
                                    <div class="progress-fill {{ $fulfilled ? 'bg-success' : 'bg-warning' }}"
                                         style="width:{{ $progress }}%"></div>
                                </div>
                                <small class="text-muted" style="white-space:nowrap; font-size:.78rem">
                                    {{ $approved }} / {{ $req->min_required }} {{ __('customer.orders.show.doc.approved_count') }}
                                </small>
                            </div>

                            {{-- Daftar dokumen yang sudah diunggah --}}
                            @if($docs->count() > 0)
                            <div class="mb-3">
                                @foreach($docs as $doc)
                                    @php
                                        $dColor = match($doc->status) {
                                            'approved', 'verified' => 'success',
                                            'rejected'             => 'danger',
                                            default                => 'warning',
                                        };
                                        $dLabel = match($doc->status) {
                                            'approved', 'verified' => __('customer.orders.show.status.approved'),
                                            'rejected'             => __('customer.orders.show.status.rejected'),
                                            default                => __('customer.orders.show.status.pending'),
                                        };
                                        $dIcon  = match($doc->status) {
                                            'approved', 'verified' => 'fa-circle-check',
                                            'rejected'             => 'fa-circle-xmark',
                                            default                => 'fa-clock',
                                        };
                                    @endphp
                                    <div class="d-flex align-items-center justify-content-between py-2
                                                border-bottom border-{{ $dColor }} border-opacity-25 flex-wrap gap-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fa {{ $dIcon }} text-{{ $dColor }}" style="font-size:.95rem"></i>
                                            <div>
                                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank"
                                                   class="text-decoration-none fw-semibold text-body" style="font-size:.85rem">
                                                    {{ $doc->original_name }}
                                                </a>
                                                <div style="font-size:.72rem" class="text-muted">
                                                    {{ $doc->created_at->format('d M Y, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge bg-{{ $dColor }}" style="font-size:.72rem">{{ $dLabel }}</span>
                                    </div>

                                    {{-- Tampilkan alasan penolakan --}}
                                    @if($doc->status === 'rejected' && $doc->rejection_reason)
                                    <div class="alert alert-danger py-2 px-3 mt-1 mb-1" style="font-size:.82rem; border-radius: 8px">
                                        <i class="fa fa-circle-info me-1"></i>
                                        <strong>{{ __('customer.orders.show.rejection_reason') }}</strong> {{ $doc->rejection_reason }}
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted small mb-3"><i class="fa fa-folder-open me-1"></i>{{ __('customer.orders.show.upload.no_docs_yet') }}</p>
                            @endif

                            {{-- ══════════════════════════════════════════════════════════
                                 UPLOAD FORM — hanya tampil jika:
                                 1. Ada dokumen REJECTED (perlu upload ulang / revisi), ATAU
                                 2. Belum ada dokumen sama sekali DAN order masih 'draft'
                                 ══════════════════════════════════════════════════════════ --}}
                            @php
                                $hasRejectedDoc  = $docs->where('status', 'rejected')->count() > 0;
                                $hasNoDocs       = $docs->count() === 0;
                                $isOrderDraft    = $order->status === 'draft';

                                // Tampilkan upload jika:
                                // - Ada rejected → suruh upload ulang
                                // - Belum ada dokumen apapun dan order masih draft (baru saja order tapi belum upload via order form)
                                $showUpload = $canUpload && ($hasRejectedDoc || ($hasNoDocs && $isOrderDraft));
                            @endphp

                            @if($showUpload)
                            <form action="{{ route('customer.documents.store') }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <input type="hidden" name="order_id"      value="{{ $order->id }}">
                                <input type="hidden" name="document_type" value="{{ $docType }}">

                                @if($hasRejectedDoc)
                                {{-- Banner konteks: upload ulang karena ditolak --}}
                                <div class="alert alert-warning py-2 px-3 mb-2" style="font-size:.82rem;border-radius:8px">
                                    <i class="fa fa-rotate-left me-1"></i>
                                    <strong>Upload Ulang:</strong> Dokumen ini ditolak admin. Harap upload versi yang sudah diperbaiki.
                                </div>
                                @endif

                                <div class="upload-zone" id="zone-{{ $loop->index }}"
                                     onclick="document.getElementById('file-{{ $loop->index }}').click()">
                                    <i class="fa fa-cloud-arrow-up text-muted mb-2" style="font-size:1.5rem"></i>
                                    <div class="fw-semibold text-muted small">
                                        {{ $hasRejectedDoc ? __('customer.orders.show.upload.click_reupload') : __('customer.orders.show.upload.click_upload') }}
                                    </div>
                                    <div class="text-muted" style="font-size:.75rem">{{ __('customer.orders.show.upload.hint') }}</div>
                                    <div id="fname-{{ $loop->index }}" class="text-primary small mt-1 d-none fw-semibold"></div>
                                </div>
                                <input type="file" id="file-{{ $loop->index }}" name="document" class="d-none"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       onchange="previewFile(this, '{{ $loop->index }}')">
                                <button type="submit" id="submit-{{ $loop->index }}"
                                        class="btn {{ $hasRejectedDoc ? 'btn-warning' : 'btn-primary' }} btn-sm w-100 mt-2 d-none"
                                        style="border-radius:8px">
                                    <i class="fa fa-upload me-1"></i>
                                    {{ $hasRejectedDoc ? __('customer.orders.show.upload.btn_revision') : __('customer.orders.show.upload.btn_submit') }}
                                </button>
                            </form>

                            @elseif(!$canUpload)
                            {{-- Batas maksimal upload tercapai --}}
                            <div class="text-muted small mt-2">
                                <i class="fa fa-circle-info me-1"></i>
                                {{ __('customer.orders.show.upload.max_allowed') }}
                            </div>

                            @elseif($docs->where('status', 'pending')->count() > 0)
                            {{-- Ada dokumen pending → tunggu review admin --}}
                            <div class="alert alert-info py-2 px-3 mt-2 mb-0" style="font-size:.82rem;border-radius:8px;background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af">
                                <i class="fa fa-hourglass-half me-1"></i>
                                <strong>Menunggu review admin.</strong> Upload baru tidak diperlukan saat ini.
                                Silakan tunggu hasil verifikasi.
                            </div>

                            @elseif($fulfilled)
                            {{-- Semua sudah approved --}}
                            <div class="alert py-2 px-3 mt-2 mb-0" style="font-size:.82rem;border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534">
                                <i class="fa fa-circle-check me-1"></i>
                                <strong>Dokumen {{ __('customer.orders.show.doc.approved_count') }}.</strong> Tidak perlu upload lagi.
                            </div>

                            @endif
                        </div>{{-- /p-3 --}}
                    </div>{{-- /doc-card --}}
                @endforeach
            </div>
        </div>
        @else
        {{-- Order tidak punya service / requirement → tampilkan form upload lama --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 px-4 border-0">
                <h6 class="fw-bold mb-0"><i class="fa fa-file-arrow-up me-2 text-muted"></i>{{ __('customer.orders.show.legacy.title') }}</h6>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('customer.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('customer.orders.show.legacy.type') }}</label>
                        <select name="document_type" class="form-select" required>
                            <option value="">{{ __('customer.orders.show.legacy.select') }}</option>
                            <option value="KTP_DIREKTUR">KTP Direktur</option>
                            <option value="NPWP_DIREKTUR">NPWP Direktur</option>
                            <option value="KTP_PEMEGANG_SAHAM">KTP Pemegang Saham</option>
                            <option value="NPWP_PEMEGANG_SAHAM">NPWP Pemegang Saham</option>
                            <option value="KTP_KOMISARIS">KTP Komisaris</option>
                            <option value="NPWP_KOMISARIS">NPWP Komisaris</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('customer.orders.show.legacy.file') }}</label>
                        <input type="file" name="document" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">{{ __('customer.orders.show.legacy.title') }}</button>
                </form>

                @if($order->documents->count() > 0)
                <hr>
                <h6 class="fw-bold mt-3">{{ __('customer.orders.show.legacy.uploaded_title') }}</h6>
                @foreach($order->documents as $doc)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <span class="small">{{ $doc->original_name }}</span>
                        <span class="badge bg-{{ $doc->status === 'approved' || $doc->status === 'verified' ? 'success' : ($doc->status === 'rejected' ? 'danger' : 'warning') }}">{{ $doc->status }}</span>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- ── RIGHT COL ── --}}
    <div class="col-lg-5">

        {{-- {{ __('customer.orders.show.payment.title') }} --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent pt-4 pb-2 px-4 border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="fa fa-credit-card me-2 text-muted"></i>{{ __('customer.orders.show.payment.title') }}</h6>
                <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
            </div>
            <div class="card-body px-4 pb-4">
                @if($order->payment_proof)
                <div class="mb-3">
                    <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-eye me-1"></i>Lihat Bukti {{ __('customer.orders.show.payment.title') }}
                    </a>
                </div>
                @endif

                @if(!in_array($order->payment_status, ['verified']))
                <form action="{{ route('customer.orders.payment-proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label fw-semibold small">
                        {{ $order->payment_proof ? 'Update Bukti ' . __('customer.orders.show.payment.title') : 'Upload Bukti ' . __('customer.orders.show.payment.title') }}
                    </label>
                    <input type="file" name="payment_proof" class="form-control form-control-sm mb-2" required>
                    <small class="text-muted d-block mb-2">{{ __('customer.orders.show.payment.hint') }}</small>
                    <button class="btn btn-primary btn-sm w-100" type="submit">
                        <i class="fa fa-upload me-1"></i>Kirim Bukti {{ __('customer.orders.show.payment.title') }}
                    </button>
                </form>
                @else
                <div class="alert alert-success py-2 px-3 mb-0" style="font-size:.85rem">
                    <i class="fa fa-circle-check me-1"></i>{{ __('customer.orders.show.payment.title') }} telah diverifikasi.
                </div>
                @endif
            </div>
        </div>

        {{-- Ringkasan kelengkapan --}}
        @if($documentSummary && count($documentSummary) > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent pt-4 pb-2 px-4 border-0">
                <h6 class="fw-bold mb-0"><i class="fa fa-list-check me-2 text-muted"></i>{{ __('customer.orders.show.summary.title') }}</h6>
            </div>
            <div class="card-body px-4 pb-4">
                @foreach($documentSummary as $docType => $summary)
                @php
                    $req       = $summary['requirement'];
                    $approved  = $summary['approved_count'];
                    $fulfilled = $summary['is_fulfilled'];
                @endphp
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small">{{ $req->label }}</div>
                        <div class="text-muted" style="font-size:.75rem">Min. {{ $req->min_required }} {{ __('customer.orders.show.doc.approved_count') }}</div>
                    </div>
                    @if($fulfilled)
                        <i class="fa fa-circle-check text-success fs-5"></i>
                    @else
                        <i class="fa fa-circle-xmark text-danger fs-5"></i>
                    @endif
                </div>
                @endforeach

                @php $total = count($documentSummary); $done = collect($documentSummary)->filter(fn($d)=>$d['is_fulfilled'])->count(); @endphp
                <div class="progress-pill mt-3 mb-1">
                    <div class="progress-fill bg-success" style="width:{{ $total > 0 ? round($done/$total*100) : 0 }}%"></div>
                </div>
                <small class="text-muted">{{ $done }} / {{ $total }} {{ __('customer.orders.show.summary.progress') }}</small>
            </div>
        </div>
        @endif

    </div>
</div>

@push('scripts')
<script>
function previewFile(input, idx) {
    const fname  = document.getElementById('fname-' + idx);
    const submit = document.getElementById('submit-' + idx);
    if (input.files && input.files[0]) {
        fname.textContent  = '📎 ' + input.files[0].name;
        fname.classList.remove('d-none');
        submit.classList.remove('d-none');
    }
}
</script>
@endpush
@endsection
