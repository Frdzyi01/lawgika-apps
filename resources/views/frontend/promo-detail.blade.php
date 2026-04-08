
@extends('layout.app')

@section('content')
{{-- ===== PERFORMANCE OVERRIDE ===== --}}
<style>
  .animated {
    animation-duration: 0.01ms !important;
    animation-delay: 0.01ms !important;
    opacity: 1 !important;
    visibility: visible !important;
  }

  [class*="wow"] {
    opacity: 1 !important;
    visibility: visible !important;
    transform: none !important;
  }

  .mouse-cursor,
  .cursor-inner,
  .cursor-outer {
    display: none !important;
  }
</style>

{{-- ===== PROMO DETAIL HERO BANNER ===== --}}
<style>
  :root {
    --header-h: 110px;
  }

  .promo-detail-hero {
    background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%);
    padding-top: calc(var(--header-h) + 48px);
    padding-bottom: 56px;
    position: relative;
    overflow: hidden;
  }

  .promo-detail-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 80% at 60% 50%, rgba(220, 53, 69, 0.18) 0%, transparent 70%);
    pointer-events: none;
  }

  .promo-detail-breadcrumb {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.55);
    margin-bottom: 14px;
    letter-spacing: 0.3px;
  }

  .promo-detail-breadcrumb a {
    color: rgba(255, 255, 255, 0.55);
    text-decoration: none;
    transition: color 0.18s;
  }

  .promo-detail-breadcrumb a:hover {
    color: #ff8a99;
  }

  .promo-detail-breadcrumb .sep {
    margin: 0 8px;
    opacity: 0.45;
  }

  .promo-detail-breadcrumb .current {
    color: #ff8a99;
  }

  .promo-detail-title {
    font-size: 2.2rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.22;
    letter-spacing: -0.3px;
    margin: 0;
  }

  @media (max-width: 767px) {
    .promo-detail-title {
      font-size: 1.65rem;
    }
  }

  /* ===== MAIN CONTENT AREA ===== */
  .promo-detail-section {
    background: #f7f6f8;
    padding: 56px 0 80px;
  }

  /* ---- Main card ---- */
  .promo-main-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
    border: 1px solid #f0edf3;
    margin-bottom: 24px;
  }

  .promo-main-card-img {
    width: 100%;
    max-height: 440px;
    object-fit: cover;
    display: block;
  }

  .promo-img-placeholder {
    height: 220px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d1d5db;
    font-size: 3rem;
  }

  .promo-main-card-body {
    padding: 32px 36px 36px;
  }

  @media (max-width: 575px) {
    .promo-main-card-body {
      padding: 22px 18px 24px;
    }
  }

  /* Badge row */
  .promo-badge-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 24px;
  }

  .promo-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 50px;
  }

  .promo-badge-success {
    background: #d1fae5;
    color: #065f46;
  }

  .promo-badge-date {
    background: #f3f4f6;
    color: #6b7280;
    border: 1px solid #e5e7eb;
  }

  .promo-badge-expire {
    background: #fff1f2;
    color: #b91c1c;
    border: 1px solid #fecdd3;
  }

  /* Diskon box */
  .promo-discount-box {
    background: linear-gradient(90deg, #dc3545 0%, #9b1c2c 100%);
    border-radius: 12px;
    padding: 20px 24px;
    display: flex;
    align-items: center;
    gap: 18px;
    margin-bottom: 28px;
    color: #fff;
  }

  .promo-discount-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.18);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
  }

  .promo-discount-label {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    opacity: 0.80;
    margin-bottom: 2px;
  }

  .promo-discount-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
  }

  /* Section title */
  .promo-section-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 1rem;
    font-weight: 700;
    color: #1a0208;
    margin-bottom: 12px;
  }

  .promo-section-label i {
    color: #dc3545;
  }

  /* Deskripsi */
  .promo-desc-text {
    color: #4b5563;
    line-height: 1.85;
    white-space: pre-line;
    font-size: 0.96rem;
  }

  /* Tanggal meta box */
  .promo-meta-box {
    background: #f9fafb;
    border: 1px solid #f0edf3;
    border-radius: 10px;
    padding: 14px 18px;
    margin-top: 24px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    font-size: 0.86rem;
    color: #6b7280;
  }

  .promo-meta-box strong {
    color: #374151;
  }

  /* CTA buttons */
  .promo-cta-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 28px;
  }

  .promo-btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #dc3545;
    color: #fff !important;
    font-weight: 700;
    font-size: 0.9rem;
    padding: 12px 26px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 4px 14px rgba(220, 53, 69, 0.30);
    transition: background 0.22s, transform 0.18s, box-shadow 0.22s;
  }

  .promo-btn-primary:hover {
    background: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(220, 53, 69, 0.38);
  }

  .promo-btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #374151 !important;
    font-weight: 600;
    font-size: 0.9rem;
    padding: 12px 24px;
    border-radius: 10px;
    text-decoration: none;
    border: 1.5px solid #e5e7eb;
    transition: border-color 0.18s, background 0.18s;
  }

  .promo-btn-secondary:hover {
    border-color: #dc3545;
    background: #fff5f6;
    color: #dc3545 !important;
  }

  /* ===== SIDEBAR ===== */
  .promo-sidebar-title {
    font-size: 0.85rem;
    font-weight: 800;
    color: #1a0208;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    border-bottom: 2px solid #f0edf3;
    padding-bottom: 10px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 7px;
  }

  .promo-sidebar-title i {
    color: #dc3545;
  }

  .promo-sidebar-item {
    display: flex;
    gap: 14px;
    align-items: center;
    text-decoration: none;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid #f0edf3;
    background: #fff;
    box-shadow: 0 1px 6px rgba(0, 0, 0, 0.05);
    margin-bottom: 10px;
    transition: border-color 0.2s, box-shadow 0.2s, transform 0.18s;
  }

  .promo-sidebar-item:hover {
    border-color: rgba(220, 53, 69, 0.3);
    box-shadow: 0 4px 14px rgba(220, 53, 69, 0.10);
    transform: translateY(-2px);
  }

  .promo-sidebar-thumb {
    width: 62px;
    height: 62px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
  }

  .promo-sidebar-thumb-placeholder {
    width: 62px;
    height: 62px;
    border-radius: 10px;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d1d5db;
    flex-shrink: 0;
    font-size: 1.2rem;
  }

  .promo-sidebar-name {
    font-size: 0.84rem;
    font-weight: 700;
    color: #1a0208;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .promo-sidebar-date {
    font-size: 0.73rem;
    color: #9ca3af;
    margin-top: 4px;
  }
