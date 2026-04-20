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
        background: url('https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat;
        /* Ganti: ISO Certification / Quality Management */
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
            url('https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        /* Ganti: Quality Management Team */
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
                <li class="breadcrumb-item active small" aria-current="page">Sertifikasi ISO</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Hero Section --}}
<section class="hero-agency">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="section-label" style="color: rgba(255,255,255,0.7)">Standar Manajemen Internasional</span>
            <h1 class="hero-title">Tingkatkan Kredibilitas Bisnis dengan Sertifikasi ISO</h1>
            <p class="hero-subtitle">
                Menyediakan layanan penerbitan sertifikasi ISO untuk membantu perusahaan mempersiapkan dan memperoleh sertifikat standar manajemen sesuai dengan ketentuan yang berlaku.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="https://wa.me/628123456789" class="btn-white">Konsultasi Gratis</a>
                <a href="#iso-details" class="btn-outline-white">Pelajari Selengkapnya</a>
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
                    <div class="trust-icon"><i class="bi bi-patch-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Lembaga Terakreditasi</h6>
                        <small class="text-muted">KAN & Internasional</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-clipboard-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Pendampingan Penuh</h6>
                        <small class="text-muted">Gap analysis & audit</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-trophy"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Tingkat Kelulusan Tinggi</h6>
                        <small class="text-muted">Ratusan klien sukses</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="iso-details" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Sertifikasi ISO 9001">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">01. Manajemen Mutu</span>
                    <h2 class="display-5 fw-bold mb-3">ISO 9001:2015</h2>
                    <p class="text-muted mb-4 lead">
                        Standar internasional untuk Sistem Manajemen Mutu (SMM). Sertifikasi ini membuktikan bahwa perusahaan Anda konsisten dalam menyediakan produk dan layanan yang memenuhi kebutuhan pelanggan serta peraturan yang berlaku.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - Meningkatkan kepuasan pelanggan</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - Efisiensi proses operasional</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - Syarat tender proyek pemerintah/swasta</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=1000&q=80" class="w-100" alt="ISO Lainnya">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">02. Standar Lainnya</span>
                    <h2 class="display-5 fw-bold mb-3">ISO 14001, 45001, 27001 & 37001</h2>
                    <p class="text-muted mb-4 lead">
                        Kami juga melayani pendampingan sertifikasi untuk berbagai standar ISO lainnya sesuai dengan bidang industri dan kebutuhan spesifik perusahaan Anda.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - ISO 14001 (Sistem Manajemen Lingkungan)</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - ISO 45001 (Keselamatan & Kesehatan Kerja)</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - ISO 27001 (Sistem Manajemen Keamanan Informasi)</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> - ISO 37001 (Sistem Manajemen Anti Penyuapan)</li>
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
            <h2 class="fw-bold display-5">Proses Sertifikasi ISO</h2>
        </div>
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">1</div>
                        <h5 class="fw-bold">Gap Analysis</h5>
                        <p class="text-muted small px-lg-4">Evaluasi kesenjangan sistem manajemen perusahaan dengan standar ISO.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">2</div>
                        <h5 class="fw-bold">Penyusunan Dokumen</h5>
                        <p class="text-muted small px-lg-4">Pembuatan dan implementasi dokumen SOP serta kebijakan mutu.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="timeline-item">
                        <div class="timeline-circle">3</div>
                        <h5 class="fw-bold">Audit & Sertifikasi</h5>
                        <p class="text-muted small px-lg-4">Audit oleh badan sertifikasi terakreditasi dan penerbitan sertifikat.</p>
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
                <span class="badge-premium bg-white shadow-sm mb-3">Jaminan Mutu & Kepercayaan</span>
                <h2 class="display-3 fw-bold text-white mb-3">Sertifikasi ISO, Investasi untuk Masa Depan Bisnis Anda</h2>
                <p class="text-white-50 lead mb-0">Dapatkan pengakuan internasional dan unggul dalam persaingan global dengan standar manajemen yang diakui dunia.</p>
            </div>
        </div>
    </div>
</section>


@include('layout.partials.layanan-kami')


{{-- ===== FAQ SECTION ===== --}}
<section class="pt-faq">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Bantuan Sentral</span>
            <h2>FAQ terkait Sertifikasi ISO</h2>
            <p>Pertanyaan yang paling sering diajukan seputar proses sertifikasi ISO</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        Apa itu sertifikasi ISO dan mengapa penting?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Sertifikasi ISO adalah pengakuan internasional yang menunjukkan bahwa perusahaan telah menerapkan sistem manajemen sesuai standar yang diakui global. Sertifikasi ini penting untuk meningkatkan kredibilitas, kepercayaan pelanggan, efisiensi operasional, dan akses ke pasar global.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Berapa lama proses sertifikasi ISO?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Durasi proses sertifikasi ISO bervariasi tergantung ukuran perusahaan, kompleksitas proses, dan kesiapan sistem manajemen. Umumnya membutuhkan waktu 3-6 bulan, yang mencakup gap analysis, penyusunan dokumen, implementasi, hingga audit sertifikasi.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apa saja persyaratan untuk mendapatkan ISO 9001?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Persyaratan utama ISO 9001:2015 meliputi: dokumentasi sistem manajemen mutu (kebijakan mutu, sasaran mutu, SOP), implementasi proses yang konsisten, pemantauan kepuasan pelanggan, audit internal, dan tinjauan manajemen secara berkala.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah sertifikasi ISO berlaku seumur hidup?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Tidak. Sertifikat ISO berlaku selama 3 tahun. Selama periode tersebut, perusahaan wajib menjalani surveillance audit setiap tahun untuk memastikan sistem manajemen tetap berjalan sesuai standar. Setelah 3 tahun, perusahaan harus melakukan resertifikasi.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah perusahaan kecil bisa mendapatkan ISO?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Ya, sertifikasi ISO dapat diterapkan pada perusahaan dari berbagai ukuran, termasuk UKM. Standar ISO bersifat fleksibel dan dapat disesuaikan dengan skala dan kompleksitas bisnis Anda.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Berapa biaya untuk mendapatkan sertifikasi ISO?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Biaya sertifikasi ISO bervariasi tergantung jenis ISO, ruang lingkup, jumlah karyawan, dan kompleksitas proses bisnis. Kami menyediakan konsultasi gratis untuk memberikan estimasi biaya yang sesuai dengan kebutuhan perusahaan Anda.
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