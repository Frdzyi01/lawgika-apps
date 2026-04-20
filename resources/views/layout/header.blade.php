<!-- Offcanvas Area Start -->
<div class="fix-area">
  <div class="offcanvas__info">
    <div class="offcanvas__wrapper">
      <div class="offcanvas__content">
        <div
          class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
          <div class="offcanvas__logo">
            <a href="{{ url('/') }}">
              <img src="{{ asset('buyer-file/assets/img/logo-remove-black.png') }}" alt="logo-img" />
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
        <div class="mobile-menu fix mb-3"></div>
        <div class="offcanvas__contact">
          <h4>Contact Info</h4>
          <ul>
            <li class="d-flex align-items-center">
              <div class="offcanvas__contact-icon">
                <i class="fal fa-map-marker-alt"></i>
              </div>
              <div class="offcanvas__contact-text">
                <a target="_blank" href="#">Main Street, Melbourne, Australia</a>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <div class="offcanvas__contact-icon mr-15">
                <i class="fal fa-envelope"></i>
              </div>
              <div class="offcanvas__contact-text">
                <a href="#"><span class="mailto:info@example.com">info@example.com</span></a>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <div class="offcanvas__contact-icon mr-15">
                <i class="fal fa-clock"></i>
              </div>
              <div class="offcanvas__contact-text">
                <a target="_blank" href="#">Mod-friday, 09am -05pm</a>
              </div>
            </li>
            <li class="d-flex align-items-center">
              <div class="offcanvas__contact-icon mr-15">
                <i class="far fa-phone"></i>
              </div>
              <div class="offcanvas__contact-text">
                <a href="#">+11002345909</a>
              </div>
            </li>
          </ul>
          <div class="header-button mt-4">

            {{-- Jika belum login --}}
            @guest
            <div class="d-flex flex-column gap-2">

              {{-- Login --}}
              <button class="theme-btn text-center"
                data-bs-toggle="modal"
                data-bs-target="#exampleModal">
                Masuk <i class="fa-solid fa-arrow-right-long"></i>
              </button>

              {{-- Register --}}
              <button class="theme-btn text-center"
                data-bs-toggle="modal"
                data-bs-target="#registerModal">
                Daftar <i class="fa-solid fa-user-plus"></i>
              </button>

            </div>
            @endguest

            {{-- Jika sudah login --}}
            @auth
            <div class="d-flex flex-column gap-2">

              {{-- Dashboard --}}
              @if(Auth::user()->role === 'admin')
              <a href="/admin/dashboard" class="theme-btn text-center">
                Dashboard <i class="fa-solid fa-arrow-right-long"></i>
              </a>
              @else
              <a href="/dashboard" class="theme-btn text-center">
                Dashboard <i class="fa-solid fa-arrow-right-long"></i>
              </a>
              @endif

              {{-- Logout --}}
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="theme-btn text-center w-100">
                  Keluar <i class="fa-solid fa-sign-out-alt"></i>
                </button>
              </form>

            </div>
            @endauth

          </div>
          <div class="social-icon d-flex align-items-center">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="offcanvas__overlay"></div>

