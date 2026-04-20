@extends('layout.app')

@section('content')
    
{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika Blog</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Tentang Kami</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Lawgika adalah platform digital yang menyediakan layanan hukum dan bisnis untuk membantu para pelaku usaha dalam menjalankan bisnisnya dengan lebih mudah dan efisien.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                 <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Tentang Kami</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>



{{-- Profile & Mission Section --}}
<section class="about-detail-area section-padding">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="about-image-wrap position-relative">
                    <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=800&q=80" alt="Lawgika Team" class="img-fluid rounded-4 shadow-lg">
                    <div class="experience-badge bg-danger text-white p-4 rounded-4 position-absolute bottom-0 start-0 m-4 shadow-lg d-none d-md-block">
                        <h2 class="fw-bold mb-0">5+</h2>
                        <p class="mb-0 small">Tahun Pengalaman</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-title mb-4">
                        <span class="text-danger fw-bold text-uppercase" style="letter-spacing: 1.5px; font-size: 0.85rem">Profil Perusahaan</span>
                        <h2 class="fw-bold mt-2 display-6">Mitra Strategis Pertumbuhan <span class="text-danger">Bisnis Anda</span></h2>
                    </div>
                    <p class="text-muted mb-4" style="line-height: 1.8">
                        Lawgika Bisnis Indonesia adalah platform digital yang berdedikasi untuk membantu para pelaku usaha di Indonesia dalam mengelola aspek legalitas dan bisnis secara efisien. Kami percaya bahwa setiap bisnis, besar maupun kecil, berhak mendapatkan akses layanan hukum yang profesional dan terjangkau.
                    </p>
                    
                    <div class="row g-4 mt-2">
                        <div class="col-md-6">
                            <div class="vision-box p-4 rounded-4" style="background: #fff5f6; border-left: 4px solid #dc3545;">
                                <h5 class="fw-bold mb-2">Visi Kami</h5>
                                <p class="small text-muted mb-0">Menjadi katalisator utama dalam pertumbuhan ekosistem bisnis di Indonesia melalui solusi hukum digital terintegrasi.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mission-box p-4 rounded-4" style="background: #f8f9fa; border-left: 4px solid #6c757d;">
                                <h5 class="fw-bold mb-2">Misi Kami</h5>
                                <p class="small text-muted mb-0">Memberikan layanan legalitas yang cepat, akurat, dan transparan untuk mendukung kepatuhan hukum bisnis Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Choose Us Section --}}
<section class="why-choose-area section-padding bg-light">
    <div class="container">
        <div class="text-center mb-5 max-w-700 mx-auto">
            <span class="text-danger fw-bold text-uppercase" style="letter-spacing: 1.5px; font-size: 0.85rem">Keunggulan Kami</span>
            <h2 class="fw-bold mt-2">Mengapa Memilih Lawgika?</h2>
            <p class="text-muted">Kami menggabungkan keahlian profesional dengan teknologi digital untuk memberikan pengalaman terbaik bagi klien kami.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="feature-card-item p-4 bg-white rounded-4 shadow-sm h-100 text-center transition-all hover-translate-y">
                    <div class="icon-wrap mb-3 mx-auto d-flex align-items-center justify-content-center bg-danger-subtle text-danger rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fas fa-user-tie fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Konsultan Ahli</h5>
                    <p class="small text-muted mb-0">Didukung oleh tim konsultan hukum dan bisnis yang berpengalaman di bidangnya.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card-item p-4 bg-white rounded-4 shadow-sm h-100 text-center transition-all hover-translate-y">
                    <div class="icon-wrap mb-3 mx-auto d-flex align-items-center justify-content-center bg-primary-subtle text-primary rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fas fa-shield-alt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Keamanan Data</h5>
                    <p class="small text-muted mb-0">Sistem penanganan dokumen yang aman dan menjamin kerahasiaan informasi bisnis Anda.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card-item p-4 bg-white rounded-4 shadow-sm h-100 text-center transition-all hover-translate-y">
                    <div class="icon-wrap mb-3 mx-auto d-flex align-items-center justify-content-center bg-success-subtle text-success rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fas fa-bolt fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Proses Efisien</h5>
                    <p class="small text-muted mb-0">Alur kerja yang digital dan terotomasi untuk memastikan layanan selesai tepat waktu.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="feature-card-item p-4 bg-white rounded-4 shadow-sm h-100 text-center transition-all hover-translate-y">
                    <div class="icon-wrap mb-3 mx-auto d-flex align-items-center justify-content-center bg-info-subtle text-info rounded-circle" style="width: 70px; height: 70px;">
                        <i class="fas fa-search-dollar fa-2x"></i>
                    </div>
                    <h5 class="fw-bold">Transparansi Biaya</h5>
                    <p class="small text-muted mb-0">Tidak ada biaya tersembunyi. Semua tarif layanan kami sampaikan secara terbuka.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="stats-area py-5" style="background: #1a0208;">
    <div class="container py-4">
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <div class="stat-item text-white">
                    <h2 class="fw-bold display-5 mb-1 counter">500+</h2>
                    <p class="text-white-50 mb-0">Klien Terpercaya</p>
                </div>
            </div>
            <div class="col-md-4 border-start border-end border-white-10">
                <div class="stat-item text-white">
                    <h2 class="fw-bold display-5 mb-1 counter">95.000+</h2>
                    <p class="text-white-50 mb-0">Database Peraturan</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item text-white">
                    <h2 class="fw-bold display-5 mb-1 counter">24/7</h2>
                    <p class="text-white-50 mb-0">Akses Layanan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Services Overview --}}
