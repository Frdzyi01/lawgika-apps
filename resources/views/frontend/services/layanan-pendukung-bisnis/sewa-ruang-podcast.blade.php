@extends('layout.app')
@section('title', 'Sewa Ruang Podcast Studio | Lawgika')
@section('meta_description', 'Sewa studio podcast profesional dengan peralatan lengkap, soundproofing, dan operator. Mulai produksi konten Anda sekarang.')
@section('meta_keywords', 'Sewa Ruang Podcast, Jasa Sewa Ruang Podcast, Konsultan Sewa Ruang Podcast, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


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

    /* Pricing Table */
    .pt-pricing {
        padding: 80px 0;
        background: #fff;
    }

    .pricing-table-container {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        border-radius: 20px;
        border: 1px solid #f0e4e8;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .pricing-table-header {
        display: grid;
        grid-template-columns: 1fr;
        /* Diubah jadi 1 kolom agar lebih fokus */
        background: var(--bg-light);
        border-bottom: 1px solid #f0e4e8;
    }

    .pricing-column {
        padding: 50px 40px;
    }

    .pricing-column-right {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #fff;
        transition: background 0.3s ease;
    }

    .pricing-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .pricing-subtitle {
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .pricing-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .pricing-benefit-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pricing-benefit-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 1.05rem;
        color: #334155;
    }

    .pricing-benefit-list li i {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .pricing-column-right .pricing-benefit-list {
        display: inline-block;
        text-align: left;
        margin-bottom: 40px;
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

    .gallery-grid-podcast {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-top: 40px;
    }

    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        aspect-ratio: 16 / 10;
        cursor: pointer;
    }

    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
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

    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(78, 5, 22, 0) 40%, rgba(78, 5, 22, 0.7) 100%);
        opacity: 0;
        transition: opacity 0.4s ease;
        display: flex;
        align-items: flex-end;
        padding: 30px;
    }

    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }

    .gallery-overlay .overlay-text {
        color: #fff;
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .gallery-item:hover .gallery-overlay .overlay-text {
        transform: translateY(0);
    }

    .gallery-overlay h5 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .gallery-overlay p {
        font-size: 0.9rem;
        opacity: 0.9;
        margin: 0;
    }

    @media (max-width: 768px) {
        .gallery-grid-podcast {
            grid-template-columns: 1fr;
        }
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
        padding: 40px;
        backdrop-filter: blur(5px);
    }

    #lightbox-overlay img {
        max-width: 100%;
        max-height: 100%;
        border-radius: 10px;
        box-shadow: 0 0 50px rgba(0, 0, 0, 0.5);
    }

    #lightbox-close {
        position: absolute;
        top: 30px;
        right: 30px;
        color: #fff;
        font-size: 2.5rem;
        cursor: pointer;
    }
</style>

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.8) 0%, rgba(45, 6, 16, 0.8) 50%, rgba(26, 2, 8, 0.8) 100%), 
                      url('https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem" data-i18n="podcast.hero.badge">Lawgika | Podcast Studio</span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="podcast.hero.title">Sewa Ruang Podcast Profesional</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="podcast.hero.desc">Studio podcast modern dengan peralatan rekaman berkualitas tinggi, soundproofing sempurna, dan tim teknis siap membantu konten terbaik Anda.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="nav.services.podcast_room">Sewa Ruang Podcast</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI PODCAST ROOM ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2 data-i18n="podcast.solusi.title">Ciptakan Konten Berkualitas Tinggi Tanpa Ribet</h2>
                <p data-i18n="podcast.solusi.desc">Tidak perlu pusing memikirkan peralatan mahal and akustik ruangan. Bawa materi Anda, dan biarkan fasilitas studio kami menangani sisi teknis produksi audio &amp; video Anda.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="podcast.solusi.list1">Akustik ruangan standar broadcast</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="podcast.solusi.list2">Mic condenser &amp; mixer profesional</span></li>
                    <li><i class="fa-regular fa-circle-check"></i> <span data-i18n="podcast.solusi.list3">Suasana nyaman, ber-AC &amp; privat</span></li>
                </ul>
                <a href="#pricing" class="btn-outline-brand" data-i18n="podcast.solusi.cta">Lihat Harga Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="{{ asset('buyer-file/assets/img/podcastroom/ruang.png') }}" alt="Podcast Studio" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== GALLERY SECTION PODCAST ===== --}}
