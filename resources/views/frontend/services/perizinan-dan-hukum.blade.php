@extends('layout.app')

@section('content')

<style>
    /* ===== MINIMAL CSS & VARIABLES ===== */
    :root {
        --primary: #4e0516;
        --primary-light: #7a0a23;
        --accent: #c9a03d;
        --accent-green: #10b981;
        --accent-green-hover: #059669;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg-light: #fdf8f5;
        --card-bg: #ffffff;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--dark);
    }

    /* Utilities */
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
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0 0 15px;
    }

    .section-title p {
        color: var(--gray);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .img-fluid-rounded {
        border-radius: 20px;
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.08);
    }

    /* ===== SOLUSI SECTION ===== */
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
        padding: 12px 30px;
        border: 2px solid var(--primary);
        color: var(--primary);
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.15s ease;
    }

    .btn-outline-brand:hover {
        background: var(--primary);
        color: #fff;
    }

    /* ===== WHY US SECTION ===== */
    .why-us-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #fdf8f5 0%, #fff8f3 100%);
        position: relative;
    }

    .why-us-section .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .why-us-section .section-header h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1e1b2b;
        margin-bottom: 10px;
    }

    .why-us-section .section-header p {
        color: #64748b;
        font-size: 1.1rem;
    }

    .why-us-card {
        background: #fff;
        padding: 35px 25px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
        border: 1px solid #f5e6e8;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }

    .why-us-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(78, 5, 22, 0.08);
        border-color: #c9a03d;
    }

    .why-us-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #4e0516 0%, #7a0a23 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .why-us-icon i {
        font-size: 2.2rem;
        color: #fff;
    }

    .why-us-card h4 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e1b2b;
        margin-bottom: 12px;
    }

    .why-us-card p {
        color: #64748b;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* ===== SERVICE CARDS (List Layanan) ===== */
    .service-card {
        background-color: var(--card-bg);
        border-radius: 50px;
        padding: 16px 25px 16px 40px;
        position: relative;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        border: 1px solid #f0e4e8;
    }

    .service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.08);
        border-color: #d1fae5;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: -1px;
        bottom: -1px;
        left: -1px;
        width: 14px;
        background-color: var(--accent-green);
        border-radius: 50px 0 0 50px;
        transition: background-color 0.25s;
    }

    .service-card:hover::before {
        background-color: var(--accent-green-hover);
    }

    .service-title {
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        font-size: 1.1rem;
        letter-spacing: -0.3px;
    }

    .btn-detail {
        background-color: var(--accent-green);
        color: #ffffff;
        border-radius: 50px;
        padding: 10px 24px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        transition: background-color 0.25s ease, transform 0.25s ease;
    }

    .service-card:hover .btn-detail {
        background-color: var(--accent-green-hover);
        color: #ffffff;
        transform: scale(1.02);
    }

    @media (max-width: 576px) {
        .service-card {
            flex-direction: column;
            align-items: flex-start;
            border-radius: 20px;
            padding: 20px 20px 20px 35px;
            gap: 15px;
        }

        .service-card::before {
            border-radius: 20px 0 0 20px;
            width: 10px;
        }

        .service-title {
            font-size: 1.05rem;
        }

        .btn-detail {
            width: 100%;
            justify-content: center;
        }
    }

    /* ===== FAQ SECTION ===== */
    .pt-faq {
        padding: 80px 0;
        background: #fff;
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
</style>

{{-- ===== HERO SECTION ===== --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.85) 0%, rgba(45, 6, 16, 0.85) 50%, rgba(26, 2, 8, 0.85) 100%), 
                      url('https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding-top: 200px;
    padding-bottom: 100px;
  ">
    <div class="container position-relative z-1">
        <div class="row align-items-center text-center text-lg-start">
            <div class="col-lg-8 mx-auto mx-lg-0">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Legal Services</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Layanan Perizinan & Hukum</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.15rem; max-width: 600px;">
                        Solusi legalitas, perizinan bisnis, dan perlindungan hukum profesional untuk menjaga kepatuhan dan keamanan perusahaan Anda.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center justify-content-lg-end mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Perizinan & Hukum</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI LEGALITAS (Teks Disesuaikan) ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2>Solusi Legalitas & Perizinan Terpadu Untuk Bisnis Anda</h2>
                <p>Memastikan legalitas bisnis bukan sekadar formalitas, melainkan fondasi keamanan perusahaan Anda. Kami mendampingi pengurusan perizinan dasar hingga perlindungan aset hukum secara profesional dan efisien.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Pengurusan perizinan OSS & NIB yang cepat</li>
                    <li><i class="fa-regular fa-circle-check"></i> Perlindungan Merek & Kekayaan Intelektual (HAKI)</li>
                    <li><i class="fa-regular fa-circle-check"></i> Drafting & review kontrak bisnis yang aman</li>
                </ul>
                <a href="#layanan" class="btn-outline-brand">Lihat Semua Layanan →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&auto=format" alt="Legal Business Consultation" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS (Teks Disesuaikan) ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>MENGAPA MEMILIH KAMI?</h2>
            <p>Keunggulan mempercayakan urusan perizinan dan hukum perusahaan Anda kepada Lawgika</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>
                    <h4>Kepatuhan Terjamin</h4>
                    <p>Menjamin seluruh izin operasional dan dokumen hukum Anda sesuai dengan regulasi pemerintah terbaru.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <h4>Proses Transparan</h4>
                    <p>Alur pengurusan yang jelas, waktu pengerjaan terukur, dan progres yang selalu kami laporkan secara berkala.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                    <h4>Konsultan Ahli</h4>
                    <p>Ditangani langsung oleh tim spesialis perizinan dan profesional hukum bisnis yang berpengalaman.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== MAIN CONTENT (Daftar Layanan) ===== --}}
