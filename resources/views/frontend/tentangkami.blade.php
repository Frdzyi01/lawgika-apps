@extends('layout.app')
@section('title', 'Tentang Kami | Lawgika - Konsultan Legal & Bisnis Terpercaya')
@section('meta_description', 'Pelajari lebih lanjut tentang Lawgika Bisnis Indonesia. Kami adalah mitra terpercaya untuk solusi legalitas, perizinan, dan pertumbuhan bisnis Anda di Indonesia.')
@section('meta_keywords', 'Tentang Lawgika, Profil Lawgika, Visi Misi Lawgika, Konsultan Bisnis Indonesia, Legalitas Perusahaan')

@section('content')

{{-- Hero / Breadcrumb Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #4e0516 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 100px; overflow: hidden;">
    {{-- Decorative Background Elements --}}
    <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');"></div>
    <div class="position-absolute bottom-0 end-0 p-5 opacity-20 d-none d-lg-block">
        <i class="fas fa-balance-scale text-white" style="font-size: 200px; transform: rotate(-15deg);"></i>
    </div>

    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="page-title-content animate__animated animate__fadeInLeft">
                    <span class="badge bg-danger rounded-pill px-3 py-2 fw-bold mb-3 shadow-sm text-uppercase" style="font-size: 0.75rem; letter-spacing: 2px;">Company Profile</span>
                    <h1 class="text-white fw-bold mb-4 display-3">Mengenal Lebih Dekat <br><span class="text-white">Lawgika</span></h1>
                    <p class="text-white lead mb-0" style="max-width: 600px;">Mitra strategis terpercaya dalam membangun, menjalankan, dan mengembangkan bisnis Anda di Indonesia dengan solusi legalitas terpadu.</p>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-5 mt-lg-0 animate__animated animate__fadeInRight">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0 bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none opacity-75 hover-opacity-100">Beranda</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">Tentang Kami</li>
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
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=1000&q=80" alt="Lawgika Office" class="img-fluid rounded-5 shadow-2xl">
                    <div class="position-absolute bottom-0 end-0 m-4 p-4 bg-white rounded-4 shadow-lg d-none d-lg-block border-start border-5 border-danger">
                        <h4 class="fw-bold mb-1">Dinamis & Terpercaya</h4>
                        <p class="text-muted small mb-0">Solusi Bisnis Terintegrasi</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
                <div class="section-title mb-4">
                    <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 2px;">Kata Pengantar</h5>
                    <h2 class="fw-bold display-6 mb-4">Komitmen Kami Untuk <span class="text-danger">Kesuksesan Anda</span></h2>
                </div>
                <div class="intro-text" style="text-align: justify;">
                    <p class="text-muted mb-4 fs-5" style="line-height: 1.8;">
                        Dinamika iklim usaha saat ini menuntut para pelaku bisnis untuk tidak hanya fokus pada ekspansi, tetapi juga pada ketajaman aspek legalitas, kepatuhan pajak, dan tata kelola keuangan yang presisi. Kami memahami bahwa membangun tim internal untuk menangani seluruh spektrum tersebut memerlukan investasi waktu dan biaya yang besar.
                    </p>
                    <p class="text-muted mb-4" style="line-height: 1.7;">
                        <strong>PT Lawgika Bisnis Indonesia</strong> hadir sebagai solusi bisnis terpadu yang berfokus pada layanan penyewaan alamat virtual, ruang meeting, layanan legalitas, perizinan usaha, pembukuan, perpajakan, serta fasilitas penunjang operasional bisnis lainnya.
                    </p>
                    <p class="text-muted" style="line-height: 1.7;">
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
    <div class="position-absolute top-0 end-0 m-n5 opacity-5 d-none d-lg-block" style="width: 400px; height: 400px; background: #a6a6a6ff; border-radius: 50%; filter: blur(80px);"></div>
    <div class="position-absolute bottom-0 start-0 m-n5 opacity-5 d-none d-lg-block" style="width: 300px; height: 300px; background: #a6a6a6ff; border-radius: 50%; filter: blur(60px);"></div>

    <div class="container py-lg-5 position-relative z-1">
        <div class="text-center mb-5 animate__animated animate__fadeIn">
            <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 3px; font-size: 0.8rem;">Filosofi Kami</h5>
            <h2 class="fw-bold display-5">Visi & <span class="text-danger">Misi</span></h2>
            <div class="mx-auto bg-danger mt-3" style="width: 60px; height: 4px; border-radius: 2px;"></div>
        </div>

        <div class="row g-5 align-items-stretch">
            {{-- Vision Side --}}
            <div class="col-lg-5 animate__animated animate__fadeInLeft">
                <div class="vision-card-premium h-100 p-5 rounded-5 shadow-2xl position-relative overflow-hidden" style="background: #1a0208; color: white;">
                    <div class="position-absolute top-0 end-0 p-4 opacity-10">
                        <i class="fas fa-bullseye fa-6x"></i>
                    </div>
                    <div class="icon-box-glass mb-4 d-inline-flex align-items-center justify-content-center rounded-4" style="width: 80px; height: 80px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px);">
                        <i class="fas fa-eye fa-2x text-white"></i>
                    </div>
                    <h3 class="fw-bold mb-4" style="color: white;">Visi Kami</h3>
                    <p class="lead mb-0" style="line-height: 1.8; font-weight: 300; letter-spacing: 0.5px;">
                        "Menjadi mitra terpercaya bagi pebisnis dalam membangun dan mengembangkan bisnis yang <span class="text-danger fw-bold" style="color: white !important;">efisien</span>, <span class="text-danger fw-bold" style="color: white !important;">patuh regulasi</span>, dan dikelola secara <span class="text-danger fw-bold" style="color: white !important;">profesional</span> di Indonesia."
                    </p>
                    <div class="mt-5 pt-4 border-top border-white-10">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-group d-flex">
                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border: 2px solid #1a0208;">
                                    <i class="fas fa-check text-white small"></i>
                                </div>
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center ms-n2" style="width: 40px; height: 40px; border: 2px solid #1a0208;">
                                    <i class="fas fa-star text-danger small"></i>
                                </div>
                            </div>
                            <span class="small opacity-75">Dipercaya oleh 500+ Klien</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Mission Side --}}
            <div class="col-lg-7">
                <div class="row g-4 h-100">
                    <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.1s;">
                        <div class="mission-item-premium p-4 rounded-4 bg-white shadow-sm border border-light h-100 transition-all hover-shadow-lg d-flex align-items-start gap-4">
                            <div class="mission-icon-wrap flex-shrink-0 bg-danger-subtle text-danger rounded-4 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                <i class="fas fa-layer-group fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Solusi Terintegrasi</h5>
                                <p class="text-muted mb-0 small" style="line-height: 1.6;">Menyediakan solusi terintegrasi dalam pendirian usaha, legalitas, akuntansi, dan perpajakan secara praktis dan efisien.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.2s;">
                        <div class="mission-item-premium p-4 rounded-4 bg-white shadow-sm border border-light h-100 transition-all hover-shadow-lg d-flex align-items-start gap-4">
                            <div class="mission-icon-wrap flex-shrink-0 bg-danger-subtle text-danger rounded-4 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                <i class="fas fa-chart-line fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Dukungan Pertumbuhan</h5>
                                <p class="text-muted mb-0 small" style="line-height: 1.6;">Mendukung pertumbuhan bisnis klien melalui layanan yang akurat, tepat waktu, dan berorientasi pada hasil yang nyata.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
                        <div class="mission-item-premium p-4 rounded-4 bg-white shadow-sm border border-light h-100 transition-all hover-shadow-lg d-flex align-items-start gap-4">
                            <div class="mission-icon-wrap flex-shrink-0 bg-danger-subtle text-danger rounded-4 d-flex align-items-center justify-content-center" style="width: 65px; height: 65px;">
                                <i class="fas fa-user-shield fa-xl"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-2">Layanan Profesional</h5>
                                <p class="text-muted mb-0 small" style="line-height: 1.6;">Memberikan layanan profesional melalui tim yang kompeten, berpengalaman, dan menjunjung tinggi integritas dalam setiap prosesnya.</p>
                            </div>
                        </div>
                    </div>
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
                    <h5 class="text-danger fw-bold text-uppercase mb-2" style="letter-spacing: 2px;">Hubungi Kami</h5>
                    <h2 class="fw-bold display-6">Siap Melayani <span class="text-danger">Kebutuhan Bisnis Anda</span></h2>
                </div>

                <div class="contact-info-wrap">
                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-danger text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-map-marker-alt fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Alamat Kantor</h6>
                            <p class="text-muted mb-0">World Capital Tower Lt. 38 no 6-7, Mega Kuningan, Jakarta Selatan, Jakarta - Indonesia</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-success text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fab fa-whatsapp fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">WhatsApp</h6>
                            <a href="https://wa.me/6281112088600" target="_blank" class="text-muted text-decoration-none">+62 811 1208 8600</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-primary text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-envelope fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Email</h6>
                            <a href="mailto:informasi@lawgika.co.id" class="text-muted text-decoration-none">informasi@lawgika.co.id</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-4">
                        <div class="icon-circle bg-dark text-white me-4 p-3 rounded-circle shadow-sm">
                            <i class="fas fa-phone fa-fw"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Telepon</h6>
                            <p class="text-muted mb-0">021-3970-6065</p>
                        </div>
                    </div>

                    <div class="social-links mt-5">
                        <h6 class="fw-bold mb-3 text-uppercase small" style="letter-spacing: 1px;">Ikuti Kami</h6>
                        <div class="d-flex gap-3">
                            <a href="https://www.instagram.com/lawgika.co.id" target="_blank" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-instagram"></i></a>
                            <a href="https://id.linkedin.com/company/lawgika-associates-law-firm" target="_blank" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="btn btn-outline-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;"><i class="fab fa-facebook-f"></i></a>
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
<section class="cta-section py-5 position-relative overflow-hidden" style="background: #1a0208;">
    <div class="container py-lg-5 text-center text-white z-1 position-relative">
        <h2 class="fw-bold mb-4 display-5">Siap Berkolaborasi Bersama Lawgika?</h2>
        <p class="lead opacity-75 mb-5 max-w-700 mx-auto">Kami siap membantu mewujudkan visi bisnis Anda dengan dukungan legalitas dan operasional yang profesional.</p>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="https://wa.me/6281112088600" class="theme-btn btn-lg px-5 py-3">Konsultasi Sekarang</a>
            <a href="{{ asset('Company Profile.pdf') }}" download class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill">Unduh Company Profile</a>
        </div>
    </div>
    {{-- Background Decoration --}}
    <div class="position-absolute top-0 end-0 p-5 opacity-10">
        <i class="fas fa-rocket fa-10x" style="transform: rotate(15deg);"></i>
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
</style>

@endsection