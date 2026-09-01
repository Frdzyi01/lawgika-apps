@extends('layout.app')
@section('title', 'Sewa Meeting Room Profesional & Nyaman | Lawgika')
@section('meta_description', 'Sewa ruang meeting untuk keperluan rapat bisnis, negosiasi, atau presentasi. Dilengkapi dengan fasilitas modern dan Smart TV.')
@section('meta_keywords', 'Sewa Meeting Room, Jasa Sewa Meeting Room, Konsultan Sewa Meeting Room, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


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

    /* ===== PAGE SPECIFIC STYLES ===== */

    /* Solusi Section */
    .pt-solution {
        padding: 80px 0;
        background: #fff;
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
        padding: 14px 36px;
        border: 2px solid var(--primary);
        color: var(--primary);
        border-radius: 999px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.9rem;
    }

    .btn-outline-brand:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 20px rgba(78, 5, 22, 0.2);
    }

    /* Benefits / Why Us */
    .why-us-section {
        padding: 100px 0;
        background: #f8f9fa;
        position: relative;
    }

    .why-us-section .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .why-us-section .section-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 15px;
    }

    .why-us-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.03);
        transition: all 0.3s ease;
        height: 100%;
    }

    .why-us-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    }

    .why-us-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 25px;
        background: rgba(78, 5, 22, 0.05);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .why-us-card:hover .why-us-icon {
        background: var(--primary);
    }

    .why-us-icon i {
        font-size: 1.8rem;
        color: var(--primary);
        transition: all 0.3s ease;
    }

    .why-us-card:hover .why-us-icon i {
        color: #fff;
    }

    .why-us-card h4 {
        font-size: 1.3rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 15px;
    }

    .why-us-card p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.7;
    }

    /* Pricing Table Redesign */
    .pt-pricing {
        padding: 100px 0;
        background: #fcfcfc;
    }

    .pricing-grid {
        display: flex;
        justify-content: center;
        gap: 30px;
        flex-wrap: wrap;
    }

    .pricing-card-modern {
        background: #fff;
        border-radius: 24px;
        padding: 30px 40px 50px 40px;
        width: 100%;
        max-width: 450px;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid #f1f5f9;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    }

    .pricing-card-modern:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 50px -12px rgba(78, 5, 22, 0.12);
    }

    .pricing-card-modern.featured {
        background: linear-gradient(145deg, #4e0516 0%, #2d030d 100%);
        color: #fff;
        border: none;
    }

    .pricing-card-modern.featured .pricing-title,
    .pricing-card-modern.featured .pricing-price,
    .pricing-card-modern.featured .pricing-benefit-list li {
        color: #fff;
    }

    .pricing-card-modern.featured .pricing-subtitle {
        color: var(--accent);
        background: rgba(201, 160, 61, 0.15);
        display: inline-block;
        padding: 4px 12px;
        border-radius: 8px;
    }

    .pricing-badge-popular {
        position: absolute;
        top: -15px;
        right: 30px;
        background: var(--accent);
        color: var(--dark);
        padding: 6px 18px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 10px 20px rgba(201, 160, 61, 0.3);
    }

    .pricing-title {
        font-size: 1.4rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: var(--dark);
    }

    .pricing-subtitle {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--gray);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
        display: block;
    }

    .pricing-price-wrap {
        margin-bottom: 35px;
    }

    .pricing-price {
        font-size: 2.8rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 5px;
        color: var(--primary);
    }

    .pricing-card-modern.featured .pricing-price {
        color: #fff;
    }

    .pricing-benefit-list {
        list-style: none;
        padding: 0;
        margin: 0 0 40px 0;
        flex-grow: 1;
    }

    .pricing-benefit-list li {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 18px;
        font-size: 1rem;
        color: #475569;
        line-height: 1.4;
    }

    .pricing-benefit-list li i {
        color: #10b981;
        margin-top: 4px;
        font-size: 1.1rem;
    }

    .pricing-card-modern.featured .pricing-benefit-list li i {
        color: var(--accent);
    }

    .btn-pricing-modern {
        width: 100%;
        padding: 16px 30px;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        display: block;
        border: 2px solid var(--primary);
        background: transparent;
        color: var(--primary);
    }

    .pricing-card-modern.featured .btn-pricing-modern {
        background: var(--accent);
        border-color: var(--accent);
        color: var(--dark);
    }

    .btn-pricing-modern:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(78, 5, 22, 0.2);
    }

    .pricing-card-modern.featured .btn-pricing-modern:hover {
        background: #fff;
        border-color: #fff;
        color: var(--primary);
        box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
    }

    .btn-terms-outline {
        width: 100%;
        padding: 12px 20px;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.88rem;
        transition: all 0.25s ease;
        text-align: center;
        border: 1.5px dashed var(--primary);
        background: rgba(78, 5, 22, 0.04);
        color: var(--primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-terms-outline:hover {
        background: var(--primary);
        color: #fff;
        border-style: solid;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(78, 5, 22, 0.18);
    }

    /* Modal Syarat & Ketentuan Meeting Room */
    .meeting-terms-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 10500;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .meeting-terms-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    .meeting-terms-modal {
        background: #ffffff;
        border-radius: 20px;
        width: 100%;
        max-width: 650px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
        transform: scale(0.95) translateY(10px);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .meeting-terms-modal-overlay.active .meeting-terms-modal {
        transform: scale(1) translateY(0);
    }

    .mtm-header {
        padding: 20px 24px;
        background: #fdf8f9;
        border-bottom: 1px solid #f1e2e5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .mtm-close {
        background: transparent;
        border: none;
        font-size: 1.8rem;
        line-height: 1;
        color: #94a3b8;
        cursor: pointer;
        transition: color 0.2s;
        padding: 0 4px;
    }

    .mtm-close:hover {
        color: #4e0516;
    }

    .mtm-body {
        padding: 22px 24px;
        overflow-y: auto;
        flex: 1;
    }

    .terms-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .terms-item {
        display: flex;
        gap: 14px;
        padding: 13px 16px;
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        transition: all 0.2s ease;
    }

    .terms-item:hover {
        background: #fdf2f4;
        border-color: #fce7eb;
        transform: translateX(3px);
    }

    .terms-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #4e0516;
        color: #fff;
        font-weight: 700;
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .terms-text {
        font-size: 0.88rem;
        line-height: 1.55;
        color: #334155;
    }

    .terms-text strong {
        color: #1e293b;
        display: block;
        margin-bottom: 2px;
    }

    .terms-text p {
        margin-bottom: 0;
        color: #475569;
    }

    .mtm-footer {
        padding: 16px 24px;
        border-top: 1px solid #f1e2e5;
        background: #fafafa;
        display: flex;
        justify-content: flex-end;
    }

    .btn-mtm-agree {
        background: #4e0516;
        color: #fff;
        border: none;
        padding: 12px 26px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.92rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-mtm-agree:hover {
        background: #6e0c24;
        box-shadow: 0 4px 14px rgba(78, 5, 22, 0.25);
    }

    /* FAQ */
    .pt-faq {
        padding: 80px 0;
        background: var(--bg-light);
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
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 20px;
        letter-spacing: -0.5px;
    }

    .section-title p {
        color: var(--gray);
        font-size: 1.1rem;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Utilities */
    .img-fluid-rounded {
        border-radius: 20px;
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.08);
    }

    /* ===== GALLERY SECTION STYLES ===== */
    .gallery-section {
        padding: 80px 0;
        background: #fff;
    }

    .gallery-grid-meeting {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 40px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        aspect-ratio: 4 / 3;
        cursor: pointer;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .page-title-area {
        overflow: hidden;
    }

    .page-title-area::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(26, 2, 8, 0.4) 0%, rgba(26, 2, 8, 0.8) 100%);
        z-index: 0;
    }

    .page-title-content h1 {
        line-height: 1.1;
        letter-spacing: -1px;
    }

    .btn-pricing {
        border-radius: 999px !important;
        transition: all 0.3s ease;
    }

    .btn-pricing:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .gallery-item:hover img {
        transform: scale(1.05);
    }

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(30, 27, 43, 0.4);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay i {
        color: #fff;
        font-size: 2rem;
        transform: scale(0.5);
        transition: transform 0.3s ease;
    }

    .gallery-item:hover .gallery-overlay i {
        transform: scale(1);
    }

    @media (max-width: 576px) {
        .gallery-grid-meeting {
            grid-template-columns: 1fr;
        }
    }

    /* Meeting Room Slider Custom Styles */
    .gallery-swiper-meeting {
        padding: 10px 4px 20px;
        position: relative;
    }

    .gallery-nav-wrap {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-bottom: 20px;
    }

    .gallery-arrow-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 1.5px solid #e5e7eb;
        background: #fff;
        color: #111827;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
    }

    .gallery-arrow-btn:hover:not(.swiper-button-disabled) {
        border-color: var(--primary);
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(78, 6, 22, 0.2);
    }

    .gallery-arrow-btn.swiper-button-disabled {
        opacity: 0.4;
        cursor: not-allowed;
        filter: grayscale(1);
    }

    /* Lightbox Styles */
    #lightbox-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.9);
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    #lightbox-overlay img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 8px;
    }

    #lightbox-close {
        position: absolute;
        top: 20px;
        right: 20px;
        color: #fff;
        font-size: 2.5rem;
        cursor: pointer;
    }
