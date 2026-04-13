@extends('layout.app')

@section('content')

<style>
    /* ===== MINIMAL CSS ===== */
    :root {
        --primary: #4e0516;
        --primary-light: #7a0a23;
        --accent: #c9a03d;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg-light: #fdf8f5;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--dark);
    }

    /* Hapus padding app layout bentrok bila perlu */

    /* Solusi Section */
    .pt-solution {
        padding: 80px 0;
        background: #fff;
        contain: content;
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }

    .pt-solution h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 15px;
    }

    .pt-solution p {
        color: var(--gray);
        font-size: 1.05rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .solution-list {
        list-style: none;
        padding: 0;
        margin-bottom: 30px;
    }

    .solution-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        color: #334155;
        font-weight: 500;
    }

    .solution-list li i {
        color: #10b981;
        font-size: 1.2rem;
    }

    .btn-outline-brand {
        display: inline-block;
        padding: 12px 30px;
        border: 2px solid var(--primary);
        color: var(--primary);
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .btn-outline-brand:hover {
        background: var(--primary);
        color: #fff;
    }

    /* Benefits */
    .pt-benefits {
        padding: 80px 0;
        background: var(--bg-light);
        content-visibility: auto;
        contain-intrinsic-size: auto 400px;
    }

    .benefit-card {
        background: #fff;
        padding: 30px 25px;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        border: 1px solid #f0e4e8;
        text-align: center;
        height: 100%;
        transform: translateZ(0);
        transition: transform 0.15s ease;
    }

    .benefit-card:hover {
        transform: translateY(-3px);
    }

    .benefit-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .benefit-card h4 {
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
        font-size: 1.1rem;
    }

    .benefit-card p {
        color: var(--gray);
        font-size: 0.9rem;
        margin: 0;
    }

    /* Process Steps */
    .pt-process {
        padding: 80px 0;
        background: #fff;
        content-visibility: auto;
        contain-intrinsic-size: auto 400px;
    }

    .process-step {
        text-align: center;
        padding: 25px 20px;
    }

    .step-number {
        width: 60px;
        height: 60px;
        background: var(--primary);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.5rem;
        margin: 0 auto 20px;
        box-shadow: 0 4px 15px rgba(78, 5, 22, 0.2);
    }

    .process-step h5 {
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dark);
        font-size: 1.1rem;
    }

    .process-step p {
        color: var(--gray);
        font-size: 0.9rem;
        margin: 0;
    }

    /* Requirements */
    .pt-requirements {
        padding: 80px 0;
        background: var(--bg-light);
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }

    .requirement-box {
        background: #fff;
        padding: 40px;
        border-radius: 20px;
        border: 1px solid #f0e4e8;
        height: 100%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
    }

    .requirement-box h4 {
        font-weight: 700;
        margin-bottom: 25px;
        color: var(--dark);
        font-size: 1.3rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .requirement-box ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .requirement-box li {
        padding: 12px 0;
        border-bottom: 1px solid #f5f5f5;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .requirement-box li:last-child {
        border-bottom: none;
    }

    .requirement-box li i {
        color: var(--accent);
    }

    /* Pricing Cards */
    .pt-pricing {
        padding: 80px 0;
        background: #fff;
        content-visibility: auto;
        contain-intrinsic-size: auto 700px;
    }

    .pricing-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid #f0e4e8;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        transform: translateZ(0);
        transition: transform 0.15s ease;
    }

    .pricing-card:hover {
        transform: translateY(-3px);
    }

    .pricing-card.featured {
        border: 2px solid var(--accent);
    }

    .pricing-card .badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--accent);
        color: #1e1b2b;
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .pricing-card h4 {
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center;
        color: var(--dark);
        font-size: 1.3rem;
    }

    .pricing-card .price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
        text-align: center;
        margin-bottom: 25px;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0 0 30px;
        flex-grow: 1;
    }

    .feature-list li {
        padding: 10px 0;
        display: flex;
        align-items: center;
        gap: 12px;
        border-bottom: 1px solid #f5f5f5;
        font-size: 0.95rem;
        color: #334155;
    }

    .feature-list li i.fa-check {
        color: var(--primary);
        width: 18px;
    }

    .feature-list li i.fa-minus {
        color: #d4d4d8;
        width: 18px;
    }

    .feature-list li.disabled {
        color: #a1a1aa;
    }

    .btn-pricing {
        display: block;
        text-align: center;
        padding: 14px;
        border: 2px solid var(--primary);
        border-radius: 50px;
        color: var(--primary);
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .btn-pricing:hover {
        background: var(--primary);
        color: #fff;
    }

    .btn-pricing-primary {
        display: block;
        text-align: center;
        padding: 14px;
        background: var(--primary);
        border-radius: 50px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
        border: none;
    }

    .btn-pricing-primary:hover {
        background: var(--primary-light);
        color: #fff;
    }

    /* FAQ */
    .pt-faq {
        padding: 80px 0;
        background: var(--bg-light);
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }

    .faq-item {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid #f0e4e8;
        overflow: hidden;
    }

    .faq-question {
        padding: 20px 25px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .faq-question i {
        transition: transform 0.2s ease;
        color: var(--accent);
    }

    .faq-answer {
        padding: 0 25px 20px;
        color: var(--gray);
        display: none;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    /* Section Title Umum */
    .section-title {
        margin-bottom: 40px;
    }

    .section-title .subtitle {
        color: var(--accent);
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 8px;
    }

    .section-title h2 {
        color: var(--dark);
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0 0 15px;
    }

    .section-title p {
        color: var(--gray);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .pt-hero h1 {
            font-size: 2.2rem;
        }

        .pt-hero {
            padding: 60px 0;
            text-align: center;
        }

        .pt-hero .hero-badge {
            margin: 0 auto 20px;
        }

        .section-title h2 {
            font-size: 1.8rem;
        }

        .pt-solution {
            text-align: center;
        }

        .solution-list li {
            justify-content: center;
        }

        .page-title-area {
            padding-top: 100px !important;
            padding-bottom: 65px !important;
        }
    }

    /* Utilities */
    .img-fluid-rounded {
        border-radius: 20px;
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.08);
    }
</style>

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.6) 0%, rgba(45, 6, 16, 0.6) 50%, rgba(26, 2, 8, 0.6) 100%), 
                      url('https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1200&q=60');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding-top: 200px;
    padding-bottom: 100px;
  ">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika </span>
                    <h1 class="text-white fw-bold mb-3 display-4">Jasa Pembuatan PT Perorangan</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Dirikan Perusahaan PT untuk Bisnis Anda. Jasa Pembuatan PT dari Lawgika membantu usaha Anda menjadi entitas bisnis yang legal dan bonafide dengan proses paling efisien.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Pembuatan PT Perorangan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>



