@extends('layout.app')
@section('title', 'Beranda | Lawgika - Konsultan Legal & Bisnis')
@section('meta_description', 'Layanan Beranda terbaik dan terpercaya di Indonesia oleh Lawgika.co.id. Proses cepat, legal, dan aman untuk kebutuhan bisnis Anda.')
@section('meta_keywords', 'Beranda, Jasa Beranda, Konsultan Beranda, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


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
  .mouse-cursor, .cursor-inner, .cursor-outer { display: none !important; }
</style>

{{-- ===== PAGE HERO BANNER ===== --}}
<style>
  :root { --header-h: 110px; }

  .promo-hero {
    background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%);
    padding-top: calc(var(--header-h) + 48px);
    padding-bottom: 56px;
    position: relative;
    overflow: hidden;
  }
  .promo-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 80% at 60% 50%, rgba(78, 6, 22,0.18) 0%, transparent 70%);
    pointer-events: none;
  }
  .promo-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(78, 6, 22,0.18);
    border: 1px solid rgba(78, 6, 22,0.4);
    color: #ff8a99;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    border-radius: 50px;
    padding: 6px 18px;
    margin-bottom: 18px;
  }
  .promo-hero-title {
    font-size: 2.8rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 14px;
    letter-spacing: -0.5px;
  }
  .promo-hero-title span { color: #ff8a99; }
  .promo-hero-desc {
    color: rgba(255,255,255,0.72);
    font-size: 1rem;
    line-height: 1.75;
    max-width: 520px;
  }
  @media (max-width: 767px) {
    .promo-hero-title { font-size: 2rem; }
  }
</style>


<section class="promo-hero">
  <div class="container" style="max-width:1200px;">
    <div class="promo-hero-eyebrow"><i class="fas fa-tag"></i> <span data-i18n="promo.hero_eyebrow">Penawaran Eksklusif</span></div>
    <h1 class="promo-hero-title" data-i18n="promo.hero_title">Promo & <span>Diskon Terbaik</span><br>Untuk Anda</h1>
    <p class="promo-hero-desc" data-i18n="promo.hero_desc">Dapatkan penawaran spesial untuk layanan legal dan bisnis kami. Hemat lebih banyak, dapatkan layanan terbaik.</p>
  </div>
</section>

{{-- ===== KATALOG PROMO SECTION ===== --}}
<style>
  .promo-catalog-section {
    background: #f8f9fa;
    padding: 64px 0 80px;
  }

  .sp-header {
    margin-bottom: 28px;
  }

  .sp-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff1f3;
    color: #4e0616;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    border-radius: 50px;
    padding: 4px 12px;
    margin-bottom: 8px;
  }

  .sp-main-title {
    font-size: 1.9rem;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.3px;
    margin-bottom: 0;
    line-height: 1.2;
  }

  .sp-main-title span {
    color: #4e0616;
  }

  /* ---- Promo cards: identical to homepage (Photo 2) ---- */
  .sp-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);
    border: none;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
  }

  .sp-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.11);
  }

  .sp-card-img-wrap {
    position: relative;
    overflow: hidden;
    flex-shrink: 0;
    width: 100%;
    aspect-ratio: 1 / 1;
  }

  .sp-card-img-wrap img {
    width: 100%;
    height: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    display: block;
    transition: transform 0.4s ease;
  }

  .sp-card:hover .sp-card-img-wrap img {
    transform: scale(1.05);
  }

  .sp-img-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: #4e0616;
    color: #fff;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 50px;
    line-height: 1;
    z-index: 2;
  }

  .sp-card-body {
    padding: 18px 18px 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .sp-card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  .sp-card-desc {
    font-size: 0.83rem;
    color: #6b7280;
    line-height: 1.6;
    flex: 1;
    margin-bottom: 14px;
  }

  .sp-price-block {
    margin-bottom: 14px;
  }

  .sp-price-new {
    font-size: 1.1rem;
    font-weight: 700;
    color: #4e0616;
  }

  .promo-card-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #111827;
    font-size: 0.88rem;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.2s ease, gap 0.2s ease;
    margin-top: auto;
  }

  .promo-card-btn:hover {
    color: #4e0616;
    gap: 10px;
  }

  .promo-card-btn i {
    font-size: 0.75rem;
    transition: transform 0.2s;
  }

  .promo-card-btn:hover i {
    transform: translateX(3px);
  }

  /* ---- No promo state ---- */
  .promo-empty {
    text-align: center;
    padding: 80px 0;
  }

  .promo-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(78, 6, 22, 0.08);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #4e0616;
    margin-bottom: 22px;
  }

  .promo-empty h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a0208;
    margin-bottom: 8px;
  }

  .promo-empty p {
    color: #9ca3af;
    font-size: 0.92rem;
  }
</style>

<section class="promo-catalog-section">
  <div class="container" style="max-width:1200px;">

    <div class="sp-header">
      <div>
        <div class="sp-eyebrow"><i class="fas fa-bolt"></i> <span data-i18n="dashboard.promo.eyebrow">Penawaran Terbatas</span></div>
        <h2 class="sp-main-title" data-i18n="dashboard.promo.title">Special <span>Promo</span> Lawgika</h2>
      </div>
    </div>

    @if($promos->isEmpty())
      <div class="promo-empty">
        <div class="promo-empty-icon"><i class="fas fa-tag"></i></div>
        <h3 data-i18n="promo.empty_title">Belum Ada Promo</h3>
        <p data-i18n="promo.empty_desc">Saat ini tidak ada promo aktif. Pantau terus halaman ini untuk penawaran terbaru!</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($promos as $promo)
        <div class="col-12 col-md-6 col-lg-3">
          <div class="sp-card">

            <div class="sp-card-img-wrap">
              @if($promo->gambar)
                <img loading="lazy" src="{{ asset('storage/' . $promo->gambar) }}" alt="{{ $promo->judul }}" loading="lazy">
              @else
                <img loading="lazy" src="{{ asset('lawgika/home/promo-placeholder.webp') }}" alt="{{ $promo->judul }}" loading="lazy">
              @endif

              <span class="sp-img-badge">
                @if($promo->tipe_diskon === 'persen')
                  HEMAT {{ number_format($promo->diskon, 0) }}%
                @else
                  HEMAT Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                @endif
              </span>
            </div>

            <div class="sp-card-body">
              <div class="sp-card-title">{{ $promo->judul }}</div>
              <div class="sp-card-desc">{{ Str::limit($promo->deskripsi, 100) }}</div>
              <div class="sp-price-block">
                <span class="sp-price-new">
                  @if($promo->tipe_diskon === 'persen')
                    Diskon {{ number_format($promo->diskon, 0) }}%
                  @else
                    Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                  @endif
                </span>
              </div>
              <a href="{{ route('promo.show', $promo->id) }}" class="promo-card-btn" id="promo-btn-{{ $promo->id }}">
                <span data-i18n="promo.view_detail">Lihat Detail</span> <i class="fas fa-arrow-right"></i>
              </a>
            </div>

          </div>
        </div>
        @endforeach
      </div>
    @endif

  </div>
</section>

<script>
  window.WOW = function() { return { init: function() {} }; };
  (function() {
    var _orig = document.querySelector.bind(document);
    document.querySelector = function(sel) {
      if (sel === '.cursor-inner' || sel === '.cursor-outer') return null;
      return _orig(sel);
    };
    setTimeout(function() { document.querySelector = _orig; }, 600);
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