</style>

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.7) 0%, rgba(45, 6, 16, 0.7) 50%, rgba(26, 2, 8, 0.7) 100%), 
                      url('https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm"
                        style="font-size: 0.85rem" data-i18n="meeting.hero.badge">Lawgika | Meeting Room</span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="meeting.hero.title">Sewa Meeting Room Nyaman & Profesional</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="meeting.hero.desc">Fasilitas ruang
                        meeting yang ideal untuk presentasi, workshop, diskusi tim, hingga pertemuan klien penting.
                        Didukung dengan fasilitas lengkap dan modern.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}"
                                class="text-white text-decoration-none" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="nav.services.meeting_room">Sewa Meeting Room</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI MEETING ROOM ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2 data-i18n="meeting.solusi.title">Ruang Meeting Representatif untuk Bisnis Anda</h2>
                <p data-i18n="meeting.solusi.desc">Mencari ruang untuk pertemuan tim, negosiasi dengan klien, atau presentasi produk? Meeting room kami
                    didesain khusus untuk mendukung produktivitas dan impresi profesional Anda.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="meeting.solusi.list1">Lingkungan kondusif & privat</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="meeting.solusi.list2">Dilengkapi Smart TV & proyektor</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="meeting.solusi.list3">Koneksi WiFi kecepatan tinggi</span></li>
                </ul>
                <a href="#pricing" class="btn-outline-brand" data-i18n="meeting.solusi.cta">Lihat Harga Layanan →</a>
            </div>
            <div class="col-lg-6">
                <img loading="lazy" src="{{ asset('buyer-file/assets/img/meetingroom/ruang.webp') }}"
                    alt="Meeting Room" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== GALLERY SECTION MEETING ===== --}}
