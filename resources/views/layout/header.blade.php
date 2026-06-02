<div class="offcanvas__info">
  <div class="offcanvas__wrapper">
    <div class="offcanvas__content">
      <div class="offcanvas__top d-flex justify-content-between align-items-center">
        <div class="offcanvas__logo">
          <a href="{{ url('/') }}">
            <img src="{{ asset('buyer-file/assets/img/lowo-turun-removebg-preview.webp') }}" alt="logo-img" />
          </a>
        </div>
        <div class="offcanvas__close">
          <button>
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
      <p class="text d-none d-xl-block">
        Lawgika Bisnis Indonesia adalah mitra terpercaya dalam layanan hukum dan bisnis, membantu perusahaan dalam pendirian usaha, perizinan, serta pengelolaan legalitas secara profesional.
      </p>

      <!-- START: Mobile Navigation Accordion -->
      <div class="offcanvas__mobile-menu fix mb-3">
        <div class="lw-mobile-nav">
          <!-- Beranda -->
          <div class="lw-nav-item">
            <a href="{{ url('/') }}" class="lw-nav-link">
              <span data-i18n="nav.home">Beranda</span>
            </a>
          </div>

          <!-- Layanan -->
          <div class="lw-nav-item has-submenu">
            <a href="javascript:void(0)" class="lw-nav-link lw-toggle">
              <span data-i18n="nav.services">Layanan</span>
              <i class="fas fa-chevron-down"></i>
            </a>
            <div class="lw-submenu">
              <!-- Kantor & Ruang Podcast -->
              <div class="lw-sub-item has-submenu">
                <a href="javascript:void(0)" class="lw-sub-link lw-toggle">
                  <span data-i18n="nav.services.office_title">Kantor & Ruang Podcast</span>
                  <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="lw-sub-list">
                  <li><a href="{{ url('/virtual-office') }}">Virtual Office</a></li>
                  <li><a href="{{ url('/sewa-meeting-room') }}">Sewa Meeting Room</a></li>
                  <li><a href="{{ url('/sewa-ruang-podcast') }}">Sewa Ruang Podcast</a></li>
                </ul>
              </div>
              <!-- Legalitas Perusahaan -->
              <div class="lw-sub-item has-submenu">
                <a href="javascript:void(0)" class="lw-sub-link lw-toggle">
                  <span data-i18n="nav.services.legal_title">Legalitas Perusahaan</span>
                  <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="lw-sub-list">
                  <li><a href="{{ url('/pendirian-pt-perorangan') }}">Pendirian PT Perorangan</a></li>
                  <li><a href="{{ url('/pendirian-pt') }}">Pendirian PT Perseroan</a></li>
                  <li><a href="{{ url('/pendirian-pt-pma') }}">Pendirian PT PMA</a></li>
                  <li><a href="{{ url('/pendirian-cv') }}">Pendirian CV</a></li>
                  <li><a href="{{ url('/pendirian-yayasan') }}">Pendirian Yayasan</a></li>
                  <li><a href="{{ url('/pendirian-firma') }}">Pendirian Firma</a></li>
                </ul>
              </div>
              <!-- Pajak & Pembukuan -->
              <div class="lw-sub-item has-submenu">
                <a href="javascript:void(0)" class="lw-sub-link lw-toggle">
                  <span data-i18n="nav.services.tax_title">Pajak & Pembukuan</span>
                  <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="lw-sub-list">
                  <li><a href="{{ url('/jasa-pembukuan-perpajakan') }}">Jasa Pembukuan & Perpajakan</a></li>
                  <li><a href="{{ url('/pelaporan-spt-tahunan') }}">Pelaporan SPT Tahunan</a></li>
                </ul>
              </div>
              <!-- Perizinan & Hukum -->
              <div class="lw-sub-item has-submenu">
                <a href="javascript:void(0)" class="lw-sub-link lw-toggle">
                  <span data-i18n="nav.services.perizinan_title">Perizinan & Hukum</span>
                  <i class="fas fa-chevron-down"></i>
                </a>
                <ul class="lw-sub-list">
                  <li><a href="{{ url('/haki') }}">Hak Kekayaan Intelektual</a></li>
                  <li><a href="{{ url('/nib-dan-oss') }}">NIB dan OSS</a></li>
                  <li><a href="{{ url('/laporan-lkpm') }}">Laporan LKPM</a></li>
                  <li><a href="{{ url('/sertifikat-iso') }}">Sertifikat ISO</a></li>
                  <li><a href="{{ url('/pengurusan-pkp') }}">Pengurusan PKP</a></li>
                  <li><a href="{{ url('/perizinan-dan-hukum') }}">Perizinan Lainnya</a></li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Pusat Pelatihan -->
          <div class="lw-nav-item has-submenu">
            <a href="javascript:void(0)" class="lw-nav-link lw-toggle">
              <span data-i18n="nav.training">Pusat Pelatihan</span>
              <i class="fas fa-chevron-down"></i>
            </a>
           <div class="lw-submenu">

    <!-- Pelatihan Webinar -->
    <div class="lw-sub-item">
        <a href="{{ url('/upcoming-event') }}" class="lw-sub-link" data-i18n="nav.training.webinar">Pelatihan Webinar</a>
    </div>

    <!-- Artikel -->
    <div class="lw-sub-item">
        <a href="{{ url('/berita') }}" class="lw-sub-link" data-i18n="nav.training.artikel">Artikel</a>
    </div>

    <!-- Kumparan Peraturan -->
    <div class="lw-sub-item">
        <a href="{{ url('/database-peraturan') }}" class="lw-sub-link" data-i18n="nav.training.peraturan">Kumparan Peraturan</a>
    </div>

