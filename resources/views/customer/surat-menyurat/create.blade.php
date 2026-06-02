@extends('layouts-customer.app')
@section('title', __('customer.surat.create.title'))
@section('content')
<div class="container-fluid py-4" style="max-width:720px;">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">{{ __('customer.sidebar.correspondence') }}</a>
      </li>
      <li class="breadcrumb-item active">{{ __('customer.surat.index.btn_new') }}</li>
    </ol>
  </nav>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

      <div class="mb-4">
        <h5 class="fw-bold mb-1">{{ __('customer.surat.create.header') }}</h5>
        <p class="text-muted mb-0" style="font-size:.87rem;">
          {{ __('customer.surat.create.desc') }}
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
          <label for="title" class="form-label fw-semibold">
            {{ __('customer.surat.create.title_label') }} <span class="text-danger">*</span>
          </label>
          <input type="text" id="title" name="title"
                 class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}"
                 placeholder="{{ __('customer.surat.create.title_placeholder') }}">
          @error('title')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Note --}}
        <div class="mb-4">
          <label for="note" class="form-label fw-semibold">
            {{ __('customer.surat.create.note_label') }} <span class="text-danger">*</span>
          </label>
          <textarea id="note" name="note" rows="4"
                    class="form-control @error('note') is-invalid @enderror"
                    placeholder="{{ __('customer.surat.create.note_placeholder') }}">{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Upload PDF --}}
        <div class="mb-5">
          <label for="file" class="form-label fw-semibold">
            {{ __('customer.surat.create.file_label') }} <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-4 text-center bg-light"
               style="border-style:dashed!important;cursor:pointer;"
               onclick="document.getElementById('file').click()">
            <ion-icon name="cloud-upload-outline" style="font-size:2.2rem;" class="text-secondary"></ion-icon>
            <p class="mb-1 mt-2 fw-semibold text-secondary">{{ __('customer.surat.create.file_click') }}</p>
            <p class="text-muted mb-0"><small>{{ __('customer.surat.create.file_hint') }}</small></p>
            <p id="file-name-preview" class="text-primary fw-semibold mt-2 mb-0" style="display:none;"></p>
          </div>
          <input type="file" id="file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror"
                 onchange="document.getElementById('file-name-preview').textContent=this.files[0]?.name;
                           document.getElementById('file-name-preview').style.display='block';">
          @error('file')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
          @enderror
        </div>

        <div class="d-flex gap-3">
          <a href="{{ route('customer.surat-menyurat.index') }}"
             class="btn btn-outline-secondary px-4 fw-semibold rounded-3">
            {{ __('customer.surat.create.btn_cancel') }}
          </a>
          <button type="submit" class="btn btn-danger px-5 fw-semibold shadow-sm rounded-3">
            <ion-icon name="send-outline" style="vertical-align:-2px;"></ion-icon>
            {{ __('customer.surat.create.btn_submit') }}
          </button>
        </div>

      </form>
    </div>
  </div>

</div>
@endsection