<section class="gallery-section">
    <div class="container">
        <div class="section-title text-center">
            <span class="subtitle" data-i18n="nav.services.podcast_room">Podcast Room</span>
            <h2 data-i18n="podcast.gallery.title">Studio Gallery</h2>
            <p data-i18n="podcast.gallery.desc">Ruang podcast profesional dengan fasilitas lengkap untuk kebutuhan konten Anda</p>
        </div>

        <div class="gallery-grid-podcast">
            <div class="gallery-item" onclick="openLightbox('{{ asset('buyer-file/assets/img/podcastroom/ruangpodcast.jpg') }}')">
                <img src="{{ asset('buyer-file/assets/img/podcastroom/ruangpodcast.jpg') }}" alt="Podcast Room 1">
                <div class="gallery-overlay">
                    <div class="overlay-text">
                        <h5>Podcast Studio A</h5>
                        <p>Professional Setup</p>
                    </div>
                </div>
            </div>
            <div class="gallery-item" onclick="openLightbox('{{ asset('buyer-file/assets/img/podcastroom/ruangpodcast2.jpg') }}')">
                <img src="{{ asset('buyer-file/assets/img/podcastroom/ruangpodcast2.jpg') }}" alt="Podcast Room 2">
                <div class="gallery-overlay">
                    <div class="overlay-text">
                        <h5>Podcast Studio B</h5>
                        <p>Comfortable Atmosphere</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox-overlay" onclick="this.style.display='none'">
    <span id="lightbox-close">&times;</span>
    <img id="lightbox-img" src="" alt="Full view">
</div>

<script>
    function openLightbox(src) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox-overlay').style.display = 'flex';
    }
</script>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2 data-i18n="podcast.facility.title">FASILITAS STUDIO KAMI</h2>
            <p data-i18n="podcast.facility.desc">Standar perlengkapan terbaik untuk hasil audio yang jernih dan profesional</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <h4 data-i18n="podcast.facility.item1_title">Pro Audio Gear</h4>
                    <p data-i18n="podcast.facility.item1_desc">Dilengkapi mikrofon kondenser premium, headphone monitor, dan mixer digital standar penyiaran.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h4 data-i18n="podcast.facility.item2_title">Acoustic Treatment</h4>
                    <p data-i18n="podcast.facility.item2_desc">Ruangan dirancang dengan soundproofing optimal untuk mencegah gema dan kebisingan dari luar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4 data-i18n="podcast.facility.item3_title">Lounge &amp; Pantry</h4>
                    <p data-i18n="podcast.facility.item3_desc">Bersantai sejenak sebelum atau sesudah rekaman dengan fasilitas ruang tunggu ber-AC dan free flow air.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE PODCAST ROOM ===== --}}