<!-- Header Section Start -->
<header class="header-section-1">
  <div class="header-top">
    <div class="container">
      <div class="header-top-wrapper">
        <ul class="contact-list">
          <li>
            <i class="far fa-envelope"></i>
            <a href="#">info@example.com</a>
          </li>

        </ul>
        <p>
          Dapatkan konsultasi awal gratis untuk pendirian perusahaan Anda.
        </p>
        <ul class="list">

          <li>
            <i class="fa-regular fa-phone"></i>
            <a href="#">+208-6666-0112</a>
          </li>
          <li>
            <i class="fa-light fa-comments"></i><a href="#">Live Chat</a>
          </li>
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
                  src="{{ asset('buyer-file/assets/img/logo-removebg.png') }}"
                  alt="logo-img"
                  style="width: 13pc" />
              </a>
              <a href="{{ url('/') }}" class="header-logo-2">
                <img
                  src="{{ asset('buyer-file/assets/img/logo-remove-black.png') }}"
                  alt="logo-img"
                  style="width: 13pc" />
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
                        Beranda
                      </a>

                    </li>
                    <li class="has-dropdown active d-xl-none">
                      <a href="{{('/')}}" class="border-none">
                        Beranda
                      </a>

                    </li>
                    <li class="has-dropdown menu-thumb" id="mm-layanan-li">
                      <a href="#" id="mm-layanan-trigger" class="lw-mm-trigger" aria-expanded="false">
                        Layanan
                        <i class="fas fa-angle-down" id="mm-layanan-arrow" style="transition:transform 0.22s ease;"></i>
                      </a>

                      <!-- ===== MEGA MENU: LAYANAN ===== -->
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

                          .lw-col {
                            padding: 0 18px;
                            border-right: 1px solid #f3f4f6;
                          }

                          .lw-col:first-child {
                            padding-left: 0;
                          }

                          .lw-col:last-child {
                            border-right: none;
                          }

                          .lw-col-title {
                            font-size: 0.68rem;
                            font-weight: 800;
                            letter-spacing: 1.4px;
                            text-transform: uppercase;
                            color: #6b7280;
                            margin-bottom: 14px;
                            padding-bottom: 10px;
                            border-bottom: 2px solid #f3f4f6;
                            display: flex;
                            align-items: center;
                            gap: 7px;
                          }

                          .lw-col-title i {
                            color: #dc3545;
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
                            font-size: 0.84rem;
                            font-weight: 500;
                            color: #374151;
                            padding: 6px 8px 6px 0;
                            text-decoration: none;
                            border-radius: 6px;
                            transition: color 0.15s, background 0.15s;
                            line-height: 1.4;
                          }

                          .lw-col ul li a:hover {
                            color: #dc3545;
                            background: #fff5f6;
                            padding-left: 8px;
                          }

                          /* ---- Right: Sidebar ---- */
                          .lw-mega-sidebar {
                            width: 240px;
                            flex-shrink: 0;
                            background: #fafafa;
                            border-left: 1px solid #f0f0f0;
                            padding: 24px 18px;
                            display: flex;
                            flex-direction: column;
                            gap: 10px;
                          }

                          .lw-sidebar-label {
                            font-size: 0.65rem;
                            font-weight: 800;
                            letter-spacing: 1.2px;
                            text-transform: uppercase;
                            color: #9ca3af;
                            margin-bottom: 6px;
                          }

                          .lw-sidebar-card {
                            display: block;
                            background: #fff;
                            border: 1px solid #f0f0f0;
                            border-radius: 10px;
                            padding: 12px 13px;
                            text-decoration: none;
                            transition: box-shadow 0.18s, border-color 0.18s;
                          }

                          .lw-sidebar-card:hover {
                            box-shadow: 0 4px 16px rgba(220, 53, 69, 0.1);
                            border-color: #fca5a5;
                          }

                          .lw-sidebar-card-title {
                            font-size: 0.83rem;
                            font-weight: 700;
                            color: #111827;
                            margin-bottom: 2px;
                          }

                          .lw-sidebar-card-sub {
                            font-size: 0.72rem;
                            color: #9ca3af;
                          }

                          .lw-sidebar-card-price {
                            font-size: 0.78rem;
                            font-weight: 700;
                            color: #dc3545;
                            margin-top: 6px;
                          }

                          .lw-sidebar-cta {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            gap: 6px;
                            margin-top: 4px;
                            background: #dc3545;
                            color: #fff;
                            border-radius: 8px;
                            padding: 9px 14px;
                            font-size: 0.8rem;
                            font-weight: 700;
                            text-decoration: none;
                            transition: background 0.18s;
                          }

                          .lw-sidebar-cta:hover {
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

                            .lw-mega-wrap {
                              width: min(900px, 96vw);
                            }
                          }

                          @media (max-width: 991px) {
                            .lw-mega-cols {
                              grid-template-columns: repeat(2, 1fr);
                            }
                          }
                        </style>

                        <div class="container p-0">
                          <div class="lw-mega-body">

                            <!-- LEFT: 3 CATEGORY COLUMNS -->
                            <div class="lw-mega-cols">

                              <!-- Kolom 1: Pendirian Badan Usaha -->
                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-building"></i> Pendirian Badan Usaha
                                </div>
                                <ul>
                                  <li><a href="{{ url('/pendirian-pt-perorangan') }}">Pendirian PT Perorangan</a></li>
                                  <li><a href="{{ url('/pendirian-pt-<-1m') }}">Pendirian PT < 1M</a>
                                  </li>
                                  <li><a href="{{ url('/pendirian-pt->-1m') }}">Pendirian PT > 1M</a></li>
                                  <li><a href="{{ url('/pendirian-pt-pma') }}">Pendirian PT PMA</a></li>
                                  <li><a href="{{ url('/pendirian-cv') }}">Pendirian CV</a></li>
                                  <li><a href="{{ url('/pendirian-yayasan') }}">Pendirian Yayasan</a></li>
                                  <li><a href="{{ url('/pendirian-firma') }}">Pendirian Firma</a></li>
                                </ul>
                              </div>

                              <!-- Kolom 2: Perizinan & Hukum -->
                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-file-contract"></i> Perizinan &amp; Hukum
                                </div>
                                <ul>
                                  <li><a href="{{ url('/nib-dan-oss') }}">NIB &amp; OSS</a></li>
                                  <li><a href="{{ url('/haki') }}">HAKI / Pendaftaran Merek</a></li>
                                  <!-- <li><a href="{{ url('/perubahan-anggaran-dasar') }}">Perubahan Anggaran Dasar</a></li>
                                  <li><a href="{{ url('/penutupan-perusahaan') }}">Penutupan Perusahaan</a></li>
                                  <li><a href="{{ url('/sbu-sijuk') }}">SBU &amp; SIJUK</a></li> -->
                                  <li><a href="{{ url('/laporan-lkpm') }}">Laporan LKPM</a></li>
                                  <li><a href="{{ url('/sertifikat-iso') }}">Sertifikat ISO</a></li>
                                  <li><a href="{{ url('/surat-keterangan-tidak-pailit') }}" style="font-size: 14px !important;">Surat Keterangan Tidak Pailit</a></li>
                                  <li><a href="{{ url('/drafting-review-perjanjian-bisnis') }}">Drafting Perjanjian Bisnis</a></li>
                                </ul>
                                <div class="lw-col-title mt-3">
                                  <i class="fas fa-map-marker-alt"></i> Kantor &amp; Pendukung
                                </div>
                                <ul>
                                  <li><a href="{{ url('/virtual-office') }}">Virtual Office</a></li>
                                  <li><a href="{{ url('/sewa-meeting-room') }}">Sewa Meeting Room</a></li>
                                  <!-- <li><a href="{{ url('/layanan-visa-kitas') }}">Visa dan KITAS</a></li> -->
                                  <li><a href="{{ url('/layanan-konsultasi-bisnis') }}">Konsultasi Bisnis</a></li>
                                </ul>
                              </div>

                              <!-- Kolom 3: Pajak, Kantor & Layanan -->
                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-calculator"></i> Pajak &amp; Pembukuan
                                </div>
                                <ul>
                                  <li><a href="{{ url('/jasa-pembukuan-perpajakan') }}">Jasa Pembukuan & Perpajakan</a></li>
                                  <li><a href="{{ url('/layanan-payroll') }}">Layanan Payroll</a></li>
                                  <li><a href="{{ url('/pendaftaran-npwp') }}">Pendaftaran NPWP</a></li>
                                  <li><a href="{{ url('/pelaporan-spt-badan') }}">Pelaporan SPT Badan</a></li>
                                  <li><a href="{{ url('/pelaporan-spt-pribadi') }}">Pelaporan SPT Pribadi</a></li>
                                  <li><a href="{{ url('/audit-laporan-keuangan') }}">Audit Laporan Keuangan</a></li>
                                  <li><a href="{{ url('/pengurusan-pkp') }}">Pengurusan PKP</a></li>
                                </ul>

                              </div>

                            </div><!-- /lw-mega-cols -->

                            <!-- RIGHT: SIDEBAR -->
                            <div class="lw-mega-sidebar">
                              <div class="lw-sidebar-label">Paket Populer</div>

                              <a href="{{ url('/pendirian-pt') }}" class="lw-sidebar-card">
                                <div class="lw-sidebar-card-title">Pendirian PT Lengkap</div>
                                <div class="lw-sidebar-card-sub">Akta + NIB + NPWP Perusahaan</div>
                                <div class="lw-sidebar-card-price">mulai Rp 5.950.000</div>
                              </a>

                              <a href="{{ url('/virtual-office') }}" class="lw-sidebar-card">
                                <div class="lw-sidebar-card-title">Virtual Office</div>
                                <div class="lw-sidebar-card-sub">Alamat bisnis prestisius Jakarta</div>
                                <div class="lw-sidebar-card-price">mulai Rp 2.400.000 / thn</div>
                              </a>

                              <a href="{{ url('/haki') }}" class="lw-sidebar-card">
                                <div class="lw-sidebar-card-title">Pendaftaran Merek (HAKI)</div>
                                <div class="lw-sidebar-card-sub">Lindungi brand Anda sekarang</div>
                                <div class="lw-sidebar-card-price">mulai Rp 2.000.000</div>
                              </a>

                              <a href="#layanan-kami-section" class="lw-sidebar-cta">
                                Semua Layanan <i class="fas fa-arrow-right"></i>
                              </a>
                            </div><!-- /lw-mega-sidebar -->

                          </div><!-- /lw-mega-body -->
                        </div><!-- /.container p-0 -->
                      </div>
                      <!-- ===== END MEGA MENU ===== -->
                    </li>

                    <!-- ========== MOBILE LAYANAN MENU (SIAP PAKAI) ========== -->
                    <li class="has-dropdown d-xl-none">
                      <a href="#" class="mobile-main-link" id="mobileLayananToggle">Layanan</a>
                      <div class="mobile-menu-wrapper" id="mobileLayananMenu" style="display: none;">

                        <!-- Kategori 1 -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle">
                            <span>Pendirian Badan Usaha</span>
                          </a>
                          <div class="mobile-cat-body" style="display: none;">
                            <ul>
                              <li><a href="{{ url('/pendirian-pt-perorangan') }}">Pendirian PT Perorangan</a></li>
                              <li><a href="{{ url('/pendirian-pt') }}">Pendirian PT</a></li>
                              <li><a href="{{ url('/pendirian-pt-pma') }}">Pendirian PT PMA</a></li>
                              <li><a href="{{ url('/pendirian-cv') }}">Pendirian CV</a></li>
                              <li><a href="{{ url('/pendirian-yayasan') }}">Pendirian Yayasan</a></li>
                              <li><a href="{{ url('/pendirian-firma') }}">Pendirian Firma</a></li>
                            </ul>
                          </div>
                        </div>

                        <!-- Kategori 2 -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle">
                            <span>Perizinan & Hukum</span>
                          </a>
                          <div class="mobile-cat-body" style="display: none;">
                            <ul>
                              <li><a href="{{ url('/nib-dan-oss') }}">NIB & OSS</a></li>
                              <li><a href="{{ url('/haki') }}">HAKI / Pendaftaran Merek</a></li>
                              <li><a href="{{ url('/laporan-lkpm') }}">Laporan LKPM</a></li>
                              <li><a href="{{ url('/sertifikat-iso') }}">Sertifikat ISO</a></li>
                              <li><a href="{{ url('/surat-keterangan-tidak-pailit') }}">Surat Keterangan Tidak Pailit</a></li>
                              <li><a href="{{ url('/drafting-review-perjanjian-bisnis') }}">Drafting Perjanjian Bisnis</a></li>
                            </ul>
                          </div>
                        </div>

                        <!-- Kategori 3 -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle">
                            <span>Pajak & Pembukuan</span>
                          </a>
                          <div class="mobile-cat-body" style="display: none;">
                            <ul>
                              <li><a href="{{ url('/jasa-pembukuan-perpajakan') }}">Jasa Pembukuan & Perpajakan</a></li>
                              <li><a href="{{ url('/layanan-payroll') }}">Layanan Payroll</a></li>
                              <li><a href="{{ url('/pendaftaran-npwp') }}">Pendaftaran NPWP</a></li>
                              <li><a href="{{ url('/pelaporan-spt-badan') }}">Pelaporan SPT Badan</a></li>
                              <li><a href="{{ url('/pelaporan-spt-pribadi') }}">Pelaporan SPT Pribadi</a></li>
                              <li><a href="{{ url('/audit-laporan-keuangan') }}">Audit Laporan Keuangan</a></li>
                              <li><a href="{{ url('/pengurusan-pkp') }}">Pengurusan PKP</a></li>
                            </ul>
                          </div>
                        </div>

                        <!-- Kategori 4 -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle">
                            <span>Kantor & Pendukung</span>
                          </a>
                          <div class="mobile-cat-body" style="display: none;">
                            <ul>
                              <li><a href="{{ url('/virtual-office') }}">Virtual Office</a></li>
                              <li><a href="{{ url('/sewa-meeting-room') }}">Sewa Meeting Room</a></li>
                              <li><a href="{{ url('/layanan-konsultasi-bisnis') }}">Konsultasi Bisnis</a></li>
                            </ul>
                          </div>
                        </div>

                      </div>
                    </li>

                    <!-- STYLE KHUSUS MENU MOBILE -->
                    <style>
                      /* === RESET LIST STYLE === */
                      .has-dropdown {
                        list-style: none;
                      }

                      /* === TOMBOL UTAMA "LAYANAN" === */
                      .mobile-main-link {
                        display: block;
                        padding: 14px 20px;
                        background: #ffffff;
                        color: #333333;
                        text-decoration: none;
                        font-weight: 600;
                        font-size: 16px;
                        border-bottom: 1px solid #e9ecef;
                        transition: background 0.2s, color 0.2s;
                        position: relative;
                        cursor: pointer;
                      }

                      /* Tanda + default (sebelum dibuka) */
                      .mobile-main-link::after {
                        content: "+";
                        position: absolute;
                        right: 20px;
                        top: 50%;
                        transform: translateY(-50%);
                        font-size: 22px;
                        font-weight: 400;
                        color: #6c757d;
                        transition: transform 0.2s;
                      }

                      /* Saat menu utama terbuka (class active) */
                      .mobile-main-link.active {
                        background-color: #dc3545 !important;
                        color: #ffffff !important;
                      }

                      .mobile-main-link.active::after {
                        content: "−";
                        color: #ffffff;
                      }

                      /* === WRAPPER MENU UTAMA === */
                      .mobile-menu-wrapper {
                        background: #ffffff;
                        border-bottom: 1px solid #dee2e6;
                      }

                      /* === ITEM KATEGORI === */
                      .mobile-cat-item {
                        border-top: 1px solid #e9ecef;
                      }

                      .mobile-cat-toggle {
                        display: block;
                        padding: 12px 20px;
                        background-color: #f8f9fa;
                        color: #212529;
                        text-decoration: none;
                        font-weight: 500;
                        font-size: 15px;
                        position: relative;
                        cursor: pointer;
                        transition: background 0.2s, color 0.2s;
                      }

                      /* Tanda panah bawah untuk kategori */
                      .mobile-cat-toggle::after {
                        content: "▼";
                        position: absolute;
                        right: 20px;
                        top: 50%;
                        transform: translateY(-50%);
                        font-size: 12px;
                        color: #6c757d;
                        transition: transform 0.3s;
                      }

                      /* Saat kategori terbuka */
                      .mobile-cat-toggle.active {
                        background-color: #fceaea;
                        color: #dc3545;
                      }

                      .mobile-cat-toggle.active::after {
                        transform: translateY(-50%) rotate(180deg);
                        color: #dc3545;
                      }

                      /* === BODY SUBMENU (LEVEL 3) === */
                      .mobile-cat-body {
                        background: #ffffff;
                      }

                      .mobile-cat-body ul {
                        list-style: none;
                        margin: 0;
                        padding: 0;
                      }

                      .mobile-cat-body li {
                        border-bottom: 1px solid #f1f3f5;
                      }

                      .mobile-cat-body li:last-child {
                        border-bottom: none;
                      }

                      .mobile-cat-body li a {
                        display: block;
                        padding: 12px 20px 12px 35px;
                        /* indentasi kiri */
                        color: #495057;
                        text-decoration: none;
                        font-size: 14px;
                        transition: background 0.15s, color 0.15s;
                      }

                      .mobile-cat-body li a:hover {
                        background: #fff5f6;
                        color: #dc3545;
                      }
                    </style>

                    <!-- JAVASCRIPT MENU (TANPA BOOTSTRAP) -->
                    <script>
                      (function() {
                        function initMobileMenu() {
                          const mainToggle = document.getElementById('mobileLayananToggle');
                          const mainMenu = document.getElementById('mobileLayananMenu');

                          if (!mainToggle || !mainMenu) {
                            console.warn('Elemen mobileLayananToggle atau mobileLayananMenu tidak ditemukan.');
                            return;
                          }

                          // --- Toggle Menu Utama "Layanan" ---
                          mainToggle.addEventListener('click', function(e) {
                            e.preventDefault();
                            const isHidden = (mainMenu.style.display === 'none' || mainMenu.style.display === '');

                            if (isHidden) {
                              mainMenu.style.display = 'block';
                              this.classList.add('active');
                            } else {
                              mainMenu.style.display = 'none';
                              this.classList.remove('active');
                              // Tutup semua subkategori saat menu utama ditutup
                              document.querySelectorAll('.mobile-cat-body').forEach(body => body.style.display = 'none');
                              document.querySelectorAll('.mobile-cat-toggle').forEach(toggle => toggle.classList.remove('active'));
                            }
                          });

                          // --- Toggle Kategori (Akordeon) ---
                          const catToggles = document.querySelectorAll('.mobile-cat-toggle');

                          catToggles.forEach(toggle => {
                            toggle.addEventListener('click', function(e) {
                              e.preventDefault();

                              const catBody = this.nextElementSibling;
                              if (!catBody || !catBody.classList.contains('mobile-cat-body')) return;

                              const isActive = this.classList.contains('active');

                              // Tutup semua kategori lain (akordeon)
                              document.querySelectorAll('.mobile-cat-body').forEach(body => {
                                if (body !== catBody) body.style.display = 'none';
                              });
                              document.querySelectorAll('.mobile-cat-toggle').forEach(t => {
                                if (t !== this) t.classList.remove('active');
                              });

                              // Toggle kategori saat ini
                              if (!isActive) {
                                catBody.style.display = 'block';
                                this.classList.add('active');
                              } else {
                                catBody.style.display = 'none';
                                this.classList.remove('active');
                              }
                            });
                          });
                        }

                        // Jalankan setelah DOM siap
                        if (document.readyState === 'loading') {
                          document.addEventListener('DOMContentLoaded', initMobileMenu);
                        } else {
                          initMobileMenu();
                        }
                      })();
                    </script>
                    <!-- ========== END MOBILE LAYANAN ========== -->

                    <li class="has-dropdown menu-thumb" id="mm-pelatihan-li">
                      <a href="#" id="mm-pelatihan-trigger" class="lw-mm-trigger" aria-expanded="false">
                        Pusat Pelatihan
                        <i class="fas fa-angle-down" id="mm-pelatihan-arrow" style="transition:transform 0.22s ease;"></i>
                      </a>

                      <!-- ===== COMPACT CARD MEGA MENU: Pusat Pelatihan ===== -->
                      <div class="lw-pelatihan-wrap" id="lwMegaPelatihan" role="navigation" aria-label="Pusat Pelatihan Menu">
                        <style>
                          /* ── Pusat Pelatihan: Wrapper ── */
                          #mm-pelatihan-li {
                            position: static;
                          }

                          .lw-pelatihan-wrap {
                            display: none;
                            position: absolute;
                            top: calc(100% + 10px);
                            left: 50%;
                            transform: translateX(-50%);
                            z-index: 99999;
                            opacity: 0;
                            transition: opacity 0.18s ease, transform 0.18s ease;
                            pointer-events: none;
                            transform: translateX(-50%) translateY(8px);
                          }

                          .lw-pelatihan-wrap.lw-mm-open {
                            display: block !important;
                            opacity: 1;
                            transform: translateX(-50%) translateY(0) !important;
                            pointer-events: auto;
                          }

                          /* ── Card Box ── */
                          .lw-pelatihan-box {
                            width: 480px;
                            background: #fff;
                            border-radius: 16px;
                            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.14), 0 4px 16px rgba(0, 0, 0, 0.06);
                            border: 1px solid #f0f0f0;
                            padding: 18px;
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 10px;
                          }

                          /* ── Card Header Label ── */
                          .lw-pelatihan-header {
                            grid-column: 1 / -1;
                            font-size: 0.63rem;
                            font-weight: 800;
                            letter-spacing: 1.3px;
                            text-transform: uppercase;
                            color: #9ca3af;
                            padding-bottom: 10px;
                            border-bottom: 1px solid #f3f4f6;
                            margin-bottom: 2px;
                          }

                          /* ── Individual Card Item ── */
                          .lw-pelatihan-item {
                            display: flex;
                            align-items: flex-start;
                            gap: 11px;
                            padding: 11px 12px;
                            border-radius: 10px;
                            text-decoration: none !important;
                            transition: background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
                            border: 1px solid transparent;
                          }

                          .lw-pelatihan-item:hover {
                            background: #fff5f6 !important;
                            border-color: #fde8ea;
                            transform: translateY(-2px);
                            box-shadow: 0 4px 14px rgba(220, 53, 69, 0.09);
                          }

                          /* ── Icon Badge ── */
                          .lw-pelatihan-icon {
                            width: 38px;
                            height: 38px;
                            border-radius: 9px;
                            background: #fff5f6;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 18px;
                            flex-shrink: 0;
                            transition: background 0.18s ease;
                          }

                          .lw-pelatihan-item:hover .lw-pelatihan-icon {
                            background: #fde8ea;
                          }

                          /* ── Text Content ── */
                          .lw-pelatihan-text h6 {
                            font-size: 1rem;
                            font-weight: 700;
                            color: #111827;
                            margin: 0 0 2px 0;
                            line-height: 1.3;
                            transition: color 0.15s ease;
                          }

                          .lw-pelatihan-item:hover .lw-pelatihan-text h6 {
                            color: #dc3545;
                          }

                          .lw-pelatihan-text p {
                            font-size: 0.8rem;
                            color: #9ca3af;
                            margin: 0;
                            line-height: 1.4;
                          }

                          /* ── Arrow rotate on open ── */
                          #mm-pelatihan-trigger[aria-expanded="true"] #mm-pelatihan-arrow {
                            transform: rotate(180deg);
                          }

                          #mm-pelatihan-arrow {
                            display: inline-block;
                            transition: transform 0.22s ease;
                          }
                        </style>

                        <div class="lw-pelatihan-box">
                          <!-- Label Header -->
                          <div class="lw-pelatihan-header">Pusat Pelatihan & Informasi</div>

                          <!-- Item 1: Pelatihan & Seminar -->
                          <a href="{{ url('#') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">📘</div>
                            <div class="lw-pelatihan-text">
                              <h6>Pelatihan & Seminar</h6>
                              <p>Program training bisnis & legal</p>
                            </div>
                          </a>

                          <!-- Item 2: Artikel -->
                          <a href="{{ url('/berita') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">📄</div>
                            <div class="lw-pelatihan-text">
                              <h6>Artikel</h6>
                              <p>Insight hukum & bisnis terkini</p>
                            </div>
                          </a>

                          <!-- Item 3: Kumpulan Peraturan -->
                          <a href="{{ route('peraturan.index') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">⚖️</div>
                            <div class="lw-pelatihan-text">
                              <h6>Kumpulan Peraturan</h6>
                              <p>Database regulasi terbaru</p>
                            </div>
                          </a>

                          <!-- Item 4: Promo -->
                          <a href="{{ url('promo') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">🔥</div>
                            <div class="lw-pelatihan-text">
                              <h6>Promo</h6>
                              <p>Penawaran spesial Lawgika</p>
                            </div>
                          </a>

                          <!-- Item 5: Karir -->
                          <a href="{{ url('#') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">💼</div>
                            <div class="lw-pelatihan-text">
                              <h6>Karir</h6>
                              <p>Gabung bersama tim kami</p>
                            </div>
                          </a>

                          <!-- Item 6: Tentang Kami -->
                          <a href="{{ url('tentang-kami') }}" class="lw-pelatihan-item">
                            <div class="lw-pelatihan-icon">🏢</div>
                            <div class="lw-pelatihan-text">
                              <h6>Tentang Kami</h6>
                              <p>Profil & visi Lawgika</p>
                            </div>
                          </a>

                        </div><!-- /lw-pelatihan-box -->
                      </div>
                      <!-- ===== END COMPACT CARD MEGA MENU ===== -->
                    </li>

                    <!-- Mobile Layanan Accordion (d-xl-none only) -->
                    <li class="d-xl-none">
                      <a href="#">
                        Pusat Pelatihan
                        <i class="fas fa-angle-down"></i>
                      </a>
                      <ul class="submenu" style="list-style: none; padding: 0; margin: 0;">
                        <li><a href="{{ url('#') }}" style="display: block; padding: 12px 20px; border-bottom: 1px solid #eee;">📘 Pelatihan & Seminar</a></li>
                        <li><a href="{{ url('/berita') }}" style="display: block; padding: 12px 20px; border-bottom: 1px solid #eee;">📄 Artikel</a></li>
                        <li><a href="{{ route('peraturan.index') }}" style="display: block; padding: 12px 20px; border-bottom: 1px solid #eee;">⚖️ Kumpulan Peraturan</a></li>
                        <li><a href="{{ url('promo') }}" style="display: block; padding: 12px 20px; border-bottom: 1px solid #eee;">🔥 Promo</a></li>
                        <li><a href="{{ url('#') }}" style="display: block; padding: 12px 20px; border-bottom: 1px solid #eee;">💼 Karir</a></li>
                        <li><a href="{{ url('tentang-kami') }}" style="display: block; padding: 12px 20px;">🏢 Tentang Kami</a></li>
                      </ul>
                    </li>


                    <li>
                      <a href="{{ url('tentang-kami') }}">Tentang Kami</a>
                    </li>

                    <li>
                      <a href="#">
                        Klien
                        <i class="fas fa-angle-down"></i>
                      </a>
                      <ul class="submenu">
                        <li><a href="#">Faq</a></li>
                        <li><a href="#">Error 404</a></li>
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Contact Us</a></li>
                      </ul>
                    </li>
                  </ul>
                </nav>
              </div>
            </div>
            <a href="#" class="search-trigger search-icon"><i class="fal fa-search"></i></a>
            <div class="header__hamburger d-xl-block my-auto">
              <div class="sidebar__toggle">
                <i class="fas fa-bars"></i>
              </div>
            </div>
            <div class="header-button">
              @guest
              <!-- Menampilkan tombol 'Masuk' jika belum login -->
              <a href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Masuk
              </a>
              @endguest

              @auth
              <!-- Menampilkan dropdown untuk user yang sudah login -->
              <div class="dropdown">
                <button class="theme-btn dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                  Profile
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <!-- Dashboard link -->
                  @if(Auth::user()->role === 'admin')
                  <li><a class="dropdown-item" href="/admin/dashboard">Dashboard</a></li>
                  @else
                  <li><a class="dropdown-item" href="/dashboard">Dashboard</a></li>
                  @endif

                  <!-- Logout link -->
                  <li>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                      @csrf
                      <button type="submit" class="dropdown-item">Keluar</button>
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
    color: #ffffff !important;
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
    background-color: #dc3545 !important;
    color: #fff !important;
    border: none !important;
  }

  .header-1 .header-button .theme-btn:hover {
    background-color: #b91c1c !important;
  }

  /* Pastikan dropdown / mega menu textnya tetap gelap */
  .header-1 .header-main .main-menu ul li ul.submenu a,
  .header-1 .header-main .main-menu ul li .lw-mega-body a {
    color: #111827 !important;
  }

  .header-1 .header-main .main-menu ul li ul.submenu a:hover,
  .header-1 .header-main .main-menu ul li .lw-mega-body a:hover {
    color: #dc3545 !important;
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
    color: #dc3545 !important;
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
  #mm-layanan-arrow {
    display: inline-block;
  }

  .lw-mm-trigger[aria-expanded="true"] #mm-layanan-arrow,
  .lw-mm-trigger.active #mm-layanan-arrow {
    transform: rotate(180deg);
  }
