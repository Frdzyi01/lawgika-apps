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
  <div class="card border-0 shadow-sm mb-3"
       style="border-radius:14px;
              border-left:4px solid
              {{ $doc->status === 'done' ? '#22c55e' : ($doc->status === 'replied' ? '#3b82f6' : '#9ca3af') }} !important;">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div style="flex:1;">
          <div class="d-flex align-items-center gap-2 mb-1">
            <h6 class="fw-bold mb-0" style="font-size:.97rem;">{{ $doc->title }}</h6>
            @php
              $badgeStyle = match($doc->status) {
                'done'    => 'background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;',
                'replied' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;',
                default   => 'background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;',
              };
            @endphp
            <span style="font-size:.72rem;padding:3px 10px;border-radius:99px;font-weight:700;{{ $badgeStyle }}">
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
          <p class="text-secondary mb-0" style="font-size:.86rem;">{{ Str::limit($doc->note, 140) }}</p>
        </div>
        <div>
          <a href="{{ route('admin.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-primary px-4 fw-semibold shadow-sm"
             style="border-radius:9px;font-size:.82rem;white-space:nowrap;">
            <ion-icon name="eye-outline" style="vertical-align:-2px;"></ion-icon>
            Lihat & Balas
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body text-center py-5">
      <ion-icon name="mail-open-outline" style="font-size:3rem;color:#d1d5db;"></ion-icon>
      <p class="text-muted mt-3 fw-semibold mb-1">Belum ada surat masuk</p>
      <p class="text-muted" style="font-size:.85rem;">Surat dari customer akan muncul di sini.</p>
    </div>
  </div>
  @endforelse

</div>
@endsection