<section id="layanan" class="py-5" style="background-color: #faf5f2; min-height: 50vh;">
    <div class="container py-5">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Katalog Layanan Hukum</span>
            <h2>Pilih Layanan Kami</h2>
            <p>Pilih spesifikasi layanan hukum yang sesuai dengan skala dan kebutuhan operasional perusahaan Anda saat ini.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9 col-md-11">
                <div class="d-flex flex-column gap-3">

                    {{-- NIB & OSS --}}
                    <a href="{{ url('/nib-dan-oss') }}" class="service-card">
                        <h3 class="service-title">NIB & OSS</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    {{-- HAKI / Pendaftaran Merek --}}
                    <a href="{{ url('/haki') }}" class="service-card">
                        <h3 class="service-title">HAKI / Pendaftaran Merek</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    {{-- Laporan LKPM --}}
                    <a href="{{ url('/laporan-lkpm') }}" class="service-card">
                        <h3 class="service-title">Laporan LKPM</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    {{-- Sertifikat ISO --}}
                    <a href="{{ url('/sertifikat-iso') }}" class="service-card">
                        <h3 class="service-title">Sertifikat ISO</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    {{-- Surat Keterangan Tidak Pailit --}}
                    <a href="{{ url('/surat-keterangan-tidak-pailit') }}" class="service-card">
                        <h3 class="service-title">Surat Keterangan Tidak Pailit</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

                    {{-- Drafting Perjanjian Bisnis --}}
                    <a href="{{ url('/drafting-review-perjanjian-bisnis') }}" class="service-card">
                        <h3 class="service-title">Drafting Perjanjian Bisnis</h3>
                        <span class="btn-detail">Lebih Detail <i class="fa-solid fa-arrow-right"></i></span>
                    </a>

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
            <span class="subtitle">Bantuan Sentral</span>
            <h2>FAQ Perizinan & Hukum</h2>
            <p>Pertanyaan yang paling sering diajukan seputar layanan legalitas perusahaan</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Berapa lama proses pengurusan NIB dan OSS? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Dengan kelengkapan dokumen yang sesuai, proses penerbitan Nomor Induk Berusaha (NIB) melalui sistem OSS berbasis risiko biasanya dapat diselesaikan dalam waktu 1-3 hari kerja.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Mengapa perusahaan saya wajib mendaftarkan merek (HAKI)? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Mendaftarkan merek memberikan hak eksklusif secara hukum untuk menggunakan merek tersebut, serta melindungi bisnis Anda dari plagiarisme atau klaim pihak lain yang dapat merugikan aset perusahaan.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah pelaporan LKPM wajib untuk semua perusahaan? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, Laporan Kegiatan Penanaman Modal (LKPM) wajib dilaporkan secara berkala oleh setiap pelaku usaha yang memiliki NIB, sesuai dengan skala usaha dan nilai investasinya.</div>
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