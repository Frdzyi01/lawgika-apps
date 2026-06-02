@extends('layouts-customer.app')
@section('title', __('customer.surat.index.title'))
@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">{{ __('customer.surat.index.title') }}</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">{{ __('customer.surat.index.desc') }}</p>
    </div>
    <a href="{{ route('customer.surat-menyurat.create') }}"
       class="btn btn-danger px-4 fw-semibold shadow-sm rounded-3">
      <ion-icon name="add-circle-outline" style="vertical-align:-3px;"></ion-icon>
      {{ __('customer.surat.index.btn_new') }}
    </a>
  </div>

  {{-- Flash --}}
  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm">
    <ion-icon name="checkmark-circle-outline" style="vertical-align:-3px;"></ion-icon>
    {{ session('success') }}
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
          <h6 class="fw-bold mb-1" style="font-size:1rem;">{{ $doc->title }}</h6>
          <p class="text-muted mb-2" style="font-size:.85rem;">
            <ion-icon name="calendar-outline" style="vertical-align:-2px;"></ion-icon>
            {{ $doc->created_at->format('d M Y, H:i') }}
          </p>
          <p class="mb-0 text-secondary" style="font-size:.87rem;max-width:600px;">
            {{ Str::limit($doc->note, 120) }}
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
          <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-2" style="color: {{ in_array($badgeColor, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
            {{ $doc->status_label }}
          </span>
          {{-- Replies count --}}
          @if($doc->replies->count())
          <small class="text-muted">
            <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
            {{ $doc->replies->count() }} {{ __('customer.surat.index.balasan') }}
          </small>
          @endif
          <a href="{{ route('customer.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-outline-primary px-3 fw-semibold rounded-3">
            <ion-icon name="eye-outline" style="vertical-align:-2px;"></ion-icon>
            {{ __('customer.surat.index.btn_detail') }}
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
      <ion-icon name="mail-open-outline" style="font-size:3rem;" class="text-muted"></ion-icon>
      <p class="text-muted mt-3 mb-1 fw-semibold">{{ __('customer.surat.index.no_history') }}</p>
      <p class="text-muted" style="font-size:.85rem;">{{ __('customer.surat.index.no_history_desc') }}</p>
      <a href="{{ route('customer.surat-menyurat.create') }}" class="btn btn-danger mt-1 px-4 fw-semibold rounded-3">
        {{ __('customer.surat.index.send_now') }}
      </a>
    </div>
  </div>
  @endforelse

</div>
@endsection
