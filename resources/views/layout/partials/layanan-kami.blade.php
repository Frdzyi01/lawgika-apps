<style>
  /* ==============================
       LAYANAN KAMI – Custom Styles
    ============================== */
  #layanan-kami-section {
    background-color: #fff;
    padding: 72px 0 80px;
  }

  /* ------- Section Title ------- */
  .layanan-section-title {
    font-size: 2rem;
    font-weight: 700;
    color: #111827;
    letter-spacing: -0.3px;
    margin-bottom: 0;
  }

  /* ------- Tab Nav ------- */
  .layanan-nav {
    border-bottom: 1.5px solid #e5e7eb;
    gap: 0;
    flex-wrap: nowrap;
    overflow-x: auto;
    scrollbar-width: none;
  }

  .layanan-nav::-webkit-scrollbar {
    display: none;
  }

  .layanan-nav .nav-link {
    color: #6b7280;
    font-size: 0.95rem;
    font-weight: 500;
    padding: 10px 0;
    margin-right: 36px;
    border: none;
    border-bottom: 2.5px solid transparent;
    background: transparent;
    border-radius: 0;
    white-space: nowrap;
    transition: color 0.2s, border-color 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  .layanan-nav .nav-link:hover {
    color: #dc3545;
  }

  .layanan-nav .nav-link.active {
    color: #dc3545;
    border-bottom-color: #dc3545;
    background: transparent;
    font-weight: 600;
  }

  .layanan-nav .nav-link .nav-arrow {
    font-size: 0.8rem;
    transition: transform 0.2s;
  }

  .layanan-nav .nav-link.active .nav-arrow {
    transform: translateX(3px);
  }

  /* ------- Banner / Sub-header ------- */
  .layanan-banner {
    background: linear-gradient(135deg, #f0f9ff 0%, #e8f5e9 100%);
    border-radius: 16px;
    padding: 36px 40px;
    margin-bottom: 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    min-height: 160px;
    position: relative;
    overflow: hidden;
  }

  .layanan-banner-text h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    margin-bottom: 10px;
  }

  .layanan-banner-text p {
    font-size: 0.9rem;
    color: #4b5563;
    max-width: 480px;
    line-height: 1.65;
    margin-bottom: 0;
  }

  .layanan-banner-icon {
    flex-shrink: 0;
  }

  .layanan-banner-icon svg {
    width: 110px;
    height: 110px;
    opacity: 0.85;
  }

  /* ------- Navigation Arrows ------- */
  .layanan-nav-arrows {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 20px;
  }

  .layanan-arrow-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 1.5px solid #d1d5db;
    background: #fff;
    color: #111827;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
    transition: border-color 0.2s, background 0.2s, color 0.2s;
  }

  .layanan-arrow-btn:hover {
    border-color: #dc3545;
    background: #dc3545;
    color: #fff;
  }

  /* ------- Cards ------- */
  .layanan-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    border: none;
    overflow: hidden;
    transition: box-shadow 0.28s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  .layanan-card:hover {
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  }

  .layanan-card-img-wrap {
    position: relative;
    overflow: hidden;
  }

  .layanan-card-img-wrap img {
    width: 100%;
    height: 190px;
    object-fit: cover;
    display: block;
  }

  .layanan-card-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #dc3545;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    text-transform: uppercase;
  }

  .layanan-card-body {
    padding: 20px 20px 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .layanan-card-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: #111827;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .layanan-card-desc {
    font-size: 0.875rem;
    color: #6b7280;
    line-height: 1.6;
    flex: 1;
    margin-bottom: 16px;
  }

  .layanan-card-price-label {
    font-size: 0.72rem;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
  }

  .layanan-card-price {
    font-size: 1rem;
    font-weight: 700;
    color: #dc3545;
    margin-bottom: 16px;
  }

  .layanan-card-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 0;
    font-size: 0.875rem;
    font-weight: 600;
    width: 100%;
    cursor: pointer;
    transition: background 0.22s, transform 0.15s;
    text-align: center;
    display: block;
    text-decoration: none;
  }

  .layanan-card-btn:hover {
    background: #b91c1c;
    color: #fff;
    transform: translateY(-1px);
  }

  /* ------- Tab Content: single fade on container only ------- */
  .layanan-tab-pane {
    display: none;
  }

  .layanan-tab-pane.active-visible {
    display: block;
    animation: layananFadeIn 0.32s ease both;
  }

  @keyframes layananFadeIn {
    from {
      opacity: 0;
    }

    to {
      opacity: 1;
    }
  }

  /* ------- Responsive ------- */
  @media (max-width: 767.98px) {
    #layanan-kami-section {
      padding: 48px 0 56px;
    }

    .layanan-banner {
      flex-direction: column;
      align-items: flex-start;
      padding: 28px 24px;
    }

    .layanan-banner-icon svg {
      width: 80px;
      height: 80px;
    }

    .layanan-section-title {
      font-size: 1.6rem;
    }
  }

  @media (max-width: 575.98px) {
    .layanan-nav .nav-link {
      font-size: 0.85rem;
      margin-right: 20px;
    }
  }
