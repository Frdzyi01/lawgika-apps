@extends('layouts-admin.admin')
@section('title', 'Surat Menyurat Dokumen Legal')
@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">📄 Surat Menyurat Dokumen Legal</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">Semua korespondensi dokumen PDF dari customer masuk di sini.</p>
    </div>
    <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size:.85rem;">
      {{ $correspondences->count() }} Surat
    </span>
  </div>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm">
    <ion-icon name="checkmark-circle-outline" style="vertical-align:-3px;"></ion-icon>
    {{ session('success') }}
  </div>
  @endif

  {{-- Cards --}}
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
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div style="flex:1;">
          <div class="d-flex align-items-center gap-2 mb-1">
            <h6 class="fw-bold mb-0">{{ $doc->title }}</h6>
            @php
              $badgeColor = match($doc->status) {
                'done'    => 'success',
                'replied' => 'info',
                default   => 'warning',
              };
            @endphp
            <span class="badge bg-{{ $badgeColor }} rounded-pill px-3 py-1" style="color: {{ in_array($badgeColor, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
              {{ $doc->status_label }}
            </span>
          </div>
          <div class="d-flex flex-wrap gap-3 mb-2">
            <small class="text-muted">
              <ion-icon name="person-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $doc->user->name ?? '-' }}
            </small>
            <small class="text-muted">
              <ion-icon name="mail-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $doc->user->email ?? '-' }}
            </small>
            <small class="text-muted">
              <ion-icon name="calendar-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $doc->created_at->format('d M Y, H:i') }}
            </small>
            @if($doc->replies->count())
            <small style="color:#3b82f6;font-weight:600;">
              <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
              {{ $doc->replies->count() }} balasan
            </small>
            @endif
          </div>
          <p class="text-secondary mb-0"><small>{{ Str::limit($doc->note, 140) }}</small></p>
        </div>
        <div>
          <a href="{{ route('admin.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-primary px-4 fw-semibold shadow-sm rounded-3 text-nowrap">
            <ion-icon name="eye-outline" style="vertical-align:-2px;"></ion-icon>
            Lihat & Balas
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
      <ion-icon name="mail-open-outline" style="font-size:3rem;" class="text-muted"></ion-icon>
      <p class="text-muted mt-3 fw-semibold mb-1">Belum ada surat masuk</p>
      <p class="text-muted"><small>Surat dari customer akan muncul di sini.</small></p>
    </div>
  </div>
  @endforelse

</div>
@endsection