<section class="pt-pricing" id="pricing" style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle" data-i18n="podcast.pricing.badge">Harga &amp; Paket</span>
            <h2 data-i18n="podcast.pricing.title">Podcast Room</h2>
            <p data-i18n="podcast.pricing.desc">Tarif profesional, fasilitas lengkap — mulai produksi konten Anda hari ini</p>
        </div>

        {{-- ===== CORPORATE TABLE LAYOUT ===== --}}
        <style>
            .pricing-card {
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
                border: 2px solid var(--primary);
                position: relative;
                overflow: hidden;
                transition: transform 0.3s ease;
            }

            .pricing-card:hover {
                transform: translateY(-5px);
            }

            .pricing-header {
                background: var(--primary);
                color: #fff;
                padding: 30px 20px;
                text-align: center;
            }

            .pricing-header h3 {
                font-size: 1.6rem;
                font-weight: 800;
                margin: 0 0 5px;
                color: #fff;
            }

            .pricing-header p {
                font-size: 0.95rem;
                opacity: 0.9;
                margin: 0;
            }

            .pricing-body {
                padding: 30px 24px;
                text-align: center;
            }

            .pricing-price {
                font-size: 2.6rem;
                font-weight: 800;
                color: var(--dark);
                margin-bottom: 5px;
                line-height: 1;
            }

            .pricing-desc {
                color: var(--gray);
                font-size: 0.95rem;
                margin-bottom: 25px;
                font-weight: 500;
            }

            .feature-list {
                list-style: none;
                padding: 0;
                margin: 0 0 30px;
                text-align: left;
            }

            .feature-list li {
                padding: 12px 0;
                border-bottom: 1px solid #f0f0f0;
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 500;
                color: #334155;
            }

            .feature-list li:last-child {
                border-bottom: none;
            }

            .feature-list li i {
                color: var(--primary);
                font-size: 1.1rem;
            }

            .btn-pricing-primary {
                display: block;
                width: 100%;
                text-align: center;
                padding: 16px;
                background: var(--primary);
                border-radius: 50px;
                color: #fff;
                font-weight: 700;
                font-size: 1.05rem;
                text-decoration: none;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 8px 20px rgba(78, 5, 22, 0.2);
            }

            .btn-pricing-primary:hover {
                background: var(--primary-light);
                color: #fff;
                transform: translateY(-2px);
                box-shadow: 0 12px 25px rgba(78, 5, 22, 0.3);
            }

            .short-rental-box {
                background: var(--bg-light);
                border: 1px dashed var(--accent);
                padding: 15px;
                border-radius: 12px;
                margin-bottom: 30px;
                text-align: center;
            }

            .short-rental-box .title {
                font-size: 0.9rem;
                font-weight: 700;
                color: var(--dark);
                margin-bottom: 5px;
            }

            .short-rental-box .desc {
                font-size: 0.85rem;
                color: var(--gray);
                margin: 0;
            }

            /* Premium Podcast Note Wrap */
            .podcast-note-wrap {
                max-width: 500px;
                margin: 22px auto 0;
                background: #fdfdfd;
                border: 1px solid #f0f1f3;
                border-radius: 14px;
                padding: 24px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            }

            .podcast-note-item {
                display: flex;
                align-items: flex-start;
                gap: 16px;
                margin-bottom: 20px;
            }

            .podcast-note-item:last-child {
                margin-bottom: 0;
            }

            .podcast-note-icon {
                font-size: 1.1rem;
                color: var(--primary);
                background: #fff0f1;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }

            .podcast-note-content {
                flex: 1;
            }

            .podcast-note-content h5 {
                font-size: 0.95rem;
                font-weight: 700;
                color: var(--dark);
                margin: 0 0 4px;
            }

            .podcast-note-content p {
                font-size: 0.88rem;
                color: var(--gray);
                line-height: 1.6;
                margin: 0;
            }
        </style>

        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-7">
                <div class="pricing-card">
                    <div class="pricing-header">
                        <h3 data-i18n="podcast.pricing.card.title">PAKET PODCAST ROOM</h3>
                        <p data-i18n="podcast.pricing.card.subtitle">Fasilitas lengkap untuk rekaman Anda</p>
                    </div>
                    <div class="pricing-body">
                        <div class="pricing-price">Rp 700.000</div>
                        <div class="pricing-desc" data-i18n="podcast.pricing.card.desc">
                            Durasi 2 Jam Pertama <br>
                            <span style="color: var(--primary); font-size: 0.85rem;">(+ Rp 300.000 / jam berikutnya)</span>
                        </div>

                        <div class="short-rental-box">
                            <div class="title" data-i18n="podcast.pricing.card.short_title">Butuh Waktu Singkat?</div>
                            <div class="desc" data-i18n="podcast.pricing.card.short_desc">Harga sewa untuk <strong>1 Jam Pertama</strong> hanya <strong>Rp 500.000</strong>.</div>
                        </div>

                        <ul class="feature-list">
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit1">Ruang Podcast Profesional</span></li>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit2">Peralatan Podcast Lengkap</span></li>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit3">Akses Wifi Berkecepatan Tinggi</span></li>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit4">Ruangan Ber-AC yang Nyaman</span></li>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit5">Didampingi Operator Podcast</span></li>
                            <li><i class="fa-solid fa-check"></i> <span data-i18n="podcast.pricing.card.benefit6">Akses Ruang Tunggu &amp; Pantry</span></li>
                        </ul>

                        <button type="button" class="btn-pricing-primary" onclick="openPodcastBookingModal()" data-i18n="podcast.pricing.card.cta">
                            <i class="fa-solid fa-calendar-check me-2"></i> Pilih Jadwal &amp; Pesan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Note Harga --}}
        <div class="podcast-note-wrap">
            <div class="podcast-note-item">
                <div class="podcast-note-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="podcast-note-content">
                    <h5 data-i18n="podcast.note.title1">Aturan Harga</h5>
                    <p data-i18n="podcast.note.desc1">Durasi 2 jam pertama menggunakan harga paket <strong>Rp 700.000</strong>. Setelah melewati 2 jam, dikenakan tambahan <strong>Rp 300.000 / jam</strong> berikutnya.</p>
                </div>
            </div>

            <div class="podcast-note-item">
                <div class="podcast-note-icon">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="podcast-note-content">
                    <h5 data-i18n="podcast.note.title2">Persiapan Sesi</h5>
                    <p data-i18n="podcast.note.desc2">Harap tiba di lokasi <strong>15 menit sebelum</strong> jadwal dimulai untuk persiapan dan pengecekan teknis peralatan studio.</p>
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
            <span class="subtitle" data-i18n="podcast.faq.badge">Bantuan Sentral</span>
            <h2 data-i18n="podcast.faq.title">FAQ terkait Ruang Podcast</h2>
            <p data-i18n="podcast.faq.desc">Pertanyaan yang paling sering diajukan seputar penyewaan studio podcast</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="podcast.faq.q1">Berapa jumlah maksimal orang di dalam studio?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="podcast.faq.a1">Studio kami dirancang optimal untuk 2 hingga 4 orang pembicara sekaligus agar kualitas audio tetap fokus dan tidak *bocor*.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="podcast.faq.q2">Apakah saya perlu membawa *memory card* atau *harddisk* sendiri?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="podcast.faq.a2">Ya, kami sangat menyarankan Anda membawa *storage* sendiri untuk memudahkan pemindahan *file* raw (mentah) langsung setelah sesi rekaman selesai.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question"><span data-i18n="podcast.faq.q3">Apakah sudah termasuk layanan *editing* video/audio?</span> <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer" data-i18n="podcast.faq.a3">Paket sewa standar hanya mencakup penggunaan ruangan dan peralatan. Untuk layanan tambahan *editing* pasca produksi, silakan konsultasikan dengan tim teknis kami di lokasi.</div>
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