</div>
          </div>

          <!-- Profil & Legalitas -->
          <div class="lw-nav-item has-submenu">
            <a href="javascript:void(0)" class="lw-nav-link lw-toggle">
              <span data-i18n="nav.profile">Profil & Legalitas</span>
              <i class="fas fa-chevron-down"></i>
            </a>
            <div class="lw-submenu">
              <ul class="lw-sub-list" style="display: block; padding-left: 0;">
                <li><a href="{{ url('tentang-kami') }}" data-i18n="nav.profile.perusahaan">Profil Perusahaan</a></li>
                <li><a href="{{ url('karir') }}" data-i18n="nav.profile.karir">Karir</a></li>
                <li><a href="{{ url('kerjasama-bisnis') }}" data-i18n="nav.profile.kerjasama">Kerjasama Bisnis</a></li>
                <li><a href="{{ url('promo') }}" data-i18n="nav.profile.promo">Promo</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!-- END: Mobile Navigation Accordion -->

      <!-- START: Mobile Language Switcher -->
      <div class="lw-lang-mobile-wrap">
        <div class="lw-lang-mobile-label" data-i18n="lang.switcher.label">Bahasa</div>
        <button class="lw-lang-mobile-btn" id="lw-lang-mobile-trigger" type="button">
          <span><span id="lw-lang-mobile-flag">🇮🇩</span> <span id="lw-lang-mobile-current" data-i18n="lang.id">Bahasa Indonesia</span></span>
          <i class="fas fa-chevron-down" style="font-size:11px;"></i>
        </button>
        <div class="lw-lang-mobile-dropdown" id="lw-lang-mobile-dropdown">
          <div class="lw-lang-mobile-item" data-lw-lang="id">
            <span class="lw-lang-flag">🇮🇩</span> <span data-i18n="lang.id">Bahasa Indonesia</span>
          </div>
          <div class="lw-lang-mobile-item" data-lw-lang="en">
            <span class="lw-lang-flag">🇺🇸</span> <span data-i18n="lang.en">English</span>
          </div>
          <div class="lw-lang-mobile-item" data-lw-lang="zh">
            <span class="lw-lang-flag">🇨🇳</span> <span data-i18n="lang.zh">中文</span>
          </div>
        </div>
      </div>
      <!-- END: Mobile Language Switcher -->

      <div class="offcanvas__contact">
        <h4 class="fw-normal" data-i18n="offcanvas.contact_title">Informasi Kontak</h4>
      <ul class="ps-0" style="font-weight: normal;">
  <li class="d-flex align-items-start mb-3">
    <div class="offcanvas__contact-icon me-3">
      <i class="fal fa-map-marker-alt"></i>
    </div>
    <div class="offcanvas__contact-text">
      <a target="_blank" href="#" class="text text-decoration-none" style="font-weight: normal;">
        World Capital Tower Lt 38 unit 06-07, Kuningan, Mega Kuningan, Kecamatan Setiabudi, Kota Jakarta Selatan
      </a>
    </div>
  </li>

  <li class="d-flex align-items-center mb-3">
    <div class="offcanvas__contact-icon me-3">
      <i class="fal fa-envelope"></i>
    </div>
    <div class="offcanvas__contact-text">
      <a href="mailto:informasi@lawgika.co.id" class="text text-decoration-none" style="font-weight: normal;">
        informasi@lawgika.co.id
      </a>
    </div>
  </li>

  <li class="d-flex align-items-center mb-3">
    <div class="offcanvas__contact-icon me-3">
      <i class="fal fa-clock"></i>
    </div>
    <div class="offcanvas__contact-text">
      <span class="text" style="font-weight: normal;" data-i18n="offcanvas.hours">Senin-Jumat, 08.00 - 17.00</span>
    </div>
  </li>

  <li class="d-flex align-items-center mb-4">
    <div class="offcanvas__contact-icon me-3">
      <i class="far fa-phone"></i>
    </div>
    <div class="offcanvas__contact-text">
      <a href="https://wa.me/6281112088600" target="_blank" class="text text-decoration-none" style="font-weight: normal;">
        +62 811-1208-8600
      </a>
    </div>
  </li>
</ul>
        <div class="header-button mb-4">
          {{-- Jika belum login --}}
          @guest
          <div class="d-flex flex-column gap-2">
            <button class="theme-btn text-center fw-normal offcanvas-close-trigger"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal">
              <span data-i18n="nav.login">Masuk</span> <i class="fa-solid fa-arrow-right-long ms-2"></i>
            </button>
            <button class="theme-btn text-center fw-normal offcanvas-close-trigger"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal2">
              <span data-i18n="nav.register">Daftar</span> <i class="fa-solid fa-user-plus ms-2"></i>
            </button>
          </div>
          @endguest

          {{-- Jika sudah login --}}
          @auth
          <div class="d-flex flex-column gap-2">
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="theme-btn text-center fw-normal">
              Dashboard <i class="fa-solid fa-arrow-right-long ms-2"></i>
            </a>
            @else
            <a href="/dashboard" class="theme-btn text-center fw-normal">
              Dashboard <i class="fa-solid fa-arrow-right-long ms-2"></i>
            </a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="theme-btn text-center w-100 fw-normal">
                <span data-i18n="nav.logout">Keluar</span> <i class="fa-solid fa-sign-out-alt ms-2"></i>
              </button>
            </form>
          </div>
          @endauth
        </div>
        <div class="social-icon d-flex align-items-center gap-3">
          <a href="https://web.facebook.com/lawgika.co.id/" class="text-decoration-none" title="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/lawgika.co.id?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="text-decoration-none" title="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="https://www.tiktok.com/@lawgika.co.id?is_from_webapp=1&sender_device=pc" target="_blank" class="text-decoration-none" title="TikTok"><i class="fab fa-tiktok"></i></a>
          <a href="https://id.linkedin.com/company/lawgika-associates-law-firm" target="_blank" class="text-decoration-none" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Custom CSS for Mobile Accordion -->
  <style>
    .lw-mobile-nav {
      margin-top: 15px;
      border-top: 1px solid #f0f0f0;
    }

    .lw-nav-item {
      border-bottom: 1px solid #f0f0f0;
    }

    .lw-nav-link {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 5px;
      font-size: 16px;
      font-weight: 600;
      color: #1a1a1a;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .lw-nav-link:hover,
    .lw-nav-link.active {
      color: #4e0616;
      background: #fff9f9;
    }

    .lw-nav-link i {
      font-size: 12px;
      color: #999;
      transition: transform 0.3s ease;
    }

    .lw-nav-link.active i {
      transform: rotate(180deg);
      color: #4e0616;
    }

    .lw-submenu {
      display: none;
      padding-left: 15px;
      background: #fcfcfc;
      border-top: 1px solid #f9f9f9;
    }

    .lw-sub-item {
      border-bottom: 1px solid #f5f5f5;
    }

    .lw-sub-item:last-child {
      border-bottom: none;
    }

    .lw-sub-link {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 5px;
      font-size: 14px;
      font-weight: 500;
      color: #444;
      text-decoration: none;
      transition: all 0.2s ease;
    }

    .lw-sub-link.active {
      color: #4e0616;
      font-weight: 600;
    }

    .lw-sub-link i {
      font-size: 10px;
      color: #bbb;
      transition: transform 0.3s ease;
    }

    .lw-sub-link.active i {
      transform: rotate(180deg);
      color: #4e0616;
    }

    .lw-sub-list {
      display: none;
      list-style: none;
      padding: 0 0 10px 15px;
      margin: 0;
    }

    .lw-sub-list li {
      padding: 8px 0;
    }

    .lw-sub-list li a {
      font-size: 13.5px;
      color: #666;
      text-decoration: none;
      display: block;
      transition: all 0.2s ease;
    }

    .lw-sub-list li a:hover {
      color: #4e0616;
      padding-left: 5px;
    }

    @media (min-width: 1200px) {
      .mobile-menu {
        display: none !important;
      }
    }
  </style>

  <!-- Custom JS for Mobile Accordion -->
  <script>
    (function() {
      function initMobileAccordion() {
        const toggles = document.querySelectorAll('.lw-toggle');

        toggles.forEach(toggle => {
          toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const target = this.nextElementSibling;

            // Toggle active class on link
            this.classList.toggle('active');

            // Toggle display of submenu/sublist
            if (target && (target.classList.contains('lw-submenu') || target.classList.contains('lw-sub-list'))) {
              if (target.style.display === 'block') {
                target.style.display = 'none';
              } else {
                target.style.display = 'block';
              }
            }
          });
        });
      }

      function initOffcanvasAutoClose() {
        const closeTriggers = document.querySelectorAll('.offcanvas-close-trigger');
        closeTriggers.forEach(trigger => {
          trigger.addEventListener('click', function() {
            const offcanvas = document.querySelector('.offcanvas__info');
            const overlay = document.querySelector('.offcanvas__overlay');
            if (offcanvas) offcanvas.classList.remove('info-open');
            if (overlay) overlay.classList.remove('overlay-open');
          });
        });
      }

      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
          initMobileAccordion();
          initOffcanvasAutoClose();
        });
      } else {
        initMobileAccordion();
        initOffcanvasAutoClose();
      }
    })();
  </script>