</style>

{{-- =================== SECTION HTML =================== --}}
<section id="layanan-kami-section" aria-label="Layanan Kami">
  <div class="container">

    {{-- ── Section Title ── --}}
    <h2 class="layanan-section-title mb-3">Layanan Kami</h2>

    {{-- ── Tab Navigation ── --}}
    <ul class="nav layanan-nav mb-4" id="layananTab" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-office"
          data-layanan-tab="office"
          type="button" role="tab"
          aria-controls="pane-office" aria-selected="true">
          Office &amp; Work Space
          <span class="nav-arrow">→</span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-business"
          data-layanan-tab="business"
          type="button" role="tab"
          aria-controls="pane-business" aria-selected="false">
          Business Services
          <span class="nav-arrow">→</span>
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-foreign"
          data-layanan-tab="foreign"
          type="button" role="tab"
          aria-controls="pane-foreign" aria-selected="false">
          Foreign Services
          <span class="nav-arrow">→</span>
        </button>
      </li>
    </ul>

    {{-- ── Tab Content Wrapper ── --}}
    <div id="layananTabContent">

      {{-- ═══════════════════════════════════
                 TAB 1 – Office & Work Space
            ═══════════════════════════════════ --}}
      <div class="layanan-tab-pane active-visible"
        id="pane-office" role="tabpanel" aria-labelledby="tab-office">

        {{-- Sub-banner --}}
        <div class="layanan-banner">
          <div class="layanan-banner-text">
            <h2>Saatnya Membuat Bisnis Anda Lebih Besar</h2>
            <p>Akan sangat disayangkan jika bisnis Anda tidak memiliki kantor dan ruang kerja
              yang mampu membuat Anda menjalankan bisnis secara efektif.</p>
          </div>
          <div class="layanan-banner-icon d-none d-md-block">
            {{-- Office SVG Illustration --}}
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="10" y="50" width="100" height="60" rx="4" stroke="#2CB67D" stroke-width="3" fill="none" />
              <rect x="25" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
              <rect x="75" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
              <line x1="60" y1="50" x2="60" y2="110" stroke="#2CB67D" stroke-width="2" />
              <rect x="35" y="90" width="50" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
              <line x1="10" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
              <line x1="110" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
              <circle cx="60" cy="18" r="4" fill="#2CB67D" />
              <!-- Chair sketch -->
              <path d="M78 32 Q85 25 92 32" stroke="#2CB67D" stroke-width="2" fill="none" />
              <line x1="85" y1="32" x2="85" y2="45" stroke="#2CB67D" stroke-width="2" />
              <path d="M78 45 Q85 48 92 45" stroke="#2CB67D" stroke-width="2" fill="none" />
              <line x1="80" y1="45" x2="78" y2="55" stroke="#2CB67D" stroke-width="2" />
              <line x1="90" y1="45" x2="92" y2="55" stroke="#2CB67D" stroke-width="2" />
            </svg>
          </div>
        </div>

        {{-- Arrow Controls --}}
        <div class="layanan-nav-arrows">
          <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
          <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
        </div>

        {{-- Cards Grid --}}
        <div class="row g-4">
          {{-- Card 1: Virtual Office --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=500&q=80"
                  alt="Virtual Office – Gedung Perkantoran Modern" loading="lazy">
                <span class="layanan-card-badge">Best Seller</span>
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Virtual Office</div>
                <div class="layanan-card-desc">Hemat biaya operasional hingga 90% dengan alamat kantor prestisius tanpa sewa fisik.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 299.000/Bulan*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Virtual Office')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 2: Serviced Office --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&q=80"
                  alt="Serviced Office – Ruang Kantor Siap Pakai" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Serviced Office</div>
                <div class="layanan-card-desc">Ruang kantor siap pakai yang ideal untuk tim Anda, dilengkapi fasilitas lengkap.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 4.500.000/Bulan*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Serviced Office')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 3: Meeting Room --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=500&q=80"
                  alt="Meeting Room – Ruang Rapat Profesional" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Meeting Room</div>
                <div class="layanan-card-desc">Tempat yang cocok untuk melakukan pertemuan penting dengan klien atau mitra bisnis.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 255.000/Jam*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Meeting Room')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 4: Coworking Space --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1600508774634-4e11d34730e2?w=500&q=80"
                  alt="Coworking Space – Ruang Kerja Bersama" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Coworking Space</div>
                <div class="layanan-card-desc">Ruang kerja bersama yang fleksibel dan produktif untuk freelancer maupun startup.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 150.000/Hari*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Coworking Space')">Order Now</button>
              </div>
            </div>
          </div>
        </div>{{-- /row --}}
      </div>{{-- /pane-office --}}


      {{-- ═══════════════════════════════════
                 TAB 2 – Business Services
            ═══════════════════════════════════ --}}
      <div class="layanan-tab-pane"
        id="pane-business" role="tabpanel" aria-labelledby="tab-business">

        {{-- Sub-banner --}}
        <div class="layanan-banner" style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);">
          <div class="layanan-banner-text">
            <h2>Wujudkan Bisnis Impian Anda Bersama Kami</h2>
            <p>Kami hadir untuk membantu Anda mengurus seluruh kebutuhan legalitas dan administrasi
              bisnis dengan cepat, tepat, dan terpercaya.</p>
          </div>
          <div class="layanan-banner-icon d-none d-md-block">
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="20" y="15" width="80" height="95" rx="6" stroke="#F59E0B" stroke-width="3" fill="none" />
              <line x1="35" y1="38" x2="85" y2="38" stroke="#F59E0B" stroke-width="2.5" />
              <line x1="35" y1="52" x2="85" y2="52" stroke="#F59E0B" stroke-width="2.5" />
              <line x1="35" y1="66" x2="70" y2="66" stroke="#F59E0B" stroke-width="2.5" />
              <line x1="35" y1="80" x2="65" y2="80" stroke="#F59E0B" stroke-width="2.5" />
              <circle cx="85" cy="88" r="18" fill="#fff7ed" stroke="#F59E0B" stroke-width="2.5" />
              <path d="M78 88 l4 5 l9-9" stroke="#F59E0B" stroke-width="3" stroke-linecap="round" fill="none" />
            </svg>
          </div>
        </div>

        <div class="layanan-nav-arrows">
          <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
          <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
        </div>

        <div class="row g-4">
          {{-- Card 1 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&q=80"
                  alt="Company Registration – Pendirian Perusahaan" loading="lazy">
                <span class="layanan-card-badge">Populer</span>
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Company Registration</div>
                <div class="layanan-card-desc">Proses pendirian badan usaha PT, CV, Firma dengan cepat dan sesuai regulasi yang berlaku.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 2.500.000/Paket*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Company Registration')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 2 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=500&q=80"
                  alt="Legal Consulting – Konsultasi Hukum Bisnis" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Legal Consulting</div>
                <div class="layanan-card-desc">Konsultasi hukum profesional untuk melindungi bisnis Anda dari risiko hukum yang merugikan.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Legal Consulting')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 3 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=500&q=80"
                  alt="Tax Services – Layanan Pajak" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Tax Services</div>
                <div class="layanan-card-desc">Pengurusan NPWP, pelaporan SPT Tahunan, dan konsultasi pajak bisnis yang akurat dan aman.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 750.000/Laporan*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Tax Services')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 4 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80"
                  alt="Business Licensing – Perizinan Usaha" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Business Licensing</div>
                <div class="layanan-card-desc">Pengurusan NIB, OSS, SIUP, dan berbagai izin usaha lainnya secara cepat dan terpercaya.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 1.200.000/Izin*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Business Licensing')">Order Now</button>
              </div>
            </div>
          </div>
        </div>{{-- /row --}}
      </div>{{-- /pane-business --}}


      {{-- ═══════════════════════════════════
                 TAB 3 – Foreign Services
            ═══════════════════════════════════ --}}
      <div class="layanan-tab-pane"
        id="pane-foreign" role="tabpanel" aria-labelledby="tab-foreign">

        {{-- Sub-banner --}}
        <div class="layanan-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);">
          <div class="layanan-banner-text">
            <h2>Solusi Lengkap untuk Kebutuhan Imigrasi Anda</h2>
            <p>Kami membantu WNA dan ekspatriat mengurus seluruh dokumen keimigrasian
              di Indonesia dengan mudah, cepat, dan sesuai prosedur resmi.</p>
          </div>
          <div class="layanan-banner-icon d-none d-md-block">
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="60" cy="60" r="44" stroke="#6366F1" stroke-width="3" fill="none" />
              <ellipse cx="60" cy="60" rx="24" ry="44" stroke="#6366F1" stroke-width="2.5" fill="none" />
              <line x1="16" y1="60" x2="104" y2="60" stroke="#6366F1" stroke-width="2.5" />
              <path d="M22 38 Q60 48 98 38" stroke="#6366F1" stroke-width="2" fill="none" />
              <path d="M22 82 Q60 72 98 82" stroke="#6366F1" stroke-width="2" fill="none" />
            </svg>
          </div>
        </div>

        <div class="layanan-nav-arrows">
          <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
          <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
        </div>

        <div class="row g-4">
          {{-- Card 1 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1551009175-15bdf9dcb580?w=500&q=80"
                  alt="Visa Application – Permohonan Visa" loading="lazy">
                <span class="layanan-card-badge">Terlaris</span>
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Visa Application</div>
                <div class="layanan-card-desc">Pengurusan visa kunjungan, bisnis, dan tinggal terbatas di Indonesia secara profesional.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 1.500.000/Proses*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Visa Application')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 2 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&q=80"
                  alt="KITAS / KITAP – Izin Tinggal WNA" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">KITAS / KITAP</div>
                <div class="layanan-card-desc">Pengurusan izin tinggal terbatas dan tetap untuk warga negara asing di Indonesia.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 3.500.000/Proses*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('KITAS / KITAP')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 3 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=500&q=80"
                  alt="Passport Assistance – Bantuan Paspor" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Passport Assistance</div>
                <div class="layanan-card-desc">Bantuan persiapan, pengurusan, dan perpanjangan paspor untuk keperluan perjalanan internasional.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 800.000/Proses*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Passport Assistance')">Order Now</button>
              </div>
            </div>
          </div>
          {{-- Card 4 --}}
          <div class="col-12 col-md-6 col-lg-3">
            <div class="layanan-card">
              <div class="layanan-card-img-wrap">
                <img src="https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=500&q=80"
                  alt="Immigration Consulting – Konsultasi Imigrasi" loading="lazy">
              </div>
              <div class="layanan-card-body">
                <div class="layanan-card-title">Immigration Consulting</div>
                <div class="layanan-card-desc">Layanan konsultasi keimigrasian komprehensif untuk WNA yang akan tinggal atau bekerja di Indonesia.</div>
                <div class="layanan-card-price-label">Price Start From</div>
                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                <button type="button" class="layanan-card-btn" onclick="openOrderModal('Immigration Consulting')">Order Now</button>
              </div>
            </div>
          </div>
        </div>{{-- /row --}}
      </div>{{-- /pane-foreign --}}

    </div>{{-- /#layananTabContent --}}
  </div>{{-- /.container --}}
</section>

{{-- =================== JAVASCRIPT (TAB) =================== --}}
<script>
  (function() {
    'use strict';
    const tabBtns = document.querySelectorAll('[data-layanan-tab]');
    const tabPanes = document.querySelectorAll('.layanan-tab-pane');
    tabBtns.forEach(function(btn) {
      btn.addEventListener('click', function() {
        const target = btn.getAttribute('data-layanan-tab');
        tabBtns.forEach(function(b) { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
        btn.classList.add('active'); btn.setAttribute('aria-selected', 'true');
        tabPanes.forEach(function(pane) { if (pane.classList.contains('active-visible')) { pane.classList.remove('active-visible'); pane.style.display = 'none'; } });
        const targetPane = document.getElementById('pane-' + target);
        if (targetPane) { targetPane.style.display = 'block'; void targetPane.offsetWidth; targetPane.classList.add('active-visible'); }
      });
    });
  })();
</script>

{{-- =================== ORDER MODAL =================== --}}
{{-- Isi nama layanan secara dinamis saat tombol diklik --}}
<div id="orderModalOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.55); z-index:99999; align-items:center; justify-content:center;" onclick="closeOrderModal(event)">
  <div id="orderModalBox" style="background:#fff; border-radius:16px; padding:36px 32px; width:100%; max-width:480px; position:relative; box-shadow:0 20px 60px rgba(0,0,0,0.18); margin:16px;" onclick="event.stopPropagation()">

    {{-- Close button --}}
    <button onclick="document.getElementById('orderModalOverlay').style.display='none'" style="position:absolute; top:14px; right:16px; background:none; border:none; font-size:1.5rem; line-height:1; cursor:pointer; color:#9ca3af;" aria-label="Tutup">&times;</button>

    <h4 style="font-weight:700; font-size:1.2rem; margin-bottom:4px; color:#111827;">Pesan Layanan</h4>
    <p id="orderModalServiceLabel" style="color:#dc3545; font-weight:600; font-size:0.95rem; margin-bottom:20px;"></p>

    <form id="orderForm" action="{{ route('public.order.store') }}" method="POST">
      @csrf
      <input type="hidden" name="service_name" id="orderServiceInput">

      <div style="margin-bottom:14px;">
        <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:6px; color:#374151;">Nama Lengkap <span style="color:#dc3545;">*</span></label>
        <input type="text" name="name" required placeholder="Masukkan nama lengkap Anda"
          value="{{ auth()->user()->name ?? '' }}"
          style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px; padding:10px 14px; font-size:0.9rem; outline:none; transition:border-color .2s; color:#111827 !important; background:#fff !important;"
          onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e5e7eb'">
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:6px; color:#374151;">Email <span style="color:#dc3545;">*</span></label>
        <input type="email" name="email" required placeholder="email@domain.com"
          value="{{ auth()->user()->email ?? '' }}"
          style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px; padding:10px 14px; font-size:0.9rem; outline:none; transition:border-color .2s; color:#111827 !important; background:#fff !important;"
          onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e5e7eb'">
      </div>

      <div style="margin-bottom:14px;">
        <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:6px; color:#374151;">Nomor WhatsApp <span style="color:#dc3545;">*</span></label>
        <input type="text" name="phone" required placeholder="08xx-xxxx-xxxx"
          value="{{ auth()->user()->phone ?? '' }}"
          style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px; padding:10px 14px; font-size:0.9rem; outline:none; transition:border-color .2s; color:#111827 !important; background:#fff !important;"
          onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e5e7eb'">
      </div>

      <div style="margin-bottom:20px;">
        <label style="display:block; font-size:0.82rem; font-weight:600; margin-bottom:6px; color:#374151;">Catatan (opsional)</label>
        <textarea name="notes" rows="3" placeholder="Ceritakan kebutuhan Anda secara singkat…"
          style="width:100%; border:1.5px solid #e5e7eb; border-radius:8px; padding:10px 14px; font-size:0.9rem; outline:none; resize:none; transition:border-color .2s; color:#111827 !important; background:#fff !important;"
          onfocus="this.style.borderColor='#dc3545'" onblur="this.style.borderColor='#e5e7eb'"></textarea>
      </div>

      <button type="submit" id="orderSubmitBtn"
        style="width:100%; background:#dc3545; color:#fff; border:none; border-radius:10px; padding:13px; font-size:1rem; font-weight:700; cursor:pointer; transition:background .2s;"
        onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc3545'"
        onclick="this.disabled=true; this.textContent='Memproses…'; this.closest('form').submit();">
        Kirim Pesanan
      </button>
    </form>
  </div>
</div>

{{-- ── Auth state untuk JS (server-side rendered, aman) ── --}}
<script>
  var IS_LOGGED_IN = {{ auth()->check() ? 'true' : 'false' }};

  function openOrderModal(serviceName) {
    if (!IS_LOGGED_IN) {
      showLoginToast();
      return;
    }
    document.getElementById('orderServiceInput').value = serviceName;
    document.getElementById('orderModalServiceLabel').textContent = serviceName;
    var overlay = document.getElementById('orderModalOverlay');
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
  }

  function closeOrderModal(e) {
    if (e.target === document.getElementById('orderModalOverlay')) {
      document.getElementById('orderModalOverlay').style.display = 'none';
      document.body.style.overflow = '';
    }
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      document.getElementById('orderModalOverlay').style.display = 'none';
      document.body.style.overflow = '';
    }
  });

  // ── Login Notification Logic ──
  function showLoginToast() {
    var toast = document.getElementById('loginRequiredToast');
    if (!toast) return;
    toast.style.opacity = '0';
    toast.style.display = 'flex';
    setTimeout(function() { toast.style.opacity = '1'; }, 10);
  }

  function hideLoginToast() {
    var toast = document.getElementById('loginRequiredToast');
    if (!toast) return;
    toast.style.opacity = '0';
    setTimeout(function() { toast.style.display = 'none'; }, 300);
  }

  function openLoginModal() {
    hideLoginToast();
    setTimeout(function() {
      var modalEl = document.getElementById('exampleModal');
      if (modalEl && typeof bootstrap !== 'undefined') {
        bootstrap.Modal.getOrCreateInstance(modalEl).show();
      }
    }, 250);
  }
</script>

{{-- ── Login Required Notification ── --}}
<div id="loginRequiredToast"
  style="display:none; opacity:0; position:fixed; top:24px; right:24px; z-index:999999;
         background:#fff; border-radius:14px; padding:16px 18px;
         min-width:280px; max-width:340px;
         box-shadow:0 12px 40px rgba(0,0,0,0.16); border-left:4px solid #dc3545;
         align-items:flex-start; gap:12px; transition:opacity 0.3s ease;">

  {{-- Icon --}}
  <div style="flex-shrink:0; padding-top:2px;">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="10"/>
      <line x1="12" y1="8" x2="12" y2="12"/>
      <line x1="12" y1="16" x2="12.01" y2="16"/>
    </svg>
  </div>

  {{-- Content --}}
  <div style="flex:1; min-width:0;">
    <p style="margin:0 0 3px; font-weight:700; font-size:0.88rem; color:#111827;">Login Diperlukan</p>
    <p style="margin:0 0 12px; font-size:0.8rem; color:#6b7280; line-height:1.5;">
      Silakan login untuk melakukan pemesanan layanan.
    </p>
    <div style="display:flex; gap:8px;">
      <button onclick="openLoginModal()"
        style="background:#dc3545; color:#fff; border:none; padding:7px 16px;
               border-radius:8px; font-size:0.82rem; font-weight:700; cursor:pointer;
               transition:background .15s;"
        onmouseover="this.style.background='#b91c1c'"
        onmouseout="this.style.background='#dc3545'">
        Login
      </button>
      <button onclick="hideLoginToast()"
        style="background:#f3f4f6; color:#6b7280; border:none; padding:7px 14px;
               border-radius:8px; font-size:0.82rem; cursor:pointer;
               transition:background .15s;"
        onmouseover="this.style.background='#e5e7eb'"
        onmouseout="this.style.background='#f3f4f6'">
        Tutup
      </button>
    </div>
  </div>

  {{-- X close --}}
  <button onclick="hideLoginToast()"
    style="position:absolute; top:10px; right:12px; background:none; border:none;
           font-size:1.1rem; color:#9ca3af; cursor:pointer; line-height:1;">&times;</button>
</div>

{{-- Flash success toast --}}
@if(session('success'))
<div id="orderToast" style="position:fixed; bottom:28px; right:28px; background:#16a34a; color:#fff; padding:14px 22px; border-radius:12px; font-size:0.92rem; font-weight:600; box-shadow:0 8px 24px rgba(0,0,0,0.15); z-index:999999; max-width:340px; display:flex; align-items:center; gap:10px;">
  <span style="font-size:1.2rem;">✓</span>
  <span>{{ session('success') }}</span>
</div>
<script>setTimeout(function(){ var t = document.getElementById('orderToast'); if(t){t.style.transition='opacity .5s';t.style.opacity='0'; setTimeout(function(){t.remove();},500);} }, 4000);</script>
@endif
