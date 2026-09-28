@extends('layout.app')

@section('title', 'Syarat & Ketentuan Sewa Podcast Room Lawgika')
@section('meta_description', 'Syarat dan ketentuan resmi sewa studio podcast di Lawgika. Ketahui aturan reservasi, pembayaran, penggunaan studio, operator, dan tanggung jawab penyewa sebelum melakukan pemesanan.')
@section('meta_keywords', 'Syarat Ketentuan Sewa Podcast Room, Studio Podcast Lawgika, Aturan Sewa Podcast Jakarta, Reservasi Podcast Room')

@section('content')
<style>
    /* ===== SYARAT & KETENTUAN STYLES ===== */
    :root {
        --lawgika-primary: #4e0516;
        --lawgika-primary-dark: #32030e;
        --lawgika-primary-light: #7a0a23;
        --lawgika-gold: #c9a03d;
        --lawgika-dark: #1e1b2b;
        --lawgika-gray: #64748b;
        --lawgika-bg-light: #fdf8f5;
    }

    .snk-hero {
        background: linear-gradient(135deg, var(--lawgika-primary-dark) 0%, var(--lawgika-primary) 50%, var(--lawgika-primary-light) 100%);
        color: #ffffff;
        padding: 70px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .snk-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(201, 160, 61, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .snk-hero h1 {
        font-size: 2.3rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .snk-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 780px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .snk-container {
        padding: 50px 0 80px;
        background-color: #f8fafc;
    }

    .snk-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 40px;
    }

    .snk-card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--lawgika-dark);
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .snk-card-title i {
        font-size: 1.5rem;
        color: var(--lawgika-primary);
    }

    .snk-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
        counter-reset: snk-counter;
    }

    .snk-list li {
        counter-increment: snk-counter;
        position: relative;
        padding-left: 48px;
        margin-bottom: 20px;
        font-size: 1rem;
        line-height: 1.75;
        color: #334155;
    }

    .snk-list li:last-child {
        margin-bottom: 0;
    }

    .snk-list li::before {
        content: counter(snk-counter);
        position: absolute;
        left: 0;
        top: 2px;
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, var(--lawgika-primary), var(--lawgika-primary-light));
        color: #ffffff;
        font-weight: 700;
        font-size: 0.85rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .snk-closing-box {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        margin-top: 40px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .snk-closing-box p {
        font-size: 1.1rem;
        color: #334155;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .snk-closing-box p:last-child {
        margin-bottom: 0;
    }

    .btn-wa-snk {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        background-color: #25d366;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.05rem;
        padding: 14px 32px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.35);
        transition: all 0.3s ease;
    }

    .btn-wa-snk:hover {
        background-color: #1da851;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.45);
    }

    @media (max-width: 768px) {
        .snk-hero {
            padding: 50px 0 40px;
        }
        .snk-hero h1 {
            font-size: 1.6rem;
        }
        .snk-card {
            padding: 24px 20px;
        }
        .snk-card-title {
            font-size: 1.25rem;
        }
        .snk-list li {
            padding-left: 42px;
            font-size: 0.95rem;
            margin-bottom: 18px;
        }
        .snk-list li::before {
            width: 28px;
            height: 28px;
            font-size: 0.8rem;
        }
        .snk-closing-box {
            padding: 25px 20px;
        }
    }
</style>

<!-- Hero Section -->
<section class="snk-hero text-center">
    <div class="container">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">
            <i class="fa-solid fa-file-contract me-1"></i> Syarat & Ketentuan
        </span>
        <h1>Syarat & Ketentuan Sewa Podcast Room Lawgika</h1>
        <p>
            Harap membaca dan memahami seluruh syarat dan ketentuan berikut sebelum melakukan reservasi studio podcast di Lawgika.
        </p>
    </div>
</section>