</div>
</div>
<div class="offcanvas__overlay"></div>

<header class="header-section-1">
  <div class="header-top">
    <div class="container">
      <div class="header-top-wrapper">
        <ul class="contact-list">
          <li>
            <i class="far fa-envelope"></i>
            <a href="mailto:informasi@lawgika.co.id">
              informasi@lawgika.co.id
            </a>
          </li>

        </ul>
        <p data-i18n="header.top_promo">
          Dapatkan konsultasi awal gratis untuk pendirian perusahaan Anda.
        </p>
        <ul class="list">

          <li>
            <i class="fa-regular fa-phone"></i>
            <a href="tel:+622139706065">
              021-3970-6065
            </a>
          </li>
          <!-- <li>
            <i class="fa-light fa-comments"></i><a href="#">Live Chat</a>
          </li> -->
        </ul>
      </div>
    </div>
  </div>
  <div id="header-sticky" class="header-1">
    <div class="container">
      <div class="mega-menu-wrapper">
        <div class="header-main">
          <div class="header-left">
            <div class="logo">
              <a href="{{ url('/') }}" class="header-logo">
                <img
                  src="{{ asset('buyer-file/assets/img/logo-removebg.webp') }}"
                  alt="logo-img"
                  style="width: 16pc"
                  fetchpriority="high" />
              </a>
              <a href="{{ url('/') }}" class="header-logo-2">
                <img
                  src="{{ asset('buyer-file/assets/img/lowo-turun-removebg-preview.webp') }}"
                  alt="logo-img"
                  style="width: 16pc"
                  fetchpriority="high" />
              </a>
            </div>
          </div>
          <div
            class="header-right d-flex justify-content-end align-items-center">
            <div class="mean__menu-wrapper">
              <div class="main-menu">
                <nav id="mobile-menu">
                  <ul>
                    <li class="has-dropdown active menu-thumb">
                      <a href="{{('/')}}">
                        <span data-i18n="nav.home">Beranda</span>
                      </a>

                    </li>

                    <li class="has-dropdown menu-thumb" id="mm-layanan-li">
                      <a href="#" id="mm-layanan-trigger" class="lw-mm-trigger" aria-expanded="false">
                        <span data-i18n="nav.services">Layanan</span>
                        <i class="fas fa-angle-down" id="mm-layanan-arrow" style="transition:transform 0.22s ease;"></i>
                      </a>

                      <div class="lw-mega-wrap" id="lwMegaMenu" role="navigation" aria-label="Layanan Menu">
                        <style>
                          /* Parent (navbar container) harus position: relative */
                          .header-main {
                            position: relative;
                          }

                          /* ---- Mega Menu Container ---- */
                          #mm-layanan-li {
                            position: static;
                          }

                          .lw-mega-wrap {
                            display: none;
                            position: absolute;
                            top: 100%;
                            left: 50%;
                            /* transform is set in the trigger classes below to prevent override */
                            width: 100%;
                            max-width: 1200px;
                            margin-top: 10px;
                            /* Jarak dari navbar */
                            background: #fff;
                            border-radius: 14px;
                            box-shadow: 0 12px 48px rgba(0, 0, 0, 0.13), 0 2px 8px rgba(0, 0, 0, 0.06);
                            border: 1px solid #f0f0f0;
                            z-index: 99999;
                            /* Z-Index Tinggi */
                            overflow: hidden;
                          }

                          /* Trigger: JS-controlled via .lw-mm-open class (click only) */
                          .lw-mega-wrap {
                            opacity: 0;
                            transform: translate(-50%, 8px);
                            /* Center to container & offset slightly */
                            transition: opacity 0.18s ease, transform 0.18s ease;
                            pointer-events: none;
                          }

                          .lw-mega-wrap.lw-mm-open {
                            display: flex !important;
                            flex-direction: column;
                            opacity: 1;
                            transform: translate(-50%, 0);
                            /* Locked at center */
                            pointer-events: auto;
                          }

                          /* ---- Inner layout: left columns + right sidebar ---- */
                          .lw-mega-body {
                            display: flex;
                            align-items: stretch;
                            width: 100%;
                          }

                          /* ---- Left: 3 category columns ---- */
                          .lw-mega-cols {
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            gap: 0;
                            flex: 1;
                            padding: 28px 24px;
                          }

                          .lw-mega-cols-4 {
                            display: grid;
                            grid-template-columns: repeat(4, 1fr);
                            gap: 0;
                            flex: 1;
                            padding: 28px 24px;
                          }

                          .lw-mega-cols-5 {
                            flex: 1;
                            display: flex;
                            align-items: stretch;
                          }

                          .lw-col-group-2 {
                            display: grid;
                            grid-template-columns: repeat(2, 1fr);
                            flex: 0 0 40%;
                            padding: 28px 24px;
                            background: #fdfdfd;
                            border-right: 1px solid #f3f4f6;
                          }

                          .lw-col-group-3 {
                            display: grid;
                            grid-template-columns: repeat(3, 1fr);
                            flex: 0 0 60%;
                            padding: 28px 24px;
                          }

                          .lw-col {
                            padding: 0 20px;
                            border-right: 1px solid #f3f4f6;
                          }

                          .lw-col:first-child {
                            padding-left: 0;
                          }

                          .lw-col:last-child {
                            border-right: none;
                          }

                          .lw-col-title {
                            font-size: 0.7rem;
                            font-weight: 700;
                            letter-spacing: 0.08em;
                            text-transform: uppercase;
                            color: #8c0c1e;
                            /* Darker red for professional look */
                            margin-bottom: 16px;
                            padding-bottom: 8px;
                            border-bottom: 1px solid #f0f0f0;
                            display: flex;
                            align-items: center;
                            gap: 8px;
                          }

                          .lw-col-title i {
                            color: #4e0616;
                            font-size: 0.75rem;
                          }

                          .lw-col ul {
                            list-style: none;
                            padding: 0;
                            margin: 0;
                          }

                          .lw-col ul li {
                            margin-bottom: 2px;
                          }

                          .lw-col ul li a {
                            display: block;
                            font-size: 0.88rem;
                            /* Slightly larger for professionalism */
                            font-weight: 500;
                            color: #2c3e50;
                            /* Deep gray-blue for better contrast */
                            padding: 8px 12px 8px 0;
                            text-decoration: none;
                            border-radius: 6px;
                            transition: all 0.2s ease;
                            line-height: 1.5;
                          }

                          .lw-col ul li a:hover {
                            color: #4e0616;
                            background: #fff5f6;
                            padding-left: 8px;
                          }

                          /* ---- Right: Sidebar ---- */
                          .lw-mega-sidebar {
                            width: 240px;
                            flex-shrink: 0;
                            /* background: #fafafa; */
                            border-left: 1px solid #f0f0f0;
                            padding: 24px 18px;
                            display: flex;
                            flex-direction: column;
                            gap: 10px;
                          }

                          .lw-sidebar-label {
                            font-size: 0.68rem;
                            font-weight: 700;
                            letter-spacing: 0.1em;
                            text-transform: uppercase;
                            color: #94a3b8;
                            margin-bottom: 8px;
                          }

                          .lw-sidebar-card {
                            display: block;
                            background: #f8fafc;
                            border: 1px solid #e2e8f0;
                            border-radius: 12px;
                            padding: 16px;
                            text-decoration: none;
                            transition: all 0.2s ease-in-out;
                          }

                          .lw-sidebar-card:hover {
                            background: #ffffff;
                            box-shadow: 0 4px 20px rgba(78, 6, 22, 0.08);
                            border-color: #4e0616;
                            transform: translateY(-2px);
                          }

                          .lw-sidebar-card-title {
                            font-size: 0.9rem;
                            font-weight: 700;
                            color: #1e293b;
                            margin-bottom: 4px;
                          }

                          .lw-sidebar-card-sub {
                            font-size: 0.75rem;
                            color: #64748b;
                            line-height: 1.4;
                          }

                          .lw-sidebar-card-price {
                            font-size: 0.8rem;
                            font-weight: 700;
                            color: #8c0c1e;
                            margin-top: 8px;
                          }

                          .lw-sidebar-cta {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 8px;
                            margin-top: 8px;
                            background: linear-gradient(135deg, #4e0616 0%, #8c0c1e 100%);
                            color: #fff !important;
                            border-radius: 10px;
                            padding: 12px 14px;
                            font-size: 0.85rem;
                            font-weight: 600;
                            text-decoration: none;
                            transition: all 0.2s ease;
                            box-shadow: 0 4px 12px rgba(78, 6, 22, 0.2);
                          }

                          .lw-sidebar-cta:hover {
                            transform: scale(1.02);
                            box-shadow: 0 6px 16px rgba(78, 6, 22, 0.3);
                          }


                          background: #b91c1c;
                          color: #fff;
                          }

                          /* ---- Responsive: hide sidebar on <1200px ---- */
                          @media (max-width: 1199px) {
                            .lw-mega-sidebar {
                              display: none;
                            }

                            .lw-mega-cols {
                              grid-template-columns: repeat(3, 1fr);
                            }

                            .lw-mega-cols-4 {
                              grid-template-columns: repeat(3, 1fr);
                            }

                            .lw-mega-wrap {
                              width: min(900px, 96vw);
                            }
                          }

                          @media (max-width: 991px) {
                            .lw-mega-cols {
                              grid-template-columns: repeat(2, 1fr);
                            }

                            .lw-mega-cols-4 {
                              grid-template-columns: repeat(2, 1fr);
                            }
                          }
                        </style>

                        <div class="container p-0">
                          <div class="lw-mega-body">
                            <div class="lw-mega-cols-4">
                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-file-contract"></i> <span data-i18n="nav.services.office_title">Kantor &amp; Ruang Podcast</span>
                                </div>
                                <ul>
                                  <li><a href="{{ url('/virtual-office') }}">Virtual Office</a></li>
                                  <li><a href="{{ url('/sewa-meeting-room') }}">Sewa Meeting Room</a></li>
                                  <li><a href="{{ url('/sewa-ruang-podcast') }}">Sewa Ruang Podcast</a></li>
                                </ul>
                              </div>

                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-building"></i> <span data-i18n="nav.services.legal_title">Legalitas Perusahaan</span>
                                </div>
                                <ul>
                                  <li><a href="{{ url('/pendirian-pt-perorangan') }}">Pendirian PT Perorangan</a></li>
                                  <li><a href="{{ url('/pendirian-pt') }}">Pendirian PT Perseroan</a></li>
                                  <li><a href="{{ url('/pendirian-pt-pma') }}">Pendirian PT PMA</a></li>
                                  <li><a href="{{ url('/pendirian-cv') }}">Pendirian CV</a></li>
                                  <li><a href="{{ url('/pendirian-yayasan') }}">Pendirian Yayasan</a></li>
                                  <li><a href="{{ url('/pendirian-firma') }}">Pendirian Firma</a></li>

                                </ul>
                              </div>

                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-calculator"></i> <span data-i18n="nav.services.tax_title">Pajak &amp; Pembukuan</span>
                                </div>
                                <ul>
                                  <li><a href="{{ url('/jasa-pembukuan-perpajakan') }}">Jasa Pembukuan & Perpajakan</a></li>
                                  <!-- <li><a href="{{ url('/pendaftaran-npwp') }}">Pendaftaran NPWP</a></li> -->
                                  <li><a href="{{ url('/pelaporan-spt-tahunan') }}">Pelaporan SPT Tahunan</a></li>
                                </ul>

                              </div>

                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-building"></i> <span data-i18n="nav.services.perizinan_title">Perizinan Dan Hukum</span>
                                </div>
                                <ul>
                                  <li><a href="{{ url('/haki') }}">Hak Kekayaan Intelektual</a></li> 
                                  <li><a href="{{ url('/nib-dan-oss') }}">NIB dan OSS</a></li>
                                  <li><a href="{{ url('/laporan-lkpm') }}">Laporan LKPM</a></li>
                                  <li><a href="{{ url('/sertifikat-iso') }}">Sertifikat ISO</a></li>
                                  <li><a href="{{ url('/pengurusan-pkp') }}">Pengurusan PKP</a></li>
                                </ul>
                                <a href="{{ url('/perizinan-dan-hukum') }}" class="lw-sidebar-cta" style="padding:13px !important; color:white !important">
                                  Perizinan Lainnya <i class="fas fa-arrow-right"></i>
                                </a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>

