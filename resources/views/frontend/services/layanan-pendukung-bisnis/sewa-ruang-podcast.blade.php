@extends('layout.app')

@section('content')

<style>
    /* ===== MINIMAL CSS (Sama dengan Meeting Room) ===== */
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

    /* Pricing Table */
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
        grid-template-columns: 1fr;
        /* Diubah jadi 1 kolom agar lebih fokus */
        background: var(--bg-light);
        border-bottom: 1px solid #f0e4e8;
    }

    .pricing-column {
        padding: 50px 40px;
    }

    .pricing-column-right {
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background: #fff;
        transition: background 0.3s ease;
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
        margin-bottom: 40px;
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
    background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.8) 0%, rgba(45, 6, 16, 0.8) 50%, rgba(26, 2, 8, 0.8) 100%), 
                      url('https://images.unsplash.com/photo-1590602847861-f357a9332bbc?auto=format&fit=crop&w=1200&q=80');
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
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Lawgika | Podcast Studio</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Sewa Ruang Podcast Profesional</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Studio podcast modern dengan peralatan rekaman berkualitas tinggi, soundproofing sempurna, dan tim teknis siap membantu konten terbaik Anda.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Sewa Ruang Podcast</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI PODCAST ROOM ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5 flex-row-reverse">
            <div class="col-lg-6">
                <h2>Ciptakan Konten Berkualitas Tinggi Tanpa Ribet</h2>
                <p>Tidak perlu pusing memikirkan peralatan mahal dan akustik ruangan. Bawa materi Anda, dan biarkan fasilitas studio kami menangani sisi teknis produksi audio & video Anda.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Akustik ruangan standar broadcast</li>
                    <li><i class="fa-regular fa-circle-check"></i> Mic condenser & mixer profesional</li>
                    <li><i class="fa-regular fa-circle-check"></i> Suasana nyaman, ber-AC & privat</li>
                </ul>
                <a href="#pricing" class="btn-outline-brand">Lihat Harga Paket →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1598550874175-4d0ef436c909?w=800&auto=format" alt="Podcast Studio" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== MANFAAT & FASILITAS ===== --}}
<section class="why-us-section">
    <div class="container">
        <div class="section-header">
            <h2>FASILITAS STUDIO KAMI</h2>
            <p>Standar perlengkapan terbaik untuk hasil audio yang jernih dan profesional</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-microphone-lines"></i>
                    </div>
                    <h4>Pro Audio Gear</h4>
                    <p>Dilengkapi mikrofon kondenser premium, headphone monitor, dan mixer digital standar penyiaran.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-sliders"></i>
                    </div>
                    <h4>Acoustic Treatment</h4>
                    <p>Ruangan dirancang dengan soundproofing optimal untuk mencegah gema dan kebisingan dari luar.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="why-us-card">
                    <div class="why-us-icon">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <h4>Lounge & Pantry</h4>
                    <p>Bersantai sejenak sebelum atau sesudah rekaman dengan fasilitas ruang tunggu ber-AC dan free flow air.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== PRICING TABLE PODCAST ROOM ===== --}}
