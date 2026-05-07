@extends('layout.app')

@section('content')

<style>
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Podcast Studio</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Sewa Ruang Podcast Profesional</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Studio podcast modern dengan peralatan rekaman berkualitas tinggi, soundproofing sempurna, dan tim teknis siap membantu konten terbaik Anda.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Sewa Ruang Podcast</li>
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
                <h2>Ciptakan Konten Berkualitas Tinggi Tanpa Ribet</h2>
                <p>Tidak perlu pusing memikirkan peralatan mahal dan akustik ruangan. Bawa materi Anda, dan biarkan fasilitas studio kami menangani sisi teknis produksi audio & video Anda.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Akustik ruangan standar broadcast</li>
                    <li><i class="fa-regular fa-circle-check"></i> Mic condenser & mixer profesional</li>
                    <li><i class="fa-regular fa-circle-check"></i> Suasana nyaman, ber-AC & privat</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Harga Paket →</a>
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
            <span class="subtitle">Podcast Room</span>
            <h2>Studio Gallery</h2>
            <p>Ruang podcast profesional dengan fasilitas lengkap untuk kebutuhan konten Anda</p>
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
            <h2>FASILITAS STUDIO KAMI</h2>
            <p>Standar perlengkapan terbaik untuk hasil audio yang jernih dan profesional</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <h4>Pro Audio Gear</h4>
                    <p>Dilengkapi mikrofon kondenser premium, headphone monitor, dan mixer digital standar penyiaran.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h4>Acoustic Treatment</h4>
                    <p>Ruangan dirancang dengan soundproofing optimal untuk mencegah gema dan kebisingan dari luar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4>Lounge & Pantry</h4>
                    <p>Bersantai sejenak sebelum atau sesudah rekaman dengan fasilitas ruang tunggu ber-AC dan free flow air.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE PODCAST ROOM ===== --}}