<style>
  /* Fix Pusat Pelatihan & Profil Legalitas: compact, center di bawah trigger */
  #mm-pelatihan-li, #mm-tentang-li { position: relative !important; }
  #lwMegaMenuPelatihan, #lwMegaMenuTentang {
    width: auto !important;
    min-width: 220px !important;
    max-width: 300px !important;
    left: 50% !important;
    transform: translate(-50%, 8px) !important;
  }
  #lwMegaMenuPelatihan.lw-mm-open, #lwMegaMenuTentang.lw-mm-open {
    transform: translate(-50%, 0) !important;
  }
  #lwMegaMenuPelatihan .lw-mega-body, #lwMegaMenuTentang .lw-mega-body { display: block !important; }
</style>
                    <li class="has-dropdown menu-thumb" id="mm-pelatihan-li">
                      <a href="#" id="mm-pelatihan-trigger" class="lw-mm-trigger" aria-expanded="false">
                        <span data-i18n="nav.training">Pusat Pelatihan</span>
                        <i class="fas fa-angle-down" id="mm-pelatihan-arrow" style="transition:transform 0.22s ease;"></i>
                      </a>
                      <div class="lw-mega-wrap" id="lwMegaMenuPelatihan" role="navigation" aria-label="Pusat Pelatihan Menu">
                        <div style="padding: 20px 24px;">
                          <div class="lw-col-title">
                            <i class="fas fa-graduation-cap"></i> <span data-i18n="nav.training.section_title">Program Edukasi</span>
                          </div>
                          <ul style="list-style:none;padding:0;margin:0;">
                            <li><a href="{{ url('/upcoming-event') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.training.webinar">Pelatihan Webinar</a></li>
                            <li><a href="{{ url('/berita') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.training.artikel">Artikel Berita</a></li>
                            <li><a href="{{ url('/database-peraturan') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.training.peraturan">Kumpulan Peraturan</a></li>
                          </ul>
                        </div>
                      </div>
                    </li>

                    <li class="has-dropdown menu-thumb" id="mm-tentang-li">
                      <a href="#" id="mm-tentang-trigger" class="lw-mm-trigger" aria-expanded="false">
                        <span data-i18n="nav.profile">Profil &amp; Legalitas</span>
                        <i class="fas fa-angle-down" id="mm-tentang-arrow" style="transition:transform 0.22s ease;"></i>
                      </a>
                      <div class="lw-mega-wrap" id="lwMegaMenuTentang" role="navigation" aria-label="Tentang Kami Menu">
                        <div style="padding: 20px 24px;">
                          <div class="lw-col-title">
                            <i class="fas fa-building"></i> <span data-i18n="nav.profile.section_title">Profil &amp; Legalitas</span>
                          </div>
                          <ul style="list-style:none;padding:0;margin:0;">
                            <li><a href="{{ url('tentang-kami') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.profile.perusahaan">Profil Perusahaan</a></li>
                            <li><a href="{{ url('karir') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.profile.karir">Karir</a></li>
                            <li><a href="{{ url('kerjasama-bisnis') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.profile.kerjasama">Kerjasama Bisnis</a></li>
                              <li><a href="{{ url('promo') }}" class="lw-col ul li a" style="color:black !important;" data-i18n="nav.profile.promo">Promo </a></li> 
                          </ul>
                        </div>
                      </div>
                    </li>

                    <li>
                      <a href="{{ url('#') }}"><span></span></a>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
            <a href="#" class="search-trigger search-icon"><i class="fal fa-search"></i></a>

            <span class="lw-lang-sep d-lg-none"></span>

            <!-- Language Switcher -->
            <div class="lw-lang-switcher d-flex my-auto" id="lw-lang-switcher-desktop" style="margin-left: -3px; margin-right: 10px;">
              <button class="lw-lang-btn" id="lw-lang-trigger" type="button" style="padding: 6px 10px; font-size: 0.8rem;">
                <span id="lw-lang-current-flag">🇮🇩</span>
                <span id="lw-lang-current-name" class="d-none d-lg-inline">Indonesia</span>
                <i class="fas fa-angle-down lw-lang-arrow"></i>
              </button>
              <div class="lw-lang-dropdown" id="lw-lang-dropdown" style="right: 0; min-width: 160px;">
                <div class="lw-lang-item" data-lw-lang="id">
                  <span class="lw-lang-flag">🇮🇩</span>
                  <span data-i18n="lang.id">Bahasa Indonesia</span>
                </div>
                <div class="lw-lang-item" data-lw-lang="en">
                  <span class="lw-lang-flag">🇺🇸</span>
                  <span data-i18n="lang.en">English</span>
                </div>
                <div class="lw-lang-item" data-lw-lang="zh">
                  <span class="lw-lang-flag">🇨🇳</span>
                  <span data-i18n="lang.zh">中文</span>
                </div>
              </div>
            </div>

            <div class="header__hamburger d-lg-none my-auto">
              <div class="sidebar__toggle">
                <i class="fas fa-bars"></i>
              </div>
            </div>
            <div class="header-button">
              @guest
              <a href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <span data-i18n="nav.login">Masuk</span>
              </a>
              @endguest

              @auth
              <div class="dropdown">
                <button class="theme-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  Profile
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  @if(Auth::user()->role === 'admin')
                  <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                  @else
                  <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                  @endif

                  <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                      @csrf
                      <button type="submit" class="dropdown-item" data-i18n="nav.logout">Keluar</button>
                    </form>
                  </li>
                </ul>
              </div>
              @endauth
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

