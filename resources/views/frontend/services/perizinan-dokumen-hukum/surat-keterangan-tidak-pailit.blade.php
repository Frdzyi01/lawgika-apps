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
        /* Ganti: Legal Document & Court */
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
            url('https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        /* Ganti: Courthouse/Legal Building */
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
                <li class="breadcrumb-item active small" aria-current="page">Surat Keterangan Tidak Pailit</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Hero Section --}}
<section class="hero-agency">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="section-label" style="color: rgba(255,255,255,0.7)">Dokumen Legalitas Perusahaan</span>
            <h1 class="hero-title">Surat Keterangan Tidak Pailit Resmi & Terpercaya</h1>
            <p class="hero-subtitle">
                Menyediakan layanan pengurusan Surat Keterangan Tidak Pailit untuk membantu memperoleh dokumen resmi yang menyatakan bahwa individu atau perusahaan tidak sedang dalam proses kepailitan.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="https://wa.me/628123456789" class="btn-white">Konsultasi Gratis</a>
                <a href="#sktp-details" class="btn-outline-white">Pelajari Selengkapnya</a>
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
                    <div class="trust-icon"><i class="bi bi-bank"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Resmi Pengadilan</h6>
                        <small class="text-muted">Diterbitkan PN Niaga</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Proses Cepat</h6>
                        <small class="text-muted">Selesai 3-5 hari kerja</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-building"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold">Individu & Perusahaan</h6>
                        <small class="text-muted">Layanan lengkap</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="sktp-details" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Pengadilan Niaga">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">01. Definisi & Fungsi</span>
                    <h2 class="display-5 fw-bold mb-3">Apa itu Surat Keterangan Tidak Pailit?</h2>
                    <p class="text-muted mb-4 lead">
                        Surat Keterangan Tidak Pailit adalah dokumen resmi yang diterbitkan oleh Pengadilan Niaga yang menyatakan bahwa seseorang atau badan usaha tidak sedang dalam keadaan pailit atau Penundaan Kewajiban Pembayaran Utang (PKPU).
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Syarat wajib tender proyek pemerintah</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Persyaratan pengajuan kredit bank</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Bagian dari due diligence bisnis</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img src="https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=1000&q=80" class="w-100" alt="Dokumen Legal">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label">02. Layanan Kami</span>
                    <h2 class="display-5 fw-bold mb-3">Pengurusan SK Tidak Pailit</h2>
                    <p class="text-muted mb-4 lead">
                        Kami membantu Anda mengurus Surat Keterangan Tidak Pailit dari awal hingga dokumen selesai. Tim kami akan memastikan seluruh persyaratan terpenuhi dan proses berjalan lancar tanpa hambatan.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pengecekan nama di sistem Pengadilan Niaga</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Penyiapan dokumen persyaratan</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> Pengambilan dokumen di Pengadilan Niaga</li>
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
            <h2 class="fw-bold display-5">Proses Pengurusan SK Tidak Pailit</h2>
        </div>
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">1</div>
                        <h5 class="fw-bold">Konsultasi & Cek Data</h5>
                        <p class="text-muted small px-lg-4">Verifikasi nama individu/perusahaan di database kepailitan.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">2</div>
                        <h5 class="fw-bold">Penyiapan Dokumen</h5>
                        <p class="text-muted small px-lg-4">Kami siapkan formulir permohonan dan dokumen pendukung.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="timeline-item">
                        <div class="timeline-circle">3</div>
                        <h5 class="fw-bold">Proses & Terbit</h5>
                        <p class="text-muted small px-lg-4">Pengajuan ke PN Niaga dan pengambilan surat keterangan.</p>
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
                <span class="badge-premium bg-white shadow-sm mb-3">Kepercayaan & Kredibilitas</span>
                <h2 class="display-3 fw-bold text-white mb-3">Buktikan Kredibilitas Bisnis Anda dengan Dokumen Resmi</h2>
                <p class="text-white-50 lead mb-0">Surat Keterangan Tidak Pailit adalah bukti bahwa perusahaan Anda sehat secara finansial dan layak dipercaya untuk bekerja sama.</p>
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
            <h2>FAQ terkait Surat Keterangan Tidak Pailit</h2>
            <p>Pertanyaan yang paling sering diajukan seputar SK Tidak Pailit</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        Apa itu Surat Keterangan Tidak Pailit?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Surat Keterangan Tidak Pailit adalah dokumen resmi yang diterbitkan oleh Pengadilan Niaga yang menyatakan bahwa seseorang (individu) atau badan hukum (perusahaan) tidak tercatat dalam daftar pihak yang sedang dalam proses kepailitan atau Penundaan Kewajiban Pembayaran Utang (PKPU).
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Kapan Surat Keterangan Tidak Pailit dibutuhkan?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Dokumen ini umumnya diperlukan untuk: mengikuti tender/lelang proyek pemerintah, pengajuan kredit/pinjaman bank, proses merger & akuisisi perusahaan, due diligence calon mitra bisnis, dan persyaratan administratif lainnya.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Berapa lama proses pengurusan SK Tidak Pailit?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Proses pengurusan Surat Keterangan Tidak Pailit umumnya memakan waktu 3-5 hari kerja setelah seluruh dokumen persyaratan lengkap. Kami akan membantu mempercepat proses dengan memastikan semua persyaratan terpenuhi.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apa saja persyaratan pengurusan SK Tidak Pailit?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Untuk individu: Fotokopi KTP dan NPWP. Untuk perusahaan: Akta Pendirian, SK Kemenkumham, NPWP Perusahaan, KTP dan NPWP Direktur. Persyaratan dapat berbeda tergantung Pengadilan Niaga setempat.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Apakah SK Tidak Pailit memiliki masa berlaku?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Surat Keterangan Tidak Pailit tidak memiliki masa berlaku yang tercantum secara spesifik. Namun, umumnya instansi peminta dokumen menetapkan batas waktu 3-6 bulan sejak tanggal penerbitan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        Di Pengadilan Niaga mana saja bisa mengurus?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Surat Keterangan Tidak Pailit dapat diurus di Pengadilan Niaga yang wilayah hukumnya meliputi domisili pemohon. Saat ini terdapat 5 Pengadilan Niaga di Indonesia: Jakarta Pusat, Surabaya, Semarang, Medan, dan Makassar. Kami dapat membantu pengurusan di seluruh lokasi tersebut.
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