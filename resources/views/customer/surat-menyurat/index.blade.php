@extends('layouts-customer.app')
@section('title', 'Surat Menyurat Dokumen Legal')
@section('content')
<div class="container-fluid py-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">📄 Surat Menyurat Dokumen Legal</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">Kelola korespondensi PDF Anda dengan tim legal kami.</p>
    </div>
    <a href="{{ route('customer.surat-menyurat.create') }}"
       class="btn btn-danger px-4 fw-semibold shadow-sm"
       style="border-radius:10px;">
      <ion-icon name="add-circle-outline" style="vertical-align:-3px;"></ion-icon>
      Kirim Dokumen Baru
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
  <div class="card border-0 shadow-sm mb-3"
       style="border-radius:14px; border-left:4px solid
         {{ $doc->status === 'done' ? '#22c55e' : ($doc->status === 'replied' ? '#3b82f6' : '#9ca3af') }} !important;">
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
            $badgeStyle = match($doc->status) {
              'done'    => 'background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0;',
              'replied' => 'background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;',
              default   => 'background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;',
            };
          @endphp
          <span style="font-size:.78rem;padding:4px 12px;border-radius:99px;font-weight:600;{{ $badgeStyle }}">
            {{ $doc->status_label }}
          </span>
          {{-- Replies count --}}
          @if($doc->replies->count())
          <small class="text-muted">
            <ion-icon name="chatbubbles-outline" style="vertical-align:-2px;"></ion-icon>
            {{ $doc->replies->count() }} balasan
          </small>
          @endif
          <a href="{{ route('customer.surat-menyurat.show', $doc->id) }}"
             class="btn btn-sm btn-outline-primary px-3 fw-semibold"
             style="border-radius:8px;font-size:.82rem;">
            <ion-icon name="eye-outline" style="vertical-align:-2px;"></ion-icon>
            Lihat Detail
          </a>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body text-center py-5">
      <ion-icon name="mail-open-outline" style="font-size:3rem;color:#d1d5db;"></ion-icon>
      <p class="text-muted mt-3 mb-1 fw-semibold">Belum ada dokumen dikirim</p>
      <p class="text-muted" style="font-size:.85rem;">Mulai kirim dokumen PDF pertama Anda ke tim kami.</p>
      <a href="{{ route('customer.surat-menyurat.create') }}" class="btn btn-danger mt-1 px-4 fw-semibold" style="border-radius:10px;">
        Kirim Sekarang
      </a>
    </div>
  </div>
  @endforelse

</div>
@endsection
