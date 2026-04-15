@extends('layout.app')

@section('content')

<style>
    /* Premium Legal & Accounting Agency Design System */
    :root {
        --law-maroon: #800000;
        --law-maroon-dark: #4a0000;
        --law-maroon-light: #a52a2a;
        --law-gold: #D4AF37;
        --law-gray-bg: #fafbfc;
        --law-text: #1e293b;
        --law-text-muted: #64748b;
        --success-green: #10b981;
        --warning-amber: #f59e0b;
        --info-blue: #3b82f6;
    }

    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: var(--law-text);
        background-color: var(--law-gray-bg);
    }

    .section-padding {
        padding: 80px 0;
    }

    /* Trust Bar - Modern Card Style */
    .trust-wrapper {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .trust-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
    }

    .trust-card {
        background: #fff;
        padding: 30px 24px;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border: 1px solid rgba(128, 0, 0, 0.05);
        text-align: center;
    }

    .trust-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 60px rgba(128, 0, 0, 0.12);
        border-color: var(--law-maroon-light);
    }

    .trust-icon-wrapper {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
        color: var(--law-maroon);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 28px;
        margin: 0 auto 20px;
        transition: all 0.3s ease;
    }

    .trust-card:hover .trust-icon-wrapper {
        background: var(--law-maroon);
        color: #fff;
        transform: rotate(5deg) scale(1.05);
    }

    .trust-card h5 {
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--law-text);
    }

    .trust-card p {
        color: var(--law-text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* Section Header */
    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-tag {
        display: inline-block;
        background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
        color: var(--law-maroon);
        padding: 6px 20px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--law-text);
        letter-spacing: -1px;
        margin-bottom: 16px;
    }

    .section-header p {
        font-size: 1.1rem;
        color: var(--law-text-muted);
        max-width: 700px;
        margin: 0 auto;
    }

    /* Service Overview Cards */
    .service-overview {
        padding: 80px 0;
        background: #fff;
    }

    .service-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 30px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .service-card {
        background: #fff;
        border-radius: 24px;
        padding: 40px 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.4s ease;
        border: 1px solid #f0e4e8;
        position: relative;
        overflow: hidden;
    }

    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--law-maroon) 0%, var(--law-maroon-light) 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
        transform-origin: left;
    }

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 50px rgba(128, 0, 0, 0.1);
    }

    .service-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--law-maroon) 0%, var(--law-maroon-light) 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        font-size: 30px;
        margin-bottom: 24px;
    }

    .service-card h3 {
        font-weight: 700;
        margin-bottom: 16px;
        color: var(--law-text);
        font-size: 1.5rem;
    }

    .service-card p {
        color: var(--law-text-muted);
        line-height: 1.7;
        margin-bottom: 24px;
    }

    .service-features {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .service-features li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        color: var(--law-text);
        font-size: 1rem;
    }

    .service-features li i {
        color: var(--success-green);
        font-size: 1.2rem;
    }

    /* Keuntungan Section */
    .benefits-section {
        padding: 80px 0;
        background: linear-gradient(180deg, #fafbfc 0%, #fff 100%);
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }

    .benefit-card {
        background: #fff;
        border-radius: 20px;
        padding: 35px 25px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #f0e4e8;
        text-align: center;
        height: 100%;
    }

    .benefit-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 40px rgba(128, 0, 0, 0.1);
        border-color: var(--law-maroon-light);
    }

    .benefit-number {
        width: 50px;
        height: 50px;
        background: var(--law-maroon);
        color: #fff;
        font-weight: 800;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        margin: 0 auto 20px;
    }

    .benefit-card h4 {
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--law-text);
        font-size: 1.2rem;
    }

    .benefit-card p {
        color: var(--law-text-muted);
        line-height: 1.7;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    /* Pricing Calculator Section */
    .pricing-section {
        padding: 80px 0;
        background: #fff;
    }

    .pricing-calculator {
        background: #fff;
        border-radius: 30px;
        padding: 50px;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0e4e8;
        max-width: 900px;
        margin: 0 auto;
    }

    .pricing-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .pricing-badge {
        background: var(--law-maroon);
        color: #fff;
        padding: 4px 16px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 16px;
    }

    .pricing-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .pricing-option {
        text-align: center;
        padding: 30px 20px;
        border-radius: 20px;
        background: #fafbfc;
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
    }

    .pricing-option:hover,
    .pricing-option.selected {
        background: #fff5f5;
        border-color: var(--law-maroon);
    }

    .pricing-option h6 {
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--law-text);
    }

    .pricing-option .price {
        font-size: 2rem;
        font-weight: 800;
        color: var(--law-maroon);
        margin-bottom: 8px;
    }

    .pricing-option .price small {
        font-size: 0.9rem;
        font-weight: 400;
        color: var(--law-text-muted);
    }

    .pricing-option p {
        color: var(--law-text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    .btn-calculate {
        background: var(--law-maroon);
        color: #fff;
        border: none;
        padding: 16px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1.1rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .btn-calculate:hover {
        background: var(--law-maroon-dark);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(128, 0, 0, 0.3);
    }

    /* Features Comparison Table */
    .comparison-section {
        padding: 80px 0;
        background: #fafbfc;
    }

    .comparison-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
    }

    .comparison-table thead th {
        background: var(--law-maroon);
        color: #fff;
        padding: 20px;
        font-weight: 600;
        text-align: center;
        border: none;
    }

    .comparison-table thead th:first-child {
        text-align: left;
        background: #2d3436;
    }

    .comparison-table tbody td {
        padding: 18px 20px;
        border-bottom: 1px solid #f0e4e8;
        text-align: center;
        color: var(--law-text);
    }

    .comparison-table tbody td:first-child {
        text-align: left;
        font-weight: 500;
        background: #fafbfc;
    }

    .comparison-table tbody tr:last-child td {
        border-bottom: none;
    }

    .check-icon {
        color: var(--success-green);
        font-size: 1.3rem;
    }

    .check-icon.muted {
        color: #cbd5e1;
    }

    /* Process Timeline */
    .timeline-modern {
        padding: 80px 0;
        background: #fff;
    }

    .timeline-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        max-width: 1000px;
        margin: 0 auto;
    }

    .timeline-steps::before {
        content: '';
        position: absolute;
        top: 50px;
        left: 10%;
        width: 80%;
        height: 3px;
        background: linear-gradient(90deg, var(--law-maroon) 0%, var(--law-maroon-light) 50%, #e0e0e0 100%);
        z-index: 1;
    }

    .step-item {
        text-align: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-circle {
        width: 100px;
        height: 100px;
        background: #fff;
        border: 3px solid var(--law-maroon);
        border-radius: 30px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(128, 0, 0, 0.1);
    }

    .step-item:hover .step-circle {
        background: var(--law-maroon);
        transform: scale(1.05) rotate(3deg);
    }

    .step-circle .step-number {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--law-maroon);
        line-height: 1;
    }

    .step-item:hover .step-circle .step-number {
        color: #fff;
    }

    .step-circle i {
        font-size: 1.8rem;
        color: var(--law-maroon);
    }

    .step-item:hover .step-circle i {
        color: #fff;
    }

    .step-item h5 {
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--law-text);
    }

    .step-item p {
        color: var(--law-text-muted);
        font-size: 0.9rem;
        padding: 0 10px;
    }

    /* Visual Break - Parallax Style */
    .visual-break-premium {
        padding: 120px 0;
        background: linear-gradient(135deg, rgba(128, 0, 0, 0.92) 0%, rgba(74, 0, 0, 0.97) 100%),
            url('https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&q=80&w=2000') center center / cover no-repeat fixed;
        position: relative;
        overflow: hidden;
    }

    .visual-break-premium::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M50 50c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c-5.523 0-10-4.477-10-10zM10 10c0-5.523 4.477-10 10-10s10 4.477 10 10-4.477 10-10 10c-5.523 0-10-4.477-10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .break-content {
        position: relative;
        z-index: 2;
        max-width: 700px;
    }

    .break-badge {
        background: rgba(212, 175, 55, 0.2);
        backdrop-filter: blur(10px);
        color: var(--law-gold);
        padding: 8px 24px;
        border-radius: 50px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 24px;
        border: 1px solid rgba(212, 175, 55, 0.3);
    }

    .break-content h2 {
        font-size: 2.8rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 20px;
        letter-spacing: -1px;
    }

    .break-content p {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.8;
        margin-bottom: 32px;
    }

    /* FAQ Section */
    .faq-modern {
        padding: 80px 0;
        background: #fafbfc;
    }

    .faq-grid {
        max-width: 900px;
        margin: 0 auto;
    }

    .faq-item-modern {
        background: #fff;
        border-radius: 16px;
        margin-bottom: 16px;
        border: 1px solid #f0e4e8;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-item-modern:hover {
        box-shadow: 0 10px 30px rgba(128, 0, 0, 0.08);
    }

    .faq-question-modern {
        padding: 24px 30px;
        font-weight: 700;
        color: var(--law-text);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
        background: #fff;
        transition: all 0.3s ease;
    }

    .faq-question-modern:hover {
        background: #fff5f5;
    }

    .faq-question-modern i {
        color: var(--law-maroon);
        transition: transform 0.3s ease;
        font-size: 1.2rem;
    }

    .faq-answer-modern {
        padding: 0 30px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s ease, padding 0.4s ease;
        color: var(--law-text-muted);
        line-height: 1.8;
        background: #fff;
    }

    .faq-item-modern.active .faq-answer-modern {
        padding: 0 30px 24px;
        max-height: 500px;
    }

    .faq-item-modern.active .faq-question-modern i {
        transform: rotate(180deg);
    }

    .faq-item-modern.active .faq-question-modern {
        background: #fff5f5;
        color: var(--law-maroon);
    }

    /* CTA Section */
    .cta-modern {
        padding: 80px 0;
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        position: relative;
        overflow: hidden;
    }

    .cta-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(128, 0, 0, 0.3) 0%, transparent 70%);
        border-radius: 50%;
    }

    .cta-card {
        background: linear-gradient(135deg, #fff 0%, #fafbfc 100%);
        border-radius: 30px;
        padding: 60px;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.2);
        position: relative;
        z-index: 2;
    }

    .cta-card h3 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--law-text);
        margin-bottom: 16px;
    }

    .cta-card p {
        font-size: 1.1rem;
        color: var(--law-text-muted);
        margin-bottom: 30px;
    }

    .btn-cta {
        background: var(--law-maroon);
        color: #fff;
        border: none;
        padding: 16px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-cta:hover {
        background: var(--law-maroon-dark);
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(128, 0, 0, 0.3);
        color: #fff;
    }

    .btn-cta-outline {
        background: transparent;
        color: var(--law-text);
        border: 2px solid #e0e0e0;
        padding: 16px 40px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 12px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-cta-outline:hover {
        border-color: var(--law-maroon);
        color: var(--law-maroon);
        transform: translateY(-3px);
    }

    /* Animations */
    .fade-up {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeUp 0.8s forwards;
    }

    @keyframes fadeUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 991px) {
        .trust-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .service-grid {
            grid-template-columns: 1fr;
        }

        .benefits-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .pricing-options {
            grid-template-columns: 1fr;
        }

        .timeline-steps {
            flex-direction: column;
            gap: 30px;
        }

        .timeline-steps::before {
            display: none;
        }

        .section-header h2 {
            font-size: 2rem;
        }

        .break-content h2 {
            font-size: 2.2rem;
        }

        .cta-card {
            padding: 40px 30px;
        }

        .cta-card h3 {
            font-size: 1.8rem;
        }

        .page-title-area {
            padding-top: 100px !important;
            padding-bottom: 50px !important;
        }
    }

    @media (max-width: 767px) {
        .trust-grid {
            grid-template-columns: 1fr;
        }

        .benefits-grid {
            grid-template-columns: 1fr;
        }

        .comparison-table {
            font-size: 0.85rem;
        }

        .comparison-table thead th,
        .comparison-table tbody td {
            padding: 12px 10px;
        }
    }
</style>

{{-- Breadcrumb / Header Area (Style Berita) --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Layanan Payroll</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Kelola Payroll dengan Mudah & Akurat</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Layanan pengelolaan payroll profesional untuk perhitungan gaji, potongan, serta administrasi penggajian karyawan.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="#" class="text-white text-decoration-none">Layanan</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Layanan Payroll</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- Service Overview Section --}}
<section class="service-overview">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Layanan Profesional</span>
            <h2>Pembukuan & Perpajakan Profesional</h2>
            <p>Layanan terintegrasi untuk kebutuhan pembukuan dan perpajakan perusahaan Anda</p>
        </div>
        <div class="service-grid">
            <div class="service-card fade-up" style="animation-delay: 0.1s">
                <div class="service-icon">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <h3>Pembukuan Perusahaan</h3>
                <p>Layanan pembukuan profesional untuk mencatat seluruh transaksi keuangan perusahaan secara rapi, akurat, dan sesuai standar akuntansi yang berlaku.</p>
                <ul class="service-features">
                    <li><i class="bi bi-check-circle-fill"></i> Pencatatan transaksi harian</li>
                    <li><i class="bi bi-check-circle-fill"></i> Rekonsiliasi bank bulanan</li>
                    <li><i class="bi bi-check-circle-fill"></i> Penyusunan laporan keuangan</li>
                    <li><i class="bi bi-check-circle-fill"></i> Jurnal penyesuaian & penutupan</li>
                </ul>
            </div>
            <div class="service-card fade-up" style="animation-delay: 0.2s">
                <div class="service-icon">
                    <i class="bi bi-receipt-cutoff"></i>
                </div>
                <h3>Perpajakan Perusahaan</h3>
                <p>Layanan perpajakan komprehensif untuk memastikan kepatuhan pajak perusahaan Anda, mulai dari perhitungan, pelaporan, hingga konsultasi perpajakan.</p>
                <ul class="service-features">
                    <li><i class="bi bi-check-circle-fill"></i> Perhitungan & pelaporan PPh 21/23/25</li>
                    <li><i class="bi bi-check-circle-fill"></i> Pelaporan PPN masa & tahunan</li>
                    <li><i class="bi bi-check-circle-fill"></i> Penyusunan SPT Tahunan Badan</li>
                    <li><i class="bi bi-check-circle-fill"></i> Pendampingan pemeriksaan pajak</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Trust Bar --}}
