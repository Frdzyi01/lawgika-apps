@extends('layout.app')

@section('content')

<style>
    /* ===== MINIMAL CSS ===== */
    :root {
        --primary: #4e0516;
        --primary-light: #7a0a23;
        --accent: #c9a03d;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg-light: #fdf8f5;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--dark);
    }

    /* Solusi Section */
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

    /* Benefits / Why Us */
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

    /* Pricing Table 2 Column */
    .pt-pricing {
        padding: 80px 0;
        background: #fff;
    }

    .pricing-table-container {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        border-radius: 20px;
        border: 1px solid #f0e4e8;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .pricing-table-header {
        display: grid;
        grid-template-columns: 1fr 1fr;
        background: var(--bg-light);
        border-bottom: 1px solid #f0e4e8;
    }

    @media (max-width: 768px) {
        .pricing-table-header,
        .pricing-table-body {
            grid-template-columns: 1fr !important;
        }
        .pricing-column-right {
            border-left: none !important;
            border-top: 1px solid #f0e4e8;
        }
    }

    .pricing-column {
        padding: 40px;
    }

    .pricing-column-right {
        border-left: 1px solid #f0e4e8;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #fff;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .pricing-column-right:hover {
        background: #fafafa;
    }

    .pricing-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .pricing-subtitle {
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .pricing-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary);
        margin-bottom: 20px;
    }

    .pricing-benefit-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pricing-benefit-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        font-size: 1.05rem;
        color: #334155;
    }

    .pricing-benefit-list li i {
        color: var(--primary);
        font-size: 1.2rem;
    }

    .pricing-column-right .pricing-benefit-list {
        display: inline-block;
        text-align: left;
        margin-bottom: 30px;
    }

    /* FAQ */
    .pt-faq {
        padding: 80px 0;
        background: var(--bg-light);
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

    /* Section Title Umum */
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

    /* Utilities */
    .img-fluid-rounded {
        border-radius: 20px;
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.08);
    }
</style>

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative"
    style="
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.7) 0%, rgba(45, 6, 16, 0.7) 50%, rgba(26, 2, 8, 0.7) 100%), 
                      url('https://images.unsplash.com/photo-1517502884422-41eaead166d4?auto=format&fit=crop&w=1200&q=80');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding-top: 200px;
    padding-bottom: 100px;
  ">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Meeting Room</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Sewa Meeting Room Nyaman & Profesional</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Fasilitas ruang meeting yang ideal untuk presentasi, workshop, diskusi tim, hingga pertemuan klien penting. Didukung dengan fasilitas lengkap dan modern.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Sewa Meeting Room</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI MEETING ROOM ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2>Ruang Meeting Representatif untuk Bisnis Anda</h2>
                <p>Mencari ruang untuk pertemuan tim, negosiasi dengan klien, atau presentasi produk? Meeting room kami didesain khusus untuk mendukung produktivitas dan impresi profesional Anda.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Lingkungan kondusif & privat</li>
                    <li><i class="fa-regular fa-circle-check"></i> Dilengkapi Smart TV & proyektor</li>
                    <li><i class="fa-regular fa-circle-check"></i> Koneksi WiFi kecepatan tinggi</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Harga Layanan →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&auto=format" alt="Meeting Room" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>FASILITAS MEETING ROOM KAMI</h2>
            <p>Dapatkan pengalaman meeting terbaik dengan fasilitas lengkap dari Lawgika</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-tv"></i>
                    </div>
                    <h4>Smart Presentation</h4>
                    <p>Dilengkapi dengan Smart TV dan peralatan presentasi modern yang mudah dihubungkan ke berbagai perangkat.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-wifi"></i>
                    </div>
                    <h4>High Speed Internet</h4>
                    <p>Koneksi WiFi berkecepatan tinggi yang stabil untuk mendukung video conference tanpa kendala.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4>Pantry & Lounge</h4>
                    <p>Akses ke ruang tunggu yang nyaman dan pantry dengan fasilitas free flow air mineral.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE MEETING ROOM ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Harga Spesial</span>
            <h2>Paket Sewa Meeting Room</h2>
            <p>Reservasi sekarang dan dapatkan penawaran terbaik</p>
        </div>

        <div class="pricing-table-container">
            <div class="pricing-table-header">
                {{-- KIRI --}}
                <div class="pricing-column">
                    <h3 class="pricing-title">MEETING ROOM</h3>
                    <div class="pricing-subtitle">HARGA JUAL</div>
                    
                    <ul class="pricing-benefit-list mt-4">
                        <li><i class="fa-solid fa-circle"></i> Ruang Meeting Nyaman & Profesional</li>
                        <li><i class="fa-solid fa-circle"></i> Smart TV</li>
                        <li><i class="fa-solid fa-circle"></i> Akses Wifi</li>
                        <li><i class="fa-solid fa-circle"></i> Ruangan ber AC</li>
                        <li><i class="fa-solid fa-circle"></i> Layanan Print, Scan dan Fotocopy</li>
                        <li><i class="fa-solid fa-circle"></i> Ruang Tunggu dan Pantry</li>
                    </ul>
                </div>
                
                {{-- KANAN --}}
                <a href="{{ route('meeting-room.order') }}" style="text-decoration:none; color:inherit;">
                    <div class="pricing-column pricing-column-right h-100">
                        <h3 class="pricing-title">PAKET MEETING ROOM</h3>
                        <div class="pricing-subtitle">60 JAM / TAHUN</div>
                        <div class="pricing-price">Rp 1.000.000</div>
                        
                        <ul class="pricing-benefit-list">
                            <li><i class="fa-solid fa-check"></i> Ruang Meeting Nyaman</li>
                            <li><i class="fa-solid fa-check"></i> Smart TV</li>
                            <li><i class="fa-solid fa-check"></i> Akses Wifi</li>
                            <li><i class="fa-solid fa-check"></i> Ruangan ber AC</li>
                            <li><i class="fa-solid fa-check"></i> Print, Scan, Copy</li>
                            <li><i class="fa-solid fa-check"></i> Ruang Tunggu & Pantry</li>
                        </ul>
                        
                        <span class="btn-outline-brand mt-auto" style="border-radius:8px; width:100%;">Reservasi Sekarang</span>
                    </div>
                </a>
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
            <h2>FAQ terkait Meeting Room</h2>
            <p>Pertanyaan yang paling sering diajukan seputar layanan sewa ruangan</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Berapa kapasitas maksimal ruang meeting? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Kapasitas standar ruang meeting kami adalah 8-10 orang, sangat cocok untuk diskusi privat, presentasi tertutup, maupun workshop kecil.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah saya bisa memesan di luar jam kerja? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, pemesanan di luar jam operasional standar dapat dilakukan dengan perjanjian dan konfirmasi sebelumnya. Silakan hubungi tim kami untuk pengaturannya.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah boleh membawa makanan dari luar? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Boleh, namun kami menyarankan untuk tetap menjaga kebersihan ruangan. Anda juga dapat meminta bantuan tim kami jika membutuhkan layanan catering tambahan.</div>
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