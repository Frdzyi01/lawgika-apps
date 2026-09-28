@extends('layouts-customer.app')
@section('title', __('customer.surat.index.title'))
@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold text-dark d-flex align-items-center gap-2">
        <i class="bi bi-envelope-paper-fill text-danger"></i>
        <span>{{ __('customer.surat.index.title') }}</span>
      </h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">{{ __('customer.surat.index.desc') }}</p>
    </div>
    <a href="{{ route('customer.surat-menyurat.create') }}"
       class="btn btn-danger px-4 fw-semibold shadow-sm rounded-3 d-inline-flex align-items-center gap-2">
      <i class="bi bi-plus-circle-fill"></i>
      <span>{{ __('customer.surat.index.btn_new') }}</span>
    </a>
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm d-flex align-items-center gap-2">
    <i class="bi bi-check-circle-fill text-success fs-5"></i>
    <div>{{ session('success') }}</div>
  </div>
  @endif

  {{-- Card Grid --}}
  @forelse($correspondences as $doc)
  @php
    $borderColor = match($doc->status) {
      'done'    => 'border-success',
      'replied' => 'border-primary',
      default   => 'border-secondary',
    };
  @endphp
  <div class="card border-0 shadow-sm rounded-4 mb-3 border-start border-4 {{ $borderColor }}">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            @if($doc->isFromAdmin())
              <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-1 rounded-pill fw-semibold" style="font-size:0.78rem;">
                <i class="bi bi-shield-check me-1"></i> {{ __('customer.surat.index.from_lawgika') }}
              </span>
            @else
              <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-1 rounded-pill fw-semibold" style="font-size:0.78rem;">
                <i class="bi bi-send-fill me-1"></i> {{ __('customer.surat.index.from_me') }}
              </span>
            @endif
            <h5 class="fw-bold mb-0 text-dark">{{ $doc->title }}</h5>
          </div>
          <p class="text-muted mb-2 d-inline-flex align-items-center gap-1" style="font-size:.85rem;">
            <i class="bi bi-calendar-event"></i>
            <span>{{ $doc->created_at->format('d M Y, H:i') }} WIB</span>
          </p>
          <p class="mb-0 text-secondary" style="font-size:.88rem;max-width:620px;line-height:1.5;">
            {{ Str::limit($doc->note, 140) }}
          </p>
        </div>
        <div class="d-flex flex-column align-items-end gap-2">
          {{-- Status Badge --}}
          @php
            $badgeColor = match($doc->status) {
              'done'    => 'success',
              'replied' => 'info',
              default   => 'warning',
            };
          @endphp
          <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-2 fw-semibold" style="color: {{ in_array($badgeColor, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important; font-size:0.82rem;">
            <i class="bi bi-info-circle me-1"></i> {{ $doc->status_label }}
          </span>
          {{-- Replies count --}}
          @if($doc->replies->count())
          <small class="text-muted d-inline-flex align-items-center gap-1">
            <i class="bi bi-chat-left-text"></i>
            <span>{{ $doc->replies->count() }} {{ __('customer.surat.index.balasan') }}</span>
          </small>
          @endif
          <a href="{{ route('customer.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-outline-primary px-3 fw-semibold rounded-3 d-inline-flex align-items-center gap-2 mt-1">
            <i class="bi bi-eye"></i>
            <span>{{ __('customer.surat.index.btn_detail') }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
      <i class="bi bi-envelope-open fs-1 text-muted mb-3 d-block"></i>
      <p class="text-muted mb-1 fw-semibold fs-6">{{ __('customer.surat.index.no_history') }}</p>
      <p class="text-muted" style="font-size:.85rem;">{{ __('customer.surat.index.no_history_desc') }}</p>
      <a href="{{ route('customer.surat-menyurat.create') }}" class="btn btn-danger mt-2 px-4 fw-semibold rounded-3 shadow-sm d-inline-flex align-items-center gap-2">
        <i class="bi bi-plus-circle-fill"></i>
        <span>{{ __('customer.surat.index.send_now') }}</span>
      </a>
    </div>
  </div>
  @endforelse

</div>
@endsection