<section class="services-brief section-padding">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h2 class="fw-bold mb-0">Solusi Lengkap Untuk Bisnis Anda</h2>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ url('/') }}#layanan-kami-section" class="theme-btn">Lihat Semua Layanan <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="service-card p-4 rounded-4 border hover-shadow transition-all h-100">
                    <h4 class="fw-bold mb-3">Pendirian Usaha</h4>
                    <p class="text-muted small mb-4">Membantu pendirian PT, CV, Yayasan hingga Firma dengan proses yang legal dan cepat.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> PT Perorangan</li>
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> PT Biasa & PMA</li>
                        <li class="small"><i class="fas fa-check-circle text-danger me-2"></i> CV & Firma</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="service-card p-4 rounded-4 border hover-shadow transition-all h-100">
                    <h4 class="fw-bold mb-3">Perizinan & Hukum</h4>
                    <p class="text-muted small mb-4">Pengurusan dokumen legalitas pendukung operasional bisnis Anda.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> NIB & OSS</li>
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> HAKI / Merek</li>
                        <li class="small"><i class="fas fa-check-circle text-danger me-2"></i> Draft Perjanjian</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="service-card p-4 rounded-4 border hover-shadow transition-all h-100">
                    <h4 class="fw-bold mb-3">Pajak & Akuntansi</h4>
                    <p class="text-muted small mb-4">Pelaporan keuangan dan pajak yang akurat untuk ketenangan operasional.</p>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> Laporan SPT</li>
                        <li class="mb-2 small"><i class="fas fa-check-circle text-danger me-2"></i> Jasa Pembukuan</li>
                        <li class="small"><i class="fas fa-check-circle text-danger me-2"></i> Audit Keuangan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="cta-about-area section-padding position-relative overflow-hidden" style="background: linear-gradient(135deg, #dc3545 0%, #a71d2a 100%);">
    <div class="container position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center text-white">
                <h2 class="fw-bold mb-4 display-6">Siap Mewujudkan Bisnis Impian Anda?</h2>
                <p class="mb-5 opacity-75">Bergabunglah dengan ratusan pengusaha lainnya yang telah sukses membangun bisnis mereka bersama Lawgika. Konsultasikan kebutuhan Anda sekarang gratis!</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="https://wa.me/628111111111" class="btn btn-light btn-lg px-5 py-3 rounded-pill fw-bold text-danger">
                        <i class="fab fa-whatsapp me-2"></i> Hubungi via WhatsApp
                    </a>
                    <a href="{{ url('/') }}" class="btn btn-outline-light btn-lg px-5 py-3 rounded-pill fw-bold">
                        Jelajahi Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>
    {{-- Decorative circles --}}
    <div class="position-absolute translate-middle" style="width: 300px; height: 300px; background: rgba(255,255,255,0.05); border-radius: 50%; top: 0; left: 0;"></div>
    <div class="position-absolute translate-middle" style="width: 200px; height: 200px; background: rgba(255,255,255,0.03); border-radius: 50%; bottom: 0; right: 0;"></div>
</section>

<style>
    .hover-translate-y:hover {
        transform: translateY(-10px);
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .border-white-10 {
        border-color: rgba(255,255,255,0.1) !important;
    }
    .hover-shadow:hover {
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
        border-color: transparent !important;
    }
</style>

 
@endsection