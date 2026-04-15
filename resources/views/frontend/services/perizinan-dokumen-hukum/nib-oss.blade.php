@extends('layout.app')

@section('content')
{{-- Custom CSS for this page --}}
<style>
    :root {
        --maroon-primary: #800000;
        --maroon-dark: #600000;
        --gray-light: #f8f9fa;
        --text-dark: #212529;
    }

    .bg-maroon {
        background-color: var(--maroon-primary);
    }

    .text-maroon {
        color: var(--maroon-primary);
    }

    .btn-maroon {
        background-color: var(--maroon-primary);
        color: white;
        border-radius: 8px;
        padding: 12px 24px;
        transition: all 0.3s ease;
    }

    .btn-maroon:hover {
        background-color: var(--maroon-dark);
        color: white;
        transform: translateY(-2px);
    }

    .btn-outline-maroon {
        border: 2px solid var(--maroon-primary);
        color: var(--maroon-primary);
        border-radius: 8px;
        padding: 12px 24px;
        transition: all 0.3s ease;
    }

    .btn-outline-maroon:hover {
        background-color: var(--maroon-primary);
        color: white;
    }

    .service-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: white;
        height: 100%;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        margin-bottom: 20px;
        font-size: 24px;
    }

    .process-step {
        text-align: center;
        position: relative;
    }

    .step-number {
        width: 50px;
        height: 50px;
        background-color: var(--maroon-primary);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-weight: bold;
        z-index: 2;
        position: relative;
    }

    .process-line {
        position: absolute;
        top: 25px;
        left: 50%;
        width: 100%;
        height: 2px;
        background-color: #dee2e6;
        z-index: 1;
    }

    @media (max-width: 768px) {
        .process-line {
            display: none;
        }
    }

    .full-image-section {
        height: 400px;
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .full-image-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
    }

    .fade-in {
        animation: fadeIn 1s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

{{-- Breadcrumb --}}
<section class="py-5 mt-5 bg-light">
    <div class="container mt-5 pt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-maroon text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-maroon text-decoration-none">Layanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">NIB & OSS</li>
            </ol>
        </nav>
        <h1 class="fw-bold display-4">NIB & OSS</h1>
    </div>
</section>

{{-- Hero Section --}}
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 fade-in">
                <span class="text-maroon fw-bold text-uppercase tracking-wider">Perizinan & Dokumen Hukum</span>
                <h2 class="display-5 fw-bold mt-2">Solusi Perizinan Usaha Terpadu</h2>
                <p class="lead text-muted mt-3 mb-4">
                    Menyediakan layanan pengurusan, pendaftaran, serta perubahan data NIB dan OSS untuk membantu memastikan legalitas usaha Anda terdaftar dan terkelola dengan benar.
                </p>
                <div class="d-flex gap-3">
                    <a href="https://wa.me/628123456789" class="btn btn-maroon">Konsultasi Sekarang</a>
                    <a href="#proses" class="btn btn-outline-maroon">Mulai Pengurusan</a>
                </div>
            </div>
            <div class="col-lg-6 fade-in">
                <div class="rounded-4 overflow-hidden shadow-lg transform-hover transition">
                    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&q=80&w=1000" class="img-fluid" alt="NIB OSS Perizinan">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Detail Layanan --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Layanan Kami</h2>
            <div class="bg-maroon mx-auto" style="width: 50px; height: 3px;"></div>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card service-card p-4">
                    <div class="icon-box bg-maroon text-white">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>
                    <h5 class="fw-bold">Pengurusan NIB</h5>
                    <p class="text-muted small">Pendaftaran Nomor Induk Berusaha untuk segala jenis unit usaha.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card service-card p-4">
                    <div class="icon-box bg-maroon text-white">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="fw-bold">Pendaftaran OSS</h5>
                    <p class="text-muted small">Integrasi perizinan usaha melalui sistem OSS RBA terbaru.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card service-card p-4">
                    <div class="icon-box bg-maroon text-white">
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <h5 class="fw-bold">Perubahan Data</h5>
                    <p class="text-muted small">Update data perusahaan, alamat, hingga KBLI usaha Anda.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card service-card p-4">
                    <div class="icon-box bg-maroon text-white">
                        <i class="bi bi-chat-dots"></i>
                    </div>
                    <h5 class="fw-bold">Konsultasi Legalitas</h5>
                    <p class="text-muted small">Diskusi mendalam mengenai kebutuhan perizinan bisnis Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Why Choose Us --}}
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <div class="row g-4 text-center">
                    <div class="col-md-4">
                        <div class="p-4 border rounded-4 transition hover-shadow">
                            <i class="bi bi-lightning-charge text-maroon display-4 mb-3"></i>
                            <h4 class="fw-bold">Proses Cepat</h4>
                            <p class="text-muted">Kami menjamin pengurusan izin usaha Anda selesai dalam waktu sesingkat mungkin.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-4 transition hover-shadow">
                            <i class="bi bi-patch-check text-maroon display-4 mb-3"></i>
                            <h4 class="fw-bold">Legal & Terpercaya</h4>
                            <p class="text-muted">Dikelola oleh tenaga ahli hukum profesional yang berpengalaman.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 border rounded-4 transition hover-shadow">
                            <i class="bi bi-headset text-maroon display-4 mb-3"></i>
                            <h4 class="fw-bold">Konsultasi Mudah</h4>
                            <p class="text-muted">Layanan customer support yang responsif dan siap membantu kapan saja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Proses Layanan --}}
<section id="proses" class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Proses Layanan</h2>
            <p class="text-muted">Langkah mudah mengurus legalitas usaha Anda</p>
        </div>
        <div class="row">
            <div class="col-md-4 process-step mb-4 mb-md-0">
                <div class="process-line"></div>
                <div class="step-number">1</div>
                <h5 class="fw-bold">Konsultasi</h5>
                <p class="text-muted small px-lg-5">Sampaikan kebutuhan bisnis dan dokumen pendukung Anda.</p>
            </div>
            <div class="col-md-4 process-step mb-4 mb-md-0">
                <div class="process-line"></div>
                <div class="step-number">2</div>
                <h5 class="fw-bold">Pengurusan Data</h5>
                <p class="text-muted small px-lg-5">Tim kami memproses pendaftaran dokumen ke sistem OSS.</p>
            </div>
            <div class="col-md-4 process-step">
                <div class="step-number">3</div>
                <h5 class="fw-bold">NIB & OSS Terbit</h5>
                <p class="text-muted small px-lg-5">Dokumen legalitas resmi terbit dan siap digunakan.</p>
            </div>
        </div>
    </div>
</section>

{{-- Full Image Section --}}
<section class="full-image-section" style="background-image: url('https://images.unsplash.com/photo-1560472355-536de3962603?auto=format&fit=crop&q=80&w=1600');">
    <div class="container position-relative z-1 text-center">
        <h2 class="display-3 fw-bold text-white mb-0">Solusi Legalitas Usaha Anda</h2>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-5 bg-maroon text-white text-center">
    <div class="container py-4">
        <h2 class="fw-bold mb-4">Butuh Bantuan Pengurusan NIB & OSS?</h2>
        <a href="https://wa.me/628123456789" class="btn btn-light btn-lg px-5 rounded-pill fw-bold text-maroon py-3 shadow">
            Hubungi Kami Sekarang
        </a>
    </div>
</section>

@endsection
