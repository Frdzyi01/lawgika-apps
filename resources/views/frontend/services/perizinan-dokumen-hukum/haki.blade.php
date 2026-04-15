@extends('layout.app')

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

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--law-text);
        background-color: var(--law-gray-bg);
    }

    .section-padding {
        padding: 100px 0;
    }

    /* Hero Section - Immersive */
    .hero-agency {
        height: 85vh;
        min-height: 600px;
        background: url('https://images.unsplash.com/photo-1589578527966-fdac0f44566c?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat; /* Ganti: Intellectual Property / Patent Concept */
        position: relative;
        display: flex;
        align-items: center;
        margin-top: 0;
    }

    .hero-agency::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.4) 60%, rgba(0, 0, 0, 0.2) 100%);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        max-width: 650px;
    }

    .hero-title {
        font-size: clamp(2.5rem, 5vw, 4rem);
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 24px;
        color: #fff;
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 35px;
        line-height: 1.6;
    }

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
            url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed; /* Ganti: Legal Document & Stamp */
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
        background: radial-gradient(circle, rgba(128, 0, 0, 0.1) 0%, transparent 70%);
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

{{-- Breadcrumb --}}
<section class="bg-white py-3 border-bottom overflow-hidden mt-5 pt-1">
    <div class="container pt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-maroon text-decoration-none small">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-maroon text-decoration-none small">Layanan</a></li>
                <li class="breadcrumb-item active small" aria-current="page">Pendaftaran HAKI</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Hero Section --}}
<section class="hero-agency">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="section-label" style="color: rgba(255,255,255,0.7)">Hak Kekayaan Intelektual</span>
            <h1 class="hero-title">Lindungi Merek & Karya Anda Secara Hukum</h1>
            <p class="hero-subtitle">
                Menyediakan layanan pendaftaran HAKI (Hak Kekayaan Intelektual) untuk membantu melindungi merek, karya, atau aset intelektual bisnis Anda secara resmi sesuai ketentuan yang berlaku.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="https://wa.me/628123456789" class="btn-white">Konsultasi Gratis</a>
                <a href="#haki-details" class="btn-outline-white">Pelajari Selengkapnya</a>
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
                    <div class="trust-icon"><i class="bi bi-shield-lock"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Perlindungan Hukum</h6>
                        <small class="text-muted">Hak eksklusif terjamin</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-search"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Pengecekan Merek</h6>
                        <small class="text-muted">Hindari penolakan</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Proses Transparan</h6>
                        <small class="text-muted">Pantau status online</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="haki-details" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1589578527966-fdac0f44566c?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Pendaftaran Merek Dagang">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">01. Merek Dagang</span>
                    <h2 class="display-5 fw-bold mb-3">Pendaftaran Merek (Trademark)</h2>
                    <p class="text-muted mb-4 lead">
                        Merek adalah identitas bisnis Anda. Kami membantu proses pendaftaran merek dagang ke DJKI Kemenkumham agar brand Anda mendapatkan perlindungan hukum eksklusif selama 10 tahun dan dapat diperpanjang.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pengecekan ketersediaan nama merek</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Penyusunan deskripsi & kelas merek</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pendampingan hingga sertifikat terbit</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1627398242454-45a1465c2479?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Hak Cipta">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">02. Cipta & Paten</span>
                    <h2 class="display-5 fw-bold mb-3">Hak Cipta & Paten Sederhana</h2>
                    <p class="text-muted mb-4 lead">
                        Lindungi karya intelektual Anda mulai dari software, buku, musik, desain industri, hingga invensi teknologi. Kami bantu daftarkan Hak Cipta dan Paten Sederhana Anda secara profesional.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pendaftaran Hak Cipta (Software, Seni, Sastra)</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pendaftaran Desain Industri</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pendaftaran Paten Sederhana</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Process Timeline --}}