<!-- <div class="trust-wrapper">
    <div class="container">
        <div class="trust-grid">
            <div class="trust-card fade-up" style="animation-delay: 0.1s">
                <div class="trust-icon-wrapper">
                    <i class="bi bi-calculator"></i>
                </div>
                <h5>Hitung Akurat</h5>
                <p>Perhitungan gaji, lembur, dan potongan presisi</p>
            </div>
            <div class="trust-card fade-up" style="animation-delay: 0.2s">
                <div class="trust-icon-wrapper">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <h5>Slip Gaji Digital</h5>
                <p>Slip gaji profesional dan mudah diakses</p>
            </div>
            <div class="trust-card fade-up" style="animation-delay: 0.3s">
                <div class="trust-icon-wrapper">
                    <i class="bi bi-bank"></i>
                </div>
                <h5>BPJS & Pajak</h5>
                <p>Perhitungan dan pelaporan BPJS & PPh 21</p>
            </div>
            <div class="trust-card fade-up" style="animation-delay: 0.4s">
                <div class="trust-icon-wrapper">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h5>Kerahasiaan Data</h5>
                <p>Data karyawan terjamin keamanannya</p>
            </div>
        </div>
    </div>
</div> -->



{{-- Keuntungan Section --}}
<section class="benefits-section">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Mengapa Memilih Kami</span>
            <h2>Keuntungan Menggunakan Jasa Lawgika.co.id</h2>
            <p>Dapatkan layanan pajak & pembukuan terbaik dengan berbagai keuntungan eksklusif</p>
        </div>
        <div class="benefits-grid">
            <div class="benefit-card fade-up" style="animation-delay: 0.1s">
                <div class="benefit-number">1</div>
                <h4>Dikelola Tim Profesional</h4>
                <p>Pembukuan dan perpajakan perusahaan Anda dikelola oleh tim profesional berpengalaman di bidang akuntansi dan perpajakan.</p>
            </div>
            <div class="benefit-card fade-up" style="animation-delay: 0.2s">
                <div class="benefit-number">2</div>
                <h4>Kepatuhan Pajak Terjamin</h4>
                <p>Kami memastikan seluruh kewajiban perpajakan perusahaan Anda terpenuhi tepat waktu dan sesuai regulasi terbaru.</p>
            </div>
            <div class="benefit-card fade-up" style="animation-delay: 0.3s">
                <div class="benefit-number">3</div>
                <h4>Hemat Waktu & Biaya</h4>
                <p>Efisiensi waktu dan biaya operasional dengan menyerahkan urusan pembukuan dan pajak kepada ahlinya.</p>
            </div>
            <div class="benefit-card fade-up" style="animation-delay: 0.4s">
                <div class="benefit-number">4</div>
                <h4>Assessment Kebutuhan</h4>
                <p>Kami menyediakan assessment untuk mendapatkan paket layanan yang tepat sesuai kebutuhan perusahaan Anda.</p>
            </div>
        </div>
    </div>
