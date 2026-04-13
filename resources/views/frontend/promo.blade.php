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
    background: radial-gradient(ellipse 70% 80% at 60% 50%, rgba(220,53,69,0.18) 0%, transparent 70%);
    pointer-events: none;
  }
  .promo-hero-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(220,53,69,0.18);
    border: 1px solid rgba(220,53,69,0.4);
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
    <div class="promo-hero-eyebrow"><i class="fas fa-tag"></i> Penawaran Eksklusif</div>
    <h1 class="promo-hero-title">Promo & <span>Diskon Terbaik</span><br>Untuk Anda</h1>
    <p class="promo-hero-desc">Dapatkan penawaran spesial untuk layanan legal dan bisnis kami. Hemat lebih banyak, dapatkan layanan terbaik.</p>
  </div>
</section>

{{-- ===== KATALOG PROMO SECTION ===== --}}
<style>
  .promo-catalog-section {
    background: #f7f6f8;
    padding: 64px 0 80px;
  }

  /* ---- Section header ---- */
  .promo-section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #dc3545;
    font-size: 0.78rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 10px;
  }
  .promo-section-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1a0208;
    margin-bottom: 6px;
  }
  .promo-section-sub {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 40px;
  }

  /* ---- Promo card ---- */
  .promo-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.07);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid #f0edf3;
  }
  .promo-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 36px rgba(220,53,69,0.14);
    border-color: rgba(220,53,69,0.18);
  }
  .promo-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 210px;
    background: #f3f4f6;
    flex-shrink: 0;
  }
  .promo-card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.40s ease;
  }
  .promo-card:hover .promo-card-img-wrap img {
    transform: scale(1.06);
  }
  .promo-card-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    background: #dc3545;
    color: #fff;
    font-size: 0.73rem;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 50px;
  }
  .promo-card-body {
    padding: 22px 22px 14px;
    flex: 1;
  }
  .promo-card-title {
    font-size: 1.08rem;
    font-weight: 700;
    color: #1a0208;
    margin-bottom: 10px;
    line-height: 1.42;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .promo-card-desc {
    color: #6b7280;
    font-size: 0.88rem;
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }
  .promo-card-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 22px 16px;
    font-size: 0.80rem;
    color: #9ca3af;
  }
  .promo-card-meta i { font-size: 0.78rem; }
  .promo-card-footer {
    padding: 0 22px 22px;
  }
  .promo-card-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #dc3545;
    color: #fff !important;
    font-weight: 700;
    font-size: 0.88rem;
    padding: 11px 22px;
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.22s ease, transform 0.18s ease, box-shadow 0.22s ease;
    box-shadow: 0 4px 14px rgba(220,53,69,0.30);
    width: 100%;
  }
  .promo-card-btn:hover {
    background: #b91c1c;
    color: #fff !important;
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(220,53,69,0.38);
  }
  .promo-card-btn i { transition: transform 0.20s; }
  .promo-card-btn:hover i { transform: translateX(4px); }

  /* ---- No promo state ---- */
  .promo-empty {
    text-align: center;
    padding: 80px 0;
  }
  .promo-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(220,53,69,0.08);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #dc3545;
    margin-bottom: 22px;
  }
  .promo-empty h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a0208;
    margin-bottom: 8px;
  }
  .promo-empty p { color: #9ca3af; font-size: 0.92rem; }
</style>

<section class="promo-catalog-section">
  <div class="container" style="max-width:1200px;">

    <div class="promo-section-label"><i class="fas fa-fire"></i> Promo Aktif</div>
    <h2 class="promo-section-title">Semua Penawaran Terbaik</h2>
    <p class="promo-section-sub">Jangan lewatkan kesempatan emas ini — pilih promo yang sesuai kebutuhan Anda.</p>

    @if($promos->isEmpty())
      <div class="promo-empty">
        <div class="promo-empty-icon"><i class="fas fa-tag"></i></div>
        <h3>Belum Ada Promo</h3>
        <p>Saat ini tidak ada promo aktif. Pantau terus halaman ini untuk penawaran terbaru!</p>
      </div>
    @else
      <div class="row g-4">
        @foreach($promos as $promo)
        <div class="col-lg-4 col-md-6">
          <div class="promo-card">

            <div class="promo-card-img-wrap">
              @if($promo->gambar)
                <img src="{{ asset('storage/'.$promo->gambar) }}" alt="{{ $promo->judul }}" loading="lazy">
              @else
                <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&q=70&auto=format&fit=crop" alt="{{ $promo->judul }}" loading="lazy">
              @endif

              @if($promo->diskon)
                <span class="promo-card-badge">
                  {{ $promo->tipe_diskon === 'persen' ? $promo->diskon.'%' : 'Rp '.number_format($promo->diskon,0,',','.') }} OFF
                </span>
              @endif
            </div>

            <div class="promo-card-body">
              <div class="promo-card-title">{{ $promo->judul }}</div>
              <p class="promo-card-desc">{{ \Illuminate\Support\Str::limit($promo->deskripsi, 110) }}</p>
            </div>

            @if($promo->tanggal_berakhir)
            <div class="promo-card-meta">
              <span><i class="fas fa-calendar-alt"></i> Berlaku s.d. {{ $promo->tanggal_berakhir->format('d M Y') }}</span>
            </div>
            @endif

            <div class="promo-card-footer">
              <a href="{{ route('promo.show', $promo->id) }}" class="promo-card-btn" id="promo-btn-{{ $promo->id }}">
                Lihat Detail <i class="fas fa-arrow-right"></i>
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