<section class="section-padding bg-white border-top">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-label">Workflow</span>
            <h2 class="fw-bold display-5">Proses Pendaftaran HAKI</h2>
        </div>
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">1</div>
                        <h5 class="fw-bold">Konsultasi & Cek Merek</h5>
                        <p class="text-muted small px-lg-4">Analisis potensi keberhasilan pendaftaran merek/karya Anda.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">2</div>
                        <h5 class="fw-bold">Penyiapan Dokumen</h5>
                        <p class="text-muted small px-lg-4">Kami siapkan seluruh formulir dan dokumen pendukung.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="timeline-item">
                        <div class="timeline-circle">3</div>
                        <h5 class="fw-bold">Submit & Pemantauan</h5>
                        <p class="text-muted small px-lg-4">Pengajuan resmi dan monitoring proses di DJKI.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Visual Break --}}
<section class="visual-break-agency">
    <div class="container position-relative z-1">
        <div class="row">
            <div class="col-lg-7 fade-up">
                <span class="badge-premium bg-white shadow-sm mb-3">Perlindungan Aset Intelektual</span>
                <h2 class="display-3 fw-bold text-white mb-3">Jangan Biarkan Ide & Merek Anda Diklaim Pihak Lain</h2>
                <p class="text-white-50 lead mb-0">HAKI adalah aset tak berwujud yang sangat berharga. Amankan hak eksklusif Anda sekarang juga sebelum terlambat.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== LAYANAN KAMI SECTION ===== --}}