</style>

{{-- ===== HERO BANNER ===== --}}
<section class="promo-detail-hero">
  <div class="container" style="max-width:1200px;">
    <p class="promo-detail-breadcrumb">
      <a href="{{ url('/') }}">Beranda</a>
      <span class="sep">/</span>
      <a href="{{ url('/promo') }}">Promo</a>
      <span class="sep">/</span>
      <span class="current">{{ \Illuminate\Support\Str::limit($promo->judul, 45) }}</span>
    </p>
    <h1 class="promo-detail-title">{{ $promo->judul }}</h1>
  </div>
</section>

{{-- ===== MAIN CONTENT ===== --}}
<section class="promo-detail-section">
  <div class="container" style="max-width:1200px;">
    <div class="row g-4">

      {{-- KOLOM UTAMA --}}
      <div class="col-lg-8">

        <div class="promo-main-card">

          {{-- Gambar --}}
          @if($promo->gambar)
          <img src="{{ asset('storage/'.$promo->gambar) }}"
            class="promo-main-card-img img-fluid w-100"
            alt="{{ $promo->judul }}">
          @else
          <div class="promo-img-placeholder">
            <i class="fas fa-image"></i>
          </div>
          @endif

          <div class="promo-main-card-body">

            {{-- Badge Status --}}
            <div class="promo-badge-row">
              <span class="promo-badge promo-badge-success">
                <i class="fas fa-check-circle"></i> Promo Aktif
              </span>
              <span class="promo-badge promo-badge-date">
                <i class="fas fa-clock"></i> {{ $promo->created_at->format('d F Y') }}
              </span>
              @if($promo->tanggal_berakhir)
              <span class="promo-badge promo-badge-expire">
                <i class="fas fa-calendar-times"></i> Berakhir: {{ $promo->tanggal_berakhir->format('d M Y') }}
              </span>
              @endif
            </div>

            {{-- Diskon Box --}}
            @if($promo->diskon)
            <div class="promo-discount-box">
              <div class="promo-discount-icon">
                <i class="fas fa-percent"></i>
              </div>
              <div>
                <div class="promo-discount-label">Hemat</div>
                <div class="promo-discount-value">
                  @if($promo->tipe_diskon === 'persen')
                  {{ number_format($promo->diskon, 0) }}% OFF
                  @else
                  Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                  @endif
                </div>
              </div>
            </div>
            @endif

            {{-- Detail Promo --}}
            <div class="promo-section-label">
              <i class="fas fa-info-circle"></i> Detail Promo
            </div>
            <p class="promo-desc-text">{{ $promo->deskripsi }}</p>

            {{-- Meta Tanggal --}}
            @if($promo->tanggal_mulai)
            <div class="promo-meta-box">
              <span>
                <i class="fas fa-calendar-plus text-danger me-1"></i>
                Mulai: <strong>{{ $promo->tanggal_mulai->format('d M Y') }}</strong>
              </span>
              @if($promo->tanggal_berakhir)
              <span>
                <i class="fas fa-calendar-times text-danger me-1"></i>
                Berakhir: <strong>{{ $promo->tanggal_berakhir->format('d M Y') }}</strong>
              </span>
              @endif
            </div>
            @endif

            {{-- CTA Buttons --}}
            <div class="promo-cta-row">
              <a href="{{ url('/layanan-konsultasi-bisnis') }}" class="promo-btn-primary">
                <i class="fas fa-phone-alt"></i> Hubungi Kami
              </a>
              <a href="{{ url('/promo') }}" class="promo-btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Promo
              </a>
            </div>

          </div>
        </div>

      </div>{{-- /col-lg-8 --}}

      {{-- SIDEBAR --}}
      <div class="col-lg-4">

        <div class="promo-sidebar-title">
          <i class="fas fa-tag"></i> Promo Lainnya
        </div>

        @php
        $otherPromos = \App\Models\Promo::where('status', true)
        ->where('id', '!=', $promo->id)
        ->latest()
        ->limit(5)
        ->get();
        @endphp

        @forelse($otherPromos as $other)
        <a href="{{ route('promo.show', $other->id) }}" class="promo-sidebar-item">

          @if($other->gambar)
          <img src="{{ asset('storage/'.$other->gambar) }}"
            class="promo-sidebar-thumb"
            alt="{{ $other->judul }}"
            loading="lazy">
          @else
          <div class="promo-sidebar-thumb-placeholder">
            <i class="fas fa-tag"></i>
          </div>
          @endif

          <div style="min-width:0;">
            <div class="promo-sidebar-name">{{ $other->judul }}</div>
            @if($other->tanggal_berakhir)
            <div class="promo-sidebar-date">
              <i class="fas fa-calendar-alt me-1"></i>s.d. {{ $other->tanggal_berakhir->format('d M Y') }}
            </div>
            @endif
          </div>
        </a>
        @empty
        <p style="color:#9ca3af; font-size:0.88rem;">Belum ada promo lain.</p>
        @endforelse

      </div>{{-- /col-lg-4 --}}

    </div>{{-- /row --}}
  </div>{{-- /container --}}
</section>

<script>
  window.WOW = function() {
    return {
      init: function() {}
    };
  };
  (function() {
    var _orig = document.querySelector.bind(document);
    document.querySelector = function(sel) {
      if (sel === '.cursor-inner' || sel === '.cursor-outer') return null;
      return _orig(sel);
    };
    setTimeout(function() {
      document.querySelector = _orig;
    }, 600);
  })();

  (function() {
    function setH() {
      var hdr = document.querySelector('.header-section-1');
      if (!hdr) return;
      document.documentElement.style.setProperty('--header-h', hdr.getBoundingClientRect().height + 'px');
    }
    setH();
    window.addEventListener('load', setH);
  })();
</script>

@endsection