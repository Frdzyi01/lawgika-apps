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
                      url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | PT PMA</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Jasa Pendirian PT PMA</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Solusi legalitas untuk perusahaan asing yang ingin berinvestasi dan mendirikan badan usaha di Indonesia. Proses cepat, transparan, dan sesuai regulasi BKPM.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Pendirian PT PMA</li>
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
                <h2>Solusi Legalitas PMA Yang Tepat Untuk Investor Asing</h2>
                <p>PT PMA (Penanaman Modal Asing) adalah solusi ideal bagi investor asing yang ingin mendirikan perusahaan di Indonesia dengan kepemilikan saham asing. Kami membantu proses pendirian yang sesuai dengan aturan BKPM dan Kemenkumham.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Pendampingan khusus investor asing</li>
                    <li><i class="fa-regular fa-circle-check"></i> Pengurusan izin BKPM & Kemenkumham</li>
                    <li><i class="fa-regular fa-circle-check"></i> Konsultasi regulasi & KBLI untuk PMA</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Pilihan Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&auto=format" alt="International Business Meeting" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>MENGAPA MEMILIH KAMI?</h2>
            <p>Dapatkan berbagai keuntungan tambahan saat mendirikan PT PMA bersama kami</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                    <h4>Dukungan Ekspatriat</h4>
                    <p>Pendampingan khusus untuk WNA & ekspatriat dalam proses legalitas.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h4>Konsultasi BKPM</h4>
                    <p>Bantuan pengurusan izin prinsip dan izin usaha dari BKPM.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h4>Kepatuhan Hukum</h4>
                    <p>Jaminan dokumen sesuai dengan regulasi PMA terbaru.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROSES PENDIRIAN PT PMA ===== --}}
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Tahapan Tuntas</span>
            <h2>PROSES PENDIRIAN PT PMA</h2>
            <p>Kami menyederhanakan birokrasi PMA menjadi tahapan yang jelas</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-step-number">1</div>
                <h5>Konsultasi & Persiapan</h5>
                <p>Konsultasi bidang usaha, persiapan dokumen perusahaan asing, dan penentuan struktur kepemilikan saham.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">2</div>
                <h5>Pengajuan Izin Prinsip</h5>
                <p>Pengajuan izin prinsip ke BKPM dan pengecekan nama PT melalui sistem AHU Kemenkumham.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">3</div>
                <h5>Pengesahan & Legalitas</h5>
                <p>Penerbitan Akta Pendirian, SK Kemenkumham, NIB, dan izin usaha lainnya.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN PT PMA ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge">Persiapan Dokumen</span>
            <h2>PERSYARATAN DOKUMEN PT PMA</h2>
            <p>Siapkan berkas berikut, tim kami akan memprosesnya</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-building"></i>
                        </div>
                        <h3>DOKUMEN PERUSAHAAN ASING</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> Akta pendirian perusahaan asing (diterjemahkan)</li>
                        <li><i class="fa-solid fa-circle-check"></i> NPWP perusahaan asing (jika ada)</li>
                        <li><i class="fa-solid fa-circle-check"></i> SK Kemenkumham perusahaan asing</li>
                        <li><i class="fa-solid fa-circle-check"></i> Surat kuasa dari pemegang saham asing</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Konsultasi Dokumen <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-passport"></i>
                        </div>
                        <h3>DOKUMEN PRIBADI</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> Paspor pemegang saham asing (legalisasi)</li>
                        <li><i class="fa-solid fa-circle-check"></i> KTP & NPWP direktur (WNI)</li>
                        <li><i class="fa-solid fa-circle-check"></i> Pas foto direktur & komisaris</li>
                        <li><i class="fa-solid fa-circle-check"></i> Surat domisili sementara (jika WNA)</li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        Upload Dokumen <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE PT PMA (SESUAI FOTO) ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Pilihan Paket PMA</span>
            <h2>Paket Pendirian PT PMA</h2>
            <p>Pilih paket yang sesuai dengan kebutuhan investasi Anda di Indonesia</p>
        </div>

        <div class="row g-4 justify-content-center">
            {{-- PREMIUM --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>PREMIUM</h4>
                    <div class="price">Rp 7.830.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Alamat Bisnis Eksklusif</li>
                        <li class="disabled"><i class="fa-solid fa-minus"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</li>
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
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20PREMIUM%20PT%20PMA" class="btn-pricing">Pilih Paket Premium</a>
                </div>
            </div>

            {{-- EKSKLUSIF (Recommended) --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card featured">
                    <span class="badge">REKOMENDASI</span>
                    <h4>EKSKLUSIF</h4>
                    <div class="price">Rp 9.540.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</li>
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
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20EKSKLUSIF%20PT%20PMA" class="btn-pricing-primary">Pilih Paket Eksklusif</a>
                </div>
            </div>

            {{-- ENTERPRISE --}}
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <h4>ENTERPRISE</h4>
                    <div class="price">Rp 10.440.000</div>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> Pengecekan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Pemesanan Nama PT</li>
                        <li><i class="fa-solid fa-check"></i> Akta Pendirian PT</li>
                        <li><i class="fa-solid fa-check"></i> SK Menkumham</li>
                        <li><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</li>
                        <li><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</li>
                        <li><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</li>
                        <li><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</li>
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
                    <a href="https://wa.me/628123456789?text=Halo%20saya%20tertarik%20Paket%20ENTERPRISE%20PT%20PMA" class="btn-pricing">Pilih Paket Enterprise</a>
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
            <h2>FAQ terkait Pendirian PT PMA</h2>
            <p>Pertanyaan yang paling sering diajukan seputar legalitas PT PMA di Indonesia</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Apa itu PT PMA dan siapa yang bisa mendirikannya? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">PT PMA (Penanaman Modal Asing) adalah perseroan terbatas yang didirikan oleh investor asing atau pihak asing yang ingin menanamkan modal di Indonesia. Pendiri bisa berupa WNA atau perusahaan asing yang bekerja sama dengan WNI sesuai ketentuan BKPM.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa minimal investasi untuk PT PMA? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Sesuai aturan BKPM terbaru, nilai investasi PT PMA minimal Rp 10 Miliar di luar tanah dan bangunan, dengan modal disetor minimal Rp 2.5 Miliar.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Berapa lama proses pendirian PT PMA? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Dengan dokumen lengkap, proses pendirian PT PMA memakan waktu sekitar 2-4 minggu, tergantung pada kompleksitas dan respons dari instansi terkait seperti BKPM dan Kemenkumham.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah WNA bisa menjadi direktur PT PMA? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, WNA diperbolehkan menjadi direktur di PT PMA. Namun wajib memiliki KITAS (izin tinggal terbatas) dan memenuhi ketentuan TKA (Tenaga Kerja Asing).</div>
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