{{-- ===== SOLUSI LEGALITAS ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2>Solusi Legalitas Mudah & Fleksibel Untuk Anda</h2>
                <p>PT Perorangan adalah solusi ideal bagi wirausaha tunggal yang ingin memiliki badan hukum resmi tanpa perlu partner (pendiri tunggal). Dengan proses yang sederhana, cepat, dan biaya terjangkau.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Proses 100% online, efisien & transparan</li>
                    <li><i class="fa-regular fa-circle-check"></i> Akta & SK Resmi dari Kemenkumham</li>
                    <li><i class="fa-regular fa-circle-check"></i> Didampingi konsultan hukum bersertifikat</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Pilihan Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=800&auto=format" alt="Business Handshake" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="pt-benefits">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Keuntungan Ekstra</span>
            <h2>Fasilitas Layanan Kami</h2>
            <p>Dapatkan berbagai keuntungan tambahan saat mendirikan perusahaan bersama kami.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon">🏦</div>
                    <h4>Pembukaan Rekening</h4>
                    <p>Dapatkan gratis pendampingan pembukaan rekening bisnis PT Anda pasca terbit SK.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon">📋</div>
                    <h4>Nomor EFIN & Pajak</h4>
                    <p>Terima beres pengurusan permohonan Nomor EFIN dan kebutuhan perpajakan perdana.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <div class="benefit-icon">💬</div>
                    <h4>Konsultasi Gratis</h4>
                    <p>Kami akan mendampingi dan memberikan advis strategis untuk kebutuhan regulasi PT Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROSES PENDIRIAN PT ===== --}}
