@extends('layouts-admin.admin')
@section('title', 'Detail Surat – ' . $correspondence->title)
@section('content')
<div class="container-fluid py-4" style="max-width:920px;">

  {{-- Top Navigation & Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size:.85rem;">
        <li class="breadcrumb-item">
          <a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted"><i class="fa-solid fa-house"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('admin.surat-menyurat.index') }}" class="text-decoration-none text-primary">Surat Menyurat</a>
        </li>
        <li class="breadcrumb-item active text-truncate" style="max-width:320px;">{{ $correspondence->title }}</li>
      </ol>
    </nav>
    <a href="{{ route('admin.surat-menyurat.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm rounded-3">
      <i class="fa-solid fa-arrow-left"></i>
      <span>Kembali ke Semua Surat</span>
    </a>
  </div>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm d-flex align-items-center gap-2">
    <i class="fa-solid fa-circle-check text-success fs-5"></i>
    <div>{{ session('success') }}</div>
  </div>
  @endif

  {{-- Status & Direction Bar --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-4 {{ $correspondence->isFromAdmin() ? 'border-primary' : 'border-success' }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            @if($correspondence->isFromAdmin())
              <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 rounded-pill fw-semibold" style="font-size:.85rem;">
                <i class="fa-solid fa-paper-plane me-1"></i> Surat Keluar (Admin &rarr; Client)
              </span>
            @else
              <span class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fw-semibold" style="font-size:.85rem;">
                <i class="fa-solid fa-inbox me-1"></i> Surat Masuk (Client &rarr; Admin)
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
              <i class="fa-solid fa-circle-info me-1"></i> Status: {{ $correspondence->status_label }}
            </span>
          </div>
          <h4 class="fw-bold mb-1 text-dark">{{ $correspondence->title }}</h4>
          <small class="text-muted">
            <i class="fa-regular fa-clock me-1"></i> Dibuat pada {{ $correspondence->created_at->format('d M Y, H:i') }} WIB
          </small>
        </div>

        {{-- Form Update Status --}}
        <div>
          <form action="{{ route('admin.surat-menyurat.status', $correspondence->id) }}" method="POST" class="d-flex align-items-center gap-2">
            @csrf
            <select name="status" class="form-select form-select-sm rounded-3 fw-semibold" style="min-width:140px;">
              <option value="pending" {{ $correspondence->status === 'pending' ? 'selected' : '' }}>
                {{ $correspondence->isFromAdmin() ? 'Terkirim / Menunggu' : 'Menunggu Tindakan' }}
              </option>
              <option value="replied" {{ $correspondence->status === 'replied' ? 'selected' : '' }}>Dibalas</option>
              <option value="done" {{ $correspondence->status === 'done' ? 'selected' : '' }}>Selesai</option>
            </select>
            <button type="submit" class="btn btn-sm btn-success px-3 fw-semibold rounded-3 text-nowrap d-inline-flex align-items-center gap-1">
              <i class="fa-solid fa-check"></i>
              <span>Update Status</span>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- Party Information Card (Pengirim & Penerima) --}}
  <div class="row g-3 mb-4">
    {{-- Pengirim --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-2 mb-3 text-secondary">
            <i class="fa-solid fa-circle-arrow-up text-primary fs-5"></i>
            <span class="fw-bold text-uppercase" style="font-size:0.8rem; letter-spacing:0.5px;">Pengirim (From)</span>
          </div>
          @if($correspondence->isFromAdmin())
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="fa-solid fa-user-shield"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Admin Lawgika</h6>
                <p class="text-muted mb-0 small">Surat diterbitkan resmi oleh Tim Admin Lawgika</p>
              </div>
            </div>
          @else
            <div class="d-flex align-items-start gap-3">
              <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.3rem;flex-shrink:0;">
                <i class="fa-solid fa-building"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">{{ $correspondence->user->company_name ?? $correspondence->user->name }}</h6>
                <p class="text-muted mb-1 small">PIC: {{ $correspondence->user->pic_name ?? $correspondence->user->name }}</p>
                <div class="small text-secondary">
                  <div><i class="fa-regular fa-envelope me-1"></i> {{ $correspondence->user->email ?? '-' }}</div>
                  @if($correspondence->user->phone)
                    <div><i class="fa-brands fa-whatsapp me-1 text-success"></i> {{ $correspondence->user->phone }}</div>
                  @endif
                </div>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    {{-- Penerima / Client Tujuan --}}
    <div class="col-md-6">
      <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
        <div class="card-body p-4">
          <div class="d-flex align-items-center gap-2 mb-3 text-secondary">
            <i class="fa-solid fa-circle-arrow-down text-info fs-5"></i>
            <span class="fw-bold text-uppercase" style="font-size:0.8rem; letter-spacing:0.5px;">Penerima / Client Tujuan (To)</span>
          </div>
          @if($correspondence->isFromAdmin())
            <div class="d-flex align-items-start gap-3">
              <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.3rem;flex-shrink:0;">
                <i class="fa-solid fa-building"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">{{ $correspondence->user->company_name ?? $correspondence->user->name }}</h6>
                <p class="text-muted mb-1 small">PIC: {{ $correspondence->user->pic_name ?? $correspondence->user->name }}</p>
                <div class="small text-secondary">
                  <div><i class="fa-regular fa-envelope me-1"></i> {{ $correspondence->user->email ?? '-' }}</div>
                  @if($correspondence->user->phone)
                    <div><i class="fa-brands fa-whatsapp me-1 text-success"></i> {{ $correspondence->user->phone }}</div>
                  @endif
                </div>
              </div>
            </div>
          @else
            <div class="d-flex align-items-center gap-3">
              <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:48px;height:48px;font-size:1.3rem;">
                <i class="fa-solid fa-user-shield"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Tim Lawgika (Admin)</h6>
                <p class="text-muted mb-0 small">Divisi Korespondensi & Legalitas Lawgika</p>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Detail Dokumen & Pesan Surat Utama --}}
  <div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
      <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
        <i class="fa-solid fa-file-lines text-primary"></i>
        <span>Dokumen & Pesan Surat Utama</span>
      </h6>
      <span class="badge bg-light text-secondary border px-3 py-1">Surat Pokok</span>
    </div>
    <div class="card-body p-4">
      {{-- Pesan / Catatan --}}
      <div class="mb-4">
        <label class="form-label fw-bold text-secondary text-uppercase small mb-2">
          @if($correspondence->isFromAdmin())
            <i class="fa-solid fa-comment-dots text-primary me-1"></i> Catatan / Pesan dari Admin untuk Client:
          @else
            <i class="fa-solid fa-comment-dots text-success me-1"></i> Catatan dari Customer / Client:
          @endif
        </label>
        <div class="p-3 bg-light rounded-3 border" style="font-size:0.95rem; line-height:1.6; white-space:pre-line;">
          {{ $correspondence->note }}
        </div>
      </div>

      {{-- File Attachment Box --}}
      <div class="border rounded-3 p-3 bg-white d-flex align-items-center justify-content-between flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="rounded bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width:46px;height:46px;font-size:1.4rem;">
            <i class="fa-solid fa-file-pdf"></i>
          </div>
          <div>
            <h6 class="mb-0 fw-bold text-dark">{{ $correspondence->title }}.pdf</h6>
            <small class="text-muted">Dokumen Resmi (Format PDF)</small>
          </div>
        </div>
        <div>
          <a href="{{ asset('storage/' . $correspondence->file_path) }}" target="_blank"
             class="btn btn-outline-danger fw-semibold px-4 rounded-3 d-inline-flex align-items-center gap-2 shadow-sm">
            <i class="fa-solid fa-download"></i>
            <span>Unduh / Buka Dokumen PDF</span>
          </a>
        </div>
      </div>
    </div>
  </div>

  {{-- Riwayat Percakapan & Balasan (Thread Timeline) --}}
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h6 class="fw-bold mb-0 text-secondary text-uppercase d-flex align-items-center gap-2">
      <i class="fa-regular fa-comments"></i>
      <span>Riwayat Balasan & Dokumen Tambahan ({{ $correspondence->replies->count() }})</span>
    </h6>
  </div>

  @forelse($correspondence->replies as $reply)
  @php
    $isAdminReply = $reply->sender_role === 'admin';
    $replyBorder = $isAdminReply ? 'border-primary' : 'border-success';
    $badgeBg = $isAdminReply ? 'bg-primary-subtle text-primary border-primary' : 'bg-success-subtle text-success border-success';
    $roleName = $isAdminReply ? 'Admin (' . ($reply->user->name ?? 'Admin Lawgika') . ')' : 'Client (' . ($reply->user->pic_name ?? $reply->user->name ?? 'Client') . ')';
  @endphp
  <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 {{ $replyBorder }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
        <span class="badge {{ $badgeBg }} border px-3 py-1 rounded-pill fw-semibold">
          <i class="{{ $isAdminReply ? 'fa-solid fa-user-shield' : 'fa-solid fa-circle-user' }} me-1"></i>
          {{ $roleName }}
        </span>
        <small class="text-muted"><i class="fa-regular fa-clock me-1"></i> {{ $reply->created_at->format('d M Y, H:i') }} WIB</small>
      </div>

      <div class="mb-3 mt-2 text-dark" style="line-height:1.6; white-space:pre-line;">
        {{ $reply->note }}
      </div>

      @if(!empty($reply->file_path))
      <div class="d-inline-block">
        <a href="{{ asset('storage/' . $reply->file_path) }}" target="_blank"
           class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-3 d-inline-flex align-items-center gap-2">
          <i class="fa-solid fa-paperclip text-danger"></i>
          <span>Unduh PDF Balasan</span>
        </a>
      </div>
      @endif
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body text-center py-4">
      <i class="fa-regular fa-comments fs-3 text-muted mb-2 d-block"></i>
      <p class="text-muted mb-0"><small>Belum ada balasan atau lampiran lanjutan untuk surat ini.</small></p>
    </div>
  </div>
  @endforelse

  {{-- Form Kirim Balasan / Dokumen Lanjutan --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-4 border-primary">
    <div class="card-header bg-white py-3 border-bottom">
      <h6 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
        <i class="fa-solid fa-reply text-primary"></i>
        <span>Kirim Balasan / Lampiran Tambahan</span>
      </h6>
      <small class="text-muted">Kirimkan catatan atau dokumen PDF lanjutan kepada client terkait surat ini.</small>
    </div>
    <div class="card-body p-4">
      <form action="{{ route('admin.surat-menyurat.reply', $correspondence->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Catatan / Pesan Balasan <span class="text-danger">*</span>
          </label>
          <textarea name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    placeholder="Tuliskan catatan, tanggapan, atau penjelasan untuk client di sini..." required>{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            Unggah File PDF Balasan / Lampiran <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-3 text-center bg-light"
               style="border-style:dashed!important;cursor:pointer;"
               onclick="document.getElementById('reply-file').click()">
            <i class="fa-solid fa-cloud-arrow-up fs-2 text-secondary d-block mb-1"></i>
            <p class="mb-0 mt-1 text-muted"><small>Klik di sini untuk memilih file PDF · Maksimal 5MB</small></p>
            <p id="reply-file-name" class="text-primary fw-semibold mb-0 mt-1" style="display:none;"></p>
          </div>
          <input type="file" id="reply-file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror" required
                 onchange="document.getElementById('reply-file-name').textContent=this.files[0]?.name;
                           document.getElementById('reply-file-name').style.display='block';">
          @error('file')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
          @enderror
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm rounded-3 d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-paper-plane"></i>
            <span>Kirim Balasan</span>
          </button>
        </div>
      </form>
    </div>
  </div>

</div>
@endsection