{{-- ===== MODAL BOOKING CALENDLY-STYLE (Dengan Logic Podcast) ===== --}}
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
            <h4 id="bmPackageName" data-i18n="podcast.modal.title">Paket Podcast Room</h4>
            <div class="bm-info-item"><i class="fa-regular fa-clock"></i> <span id="bmDuration" data-i18n="podcast.modal.duration_val">2 Jam</span></div>
            <div class="bm-info-item"><i class="fa-solid fa-location-dot"></i> <span data-i18n="podcast.modal.location">Studio Offline (Lawgika Office)</span></div>
            <div style="margin-top:20px;">
                <p style="font-size:0.9rem; color:#64748b; line-height:1.6;" data-i18n="podcast.modal.instruction">
                    Pilih tanggal dan waktu yang tersedia untuk produksi podcast Anda bersama Lawgika.
                </p>
            </div>
        </div>
        <div class="bm-right">
            <button class="bm-close" onclick="closeBookingModal()">&times;</button>
            <div class="bm-header">
                <h3 data-i18n="podcast.modal.header">Pilih tanggal &amp; waktu</h3>
            </div>
            <div class="bm-body">
                <div class="bm-calendar-col">
                    <div class="calendar-nav">
                        <button id="calPrev" onclick="changeMonth(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                        <span id="calMonthYear"></span>
                        <button id="calNext" onclick="changeMonth(1)"><i class="fa-solid fa-chevron-right"></i></button>
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
                    <div style="font-size:0.9rem; font-weight:600; color:#1e1b2b; margin-bottom:15px;" id="timeColTitle"></div>
                    <div id="timeSlotsContainer" style="overflow-y:auto; max-height:250px; padding-right:10px;"></div>
                </div>
            </div>
            <div class="bm-footer">
                <button class="btn-bm-cancel" onclick="closeBookingModal()" data-i18n="podcast.modal.cancel">Batal</button>
                <button class="btn-bm-submit" id="btnReservasi" disabled onclick="submitBooking()" data-i18n="podcast.modal.submit">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JS Logic dari Podcast Rental, disesuaikan untuk Modal
    let selDurasi = null;
    let selPrice = 0;
    let selTanggal = null;
    let selJam = null;

    let curYear, curMonth;
    let bookedSlotsCache = {};

    const SLOTS = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];

    function getCurrentLocale() {
        const localeMap = {
            id: 'id-ID',
            en: 'en-US',
            zh: 'zh-CN'
        };
        const currentLang = window.LwI18n ? window.LwI18n.current() : 'id';
        return localeMap[currentLang] || 'id-ID';
    }

    // === Podcast Dynamic Pricing Formula ===
    function calcPodcastPrice(jam) {
        if (jam <= 0) return 0;
        if (jam === 1) return 500000;
        if (jam === 2) return 700000;
        return 700000 + ((jam - 2) * 300000);
    }

    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function openPodcastBookingModal() {
        openBookingModal(2, 700000);
    }

    function openBookingModal(durasi, price) {
        selDurasi = durasi;
        selPrice = price;

        selTanggal = null;
        selJam = null;
        document.getElementById('bmTimeCol').classList.remove('active');
        document.getElementById('btnReservasi').disabled = true;

        const n = new Date();
        curYear = n.getFullYear();
        curMonth = n.getMonth();
        renderCal();

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

    function changeMonth(d) {
        curMonth += d;
        if (curMonth > 11) {
            curMonth = 0;
            curYear++;
        }
        if (curMonth < 0) {
            curMonth = 11;
            curYear--;
        }
        renderCal();
    }

    function renderCal() {
        const currentLocale = getCurrentLocale();
        const dateObj = new Date(curYear, curMonth, 1);
        document.getElementById('calMonthYear').innerText = dateObj.toLocaleDateString(currentLocale, { month: 'long', year: 'numeric' });
        const calDays = document.getElementById('calDays');
        calDays.innerHTML = '';

        const firstDay = new Date(curYear, curMonth, 1).getDay();
        const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 0; i < firstDay; i++) {
            calDays.innerHTML += `<div></div>`;
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(curYear, curMonth, d);
            const isPast = dateObj < today;
            // Jika mau disable hari minggu/sabtu bisa tambahkan || dateObj.getDay() === 0
            const disabled = isPast;

            const dateStr = `${curYear}-${String(curMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const activeClass = (selTanggal === dateStr) ? 'active' : '';
            const disClass = disabled ? 'disabled' : '';

            if (disabled) {
                calDays.innerHTML += `<div class="calendar-date disabled">${d}</div>`;
            } else {
                calDays.innerHTML += `<div class="calendar-date ${activeClass}" onclick="pilihTgl('${dateStr}', ${d})">${d}</div>`;
            }
        }
    }

    async function pilihTgl(dateStr, dayNum) {
        selTanggal = dateStr;
        selJam = null;
        document.getElementById('btnReservasi').disabled = true;
        renderCal(); // Refresh active state

        const currentLocale = getCurrentLocale();
        const dateObj = new Date(curYear, curMonth, dayNum);
        document.getElementById('timeColTitle').innerText = dateObj.toLocaleDateString(currentLocale, { weekday: 'long', day: 'numeric', month: 'long' });
        document.getElementById('bmTimeCol').classList.add('active');

        const container = document.getElementById('timeSlotsContainer');
        const checkingText = window.LwI18n ? window.LwI18n.t('podcast.modal.checking') : 'Mengecek ketersediaan...';
        const fullText = window.LwI18n ? window.LwI18n.t('podcast.modal.full') : 'Penuh';
        
        container.innerHTML = `<div style="text-align:center;padding:20px;color:#64748b;"><i class="fa-solid fa-spinner fa-spin"></i> ${checkingText}</div>`;

        // Fetch booked slots via AJAX (Sesuai endpoint podcast)
        let booked = [];
        if (bookedSlotsCache[dateStr]) {
            booked = bookedSlotsCache[dateStr];
        } else {
            try {
                const res = await fetch(`/podcast-room/booked-slots?date=${dateStr}`);
                booked = await res.json();
                bookedSlotsCache[dateStr] = booked;
            } catch (e) {
                console.error('Gagal memuat jadwal', e);
            }
        }

        container.innerHTML = '';
        SLOTS.forEach(time => {
            if (booked.includes(time)) {
                container.innerHTML += `<div class="time-slot disabled">${time} (${fullText})</div>`;
            } else {
                container.innerHTML += `<div class="time-slot" onclick="pilihJam('${time}', this)">${time}</div>`;
            }
        });
    }

    function pilihJam(jam, el) {
        if (el.classList.contains('disabled')) return;
        selJam = jam;
        document.querySelectorAll('.time-slot').forEach(n => n.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('btnReservasi').disabled = false;
    }

    function submitBooking() {
        if (!selTanggal || !selJam || !selDurasi) return;

        // Redirect ke endpoint order podcast
        const url = `/podcast-room/order?tanggal=${selTanggal}&jam=${selJam}&durasi=${selDurasi}`;
        window.location.href = url;
    }
</script>

@endsection