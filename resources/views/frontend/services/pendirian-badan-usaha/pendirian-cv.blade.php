@extends('layout.app')
@section('title', 'Pendirian Cv | Lawgika - Konsultan Legal & Bisnis')
@section('meta_description', 'Layanan Pendirian Cv terbaik dan terpercaya di Indonesia oleh Lawgika.co.id. Proses cepat, legal, dan aman untuk kebutuhan bisnis Anda.')
@section('meta_keywords', 'Pendirian Cv, Jasa Pendirian Cv, Konsultan Pendirian Cv, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


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
        font-size: 1.8rem;
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
                      url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&auto=format');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem" data-i18n="cv.hero_badge">Lawgika | Pendirian CV</span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="cv.hero_title">Jasa Pendirian CV (Commanditaire Vennootschap)</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="cv.hero_desc">Solusi legalitas untuk firma dan usaha bersama. CV adalah badan usaha yang cocok untuk bisnis dengan modal bersama antara sekutu aktif dan sekutu pasif. Proses cepat, transparan, dan terpercaya.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="cv.breadcrumb">Pendirian CV</li>
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
                <h2 data-i18n="cv.sol_title">Solusi Legalitas CV Untuk Bisnis Anda</h2>
                <p data-i18n="cv.sol_desc">CV (Commanditaire Vennootschap) adalah badan usaha yang ideal untuk usaha dengan dua jenis sekutu: sekutu aktif (pengelola) dan sekutu pasif (pemberi modal). Kami membantu pendirian CV secara cepat dan sesuai regulasi.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="cv.sol_list1">Pendirian cepat dengan akta notaris</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="cv.sol_list2">SK Kemenkumham resmi</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="cv.sol_list3">Didampingi konsultan hukum berpengalaman</span></li>
                </ul>
                <a href="#pricing" class="btn-outline-brand" data-i18n="ui.see_packages">Lihat Pilihan Paket →</a>
            </div>
            <div class="col-lg-6">
                <img loading="lazy" src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format" alt="Tim kerja sedang berdiskusi" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2 data-i18n="cv.why_title">MENGAPA MEMILIH KAMI?</h2>
            <p data-i18n="cv.why_desc">Dapatkan berbagai keuntungan tambahan saat mendirikan perusahaan bersama kami</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <h4 data-i18n="cv.why_card1_title">Legalitas Resmi</h4>
                    <p data-i18n="cv.why_card1_desc">Akta pendirian dan SK Kemenkumham yang sah secara hukum.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-handshake"></i>
                    </div>
                    <h4 data-i18n="cv.why_card2_title">Pendampingan Profesional</h4>
                    <p data-i18n="cv.why_card2_desc">Konsultasi gratis dan pendampingan hingga CV Anda berdiri.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-chart-line"></i>
                    </div>
                    <h4 data-i18n="cv.why_card3_title">Skalabilitas Bisnis</h4>
                    <p data-i18n="cv.why_card3_desc">CV dapat naik kelas menjadi PT saat bisnis Anda berkembang.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PROSES PENDIRIAN CV ===== --}}
