@extends('layout.app')
@section('title', 'Jasa Jasa Pembukuan Perpajakan & Konsultan Pajak | Lawgika')
@section('meta_description', 'Layanan Jasa Pembukuan Perpajakan profesional untuk mengurus administrasi dan kepatuhan pajak perusahaan Anda dengan tepat waktu.')
@section('meta_keywords', 'Jasa Pembukuan Perpajakan, Konsultan Pajak, Jasa Pembukuan, Lapor Pajak, Tax Accounting Lawgika')


@section('content')
<style>
    /* Premium Legal Agency Design System */
    :root {
        --law-maroon: #800000;
        --law-maroon-dark: #4a0000;
        --law-maroon-light: #a52a2a;
        --law-gold: #D4AF37;
        --law-gray-bg: #fdfdfd;
        --law-text: #2d3436;
        --law-text-muted: #636e72;
    }


    .section-padding {
        padding: 80px 0;
    }

    /* Section Header */
    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-tag {
        display: inline-block;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
        color: var(--law-maroon);
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--law-text);
        letter-spacing: -1px;
        margin-bottom: 16px;
    }

    .section-header p {
        font-size: 1.1rem;
        color: var(--law-text-muted);
        max-width: 700px;
        margin: 0 auto;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--law-text);
        background-color: var(--law-gray-bg);
    }

    .section-padding {
        padding: 100px 0;
    }

    /* Trust Feature Bar */

    /* Trust Feature Bar */
    .trust-bar {
        background: #fff;
        padding: 40px 0;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.05);
        margin-top: -60px;
        position: relative;
        z-index: 10;
        border-radius: 12px;
    }

    .trust-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 20px;
    }

    .trust-icon {
        width: 50px;
        height: 50px;
        background: #fff1f1;
        color: var(--law-maroon);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 20px;
        transition: transform 0.3s ease;
    }

    .trust-item:hover .trust-icon {
        transform: scale(1.1);
        background: var(--law-maroon);
        color: #fff;
    }

    /* Zig-Zag Sections */
    .zigzag-section {
        overflow: hidden;
    }

    .zigzag-image-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
    }

    .zigzag-image-container img {
        transition: transform 0.8s ease;
    }

    .zigzag-section:hover .zigzag-image-container img {
        transform: scale(1.05);
    }

    .zigzag-text {
        padding: 40px;
    }

    .section-label {
        color: var(--law-maroon);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.85rem;
        margin-bottom: 12px;
        display: block;
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

    /* Pricing Cards - UPDATED FOR ACCOUNTING & TAX SERVICES */
    .pt-pricing {
        padding: 80px 0;
        background: #f8fafc;
        content-visibility: auto;
        contain-intrinsic-size: auto 700px;
    }

    .pricing-card {
        background: #fff;
        border-radius: 20px;
        padding: 40px 30px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        transform: translateZ(0);
        transition: transform 0.15s ease;
    }

    .pricing-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    .pricing-card.featured {
        border: 2px solid #4a0000;
        background: #fff;
    }

    .pricing-card .badge {
        position: absolute;
        top: -12px;
        left: 50%;
        transform: translateX(-50%);
        background: #4a0000;
        color: #fff;
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .pricing-header-icon {
        text-align: center;
        margin-bottom: 20px;
    }

    .pricing-header-icon i {
        font-size: 2.5rem;
        color: #4a0000;
        background: #eff6ff;
        padding: 15px;
        border-radius: 50%;
    }

    .pricing-card h4 {
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
        color: #1e293b;
        font-size: 1.4rem;
    }

    .pricing-subtitle {
        text-align: center;
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .pricing-card .price {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        text-align: center;
        margin-bottom: 25px;
    }

    .price small {
        font-size: 1rem;
        font-weight: 400;
        color: #64748b;
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
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.95rem;
        color: #334155;
    }

    .feature-list li i.fa-check {
        color: #10b981;
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
        border: 2px solid #e2e8f0;
        border-radius: 50px;
        color: #1e293b;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        background: #fff;
    }

    .btn-pricing:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-pricing-primary {
        display: block;
        text-align: center;
        padding: 14px;
        background: #4a0000;
        border-radius: 50px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
        border: none;
    }

    .btn-pricing-primary:hover {
        background: #1d4ed8;
        color: #fff;
    }


    /* Modern Timeline */
    .timeline-container {
        position: relative;
        padding: 60px 0;
    }

    .timeline-line {
        position: absolute;
        top: 85px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #eee;
        z-index: 1;
    }

    .timeline-item {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .timeline-circle {
        width: 50px;
        height: 50px;
        background: #fff;
        border: 2px solid var(--law-maroon);
        color: var(--law-maroon);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .timeline-item:hover .timeline-circle {
        background: var(--law-maroon);
        color: #fff;
        box-shadow: 0 0 0 8px rgba(128, 0, 0, 0.1);
    }

    /* Visual Break Section - Left Aligned & Depth */
    .visual-break-agency {
        padding: 120px 0;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 60%, transparent 100%),
            url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        position: relative;
        overflow: hidden;
    }

    .visual-break-agency::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(37, 99, 235, 0.1) 0%, transparent 70%);
        filter: blur(60px);
        z-index: 1;
    }

    /* CTA Section - Floating Card & Luxury Depth */
    .cta-wrapper-premium {
        background: linear-gradient(135deg, #2d3436 0%, #000000 100%);
        padding: 120px 0;
        position: relative;
        overflow: hidden;
    }

    .cta-glow {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(128, 0, 0, 0.2) 0%, transparent 70%);
        transform: translate(-50%, -50%);
        z-index: 1;
    }

    .cta-card-floating {
        background: #fff;
        border-radius: 40px;
        padding: 80px 60px;
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 2;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .badge-premium {
        background: #fff1f1;
        color: var(--law-maroon);
        padding: 8px 20px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.8rem;
        display: inline-block;
        margin-bottom: 20px;
    }

    .btn-hover-scale {
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .btn-hover-scale:hover {
        transform: scale(1.05);
    }

    /* Animations */
    .fade-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUp 0.8s forwards;
    }

    /* FAQ */
    .pt-faq {
        padding: 80px 0;
        background: #f8fafc;
        content-visibility: auto;
        contain-intrinsic-size: auto 500px;
    }

    .faq-item {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .faq-question {
        padding: 20px 25px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .faq-question i {
        transition: transform 0.2s ease;
        color: #4a0000;
    }

    .faq-answer {
        padding: 0 25px 20px;
        color: #475569;
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

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 991px) {
        .timeline-line {
            display: none;
        }

        .hero-agency {
            height: 70vh;
        }

        .hero-content {
            text-align: center;
            margin: 0 auto;
        }

        .zigzag-text {
            text-align: center;
            padding: 30px 0;
        }

        .trust-bar {
            margin-top: 0;
            border-radius: 0;
        }

        .visual-break-agency {
            text-align: center;
        }

        .cta-card-floating {
            padding: 40px 30px;
        }


    }
</style>

<style>/* Hero Buttons - Solid & Tidak Transparan */
.btn-white {
    background: #ffffff;
    color: #1e1b2b;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

.btn-white:hover {
    background: #f0f0f0;
    color: #4a0000;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.btn-outline-white {
    background: transparent;
    color: #ffffff;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    border: 2px solid #ffffff;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
}

.btn-outline-white:hover {
    background: #ffffff;
    color: #4a0000;
    border-color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

/* Responsive */
@media (max-width: 768px) {
    .btn-white,
    .btn-outline-white {
        padding: 12px 24px;
        font-size: 0.95rem;
    }
}</style>
{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.7) 0%, rgba(45, 6, 16, 0.7) 50%, rgba(26, 2, 8, 0.7) 100%), 
                      url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&q=80&w=1200');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem" data-i18n="jpp.hero_badge">Lawgika | Accounting & Tax</span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="jpp.hero_title">Jasa Pembukuan & Perpajakan</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="jpp.hero_desc">Layanan pembukuan akurat dan pelaporan pajak tepat waktu untuk UMKM, CV, dan PT. Fokus mengembangkan bisnis, serahkan urusan administrasi keuangan pada ahlinya.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="https://wa.me/6281112088600?text=Halo%20Lawgika,%20saya%20ingin%20berkonsultasi%20mengenai%20jasa%20pembukuan%20dan%20perpajakan." class="btn-white">
                            <i class="fa-brands fa-whatsapp me-2"></i><span data-i18n="jpp.btn_consult">Konsultasi Gratis</span>
                        </a>
                        <a href="#services" class="btn-outline-white">
                            <span data-i18n="jpp.btn_services">Lihat Layanan</span> <i class="fa-solid fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="nav.services.pembukuan">Jasa Pembukuan & Perpajakan</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- Trust Bar --}}
<div class="container">
    <div class="trust-bar fade-up" style="animation-delay: 0.2s">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-calculator"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="jpp.trust.item1_title">Akuntan Profesional</h6>
                        <small class="text-muted" data-i18n="jpp.trust.item1_desc">Bersertifikasi & Berpengalaman</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-shield-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="jpp.trust.item2_title">Laporan Akurat</h6>
                        <small class="text-muted" data-i18n="jpp.trust.item2_desc">Sesuai Standar SAK EMKM</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="jpp.trust.item3_title">Tepat Waktu</h6>
                        <small class="text-muted" data-i18n="jpp.trust.item3_desc">Bebas Denda Keterlambatan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="services" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Jasa Pembukuan Profesional">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label" data-i18n="jpp.section1.label">01. Jasa Pembukuan</span>
                    <h2 class="display-5 fw-bold mb-3" data-i18n="jpp.section1.title">Catatan Keuangan Rapi & Akurat</h2>
                    <p class="text-muted mb-4 lead">
                        <span data-i18n="jpp.section1.desc">Kami mencatat setiap transaksi bisnis Anda secara sistematis, menyusun laporan keuangan bulanan yang sesuai standar akuntansi, sehingga Anda selalu tahu kondisi keuangan terkini.</span>
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section1.list1">Input Transaksi Harian/Mingguan</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section1.list2">Rekonsiliasi Bank Bulanan</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section1.list3">Laporan Laba Rugi & Neraca</span></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1554224154-26032ffc0d07?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Jasa Perpajakan">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label" data-i18n="jpp.section2.label">02. Jasa Perpajakan</span>
                    <h2 class="display-5 fw-bold mb-3" data-i18n="jpp.section2.title">Hitung & Lapor Pajak Tanpa Ribet</h2>
                    <p class="text-muted mb-4 lead">
                        <span data-i18n="jpp.section2.desc">Tim kami akan menghitung, menyetor, dan melaporkan kewajiban pajak bulanan maupun tahunan Anda. Dijamin sesuai aturan terbaru Dirjen Pajak.</span>
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section2.list1">Perhitungan PPh 21, 23, 25, Final</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section2.list2">Pelaporan SPT Masa PPN</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i> <span data-i18n="jpp.section2.list3">Penyusunan SPT Tahunan Badan</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== DOKUMEN YANG DIPERLUKAN ===== --}}
<section class="requirements-section">
    <div class="container">
        <div class="section-header">
            <span class="badge" data-i18n="ui.req_badge">Siapkan Dokumen</span>
            <h2 data-i18n="ui.req_title">DOKUMEN YANG DIPERLUKAN</h2>    
            <p data-i18n="ui.req_desc">Hanya menyiapkan berkas berikut untuk memulai kerjasama</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <h3 data-i18n="ui.syarat_data">DATA USAHA</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data1">NPWP Badan / Pribadi</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data2">NIB / SKU / SIUP</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data3">PKP (Jika Pengusaha Kena Pajak)</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data4">Rekening Koran 3 Bulan Terakhir</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data5">Bukti Setor Pajak (SSP) Sebelumnya</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.data6">Username & Password Coretax</span></li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        <span data-i18n="jpp.req.cta1">Konsultasi Data</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="requirement-card">
                    <div class="requirement-header">
                        <div class="requirement-icon">
                            <i class="fa-solid fa-folder-open"></i>
                        </div>
                        <h3 data-i18n="ui.syarat_dokumen">DOKUMEN TRANSAKSI</h3>
                    </div>
                    <ul class="requirement-list">
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc1">Faktur Penjualan / Invoice</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc2">Faktur Pembelian & Biaya Operasional</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc3">Faktur Pajak Masukan & Keluaran</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc4">Bukti Potong PPh (Dari Customer)</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc5">Daftar Aset & Penyusutan</span></li>
                        <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.req.doc6">Data Gaji Karyawan (Jika Ada)</span></li>
                    </ul>
                    <a href="#" class="requirement-cta">
                        <span data-i18n="jpp.req.cta2">Upload Dokumen</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE - JASA PEMBUKUAN & PERPAJAKAN ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="row align-items-center g-5 mb-5 pb-4">
            <div class="col-lg-6">
                <img loading="lazy" src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=800&auto=format" alt="Jasa Pembukuan dan Perpajakan" class="img-fluid-rounded shadow-lg">
            </div>
            <div class="col-lg-6">
                <div class="section-title mb-4">
                    <span class="subtitle" data-i18n="ui.service_info">Informasi Layanan</span>
                    <h2 class="mb-3 text-start" data-i18n="jpp.pricing_title">Solusi Pembukuan & Pajak Terpercaya</h2>
                    <p class="ms-0 text-start"><span data-i18n="jpp.pricing_desc">Kelola administrasi keuangan dan kewajiban perpajakan Anda dengan lebih efisien bersama Lawgika. Kami hadir untuk memastikan bisnis Anda patuh hukum dan memiliki laporan keuangan yang akurat.</span></p>
                </div>
                
                <h5 class="fw-bold mb-3" style="color: var(--primary);" data-i18n="jpp.services_header">Layanan Yang Anda Dapatkan:</h5>
                <ul class="solution-list mb-4">
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.services.item1">Penyusunan Laporan Keuangan (Neraca & Laba Rugi)</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.services.item2">Perhitungan & Pelaporan SPT Masa (PPh & PPN)</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.services.item3">Review Kepatuhan Pajak (Tax Compliance)</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.services.item4">Konsultasi Akuntansi & Perpajakan Terpadu</span></li>
                </ul>
                
                <a href="https://wa.me/6281112088600?text=Halo%20Lawgika,%20saya%20ingin%20berkonsultasi%20mengenai%20jasa%20pembukuan%20dan%20perpajakan." 
                   target="_blank" class="btn-pricing-primary px-5 d-inline-block shadow-sm">
                    <span data-i18n="jpp.btn_offer">Minta Penawaran Via WhatsApp</span> <i class="fa-brands fa-whatsapp ms-2"></i>
                </a>
            </div>
        </div>

        <hr class="my-5 opacity-0">

        {{-- ROW 2: JASA PAJAK (GAMBAR KANAN, TEKS KIRI) --}}
        <div class="row align-items-center g-5 mb-5 pb-4">
            <div class="col-lg-6 order-lg-2">
                <img loading="lazy" src="https://images.unsplash.com/photo-1586281380349-632531db7ed4?w=800&auto=format" alt="Jasa Perpajakan Profesional" class="img-fluid-rounded shadow-lg">
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="section-title mb-4">
                    <span class="subtitle" data-i18n="ui.tax_compliance">Kepatuhan Pajak</span>
                    <h2 class="mb-3" data-i18n="jpp.why_title">Jasa Perpajakan Profesional</h2>
                    <p class="ms-0 text-start"><span data-i18n="jpp.why_desc">Maksimalkan efisiensi fiskal bisnis Anda dengan layanan perpajakan yang komprehensif. Kami membantu Anda menavigasi regulasi pajak yang kompleks agar bisnis tetap patuh dan aman dari risiko sanksi.</span></p>
                </div>
                
                <h5 class="fw-bold mb-3" style="color: var(--primary);" data-i18n="jpp.tax_advantages_header">Keunggulan Layanan Pajak:</h5>
                <ul class="solution-list mb-4">
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.tax_advantages.item1">Perencanaan Pajak Strategis (Tax Planning)</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.tax_advantages.item2">Pengurusan PKP & Administrasi NPWP</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.tax_advantages.item3">Pendampingan Audit & Sengketa Pajak</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="jpp.tax_advantages.item4">Pelaporan SPT Tahunan Badan & Pribadi</span></li>
                </ul>
                
                <a href="https://wa.me/6281112088600?text=Halo%20Lawgika,%20saya%20ingin%20berkonsultasi%20mengenai%20jasa%20perpajakan." 
                   target="_blank" class="btn-pricing-primary px-5 d-inline-block shadow-sm">
                    <span data-i18n="jpp.btn_tax_consult">Konsultasi Pajak Via WhatsApp</span> <i class="fa-brands fa-whatsapp ms-2"></i>
                </a>
            </div>
        </div>

     
    </div>
</section>





{{-- ===== KEUNTUNGAN MENGGUNAKAN JASA LAYANAN PAJAK & PEMBUKUAN ===== --}}
<section class="benefits-section">
    <div class="container">
        <div class="section-header text-center mb-5">
            <span class="section-tag" data-i18n="ui.why_us_badge_normal">Mengapa Memilih Kami?</span>
            <p data-i18n="jpp.why_us_desc">Kami berkomitmen memberikan layanan terbaik untuk memastikan bisnis Anda berjalan lancar dan patuh hukum</p>
        </div>
        <div class="row g-4">
            {{-- Benefit 1 --}}
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h4 data-i18n="jpp.benefit.title1">Tim Profesional</h4>
                    <p data-i18n="jpp.benefit.desc1">Pembukuan dan perpajakan perusahaan Anda dikelola oleh tim profesional bersertifikasi dan berpengalaman.</p>
                </div>
            </div>
            {{-- Benefit 2 --}}
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h4 data-i18n="jpp.benefit.title2">Kepatuhan Pajak Terjamin</h4>
                    <p data-i18n="jpp.benefit.desc2">Kami pastikan setiap pelaporan sesuai regulasi terbaru, menghindarkan bisnis Anda dari sanksi dan denda.</p>
                </div>
            </div>
            {{-- Benefit 3 --}}
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <h4 data-i18n="jpp.benefit.title3">Hemat Waktu & Biaya</h4>
                    <p data-i18n="jpp.benefit.desc3">Fokus pada pengembangan bisnis, serahkan urusan administrasi keuangan yang rumit kepada kami.</p>
                </div>
            </div>
            {{-- Benefit 4 --}}
            <div class="col-lg-3 col-md-6">
                <div class="benefit-card">
                    <div class="benefit-icon">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <h4 data-i18n="jpp.benefit.title4">Assessment Tepat</h4>
                    <p data-i18n="jpp.benefit.desc4">Kami menyediakan assessment gratis agar Anda mendapatkan paket layanan yang tepat sesuai kebutuhan perusahaan.</p>
                </div>
            </div>
        </div>
        <div class="text-center mt-5">
            <a href="https://wa.me/6281112088600?text=Halo%20Lawgika,%20saya%20ingin%20berkonsultasi%20mengenai%20jasa%20pembukuan%20dan%20perpajakan." class="btn-benefit-assessment">
                <i class="fa-brands fa-whatsapp me-2"></i> <span data-i18n="jpp.btn_assessment">Dapatkan Assessment Gratis</span>
            </a>
        </div>
    </div>
</section>

<style>/* ===== BENEFITS SECTION ===== */
.benefits-section {
    padding: 80px 0;
    background: #fff;
    position: relative;
}

.benefit-card {
    background: #fff;
    padding: 30px 25px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    border: 1px solid #f0e4e8;
    height: 100%;
    transition: all 0.3s ease;
    text-align: center;
}

.benefit-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(74, 0, 0, 0.08);
    border-color: #4a0000;
}

.benefit-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
    color: #4a0000;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    font-size: 28px;
    margin: 0 auto 20px;
    transition: all 0.3s ease;
}

.benefit-card:hover .benefit-icon {
    background: #4a0000;
    color: #fff;
    border-radius: 50%;
}

.benefit-card h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e1b2b;
    margin-bottom: 12px;
}

.benefit-card p {
    font-size: 0.9rem;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 0;
}

.btn-benefit-assessment {
    display: inline-flex;
    align-items: center;
    background: #4a0000;
    color: #fff;
    padding: 14px 32px;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 8px 20px rgba(74, 0, 0, 0.2);
}

.btn-benefit-assessment:hover {
    background: #2d0000;
    color: #fff;
    transform: scale(1.05);
    box-shadow: 0 12px 30px rgba(74, 0, 0, 0.3);
}

@media (max-width: 991px) {
    .benefits-section {
        padding: 60px 0;
    }
}

.btn-minta-penawaran {
    display: inline-flex;
    align-items: center;
    background: linear-gradient(135deg, #4a0000 0%, #800000 100%);
    color: #fff;
    padding: 18px 45px;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
    box-shadow: 0 10px 25px rgba(128, 0, 0, 0.25);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-minta-penawaran:hover {
    background: linear-gradient(135deg, #800000 0%, #4a0000 100%);
    color: #fff;
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 35px rgba(128, 0, 0, 0.35);
}
</style>


{{-- ===== FAQ SECTION ===== --}}
<section class="pt-faq">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle" data-i18n="jpp.faq_subtitle">Bantuan Sentral</span>
            <h2 data-i18n="jpp.faq_title">FAQ Seputar Pembukuan & Pajak</h2>
            <p data-i18n="jpp.faq_desc">Pertanyaan yang paling sering diajukan seputar jasa akuntansi dan perpajakan</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q1">Apakah jasa ini termasuk laporan keuangan full set?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a1">
                        Ya, tergantung paket yang dipilih. Untuk paket Professional dan Enterprise, Anda akan menerima Laporan Posisi Keuangan (Neraca), Laporan Laba Rugi, Laporan Perubahan Ekuitas, dan Laporan Arus Kas sesuai standar SAK EMKM atau SAK Umum.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q2">Bagaimana cara menyerahkan data transaksi?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a2">
                        Data dapat diserahkan melalui Google Drive, Dropbox, atau email dalam bentuk scan/foto bukti transaksi dan rekening koran. Untuk paket Enterprise, tim kami akan melakukan kunjungan rutin untuk mengambil dokumen fisik jika diperlukan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q3">Apakah bisa membantu jika saya telat lapor pajak?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a3">
                        Tentu. Kami dapat membantu melakukan pembetulan SPT atau pelaporan SPT masa/tahunan yang terlambat. Namun, perlu diingat bahwa sanksi administrasi (denda) keterlambatan tetap menjadi tanggung jawab wajib pajak sesuai ketentuan UU KUP.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q4">Berapa lama proses pembuatan laporan bulanan?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a4">
                        Laporan keuangan bulanan akan kami selesaikan maksimal tanggal 15 bulan berikutnya, asalkan seluruh data transaksi sudah kami terima lengkap paling lambat tanggal 5.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q5">Apakah ada biaya tambahan jika transaksi saya lebih banyak?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a5">
                        Ya, untuk paket Basic dan Professional, jika volume transaksi melebihi kuota bulanan, akan dikenakan biaya tambahan per transaksi. Detail biaya dapat didiskusikan saat kontrak. Paket Enterprise bebas kuota transaksi.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="jpp.faq.q6">Apakah layanan ini termasuk konsultasi perencanaan pajak?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="jpp.faq.a6">
                        Ya, terutama untuk paket Professional dan Enterprise. Kami akan memberikan saran legal untuk efisiensi pajak bisnis Anda sesuai dengan peraturan perpajakan yang berlaku, sehingga Anda dapat menghemat pajak secara sah.
                    </div>
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