@extends('layout.app')
@section('title', 'Tentang Kami | Lawgika - Konsultan Legal & Bisnis Terpercaya')
@section('meta_description', 'Pelajari lebih lanjut tentang Lawgika Bisnis Indonesia. Kami adalah mitra terpercaya untuk solusi legalitas, perizinan, dan pertumbuhan bisnis Anda di Indonesia.')
@section('meta_keywords', 'Tentang Lawgika, Profil Lawgika, Visi Misi Lawgika, Konsultan Bisnis Indonesia, Legalitas Perusahaan')

@section('content')

{{-- Hero / Breadcrumb Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #4e0516 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 100px; overflow: hidden;">
    {{-- Decorative Background Elements --}}
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10"></div>
    <div class="position-absolute bottom-0 end-0 p-5 opacity-20 d-none d-lg-block">
        <!-- <i class="fas fa-balance-scale text-white" style="font-size: 200px; transform: rotate(-15deg);"></i> -->
    </div>

    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="page-title-content animate__animated animate__fadeInLeft">
                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold mb-3 shadow-sm text-uppercase" style="font-size: 0.75rem; letter-spacing: 2px;" data-i18n="tentang.hero.badge">Company Profile</span>
                    <h1 class="text-white fw-bold mb-4 display-3" data-i18n="tentang.hero.title">Mengenal Lebih Dekat <br><span class="text-white">Lawgika</span></h1>
                    <p class="text-white lead mb-0" style="max-width: 600px;" data-i18n="tentang.hero.desc">Mitra strategis terpercaya dalam membangun, menjalankan, dan mengembangkan bisnis Anda di Indonesia dengan solusi legalitas terpadu.</p>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-5 mt-lg-0 animate__animated animate__fadeInRight">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none opacity-75 hover-opacity-100" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page" data-i18n="nav.profile.perusahaan">Tentang Kami</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- Kata Pengantar Section --}}
<section class="intro-section py-5 my-lg-5">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate__animated animate__fadeInUp">
                <div class="position-relative">
                    <div class="position-absolute top-0 start-0 translate-middle p-4 bg-danger rounded-4 shadow-lg d-none d-md-block" style="z-index: 2; margin-top: 50px; margin-left: 50px;">
                        <i class="fas fa-quote-left text-white fa-2x"></i>
                    </div>
                    <img src="{{ asset('lawgika/tentangkami.jpeg') }}" alt="Lawgika Office" class="img-fluid rounded-5 shadow-2xl">
                    <div class="position-absolute bottom-0 end-0 m-4 p-4 bg-white rounded-4 shadow-lg d-none d-lg-block border-start border-5 border-danger">
                        <h4 class="fw-bold mb-1" data-i18n="tentang.intro.card_title">Dinamis & Terpercaya</h4>
                        <p class="text-muted small mb-0" data-i18n="tentang.intro.card_sub">Solusi Bisnis Terintegrasi</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="section-title mb-4">
                    <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 2px;" data-i18n="tentang.intro.eyebrow">Kata Pengantar</h5>
                    <h2 class="fw-bold display-6 mb-4" data-i18n="tentang.intro.title">Komitmen Kami Untuk Kesuksesan Anda</h2>
                </div>
                <div class="intro-text" style="text-align: justify;">
                    <p class="text-muted mb-4 fs-5" style="line-height: 1.8;" data-i18n="tentang.intro.p1">
                        Dinamika iklim usaha saat ini menuntut para pelaku bisnis untuk tidak hanya fokus pada ekspansi, tetapi juga pada ketajaman aspek legalitas, kepatuhan pajak, dan tata kelola keuangan yang presisi. Kami memahami bahwa membangun tim internal untuk menangani seluruh spektrum tersebut memerlukan investasi waktu dan biaya yang besar.
                    </p>
                    <p class="text-muted mb-4 fs-5" style="line-height: 1.8;" data-i18n="tentang.intro.p2">
                        <strong>PT Lawgika Bisnis Indonesia</strong> hadir sebagai solusi bisnis terpadu yang berfokus pada layanan penyewaan alamat virtual, ruang meeting, layanan legalitas, perizinan usaha, pembukuan, perpajakan, serta fasilitas penunjang operasional bisnis lainnya.
                    </p>
                    <p class="text-muted mb-4 fs-5" style="line-height: 1.8;" data-i18n="tentang.intro.p3">
                        Beroperasi dengan brand <strong>Lawgika.co.id</strong>, kami berkomitmen memberikan layanan yang tepat sesuai kebutuhan klien, dapat dipercaya, dan profesional agar bisnis Anda dapat bertumbuh pesat di tengah persaingan pasar Indonesia.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Vision & Mission Section --}}