<!-- Main Content -->
<section class="snk-container">
    <div class="container" style="max-width: 960px;">

        <div class="snk-card">
            <h2 class="snk-card-title">
                <i class="fa-solid fa-clipboard-list"></i> 📌 Syarat & Ketentuan Sewa Studio Podcast Lawgika
            </h2>
            <ol class="snk-list">
                <li>Reservasi wajib dilakukan dan dikonfirmasi selambat-lambatnya 1 (satu) hari sebelum jadwal penggunaan studio.</li>
                <li>Reservasi dianggap valid setelah pembayaran diterima oleh Lawgika.</li>
                <li>Penyewa wajib hadir 30 menit sebelum jadwal dimulai.</li>
                <li>Keterlambatan kedatangan tidak menambah durasi penggunaan studio.</li>
                <li>Customer disarankan membawa/menyediakan media penyimpanan data untuk mentransfer file audio dan visual.</li>
                <li>Apabila penyewa ingin membawa properti, peralatan, dekorasi, atau kebutuhan tambahan lainnya, wajib menginformasikannya kepada Lawgika sebelum hari penggunaan.</li>
                <li>Apabila memerlukan penggunaan TV mohon kirimkan file ukuran 4K dengan format MP4 sebelum hari penggunaan.</li>
                <li>Perpanjangan waktu penggunaan (overtime) akan dikenakan biaya tambahan sesuai tarif yang berlaku dan ditagihkan setelah sesi podcast selesai.</li>
                <li>Reschedule dapat dilakukan maksimal 1 (satu) kali dengan pemberitahuan minimal 1 x 24 jam sebelum jadwal penggunaan dan bergantung pada ketersediaan studio.</li>
                <li>Pembatalan setelah pembayaran dilakukan tidak dapat dikembalikan (non-refundable).</li>
                <li>Penyewa bertanggung jawab atas setiap kerusakan atau kehilangan fasilitas yang disebabkan oleh penyewa maupun pihak yang dibawa oleh penyewa.</li>
                <li>Operator podcast hanya membantu untuk setup audio dan visual diawal sesuai permintaan customer.</li>
                <li>Jika membutuhkan setup khusus atau bantuan operator, mohon diinformasikan saat reservasi.</li>
                <li>Bagi customer yang tidak menggunakan jasa operator dari Lawgika, maka setiap kendala setelah video take bukan menjadi tanggung jawab dari Lawgika</li>
                <li>Dimohon untuk mengantisipasi kemungkinan kamera mati, overheat, hal yang berkaitan dengan recording berhenti.</li>
                <li>Untuk customer yang tidak menggunakan layanan operator disarankan untuk menyediakan operator yang berpengalaman dengan podcast.</li>
                <li>Lawgika hanya bertindak sebagai penyedia fasilitas studio podcast dan tidak bertanggung jawab atas isi, materi, pernyataan, maupun konsekuensi hukum dari konten yang dibuat atau disampaikan oleh penyewa.</li>
                <li>Dilarang menggunakan studio untuk kegiatan yang bertentangan dengan peraturan perundang-undangan, ketertiban umum, atau norma yang berlaku.</li>
                <li>Dengan melakukan pembayaran dan/atau menggunakan fasilitas studio, penyewa dianggap telah membaca, memahami, dan menyetujui seluruh syarat dan ketentuan ini.</li>
            </ol>
        </div>

        <!-- Closing & CTA -->
        <div class="snk-closing-box">
            <p>
                Jika Anda memiliki pertanyaan lebih lanjut mengenai sewa studio podcast, silakan hubungi tim <strong>Lawgika</strong>.
            </p>
            <a href="https://wa.me/6281112088600?text=Halo%20Admin%20Lawgika%2C%20saya%20ingin%20bertanya%20mengenai%20sewa%20podcast%20room"
               target="_blank"
               rel="noopener noreferrer"
               class="btn-wa-snk">
                <i class="fa-brands fa-whatsapp fs-4"></i> Hubungi Kami via WhatsApp
            </a>
        </div>

    </div>
</section>
@endsection
