@extends('layouts-customer.app')
@section('title', 'Detail Dokumen Legal')
@section('content')
<div class="container-fluid py-4" style="max-width:780px;">

  {{-- Breadcrumb --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb" style="font-size:.85rem;">
      <li class="breadcrumb-item">
        <a href="{{ route('customer.surat-menyurat.index') }}" class="text-decoration-none text-danger">Surat Menyurat</a>
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
  <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
    <div class="card-body p-5">

      <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
          <h5 class="fw-bold mb-1">{{ $correspondence->title }}</h5>
          <small class="text-muted">
            <ion-icon name="calendar-outline" style="vertical-align:-2px;"></ion-icon>
            Dikirim {{ $correspondence->created_at->format('d M Y, H:i') }}
          </small>
        </div>
        @php
          $badgeStyle = match($correspondence->status) {
            'done'    => 'background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;',
            'replied' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;',
            default   => 'background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;',
          };
        @endphp
        <span style="font-size:.82rem;padding:5px 14px;border-radius:99px;font-weight:700;{{ $badgeStyle }}">
          {{ $correspondence->status_label }}
        </span>
      </div>

      {{-- Note --}}
      <div class="rounded-3 p-3 mb-4" style="background:#f8fafc;border:1px solid #e2e8f0;">
        <p class="text-muted mb-1" style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Catatan</p>
        <p class="mb-0" style="font-size:.92rem;line-height:1.6;">{{ $correspondence->note }}</p>
      </div>

      {{-- Download --}}
      <a href="{{ asset('storage/' . $correspondence->file_path) }}"
         target="_blank"
         class="btn btn-outline-danger fw-semibold px-4"
         style="border-radius:10px;font-size:.87rem;">
        <ion-icon name="download-outline" style="vertical-align:-2px;"></ion-icon>
        Download Dokumen PDF
      </a>

    </div>
  </div>

  {{-- Riwayat Balasan (Timeline) --}}
  <h6 class="fw-bold mb-3 text-secondary" style="font-size:.85rem;text-transform:uppercase;letter-spacing:.5px;">
    <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
    Riwayat Balasan ({{ $correspondence->replies->count() }})
  </h6>

  @forelse($correspondence->replies as $reply)
  <div class="card border-0 shadow-sm mb-3"
       style="border-radius:12px;
              border-left:4px solid {{ $reply->sender_role === 'admin' ? '#3b82f6' : '#e11d48' }} !important;">
    <div class="card-body p-4">
      <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
        <span class="fw-bold" style="font-size:.9rem;">
          @if($reply->sender_role === 'admin')
            <ion-icon name="shield-checkmark-outline" style="vertical-align:-2px;color:#3b82f6;"></ion-icon>
            Tim Legal Lawgika
          @else
            <ion-icon name="person-circle-outline" style="vertical-align:-2px;color:#e11d48;"></ion-icon>
            Anda
          @endif
        </span>
        <small class="text-muted">{{ $reply->created_at->format('d M Y, H:i') }}</small>
      </div>
      <p class="mb-3" style="font-size:.88rem;color:#374151;line-height:1.6;">{{ $reply->note }}</p>
      <a href="{{ asset('storage/' . $reply->file_path) }}"
         target="_blank"
         class="btn btn-sm btn-outline-secondary px-3 fw-semibold"
         style="border-radius:8px;font-size:.8rem;">
        <ion-icon name="document-attach-outline" style="vertical-align:-2px;"></ion-icon>
        Download PDF Balasan
      </a>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
    <div class="card-body text-center py-4">
      <ion-icon name="hourglass-outline" style="font-size:2rem;color:#d1d5db;"></ion-icon>
      <p class="text-muted mb-0 mt-2" style="font-size:.87rem;">Belum ada balasan. Menunggu respons dari admin.</p>
    </div>
  </div>
  @endforelse

  <div class="mt-3">
    <a href="{{ route('customer.surat-menyurat.index') }}"
       class="btn btn-outline-secondary px-4 fw-semibold"
       style="border-radius:10px;font-size:.87rem;">
      ← Kembali ke Daftar
    </a>
  </div>

</div>
@endsection