{{-- ===== NAVBAR BEHAVIOR & MEGA MENU CLICK FIX ===== --}}
<style>
  /*
   * ─── 1. NAVBAR KONDISI AWAL (HERO SECTION) ─────────────────────────────────
   * Transparan, teks putih, logo terang.
   */
  #header-sticky {
    background: #4e0516 !important;
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  /* Tampilkan logo terang, sembunyikan logo gelap */
  .header-1 .header-left .logo .header-logo {
    display: block !important;
  }

  .header-1 .header-left .logo .header-logo-2 {
    display: none !important;
  }

  /* Nav link & icon putih, konsisten transisi */
  .header-1 .header-main .main-menu ul li>a {
    color: white;
    transition: color 0.3s ease;
  }

  .header-1 .header-main .main-menu ul li>a:hover {
    color: #fca5a5 !important;
  }

  /* Search icon, hamburger — text putih */
  .header-1 .search-icon,
  .header-1 .sidebar__toggle {
    color: #ffffff !important;
    transition: color 0.3s ease;
  }

  .header-1 .header-button .theme-btn {
    background-color: #ffffff !important;
    color: #000000 !important;
    border: 1px solid #ffffff !important;
    transition: all 0.3s ease !important;
  }

  .header-1 .header-button .theme-btn:hover {
    background-color: #f3f4f6 !important;
    color: #000000 !important;
    border-color: #f3f4f6 !important;
  }

  /* Pastikan dropdown / mega menu textnya tetap gelap */
  .header-1 .header-main .main-menu ul li ul.submenu a,
  .header-1 .header-main .main-menu ul li .lw-mega-body a {
    color: #111827 !important;
  }

  .header-1 .header-main .main-menu ul li ul.submenu a:hover,
  .header-1 .header-main .main-menu ul li .lw-mega-body a:hover {
    color: #4e0616 !important;
  }

  /*
   * ─── 2. NAVBAR KONDISI SETELAH SCROLL (Class: scrolled) ────────────────────
   * Putih solid, teks hitam, logo gelap, ada shadow ringan.
   */
  #header-sticky.scrolled {
    background: #ffffff !important;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.09) !important;
  }

  /* Tampilkan logo gelap, sembunyikan logo terang */
  #header-sticky.scrolled .header-left .logo .header-logo {
    display: none !important;
  }

  #header-sticky.scrolled .header-left .logo .header-logo-2 {
    display: block !important;
  }

  /* Nav link & icon hitam */
  #header-sticky.scrolled .header-main .main-menu ul li>a {
    color: #000000 !important;
  }

  #header-sticky.scrolled .header-main .main-menu ul li>a:hover {
    color: #4e0616 !important;
  }

  #header-sticky.scrolled .search-icon,
  #header-sticky.scrolled .sidebar__toggle {
    color: #000000 !important;
  }

  /* Jika tema memiliki class .sticky bawaan yang mengganggu, timpa juga */
  #header-sticky.sticky {
    background: #ffffff !important;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.09) !important;
  }

  #header-sticky.sticky .header-left .logo .header-logo {
    display: none !important;
  }

  #header-sticky.sticky .header-left .logo .header-logo-2 {
    display: block !important;
  }

  #header-sticky.sticky .header-main .main-menu ul li>a,
  #header-sticky.sticky .search-icon,
  #header-sticky.sticky .sidebar__toggle {
    color: #000000 !important;
  }

  /* Scrolled/Sticky state button styling */
  #header-sticky.scrolled .header-button .theme-btn,
  #header-sticky.sticky .header-button .theme-btn {
    background-color: #4e0516 !important;
    color: #ffffff !important;
    border: 1px solid #4e0516 !important;
  }

  #header-sticky.scrolled .header-button .theme-btn:hover,
  #header-sticky.sticky .header-button .theme-btn:hover {
    background-color: #8c0c1e !important;
    color: #ffffff !important;
    border-color: #8c0c1e !important;
  }

  /*
   * ─── 3. MEGA MENU: tampilan default hidden melalui CSS ───────────────────
   * (JS akan toggle class .lw-mm-open untuk show/hide + transition)
   */
  .lw-mega-wrap {
    display: none;
  }

  .lw-mega-wrap.lw-mm-open {
    display: flex !important;
  }

  /* Arrow rotasi saat aktif */
  #mm-layanan-arrow,
  #mm-pelatihan-arrow,
  #mm-tentang-arrow {
    display: inline-block;
  }

  .lw-mm-trigger[aria-expanded="true"] #mm-layanan-arrow,
  .lw-mm-trigger.active #mm-layanan-arrow,
  .lw-mm-trigger[aria-expanded="true"] #mm-pelatihan-arrow,
  .lw-mm-trigger.active #mm-pelatihan-arrow,
  .lw-mm-trigger[aria-expanded="true"] #mm-tentang-arrow,
  .lw-mm-trigger.active #mm-tentang-arrow {
    transform: rotate(180deg);
  }

  /* Prevent background scrolling when Bootstrap modals are active */
  html:has(body.modal-open),
  html.modal-open,
  body.modal-open {
    overflow: hidden !important;
    height: 100vh !important;
  }

  /* Styling khusus button create account di modal login */
  #btn-go-register.theme-create.style-border {
    border-width: 2px !important;
    border-color: #fff !important;
    color: #4e0516 !important;
    font-weight: 700 !important;
  }

  #btn-go-register.theme-create.style-border:hover {
    background-color: #4e0516 !important;
    color: #ffffff !important;
  }