<section class="pt-pricing" id="pricing" style="padding: 80px 0; background: #fff;">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Harga & Paket</span>
            <h2>Podcast Room</h2>
            <p>Tarif profesional, fasilitas lengkap — mulai produksi konten Anda hari ini</p>
        </div>

        {{-- ===== CORPORATE TABLE LAYOUT ===== --}}
        <style>
            /* Podcast Pricing Table */
            .podcast-pricing-wrap {
                display: grid;
                grid-template-columns: 1fr 1fr;
                border: 2px solid #1a1a1a;
                font-family: 'Inter', sans-serif;
            }

            @media (max-width: 768px) {
                .podcast-pricing-wrap {
                    grid-template-columns: 1fr;
                }
                .podcast-col-right {
                    border-left: none !important;
                    border-top: 2px solid #1a1a1a;
                }
            }

            /* Left Column */
            .podcast-col-left {
                padding: 0;
            }

            .podcast-col-left-header {
                padding: 16px 20px;
                border-bottom: 2px solid #1a1a1a;
                font-size: 1.05rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #1a1a1a;
            }

            .podcast-price-row {
                display: flex;
                align-items: center;
                padding: 12px 20px;
                border-bottom: 1px solid #d1d5db;
                font-size: 0.88rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.3px;
                color: #1a1a1a;
                min-height: 44px;
            }

            .podcast-benefit-header {
                padding: 12px 20px;
                border-bottom: 1px solid #d1d5db;
                font-size: 0.88rem;
                font-weight: 700;
                text-transform: uppercase;
                color: #1a1a1a;
                min-height: 44px;
                display: flex;
                align-items: center;
            }

            .podcast-benefit-item {
                padding: 11px 20px 11px 48px;
                border-bottom: 1px solid #e5e7eb;
                font-size: 0.85rem;
                font-weight: 500;
                text-transform: uppercase;
                letter-spacing: 0.2px;
                color: #374151;
                min-height: 42px;
                display: flex;
                align-items: center;
            }

            .podcast-benefit-item:last-child {
                border-bottom: none;
            }

            /* Right Column */
            .podcast-col-right {
                border-left: 2px solid #1a1a1a;
                padding: 0;
            }

            .podcast-col-right-header {
                padding: 16px 20px;
                border-bottom: 2px solid #1a1a1a;
                font-size: 1.05rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                color: #1a1a1a;
                text-align: center;
            }

            .podcast-col-right-sub {
                padding: 10px 20px;
                border-bottom: 2px solid #1a1a1a;
                font-size: 0.92rem;
                font-weight: 700;
                text-transform: uppercase;
                text-align: center;
                color: #1a1a1a;
                background: #f9f9f9;
            }

            .podcast-price-cell {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 12px 20px;
                border-bottom: 1px solid #d1d5db;
                min-height: 44px;
            }

            .podcast-price-cell .rp-label {
                font-weight: 700;
                font-size: 0.9rem;
                color: #1a1a1a;
                flex-shrink: 0;
            }

            .podcast-price-cell .rp-amount {
                font-weight: 800;
                font-size: 0.95rem;
                color: #1a1a1a;
                text-align: right;
            }

            .podcast-empty-cell {
                min-height: 44px;
                border-bottom: 1px solid #d1d5db;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .podcast-empty-cell:last-child {
                border-bottom: none;
            }

            .podcast-check {
                font-size: 1.1rem;
                font-weight: 900;
                color: #1a1a1a;
            }

            .podcast-cta-wrap {
                padding: 24px 20px 20px;
                border-top: 2px solid #1a1a1a;
                display: flex;
                justify-content: center;
            }

            .podcast-cta-btn {
                display: inline-block;
                padding: 13px 32px;
                background: #1a1a1a;
                color: #fff;
                font-size: 0.92rem;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                border: 2px solid #1a1a1a;
                cursor: pointer;
                transition: all 0.2s ease;
                border-radius: 0;
                text-decoration: none;
                text-align: center;
            }

            .podcast-cta-btn:hover {
                background: #4e0616;
                border-color: #4e0616;
                color: #fff;
                transform: translateY(-2px);
            }

            .podcast-note-wrap {
                margin-top: 24px;
                border: 1px solid #e5e7eb;
                background: #fafafa;
                padding: 16px 20px;
            }

            .podcast-note-wrap p {
                margin: 0 0 6px;
                font-size: 0.85rem;
                color: #374151;
                line-height: 1.5;
            }

            .podcast-note-wrap p:last-child {
                margin-bottom: 0;
            }

            .podcast-note-wrap strong {
                color: #1a1a1a;
            }
        </style>

        <div class="podcast-pricing-wrap">
            {{-- KOLOM KIRI --}}
            <div class="podcast-col-left">
                <div class="podcast-col-left-header">Podcast Room</div>

                <div class="podcast-price-row">Harga 1 Jam Pertama</div>
                <div class="podcast-price-row">Harga 2 Jam Pertama</div>
                <div class="podcast-price-row">Harga Per Jam Selanjutnya</div>

                <div class="podcast-benefit-header">Benefit</div>

                <div class="podcast-benefit-item">Ruang Podcast Profesional</div>
                <div class="podcast-benefit-item">Peralatan Podcast Lengkap</div>
                <div class="podcast-benefit-item">Akses Wifi</div>
                <div class="podcast-benefit-item">Ruangan Ber-AC</div>
                <div class="podcast-benefit-item">Operator Podcast</div>
                <div class="podcast-benefit-item">Ruang Tunggu &amp; Pantry</div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="podcast-col-right">
                <div class="podcast-col-right-header">Paket Podcast Room</div>
                <div class="podcast-col-right-sub">Durasi 2 Jam</div>

                {{-- Baris Harga --}}
                <div class="podcast-price-cell">
                    <span class="rp-label">Rp</span>
                    <span class="rp-amount">500.000</span>
                </div>
                <div class="podcast-price-cell">
                    <span class="rp-label">Rp</span>
                    <span class="rp-amount">800.000</span>
                </div>
                <div class="podcast-price-cell">
                    <span class="rp-label">Rp</span>
                    <span class="rp-amount">300.000</span>
                </div>

                {{-- Spacer: benefit header --}}
                <div class="podcast-empty-cell" style="min-height:44px; border-bottom:1px solid #d1d5db;"></div>

                {{-- Checklist Benefit --}}
                <div class="podcast-empty-cell"><span class="podcast-check">✓</span></div>
                <div class="podcast-empty-cell"><span class="podcast-check">✓</span></div>
                <div class="podcast-empty-cell"><span class="podcast-check">✓</span></div>
                <div class="podcast-empty-cell"><span class="podcast-check">✓</span></div>
                <div class="podcast-empty-cell"><span class="podcast-check">✓</span></div>
                <div class="podcast-empty-cell" style="border-bottom:none;"><span class="podcast-check">✓</span></div>

                {{-- CTA Button --}}
                <div class="podcast-cta-wrap">
                    <button type="button" class="podcast-cta-btn"
                        onclick="openPodcastBookingModal()">
                        <i class="fa-solid fa-calendar-check me-2"></i>Pilih Jadwal &amp; Pesan
                    </button>
                </div>
            </div>
        </div>

        {{-- Note Harga --}}
        <div class="podcast-note-wrap">
            <p>📌 <strong>Aturan Harga:</strong> Durasi 2 jam pertama menggunakan harga paket <strong>Rp 800.000</strong>.
                Setelah melewati 2 jam, akan dikenakan tambahan <strong>Rp 300.000 per jam</strong>.</p>
            <p>⏰ <strong>Harap datang 15 menit sebelum jadwal dimulai</strong> untuk persiapan dan pengecekan peralatan.</p>
        </div>

    </div>