<section class="pt-pricing" id="pricing">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="subtitle">Harga Spesial</span>
            <h2>Paket Sewa Studio Podcast</h2>
            <p>Pilih jadwal dan mulai produksi karya Anda sekarang</p>
        </div>

        <div class="pricing-table-container">
            <div class="pricing-table-header">
                {{-- Tengah: Beli Paket Podcast --}}
                <div class="pricing-column pricing-column-right h-100">
                    <h3 class="pricing-title">PAKET PODCAST ROOM</h3>
                    <div class="pricing-subtitle">DURASI 2 JAM</div>
                    <div class="pricing-price">Rp 500.000</div>
                    <ul class="pricing-benefit-list">
                        <li><i class="fa-solid fa-check"></i> Ruang Podcast yang proper & kedap suara</li>
                        <li><i class="fa-solid fa-check"></i> Peralatan podcast profesional</li>
                        <li><i class="fa-solid fa-check"></i> Akses Wifi & Ruangan ber-AC</li>
                        <li><i class="fa-solid fa-check"></i> Layanan Print, Scan, dan Fotocopy</li>
                        <li><i class="fa-solid fa-check"></i> Ruang Tunggu dan Pantry</li>
                    </ul>
                    <button type="button" class="btn-outline-brand mt-auto" style="border-radius:8px;max-width:300px;width:100%;background:none;cursor:pointer;"
                        onclick="openBookingModal(2, 500000)">Pilih Jadwal & Pesan</button>
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
            <h2>FAQ terkait Ruang Podcast</h2>
            <p>Pertanyaan yang paling sering diajukan seputar penyewaan studio podcast</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="faq-item">
                    <div class="faq-question">Berapa jumlah maksimal orang di dalam studio? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Studio kami dirancang optimal untuk 2 hingga 4 orang pembicara sekaligus agar kualitas audio tetap fokus dan tidak *bocor*.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah saya perlu membawa *memory card* atau *harddisk* sendiri? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Ya, kami sangat menyarankan Anda membawa *storage* sendiri untuk memudahkan pemindahan *file* raw (mentah) langsung setelah sesi rekaman selesai.</div>
                </div>
                <div class="faq-item">
                    <div class="faq-question">Apakah sudah termasuk layanan *editing* video/audio? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Paket sewa standar hanya mencakup penggunaan ruangan dan peralatan. Untuk layanan tambahan *editing* pasca produksi, silakan konsultasikan dengan tim teknis kami di lokasi.</div>
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