</style>

<script>
  (function() {
    /* Tunggu DOM siap */
    function initMegaMenu(triggerId, menuId) {
      var trigger = document.getElementById(triggerId);
      var menu = document.getElementById(menuId);
      if (!trigger || !menu) return;

      var isOpen = false;

      function openMenu() {
        // Tutup mega menu lain
        document.querySelectorAll('.lw-mega-wrap').forEach(function(m) {
          if (m !== menu) m.classList.remove('lw-mm-open');
        });
        document.querySelectorAll('.lw-mm-trigger').forEach(function(t) {
          if (t !== trigger) {
            t.classList.remove('active');
            t.setAttribute('aria-expanded', 'false');
          }
        });

        isOpen = true;
        menu.classList.add('lw-mm-open');
        trigger.setAttribute('aria-expanded', 'true');
        trigger.classList.add('active');
      }

      function closeMenu() {
        isOpen = false;
        menu.classList.remove('lw-mm-open');
        trigger.setAttribute('aria-expanded', 'false');
        trigger.classList.remove('active');
      }

      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        isOpen ? closeMenu() : openMenu();
      });

      menu.addEventListener('click', function(e) {
        e.stopPropagation();
      });

      document.addEventListener('click', function() {
        if (isOpen) closeMenu();
      });

      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isOpen) closeMenu();
      });
    }

    function initNavbarScroll() {
      var navbar = document.getElementById('header-sticky');
      if (!navbar) return;

      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });

      // Cek posisi awal saat halaman di-load
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      }
    }

    /* DOMContentLoaded atau langsung jika sudah ready */
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        initMegaMenu('mm-layanan-trigger', 'lwMegaMenu');
        initMegaMenu('mm-pelatihan-trigger', 'lwMegaMenuPelatihan');
        initMegaMenu('mm-tentang-trigger', 'lwMegaMenuTentang');
        initNavbarScroll();
      });
    } else {
      initMegaMenu('mm-layanan-trigger', 'lwMegaMenu');
      initMegaMenu('mm-pelatihan-trigger', 'lwMegaMenuPelatihan');
      initMegaMenu('mm-tentang-trigger', 'lwMegaMenuTentang');
      initNavbarScroll();
    }
  })();


  /* ── Auto-buka modal login/register jika ada error atau parameter URL ── */
  function lwAutoOpenLoginModal() {
    const urlParams = new URLSearchParams(window.location.search);
    const triggerLogin = urlParams.get('login') === '1';
    const triggerRegister = urlParams.get('register') === '1';
    const hasLoginError = document.getElementById('lw-login-error-box');
    const hasRegisterError = document.getElementById('lw-register-error-box');

    let targetModalId = null;
    if (hasLoginError || triggerLogin) {
      targetModalId = 'exampleModal';
    } else if (hasRegisterError || triggerRegister) {
      targetModalId = 'exampleModal2';
    }

    if (!targetModalId) return;

    var modalEl = document.getElementById(targetModalId);
    if (!modalEl) return;

    setTimeout(function() {
      if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var m = bootstrap.Modal.getOrCreateInstance(modalEl);
        m.show();

        // Clean up URL parameter without refreshing
        const newUrl = window.location.pathname + window.location.hash;
        window.history.replaceState({}, document.title, newUrl);
      }
    }, 250);
  }

  /* ── Dismiss toast ── */
  window.lwDismissToast = function(el) {
    if (!el) return;
    el.classList.add('lw-toast-hide');
    setTimeout(function() {
      if (el.parentNode) el.parentNode.removeChild(el);
    }, 320);
  };

  /* ── Auto-dismiss toast setelah 5 detik ── */
  function lwAutoDismissToasts() {
    var toasts = document.querySelectorAll('.lw-toast');
    toasts.forEach(function(t) {
      setTimeout(function() {
        lwDismissToast(t);
      }, 5200);
    });
  }

  /* Lock body scroll when any bootstrap modal is shown */
  function lwInitModalScrollLock() {
    document.addEventListener('show.bs.modal', function() {
      document.documentElement.style.setProperty('overflow', 'hidden', 'important');
      document.body.style.setProperty('overflow', 'hidden', 'important');
      document.documentElement.classList.add('modal-open');
    });

    document.addEventListener('hidden.bs.modal', function() {
      var openModals = document.querySelectorAll('.modal.show');
      if (openModals.length === 0) {
        document.documentElement.style.removeProperty('overflow');
        document.body.style.removeProperty('overflow');
        document.documentElement.classList.remove('modal-open');
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      lwAutoOpenLoginModal();
      lwAutoDismissToasts();
      lwInitModalScrollLock();
    });
  } else {
    lwAutoOpenLoginModal();
    lwAutoDismissToasts();
    lwInitModalScrollLock();
  }
</script>

{{-- ===== TOAST NOTIFIKASI LOGIN GAGAL ===== --}}
@if ($errors->any() && old('login_attempt'))
<style>
  /* Toast container */
  #lw-toast-wrap {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
  }

  .lw-toast {
    pointer-events: auto;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(78, 6, 22, 0.18), 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #4e0616;
    padding: 16px 20px 16px 18px;
    min-width: 300px;
    max-width: 380px;
    animation: lwToastIn 0.35s cubic-bezier(.4, 0, .2, 1) both;
  }

  .lw-toast.lw-toast-hide {
    animation: lwToastOut 0.3s cubic-bezier(.4, 0, .2, 1) both;
  }

  @keyframes lwToastIn {
    from {
      opacity: 0;
      transform: translateX(60px);
    }

    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes lwToastOut {
    from {
      opacity: 1;
      transform: translateX(0);
    }

    to {
      opacity: 0;
      transform: translateX(60px);
    }
  }

  .lw-toast-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #fff0f1;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #4e0616;
    font-size: 1.1rem;
  }

  .lw-toast-body {
    flex: 1;
  }

  .lw-toast-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #4e0616;
    margin-bottom: 3px;
  }

  .lw-toast-msg {
    font-size: 0.80rem;
    color: #374151;
    line-height: 1.45;
    margin: 0;
  }

  .lw-toast-close {
    background: none;
    border: none;
    color: #9ca3af;
    font-size: 1rem;
    cursor: pointer;
    padding: 0;
    line-height: 1;
    flex-shrink: 0;
    margin-top: 1px;
  }

  .lw-toast-close:hover {
    color: #4e0616;
  }

  .lw-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: #4e0616;
    border-radius: 0 0 14px 14px;
    animation: lwProgress 5s linear forwards;
    width: 100%;
  }

  @keyframes lwProgress {
    from {
      width: 100%;
    }

    to {
      width: 0%;
    }
  }