<section class="gallery-section">
    <div class="container">
        <div class="section-title text-center">
            <span class="subtitle" data-i18n="nav.services.meeting_room">Meeting Room</span>
            <h2 data-i18n="meeting.gallery.title">Ruang Meeting Kami</h2>
            <p data-i18n="meeting.gallery.desc">Ruang meeting nyaman dan representatif untuk kebutuhan bisnis Anda</p>
        </div>

        {{-- Slider Navigation --}}
        <div class="gallery-nav-wrap">
            <button class="gallery-arrow-btn gallery-prev-meeting" title="Previous" aria-label="Previous">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="gallery-arrow-btn gallery-next-meeting" title="Next" aria-label="Next">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

        {{-- Swiper Slider --}}
        <div class="swiper gallery-swiper-meeting">
            <div class="swiper-wrapper">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="swiper-slide">
                        <div class="gallery-item" onclick="openLightbox('{{ asset('buyer-file/assets/img/meetingroom/ruangmeeting' . ($i > 1 ? $i : '') . '.webp') }}')">
                            <img loading="lazy" src="{{ asset('buyer-file/assets/img/meetingroom/ruangmeeting' . ($i > 1 ? $i : '') . '.webp') }}" alt="Meeting Room {{ $i }}">
                            <div class="gallery-overlay">
                                <i class="fa-solid fa-magnifying-glass-plus"></i>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox-overlay" onclick="this.style.display='none'">
    <span id="lightbox-close">&times;</span>
    <img loading="lazy" id="lightbox-img" src="" alt="Full view">
</div>

<script>
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-overlay').style.display = 'flex';
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.gallery-swiper-meeting', {
                slidesPerView: 1,
                spaceBetween: 20,
                loop: false,
                navigation: {
                    nextEl: '.gallery-next-meeting',
                    prevEl: '.gallery-prev-meeting',
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    }
                }
            });
        }
    });