<section class="process-section">
    <div class="container">
        <div class="section-header">
            <span class="badge" data-i18n="ui.process.badge">Tahapan Tuntas</span>
            <h2 data-i18n="cv.process_title">PROSES PENDIRIAN CV</h2>
            <p data-i18n="cv.process_desc">Kami menyederhanakan birokrasi kompleks menjadi 3 tahap</p>
        </div>
        <div class="process-timeline">
            <div class="process-step">
                <div class="process-step-number">1</div>
                <h5 data-i18n="cv.step1_title">Persiapan Dokumen</h5>
                <p data-i18n="cv.step1_desc">Siapkan KTP, NPWP, dan kesepakatan antara sekutu aktif & pasif untuk mendirikan badan usaha CV.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">2</div>
                <h5 data-i18n="cv.step2_title">Pembuatan Akta Notaris</h5>
                <p data-i18n="cv.step2_desc">Pembuatan akta pendirian CV di hadapan notaris, dibantu oleh para tenaga ahli hukum berpengalaman kami.</p>
            </div>
            <div class="process-step">
                <div class="process-step-number">3</div>
                <h5 data-i18n="cv.step3_title">Pengesahan Kemenkumham</h5>
                <p data-i18n="cv.step3_desc">Pengesahan badan hukum di Kemenkumham dan penerbitan Surat Keputusan (SK), perizinan NIB, dan izin usaha terkait.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== PERSYARATAN DOKUMEN ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge" data-i18n="ui.req.fast_prep">Persiapan Cepat</span>
            <h2 data-i18n="cv.req_title">PERSYARATAN KELENGKAPAN CV</h2>
            <p data-i18n="ui.req.fast_prep_desc">Hanya menyiapkan berkas berikut, biarkan tim kami yang bergerak</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <h3 data-i18n="ui.req_data_title">KELENGKAPAN DATA</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.name_cv">Nama CV (minimal 3 kata)</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.address_company">Alamat lengkap perusahaan</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.kbli">Bidang usaha (Kode KBLI)</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.partners_cv">Susunan sekutu aktif & pasif</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.capital_sharing">Pembagian modal dan keuntungan</span></li>
                    </ul>
                    <a href="#" class="requirement-cta"><span data-i18n="ui.req.consult_data">Konsultasi Data</span> <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-file-pdf"></i>
                        </div>
                        <h3 data-i18n="ui.req_doc_title">KELENGKAPAN DOKUMEN</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.ktp_active_partner">Scan KTP sekutu aktif</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.npwp_active_partner">Scan NPWP sekutu aktif</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.photo_active_partner">Pas foto sekutu aktif</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="ui.req.proof_address_business">Bukti alamat usaha</span></li>
                    </ul>
                    <a href="#" class="requirement-cta"><span data-i18n="ui.req.upload_doc">Upload Dokumen</span> <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle" data-i18n="ui.pricing.transparent_choice">Pilihan Paket Transparan</span>
            <h2 data-i18n="cv.pricing_title">Paket Pendirian CV</h2>
            <p data-i18n="cv.pricing_desc">Rincian harga jujur dan tegas. Anda bayar sesuai apa yang ditunjukkan tanpa hidden markups.</p>
        </div>

        <div class="row g-4 justify-content-center">

            {{-- Izin --}}
            <div class="col-lg-5 col-md-6">
                <div class="pricing-card">
                    <h4 data-i18n="ui.pricing.pkg_basic"> Basic Package</h4>

                    <div class="price">Rp 4.500.000</div>
                    <small class="text-muted d-block mt-2 mb-3" style="font-size:0.75rem;">*Harga belum termasuk PPN 11%</small>

                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.check_name_cv">Pengecekan Nama CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.book_name_cv">Pemesanan Nama CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.deed_cv">Akta Pendirian CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.cert_menkumham">Sertifikat Pendaftaran Menkumham</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.npwp_skt">NPWP & SKT</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.nib">Nomor Induk Berusaha</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.address">Alamat Bisnis Ekslusif</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="ui.pricing.feature.meeting_podcast">Meeting Room (48 Jam) & Podcast Room (12 Jam)</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.wifi">Akses Wifi & Smart TV</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.print">Layanan Print, Scan & Fotocopy</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.mail">Pengelolaan Surat dan Paket</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.notification">Notifikasi Surat dan Paket Masuk</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.domicile">Surat Keterangan Domisili</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.community">Akses Komunitas Business</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.receptionist">Layanan Resepsionis</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.dashboard">Dashboard Login Customer</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="ui.pricing.feature.bank_account">Rekening Perusahaan Bank Mandiri/OCBC/BCA</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.signage">Signage Display</span></li>

                        <li class="disabled"><i class="fa-solid fa-minus"></i> <span data-i18n="vo.pricing.feature.call_handling">Layanan Call Handling</span></li>
                    </ul>

                    <button onclick="goOrder('cv','premium')" class="btn-pricing w-100" data-i18n="ui.pricing.choose_basic">Pilih Basic Package</button>
                </div>
            </div>

            {{-- Bundling --}}
            <div class="col-lg-5 col-md-6">
                <div class="pricing-card featured">
                    <span class="badge" data-i18n="vo.pricing.recommendation">REKOMENDASI</span>

                    <h4 data-i18n="ui.pricing.pkg_business">Business Pack </h4>

                    <div class="price">Rp 8.500.000</div>
                    <small class="text-muted d-block mt-2 mb-3" style="font-size:0.75rem;">*Harga belum termasuk PPN 11%</small>

                    <ul class="feature-list">
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.check_name_cv">Pengecekan Nama CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.book_name_cv">Pemesanan Nama CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.deed_cv">Akta Pendirian CV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.cert_menkumham">Sertifikat Pendaftaran Menkumham</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.npwp_skt">NPWP & SKT</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.nib">Nomor Induk Berusaha</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.address">Alamat Bisnis Ekslusif</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.meeting_podcast">Meeting Room (48 Jam) & Podcast Room (12 Jam)</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.wifi">Akses Wifi & Smart TV</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.print">Layanan Print, Scan & Fotocopy</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.mail">Pengelolaan Surat dan Paket</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.notification">Notifikasi Surat dan Paket Masuk</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.domicile">Surat Keterangan Domisili</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.community">Akses Komunitas Business</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.receptionist">Layanan Resepsionis</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.dashboard">Dashboard Login Customer</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="ui.pricing.feature.bank_account">Rekening Perusahaan Bank Mandiri/OCBC/BCA</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.signage">Signage Display</span></li>
                        <li><i class="fa-solid fa-check"></i> <span data-i18n="vo.pricing.feature.call_handling">Layanan Call Handling</span></li>
                    </ul>

                    <button onclick="goOrder('cv','enterprise')" class="btn-pricing-primary w-100" data-i18n="ui.pricing.choose_business">Pilih Business Package</button>
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
            <span class="subtitle" data-i18n="ui.bantuan_sentral">Bantuan Sentral</span>
            <h2 data-i18n="cv.faq_title">FAQ terkait Pendirian CV</h2>
            <p data-i18n="cv.faq_desc">Pertanyaan yang paling sering diajukan seputar legalitas & pengurusan perusahaan berbentuk CV.</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="cv.faq_q1">Apa perbedaan antara CV dan PT?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer"><span data-i18n="cv.faq_a1">CV (Commanditaire Vennootschap) memiliki dua jenis sekutu: sekutu aktif yang mengelola perusahaan dan sekutu pasif yang hanya menanamkan modal. Sedangkan PT memiliki struktur pemegang saham dan direksi yang lebih formal. CV lebih cocok untuk usaha skala kecil-menengah dengan mitra kerja.</span></div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="cv.faq_q2">Berapa lama proses pendirian CV?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer"><span data-i18n="cv.faq_a2">Dengan dokumen lengkap, proses pendirian CV memakan waktu sekitar 7-14 hari kerja, tergantung dari kecepatan pembuatan akta notaris dan pengesahan dari Kemenkumham.</span></div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="cv.faq_q3">Apakah CV bisa memiliki lebih dari 2 sekutu?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer"><span data-i18n="cv.faq_a3">Ya, CV dapat memiliki lebih dari 2 sekutu. Minimal terdapat 1 sekutu aktif dan 1 sekutu pasif. Semakin banyak sekutu, semakin besar potensi modal yang terkumpul.</span></div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="cv.faq_q4">Apakah CV dikenakan pajak badan?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer"><span data-i18n="cv.faq_a4">Ya, CV sebagai badan usaha wajib memiliki NPWP badan dan melaporkan SPT Tahunan Badan. Namun, perhitungan pajaknya berbeda dengan PT karena CV tidak memiliki pemisahan kekayaan yang seketat PT.</span></div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // ── Universal goOrder via WhatsApp ─────────────────────────────────────
    function goOrder(service, pkg) {
        let serviceName = 'Legalitas Perusahaan';
        switch (service) {
            case 'pt-perorangan':
                serviceName = 'Pendirian PT Perorangan';
                break;
            case 'pendirian-PT':
            case 'pt':
                serviceName = 'Pendirian PT Perseroan';
                break;
            case 'cv':
                serviceName = 'Pendirian CV';
                break;
            case 'yayasan':
                serviceName = 'Pendirian Yayasan';
                break;
            case 'firma':
                serviceName = 'Pendirian Firma';
                break;
            case 'pt-pma':
                serviceName = 'Pendirian PT PMA';
                break;
            default:
                serviceName = service;
        }

        let pkgName = 'Paket Basic';
        if (pkg === 'professional' || pkg === 'enterprise' || pkg === 'business') {
            pkgName = 'Paket Business';
        } else {
            pkgName = 'Paket Basic';
        }

        const message = `Halo Admin Lawgika, saya ingin memesan ${pkgName} untuk ${serviceName}. Mohon informasi lebih lanjut.`;
        const waUrl = 'https://wa.me/6281112088600?text=' + encodeURIComponent(message);
        window.open(waUrl, '_blank');
    }

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