<section class="vision-mission-section py-5 position-relative overflow-hidden" style="background: linear-gradient(to bottom, #ffffff, #fff5f6);">
    {{-- Decorative Shapes --}}
    <div class="position-absolute top-0 end-0 m-n5 opacity-5 d-none d-lg-block" style="width: 400px; height: 400px;border-radius: 50%; filter: blur(80px);"></div>
    <div class="position-absolute bottom-0 start-0 m-n5 opacity-5 d-none d-lg-block" style="width: 300px; height: 300px;border-radius: 50%; filter: blur(60px);"></div>

    <div class="container py-lg-5 position-relative z-1">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 2px; font-size: 0.85rem;" data-i18n="tentang.vision.eyebrow">FILOSOFI KAMI</h5>
            <h2 class="fw-bold display-6 text-dark" data-i18n="tentang.vision.title">Visi & Misi</h2>
        </div>

        <div class="row g-5 align-items-stretch">
            {{-- Vision Side --}}
            <div class="col-lg-6 animate__animated animate__fadeInLeft">
                <div class="vision-box-clean p-4 p-md-5 rounded-4 h-100 bg-white border border-light shadow-sm transition-all hover-shadow-md" style="border-left: 5px solid #4e0516 !important;">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="icon-wrap bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-eye fa-lg"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem; letter-spacing: 0.5px;" data-i18n="tentang.vision.box_title">Visi Kami</h4>
                    </div>
                    <p class="fs-5 text-muted mb-0" style="line-height: 1.8; text-align: justify; font-weight: 300;" data-i18n="tentang.vision.text">
                        "Menjadi mitra terpercaya bagi pebisnis dalam membangun dan mengembangkan bisnis yang <span class="text-danger fw-bold">efisien</span>, <span class="text-danger fw-bold">patuh regulasi</span>, dan dikelola secara <span class="text-danger fw-bold">profesional</span> di Indonesia."
                    </p>
                </div>
            </div>

            {{-- Mission Side --}}
            <div class="col-lg-6 animate__animated animate__fadeInRight">
                <div class="mission-box-clean p-4 p-md-5 rounded-4 h-100 bg-white border border-light shadow-sm transition-all hover-shadow-md" style="border-left: 5px solid #4e0516 !important;">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="icon-wrap bg-danger-subtle text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                            <i class="fas fa-bullseye fa-lg"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 1.4rem; letter-spacing: 0.5px;" data-i18n="tentang.mission.box_title">Misi Kami</h4>
                    </div>
                    <ul class="list-unstyled mb-0 d-flex flex-column gap-4">
                        <li class="d-flex align-items-start gap-3">
                            <div class="check-icon-wrap bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 28px; height: 28px;">
                                <i class="fas fa-check fa-xs"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;" data-i18n="tentang.mission.item1_title">Solusi Terintegrasi</h5>
                                <p class="text-muted mb-0" style="line-height: 1.6; text-align: justify; font-size: 0.95rem;" data-i18n="tentang.mission.item1_desc">Menyediakan solusi terintegrasi dalam pendirian usaha, legalitas, akuntansi, dan perpajakan secara praktis dan efisien.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="check-icon-wrap bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 28px; height: 28px;">
                                <i class="fas fa-check fa-xs"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;" data-i18n="tentang.mission.item2_title">Dukungan Pertumbuhan</h5>
                                <p class="text-muted mb-0" style="line-height: 1.6; text-align: justify; font-size: 0.95rem;" data-i18n="tentang.mission.item2_desc">Mendukung pertumbuhan bisnis klien melalui layanan yang akurat, tepat waktu, dan berorientasi pada hasil yang nyata.</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start gap-3">
                            <div class="check-icon-wrap bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 28px; height: 28px;">
                                <i class="fas fa-check fa-xs"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.1rem;" data-i18n="tentang.mission.item3_title">Layanan Profesional</h5>
                                <p class="text-muted mb-0" style="line-height: 1.6; text-align: justify; font-size: 0.95rem;" data-i18n="tentang.mission.item3_desc">Memberikan layanan profesional melalui tim yang kompeten, berpengalaman, dan menjunjung tinggi integritas dalam setiap prosesnya.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Contact & Address Section --}}
