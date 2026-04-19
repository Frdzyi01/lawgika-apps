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

    /* Benefits / Why Us */
    .why-us-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #fdf8f5 0%, #fff8f3 100%);
        position: relative;
    }

    .why-us-section .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .why-us-section .section-header h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 10px;
    }

    .why-us-section .section-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .why-us-card {
        background: #fff;
        padding: 35px 25px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f5e6e8;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }

    .why-us-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(78, 5, 22, 0.08);
        border-color: #c9a03d;
    }

    .why-us-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #4e0516 0%, #7a0a23 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .why-us-icon i {
        font-size: 2.2rem;
        color: #fff;
    }

    .why-us-card h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e1b2b;
        margin-bottom: 12px;
    }

    .why-us-card p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Process Steps */
    .process-section {
        padding: 80px 0;
        background: #fff;
    }

    .process-section .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .process-section .section-header .badge {
        background: #f5e6c8;
        color: #c9a03d;
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .process-section .section-header h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 10px;
    }

    .process-section .section-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .process-timeline {
        display: flex;
        justify-content: space-between;
        position: relative;
        max-width: 1000px;
        margin: 0 auto;
    }

    .process-timeline::before {
        content: '';
        position: absolute;
        top: 45px;
        left: 15%;
        right: 15%;
        height: 2px;
        background: linear-gradient(90deg, #4e0516 0%, #c9a03d 50%, #4e0516 100%);
        z-index: 1;
    }

    .process-step {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
        padding: 0 15px;
    }

    .process-step-number {
        width: 60px;
        height: 60px;
        background: #fff;
        border: 3px solid #4e0516;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.5rem;
        font-weight: 800;
        color: #4e0516;
        position: relative;
        z-index: 3;
    }

    .process-step.completed .process-step-number {
        background: #4e0516;
        color: #fff;
        border-color: #4e0516;
    }

    .process-step h5 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e1b2b;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .process-step p {
        color: #64748b;
        font-size: 0.9rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .process-timeline {
            flex-direction: column;
            gap: 30px;
        }

        .process-timeline::before {
            display: none;
        }

        .process-step {
            display: flex;
            text-align: left;
            gap: 20px;
            align-items: flex-start;
        }

        .process-step-number {
            margin: 0;
            flex-shrink: 0;
        }
    }

    /* Requirements */
    .requirements-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #faf5f2 0%, #fdf8f5 100%);
        position: relative;
    }

    .requirements-section .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .requirements-section .section-header .badge {
        background: #4e0516;
        color: #fff;
        padding: 5px 18px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .requirements-section .section-header h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 10px;
    }

    .requirements-section .section-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .requirement-card {
        background: #fff;
        border-radius: 24px;
        padding: 35px 30px;
        height: 100%;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.05);
        border: 1px solid #f0e4e8;
        transition: transform 0.2s ease;
    }

    .requirement-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(78, 5, 22, 0.08);
    }

    .requirement-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f5e6e8;
    }

    .requirement-icon {
        width: 55px;
        height: 55px;
        background: linear-gradient(135deg, #4e0516 0%, #7a0a23 100%);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .requirement-icon i {
        font-size: 1.8rem;
        color: #fff;
    }

    .requirement-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e1b2b;
        margin: 0;
    }

    .requirement-list {
        list-style: none;
        padding: 0;
        margin: 0 0 30px;
    }

    .requirement-list li {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px dashed #f0e4e8;
        color: #1e1b2b;
        font-size: 0.95rem;
    }

    .requirement-list li:last-child {
        border-bottom: none;
    }

    .requirement-list li i {
        color: #4e0516;
        font-size: 1.1rem;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .requirement-list li i.fa-circle-check {
        color: #10b981;
    }

    .requirement-cta {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4e0516;
        font-weight: 600;
        text-decoration: none;
        padding: 10px 0;
        border-bottom: 2px solid transparent;
        transition: border-color 0.2s ease;
    }

    .requirement-cta:hover {
        border-bottom-color: #c9a03d;
        color: #7a0a23;
    }

    .requirement-cta i {
        transition: transform 0.2s ease;
    }

    .requirement-cta:hover i {
        transform: translateX(5px);
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
        font-size: 2rem;
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
        font-size: 0.9rem;
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
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.7) 0%, rgba(45, 6, 16, 0.7) 50%, rgba(26, 2, 8, 0.7) 100%), 
                      url('https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Pendirian PT</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Jasa Pendirian PT (Perseroan Terbatas) <br> > 1M</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Solusi legalitas untuk badan usaha berbadan hukum yang modalnya terdiri atas saham-saham. Proses cepat, transparan, dan terpercaya.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Pendirian PT > 1M</li>
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
                <h2>Solusi Legalitas PT Untuk Bisnis Profesional Anda</h2>
                <p>PT (Perseroan Terbatas) adalah badan usaha berbadan hukum yang modalnya terdiri atas saham-saham. Cocok untuk bisnis yang ingin tumbuh besar, menarik investor, dan memiliki kredibilitas tinggi di mata mitra bisnis.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Pendirian cepat dengan akta notaris</li>
                    <li><i class="fa-regular fa-circle-check"></i> SK Kemenkumham resmi</li>
                    <li><i class="fa-regular fa-circle-check"></i> Didampingi konsultan hukum berpengalaman</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Pilihan Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&auto=format" alt="Business Meeting PT" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>MENGAPA MEMILIH KAMI?</h2>
            <p>Dapatkan berbagai keuntungan tambahan saat mendirikan PT bersama kami</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h4>Legalitas Kuat</h4>
                    <p>Akta pendirian dan SK Kemenkumham yang sah untuk badan usaha Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h4>Skalabilitas Bisnis</h4>
                    <p>PT memudahkan Anda menarik investor dan mengembangkan bisnis.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h4>Kepatuhan Hukum</h4>
                    <p>Jaminan dokumen sesuai dengan regulasi PT terbaru.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROSES PENDIRIAN PT ===== --}}
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Tahapan Tuntas</span>
            <h2>PROSES PENDIRIAN PT</h2>
            <p>Kami menyederhanakan birokrasi menjadi tahapan yang jelas</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-step-number">1</div>
                <h5>Persiapan Dokumen</h5>
                <p>Siapkan KTP, NPWP pendiri, dan struktur pemegang saham.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">2</div>
                <h5>Pembuatan Akta Notaris</h5>
                <p>Pembuatan akta pendirian PT di hadapan notaris.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">3</div>
                <h5>Pengesahan Kemenkumham</h5>
                <p>Pengesahan badan hukum dan penerbitan SK, NIB, dan izin usaha.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN PT ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Persiapan Dokumen</span>
            <h2>PERSYARATAN DOKUMEN PT</h2>
            <p>Siapkan berkas berikut, tim kami akan memprosesnya</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <h3>KELENGKAPAN DATA</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> Nama PT (minimal 3 kata)</li>
                        <li><i class="fa-solid fa-circle-check"></i> Alamat lengkap perusahaan</li>
                        <li><i class="fa-solid fa-circle-check"></i> Bidang usaha (Kode KBLI)</li>
                        <li><i class="fa-solid fa-circle-check"></i> Struktur pemegang saham</li>
                        <li><i class="fa-solid fa-circle-check"></i> Susunan direksi & komisaris</li>
                        <li><i class="fa-solid fa-circle-check"></i> Modal dasar, modal ditempatkan, modal disetor</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Konsultasi Data <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <h3>KELENGKAPAN DOKUMEN</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> Scan KTP pendiri & pengurus</li>
                        <li><i class="fa-solid fa-circle-check"></i> Scan NPWP pendiri & pengurus</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pas foto pendiri & pengurus</li>
                        <li><i class="fa-solid fa-circle-check"></i> Bukti alamat perusahaan</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Upload Dokumen <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE PT ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Pilihan Paket PT</span>
            <h2>Paket Pendirian PT</h2>
            <p>Pilih paket yang sesuai dengan kebutuhan bisnis profesional Anda</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- PREMIUM --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>PREMIUM</h4>
                    <div class="price">Rp 6.930.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Alamat Bisnis Eksklusif</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Fasilitas Ruang Meeting atau podcast 12 jam / bulan</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Akses Wifi dan Smart TV</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Pengelolaan surat/paket masuk</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Notifikasi Surat/Paket Masuk</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Surat Keterangan Domisili</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Gratis akses komunitas bisnis</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Resepsionis</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Dashboard Login Klien</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Storage cloud dokumen</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Rekening PT Bank OCBC/BCA/MANDIRI</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Signage Display</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Handling</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20PREMIUM%20PT" class="btn-pricing">Pilih Paket Premium</a>
                </div>
            </div>

            {{-- EKSKLUSIF (Recommended) --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <span class="badge">REKOMENDASI</span>
                    <h4>EKSKLUSIF</h4>
                    <div class="price">Rp 8.640.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 12 jam / bulan</li>
                        <li><i class="fa-solid fa-check"></i> Akses Wifi dan Smart TV</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</li>
                        <li><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</li>
                        <li><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</li>
                        <li><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Resepsionis</li>
                        <li><i class="fa-solid fa-check"></i> Dashboard Login Klien</li>
                        <li><i class="fa-solid fa-check"></i> Storage cloud dokumen</li>
                        <li><i class="fa-solid fa-check"></i> Rekening PT Bank OCBC/BCA/MANDIRI</li>
                        <li><i class="fa-solid fa-check"></i> Signage Display</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Handling</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20EKSKLUSIF%20PT" class="btn-pricing-primary">Pilih Paket Eksklusif</a>
                </div>
            </div>

            {{-- ENTERPRISE --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>ENTERPRISE</h4>
                    <div class="price">Rp 9.540.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 12 jam / bulan</li>
                        <li><i class="fa-solid fa-check"></i> Akses Wifi dan Smart TV</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</li>
                        <li><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</li>
                        <li><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</li>
                        <li><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Resepsionis</li>
                        <li><i class="fa-solid fa-check"></i> Dashboard Login Klien</li>
                        <li><i class="fa-solid fa-check"></i> Storage cloud dokumen</li>
                        <li><i class="fa-solid fa-check"></i> Rekening PT Bank OCBC/BCA/MANDIRI</li>
                        <li><i class="fa-solid fa-check"></i> Signage Display</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Call Handling</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20ENTERPRISE%20PT" class="btn-pricing">Pilih Paket Enterprise</a>
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

    /* ------- Arrows Custom ------- */
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
                                <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?w=500&q=80"
                                    alt="Virtual Office – Gedung Perkantoran Modern" loading="lazy">
                                <span class="layanan-card-badge">Best Seller</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Virtual Office</div>
                                <div class="layanan-card-desc">Hemat biaya operasional hingga 90% dengan alamat kantor prestisius tanpa sewa fisik.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 299.000/Bulan*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 2: Serviced Office --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1524758631624-e2822e304c36?w=500&q=80"
                                    alt="Serviced Office – Ruang Kantor Siap Pakai" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Serviced Office</div>
                                <div class="layanan-card-desc">Ruang kantor siap pakai yang ideal untuk tim Anda, dilengkapi fasilitas lengkap.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 4.500.000/Bulan*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 3: Meeting Room --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1606857521015-7f9fcf423740?w=500&q=80"
                                    alt="Meeting Room – Ruang Rapat Profesional" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Meeting Room</div>
                                <div class="layanan-card-desc">Tempat yang cocok untuk melakukan pertemuan penting dengan klien atau mitra bisnis.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 255.000/Jam*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 4: Coworking Space --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1527192491265-7e15c55b1ed2?w=500&q=80"
                                    alt="Coworking Space – Ruang Kerja Bersama" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Coworking Space</div>
                                <div class="layanan-card-desc">Ruang kerja bersama yang fleksibel dan produktif untuk freelancer maupun startup.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 150.000/Hari*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
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
                                <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=500&q=80"
                                    alt="Company Registration – Pendirian Perusahaan" loading="lazy">
                                <span class="layanan-card-badge">Populer</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Company Registration</div>
                                <div class="layanan-card-desc">Proses pendirian badan usaha PT, CV, Firma dengan cepat dan sesuai regulasi yang berlaku.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 2.500.000/Paket*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 2 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1505664124016-161b9eebd6bc?w=500&q=80"
                                    alt="Legal Consulting – Konsultasi Hukum Bisnis" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Legal Consulting</div>
                                <div class="layanan-card-desc">Konsultasi hukum profesional untuk melindungi bisnis Anda dari risiko hukum yang merugikan.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 3 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=500&q=80"
                                    alt="Tax Services – Layanan Pajak" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Tax Services</div>
                                <div class="layanan-card-desc">Pengurusan NPWP, pelaporan SPT Tahunan, dan konsultasi pajak bisnis yang akurat dan aman.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 750.000/Laporan*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 4 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1563986768494-4dee2763ff0f?w=500&q=80"
                                    alt="Business Licensing – Perizinan Usaha" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Business Licensing</div>
                                <div class="layanan-card-desc">Pengurusan NIB, OSS, SIUP, dan berbagai izin usaha lainnya secara cepat dan terpercaya.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 1.200.000/Izin*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
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
                                <img src="https://images.unsplash.com/photo-1569154941061-e231b4732659?w=500&q=80"
                                    alt="Visa Application – Permohonan Visa" loading="lazy">
                                <span class="layanan-card-badge">Terlaris</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Visa Application</div>
                                <div class="layanan-card-desc">Pengurusan visa kunjungan, bisnis, dan tinggal terbatas di Indonesia secara profesional.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 1.500.000/Proses*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 2 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1569982175971-d92b01cf8694?w=500&q=80"
                                    alt="KITAS / KITAP – Izin Tinggal WNA" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">KITAS / KITAP</div>
                                <div class="layanan-card-desc">Pengurusan izin tinggal terbatas dan tetap untuk warga negara asing di Indonesia.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 3.500.000/Proses*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 3 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1596781708892-71089209f984?w=500&q=80"
                                    alt="Passport Assistance – Bantuan Paspor" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Passport Assistance</div>
                                <div class="layanan-card-desc">Bantuan persiapan, pengurusan, dan perpanjangan paspor untuk keperluan perjalanan internasional.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 800.000/Proses*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                    {{-- Card 4 --}}
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=500&q=80"
                                    alt="Immigration Consulting – Konsultasi Imigrasi" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Immigration Consulting</div>
                                <div class="layanan-card-desc">Layanan konsultasi keimigrasian komprehensif untuk WNA yang akan tinggal atau bekerja di Indonesia.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                                <a href="#" class="layanan-card-btn" role="button">Order Now</a>
                            </div>
                        </div>
                    </div>
                </div>{{-- /row --}}
            </div>{{-- /pane-foreign --}}

        </div>{{-- /#layananTabContent --}}
    </div>{{-- /.container --}}
</section>

{{-- =================== JAVASCRIPT LAYER =================== --}}
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
            <p>Pertanyaan yang paling sering diajukan seputar legalitas PT di Indonesia</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Apa itu PT (Perseroan Terbatas)? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">PT (Perseroan Terbatas) adalah badan usaha berbadan hukum yang modalnya terdiri atas saham-saham. Setiap pemegang saham bertanggung jawab sebatas jumlah saham yang dimiliki. PT cocok untuk bisnis skala menengah hingga besar.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa minimal modal untuk mendirikan PT? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Sesuai UU Cipta Kerja, tidak ada ketentuan minimal modal untuk mendirikan PT. Namun, untuk PT biasa disarankan modal dasar minimal Rp 50.000.000 dengan modal disetor minimal 25% dari modal dasar.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa lama proses pendirian PT? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Dengan dokumen lengkap, proses pendirian PT memakan waktu sekitar 7-14 hari kerja, tergantung dari kecepatan pembuatan akta notaris dan pengesahan dari Kemenkumham.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa minimal pemegang saham dalam PT? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">PT minimal didirikan oleh 2 (dua) orang pemegang saham, kecuali PT Perorangan yang hanya memerlukan 1 orang pendiri.</div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                document.querySelectorAll('.faq-item').forEach(faq => {
                    if (faq !== parent) faq.classList.remove('active');
                });
                parent.classList.toggle('active');
            });
        });
    });
</script>
@endsection