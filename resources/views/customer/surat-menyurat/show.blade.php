@extends('layouts-customer.app')
@section('title', $correspondence->title . ' - ' . __('customer.surat.show.title'))
@section('content')
<div class="container-fluid py-4" style="max-width:920px;">

  {{-- Top Navigation & Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size:.85rem;">
        <li class="breadcrumb-item">
          <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-muted"><i class="bi bi-house-door"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">{{ __('customer.sidebar.correspondence') }}</a>
        </li>
        <li class="breadcrumb-item active text-truncate" style="max-width:280px;">{{ $correspondence->title }}</li>
      </ol>
    </nav>
    <a href="{{ route('customer.surat-menyurat.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm rounded-3 px-3">
      <i class="bi bi-arrow-left"></i>
      <span>{{ __('customer.surat.show.btn_back') }}</span>
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <div>{{ session('success') }}</div>
  </div>
  @endif

  @if(isset($errors) && $errors->any())
  <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm">
    <ul class="mb-0 ps-3">
      @foreach($errors->all() as $err)
        <li>{{ $err }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  {{-- Status & Direction Bar --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-4 {{ $correspondence->isFromAdmin() ? 'border-primary' : 'border-danger' }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <div class="d-flex align-items-center gap-2 flex-wrap">
          @if($correspondence->isFromAdmin())
            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill fw-semibold" style="font-size:.85rem;">
              <i class="bi bi-shield-check me-1"></i> {{ __('customer.surat.show.badge_from_lawgika') }}
            </span>
          @else
            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 rounded-pill fw-semibold" style="font-size:.85rem;">
              <i class="bi bi-send-check me-1"></i> {{ __('customer.surat.show.badge_from_me') }}
            </span>
          @endif

          @php
            $badgeColor = match($correspondence->status) {
              'done'    => 'success',
              'replied' => 'info',
              default   => 'warning',
            };
          @endphp
          <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-2" style="color: {{ in_array($badgeColor, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important; font-size:.85rem;">
            <i class="bi bi-info-circle me-1"></i> {{ __('customer.surat.show.status') }}: {{ $correspondence->status_label }}
          </span>
        </div>
        <small class="text-muted d-inline-flex align-items-center gap-1">
          <i class="bi bi-clock"></i>
          <span>{{ $correspondence->created_at->format('d M Y, H:i') }} WIB</span>
        </small>
      </div>
      <h3 class="fw-bold mb-0 text-dark">{{ $correspondence->title }}</h3>
    </div>
  </div>

  {{-- Card Metadata Pengirim & Penerima --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
      <h6 class="fw-bold text-secondary text-uppercase mb-3" style="font-size:0.8rem; letter-spacing:0.5px;">
        <i class="bi bi-people me-1"></i> {{ __('customer.surat.show.info_title') }}
      </h6>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3 border h-100">
            <small class="text-muted d-block mb-1 fw-semibold text-uppercase" style="font-size:0.75rem;">
              {{ __('customer.surat.show.sender') }}
            </small>
            @if($correspondence->isFromAdmin())
              <div class="fw-bold text-primary d-flex align-items-center gap-1" style="font-size:0.95rem;">
                <i class="bi bi-patch-check-fill"></i>
                <span>Tim Lawgika (Official)</span>
              </div>
              <small class="text-muted">Administrator Resmi Lawgika</small>
            @else
              <div class="fw-bold text-dark" style="font-size:0.95rem;">{{ $correspondence->user->name ?? 'Anda (Client)' }}</div>
              <small class="text-muted">{{ $correspondence->user->email ?? '' }}</small>
            @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3 border h-100">
            <small class="text-muted d-block mb-1 fw-semibold text-uppercase" style="font-size:0.75rem;">
              {{ __('customer.surat.show.recipient') }}
            </small>
            @if($correspondence->isFromAdmin())
              <div class="fw-bold text-dark" style="font-size:0.95rem;">{{ $correspondence->user->name ?? 'Anda (Client)' }}</div>
              <small class="text-muted">{{ $correspondence->user->email ?? '' }}</small>
            @else
              <div class="fw-bold text-danger d-flex align-items-center gap-1" style="font-size:0.95rem;">
                <i class="bi bi-building"></i>
                <span>Tim Layanan Lawgika</span>
              </div>
              <small class="text-muted">Divisi Legal & Operasional</small>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Detail Dokumen & Catatan Surat Utama --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
      <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
        <i class="bi bi-file-earmark-text-fill {{ $correspondence->isFromAdmin() ? 'text-primary' : 'text-danger' }}"></i>
        <span>{{ __('customer.surat.show.doc_card_title') }}</span>
      </h6>
      <span class="badge bg-light text-secondary border px-3 py-1">Dokumen Utama</span>
    </div>
    <div class="card-body p-4">
      {{-- Note --}}
      <div class="mb-4">
        <label class="form-label fw-bold text-secondary text-uppercase small mb-2 d-flex align-items-center gap-1">
          @if($correspondence->isFromAdmin())
            <i class="bi bi-chat-quote-fill text-primary"></i>
            <span>{{ __('customer.surat.show.catatan_lawgika') }}:</span>
          @else
            <i class="bi bi-chat-quote-fill text-danger"></i>
            <span>{{ __('customer.surat.show.catatan_me') }}:</span>
          @endif
        </label>
        <div class="p-3 bg-light rounded-3 border text-dark" style="font-size:0.95rem; line-height:1.6; white-space:pre-line;">
          {{ $correspondence->note }}
        </div>
      </div>

      {{-- Download Attachment Box --}}
      <div class="border rounded-3 p-3 bg-white d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-xs">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded-3 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center p-2" style="width:48px;height:48px;flex-shrink:0;">
            <i class="bi bi-file-earmark-pdf-fill fs-2"></i>
          </div>
          <div>
            <h6 class="mb-0 fw-bold text-dark">{{ $correspondence->title }}.pdf</h6>
            <small class="text-muted">{{ __('customer.surat.show.official_doc') }}</small>
          </div>
        </div>
        <div>
          <a href="{{ asset('storage/' . $correspondence->file_path) }}"
             target="_blank"
             class="btn btn-danger fw-semibold px-4 rounded-3 d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="bi bi-cloud-arrow-down-fill"></i>
            <span>{{ __('customer.surat.show.btn_download') }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Riwayat Balasan (Timeline) --}}
  <h6 class="fw-bold mb-3 text-secondary text-uppercase d-flex align-items-center gap-2" style="font-size:0.8rem; letter-spacing:0.5px;">
    <i class="bi bi-chat-left-dots"></i>
    <span>{{ __('customer.surat.show.history_title') }} ({{ $correspondence->replies->count() }})</span>
  </h6>

  @forelse($correspondence->replies as $reply)
  @php
    $isAdminReply = $reply->sender_role === 'admin';
    $replyBorder = $isAdminReply ? 'border-primary' : 'border-danger';
    $replyBadgeBg = $isAdminReply ? 'bg-primary-subtle text-primary border-primary' : 'bg-danger-subtle text-danger border-danger';
    $senderTitle = $isAdminReply ? __('customer.surat.show.sender_admin') : __('customer.surat.show.sender_me');
  @endphp
  <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 {{ $replyBorder }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <span class="badge {{ $replyBadgeBg }} border px-3 py-1 rounded-pill fw-semibold" style="font-size:0.82rem;">
          <i class="{{ $isAdminReply ? 'bi bi-shield-fill-check' : 'bi bi-person-fill' }} me-1"></i>
          {{ $senderTitle }}
        </span>
        <small class="text-muted d-inline-flex align-items-center gap-1">
          <i class="bi bi-clock"></i>
          <span>{{ $reply->created_at->format('d M Y, H:i') }} WIB</span>
        </small>
      </div>
      <div class="mb-3 mt-2 text-dark" style="line-height:1.6; white-space:pre-line; font-size:0.95rem;">
        {{ $reply->note }}
      </div>
      @if(!empty($reply->file_path))
      <a href="{{ asset('storage/' . $reply->file_path) }}"
         target="_blank"
         class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
        <i class="bi bi-paperclip text-danger"></i>
        <span>{{ __('customer.surat.show.btn_download_reply') }}</span>
      </a>
      @endif
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body text-center py-4">
      <i class="bi bi-chat-dots fs-1 text-muted mb-2 d-block"></i>
      <p class="text-muted mb-0" style="font-size:0.9rem;">
        @if($correspondence->isFromAdmin())
          {{ __('customer.surat.show.empty_history_admin') }}
        @else
          {{ __('customer.surat.show.empty_history_me') }}
        @endif
      </p>
    </div>
  </div>
  @endforelse

  {{-- Form Kirim Tanggapan / Balasan untuk Customer --}}
  @if($correspondence->status !== 'done')
  <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-4 border-danger">
    <div class="card-header bg-white py-3 border-bottom">
      <h6 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
        <i class="bi bi-reply-fill text-danger"></i>
        <span>{{ __('customer.surat.show.reply_form_title') }}</span>
      </h6>
      <small class="text-muted">{{ __('customer.surat.show.reply_form_desc') }}</small>
    </div>
    <div class="card-body p-4">
      <form action="{{ route('customer.surat-menyurat.reply', $correspondence->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">
            {{ __('customer.surat.show.reply_note_label') }} <span class="text-danger">*</span>
          </label>
          <textarea name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    placeholder="{{ __('customer.surat.show.reply_note_placeholder') }}" required>{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            {{ __('customer.surat.show.reply_file_label') }} <small class="text-muted fw-normal">{{ __('customer.surat.show.reply_file_optional') }}</small>
          </label>
          <div class="border rounded-3 p-3 text-center bg-light"
               style="border-style:dashed!important;cursor:pointer;"
               onclick="document.getElementById('client-reply-file').click()">
            <i class="bi bi-cloud-arrow-up fs-2 text-secondary d-block mb-1"></i>
            <p class="mb-0 mt-1 text-muted"><small>{{ __('customer.surat.show.reply_file_hint') }}</small></p>
            <p id="client-reply-file-name" class="text-danger fw-semibold mb-0 mt-2" style="display:none;"></p>
          </div>
          <input type="file" id="client-reply-file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror"
                 onchange="if(this.files && this.files[0]){document.getElementById('client-reply-file-name').textContent='📄 '+this.files[0].name; document.getElementById('client-reply-file-name').style.display='block';}">
          @error('file')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
          @enderror
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-danger px-5 fw-semibold shadow-sm rounded-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-send-fill"></i>
            <span>{{ __('customer.surat.show.reply_btn_submit') }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
  @else
  <div class="alert alert-success border-0 rounded-4 shadow-sm p-3 d-flex align-items-center gap-2 mb-4">
    <i class="bi bi-check-circle-fill text-success fs-4"></i>
    <div>
      <strong>{{ __('customer.surat.show.done_notice') }}</strong>
    </div>
  </div>
  @endif

</div>
@endsection
