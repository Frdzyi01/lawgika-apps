@extends('layouts-customer.app')
@section('title', __('customer.surat.show.title'))
@section('content')
<div class="container-fluid py-4" style="max-width:780px;">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">{{ __('customer.sidebar.correspondence') }}</a>
      </li>
      <li class="breadcrumb-item active text-truncate" style="max-width:300px;">{{ $correspondence->title }}</li>
    </ol>
  </nav>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm">
    <ion-icon name="checkmark-circle-outline" style="vertical-align:-3px;"></ion-icon>
    {{ session('success') }}
  </div>
  @endif

  {{-- Detail Card --}}
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">

      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
          <h5 class="fw-bold mb-1">{{ $correspondence->title }}</h5>
          <small class="text-muted">
            <ion-icon name="calendar-outline" style="vertical-align:-2px;"></ion-icon>
            Dikirim {{ $correspondence->created_at->format('d M Y, H:i') }}
          </small>
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
        <p class="text-muted mb-1 fw-bold"><small>{{ __('customer.surat.show.catatan') }}</small></p>
        <p class="mb-0">{{ $correspondence->note }}</p>
      </div>

      {{-- Download --}}
      <a href="{{ asset('storage/' . $correspondence->file_path) }}"
         target="_blank"
         class="btn btn-outline-danger fw-semibold px-4 rounded-3">
        <ion-icon name="download-outline" style="vertical-align:-2px;"></ion-icon>
        {{ __('customer.surat.show.btn_download') }}
      </a>

    </div>
  </div>

  {{-- {{ __('customer.surat.show.history_title') }} (Timeline) --}}
  <h6 class="fw-bold mb-3 text-secondary text-uppercase"><small>
    <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
    {{ __('customer.surat.show.history_title') }} ({{ $correspondence->replies->count() }})
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
            {{ __('customer.surat.show.sender_admin') }}
          @else
            <ion-icon name="person-circle-outline" style="vertical-align:-2px;" class="{{ $replyIconColor }}"></ion-icon>
            {{ __('customer.surat.show.sender_user') }}
          @endif
        </span>
        <small class="text-muted">{{ $reply->created_at->format('d M Y, H:i') }}</small>
      </div>
      <p class="mb-3">{{ $reply->note }}</p>
      <a href="{{ asset('storage/' . $reply->file_path) }}"
         target="_blank"
         class="btn btn-sm btn-outline-secondary px-3 fw-semibold rounded-3">
        <ion-icon name="document-attach-outline" style="vertical-align:-2px;"></ion-icon>
        {{ __('customer.surat.show.btn_download_reply') }}
      </a>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body text-center py-4">
      <ion-icon name="hourglass-outline" style="font-size:2rem;" class="text-muted"></ion-icon>
      <p class="text-muted mb-0 mt-2">{{ __('customer.surat.show.no_replies') }}</p>
    </div>
  </div>
  @endforelse

  <div class="mt-3">
    <a href="{{ route('customer.surat-menyurat.index') }}"
       class="btn btn-outline-secondary px-4 fw-semibold rounded-3">
      {{ __('customer.surat.show.btn_back') }}
    </a>
  </div>

</div>
@endsection