</style>

<script>
  (function() {
    /* Tunggu DOM siap */
    function initMegaMenu() {
      var trigger = document.getElementById('mm-layanan-trigger');
      var menu = document.getElementById('lwMegaMenu');
      var arrow = document.getElementById('mm-layanan-arrow');
      if (!trigger || !menu) return;

      var isOpen = false;

      /* ── Toggle buka/tutup ── */
      function openMenu() {
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

      /* ── Klik pada trigger ── */
      trigger.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        isOpen ? closeMenu() : openMenu();
      });

      /* ── Klik di dalam menu tidak menutup ── */
      menu.addEventListener('click', function(e) {
        e.stopPropagation();
      });

      /* ── Klik di luar → tutup ── */
      document.addEventListener('click', function() {
        if (isOpen) closeMenu();
      });

      /* ── Escape key → tutup ── */
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
        initMegaMenu();
        initNavbarScroll();
      });
    } else {
      initMegaMenu();
      initNavbarScroll();
    }
  })();

  /* ── Pusat Pelatihan Mega Menu (handler terpisah, tidak berpengaruh ke Layanan) ── */
  (function() {
    function initPelatihanMenu() {
      var pelatihanTrigger = document.getElementById('mm-pelatihan-trigger');
      var pelatihanMenu = document.getElementById('lwMegaPelatihan');

      var pelatihanOpen = false;

      if (pelatihanTrigger && pelatihanMenu) {

        pelatihanTrigger.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          pelatihanOpen = !pelatihanOpen;

          if (pelatihanOpen) {
            pelatihanMenu.classList.add('lw-mm-open');
            pelatihanTrigger.setAttribute('aria-expanded', 'true');
          } else {
            pelatihanMenu.classList.remove('lw-mm-open');
            pelatihanTrigger.setAttribute('aria-expanded', 'false');
          }
        });

        document.addEventListener('click', function(e) {
          if (!pelatihanMenu.contains(e.target) && !pelatihanTrigger.contains(e.target)) {
            pelatihanMenu.classList.remove('lw-mm-open');
            pelatihanTrigger.setAttribute('aria-expanded', 'false');
            pelatihanOpen = false;
          }
        });

      }
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initPelatihanMenu);
    } else {
      initPelatihanMenu();
    }
  })();

  /* ── Auto-buka modal login jika ada error ── */
  function lwAutoOpenLoginModal() {
    var hasError = document.getElementById('lw-login-error-box');
    if (!hasError) return;
    var modalEl = document.getElementById('exampleModal');
    if (!modalEl) return;
    setTimeout(function() {
      if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var m = bootstrap.Modal.getOrCreateInstance(modalEl);
        m.show();
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

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      lwAutoOpenLoginModal();
      lwAutoDismissToasts();
    });
  } else {
    lwAutoOpenLoginModal();
    lwAutoDismissToasts();
  }
