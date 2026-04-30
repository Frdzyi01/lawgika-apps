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
  <div class="card border-0 shadow-sm rounded-4 border-top border-3 border-success mb-4">
    <div class="card-body p-4">
      <h6 class="fw-bold mb-3">
        <ion-icon name="checkmark-done-circle-outline" style="vertical-align:-2px;color:#22c55e;"></ion-icon>
        Update Status Surat
      </h6>
      <form action="{{ route('admin.surat-menyurat.status', $correspondence->id) }}"
            method="POST" class="d-flex align-items-center gap-3 flex-wrap">
        @csrf
        <select name="status" class="form-select w-auto rounded-3">
          @foreach(['pending' => 'Menunggu', 'replied' => 'Dibalas', 'done' => 'Selesai'] as $val => $label)
          <option value="{{ $val }}" {{ $correspondence->status === $val ? 'selected' : '' }}>
            {{ $label }}
          </option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-success px-4 fw-semibold rounded-3">
          Update Status
        </button>
      </form>
    </div>
  </div>
  
  {{-- ① Detail Dokumen Asli --}}
  <div class="card border-0 shadow-sm mb-4 rounded-4">
    <div class="card-body p-4">

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
          $badgeColor = match($correspondence->status) {
            'done'    => 'success',
            'replied' => 'info',
            default   => 'warning',
          };
        @endphp
        <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-2" style="color: {{ in_array($badgeColor, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
          {{ $correspondence->status_label }}
        </span>
      </div>

      {{-- Note --}}
      <div class="rounded-3 p-3 mb-4 bg-light border">
        <p class="text-muted mb-1 fw-bold"><small>CATATAN DARI CUSTOMER</small></p>
        <p class="mb-0">{{ $correspondence->note }}</p>
      </div>

      {{-- Download --}}
      <a href="{{ asset('storage/' . $correspondence->file_path) }}"
         target="_blank"
         class="btn btn-outline-danger fw-semibold px-4 rounded-3">
        <ion-icon name="download-outline" style="vertical-align:-2px;"></ion-icon>
        Download Dokumen Customer
      </a>

    </div>
  </div>

  {{-- ② Riwayat Balasan --}}
  <h6 class="fw-bold mb-3 text-secondary text-uppercase"><small>
    <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
    Riwayat Balasan ({{ $correspondence->replies->count() }})
  </small></h6>

  @forelse($correspondence->replies as $reply)
  @php
    $replyBorder = $reply->sender_role === 'admin' ? 'border-primary' : 'border-danger';
    $replyIconColor = $reply->sender_role === 'admin' ? 'text-primary' : 'text-danger';
  @endphp
  <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 {{ $replyBorder }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
        <span class="fw-bold">
          @if($reply->sender_role === 'admin')
            <ion-icon name="shield-checkmark-outline" style="vertical-align:-2px;" class="{{ $replyIconColor }}"></ion-icon>
            Admin ({{ $reply->user->name ?? 'Admin' }})
          @else
            <ion-icon name="person-circle-outline" style="vertical-align:-2px;" class="{{ $replyIconColor }}"></ion-icon>
            Customer
          @endif
        </span>
        <small class="text-muted">{{ $reply->created_at->format('d M Y, H:i') }}</small>
      </div>
      <p class="mb-3">{{ $reply->note }}</p>
      <a href="{{ asset('storage/' . $reply->file_path) }}"
         target="_blank"
         class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-3">
        <ion-icon name="document-attach-outline" style="vertical-align:-2px;"></ion-icon>
        Download PDF Balasan
      </a>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body text-center py-4">
      <p class="text-muted mb-0"><small>Belum ada balasan untuk surat ini.</small></p>
    </div>
  </div>
  @endforelse

  {{-- ③ Form Balas --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-3 border-primary">
    <div class="card-body p-4">
      <h6 class="fw-bold mb-3">
        <ion-icon name="send-outline" style="vertical-align:-2px;" class="text-primary"></ion-icon>
        Tulis Balasan
      </h6>

      <form action="{{ route('admin.surat-menyurat.reply', $correspondence->id) }}"
            method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label fw-semibold">
            Catatan Balasan <span class="text-danger">*</span>
          </label>
          <textarea name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    placeholder="Tulis balasan / penjelasan untuk customer...">{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4">
          <label class="form-label fw-semibold">
            Upload PDF Balasan <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-3 text-center bg-light"
               style="border-style:dashed!important;cursor:pointer;"
               onclick="document.getElementById('reply-file').click()">
            <ion-icon name="cloud-upload-outline" style="font-size:1.8rem;" class="text-secondary"></ion-icon>
            <p class="mb-0 mt-1 text-muted"><small>Klik pilih file PDF · Maks 5MB</small></p>
            <p id="reply-file-name" class="text-primary fw-semibold mb-0 mt-1" style="display:none;"></p>
          </div>
          <input type="file" id="reply-file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror"
                 onchange="document.getElementById('reply-file-name').textContent=this.files[0]?.name;
                           document.getElementById('reply-file-name').style.display='block';">
          @error('file')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
          @enderror
        </div>

        <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm rounded-3">
          <ion-icon name="send-outline" style="vertical-align:-2px;"></ion-icon>
          Kirim Balasan
        </button>
      </form>
    </div>
  </div>



</div>
@endsection