{{-- ===== MODAL BOOKING CALENDLY-STYLE (Dengan Logic Podcast) ===== --}}
<style>
    .booking-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .booking-modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .booking-modal {
        background: #fff;
        width: 100%;
        max-width: 900px;
        border-radius: 16px;
        display: flex;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        transform: scale(0.95);
        transition: transform 0.3s ease;
        max-height: 90vh;
    }

    .booking-modal-overlay.active .booking-modal {
        transform: scale(1);
    }

    .bm-left {
        width: 35%;
        padding: 40px;
        background: #fafafa;
        border-right: 1px solid #eaeaea;
        display: flex;
        flex-direction: column;
    }

    .bm-left h4 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 20px;
        color: #1e1b2b;
    }

    .bm-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        margin-bottom: 12px;
        font-size: 0.95rem;
    }

    .bm-right {
        width: 65%;
        padding: 40px;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .bm-close {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
        line-height: 1;
    }

    .bm-header h3 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e1b2b;
        margin-bottom: 30px;
    }

    .bm-body {
        display: flex;
        gap: 30px;
        flex-grow: 1;
    }

    .bm-calendar-col {
        flex: 1;
    }

    .bm-time-col {
        width: 180px;
        display: none;
        flex-direction: column;
    }

    .bm-time-col.active {
        display: flex;
    }

    .calendar-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .calendar-nav button {
        background: none;
        border: none;
        cursor: pointer;
        color: #4e0516;
        padding: 5px;
    }

    .calendar-nav span {
        font-weight: 600;
        color: #1e1b2b;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        text-align: center;
    }

    .calendar-day-header {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .calendar-date {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-weight: 500;
        color: #1e1b2b;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .calendar-date:hover:not(.disabled) {
        background: #f0e4e8;
        color: #4e0516;
    }

    .calendar-date.active {
        background: #4e0516;
        color: #fff;
    }

    .calendar-date.disabled {
        color: #cbd5e1;
        cursor: not-allowed;
    }

    .time-slot {
        padding: 12px;
        border: 1px solid #4e0516;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        color: #4e0516;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 10px;
        background: #fff;
    }

    .time-slot:hover {
        background: #4e0516;
        color: #fff;
    }

    .time-slot.active {
        background: #4e0516;
        color: #fff;
    }

    .time-slot.disabled {
        border-color: #cbd5e1;
        color: #cbd5e1;
        cursor: not-allowed;
        background: #f8fafc;
    }

    .bm-footer {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        border-top: 1px solid #eaeaea;
        padding-top: 20px;
    }

    .btn-bm-cancel {
        padding: 10px 20px;
        border: none;
        background: none;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-bm-submit {
        padding: 10px 24px;
        border: none;
        background: #4e0516;
        color: #fff;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .btn-bm-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .booking-modal {
            flex-direction: column;
            max-height: 95vh;
            overflow-y: auto;
        }

        .bm-left {
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #eaeaea;
            padding: 25px;
        }

        .bm-right {
            width: 100%;
            padding: 25px;
        }

        .bm-body {
            flex-direction: column;
        }

        .bm-time-col {
            width: 100%;
            margin-top: 20px;
        }
    }
</style>

<div class="booking-modal-overlay" id="bookingModalOverlay">
    <div class="booking-modal">
        <div class="bm-left">
            <h4 id="bmPackageName">Paket Podcast Room</h4>
            <div class="bm-info-item"><i class="fa-regular fa-clock"></i> <span id="bmDuration">2 Jam</span></div>
            <div class="bm-info-item"><i class="fa-solid fa-location-dot"></i> <span>Studio Offline (Lawgika Office)</span></div>
            <div style="margin-top:20px;">
                <p style="font-size:0.9rem; color:#64748b; line-height:1.6;">
                    Pilih tanggal dan waktu yang tersedia untuk produksi podcast Anda bersama Lawgika.
                </p>
            </div>
        </div>
        <div class="bm-right">
            <button class="bm-close" onclick="closeBookingModal()">&times;</button>
            <div class="bm-header">
                <h3>Pilih tanggal & waktu</h3>
            </div>
            <div class="bm-body">
                <div class="bm-calendar-col">
                    <div class="calendar-nav">
                        <button id="calPrev" onclick="changeMonth(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                        <span id="calMonthYear">Apr 2026</span>
                        <button id="calNext" onclick="changeMonth(1)"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                    <div class="calendar-grid">
                        <div class="calendar-day-header">Min</div>
                        <div class="calendar-day-header">Sen</div>
                        <div class="calendar-day-header">Sel</div>
                        <div class="calendar-day-header">Rab</div>
                        <div class="calendar-day-header">Kam</div>
                        <div class="calendar-day-header">Jum</div>
                        <div class="calendar-day-header">Sab</div>
                    </div>
                    <div class="calendar-grid" id="calDays"></div>
                </div>
                <div class="bm-time-col" id="bmTimeCol">
                    <div style="font-size:0.9rem; font-weight:600; color:#1e1b2b; margin-bottom:15px;" id="timeColTitle"></div>
                    <div id="timeSlotsContainer" style="overflow-y:auto; max-height:250px; padding-right:10px;"></div>
                </div>
            </div>
            <div class="bm-footer">
                <button class="btn-bm-cancel" onclick="closeBookingModal()">Batal</button>
                <button class="btn-bm-submit" id="btnReservasi" disabled onclick="submitBooking()">Lanjut ke Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JS Logic dari Podcast Rental, disesuaikan untuk Modal
    let selDurasi = null;
    let selPrice = 0;
    let selTanggal = null;
    let selJam = null;

    let curYear, curMonth;
    let bookedSlotsCache = {};

    const SLOTS = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
    const MONTHS = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function openBookingModal(durasi, price) {
        selDurasi = durasi;
        selPrice = price;

        selTanggal = null;
        selJam = null;
        document.getElementById('bmTimeCol').classList.remove('active');
        document.getElementById('btnReservasi').disabled = true;

        const n = new Date();
        curYear = n.getFullYear();
        curMonth = n.getMonth();
        renderCal();

        const overlay = document.getElementById('bookingModalOverlay');
        overlay.style.display = 'flex';
        // Trigger reflow
        void overlay.offsetWidth;
        overlay.classList.add('active');
    }

    function closeBookingModal() {
        const overlay = document.getElementById('bookingModalOverlay');
        overlay.classList.remove('active');
        setTimeout(() => {
            overlay.style.display = 'none';
        }, 300);
    }

    function changeMonth(d) {
        curMonth += d;
        if (curMonth > 11) {
            curMonth = 0;
            curYear++;
        }
        if (curMonth < 0) {
            curMonth = 11;
            curYear--;
        }
        renderCal();
    }

    function renderCal() {
        document.getElementById('calMonthYear').innerText = `${MONTHS[curMonth]} ${curYear}`;
        const calDays = document.getElementById('calDays');
        calDays.innerHTML = '';

        const firstDay = new Date(curYear, curMonth, 1).getDay();
        const daysInMonth = new Date(curYear, curMonth + 1, 0).getDate();
        const today = new Date();
        today.setHours(0, 0, 0, 0);

        for (let i = 0; i < firstDay; i++) {
            calDays.innerHTML += `<div></div>`;
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateObj = new Date(curYear, curMonth, d);
            const isPast = dateObj < today;
            // Jika mau disable hari minggu/sabtu bisa tambahkan || dateObj.getDay() === 0
            const disabled = isPast;

            const dateStr = `${curYear}-${String(curMonth+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
            const activeClass = (selTanggal === dateStr) ? 'active' : '';
            const disClass = disabled ? 'disabled' : '';

            if (disabled) {
                calDays.innerHTML += `<div class="calendar-date disabled">${d}</div>`;
            } else {
                calDays.innerHTML += `<div class="calendar-date ${activeClass}" onclick="pilihTgl('${dateStr}', ${d})">${d}</div>`;
            }
        }
    }

    async function pilihTgl(dateStr, dayNum) {
        selTanggal = dateStr;
        selJam = null;
        document.getElementById('btnReservasi').disabled = true;
        renderCal(); // Refresh active state

        const dayName = new Date(curYear, curMonth, dayNum).toLocaleDateString('id-ID', {
            weekday: 'long'
        });
        document.getElementById('timeColTitle').innerText = `${dayName}, ${dayNum} ${MONTHS[curMonth]}`;
        document.getElementById('bmTimeCol').classList.add('active');

        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '<div style="text-align:center;padding:20px;color:#64748b;"><i class="fa-solid fa-spinner fa-spin"></i> Mengecek ketersediaan...</div>';

        // Fetch booked slots via AJAX (Sesuai endpoint podcast)
        let booked = [];
        if (bookedSlotsCache[dateStr]) {
            booked = bookedSlotsCache[dateStr];
        } else {
            try {
                const res = await fetch(`/podcast-room/booked-slots?date=${dateStr}`);
                booked = await res.json();
                bookedSlotsCache[dateStr] = booked;
            } catch (e) {
                console.error('Gagal memuat jadwal', e);
            }
        }

        container.innerHTML = '';
        SLOTS.forEach(time => {
            if (booked.includes(time)) {
                container.innerHTML += `<div class="time-slot disabled">${time} (Penuh)</div>`;
            } else {
                container.innerHTML += `<div class="time-slot" onclick="pilihJam('${time}', this)">${time}</div>`;
            }
        });
    }

    function pilihJam(jam, el) {
        if (el.classList.contains('disabled')) return;
        selJam = jam;
        document.querySelectorAll('.time-slot').forEach(n => n.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('btnReservasi').disabled = false;
    }

    function submitBooking() {
        if (!selTanggal || !selJam || !selDurasi) return;

        // Redirect ke endpoint order podcast
        const url = `/podcast-room/order?tanggal=${selTanggal}&jam=${selJam}&durasi=${selDurasi}`;
        window.location.href = url;
    }
</script>

@endsection