</script>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2 data-i18n="meeting.facility.title">FASILITAS MEETING ROOM KAMI</h2>
            <p data-i18n="meeting.facility.desc">Dapatkan pengalaman meeting terbaik dengan fasilitas lengkap dari Lawgika</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <h4 data-i18n="meeting.facility.item1_title">Smart Presentation</h4>
                    <p data-i18n="meeting.facility.item1_desc">Dilengkapi dengan Smart TV dan peralatan presentasi modern yang mudah dihubungkan ke berbagai
                        perangkat.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <h4 data-i18n="meeting.facility.item2_title">High Speed Internet</h4>
                    <p data-i18n="meeting.facility.item2_desc">Koneksi WiFi berkecepatan tinggi yang stabil untuk mendukung video conference tanpa kendala.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4 data-i18n="meeting.facility.item3_title">Pantry & Lounge</h4>
                    <p data-i18n="meeting.facility.item3_desc">Akses ke ruang tunggu yang nyaman dan pantry dengan fasilitas free flow air mineral.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE MEETING ROOM ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle" data-i18n="meeting.pricing.badge">Harga Spesial</span>
            <h2 data-i18n="meeting.pricing.title">Paket Sewa Meeting Room</h2>
            <p data-i18n="meeting.pricing.desc">Reservasi sekarang dan dapatkan penawaran terbaik</p>
        </div>

        <div class="pricing-grid">
            {{-- KIRI: Booking Card --}}
            <div class="pricing-card-modern">
                <h3 class="pricing-title" style="margin-top: 0; text-align: center;" data-i18n="meeting.pricing.card1.title">BOOKING MEETING ROOM</h3>
                <div class="pricing-price-wrap" style="text-align: center; margin-bottom: 20px;">
                    <div class="pricing-price" style="font-size: 1.3rem; color: var(--primary);" data-i18n="meeting.pricing.card1.subtitle">Prosedur Reservasi</div>
                </div>
                <ul class="pricing-benefit-list">
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card1.benefit1">Booking selambat-lambatnya 1 hari sebelumnya</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card1.benefit2">Jadwal mengikuti ketersediaan ruangan</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card1.benefit3">Pembatalan mohon diinformasikan minimal 12 jam sebelumnya</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card1.benefit4">Akses fasilitas premium Lawgika Office</span></li>
                </ul>
                <div class="mt-auto d-flex flex-column gap-2">
                    @if($hasBenefit)
                        <button type="button" class="btn-pricing-modern" onclick="openBookingModal('reservasi', '{{ addslashes($activeBenefit->paket ?? 'Paket Badan Usaha') }}', window.LwI18n ? window.LwI18n.t('meeting.modal.duration_val') : '60 mnt')" data-i18n="meeting.pricing.card1.cta">Booking Sekarang</button>
                    @else
                        <button type="button" class="btn-pricing-modern" onclick="showNoBenefitAlert()" data-i18n="meeting.pricing.card1.cta">Booking Sekarang</button>
                    @endif
                    <button type="button" class="btn-terms-outline" onclick="openMeetingTermsModal()" data-i18n="meeting.pricing.terms_btn">
                        <i class="fa-solid fa-file-contract me-1"></i> Syarat & Ketentuan
                    </button>
                </div>
            </div>

            {{-- KANAN: Premium Packet Card --}}
            <div class="pricing-card-modern featured">
                <h3 class="pricing-title" style="margin-top: 0; text-align: center; color: #fff; background-color: initial !important;" data-i18n="meeting.pricing.card2.title">PAKET MEETING ROOM</h3>
                <div class="pricing-price-wrap" style="text-align: center; margin-bottom: 20px;">
                    <div class="pricing-price" style="color: var(--accent); font-size: 2.5rem;">Rp 4.800.000</div>
                    <p class="text-white-50 small mt-1" data-i18n="meeting.pricing.card2.subtitle">Investasi Profesional untuk Bisnis Anda</p>
                </div>
                <ul class="pricing-benefit-list">
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card2.benefit1">Ruang Meeting Nyaman &amp; Profesional</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card2.benefit2">Smart TV &amp; High Speed WiFi</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card2.benefit3">Layanan Print, Scan dan Fotocopy</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card2.benefit4">Free Flow Mineral Water &amp; Pantry</span></li>
                    <li><i class="fa-solid fa-circle-check"></i> <span data-i18n="meeting.pricing.card2.benefit5">Kuota 60 Jam / Tahun (Fleksibel)</span></li>
                </ul>
                <div class="mt-auto">
                    <a href="{{ route('meeting-room.order', ['package' => 'paket']) }}" class="btn-pricing-modern" data-i18n="meeting.pricing.card2.cta">Beli Paket Sekarang</a>
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
            <span class="subtitle" data-i18n="meeting.faq.badge">Bantuan Sentral</span>
            <h2 data-i18n="meeting.faq.title">FAQ terkait Meeting Room</h2>
            <p data-i18n="meeting.faq.desc">Pertanyaan yang paling sering diajukan seputar layanan sewa ruangan</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="meeting.faq.q1">Berapa kapasitas maksimal ruang meeting?</span> <i
                            class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="meeting.faq.a1">Kapasitas standar ruang meeting kami adalah 8-10 orang, sangat cocok untuk
                        diskusi privat, presentasi tertutup, maupun workshop kecil.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="meeting.faq.q2">Apakah saya bisa memesan di luar jam kerja?</span> <i
                            class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="meeting.faq.a2">Ya, pemesanan di luar jam operasional standar dapat dilakukan dengan
                        perjanjian dan konfirmasi sebelumnya. Silakan hubungi tim kami untuk pengaturannya.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="meeting.faq.q3">Apakah boleh membawa makanan dari luar?</span> <i
                            class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="meeting.faq.a3">Boleh, namun kami menyarankan untuk tetap menjaga kebersihan ruangan. Anda
                        juga dapat meminta bantuan tim kami jika membutuhkan layanan catering tambahan.</div>
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

