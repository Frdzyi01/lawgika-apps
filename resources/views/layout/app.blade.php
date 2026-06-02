@php
  // Determine pageKey based on current path
  $path = request()->path();
  $pageKey = 'home';
  if ($path === '/') {
      $pageKey = 'home';
  } elseif ($path === 'tentang-kami') {
      $pageKey = 'tentang';
  } elseif ($path === 'karir') {
      $pageKey = 'karir';
  } elseif ($path === 'promo') {
      $pageKey = 'promo';
  } elseif ($path === 'upcoming-event') {
      $pageKey = 'event';
  } elseif ($path === 'database-peraturan') {
      $pageKey = 'peraturan';
  } elseif ($path === 'berita') {
      $pageKey = 'berita';
  } elseif (strpos($path, 'berita/') === 0) {
      $pageKey = 'berita_detail';
  } elseif (strpos($path, 'promo/') === 0) {
      $pageKey = 'promo_detail';
  } elseif ($path === 'perizinan-dan-hukum') {
      $pageKey = 'perizinan_hukum';
  } elseif ($path === 'virtual-office') {
      $pageKey = 'virtual_office';
  } elseif ($path === 'sewa-meeting-room') {
      $pageKey = 'meeting_room';
  } elseif ($path === 'sewa-ruang-podcast') {
      $pageKey = 'podcast_room';
  } elseif ($path === 'kerjasama-bisnis') {
      $pageKey = 'kerjasama';
  } elseif ($path === 'pendirian-pt-perorangan') {
      $pageKey = 'pt_perorangan';
  } elseif ($path === 'pendirian-pt') {
      $pageKey = 'pt';
  } elseif ($path === 'pendirian-pt-pma') {
      $pageKey = 'pt_pma';
  } elseif ($path === 'pendirian-cv') {
      $pageKey = 'cv';
  } elseif ($path === 'pendirian-firma') {
      $pageKey = 'firma';
  } elseif ($path === 'pendirian-yayasan') {
      $pageKey = 'yayasan';
  } elseif ($path === 'haki') {
      $pageKey = 'haki';
  } elseif ($path === 'nib-dan-oss') {
      $pageKey = 'nib';
  } elseif ($path === 'laporan-lkpm') {
      $pageKey = 'lkpm';
  } elseif ($path === 'sertifikat-iso') {
      $pageKey = 'iso';
  } elseif ($path === 'surat-keterangan-tidak-pailit') {
      $pageKey = 'pailit';
  } elseif ($path === 'jasa-pembukuan-perpajakan') {
      $pageKey = 'jasa_pembukuan';
  } elseif ($path === 'drafting&review-perjanjian-bisnis' || $path === 'drafting-review-perjanjian-bisnis') {
      $pageKey = 'drafting';
  } elseif ($path === 'pendaftaran-npwp') {
      $pageKey = 'npwp';
  } elseif ($path === 'pengurusan-pkp') {
      $pageKey = 'pkp';
  } elseif ($path === 'pelaporan-spt-tahunan') {
      $pageKey = 'spt_tahunan';
  } elseif ($path === 'pelaporan-spt-tahunan-success') {
      $pageKey = 'spt_tahunan_success';
  } elseif ($path === 'pelaporan-spt-pribadi') {
      $pageKey = 'spt_pribadi';
  } elseif ($path === 'pelaporan-spt-badan') {
      $pageKey = 'spt_badan';
  } elseif ($path === 'layanan-payroll') {
      $pageKey = 'payroll';
  } elseif ($path === 'audit-laporan-keuangan') {
      $pageKey = 'audit';
  }

  $titleKey = 'meta.' . $pageKey . '.title';
  $title = __($titleKey);
  if ($title === $titleKey) {
      $title = View::hasSection('title') ? View::yieldContent('title') : 'Lawgika - Legal, Tax, Accounting & Virtual Office Terpercaya';
  }

  $descKey = 'meta.' . $pageKey . '.desc';
  $description = __($descKey);
  if ($description === $descKey) {
      $description = View::hasSection('meta_description') ? View::yieldContent('meta_description') : 'Lawgika.co.id adalah konsultan profesional yang melayani pendirian PT, CV, Yayasan, Virtual Office, Jasa Pembukuan, dan Pelaporan Pajak di Indonesia. Proses cepat, legal, dan aman.';
  }

  $keywordsKey = 'meta.' . $pageKey . '.keywords';
  $keywords = __($keywordsKey);
  if ($keywords === $keywordsKey) {
      $keywords = View::hasSection('meta_keywords') ? View::yieldContent('meta_keywords') : 'Jasa Pendirian PT, Pendirian CV, Pendirian Yayasan, Virtual Office Jakarta, Jasa Pembukuan, Konsultan Pajak, Pelaporan SPT Tahunan, Pengurusan PKP, Sewa Meeting Room, Sewa Podcast Room, Legalitas Usaha, Lawgika';
  }
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="lw-html-root">
<!--<< Header Area >>-->

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- ======== SEO Optimization ======== -->
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}" />
    <meta name="keywords" content="{{ $keywords }}" />
    <meta name="google-site-verification" content="4SifO5KpxUTqWPbYNR5_n02fvRYEikrp8D7nTz2X5D0" />
    <meta name="author" content="Lawgika" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- ======== Multilingual hreflang SEO ======== -->
    <link rel="alternate" hreflang="id" href="{{ url()->current() }}" />
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}" />
    <link rel="alternate" hreflang="zh" href="{{ url()->current() }}" />
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="{{ $title }}" />
    <meta property="og:description" content="{{ $description }}" />
    <meta property="og:image" content="{{ asset('buyer-file/assets/img/logo.png') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="{{ $title }}" />
    <meta property="twitter:description" content="{{ $description }}" />
    <meta property="twitter:image" content="{{ asset('buyer-file/assets/img/logo.png') }}" />

    <!--<< Favcion >>-->
    <!-- <link rel="shortcut icon" href="{{ asset('buyer-file/assets/img/favicon.svg')}}" /> -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lawgika/logo.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('lawgika/logo.png')}}">
    <!--<< Bootstrap min.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/bootstrap.min.css')}}" />
    <!--<< All Min Css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/all.min.css')}}" />
    <!--<< Animate.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/animate.css')}}" />
    <!--<< Magnific Popup.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/magnific-popup.css')}}" />
    <!--<< MeanMenu.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/meanmenu.css')}}" />
    <!--<< Swiper Bundle.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/swiper-bundle.min.css')}}" />
    <!--<< Nice Select.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/nice-select.css')}}" />
    <!--<< Color.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/color.css')}}" />
    <!--<< Main.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/main.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
      /* Language Switcher Styles */
      .lw-lang-switcher {
        position: relative;
        display: inline-flex;
        align-items: center;
      }
      .lw-lang-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.35);
        border-radius: 50px;
        padding: 6px 12px;
        color: white;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.22s ease;
        white-space: nowrap;
        line-height: 1;
      }
      .lw-lang-btn:hover {
        background: rgba(255,255,255,0.12);
        border-color: rgba(255,255,255,0.6);
      }
      #header-sticky.scrolled .lw-lang-btn,
      #header-sticky.sticky .lw-lang-btn {
        border-color: rgba(0,0,0,0.2);
        color: #111;
      }
      #header-sticky.scrolled .lw-lang-btn:hover,
      #header-sticky.sticky .lw-lang-btn:hover {
        background: rgba(0,0,0,0.05);
        border-color: #4e0616;
        color: #4e0616;
      }
      .lw-lang-btn .lw-lang-arrow {
        font-size: 0.65rem;
        transition: transform 0.22s ease;
        opacity: 0.8;
      }
      .lw-lang-sep {
        width: 1.5px;
        height: 18px;
        background: rgba(255, 255, 255, 0.25);
        margin: 0 4px;
        align-self: center;
      }
      #header-sticky.scrolled .lw-lang-sep,
      #header-sticky.sticky .lw-lang-sep {
        background: rgba(0, 0, 0, 0.15);
      }
      .lw-lang-dropdown {
        display: none;
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 175px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
        z-index: 99999;
        overflow: hidden;
        opacity: 0;
        transform: translateY(8px);
        transition: opacity 0.18s ease, transform 0.18s ease;
        pointer-events: none;
      }
      .lw-lang-dropdown.lw-lang-open {
        display: block;
        opacity: 1;
        transform: translateY(0);
        pointer-events: auto;
      }
      .lw-lang-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #1e293b;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.15s ease, color 0.15s ease;
        border-bottom: 1px solid #f5f5f5;
      }
      .lw-lang-item:last-child { border-bottom: none; }
      .lw-lang-item:hover { background: #fff5f6; color: #4e0616; }
      .lw-lang-item.lw-lang-active {
        background: #fff5f6;
        color: #4e0616;
        font-weight: 700;
      }
      .lw-lang-item.lw-lang-active::after {
        content: '✓';
        margin-left: auto;
        font-size: 0.75rem;
        color: #4e0616;
      }
      .lw-lang-flag { font-size: 1.1rem; line-height: 1; }
      /* Mobile Language Switcher */
      .lw-lang-mobile-wrap {
        padding: 12px 5px;
        border-top: 1px solid #f0f0f0;
        margin-top: 6px;
      }
      .lw-lang-mobile-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #999;
        margin-bottom: 8px;
      }
      .lw-lang-mobile-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 12px 10px;
        background: #f8f9fa;
        border: 1px solid #eee;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a1a1a;
        cursor: pointer;
        margin-bottom: 4px;
      }
      .lw-lang-mobile-dropdown {
        display: none;
        flex-direction: column;
        gap: 4px;
        margin-top: 4px;
      }
      .lw-lang-mobile-dropdown.lw-lang-open { display: flex; }
      .lw-lang-mobile-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #444;
        cursor: pointer;
        border: 1px solid transparent;
        background: transparent;
        transition: all 0.15s;
      }
      .lw-lang-mobile-item:hover,
      .lw-lang-mobile-item.lw-lang-active {
        background: #fff5f6;
        border-color: #fca5a5;
        color: #4e0616;
        font-weight: 700;
      }
    </style>
</head>

<body data-page="{{ $pageKey }}">
    
    <!-- Preloader Start -->
    @if (request()->is('/'))
    <!-- <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner"></div>
            <div class="txt-loading">
                <span data-text-preloader="L" class="letters-loading"> L </span>
                <span data-text-preloader="A" class="letters-loading"> A </span>
                <span data-text-preloader="W" class="letters-loading"> W </span>
                <span data-text-preloader="G" class="letters-loading"> G </span>
                <span data-text-preloader="I" class="letters-loading"> I </span>
                <span data-text-preloader="K" class="letters-loading"> K </span>
                <span data-text-preloader="A" class="letters-loading"> A </span>
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div> -->
    @endif

    <!--<< Mouse Cursor Start >>-->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    @include('layout.header')

    @yield('content')

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/6281112088600" target="_blank" class="floating-whatsapp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <style>
        .floating-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 35px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .floating-whatsapp:hover {
            background-color: #128C7E;
            color: white;
            transform: scale(1.1);
        }
        
        .floating-whatsapp i {
            margin-top: 2px;
        }
    </style>

    @include('layout.footer')