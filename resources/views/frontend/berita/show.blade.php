@extends('layout.app')

@section('content')
<div class="berita-detail-page-wrapper">

  {{-- ===== PERFORMANCE OVERRIDE ===== --}}
  <style>
    .berita-detail-page-wrapper {
      /* Reset box-sizing hanya di dalam wrapper */
      box-sizing: border-box;
      --berita-header-h: 110px;
    }

    .berita-detail-page-wrapper *,
    .berita-detail-page-wrapper *::before,
    .berita-detail-page-wrapper *::after {
      box-sizing: inherit;
    }

    /* Reset heading margins dalam wrapper */
    .berita-detail-page-wrapper h1,
    .berita-detail-page-wrapper h2,
    .berita-detail-page-wrapper h3,
    .berita-detail-page-wrapper p {
      margin: 0 0 1rem 0;
    }

    .berita-detail-page-wrapper h1:last-child,
    .berita-detail-page-wrapper p:last-child {
      margin-bottom: 0;
    }

    .berita-detail-page-wrapper .animated {
      animation-duration: 0.01ms !important;
      animation-delay: 0.01ms !important;
      opacity: 1 !important;
      visibility: visible !important;
    }

    .berita-detail-page-wrapper [class*="wow"] {
      opacity: 1 !important;
      visibility: visible !important;
      transform: none !important;
    }

    .berita-detail-page-wrapper .mouse-cursor,
    .berita-detail-page-wrapper .cursor-inner,
    .berita-detail-page-wrapper .cursor-outer {
      display: none !important;
    }

    .author-title {
      font-size: 0.85rem;
      color: #6b7280;
      margin-left: 4px;
      font-weight: 400;
    }
  </style>

  {{-- ===== BERITA DETAIL HERO BANNER & CSS ===== --}}
  <style>
    .berita-detail-page-wrapper .berita-detail-hero {
      background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%);
      padding-top: calc(var(--berita-header-h) + 48px);
      padding-bottom: 56px;
      position: relative;
      overflow: hidden;
    }

    .berita-detail-page-wrapper .berita-detail-hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: radial-gradient(ellipse 70% 80% at 60% 50%, rgba(220, 53, 69, 0.18) 0%, transparent 70%);
      pointer-events: none;
    }

    .berita-detail-page-wrapper .berita-detail-breadcrumb {
      font-size: 0.82rem;
      color: rgba(255, 255, 255, 0.55);
      margin-bottom: 14px;
      letter-spacing: 0.3px;
    }

    .berita-detail-page-wrapper .berita-detail-breadcrumb a {
      color: rgba(255, 255, 255, 0.55);
      text-decoration: none;
      transition: color 0.18s;
    }

    .berita-detail-page-wrapper .berita-detail-breadcrumb a:hover {
      color: #ff8a99;
    }

    .berita-detail-page-wrapper .berita-detail-breadcrumb .sep {
      margin: 0 8px;
      opacity: 0.45;
    }

    .berita-detail-page-wrapper .berita-detail-breadcrumb .current {
      color: #ff8a99;
    }

    .berita-detail-page-wrapper .berita-detail-title {
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      line-height: 1.22;
      letter-spacing: -0.3px;
      margin: 0;
    }

    @media (max-width: 767px) {
      .berita-detail-page-wrapper .berita-detail-title {
        font-size: 1.65rem;
      }
    }

    /* ===== MAIN CONTENT AREA ===== */
    .berita-detail-page-wrapper .berita-detail-section {
      background: #f7f6f8;
      padding: 56px 0 80px;
    }

    /* ---- Main card ---- */
    .berita-detail-page-wrapper .berita-main-card {
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid #f0edf3;
      margin-bottom: 24px;
    }

    .berita-detail-page-wrapper .berita-main-card-img {
      width: 100%;
      max-height: 440px;
      object-fit: cover;
      display: block;
    }

    .berita-detail-page-wrapper .berita-img-placeholder {
      height: 220px;
      background: #f3f4f6;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #d1d5db;
      font-size: 3rem;
    }

    .berita-detail-page-wrapper .berita-main-card-body {
      padding: 32px 36px 36px;
    }

    @media (max-width: 575px) {
      .berita-detail-page-wrapper .berita-main-card-body {
        padding: 22px 18px 24px;
      }
    }

    /* Badge row */
    .berita-detail-page-wrapper .berita-badge-row {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-bottom: 24px;
    }

    .berita-detail-page-wrapper .berita-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.76rem;
      font-weight: 700;
      padding: 5px 14px;
      border-radius: 50px;
    }

    /* Kategori Colors */
    .berita-detail-page-wrapper .berita-badge-hukum {
      background: #dbeafe;
      color: #1e40af;
    }

    .berita-detail-page-wrapper .berita-badge-bisnis {
      background: #dcfce7;
      color: #166534;
    }

    .berita-detail-page-wrapper .berita-badge-perizinan {
      background: #fef3c7;
      color: #92400e;
    }

    .berita-detail-page-wrapper .berita-badge-date {
      background: #f3f4f6;
      color: #6b7280;
      border: 1px solid #e5e7eb;
    }

    /* Penulis Info Box */
    .berita-detail-page-wrapper .berita-author-box {
      background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
      border-radius: 12px;
      padding: 20px 24px;
      display: flex;
      align-items: center;
      gap: 18px;
      margin-bottom: 28px;
      color: #fff;
    }

    .berita-detail-page-wrapper .berita-author-icon {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.12);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }

    .berita-detail-page-wrapper .berita-author-label {
      font-size: 0.7rem;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      opacity: 0.80;
      margin-bottom: 2px;
    }

    .berita-detail-page-wrapper .berita-author-name {
      font-size: 1.25rem;
      font-weight: 700;
      line-height: 1.3;
    }

    .berita-detail-page-wrapper .berita-author-title {
      font-size: 0.85rem;
      opacity: 0.85;
      margin-top: 2px;
    }

    /* Section title */
    .berita-detail-page-wrapper .berita-section-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 1rem;
      font-weight: 700;
      color: #1a0208;
      margin-bottom: 12px;
    }

    .berita-detail-page-wrapper .berita-section-label i {
      color: #dc3545;
    }

    /* Deskripsi */
    .berita-detail-page-wrapper .berita-desc-text {
      color: #4b5563;
      line-height: 1.85;
      font-size: 1.05rem;
    }

    .berita-detail-page-wrapper .berita-desc-text img {
      max-width: 100%;
      height: auto;
      border-radius: 8px;
      margin: 1.5rem 0;
    }

    .berita-detail-page-wrapper .berita-desc-text a {
      color: #dc3545;
      text-decoration: underline;
    }

    /* Meta box */
    .berita-detail-page-wrapper .berita-meta-box {
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

    .berita-detail-page-wrapper .berita-meta-box strong {
      color: #374151;
    }

    /* CTA buttons */
    .berita-detail-page-wrapper .berita-cta-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 28px;
    }

    .berita-detail-page-wrapper .berita-btn-secondary {
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

    .berita-detail-page-wrapper .berita-btn-secondary:hover {
      border-color: #dc3545;
      background: #fff5f6;
      color: #dc3545 !important;
    }

    /* ===== SIDEBAR ===== */
    .berita-detail-page-wrapper .berita-sidebar-title {
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

    .berita-detail-page-wrapper .berita-sidebar-title i {
      color: #dc3545;
    }

    .berita-detail-page-wrapper .berita-sidebar-item {
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

    .berita-detail-page-wrapper .berita-sidebar-item:hover {
      border-color: rgba(220, 53, 69, 0.3);
      box-shadow: 0 4px 14px rgba(220, 53, 69, 0.10);
      transform: translateY(-2px);
    }

    .berita-detail-page-wrapper .berita-sidebar-thumb {
      width: 62px;
      height: 62px;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
    }

    .berita-detail-page-wrapper .berita-sidebar-thumb-placeholder {
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

    .berita-detail-page-wrapper .berita-sidebar-name {
      font-size: 0.84rem;
      font-weight: 700;
      color: #1a0208;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .berita-detail-page-wrapper .berita-sidebar-date {
      font-size: 0.73rem;
      color: #9ca3af;
      margin-top: 4px;
    }

    .berita-detail-page-wrapper .custom-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
  </style>

  {{-- ===== HERO BANNER ===== --}}
  <section class="berita-detail-hero">
    <div class="custom-container">
      <p class="berita-detail-breadcrumb">
        <a href="{{ url('/') }}">Beranda</a>
        <span class="sep">/</span>
        <a href="{{ url('/berita') }}">Berita</a>
        <span class="sep">/</span>
        <span class="current">{{ \Illuminate\Support\Str::limit($berita->judul, 45) }}</span>
      </p>
      <h1 class="berita-detail-title">{{ $berita->judul }}</h1>
    </div>
  </section>

  {{-- ===== MAIN CONTENT ===== --}}
  <section class="news-details fix section-padding">
    <div class="container">
      <div class="news-details-area">
        <div class="row g-5">
          <div class="col-12 col-lg-8">
            <div class="blog-post-details">
              <div class="single-blog-post">
                @if($berita->gambar)
                <div class="post-featured-thumb bg-cover" style="background-image: url('{{ asset('storage/' . $berita->gambar) }}');"></div>
                @else
                <div class="post-featured-thumb bg-cover" style="background-image: url('{{ asset('buyer-file/assets/img/news/post-4.jpg') }}');"></div>
                @endif
                <div class="post-content">
                  <ul class="post-list d-flex align-items-center">
                    <li>
                      <i class="fa-regular fa-user"></i>
                      {{ $berita->penulis }}
                      @if($berita->jabatan_penulis)
                      <span class="author-title">({{ $berita->jabatan_penulis }})</span>
                      @endif
                    </li>
                    <li>
                      <i class="fa-solid fa-calendar-days"></i>
                      {{ $berita->published_at->format('d M, Y') }}
                    </li>
                    <li>
                      <i class="fa-solid fa-tag"></i>
                      {{ $berita->kategori }}
                    </li>
                  </ul>
                  <h3>{{ $berita->judul }}</h3>

                  <div class="berita-content mt-4">
                    {!! $berita->konten !!}
                  </div>
                </div>
              </div>
              <div class="row tag-share-wrap mt-4 mb-5">
                <div class="col-lg-8 col-12">
                  <div class="tagcloud">
                    <span>Kategori:</span>
                    <a href="{{ url('/berita?kategori=' . urlencode($berita->kategori)) }}">{{ $berita->kategori }}</a>
                  </div>
                </div>
                <div class="col-lg-4 col-12 mt-3 mt-lg-0 text-lg-end">
                  <a href="{{ url('/berita') }}" class="theme-btn">
                    <i class="fa-solid fa-arrow-left-long"></i> Kembali ke Berita
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <div class="main-sidebar">
              <div class="single-sidebar-widget">
                <div class="wid-title">
                  <h3>Kategori</h3>
                </div>
                <div class="news-widget-categories">
                  <ul>
                    @foreach($kategoriList as $kat)
                    <li class="{{ request('kategori') == $kat->kategori ? 'active' : '' }}">
                      <a href="{{ url('/berita?kategori=' . urlencode($kat->kategori)) }}">
                        {{ $kat->kategori }}
                      </a>
                      <span>({{ $kat->total }})</span>
                    </li>
                    @endforeach
                  </ul>
                </div>
              </div>
              <div class="single-sidebar-widget">
                <div class="wid-title">
                  <h3>Berita Terbaru</h3>
                </div>
                <div class="recent-post-area">
                  @forelse($beritaLainnya as $item)
                  <div class="recent-items">
                    <div class="recent-thumb">
                      @if($item->gambar)
                      <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}">
                      @else
                      <img src="{{ asset('buyer-file/assets/img/news/pp3.jpg') }}" alt="placeholder">
                      @endif
                    </div>
                    <div class="recent-content">
                      <ul>
                        <li>
                          <i class="fa-solid fa-calendar-days"></i>
                          {{ $item->published_at->format('d/m/Y') }}
                        </li>
                      </ul>
                      <h6>
                        <a href="{{ route('berita.show', $item->slug) }}">
                          {{ \Illuminate\Support\Str::limit($item->judul, 40) }}
                        </a>
                      </h6>
                    </div>
                  </div>
                  @empty
                  <p class="text-muted">Belum ada berita lainnya.</p>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</div> {{-- END .berita-detail-page-wrapper --}}
@endsection