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
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>MENGAPA MEMILIH KAMI?</h2>
            <p>Dapatkan berbagai keuntungan tambahan saat mendirikan perusahaan bersama kami</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <h4>Pembukaan Rekening</h4>
                    <p>Dapatkan gratis pendampingan pembukaan rekening bisnis PT Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-clipboard-list"></i>
                    </div>
                    <h4>Nomor EFIN & Pajak</h4>
                    <p>Terima beres pengurusan permohonan Nomor EFIN dan perpajakan.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <h4>Konsultasi Gratis</h4>
                    <p>Kami akan mendampingi dan memberikan advokasi strategis untuk kebutuhan regulasi PT Anda.</p>
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
            <p>Kami menyederhanakan birokrasi kompleks menjadi 3 tahap</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-step-number">1</div>
                <h5>Persyaratan Pendirian</h5>
                <p>Berdasarkan regulasi resmi, siapkan data KTP, NPWP, dan pengecekan pemesanan nama PT untuk memvalidasi tidak ada duplikasi penamaan dengan korporasi lain.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">2</div>
                <h5>Submission Elektronik</h5>
                <p>Ahli hukum kami merangkum pernyataan pendirian secara digital menggunakan protokol autentik dalam bahasa Indonesia ke server Kemenkumham RI.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">3</div>
                <h5>Pengesahan & Legalitas</h5>
                <p>Kementerian Hukum & HAM menerbitkan Surat Keputusan pengesahan badan hukum, diikuti rilis Nomor Induk Berusaha (NIB) PT Anda siap beroperasi resmi.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Persiapan Cepat</span>
            <h2>PERSYARATAN KELENGKAPAN PT</h2>
            <p>Hanya menyiapkan berkas berikut, biarkan tim kami yang bergerak</p>
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
                        <li><i class="fa-solid fa-circle-check"></i> Nama PT (Minimal 3 kata)</li>
                        <li><i class="fa-solid fa-circle-check"></i> Alamat Lengkap Perusahaan</li>
                        <li><i class="fa-solid fa-circle-check"></i> Bidang Usaha (Kode KBLI)</li>
                        <li><i class="fa-solid fa-circle-check"></i> Struktur Modal Disetor</li>
                        <li><i class="fa-solid fa-circle-check"></i> Susunan Komisaris & Direksi</li>
                        <li><i class="fa-solid fa-circle-check"></i> Susunan Pemegang Saham</li>
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
                        <li><i class="fa-solid fa-circle-check"></i> Scan KTP Direktur / Pendiri</li>
                        <li><i class="fa-solid fa-circle-check"></i> Scan NPWP Direktur Aktif</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pas Foto Pendiri / Direktur</li>
                        <li><i class="fa-solid fa-circle-check"></i> Bukti Alamat Usaha (PBB/Sewa)</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Upload Dokumen <i class="fa-solid fa-arrow-right"></i>
                    </a>
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


@include('layout.partials.layanan-kami')



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