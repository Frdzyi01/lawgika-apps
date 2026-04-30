@extends('layouts-admin.admin')
@section('title', 'Detail Surat – ' . $correspondence->title)
@section('content')
<div class="container-fluid py-4" style="max-width:820px;">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('admin.surat-menyurat.index') }}" class="text-decoration-none text-primary">Surat Menyurat</a>
      </li>
      <li class="breadcrumb-item active text-truncate" style="max-width:350px;">{{ $correspondence->title }}</li>
    </ol>
  </nav>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm">
    <ion-icon name="checkmark-circle-outline" style="vertical-align:-3px;"></ion-icon>
    {{ session('success') }}
  </div>
  @endif

    {{-- ④ Update Status --}}
  <div class="card border-0 shadow-sm" style="border-radius:16px;border-top:3px solid #22c55e!important;">
    <div class="card-body p-4">
      <h6 class="fw-bold mb-3">
        <ion-icon name="checkmark-done-circle-outline" style="vertical-align:-2px;color:#22c55e;"></ion-icon>
        Update Status Surat
      </h6>
      <form action="{{ route('admin.surat-menyurat.status', $correspondence->id) }}"
            method="POST" class="d-flex align-items-center gap-3 flex-wrap">
        @csrf
        <select name="status" class="form-select" style="max-width:200px;border-radius:10px;">
          @foreach(['pending' => 'Menunggu', 'replied' => 'Dibalas', 'done' => 'Selesai'] as $val => $label)
          <option value="{{ $val }}" {{ $correspondence->status === $val ? 'selected' : '' }}>
            {{ $label }}
          </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-success px-4 fw-semibold" style="border-radius:10px;">
          Update Status
        </button>
      </form>
    </div>
  </div>
  
  {{-- ① Detail Dokumen Asli --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-5">

      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
          <h5 class="fw-bold mb-1">{{ $correspondence->title }}</h5>
          <div class="d-flex gap-3 flex-wrap">
            <small class="text-muted">
              <ion-icon name="person-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $correspondence->user->name ?? '-' }}
              <span class="text-secondary">({{ $correspondence->user->email ?? '' }})</span>
            </small>
            <small class="text-muted">
              <ion-icon name="calendar-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $correspondence->created_at->format('d M Y, H:i') }}
            </small>
          </div>
        </div>
        @php
          $badgeStyle = match($correspondence->status) {
            'done'    => 'background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;',
            'replied' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;',
            default   => 'background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;',
          };
        @endphp
        <span style="font-size:.82rem;padding:5px 14px;border-radius:99px;font-weight:700;{{ $badgeStyle }}">
          {{ $correspondence->status_label }}
        </span>
      </div>

      {{-- Note --}}
      <div class="rounded-3 p-3 mb-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
        <p class="text-muted mb-1" style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Catatan dari Customer</p>
        <p class="mb-0" style="font-size:.92rem;line-height:1.7;">{{ $correspondence->note }}</p>
      </div>

      {{-- Download --}}
      <a href="{{ asset('storage/' . $correspondence->file_path) }}"
         target="_blank"
         class="btn btn-outline-danger fw-semibold px-4"
         style="border-radius:10px;font-size:.87rem;">
        <ion-icon name="download-outline" style="vertical-align:-2px;"></ion-icon>
        Download Dokumen Customer
      </a>

    </div>
  </div>

  
  {{-- ② Riwayat Balasan --}}
  <h6 class="fw-bold mb-3 text-secondary" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.5px;">
    <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
    Riwayat Balasan ({{ $correspondence->replies->count() }})
  </h6>

  @forelse($correspondence->replies as $reply)
  <div class="card border-0 shadow-sm mb-3"
       style="border-radius:12px;
              border-left:4px solid {{ $reply->sender_role === 'admin' ? '#3b82f6' : '#e11d48' }} !important;">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
        <span class="fw-bold" style="font-size:.88rem;">
          @if($reply->sender_role === 'admin')
            <ion-icon name="shield-checkmark-outline" style="vertical-align:-2px;color:#3b82f6;"></ion-icon>
            Admin ({{ $reply->user->name ?? 'Admin' }})
          @else
            <ion-icon name="person-circle-outline" style="vertical-align:-2px;color:#e11d48;"></ion-icon>
            Customer
          @endif
        </span>
        <small class="text-muted">{{ $reply->created_at->format('d M Y, H:i') }}</small>
      </div>
      <p class="mb-3" style="font-size:.88rem;line-height:1.6;color:#374151;">{{ $reply->note }}</p>
      <a href="{{ asset('storage/' . $reply->file_path) }}"
         target="_blank"
         class="btn btn-sm btn-outline-secondary px-3 fw-semibold"
         style="border-radius:8px;font-size:.8rem;">
        <ion-icon name="document-attach-outline" style="vertical-align:-2px;"></ion-icon>
        Download PDF Balasan
      </a>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body text-center py-4">
      <p class="text-muted mb-0" style="font-size:.86rem;">Belum ada balasan untuk surat ini.</p>
    </div>
  </div>
  @endforelse

  {{-- ③ Form Balas --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;border-top:3px solid #3b82f6!important;">
    <div class="card-body p-4">
      <h6 class="fw-bold mb-3">
        <ion-icon name="send-outline" style="vertical-align:-2px;color:#3b82f6;"></ion-icon>
        Tulis Balasan
      </h6>

      <form action="{{ route('admin.surat-menyurat.reply', $correspondence->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold" style="font-size:.88rem;">
            Catatan Balasan <span class="text-danger">*</span>
          </label>
          <textarea name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    style="border-radius:10px;padding:10px 14px;resize:vertical;"
                    placeholder="Tulis balasan / penjelasan untuk customer...">{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold" style="font-size:.88rem;">
            Upload PDF Balasan <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-3 text-center"
               style="border-style:dashed!important;border-color:#d1d5db;background:#f8fafc;cursor:pointer;"
               onclick="document.getElementById('reply-file').click()">
            <ion-icon name="cloud-upload-outline" style="font-size:1.8rem;color:#9ca3af;"></ion-icon>
            <p class="mb-0 mt-1 text-muted" style="font-size:.83rem;">Klik pilih file PDF · Maks 5MB</p>
            <p id="reply-file-name" class="text-primary fw-semibold mb-0 mt-1" style="font-size:.83rem;display:none;"></p>
          </div>
          <input type="file" id="reply-file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror"
                 onchange="document.getElementById('reply-file-name').textContent=this.files[0]?.name;
                           document.getElementById('reply-file-name').style.display='block';">
          @error('file')
            <div class="text-danger mt-1" style="font-size:.82rem;">{{ $message }}</div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm" style="border-radius:10px;">
          <ion-icon name="send-outline" style="vertical-align:-2px;"></ion-icon>
          Kirim Balasan
        </button>
      </form>
    </div>
  </div>



</div>
@endsection