{{-- ===== MODAL BOOKING CALENDLY-STYLE ===== --}}
<style>
    .booking-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .booking-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .booking-modal {
        background: #fff;
        width: 100%;
        max-width: 900px;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.3s ease;
        max-height: 90vh;
    }

    .booking-modal-overlay.active .booking-modal {
        transform: scale(1);
    }

    .bm-left {
        width: 35%;
        padding: 40px;
        background: #fafafa;
        border-right: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
    }

    .bm-left h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #1e1b2b;
    }

    .bm-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .bm-right {
        width: 65%;
        padding: 40px;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .bm-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        line-height: 1;
    }

    .bm-header h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e1b2b;
        margin-bottom: 30px;
    }

    .bm-body {
        display: flex;
        gap: 30px;
        flex-grow: 1;
    }

    .bm-calendar-col {
        flex: 1;
    }

    .bm-time-col {
        width: 180px;
        display: none;
        flex-direction: column;
    }

    .bm-time-col.active {
        display: flex;
    }

    .calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .calendar-nav button {
        background: none;
        border: none;
        cursor: pointer;
        color: #4e0516;
        padding: 5px;
    }

    .calendar-nav span {
        font-weight: 600;
        color: #1e1b2b;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        text-align: center;
    }

    .calendar-day-header {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .calendar-date {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 500;
        color: #1e1b2b;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .calendar-date:hover:not(.disabled) {
        background: #f0e4e8;
        color: #4e0516;
    }

    .calendar-date.active {
        background: #4e0516;
        color: #fff;
    }

    .calendar-date.disabled {
        color: #cbd5e1;
        cursor: not-allowed;
    }

    .time-slot {
        padding: 12px;
        border: 1px solid #4e0516;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        color: #4e0516;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 10px;
        background: #fff;
    }

    .time-slot:hover {
        background: #4e0516;
        color: #fff;
    }

    .time-slot.active {
        background: #4e0516;
        color: #fff;
    }

    .time-slot.disabled {
        border-color: #cbd5e1;
        color: #cbd5e1;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .bm-footer {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        border-top: 1px solid #eaeaea;
        padding-top: 20px;
    }

    .btn-bm-cancel {
        padding: 10px 20px;
        border: none;
        background: none;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-bm-submit {
        padding: 10px 24px;
        border: none;
        background: #4e0516;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .btn-bm-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .booking-modal {
            flex-direction: column;
            max-height: 95vh;
            overflow-y: auto;
        }

        .bm-left {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #eaeaea;
            padding: 25px;
        }

        .bm-right {
            width: 100%;
            padding: 25px;
        }

        .bm-body {
            flex-direction: column;
        }

        .bm-time-col {
            width: 100%;
            margin-top: 20px;
        }
    }
</style>

<div class="booking-modal-overlay" id="bookingModalOverlay">
    <div class="booking-modal">
        <div class="bm-left">
            <h4 id="bmPackageName" data-i18n="meeting.modal.title">Paket Meeting Room</h4>
            <div class="bm-info-item"><i class="fa-regular fa-clock"></i> <span id="bmDuration">60 mnt</span></div>
            <div class="bm-info-item"><i class="fa-solid fa-location-dot"></i> <span data-i18n="meeting.modal.location">Meeting Offline (Lawgika Office)</span></div>
            <div style="margin-top:20px;">
                <p style="font-size:0.9rem; color:#64748b; line-height:1.6;" data-i18n="meeting.modal.instruction">
                    Pilih tanggal dan waktu yang tersedia untuk jadwal meeting room Anda.
                </p>
                <div style="margin-top:14px; padding-top:14px; border-top:1px dashed #e2e8f0;">
                    <a href="javascript:void(0)" onclick="openMeetingTermsModal()" style="font-size:0.84rem; color:var(--primary); font-weight:600; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-file-contract"></i> <span>Lihat Syarat &amp; Ketentuan Reservasi</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="bm-right">
            <button class="bm-close" onclick="closeBookingModal()">&times;</button>
            <div class="bm-header">
                <h3 data-i18n="meeting.modal.header">Pilih tanggal & waktu</h3>
            </div>
            <div class="bm-body">
                <div class="bm-calendar-col">
                    <div class="calendar-nav">
                        <button id="calPrev" onclick="changeMonth(-1)"><i
                                class="fa-solid fa-chevron-left"></i></button>
                        <span id="calMonthYear"></span>
                        <button id="calNext" onclick="changeMonth(1)"><i
                                class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header" data-i18n="calendar.day.sun">Min</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.mon">Sen</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.tue">Sel</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.wed">Rab</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.thu">Kam</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.fri">Jum</div>
                        <div class="calendar-day-header" data-i18n="calendar.day.sat">Sab</div>
                    </div>
                    <div class="calendar-grid" id="calDays"></div>
                </div>
                <div class="bm-time-col" id="bmTimeCol">
                    <div style="font-size:0.9rem; font-weight:600; color:#1e1b2b; margin-bottom:15px;"
                        id="timeColTitle"></div>
                    <div id="timeSlotsContainer" style="overflow-y:auto; max-height:250px; padding-right:10px;"></div>
                </div>
            </div>
            <div class="bm-footer">
                <button class="btn-bm-cancel" onclick="closeBookingModal()" data-i18n="meeting.modal.cancel">Batal</button>
                <button class="btn-bm-submit" id="btnReservasi" disabled onclick="submitBooking()" data-i18n="meeting.modal.submit">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL SYARAT & KETENTUAN SEWA MEETING ROOM ===== --}}
