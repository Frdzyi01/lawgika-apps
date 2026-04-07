<!-- Offcanvas Area Start -->
<div class="fix-area">
  <div class="offcanvas__info">
    <div class="offcanvas__wrapper">
      <div class="offcanvas__content">
        <div
          class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
          <div class="offcanvas__logo">
            <a href="{{('/')}}">
              <img src="{{('buyer-file/assets/img/logo-remove-black.png')}}" alt="logo-img" />
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
            <a href="#" class="theme-btn text-center">
              Get A Quote <i class="fa-solid fa-arrow-right-long"></i>
            </a>
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
          <li>
            <i class="fa-regular fa-phone"></i>
            <a href="#">+208-6666-0112</a>
          </li>
        </ul>
        <p>
          Dapatkan konsultasi awal gratis untuk pendirian perusahaan Anda.
        </p>
        <ul class="list">
          <li>
            <i class="fa-light fa-comments"></i><a href="#">Live Chat</a>
          </li>
          <li>
            <i class="fa-light fa-user"></i>
            <button data-bs-toggle="modal" data-bs-target="#exampleModal">
              Masuk
            </button>
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
              <a href="{{('/')}}" class="header-logo">
                <img
                  src="{{('buyer-file/assets/img/logo-removebg.png')}}"
                  alt="logo-img"
                  style="width: 13pc" />
              </a>
              <a href="{{('/')}}" class="header-logo-2">
                <img
                  src="{{('buyer-file/assets/img/logo-remove-black.png')}}"
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
                                  <li><a href="{{ url('/pendirian-pt') }}">Pendirian PT</a></li>
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
                                  <li><a href="{{ url('/perubahan-anggaran-dasar') }}">Perubahan Anggaran Dasar</a></li>
                                  <li><a href="{{ url('/penutupan-perusahaan') }}">Penutupan Perusahaan</a></li>
                                  <li><a href="{{ url('/sbu-sijuk') }}">SBU &amp; SIJUK</a></li>
                                  <li><a href="{{ url('/laporan-lkpm') }}">Laporan LKPM</a></li>
                                  <li><a href="{{ url('/sertifikat-iso') }}">Sertifikat ISO</a></li>
                                  <li><a href="{{ url('/drafting-review-perjanjian-bisnis') }}">Drafting Perjanjian Bisnis</a></li>
                                </ul>
                              </div>

                              <!-- Kolom 3: Pajak, Kantor & Layanan -->
                              <div class="lw-col">
                                <div class="lw-col-title">
                                  <i class="fas fa-calculator"></i> Pajak &amp; Pembukuan
                                </div>
                                <ul>
                                  <li><a href="{{ url('/langganan-jasa-pembukuan') }}">Jasa Pembukuan</a></li>
                                  <li><a href="{{ url('/langganan-jasa-perpajakan') }}">Jasa Perpajakan</a></li>
                                  <li><a href="{{ url('/layanan-payroll') }}">Payroll</a></li>
                                  <li><a href="{{ url('/pendaftaran-npwp') }}">Pendaftaran NPWP</a></li>
                                  <li><a href="{{ url('/pelaporan-spt-badan') }}">Pelaporan SPT Badan</a></li>
                                  <li><a href="{{ url('/audit-laporan-keuangan') }}">Audit Laporan Keuangan</a></li>
                                </ul>
                                <div class="lw-col-title mt-3">
                                  <i class="fas fa-map-marker-alt"></i> Kantor &amp; Pendukung
                                </div>
                                <ul>
                                  <li><a href="{{ url('/virtual-office') }}">Virtual Office</a></li>
                                  <li><a href="{{ url('/sewa-meeting-room') }}">Sewa Meeting Room</a></li>
                                  <li><a href="{{ url('/layanan-visa-kitas') }}">Visa dan KITAS</a></li>
                                  <li><a href="{{ url('/layanan-konsultasi-bisnis') }}">Konsultasi Bisnis</a></li>
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

                    <!-- Mobile Layanan Accordion (d-xl-none only) -->
                    <li class="has-dropdown active d-xl-none">
                      <a href="#" class="border-none" data-bs-toggle="collapse" data-bs-target="#mobileLayananBisnis" aria-expanded="false">
                        Layanan
                        <i class="fas fa-angle-down"></i>
                      </a>
                      <div class="collapse mobile-accordion-menu" id="mobileLayananBisnis">
                        <!-- Kategori 1: Pendirian Badan Usaha -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle" data-bs-toggle="collapse" data-bs-target="#mobCat1" aria-expanded="false">
                            Pendirian Badan Usaha
                            <i class="fas fa-chevron-down mobile-cat-arrow"></i>
                          </a>
                          <div class="collapse mobile-cat-body" id="mobCat1">
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
                        <!-- Kategori 2: Perizinan & Dokumen Hukum -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle" data-bs-toggle="collapse" data-bs-target="#mobCat2" aria-expanded="false">
                            Perizinan &amp; Dokumen Hukum
                            <i class="fas fa-chevron-down mobile-cat-arrow"></i>
                          </a>
                          <div class="collapse mobile-cat-body" id="mobCat2">
                            <ul>
                              <li><a href="#">Perubahan Anggaran Dasar</a></li>
                              <li><a href="#">Penutupan Perusahaan</a></li>
                              <li><a href="#">NIB & OSS</a></li>
                              <li><a href="#">Pendaftaran TDPSE</a></li>
                              <li><a href="#">SBU & SIJUK</a></li>
                              <li><a href="#">Laporan LKPM</a></li>
                              <li><a href="#">HAKI</a></li>
                              <li><a href="#">Sertifikat ISO</a></li>
                              <li><a href="#">Surat Keterangan Tidak Pailit</a></li>
                              <li><a href="#">Drafting & Review Perjanjian Bisnis</a></li>
                            </ul>
                          </div>
                        </div>
                        <!-- Kategori 3: Pembukuan & Pajak -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle" data-bs-toggle="collapse" data-bs-target="#mobCat3" aria-expanded="false">
                            Pembukuan & Pajak
                            <i class="fas fa-chevron-down mobile-cat-arrow"></i>
                          </a>
                          <div class="collapse mobile-cat-body" id="mobCat3">
                            <ul>
                              <li><a href="#">Langganan Jasa Pembukuan</a></li>
                              <li><a href="#">Langganan Jasa Perpajakan</a></li>
                              <li><a href="#">Layanan Payroll</a></li>
                              <li><a href="#">Point of Sales F&B</a></li>
                              <li><a href="#">Audit Laporan Keuangan</a></li>
                              <li><a href="#">Pengurusan PKP</a></li>
                              <li><a href="#">Pelaporan SPT Badan</a></li>
                              <li><a href="#">Pelaporan SPT Pribadi</a></li>
                              <li><a href="#">Pendaftaran NPWP</a></li>
                              <li><a href="#">Audit Pajak</a></li>
                            </ul>
                          </div>
                        </div>
                        <!-- Kategori 4: Layanan Pendukung Bisnis -->
                        <div class="mobile-cat-item">
                          <a href="#" class="mobile-cat-toggle" data-bs-toggle="collapse" data-bs-target="#mobCat4" aria-expanded="false">
                            Layanan Pendukung Bisnis
                            <i class="fas fa-chevron-down mobile-cat-arrow"></i>
                          </a>
                          <div class="collapse mobile-cat-body" id="mobCat4">
                            <ul>
                              <li><a href="#">Sewa Meeting Room</a></li>
                              <li><a href="#">Sewa Ruang Podcast</a></li>
                              <li><a href="#">Layanan Visa & KITAS</a></li>
                              <li><a href="#">Layanan Call Answering</a></li>
                              <li><a href="#">Layanan Konsultasi Bisnis</a></li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </li>
                    <li class="has-dropdown menu-thumb">
                      <a href="#">
                        Pusat Pelatihan
                        <i class="fas fa-angle-down"></i>
                      </a>
                      <ul class="submenu has-homemenu has-menu-hosting">
                        <li class="border-none">
                          <div class="homemenu-items">
                            <div class="row">
                              <div class="col-lg-6">
                                <div class="homemenu-list">
                                  <div class="icon">
                                    <img
                                      src="{{('buyer-file/assets/img/menu-icon/share-host.png')}}"
                                      alt="img" />
                                  </div>
                                  <div class="content">
                                    <h6>
                                      <a href="#">Pelatihan & Seminar</a>
                                    </h6>
                                    <p>Berisikan kumpulan peraturan yang dapat di download </p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <div class="homemenu-list">
                                  <div class="icon">
                                    <img
                                      src="{{('buyer-file/assets/img/menu-icon/reseller-shost.png')}}"
                                      alt="img" />
                                  </div>
                                  <div class="content">
                                    <h6>
                                      <a href="#">Artikel & Jurnal</a>
                                    </h6>
                                    <p>Berisikan kumpulan peraturan
                                      yang dapat di download </p>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-6">
                                <div class="homemenu-list">
                                  <div class="icon">
                                    <img
                                      src="{{('buyer-file/assets/img/menu-icon/reseller-shost.png')}}"
                                      alt="img" />
                                  </div>
                                  <div class="content">
                                    <h6>
                                      <a href="#">Kumpulan Peraturan</a>
                                    </h6>
                                    <p>Berisikan kumpulan peraturan yang dapat di download</p>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </li>
                      </ul>
                    </li>
                    <li class="has-dropdown active d-xl-none">
                      <a href="#" class="border-none">
                        Pusat Pelatihan
                        <i class="fas fa-angle-down"></i>
                      </a>
                      <ul class="submenu">
                        <li>
                          <a href="#">Pelatihan & Seminar</a>
                        </li>

                        <li>
                          <a href="#">Artikel & Jurnal</a>
                        </li>
                        <li>
                          <a href="#">Kumpulan Peraturan</a>
                        </li>
                      </ul>
                    </li>
                    <li>
                      <a href="#">Tentang Kami</a>
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
              <a href="#" class="theme-btn" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Masuk
                <i class="fa-solid fa-arrow-right-long"></i>
              </a>
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
</script>


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
            <form action="{{ route('login') }}" method="POST" class="login-from">
              @csrf
              <div class="form-grp cmn-mb">
                <input type="email" name="email" placeholder="Email Address" />
              </div>
              <div class="form-grp">
                <input type="password" name="password" placeholder="Enter Password" />
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
          <img src="{{('buyer-file/assets/img/sign/login.png')}}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              class="theme-create style-border"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal">
              create account
            </button>
            <button
              class="theme-btn"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal2">
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
          <img src="{{('buyer-file/assets/img/sign/create.png')}}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              class="theme-create style-border"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal">
              create account
            </button>
            <button
              class="theme-btn"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal2">
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
          <img src="{{('buyer-file/assets/img/sign/create.png')}}" alt="img" />
          <div class="signlogin-btnwrap">
            <button
              class="theme-create style-border"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal">
              create account
            </button>
            <button
              class="theme-btn"
              data-bs-toggle="modal"
              data-bs-target="#exampleModal2">
              Log In
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


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