<style>
    /* ==============================
       LAYANAN KAMI – Custom Styles
    ============================== */
    #layanan-kami-section {
        background-color: #fff;
        padding: 72px 0 80px;
    }

    .layanan-section-title {
        font-size: 2rem;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.3px;
        margin-bottom: 0;
    }

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
        color: var(--law-maroon);
    }

    .layanan-nav .nav-link.active {
        color: var(--law-maroon);
        border-bottom-color: var(--law-maroon);
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

    .layanan-banner {
        background: linear-gradient(135deg, #fdf2f2 0%, #fef3e7 100%);
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
        border-color: var(--law-maroon);
        background: var(--law-maroon);
        color: #fff;
    }

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
        background: var(--law-maroon);
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
        color: var(--law-maroon);
        margin-bottom: 16px;
    }

    .layanan-card-btn {
        background: var(--law-maroon);
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
        background: var(--law-maroon-dark);
        color: #fff;
        transform: translateY(-1px);
    }

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

<section id="layanan-kami-section" aria-label="Layanan Kami">
    <div class="container">
        <h2 class="layanan-section-title mb-3">Layanan Kami</h2>

        <ul class="nav layanan-nav mb-4" id="layananTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="tab-office" data-layanan-tab="office" type="button" role="tab" aria-controls="pane-office" aria-selected="true">
                    Office &amp; Work Space <span class="nav-arrow">→</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-business" data-layanan-tab="business" type="button" role="tab" aria-controls="pane-business" aria-selected="false">
                    Business Services <span class="nav-arrow">→</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tab-foreign" data-layanan-tab="foreign" type="button" role="tab" aria-controls="pane-foreign" aria-selected="false">
                    Foreign Services <span class="nav-arrow">→</span>
                </button>
            </li>
        </ul>

        <div id="layananTabContent">
            {{-- TAB 1 – Office & Work Space --}}
            <div class="layanan-tab-pane active-visible" id="pane-office" role="tabpanel" aria-labelledby="tab-office">
                <div class="layanan-banner">
                    <div class="layanan-banner-text">
                        <h2>Saatnya Membuat Bisnis Anda Lebih Besar</h2>
                        <p>Akan sangat disayangkan jika bisnis Anda tidak memiliki kantor dan ruang kerja yang mampu membuat Anda menjalankan bisnis secara efektif.</p>
                    </div>
                    <div class="layanan-banner-icon d-none d-md-block">
                        <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="10" y="50" width="100" height="60" rx="4" stroke="#2CB67D" stroke-width="3" fill="none" />
                            <rect x="25" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
                            <rect x="75" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
                            <line x1="60" y1="50" x2="60" y2="110" stroke="#2CB67D" stroke-width="2" />
                            <rect x="35" y="90" width="50" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
                            <line x1="10" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
                            <line x1="110" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
                            <circle cx="60" cy="18" r="4" fill="#2CB67D" />
                            <path d="M78 32 Q85 25 92 32" stroke="#2CB67D" stroke-width="2" fill="none" />
                            <line x1="85" y1="32" x2="85" y2="45" stroke="#2CB67D" stroke-width="2" />
                            <path d="M78 45 Q85 48 92 45" stroke="#2CB67D" stroke-width="2" fill="none" />
                            <line x1="80" y1="45" x2="78" y2="55" stroke="#2CB67D" stroke-width="2" />
                            <line x1="90" y1="45" x2="92" y2="55" stroke="#2CB67D" stroke-width="2" />
                        </svg>
                    </div>
                </div>
                <div class="layanan-nav-arrows">
                    <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
                    <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=500&q=80" alt="Virtual Office" loading="lazy">
                                <span class="layanan-card-badge">Best Seller</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Virtual Office</div>
                                <div class="layanan-card-desc">Hemat biaya operasional hingga 90% dengan alamat kantor prestisius tanpa sewa fisik.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 299.000/Bulan*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&q=80" alt="Serviced Office" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Serviced Office</div>
                                <div class="layanan-card-desc">Ruang kantor siap pakai yang ideal untuk tim Anda, dilengkapi fasilitas lengkap.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 4.500.000/Bulan*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=500&q=80" alt="Meeting Room" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Meeting Room</div>
                                <div class="layanan-card-desc">Tempat yang cocok untuk melakukan pertemuan penting dengan klien atau mitra bisnis.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 255.000/Jam*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1600508774634-4e11d34730e2?w=500&q=80" alt="Coworking Space" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Coworking Space</div>
                                <div class="layanan-card-desc">Ruang kerja bersama yang fleksibel dan produktif untuk freelancer maupun startup.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 150.000/Hari*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2 – Business Services --}}
            <div class="layanan-tab-pane" id="pane-business" role="tabpanel" aria-labelledby="tab-business">
                <div class="layanan-banner" style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);">
                    <div class="layanan-banner-text">
                        <h2>Wujudkan Bisnis Impian Anda Bersama Kami</h2>
                        <p>Kami hadir untuk membantu Anda mengurus seluruh kebutuhan legalitas dan administrasi bisnis dengan cepat, tepat, dan terpercaya.</p>
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
                    <button class="layanan-arrow-btn" title="Previous">&#8592;</button>
                    <button class="layanan-arrow-btn" title="Next">&#8594;</button>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&q=80" alt="Company Registration" loading="lazy">
                                <span class="layanan-card-badge">Populer</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Company Registration</div>
                                <div class="layanan-card-desc">Proses pendirian badan usaha PT, CV, Firma dengan cepat dan sesuai regulasi yang berlaku.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 2.500.000/Paket*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=500&q=80" alt="Legal Consulting" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Legal Consulting</div>
                                <div class="layanan-card-desc">Konsultasi hukum profesional untuk melindungi bisnis Anda dari risiko hukum yang merugikan.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=500&q=80" alt="Tax Services" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Tax Services</div>
                                <div class="layanan-card-desc">Pengurusan NPWP, pelaporan SPT Tahunan, dan konsultasi pajak bisnis yang akurat dan aman.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 750.000/Laporan*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80" alt="Business Licensing" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Business Licensing</div>
                                <div class="layanan-card-desc">Pengurusan NIB, OSS, SIUP, dan berbagai izin usaha lainnya secara cepat dan terpercaya.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 1.200.000/Izin*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3 – Foreign Services --}}
            <div class="layanan-tab-pane" id="pane-foreign" role="tabpanel" aria-labelledby="tab-foreign">
                <div class="layanan-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);">
                    <div class="layanan-banner-text">
                        <h2>Solusi Lengkap untuk Kebutuhan Imigrasi Anda</h2>
                        <p>Kami membantu WNA dan ekspatriat mengurus seluruh dokumen keimigrasian di Indonesia dengan mudah, cepat, dan sesuai prosedur resmi.</p>
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
                    <button class="layanan-arrow-btn" title="Previous">&#8592;</button>
                    <button class="layanan-arrow-btn" title="Next">&#8594;</button>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1551009175-15bdf9dcb580?w=500&q=80" alt="Visa Application" loading="lazy">
                                <span class="layanan-card-badge">Terlaris</span>
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Visa Application</div>
                                <div class="layanan-card-desc">Pengurusan visa kunjungan, bisnis, dan tinggal terbatas di Indonesia secara profesional.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 1.500.000/Proses*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&q=80" alt="KITAS / KITAP" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">KITAS / KITAP</div>
                                <div class="layanan-card-desc">Pengurusan izin tinggal terbatas dan tetap untuk warga negara asing di Indonesia.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 3.500.000/Proses*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=500&q=80" alt="Passport Assistance" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Passport Assistance</div>
                                <div class="layanan-card-desc">Bantuan persiapan, pengurusan, dan perpanjangan paspor untuk keperluan perjalanan internasional.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 800.000/Proses*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="layanan-card">
                            <div class="layanan-card-img-wrap">
                                <img src="https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=500&q=80" alt="Immigration Consulting" loading="lazy">
                            </div>
                            <div class="layanan-card-body">
                                <div class="layanan-card-title">Immigration Consulting</div>
                                <div class="layanan-card-desc">Layanan konsultasi keimigrasian komprehensif untuk WNA yang akan tinggal atau bekerja di Indonesia.</div>
                                <div class="layanan-card-price-label">Price Start From</div>
                                <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                                <a href="#" class="layanan-card-btn">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    (function() {
        'use strict';

        const tabBtns = document.querySelectorAll('[data-layanan-tab]');
        const tabPanes = document.querySelectorAll('.layanan-tab-pane');

        tabBtns.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const target = btn.getAttribute('data-layanan-tab');

                tabBtns.forEach(function(b) {
                    b.classList.remove('active');
                    b.setAttribute('aria-selected', 'false');
                });
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');

                tabPanes.forEach(function(pane) {
                    if (pane.classList.contains('active-visible')) {
                        pane.classList.remove('active-visible');
                        pane.style.display = 'none';
                    }
                });

                const targetPane = document.getElementById('pane-' + target);
                if (targetPane) {
                    targetPane.style.display = 'block';
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
            <h2>FAQ terkait Pendaftaran HAKI</h2>
            <p>Pertanyaan yang paling sering diajukan seputar Hak Kekayaan Intelektual</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        Apa itu HAKI dan apa saja jenisnya?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        HAKI (Hak Kekayaan Intelektual) adalah hak eksklusif yang diberikan negara kepada pencipta, penemu, atau pemilik karya intelektual. Jenis HAKI yang umum didaftarkan antara lain: Merek Dagang, Hak Cipta, Paten, Desain Industri, Rahasia Dagang, dan Indikasi Geografis.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Berapa lama proses pendaftaran merek?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Proses pendaftaran merek di DJKI memakan waktu sekitar 6-12 bulan, tergantung ada tidaknya keberatan dari pihak lain. Setelah terdaftar, perlindungan merek berlaku selama 10 tahun dan dapat diperpanjang.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apa syarat pendaftaran Hak Cipta?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Syarat pendaftaran Hak Cipta meliputi: formulir pendaftaran, surat pernyataan kepemilikan karya, contoh karya (softcopy/hardcopy), KTP pencipta, dan surat pengalihan hak (jika pencipta berbeda dengan pemohon).
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah merek yang sama bisa didaftarkan untuk kelas berbeda?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Ya, merek yang sama dapat didaftarkan pada kelas barang/jasa yang berbeda, selama tidak memiliki persamaan pada pokoknya dengan merek terdaftar milik pihak lain di kelas yang sama. Kami akan membantu Anda menentukan kelas yang tepat.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apa perbedaan Paten dan Paten Sederhana?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Paten melindungi invensi baru yang memiliki langkah inventif dan dapat diterapkan dalam industri (perlindungan 20 tahun). Paten Sederhana melindungi invensi berupa pengembangan dari produk/proses yang sudah ada (perlindungan 10 tahun) dengan proses lebih cepat.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah HAKI berlaku secara internasional?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Pendaftaran HAKI di Indonesia bersifat teritorial (hanya berlaku di wilayah Indonesia). Untuk perlindungan internasional, Anda dapat mengajukan melalui sistem Madrid Protocol (untuk merek) atau Patent Cooperation Treaty/PCT (untuk paten).
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