<div class="meeting-terms-modal-overlay" id="meetingTermsModalOverlay" style="display:none;" onclick="if(event.target===this) closeMeetingTermsModal()">
    <div class="meeting-terms-modal">
        <div class="mtm-header">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size: 1.3rem;">📌</span>
                <h4 class="mb-0 fw-bold" style="color: #4e0516; font-size: 1.15rem;">Syarat &amp; Ketentuan Sewa Meeting Room Lawgika</h4>
            </div>
            <button type="button" class="mtm-close" onclick="closeMeetingTermsModal()">&times;</button>
        </div>
        <div class="mtm-body">
            <p class="text-muted small mb-3">Harap membaca syarat dan ketentuan penggunaan ruang meeting di bawah ini sebelum melakukan reservasi:</p>
            <div class="terms-list">
                <div class="terms-item">
                    <div class="terms-num">1</div>
                    <div class="terms-text">
                        <strong>Waktu Reservasi</strong>
                        <p>Reservasi wajib dilakukan dan dikonfirmasi selambat-lambatnya <strong>1 (satu) hari</strong> sebelum jadwal penggunaan ruangan.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">2</div>
                    <div class="terms-text">
                        <strong>Keterlambatan Kedatangan</strong>
                        <p>Keterlambatan kedatangan <strong>tidak menambah</strong> durasi penggunaan ruangan.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">3</div>
                    <div class="terms-text">
                        <strong>Peralatan / Properti Tambahan</strong>
                        <p>Apabila penyewa ingin membawa properti, peralatan, dekorasi, atau kebutuhan tambahan lainnya, <strong>wajib menginformasikannya kepada Lawgika</strong> sebelum hari penggunaan.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">4</div>
                    <div class="terms-text">
                        <strong>Perpanjangan Waktu (Overtime)</strong>
                        <p>Perpanjangan waktu penggunaan (overtime) akan dikenakan <strong>pemotongan kuota meeting</strong>.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">5</div>
                    <div class="terms-text">
                        <strong>Ketentuan Reschedule</strong>
                        <p>Reschedule dapat dilakukan maksimal <strong>1 (satu) kali</strong> dengan pemberitahuan minimal <strong>1 x 24 jam</strong> sebelum jadwal penggunaan dan bergantung pada ketersediaan ruangan.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">6</div>
                    <div class="terms-text">
                        <strong>Tanggung Jawab Fasilitas</strong>
                        <p>Penyewa <strong>bertanggung jawab</strong> atas setiap kerusakan atau kehilangan fasilitas yang disebabkan oleh penyewa maupun pihak yang dibawa oleh penyewa.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">7</div>
                    <div class="terms-text">
                        <strong>Ketertiban &amp; Kepatuhan Hukum</strong>
                        <p>Dilarang menggunakan ruangan untuk kegiatan yang <strong>bertentangan dengan peraturan perundang-undangan</strong>, ketertiban umum, atau norma yang berlaku.</p>
                    </div>
                </div>
                <div class="terms-item">
                    <div class="terms-num">8</div>
                    <div class="terms-text">
                        <strong>Persetujuan Penggunaan</strong>
                        <p>Dengan menggunakan fasilitas ruangan meeting, penyewa dianggap telah <strong>membaca, memahami, dan menyetujui</strong> seluruh syarat dan ketentuan ini.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="mtm-footer">
            <button type="button" class="btn-mtm-agree" onclick="closeMeetingTermsModal()">
                <i class="fa-solid fa-check me-1"></i> Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let selectedPackage = '';
    let selectedDate = null;
    let selectedTime = null;
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    let bookedSlotsCache = {};

    const availableTimes = ['09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

    function getCurrentLocale() {
        const localeMap = {
            id: 'id-ID',
            en: 'en-US',
            zh: 'zh-CN'
        };
        const currentLang = window.LwI18n ? window.LwI18n.current() : 'id';
        return localeMap[currentLang] || 'id-ID';
    }

    function openBookingModal(pkgId, pkgName, duration) {
        selectedPackage = pkgId;
        document.getElementById('bmPackageName').innerText = pkgName;
        document.getElementById('bmDuration').innerText = duration;

        const submitBtn = document.getElementById('btnReservasi');
        if (submitBtn) {
            submitBtn.innerText = (pkgId === 'reservasi') ? 'Lanjutkan Reservasi' : 'Lanjut ke Pembayaran';
        }

        selectedDate = null;
        selectedTime = null;
        document.getElementById('bmTimeCol').classList.remove('active');
        if (submitBtn) submitBtn.disabled = true;

        currentMonth = new Date().getMonth();
        currentYear = new Date().getFullYear();
        renderCalendar();

        const overlay = document.getElementById('bookingModalOverlay');
        overlay.style.display = 'flex';
        // Trigger reflow
        void overlay.offsetWidth;
        overlay.classList.add('active');
    }

    function closeBookingModal() {
        const overlay = document.getElementById('bookingModalOverlay');
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }

    function showNoBenefitAlert() {
        const titleText = window.LwI18n ? window.LwI18n.t('meeting.modal.denied_title') : 'Belum Memiliki Paket / Kuota';
        const bodyText = window.LwI18n ? window.LwI18n.t('meeting.modal.denied_text') : 'Anda belum memiliki Paket Benefit atau kuota Meeting Room yang aktif. Silakan membeli Paket Meeting Room terlebih dahulu untuk melakukan reservasi.';
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: titleText,
                text: bodyText,
                showCancelButton: true,
                confirmButtonText: '<i class="fa-solid fa-cart-shopping me-1"></i> Beli Paket Sekarang',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4e0516',
                cancelButtonColor: '#64748b',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('meeting-room.order', ['package' => 'paket']) }}";
                }
            });
        } else {
            if (confirm(bodyText + '\n\nBeli Paket Sekarang?')) {
                window.location.href = "{{ route('meeting-room.order', ['package' => 'paket']) }}";
            }
        }
    }

    function changeMonth(dir) {
        currentMonth += dir;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar();
    }

    function renderCalendar() {
        const currentLocale = getCurrentLocale();
        const dateObj = new Date(currentYear, currentMonth, 1);
        document.getElementById('calMonthYear').innerText = dateObj.toLocaleDateString(currentLocale, { month: 'long', year: 'numeric' });
        const calDays = document.getElementById('calDays');
        calDays.innerHTML = '';

        const firstDay = new Date(currentYear, currentMonth, 1).getDay();
        const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 0; i < firstDay; i++) {
            calDays.innerHTML += `<div></div>`;
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(currentYear, currentMonth, d);
            const isPast = dateObj < today;
            const isWeekend = dateObj.getDay() === 0 || dateObj.getDay() === 6;
            const disabled = isPast || isWeekend;

            const dateStr = `${currentYear}-${String(currentMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const activeClass = (selectedDate === dateStr) ? 'active' : '';
            const disClass = disabled ? 'disabled' : '';

            if (disabled) {
                calDays.innerHTML += `<div class="calendar-date disabled">${d}</div>`;
            } else {
                calDays.innerHTML +=
                    `<div class="calendar-date ${activeClass}" onclick="selectDate('${dateStr}', ${d})">${d}</div>`;
            }
        }
    }

    async function selectDate(dateStr, dayNum) {
        selectedDate = dateStr;
        selectedTime = null;
        document.getElementById('btnReservasi').disabled = true;
        renderCalendar(); // Refresh active state

        const currentLocale = getCurrentLocale();
        const dateObj = new Date(currentYear, currentMonth, dayNum);
        document.getElementById('timeColTitle').innerText = dateObj.toLocaleDateString(currentLocale, { weekday: 'long', day: 'numeric', month: 'long' });
        document.getElementById('bmTimeCol').classList.add('active');

        const container = document.getElementById('timeSlotsContainer');
        const checkingText = window.LwI18n ? window.LwI18n.t('meeting.modal.checking') : 'Mengecek ketersediaan...';
        const fullText = window.LwI18n ? window.LwI18n.t('meeting.modal.full') : 'Penuh';
        
        container.innerHTML =
            `<div style="text-align:center;padding:20px;color:#64748b;"><i class="fa-solid fa-spinner fa-spin"></i> ${checkingText}</div>`;

        // Fetch booked slots via AJAX
        let booked = [];
        if (bookedSlotsCache[dateStr]) {
            booked = bookedSlotsCache[dateStr];
        } else {
            try {
                const res = await fetch(`{{ route('meeting-room.booked-slots') }}?date=${dateStr}`);
                booked = await res.json();
                bookedSlotsCache[dateStr] = booked;
            } catch (e) {
                console.error('Failed to load slots', e);
            }
        }

        container.innerHTML = '';
        availableTimes.forEach(time => {
            if (booked.includes(time)) {
                container.innerHTML += `<div class="time-slot disabled">${time} (${fullText})</div>`;
            } else {
                container.innerHTML +=
                    `<div class="time-slot" onclick="selectTime('${time}', this)">${time}</div>`;
            }
        });
    }

    function selectTime(time, el) {
        if (el.classList.contains('disabled')) return;
        selectedTime = time;
        document.querySelectorAll('.time-slot').forEach(n => n.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('btnReservasi').disabled = false;
    }

    function submitBooking() {
        if (!selectedDate || !selectedTime) return;

        // Save to session via URL params instead of storage for simpler redirect to checkout
        // Redirect to order page with pre-filled query params
        const url =
            `{{ route('meeting-room.order') }}?tanggal=${selectedDate}&jam=${selectedTime}&package=${selectedPackage}`;
        window.location.href = url;
    }

    // ===== Syarat & Ketentuan Modal Handlers =====
    function openMeetingTermsModal() {
        const overlay = document.getElementById('meetingTermsModalOverlay');
        if (!overlay) return;
        overlay.style.display = 'flex';
        void overlay.offsetWidth;
        overlay.classList.add('active');
    }

    function closeMeetingTermsModal() {
        const overlay = document.getElementById('meetingTermsModalOverlay');
        if (!overlay) return;
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }

    // ===== Auto-open modal jika diakses dari Dashboard Client / link reservasi kuota =====
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('book') === 'true' || urlParams.get('reservasi') === '1') {
            @if($hasBenefit)
                const pkgName = {!! json_encode($activeBenefit->paket ?? 'Paket Badan Usaha') !!};
                openBookingModal('reservasi', pkgName, '60 mnt');
            @else
                showNoBenefitAlert();
            @endif
        }
    });
</script>
@endsection