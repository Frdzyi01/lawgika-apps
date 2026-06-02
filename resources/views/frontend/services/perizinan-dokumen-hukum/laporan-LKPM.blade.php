@extends('layout.app')
@section('title', 'Jasa Laporan Lkpm & Konsultan Pajak | Lawgika')
@section('meta_description', 'Layanan Laporan Lkpm profesional untuk mengurus administrasi dan kepatuhan pajak perusahaan Anda dengan tepat waktu.')
@section('meta_keywords', 'Laporan Lkpm, Konsultan Pajak, Jasa Pembukuan, Lapor Pajak, Tax Accounting Lawgika')


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
        background: url('https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat;
        /* Ganti: Analytics & Reporting Dashboard */
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
            url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        /* Ganti: Business Report */
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

    /* Hero CTA Buttons */
    .btn-white {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: #ffffff;
        color: #800000;
        border: 2px solid #ffffff;
        padding: 14px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .btn-white:hover {
        background-color: #800000;
        color: #ffffff;
        border-color: #800000;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(128,0,0,0.35);
    }

    .btn-outline-white {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: transparent;
        color: #ffffff;
        border: 2px solid rgba(255,255,255,0.75);
        padding: 14px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-outline-white:hover {
        background-color: #ffffff;
        color: #800000;
        border-color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
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
                <li class="breadcrumb-item active small" aria-current="page" data-i18n="lkpm.breadcrumb">Pelaporan LKPM</li>
            </ol>
        </nav>
    </div>
</section>

{{-- Hero Section --}}
<section class="hero-agency">
    <div class="container">
        <div class="hero-content fade-up">
            <span class="section-label" style="color: rgba(255,255,255,0.7)" data-i18n="lkpm.hero.label">Kepatuhan & Pelaporan Investasi</span>
            <h1 class="hero-title" data-i18n="lkpm.hero.title">Pelaporan LKPM Tepat Waktu Tanpa Ribet</h1>
            <p class="hero-subtitle" data-i18n="lkpm.hero.desc">
                Menyediakan layanan pelaporan LKPM (Laporan Kegiatan Penanaman Modal) untuk membantu pelaku usaha memenuhi kewajiban pelaporan investasi melalui sistem OSS secara akurat dan tepat waktu.
            </p>
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="https://wa.me/6281112088600" class="btn-white" target="_blank" rel="noopener noreferrer">
                    <i class="fa-brands fa-whatsapp"></i> <span data-i18n="tentang.cta.primary">Konsultasi Gratis</span>
                </a>
                <a href="#lkpm-details" class="btn-outline-white">
                    <i class="fa-solid fa-circle-info"></i> <span data-i18n="hukum.hero.btn_info">Pelajari Selengkapnya</span>
                </a>
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
                    <div class="trust-icon"><i class="bi bi-calendar-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="lkpm.trust.item1_title">Tepat Waktu</h6>
                        <small class="text-muted" data-i18n="lkpm.trust.item1_desc">Bebas denda keterlambatan</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 border-end-lg">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-file-earmark-check"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="lkpm.trust.item2_title">Akurat & Valid</h6>
                        <small class="text-muted" data-i18n="lkpm.trust.item2_desc">Sesuai regulasi BKPM</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="trust-item">
                    <div class="trust-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div>
                        <h6 class="mb-0 fw-bold" data-i18n="lkpm.trust.item3_title">Pemantauan Berkala</h6>
                        <small class="text-muted" data-i18n="lkpm.trust.item3_desc">Notifikasi reminder rutin</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Zig-Zag Sections --}}
<section id="lkpm-details" class="section-padding overflow-hidden">
    <div class="container">
        {{-- Section 1 --}}
        <div class="row align-items-center mb-5 pb-lg-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Pelaporan LKPM Online">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label" data-i18n="lkpm.section1.label">01. Kewajiban Hukum</span>
                    <h2 class="display-5 fw-bold mb-3" data-i18n="lkpm.section1.title">Apa itu LKPM?</h2>
                    <p class="text-muted mb-4 lead" data-i18n="lkpm.section1.desc">
                        LKPM (Laporan Kegiatan Penanaman Modal) adalah laporan berkala yang wajib disampaikan oleh setiap pelaku usaha kepada BKPM melalui sistem OSS. Laporan ini mencakup realisasi investasi, tenaga kerja, dan perkembangan usaha.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section1.list1">Wajib bagi seluruh pemegang NIB</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section1.list2">Periode pelaporan per 3 bulan (triwulan)</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section1.list3">Sanksi administratif jika tidak melapor</span></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Section 2 --}}
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="zigzag-image-container">
                    <img loading="lazy" src="https://images.unsplash.com/photo-1543286386-713bdd548da4?auto=format&fit=crop&q=80&w=1000" class="w-100" alt="Data Realisasi Investasi">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="zigzag-text">
                    <span class="section-label" data-i18n="lkpm.section2.label">02. Layanan Profesional</span>
                    <h2 class="display-5 fw-bold mb-3" data-i18n="lkpm.section2.title">Penyusunan & Pelaporan LKPM</h2>
                    <p class="text-muted mb-4 lead" data-i18n="lkpm.section2.desc">
                        Tim kami siap membantu Anda menyusun data realisasi investasi dan tenaga kerja, menginput ke dalam sistem OSS, serta memastikan laporan tersampaikan dengan benar sebelum batas waktu yang ditentukan.
                    </p>
                    <ul class="list-unstyled mb-4">
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section2.list1">Pengumpulan data realisasi investasi</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section2.list2">Input laporan ke sistem OSS</span></li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-maroon me-2"></i> <span data-i18n="lkpm.section2.list3">Pendampingan audit kepatuhan</span></li>
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
            <span class="section-label" data-i18n="kerjasama.process.badge">Workflow</span>
            <h2 class="fw-bold display-5" data-i18n="lkpm.process.title">Proses Pelaporan LKPM</h2>
        </div>
        <div class="timeline-container">
            <div class="timeline-line"></div>
            <div class="row justify-content-center g-0">
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">1</div>
                        <h5 class="fw-bold">Pengumpulan Data</h5>
                        <p class="text-muted small px-lg-4">Kami bantu identifikasi data realisasi investasi & tenaga kerja Anda.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">
                    <div class="timeline-item">
                        <div class="timeline-circle">2</div>
                        <h5 class="fw-bold" data-i18n="lkpm.process.step2_title">Penyusunan Laporan</h5>
                        <p class="text-muted small px-lg-4">Data disusun sesuai format LKPM yang berlaku di OSS.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="timeline-item">
                        <div class="timeline-circle">3</div>
                        <h5 class="fw-bold">Submit & Bukti Terima</h5>
                        <p class="text-muted small px-lg-4">Laporan terkirim & Anda terima bukti pelaporan resmi.</p>
                    </div>
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
            <span class="subtitle" data-i18n="vo.faq.badge">Bantuan Sentral</span>
            <h2 data-i18n="lkpm.faq.title">FAQ terkait Pelaporan LKPM</h2>
            <p>Pertanyaan yang paling sering diajukan seputar Laporan Kegiatan Penanaman Modal</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q1">Apa itu LKPM dan siapa yang wajib melapor?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a1">
                        LKPM (Laporan Kegiatan Penanaman Modal) adalah laporan berkala yang wajib disampaikan oleh setiap pelaku usaha yang memiliki NIB (Nomor Induk Berusaha) kepada BKPM melalui sistem OSS. Kewajiban ini berlaku bagi seluruh badan usaha maupun perorangan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q2">Kapan batas waktu pelaporan LKPM?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a2">
                        Pelaporan LKPM dilakukan setiap 3 bulan (triwulan) dengan batas waktu sebagai berikut:
                        - Triwulan I (Jan-Mar): paling lambat 10 April
                        - Triwulan II (Apr-Jun): paling lambat 10 Juli
                        - Triwulan III (Jul-Sep): paling lambat 10 Oktober
                        - Triwulan IV (Okt-Des): paling lambat 10 Januari tahun berikutnya
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q3">Apa sanksi jika tidak melaporkan LKPM?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a3">
                        Pelaku usaha yang tidak menyampaikan LKPM tepat waktu dapat dikenakan sanksi administratif berupa peringatan tertulis, pembekuan izin usaha, hingga pencabutan izin usaha sesuai dengan Peraturan BKPM yang berlaku.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q4">Data apa saja yang dibutuhkan untuk pelaporan LKPM?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a4">
                        Data yang diperlukan antara lain: realisasi investasi (tanah, bangunan, mesin, modal kerja), jumlah tenaga kerja (laki-laki/perempuan, lokal/asing), nilai produksi/penjualan, serta kendala usaha yang dihadapi selama periode pelaporan.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q5">Apakah LKPM bisa dilaporkan nihil?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a5">
                        Ya, jika dalam periode pelaporan tidak ada realisasi investasi atau kegiatan usaha, Anda tetap wajib melaporkan LKPM dengan status "NIHIL". Ini penting untuk menjaga status kepatuhan usaha Anda di sistem OSS.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span data-i18n="lkpm.faq.q6">Bagaimana jika saya sudah terlambat melapor beberapa periode?</span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer" data-i18n="lkpm.faq.a6">
                        Jangan khawatir. Tim kami dapat membantu Anda melakukan pelaporan susulan (backlog) untuk periode-periode yang terlewat, serta memberikan pendampingan agar ke depannya tidak terjadi keterlambatan lagi.
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