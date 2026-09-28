@extends('layouts-customer.app')
@section('title', __('customer.surat.create.title'))
@section('content')
<div class="container-fluid py-4" style="max-width:760px;">

  {{-- Breadcrumb --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size:.85rem;">
        <li class="breadcrumb-item">
          <a href="{{ route('customer.dashboard') }}" class="text-decoration-none text-muted"><i class="bi bi-house-door"></i></a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">{{ __('customer.sidebar.correspondence') }}</a>
        </li>
        <li class="breadcrumb-item active">{{ __('customer.surat.index.btn_new') }}</li>
      </ol>
    </nav>
    <a href="{{ route('customer.surat-menyurat.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 shadow-sm rounded-3 px-3">
      <i class="bi bi-arrow-left"></i>
      <span>{{ __('customer.surat.create.btn_cancel') }}</span>
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4 border-top border-4 border-danger">
    <div class="card-body p-4">

      <div class="mb-4">
        <h4 class="fw-bold mb-1 text-dark d-flex align-items-center gap-2">
          <i class="bi bi-file-earmark-plus-fill text-danger"></i>
          <span>{{ __('customer.surat.create.header') }}</span>
        </h4>
        <p class="text-muted mb-0" style="font-size:.88rem;">
          {{ __('customer.surat.create.desc') }}
        </p>
      </div>

      {{-- Errors --}}
      @if(isset($errors) && $errors->any())
      <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm" style="font-size:.87rem;">
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
                 class="form-control form-control-lg fs-6 @error('title') is-invalid @enderror"
                 value="{{ old('title') }}"
                 placeholder="{{ __('customer.surat.create.title_placeholder') }}" required>
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
                    placeholder="{{ __('customer.surat.create.note_placeholder') }}" required>{{ old('note') }}</textarea>
          @error('note')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- Upload PDF --}}
        <div class="mb-4">
          <label for="file" class="form-label fw-semibold">
            {{ __('customer.surat.create.file_label') }} <span class="text-danger">*</span>
          </label>
          <div class="border rounded-3 p-4 text-center bg-light"
               style="border-style:dashed!important;cursor:pointer;"
               onclick="document.getElementById('file').click()">
            <i class="bi bi-cloud-arrow-up fs-1 text-secondary d-block mb-1"></i>
            <p class="mb-1 mt-2 fw-semibold text-secondary">{{ __('customer.surat.create.file_click') }}</p>
            <p class="text-muted mb-0"><small>{{ __('customer.surat.create.file_hint') }}</small></p>
            <p id="file-name-preview" class="text-danger fw-semibold mt-2 mb-0" style="display:none;"></p>
          </div>
          <input type="file" id="file" name="file" accept=".pdf"
                 class="d-none @error('file') is-invalid @enderror" required
                 onchange="if(this.files && this.files[0]){document.getElementById('file-name-preview').textContent='📄 '+this.files[0].name; document.getElementById('file-name-preview').style.display='block';}">
          @error('file')
            <div class="text-danger mt-1"><small>{{ $message }}</small></div>
          @enderror
        </div>

        <div class="d-flex justify-content-end gap-2 pt-2">
          <a href="{{ route('customer.surat-menyurat.index') }}"
             class="btn btn-outline-secondary px-4 fw-semibold rounded-3">
            {{ __('customer.surat.create.btn_cancel') }}
          </a>
          <button type="submit" class="btn btn-danger px-5 fw-semibold shadow-sm rounded-3 d-inline-flex align-items-center gap-2">
            <i class="bi bi-send-fill"></i>
            <span>{{ __('customer.surat.create.btn_submit') }}</span>
          </button>
        </div>

      </form>
    </div>
  </div>

</div>
@endsection