</style>

<div id="lw-toast-wrap">
  @foreach ($errors->all() as $error)
  <div class="lw-toast" style="position:relative;overflow:hidden;">
    <div class="lw-toast-icon">
      <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="lw-toast-body">
      <div class="lw-toast-title">Login Gagal</div>
      <p class="lw-toast-msg">{{ $error }}</p>
    </div>
    <button class="lw-toast-close" onclick="lwDismissToast(this.closest('.lw-toast'))" title="Tutup">
      <i class="fas fa-times"></i>
    </button>
    <div class="lw-toast-progress"></div>
  </div>
  @endforeach
</div>
@endif


<div
  class="modal modal-common-wrap fade"
  id="exampleModal"
  tabindex="-1"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div
        class="modal-body d-md-flex d-grid gap-md-0 gap-5 align-items-center">
        <div class="modal-common-content">
          <div class="box">
            <h2 data-i18n="modal.login.title">welcome back!</h2>

            {{-- Inline error di dalam modal --}}
            @if ($errors->any() && old('login_attempt'))
            <div id="lw-login-error-box" style="background:#fff0f1;border:1.5px solid #fca5a5;border-radius:10px;padding:11px 15px;margin-bottom:14px;display:flex;align-items:flex-start;gap:10px;">
              <i class="fas fa-exclamation-circle" style="color:#4e0616;margin-top:2px;flex-shrink:0;"></i>
              <div>
                @foreach ($errors->all() as $error)
                <p style="margin:0;font-size:0.82rem;color:#b91c1c;font-weight:500;line-height:1.5;">{{ $error }}</p>
                @endforeach
              </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="login-from">
              @csrf
              <input type="hidden" name="login_attempt" value="1">
              <div class="form-grp cmn-mb">
                <input type="email" name="email" data-i18n-placeholder="modal.login.email" placeholder="Email Address" value="{{ old('email') }}" />
              </div>
              <div class="form-grp">
                <input type="password" name="password" data-i18n-placeholder="modal.login.password" placeholder="Enter Password" value="" />
              </div>
              <div
                class="d-flex forgot-inner-area cmn-mb justify-content-between gap-2 flex-wrap align-items-center">
                <div class="form-check checkmark-inner">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="remember"
                    id="flexCheckChecked"
                    checked />
                  <label class="form-check-label" for="flexCheckChecked" data-i18n="modal.login.remember">
                    Remember me
                  </label>
                </div>
                @if (Route::has('password.request'))
                <a class="forgot btn btn-link"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal3"
                  data-i18n="modal.login.forgot">
                  Forgot Your Password?
                </a>
                @endif
              </div>
              <button type="submit" class="theme-btn w-100">
                <span data-i18n="modal.login.submit"> Log in </span>
              </button>
            </form>
            <span class="orting-badge" data-i18n="modal.login.or"> Or </span>
            <div
              class="form-check d-flex align-items-center gap-2 from-customradio">
              <input
                class="form-check-input"
                type="radio"
                name="flexRadioDefault"
                id="flexRadioDefault1" />
              <label class="form-check-label" for="flexRadioDefault1" data-i18n="modal.login.terms">
                i accept your terms &amp; conditions
              </label>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
                  <img src="{{ asset('lawgika/masuk.webp') }}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              id="btn-go-register"
              class="theme-create style-border"
              data-i18n="modal.login.create_account">
              create account
            </button>
            <button
              id="btn-stay-login"
              class="theme-btn"
              data-i18n="modal.login.submit">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div
  class="modal modal-common-wrap fade"
  id="exampleModal2"
  tabindex="-1"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div
        class="modal-body d-md-flex d-grid gap-md-0 gap-5 align-items-center">
        <div class="modal-common-content">
          <div class="box">
            <h2 data-i18n="modal.register.title">Create account</h2>

            {{-- Inline error di dalam modal register --}}
            @if ($errors->any() && old('register_attempt'))
            <div id="lw-register-error-box" style="background:#fff0f1;border:1.5px solid #fca5a5;border-radius:10px;padding:11px 15px;margin-bottom:14px;display:flex;align-items:flex-start;gap:10px;">
              <i class="fas fa-exclamation-circle" style="color:#4e0616;margin-top:2px;flex-shrink:0;"></i>
              <div>
                @foreach ($errors->all() as $error)
                <p style="margin:0;font-size:0.82rem;color:#b91c1c;font-weight:500;line-height:1.5;">{{ $error }}</p>
                @endforeach
              </div>
            </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="register-form" class="login-from">
              @csrf
              <input type="hidden" name="register_attempt" value="1">
              <div class="form-grp cmn-mb">
                <input type="text" name="name" data-i18n-placeholder="modal.register.name" placeholder="User name" />
              </div>
              <div class="form-grp cmn-mb">
                <input type="email" name="email" data-i18n-placeholder="modal.register.email" placeholder="Email Address" />
              </div>
              <div class="form-grp cmn-mb">
                <input type="password" name="password" data-i18n-placeholder="modal.register.password" placeholder="Enter Password" />
              </div>
              <div class="form-grp">
                <input type="password" name="password_confirmation" data-i18n-placeholder="modal.register.confirm_password" placeholder="Enter Confirm password" />
              </div>
            </form>


            <div class="pb-xxl-3">
              <div
                class="form-check d-flex align-items-center gap-2 from-customradio">
                <input
                  class="form-check-input"
                  type="radio"
                  name="flexRadioDefault"
                  id="flexRadioDefault11" />
                <label class="form-check-label" for="flexRadioDefault11" data-i18n="modal.register.terms">
                  i accept your terms & conditions
                </label>
              </div>
            </div>
            <div class="mt-4">
              <button type="submit" form="register-form" class="theme-btn w-100">
                <span data-i18n="modal.register.submit"> Daftar </span>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
                  <img src="{{ asset('lawgika/daftar.webp') }}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              id="btn-stay-register"
              class="theme-btn"
              data-i18n="modal.login.create_account">
              Create Akun
            </button>
            <button
              id="btn-go-login"
              class="theme-create style-border"
              data-i18n="modal.login.submit">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div
  class="modal modal-common-wrap fade"
  id="exampleModal3"
  tabindex="-1"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <button
          type="button"
          class="btn-close"
          data-bs-dismiss="modal"
          aria-label="Close"></button>
      </div>
      <div
        class="modal-body d-md-flex d-grid gap-md-0 gap-5 align-items-center">
        <div class="modal-common-content">
          <div class="box">
            <h2 data-i18n="modal.forgot.title">Forgot Password</h2>
            <form action="{{ route('password.email') }}" method="POST" id="forgot-password-form" class="login-from">
              @csrf
              <div class="form-grp cmn-mb">
                <input type="email" name="email" data-i18n-placeholder="modal.forgot.email" placeholder="Email Address" />
              </div>
            </form>

            <div class="mt-4">
              <button type="submit" form="forgot-password-form" class="theme-btn w-100">
                <span data-i18n="modal.forgot.submit"> Send Password Reset Link </span>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
          <img src="{{ asset('buyer-file/assets/img/sign/create.webp') }}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              id="btn-forgot-go-register"
              class="theme-create style-border">
              create account
            </button>
            <button
              id="btn-forgot-go-login"
              class="theme-btn">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script>
  (function() {
    'use strict';

    /**
     * switchModal(fromId, toId)
     * Tutup modal asal, tunggu animasi selesai (hidden.bs.modal),
     * lalu buka modal tujuan. Pola ini mencegah double-backdrop dan glitch.
     */
    function switchModal(fromId, toId) {
      var fromEl = document.getElementById(fromId);
      var toEl = document.getElementById(toId);
      if (!fromEl || !toEl) return;

      var fromModal = bootstrap.Modal.getInstance(fromEl) ||
        new bootstrap.Modal(fromEl);

      /* One-time listener: setelah modal asal benar-benar tersembunyi, buka yang baru */
      fromEl.addEventListener('hidden.bs.modal', function handler() {
        fromEl.removeEventListener('hidden.bs.modal', handler);
        bootstrap.Modal.getOrCreateInstance(toEl).show();
      });

      fromModal.hide();
    }

    function initModalSwitcher() {
      /* Login → Register */
      var btnGoRegister = document.getElementById('btn-go-register');
      if (btnGoRegister) {
        btnGoRegister.addEventListener('click', function() {
          switchModal('exampleModal', 'exampleModal2');
        });
      }

      /* Register → Login */
      var btnGoLogin = document.getElementById('btn-go-login');
      if (btnGoLogin) {
        btnGoLogin.addEventListener('click', function() {
          switchModal('exampleModal2', 'exampleModal');
        });
      }

      /* Forgot Password → Register */
      var btnForgotGoRegister = document.getElementById('btn-forgot-go-register');
      if (btnForgotGoRegister) {
        btnForgotGoRegister.addEventListener('click', function() {
          switchModal('exampleModal3', 'exampleModal2');
        });
      }

      /* Forgot Password → Login */
      var btnForgotGoLogin = document.getElementById('btn-forgot-go-login');
      if (btnForgotGoLogin) {
        btnForgotGoLogin.addEventListener('click', function() {
          switchModal('exampleModal3', 'exampleModal');
        });
      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initModalSwitcher);
    } else {
      initModalSwitcher();
    }
  })();
</script>

<div class="search-wrap">
  <div class="search-inner">
    <i class="fas fa-times search-close" id="search-close"></i>
    <div class="search-cell">
      <form method="get">
        <div class="search-field-holder">
          <input
            type="search"
            class="main-search-input"
            data-i18n-placeholder="search.placeholder"
            placeholder="Search..." />
        </div>
      </form>
    </div>
  </div>
</div>