</script>

{{-- ===== TOAST NOTIFIKASI LOGIN GAGAL ===== --}}
@if ($errors->any() && old('_token'))
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
    box-shadow: 0 8px 32px rgba(220, 53, 69, 0.18), 0 2px 8px rgba(0, 0, 0, 0.08);
    border-left: 5px solid #dc3545;
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
    color: #dc3545;
    font-size: 1.1rem;
  }

  .lw-toast-body {
    flex: 1;
  }

  .lw-toast-title {
    font-size: 0.88rem;
    font-weight: 700;
    color: #dc3545;
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
    color: #dc3545;
  }

  .lw-toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: #dc3545;
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


<!-- Modal Version 1 -->
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
            <h2>welcome back!</h2>

            {{-- Inline error di dalam modal --}}
            @if ($errors->any())
            <div id="lw-login-error-box" style="background:#fff0f1;border:1.5px solid #fca5a5;border-radius:10px;padding:11px 15px;margin-bottom:14px;display:flex;align-items:flex-start;gap:10px;">
              <i class="fas fa-exclamation-circle" style="color:#dc3545;margin-top:2px;flex-shrink:0;"></i>
              <div>
                @foreach ($errors->all() as $error)
                <p style="margin:0;font-size:0.82rem;color:#b91c1c;font-weight:500;line-height:1.5;">{{ $error }}</p>
                @endforeach
              </div>
            </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="login-from">
              @csrf
              <div class="form-grp cmn-mb">
                <input type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" />
              </div>
              <div class="form-grp">
                <input type="password" name="password" placeholder="Enter Password" value="" />
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
                  <label class="form-check-label" for="flexCheckChecked">
                    Remember me
                  </label>
                </div>
                @if (Route::has('password.request'))
                <a class="forgot btn btn-link"
                  data-bs-toggle="modal"
                  data-bs-target="#exampleModal3">
                  Forgot Your Password?
                </a>
                @endif
              </div>
              <button type="submit" class="theme-btn w-100">
                <span> Log in </span>
              </button>
            </form>
            <span class="orting-badge"> Or </span>
            <div
              class="form-check d-flex align-items-center gap-2 from-customradio">
              <input
                class="form-check-input"
                type="radio"
                name="flexRadioDefault"
                id="flexRadioDefault1" />
              <label class="form-check-label" for="flexRadioDefault1">
                i accept your terms & conditions
              </label>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
          <img src="{{ asset('buyer-file/assets/img/sign/login.png') }}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              id="btn-go-register"
              class="theme-create style-border">
              create account
            </button>
            <button
              id="btn-stay-login"
              class="theme-btn">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Version 2 -->
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
            <h2>Create account</h2>
            <form action="{{ route('register') }}" method="POST" id="register-form" class="login-from">
              @csrf
              <div class="form-grp cmn-mb">
                <input type="text" name="name" placeholder="User name" />
              </div>
              <div class="form-grp cmn-mb">
                <input type="email" name="email" placeholder="Email Address" />
              </div>
              <div class="form-grp cmn-mb">
                <input type="password" name="password" placeholder="Enter Password" />
              </div>
              <div class="form-grp">
                <input type="password" name="password_confirmation" placeholder="Enter Confirm password" />
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
                <label class="form-check-label" for="flexRadioDefault11">
                  i accept your terms & conditions
                </label>
              </div>
            </div>
            <div class="mt-4">
              <button type="submit" form="register-form" class="theme-btn w-100">
                <span> Log in </span>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
          <img src="{{ asset('buyer-file/assets/img/sign/create.png') }}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              id="btn-stay-register"
              class="theme-create style-border">
              create account
            </button>
            <button
              id="btn-go-login"
              class="theme-btn">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Version 3 -->
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
            <h2>Forgot Password</h2>
            <form action="{{ route('password.email') }}" method="POST" id="forgot-password-form" class="login-from">
              @csrf
              <div class="form-grp cmn-mb">
                <input type="email" name="email" placeholder="Email Address" />
              </div>
            </form>

            <div class="mt-4">
              <button type="submit" form="forgot-password-form" class="theme-btn w-100">
                <span> Send Password Reset Link </span>
              </button>
            </div>
          </div>
        </div>
        <div class="modal-right-thumb position-relative">
          <img src="{{ asset('buyer-file/assets/img/sign/create.png') }}" alt="img" />
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


<!-- Modal Switcher Script -->
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

<!-- Search Area Start -->

<div class="search-wrap">
  <div class="search-inner">
    <i class="fas fa-times search-close" id="search-close"></i>
    <div class="search-cell">
      <form method="get">
        <div class="search-field-holder">
          <input
            type="search"
            class="main-search-input"
            placeholder="Search..." />
        </div>
      </form>
    </div>
  </div>
</div>