</section>

{{-- Visual Break --}}
<section class="visual-break-premium">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="break-content fade-up">
                    <span class="break-badge">⭐ Fokus pada Bisnis Utama Anda</span>
                    <h2>Serahkan Urusan Payroll & Pembukuan kepada Ahlinya</h2>
                    <p>Dengan layanan profesional kami, Anda dapat menghemat waktu, mengurangi risiko kesalahan, dan memastikan kepatuhan terhadap regulasi perpajakan dan ketenagakerjaan.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <div class="bg-white bg-opacity-10 rounded-4 p-4" style="backdrop-filter: blur(10px);">
                            <h5 class="text-white mb-2">Hemat Biaya</h5>
                            <p class="text-white-50 mb-0">Hingga 40% biaya operasional</p>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-4 p-4" style="backdrop-filter: blur(10px);">
                            <h5 class="text-white mb-2">Nol Risiko Sanksi</h5>
                            <p class="text-white-50 mb-0">Kepatuhan pajak 100% terjamin</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=400&q=80" alt="Payroll Illustration" class="img-fluid rounded-4 shadow-lg" style="border: 4px solid rgba(255,255,255,0.2);">
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FAQ Section --}}
<section class="faq-modern">
    <div class="container">
        <div class="section-header">
            <span class="section-tag">Pertanyaan Umum</span>
            <h2>FAQ Layanan Payroll & Pembukuan</h2>
            <p>Jawaban atas pertanyaan yang sering diajukan tentang layanan kami</p>
        </div>
        <div class="faq-grid">
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Apa saja yang termasuk dalam layanan payroll?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Layanan payroll kami mencakup perhitungan gaji pokok, tunjangan, lembur, bonus, potongan, PPh 21, BPJS Ketenagakerjaan, BPJS Kesehatan, pembuatan slip gaji digital, serta pelaporan pajak bulanan dan tahunan.
                </div>
            </div>
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Apakah ada assessment sebelum memilih paket?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Ya, kami menyediakan assessment gratis untuk memahami kebutuhan spesifik perusahaan Anda. Hal ini memastikan Anda mendapatkan paket layanan yang paling sesuai dengan skala bisnis dan budget Anda.
                </div>
            </div>
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Berapa biaya layanan payroll dan pembukuan?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Biaya layanan payroll kami mulai dari Rp 25.000 per karyawan per bulan. Untuk layanan pembukuan dan perpajakan, biaya disesuaikan dengan volume transaksi dan kompleksitas bisnis. Hubungi kami untuk konsultasi gratis.
                </div>
            </div>
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Apakah data perusahaan kami aman?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Ya, kami sangat menjaga kerahasiaan data klien. Seluruh data disimpan dalam sistem yang terenkripsi dan hanya dapat diakses oleh tim yang berwenang. Kami juga bersedia menandatangani Perjanjian Kerahasiaan (NDA).
                </div>
            </div>
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Bagaimana dengan pelaporan SPT Tahunan?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Layanan kami mencakup penyusunan dan pelaporan SPT Tahunan Badan serta pembuatan Form 1721 A1 untuk seluruh karyawan. Tim profesional kami memastikan semua pelaporan tepat waktu dan sesuai ketentuan.
                </div>
            </div>
            <div class="faq-item-modern">
                <div class="faq-question-modern">
                    Berapa lama proses setup awal?
                    <i class="bi bi-chevron-down"></i>
                </div>
                <div class="faq-answer-modern">
                    Proses setup awal (onboarding) biasanya memakan waktu 3-5 hari kerja, tergantung kelengkapan data yang diberikan. Tim kami akan memandu Anda melalui seluruh proses pengumpulan data yang diperlukan.
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<!-- <section class="cta-modern">
    <div class="container">
        <div class="cta-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3>Siap Optimalkan Pengelolaan Keuangan Bisnis Anda?</h3>
                    <p>Hubungi kami sekarang untuk assessment gratis dan dapatkan rekomendasi paket layanan yang tepat untuk perusahaan Anda.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="https://wa.me/628123456789" class="btn-cta">
                            <i class="bi bi-whatsapp"></i> Chat via WhatsApp
                        </a>
                        <a href="#" class="btn-cta-outline">
                            <i class="bi bi-calendar-check"></i> Jadwalkan Assessment
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 text-end d-none d-lg-block">
                    <i class="bi bi-graph-up-arrow" style="font-size: 120px; color: var(--law-maroon); opacity: 0.1;"></i>
                </div>
            </div>
        </div>
    </div>
</section> -->

{{-- FAQ Toggle & Pricing Script --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // FAQ Toggle
        document.querySelectorAll('.faq-question-modern').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.closest('.faq-item-modern');
                document.querySelectorAll('.faq-item-modern').forEach(faq => {
                    if (faq !== parent) faq.classList.remove('active');
                });
                parent.classList.toggle('active');
            });
        });

        // Pricing option selection
        document.querySelectorAll('.pricing-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.pricing-option').forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    });
</script>

@endsection