</section>


@include('layout.partials.layanan-kami')

{{-- ===== FAQ SECTION ===== --}}
<section class="pt-faq">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Bantuan Sentral</span>
            <h2>FAQ terkait Ruang Podcast</h2>
            <p>Pertanyaan yang paling sering diajukan seputar penyewaan studio podcast</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Berapa jumlah maksimal orang di dalam studio? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Studio kami dirancang optimal untuk 2 hingga 4 orang pembicara sekaligus agar kualitas audio tetap fokus dan tidak *bocor*.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah saya perlu membawa *memory card* atau *harddisk* sendiri? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, kami sangat menyarankan Anda membawa *storage* sendiri untuk memudahkan pemindahan *file* raw (mentah) langsung setelah sesi rekaman selesai.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah sudah termasuk layanan *editing* video/audio? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Paket sewa standar hanya mencakup penggunaan ruangan dan peralatan. Untuk layanan tambahan *editing* pasca produksi, silakan konsultasikan dengan tim teknis kami di lokasi.</div>
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
            <h4 id="bmPackageName">Paket Podcast Room</h4>
            <div class="bm-info-item"><i class="fa-regular fa-clock"></i> <span id="bmDuration">2 Jam</span></div>
            <div class="bm-info-item"><i class="fa-solid fa-location-dot"></i> <span>Studio Offline (Lawgika Office)</span></div>
            <div style="margin-top:20px;">
                <p style="font-size:0.9rem; color:#64748b; line-height:1.6;">
                    Pilih tanggal dan waktu yang tersedia untuk produksi podcast Anda bersama Lawgika.
                </p>
            </div>
        </div>
        <div class="bm-right">
            <button class="bm-close" onclick="closeBookingModal()">&times;</button>
            <div class="bm-header">
                <h3>Pilih tanggal & waktu</h3>
            </div>
            <div class="bm-body">
                <div class="bm-calendar-col">
                    <div class="calendar-nav">
                        <button id="calPrev" onclick="changeMonth(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                        <span id="calMonthYear">Apr 2026</span>
                        <button id="calNext" onclick="changeMonth(1)"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">Min</div>
                        <div class="calendar-day-header">Sen</div>
                        <div class="calendar-day-header">Sel</div>
                        <div class="calendar-day-header">Rab</div>
                        <div class="calendar-day-header">Kam</div>
                        <div class="calendar-day-header">Jum</div>
                        <div class="calendar-day-header">Sab</div>
                    </div>
                    <div class="calendar-grid" id="calDays"></div>
                </div>
                <div class="bm-time-col" id="bmTimeCol">
                    <div style="font-size:0.9rem; font-weight:600; color:#1e1b2b; margin-bottom:15px;" id="timeColTitle"></div>
                    <div id="timeSlotsContainer" style="overflow-y:auto; max-height:250px; padding-right:10px;"></div>
                </div>
            </div>
            <div class="bm-footer">
                <button class="btn-bm-cancel" onclick="closeBookingModal()">Batal</button>
                <button class="btn-bm-submit" id="btnReservasi" disabled onclick="submitBooking()">Lanjut ke Pembayaran</button>
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
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    // === Podcast Dynamic Pricing Formula ===
    function calcPodcastPrice(jam) {
        if (jam <= 0) return 0;
        if (jam === 1) return 500000;
        if (jam === 2) return 800000;
        return 800000 + ((jam - 2) * 300000);
    }

    function formatRupiah(amount) {
        return 'Rp ' + amount.toLocaleString('id-ID');
    }

    function openPodcastBookingModal() {
        openBookingModal(2, 800000);
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
        document.getElementById('calMonthYear').innerText = `${MONTHS[curMonth]} ${curYear}`;
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

        const dayName = new Date(curYear, curMonth, dayNum).toLocaleDateString('id-ID', {
            weekday: 'long'
        });
        document.getElementById('timeColTitle').innerText = `${dayName}, ${dayNum} ${MONTHS[curMonth]}`;
        document.getElementById('bmTimeCol').classList.add('active');

        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '<div style="text-align:center;padding:20px;color:#64748b;"><i class="fa-solid fa-spinner fa-spin"></i> Mengecek ketersediaan...</div>';

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
                container.innerHTML += `<div class="time-slot disabled">${time} (Penuh)</div>`;
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