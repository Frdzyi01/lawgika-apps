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
                      url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Virtual Office</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Layanan Virtual Office Premium</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Miliki alamat bisnis prestisius tanpa perlu menyewa kantor fisik. Hemat biaya operasional hingga 90% dengan layanan Virtual Office dari Lawgika.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Virtual Office</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI VIRTUAL OFFICE ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2>Solusi Kantor Tanpa Sewa Fisik</h2>
                <p>Virtual Office adalah solusi tepat bagi Anda yang ingin memiliki alamat bisnis di kawasan prestisius tanpa harus mengeluarkan biaya sewa kantor yang mahal. Hemat biaya operasional hingga 90%!</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Alamat bisnis di kawasan strategis</li>
                    <li><i class="fa-regular fa-circle-check"></i> Pengelolaan surat & paket profesional</li>
                    <li><i class="fa-regular fa-circle-check"></i> Fasilitas meeting room & coworking space</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Pilihan Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800&auto=format" alt="Virtual Office Workspace" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>MENGAPA MEMILIH VIRTUAL OFFICE KAMI?</h2>
            <p>Dapatkan berbagai keuntungan memiliki Virtual Office bersama Lawgika</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h4>Alamat Prestisius</h4>
                    <p>Alamat di kawasan bisnis strategis yang meningkatkan kredibilitas perusahaan Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <h4>Pengelolaan Surat & Paket</h4>
                    <p>Surat dan paket bisnis Anda dikelola secara profesional dan notifikasi real-time.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-phone"></i>
                    </div>
                    <h4>Layanan Call Handling</h4>
                    <p>Layanan penerimaan telepon dan forwarding dengan nama perusahaan Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROSES LAYANAN ===== --}}
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Mudah & Cepat</span>
            <h2>PROSES AKTIVASI VIRTUAL OFFICE</h2>
            <p>Mulai gunakan Virtual Office dalam hitungan jam</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-step-number">1</div>
                <h5>Pilih Paket</h5>
                <p>Pilih paket Virtual Office yang sesuai dengan kebutuhan bisnis Anda.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">2</div>
                <h5>Isi Data Perusahaan</h5>
                <p>Lengkapi data perusahaan dan dokumen yang diperlukan.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">3</div>
                <h5>Aktif & Gunakan</h5>
                <p>Virtual Office aktif dan siap digunakan untuk bisnis Anda.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Persiapan Mudah</span>
            <h2>PERSYARATAN VIRTUAL OFFICE</h2>
            <p>Siapkan dokumen berikut untuk aktivasi</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h3>UNTUK PERUSAHAAN</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> Akta Pendirian Perusahaan</li>
                        <li><i class="fa-solid fa-circle-check"></i> NPWP Perusahaan</li>
                        <li><i class="fa-solid fa-circle-check"></i> SK Kemenkumham (jika ada)</li>
                        <li><i class="fa-solid fa-circle-check"></i> NIB (jika ada)</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Konsultasi Persyaratan <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h3>UNTUK PERORANGAN</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> KTP Pemilik</li>
                        <li><i class="fa-solid fa-circle-check"></i> NPWP Pribadi</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pas Foto</li>
                        <li><i class="fa-solid fa-circle-check"></i> Nama Usaha / Brand</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Upload Dokumen <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE VIRTUAL OFFICE ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Pilihan Paket Virtual Office</span>
            <h2>Paket Virtual Office</h2>
            <p>Pilih paket yang sesuai dengan kebutuhan bisnis Anda</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- PREMIUM --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>PREMIUM</h4>
                    <div class="price">Rp 2.580.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</li>
                        <li><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</li>
                        <li><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</li>
                        <li><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Resepsionis</li>
                        <li><i class="fa-solid fa-check"></i> Dashboard Login Klien</li>
                        <li><i class="fa-solid fa-check"></i> Storage cloud dokumen</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Fasilitas Ruang Meeting atau podcast</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Akses Wifi</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Signage Display</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Handling</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20PREMIUM%20Virtual%20Office" class="btn-pricing">Pilih Paket Premium</a>
                </div>
            </div>

            {{-- EKSKLUSIF (Recommended) --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <span class="badge">REKOMENDASI</span>
                    <h4>EKSKLUSIF</h4>
                    <div class="price">Rp 5.000.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</li>
                        <li><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</li>
                        <li><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</li>
                        <li><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Resepsionis</li>
                        <li><i class="fa-solid fa-check"></i> Dashboard Login Klien</li>
                        <li><i class="fa-solid fa-check"></i> Storage cloud dokumen</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</li>
                        <li><i class="fa-solid fa-check"></i> Akses Wifi</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li><i class="fa-solid fa-check"></i> Signage Display</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Handling</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20EKSKLUSIF%20Virtual%20Office" class="btn-pricing-primary">Pilih Paket Eksklusif</a>
                </div>
            </div>

            {{-- ENTERPRISE --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>ENTERPRISE</h4>
                    <div class="price">Rp 6.250.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</li>
                        <li><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</li>
                        <li><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</li>
                        <li><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Resepsionis</li>
                        <li><i class="fa-solid fa-check"></i> Dashboard Login Klien</li>
                        <li><i class="fa-solid fa-check"></i> Storage cloud dokumen</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</li>
                        <li><i class="fa-solid fa-check"></i> Akses Wifi</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li><i class="fa-solid fa-check"></i> Signage Display</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Call Handling</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Call Forwarding</li>
                    </ul>
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20ENTERPRISE%20Virtual%20Office" class="btn-pricing">Pilih Paket Enterprise</a>
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
            <h2>FAQ terkait Virtual Office</h2>
            <p>Pertanyaan yang paling sering diajukan seputar layanan Virtual Office</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Apa itu Virtual Office? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Virtual Office adalah layanan yang menyediakan alamat bisnis prestisius, pengelolaan surat dan paket, serta layanan administrasi kantor lainnya tanpa perlu menyewa kantor fisik secara penuh. Cocok untuk startup, UKM, dan bisnis yang ingin menghemat biaya operasional.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah alamat Virtual Office bisa digunakan untuk pendirian PT? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, alamat Virtual Office kami dapat digunakan sebagai alamat domisili untuk pendirian PT, CV, Firma, dan Yayasan. Kami menyediakan Surat Keterangan Domisili yang diperlukan untuk pengurusan legalitas perusahaan.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah saya bisa menggunakan ruang meeting? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, semua paket Virtual Office kami menyediakan akses ke fasilitas ruang meeting dan ruang podcast. Paket Eksklusif dan Enterprise mendapatkan kuota 60 jam per tahun.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah ada biaya tambahan selain harga paket? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Tidak ada biaya tersembunyi. Harga yang tertera sudah termasuk semua layanan yang disebutkan dalam benefit. Biaya pengiriman surat/ paket (jika ada) akan ditagih sesuai biaya riil.</div>
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