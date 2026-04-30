@extends('layouts-customer.app')
@section('title', 'Kirim Dokumen Legal')
@section('content')
<div class="container-fluid py-4" style="max-width:720px;">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">Surat Menyurat</a>
      </li>
      <li class="breadcrumb-item active">Kirim Dokumen Baru</li>
    </ol>
  </nav>

  <div class="card border-0 shadow-sm" style="border-radius:16px;">
    <div class="card-body p-5">

      <div class="mb-4">
        <h5 class="fw-bold mb-1">📤 Kirim Dokumen Legal (PDF)</h5>
        <p class="text-muted mb-0" style="font-size:.87rem;">
          Upload file PDF beserta catatan ke tim legal kami. Maksimal 5MB.
        </p>
      </div>

      {{-- Errors --}}
      @if($errors->any())
      <div class="alert alert-danger border-0 rounded-3 mb-4" style="font-size:.87rem;">
        <ul class="mb-0 ps-3">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
      @endif

      <form action="{{ route('customer.surat-menyurat.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Judul --}}
        <div class="mb-4">
          <label for="title" class="form-label fw-semibold" style="font-size:.9rem;">
            Judul Dokumen <span class="text-danger">*</span>
          </label>
          <input type="text" id="title" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 style="border-radius:10px;padding:10px 14px;"
                 value="{{ old('title') }}"
                 placeholder="Contoh: Permohonan Perubahan Anggaran Dasar">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Note --}}
        <div class="mb-4">
          <label for="note" class="form-label fw-semibold" style="font-size:.9rem;">
            Catatan / Keterangan <span class="text-danger">*</span>
          </label>
          <textarea id="note" name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    style="border-radius:10px;padding:10px 14px;resize:vertical;"
                    placeholder="Jelaskan keperluan atau konteks dokumen ini...">{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Upload PDF --}}
        <div class="mb-5">
          <label for="file" class="form-label fw-semibold" style="font-size:.9rem;">
            Upload File PDF <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-4 text-center"
               style="border-style:dashed!important;border-color:#d1d5db;background:#f9fafb;cursor:pointer;"
               onclick="document.getElementById('file').click()">
            <ion-icon name="cloud-upload-outline" style="font-size:2.2rem;color:#9ca3af;"></ion-icon>
            <p class="mb-1 mt-2 fw-semibold text-secondary" style="font-size:.9rem;">Klik untuk pilih file PDF</p>
            <p class="text-muted mb-0" style="font-size:.8rem;">Format: PDF · Maks. 5MB</p>
            <p id="file-name-preview" class="text-primary fw-semibold mt-2 mb-0" style="font-size:.85rem;display:none;"></p>
          </div>
          <input type="file" id="file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror"
                 onchange="document.getElementById('file-name-preview').textContent=this.files[0]?.name;
                           document.getElementById('file-name-preview').style.display='block';">
          @error('file')
            <div class="text-danger mt-1" style="font-size:.83rem;">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex gap-3">
          <a href="{{ route('customer.surat-menyurat.index') }}"
             class="btn btn-outline-secondary px-4 fw-semibold"
             style="border-radius:10px;">
            Batal
          </a>
          <button type="submit" class="btn btn-danger px-5 fw-semibold shadow-sm" style="border-radius:10px;">
            <ion-icon name="send-outline" style="vertical-align:-2px;"></ion-icon>
            Kirim Dokumen
          </button>
        </div>

      </form>
    </div>
  </div>

</div>
@endsection
