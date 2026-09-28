@extends('layouts-admin.admin')
@section('title', 'Surat Menyurat Dokumen Legal')
@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold text-dark"><i class="fa-solid fa-file-invoice text-primary me-2"></i>Surat Menyurat Dokumen Legal</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">Semua korespondensi dan pertukaran dokumen resmi antara Admin dan Client.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
      <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size:.85rem;">
        <i class="fa-solid fa-envelope me-1"></i> {{ $correspondences->count() }} Surat
      </span>
      <a href="{{ route('admin.surat-menyurat.create') }}" class="btn btn-sm btn-primary shadow-sm text-nowrap d-inline-flex align-items-center gap-2">
          <i class="fa-solid fa-plus-circle"></i> <span>Kirim Surat Baru</span>
      </a>
    </div>
  </div>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm d-flex align-items-center gap-2">
    <i class="fa-solid fa-circle-check text-success fs-5"></i>
    <div>{{ session('success') }}</div>
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
          <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
            @if($doc->isFromAdmin())
              <span class="badge bg-primary-subtle text-primary border border-primary px-2 py-1 rounded-pill" style="font-size:0.75rem;">
                <i class="fa-solid fa-paper-plane me-1"></i> Surat Keluar
              </span>
            @else
              <span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-pill" style="font-size:0.75rem;">
                <i class="fa-solid fa-inbox me-1"></i> Surat Masuk
              </span>
            @endif

            <h6 class="fw-bold mb-0 text-dark">{{ $doc->title }}</h6>
            
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
            <small class="text-muted d-inline-flex align-items-center gap-1">
              <i class="fa-solid fa-building text-secondary"></i>
              <span><strong>{{ $doc->isFromAdmin() ? 'Tujuan Client:' : 'Pengirim Client:' }}</strong> {{ $doc->user->company_name ? $doc->user->company_name . ' (' . ($doc->user->pic_name ?? $doc->user->name) . ')' : ($doc->user->pic_name ?? $doc->user->name) }}</span>
            </small>
            <small class="text-muted d-inline-flex align-items-center gap-1">
              <i class="fa-regular fa-envelope text-secondary"></i>
              <span>{{ $doc->user->email ?? '-' }}</span>
            </small>
            <small class="text-muted d-inline-flex align-items-center gap-1">
              <i class="fa-regular fa-calendar text-secondary"></i>
              <span>{{ $doc->created_at->format('d M Y, H:i') }} WIB</span>
            </small>
            @if($doc->replies->count())
            <small class="d-inline-flex align-items-center gap-1" style="color:#3b82f6;font-weight:600;">
              <i class="fa-regular fa-comments"></i>
              <span>{{ $doc->replies->count() }} balasan</span>
            </small>
            @endif
          </div>
          <p class="text-secondary mb-0"><small>{{ Str::limit($doc->note, 140) }}</small></p>
        </div>
        <div>
          <a href="{{ route('admin.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-primary px-3 fw-semibold shadow-sm rounded-3 text-nowrap d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-eye"></i>
            <span>Lihat Detail</span>
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body text-center py-5">
      <i class="fa-regular fa-envelope-open fs-1 text-muted mb-3 d-block"></i>
      <p class="text-muted fw-semibold mb-1">Belum ada surat masuk / keluar</p>
      <p class="text-muted"><small>Surat dari customer atau surat yang dikirim admin akan muncul di sini.</small></p>
    </div>
  </div>
  @endforelse

</div>
@endsection