<section class="contact-location-section py-5 my-lg-5">
    <div class="container py-lg-4">
        <div class="row g-5">
            <div class="col-lg-5 animate__animated animate__fadeInLeft">
                <div class="section-title mb-5">
                    <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 2px;" data-i18n="tentang.contact.eyebrow">Hubungi Kami</h5>
                    <h2 class="fw-bold display-6" data-i18n="tentang.contact.title">Siap Melayani Kebutuhan Bisnis Anda</h2>
                </div>

                <div class="contact-info-wrap">
                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-danger text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" data-i18n="tentang.contact.address_lbl">Alamat Kantor</h6>
                            <p class="text-muted mb-0" data-i18n="tentang.contact.address_val">World Capital Tower Lt. 38 no 6-7, Mega Kuningan, Jakarta Selatan, Jakarta - Indonesia</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-success text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fab fa-whatsapp fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" data-i18n="tentang.contact.wa_lbl">WhatsApp</h6>
                            <a href="https://wa.me/6281112088600" target="_blank" class="text-muted text-decoration-none">+62 811 1208 8600</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-primary text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-envelope fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" data-i18n="tentang.contact.email_lbl">Email</h6>
                            <a href="mailto:informasi@lawgika.co.id" class="text-muted text-decoration-none">informasi@lawgika.co.id</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-dark text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-phone fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1" data-i18n="tentang.contact.phone_lbl">Telepon</h6>
                            <p class="text-muted mb-0">021-3970-6065</p>
                        </div>
                    </div>

                    <div class="social-links mt-5">
                        <h6 class="fw-bold mb-3 text-uppercase small" style="letter-spacing: 1px;" data-i18n="tentang.contact.follow">Ikuti Kami</h6>
                        <div class="d-flex gap-3">
                            <a href="https://www.instagram.com/lawgika.co.id" target="_blank" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-instagram"></i></a>
                            <a href="https://id.linkedin.com/company/lawgika-associates-law-firm" target="_blank" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-linkedin-in"></i></a>
                            <a href="https://web.facebook.com/lawgika.co.id/" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7 animate__animated animate__fadeInRight">
                <div class="map-container rounded-5 overflow-hidden shadow-2xl position-relative border border-5 border-white">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.257970523032!2d106.82442327355443!3d-6.229682061005958!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6a1d2fd2567959%3A0xbeee0ed8352c1b29!2sLawgika%20Office!5e0!3m2!1sid!2sid!4v1778931191888!5m2!1sid!2sid" 
                        width="100%" 
                        height="550" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="cta-premium-section py-5 py-lg-6 position-relative overflow-hidden" style="background: linear-gradient(135deg, #150106 0%, #4e0516 50%, #150106 100%);">
    <!-- Background SVG Pattern Overlay -->
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-15 pointer-events-none" style="z-index: 0;">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="cta-grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255, 255, 255, 0.07)" stroke-width="1" />
                </pattern>
                <linearGradient id="gold-glow-line" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ffa31a" stop-opacity="0.35"/>
                    <stop offset="50%" stop-color="#D4AF37" stop-opacity="0.15"/>
                    <stop offset="100%" stop-color="#ffa31a" stop-opacity="0.0"/>
                </linearGradient>
            </defs>
            <rect width="100%" height="100%" fill="url(#cta-grid)" />
            <!-- Abstract luxury geometric paths -->
            <path d="M-100,220 C320,80 520,420 920,120 C1220,-80 1520,320 2020,170" fill="none" stroke="url(#gold-glow-line)" stroke-width="2" />
            <path d="M-50,270 C370,120 570,470 970,170 C1270,-30 1570,370 2070,220" fill="none" stroke="url(#gold-glow-line)" stroke-width="1" />
        </svg>
    </div>
    
    <!-- Subtle Radial Glow Overlay -->
    <div class="position-absolute top-50 start-50 translate-middle w-100 h-100 pointer-events-none" 
         style="background: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.04) 0%, transparent 70%); z-index: 0;">
    </div>
    
    <div class="container position-relative z-1 py-lg-4">
        <div class="row align-items-center g-5">
            <!-- Left Side: Content -->
            <div class="col-lg-7 text-start">
                <span class="cta-badge" data-i18n="tentang.cta.badge">KONSULTASI BISNIS & LEGALITAS</span>
                <h2 class="cta-headline display-6" data-i18n="tentang.cta.title">Wujudkan Bisnis yang Legal, Profesional, dan Siap Berkembang</h2>
                <p class="cta-description" data-i18n="tentang.cta.desc">Lawgika siap mendampingi kebutuhan legalitas, perizinan, perpajakan, hingga pengembangan bisnis Anda melalui layanan yang terintegrasi dan profesional.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="https://wa.me/6281112088600" target="_blank" class="cta-btn-primary">
                        <i class="fab fa-whatsapp fs-5"></i>
                        <span data-i18n="tentang.cta.primary">Konsultasi Gratis</span>
                    </a>
                    <a href="{{ asset('Company Profile.pdf') }}" download class="cta-btn-secondary">
                        <i class="fas fa-file-download"></i>
                        <span data-i18n="tentang.cta.secondary">Unduh Company Profile</span>
                    </a>
                </div>
            </div>
            
            <!-- Right Side: Trust points / stats -->
            <div class="col-lg-5">
                <div class="cta-trust-card">
                    <h4 class="cta-trust-title" data-i18n="tentang.cta.trust_title">Kemitraan Terpercaya</h4>
                    <ul class="cta-trust-list">
                        <li class="cta-trust-item">
                            <div class="cta-trust-icon-wrap">
                                <i class="fas fa-check"></i>
                            </div>
                            <span data-i18n="tentang.cta.trust1">Tim Profesional & Berlisensi</span>
                        </li>
                        <li class="cta-trust-item">
                            <div class="cta-trust-icon-wrap">
                                <i class="fas fa-check"></i>
                            </div>
                            <span data-i18n="tentang.cta.trust2">Layanan Terintegrasi Satu Atap</span>
                        </li>
                        <li class="cta-trust-item">
                            <div class="cta-trust-icon-wrap">
                                <i class="fas fa-check"></i>
                            </div>
                            <span data-i18n="tentang.cta.trust3">Pendampingan Hukum Berpengalaman</span>
                        </li>
                        <li class="cta-trust-item">
                            <div class="cta-trust-icon-wrap">
                                <i class="fas fa-check"></i>
                            </div>
                            <span data-i18n="tentang.cta.trust4">Respon Cepat, Akurat & Konsultatif</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-translate-y:hover {
        transform: translateY(-10px);
    }
    .transition-all {
        transition: all 0.3s ease-in-out;
    }
    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .max-w-700 {
        max-width: 700px;
    }
    .hover-opacity-100:hover {
        opacity: 1 !important;
    }
    .hover-shadow-md {
        transition: all 0.3s ease-in-out;
    }
    .hover-shadow-md:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.08) !important;
        transform: translateY(-3px);
    }

    /* Premium CTA Redesign Styles */
    .cta-premium-section {
        position: relative;
        z-index: 1;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }
    .cta-badge {
        font-size: 0.85rem;
        letter-spacing: 2px;
        font-weight: 700;
        color: #ffa31a; /* Gold Accent */
        text-transform: uppercase;
        margin-bottom: 16px;
        display: inline-block;
    }
    .cta-headline {
        color: #ffffff;
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: -0.5px;
        margin-bottom: 20px;
    }
    .cta-description {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 35px;
        max-width: 600px;
    }
    .cta-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #ffa31a 0%, #D4AF37 100%);
        color: #1a0208 !important;
        font-weight: 700;
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 8px 20px rgba(250, 163, 26, 0.2);
        border: none;
    }
    .cta-btn-primary:hover {
        background: linear-gradient(135deg, #ffa31a 0%, #ffd480 100%);
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(250, 163, 26, 0.4);
    }
    .cta-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.05);
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-weight: 600;
        padding: 15px 35px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }
    .cta-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.12);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-3px);
    }
    .cta-trust-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 24px;
        padding: 35px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        position: relative;
    }
    .cta-trust-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, transparent 100%);
        pointer-events: none;
    }
    .cta-trust-title {
        color: #ffffff;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 25px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding-bottom: 15px;
    }
    .cta-trust-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    .cta-trust-item {
        display: flex;
        align-items: center;
        gap: 15px;
        color: rgba(255, 255, 255, 0.85);
        font-size: 1rem;
        font-weight: 500;
    }
    .cta-trust-icon-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(250, 163, 26, 0.1);
        border: 1px solid rgba(250, 163, 26, 0.25);
        color: #ffa31a;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
</style>

@endsection