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
        background: url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat;
        /* Ganti: Contract signing / Legal drafting */
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
            url('https://images.unsplash.com/photo-1505664194779-8beaceb93744?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        /* Ganti: Legal books & gavel */
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
                <li class="breadcrumb-item active small" aria-current="page">Drafting & Review Perjanjian</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Hero Section --}}
<section class="hero-agency">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="section-label" style="color: rgba(255,255,255,0.7)">Layanan Hukum Profesional</span>
            <h1 class="hero-title">Drafting & Review Perjanjian Bisnis</h1>
            <p class="hero-subtitle">
                Layanan drafting dan review perjanjian bisnis untuk membantu menyusun serta meninjau dokumen perjanjian agar jelas, melindungi kepentingan para pihak, dan sesuai dengan ketentuan hukum yang berlaku.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="https://wa.me/628123456789" class="btn-white">Konsultasi Gratis</a>
                <a href="#contract-details" class="btn-outline-white">Pelajari Selengkapnya</a>
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
                    <div class="trust-icon"><i class="bi bi-pencil-square"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Ahli Hukum</h6>
                        <small class="text-muted">Advokat berpengalaman</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-shield-shaded"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Mitigasi Risiko</h6>
                        <small class="text-muted">Cegah sengketa</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-translate"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Bilingual</h6>
                        <small class="text-muted">Indonesia & Inggris</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="contract-details" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1000&q=80" class="w-100" alt="Drafting Perjanjian">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">01. Layanan Drafting</span>
                    <h2 class="display-5 fw-bold mb-3">Penyusunan Perjanjian (Drafting)</h2>
                    <p class="text-muted mb-4 lead">
                        Tim advokat kami siap membantu menyusun berbagai jenis perjanjian bisnis yang sesuai dengan kebutuhan spesifik dan karakteristik transaksi Anda, memastikan setiap klausul melindungi kepentingan hukum Anda.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Perjanjian Kerjasama/Joint Venture</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Perjanjian Jual Beli & Sewa Menyewa</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Perjanjian Kerahasiaan (NDA) & Kerja</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1000&q=80" class="w-100" alt="Review Perjanjian">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">02. Layanan Review</span>
                    <h2 class="display-5 fw-bold mb-3">Telaah & Analisis Perjanjian (Review)</h2>
                    <p class="text-muted mb-4 lead">
                        Menerima draft perjanjian dari pihak lain? Jangan tandatangani sebelum kami telaah. Kami akan mengidentifikasi klausul-klausul berisiko, ketidakseimbangan hak dan kewajiban, serta potensi kerugian di masa depan.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Identifikasi klausul merugikan</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Rekomendasi perubahan & negosiasi</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Legal opinion tertulis</li>
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
            <h2 class="fw-bold display-5">Proses Layanan Kami</h2>
        </div>
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">1</div>
                        <h5 class="fw-bold">Konsultasi Awal</h5>
                        <p class="text-muted small px-lg-4">Diskusi kebutuhan, konteks bisnis, dan poin-poin penting.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">2</div>
                        <h5 class="fw-bold">Drafting / Review</h5>
                        <p class="text-muted small px-lg-4">Penyusunan atau analisis mendalam terhadap perjanjian.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="timeline-item">
                        <div class="timeline-circle">3</div>
                        <h5 class="fw-bold">Finalisasi & Serah Terima</h5>
                        <p class="text-muted small px-lg-4">Revisi akhir dan penyerahan dokumen siap tanda tangan.</p>
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
                <span class="badge-premium bg-white shadow-sm mb-3">Keamanan Transaksi Bisnis</span>
                <h2 class="display-3 fw-bold text-white mb-3">Perjanjian yang Kuat, Bisnis yang Aman</h2>
                <p class="text-white-50 lead mb-0">Jangan biarkan perjanjian yang ambigu atau tidak seimbang menjadi sumber masalah di kemudian hari. Lindungi bisnis Anda dengan kontrak yang solid.</p>
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
            <h2>FAQ terkait Drafting & Review Perjanjian</h2>
            <p>Pertanyaan yang paling sering diajukan seputar layanan perjanjian bisnis</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        Apa pentingnya perjanjian tertulis dalam bisnis?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Perjanjian tertulis berfungsi sebagai alat bukti yang sah di mata hukum jika terjadi sengketa di kemudian hari. Selain itu, perjanjian yang jelas dan komprehensif memberikan kepastian hukum, mengatur hak dan kewajiban para pihak, serta meminimalkan potensi kesalahpahaman.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apa perbedaan drafting dan review perjanjian?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Drafting adalah proses menyusun perjanjian dari awal (nol) sesuai dengan kebutuhan dan keinginan klien. Review adalah proses menelaah dan menganalisis draft perjanjian yang sudah ada (biasanya dibuat oleh pihak lain) untuk mengidentifikasi risiko dan memberikan rekomendasi perubahan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Berapa lama proses drafting atau review perjanjian?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Waktu pengerjaan bergantung pada kompleksitas perjanjian. Untuk perjanjian standar, proses drafting atau review dapat diselesaikan dalam 3-5 hari kerja. Untuk perjanjian kompleks seperti joint venture atau M&A, waktu yang dibutuhkan bisa 1-2 minggu.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah bisa membuat perjanjian bilingual (Indonesia-Inggris)?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Ya, kami menyediakan layanan drafting dan review perjanjian dalam dua bahasa (Indonesia dan Inggris) untuk memenuhi kebutuhan transaksi bisnis lintas negara. Perjanjian bilingual dibuat dengan memperhatikan kesesuaian terminologi hukum di kedua bahasa.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Klausul apa saja yang wajib ada dalam perjanjian bisnis?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Setidaknya ada beberapa klausul penting: Identitas Para Pihak, Maksud dan Tujuan, Hak dan Kewajiban, Jangka Waktu, Nilai Kontrak dan Cara Pembayaran, Wanprestasi dan Sanksi, Keadaan Memaksa (Force Majeure), Penyelesaian Sengketa, dan Ketentuan Penutup.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah perjanjian di bawah tangan sah secara hukum?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Ya, perjanjian di bawah tangan (tidak dibuat di hadapan notaris) tetap sah dan mengikat para pihak secara hukum. Namun, untuk perjanjian tertentu seperti pendirian PT, jual beli tanah, atau perjanjian kredit dengan jaminan, wajib dibuat dalam bentuk akta notaris.
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