<section class="pt-process">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Tahapan Tuntas</span>
            <h2>Proses Pendirian PT di Indonesia</h2>
            <p>Kami menyederhanakan birokrasi kompleks menjadi beberapa tahap transparan dan cepat.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h5>Persyaratan Pendirian PT</h5>
                    <p>Berdasarkan regulasi resmi, siapkan data KTP, NPWP, dan pengecekan pemesanan nama PT untuk memvalidasi tidak ada duplikasi penamaan dengan korporasi lain.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h5>Submission Elektronik Hukum</h5>
                    <p>Ahli hukum kami merangkum pernyataan pendirian secara digital menggunakan protokol autentik dalam bahasa Indonesia ke server Kemenkumham RI.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h5>Pengesahan & Legalitas Penuh</h5>
                    <p>Kementerian Hukum & HAM menerbitkan Surat Keputusan pengesahan badan hukum, diikuti rilis Nomor Induk Berusaha (NIB) PT Anda siap beroperasi resmi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN ===== --}}
<section class="pt-requirements">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Persiapan Cepat</span>
            <h2>Persyaratan Kelengkapan PT</h2>
            <p>Hanya menyiapkan berkas inti administrasi berikut, biarkan tim kami yang bergerak keras.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-box">
                    <h4><i class="fa-solid fa-list-check"></i> Kelengkapan Data</h4>
                    <ul>
                        <li><i class="fa-solid fa-check"></i> Nama PT (Opsi minimal 3 kata spesifik)</li>
                        <li><i class="fa-solid fa-check"></i> Alamat Lengkap Kedudukan Perusahaan</li>
                        <li><i class="fa-solid fa-check"></i> Bidang Usaha Bersesuaian Kode KBLI</li>
                        <li><i class="fa-solid fa-check"></i> Struktur Modal Disetor Perusahaan</li>
                        <li><i class="fa-solid fa-check"></i> Susunan Komisaris Direksi & Jabatan</li>
                        <li><i class="fa-solid fa-check"></i> Susunan Valid Pemegang Saham</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-box">
                    <h4><i class="fa-solid fa-file-pdf"></i> Kelengkapan Dokumen</h4>
                    <ul>
                        <li><i class="fa-solid fa-check"></i> Scan KTP Direktur / Pendiri</li>
                        <li><i class="fa-solid fa-check"></i> Scan NPWP Direktur / Pendiri Aktif</li>
                        <li><i class="fa-solid fa-check"></i> Pas Foto Pendiri / Direktur Berwarna</li>
                        <li><i class="fa-solid fa-check"></i> Dokumen Bukti Alamat Usaha (Contoh: PBB/Sewa)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Pilihan Paket Transparan</span>
            <h2>Paket Pendirian PT Perorangan</h2>
            <p>Rincian harga jujur dan tegas. Anda bayar sesuai apa yang ditunjukkan tanpa *hidden markups*.</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- Basic --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>Paket Basic</h4>
                    <div class="price">Rp 2.000.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian Elektronik</li>
                        <li><i class="fa-solid fa-check"></i> SK Kemenkumham Terbit</li>
                        <li><i class="fa-solid fa-check"></i> NPWP Badan Usaha</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Izin Usaha</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Rekening Bank PT</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Konsultasi Legal VIP</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Pendampingan 6 Bulan</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Review Kontrak Bisnis</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20Basic%20PT%20Perorangan" class="btn-pricing">Pilih Paket Basic</a>
                </div>
            </div>

            {{-- Professional (Recommended) --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <span class="badge">REKOMENDASI</span>
                    <h4>Paket Professional</h4>
                    <div class="price">Rp 6.000.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian Elektronik</li>
                        <li><i class="fa-solid fa-check"></i> SK Kemenkumham Terbit</li>
                        <li><i class="fa-solid fa-check"></i> NPWP Badan Usaha</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Izin Usaha</li>
                        <li><i class="fa-solid fa-check"></i> Rekening Bank PT</li>
                        <li><i class="fa-solid fa-check"></i> Konsultasi Legal VIP</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Pendampingan 6 Bulan</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Review Kontrak Bisnis</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20Professional%20PT%20Perorangan" class="btn-pricing-primary">Pilih Paket Professional</a>
                </div>
            </div>

            {{-- Enterprise --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>Paket Enterprise</h4>
                    <div class="price">Rp 8.000.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian Elektronik</li>
                        <li><i class="fa-solid fa-check"></i> SK Kemenkumham Terbit</li>
                        <li><i class="fa-solid fa-check"></i> NPWP Badan Usaha</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Izin Usaha</li>
                        <li><i class="fa-solid fa-check"></i> Rekening Bank PT</li>
                        <li><i class="fa-solid fa-check"></i> Konsultasi Legal VIP</li>
                        <li><i class="fa-solid fa-check"></i> Pendampingan 6 Bulan</li>
                        <li><i class="fa-solid fa-check"></i> Review Kontrak Bisnis</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20Enterprise%20PT%20Perorangan" class="btn-pricing">Pilih Paket Enterprise</a>
                </div>
            </div>
        </div>
    </div>
</section>

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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
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
                                <a href="#" class="layanan-card-btn" role="button">Details</a>
                            </div>
                        </div>
                    </div>
                </div>{{-- /row --}}
            </div>{{-- /pane-foreign --}}

        </div>{{-- /#layananTabContent --}}
    </div>{{-- /.container --}}
</section>

{{-- =================== JAVASCRIPT =================== --}}
<script>
    (function() {
        'use strict';

        // Tab switching with fade + slide-up animation
        const tabBtns = document.querySelectorAll('[data-layanan-tab]');
        const tabPanes = document.querySelectorAll('.layanan-tab-pane');

        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const target = btn.getAttribute('data-layanan-tab');

                // Update active button state
                tabBtns.forEach(function(b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');

                // Hide current pane
                tabPanes.forEach(function(pane) {
                    if (pane.classList.contains('active-visible')) {
                        pane.classList.remove('active-visible');
                        pane.style.display = 'none';
                    }
                });

                // Show target pane with animation
                const targetPane = document.getElementById('pane-' + target);
                if (targetPane) {
                    targetPane.style.display = 'block';
                    // Trigger reflow to re-run animation
                    void targetPane.offsetWidth;
                    targetPane.classList.add('active-visible');
                }
            });
        });
    })();
</script>


{{-- ===== FAQ SECTION ===== --}}
<section class="pt-faq">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Bantuan Sentral</span>
            <h2>FAQ terkait Pendirian PT</h2>
            <p>Pertanyaan yang paling sering diajukan seputar legalitas & pengurusan perusahaan.</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Apa Syarat Utama Mendirikan PT Perorangan? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Pendiri wajib Warga Negara Indonesia (WNI) berusia minimal 21 tahun atau sudah menikah, belum pernah mendirikan PT Perorangan dalam 1 tahun terakhir, dan menyiapkan identitas resmi (KTP dan NPWP aktif). PT tipe ini biasanya terbatas pada klasifikasi kriteria Usaha Mikro dan Kecil (UMK) yang permodalannya tidak melebihi persentase tertentu.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa Lama Waktu Yang Dibutuhkan Hingga Terbit Akta? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Dengan kelengkapan berkas yang memenuhi syarat penuh, proses penyelesaian administrasi dan pendaftaran elektronik dapat rampung dengan estimasi sangat singkat—tergantung kecepatan Anda menyediakan data valid (Umumnya berkisar antara hitungan 2 jam higga 5 hari operasional saja).</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah Harus Memiliki Alamat Tempat (Ruko/Gedung Fisik)? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Setiap PT termasuk PT perorangan tetap diwajibkan menyertakan alamat jelas kedudukan. Namun apabila Anda tidak memiliki ruko/gedung fisik bersertifikat komersial, Anda cukup menyewa layanan "Virtual Office" legal di zona bisnis untuk menekan limitasi modal operasional Anda.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apa Sih Perbedaan Antara PT Biasa dengan Perorangan? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">PT biasa absolut didirikan minimal oleh 2 pemegang saham independen di hadapan Akta Notaris, sedangkan PT Perorangan bisa berdiri kokoh pada 1 subjek pengurus tunggal saja. Birokrasinya jauh lebih praktis karena cukup meregistrasikan Surat Pernyataan Pendirian secara format elektronik penuh ke kementerian Hukum dan HAM Republik Indonesia.</div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    // Simple FAQ accordion (vanilla JS - ringan)
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                // Tutup FAQ lain yang sedang terbuka
                document.querySelectorAll('.faq-item').forEach(faq => {
                    if (faq !== parent) faq.classList.remove('active');
                });
                // Toggle yang di-klik
                parent.classList.toggle('active');
            });
        });
    });
</script>
@endsection