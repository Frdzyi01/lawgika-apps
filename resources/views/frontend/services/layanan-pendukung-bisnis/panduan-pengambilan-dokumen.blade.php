@extends('layout.app')

@section('title', 'Panduan Pengambilan Dokumen Virtual Office | Lawgika')
@section('meta_description', 'Panduan resmi dan SOP pengambilan dokumen & surat masuk bagi klien Virtual Office Lawgika.')
@section('meta_keywords', 'Panduan Pengambilan Dokumen, Virtual Office, Lawgika, Surat Masuk, SOP Dokumen')

@section('content')
<style>
    /* ===== PANDUAN PENGAMBILAN DOKUMEN STYLES ===== */
    :root {
        --lawgika-primary: #4e0516;
        --lawgika-primary-dark: #32030e;
        --lawgika-primary-light: #7a0a23;
        --lawgika-gold: #c9a03d;
        --lawgika-dark: #1e1b2b;
        --lawgika-gray: #64748b;
        --lawgika-bg-light: #fdf8f5;
    }

    .guide-hero {
        background: linear-gradient(135deg, var(--lawgika-primary-dark) 0%, var(--lawgika-primary) 50%, var(--lawgika-primary-light) 100%);
        color: #ffffff;
        padding: 70px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .guide-hero::before {
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

    .guide-hero h1 {
        font-size: 2.3rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        color: #ffffff;
        margin-bottom: 15px;
    }

    .guide-hero p {
        font-size: 1.1rem;
        color: rgba(255, 255, 255, 0.9);
        max-width: 780px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .guide-container {
        padding: 50px 0 80px;
        background-color: #f8fafc;
    }

    .guide-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .guide-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .guide-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }

    .badge-metode-1 {
        background-color: #e0f2fe;
        color: #0369a1;
    }

    .badge-metode-2 {
        background-color: #fef3c7;
        color: #b45309;
    }

    .badge-metode-3 {
        background-color: #f3e8ff;
        color: #6b21a8;
    }

    .guide-card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--lawgika-dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .guide-card-title i {
        font-size: 1.5rem;
        color: var(--lawgika-primary);
    }

    .guide-checklist {
        list-style: none;
        padding-left: 0;
        margin-bottom: 20px;
    }

    .guide-checklist li {
        position: relative;
        padding-left: 32px;
        margin-bottom: 14px;
        font-size: 1rem;
        line-height: 1.6;
        color: #334155;
    }

    .guide-checklist li i.check-icon {
        position: absolute;
        left: 0;
        top: 3px;
        font-size: 1.1rem;
        color: #16a34a;
    }

    /* Warning Yellow Highlight Box */
    .warning-box {
        background-color: #fffbeb;
        border-left: 4px solid #f59e0b;
        padding: 16px 20px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .warning-box p {
        margin: 0;
        font-weight: 700;
        color: #92400e;
        font-size: 0.95rem;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .warning-box p i {
        font-size: 1.2rem;
        color: #d97706;
        margin-top: 2px;
    }

    /* Info Blue Box for Catatan */
    .info-box {
        background-color: #eff6ff;
        border: 1px solid #bfdbfe;
        border-left: 5px solid #2563eb;
        border-radius: 12px;
        padding: 30px;
        margin-top: 40px;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.06);
    }

    .info-box h5 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e40af;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-box ul {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .info-box ul li {
        position: relative;
        padding-left: 30px;
        margin-bottom: 15px;
        font-size: 1rem;
        line-height: 1.6;
        color: #1e3a8a;
    }

    .info-box ul li:last-child {
        margin-bottom: 0;
    }

    .info-box ul li i {
        position: absolute;
        left: 0;
        top: 4px;
        font-size: 1rem;
        color: #2563eb;
    }

    /* Closing & CTA Box */
    .closing-box {
        background: #ffffff;
        border-radius: 16px;
        padding: 40px;
        text-align: center;
        margin-top: 40px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    .closing-box p {
        font-size: 1.1rem;
        color: #334155;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .btn-wa-contact {
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

    .btn-wa-contact:hover {
        background-color: #1da851;
        color: #ffffff;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(37, 211, 102, 0.45);
    }

    @media (max-width: 768px) {
        .guide-hero {
            padding: 50px 0 40px;
        }
        .guide-hero h1 {
            font-size: 1.75rem;
        }
        .guide-card {
            padding: 20px;
        }
        .info-box {
            padding: 20px;
        }
        .closing-box {
            padding: 25px 20px;
        }
    }
</style>

<!-- Hero Section -->
<section class="guide-hero text-center">
    <div class="container">
        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold text-uppercase mb-3" style="font-size: 0.8rem; letter-spacing: 1px;">
            <i class="fa-solid fa-shield-halved me-1"></i> Standar Operasional Prosedur
        </span>
        <h1>Panduan Pengambilan Dokumen Virtual Office</h1>
        <p>
            Terima kasih telah menggunakan layanan Virtual Office Lawgika.
            Silakan ikuti panduan berikut ketika mengambil dokumen atau surat yang telah diterima oleh tim kami.
        </p>
    </div>
</section>

<!-- Main Guide Content -->
<section class="guide-container">
    <div class="container" style="max-width: 960px;">

        <div class="text-center mb-5">
            <h2 class="fw-bold text-dark" style="font-size: 1.8rem;">Metode Pengambilan Dokumen</h2>
            <p class="text-muted">Pilih salah satu dari 3 metode resmi pengambilan dokumen di bawah ini:</p>
        </div>

        <!-- 1. Ambil / Kurir Sendiri -->
        <div class="guide-card">
            <span class="guide-badge badge-metode-1">
                <i class="fa-solid fa-user-check"></i> Metode 1
            </span>
            <h3 class="guide-card-title">
                <i class="fa-solid fa-person-walking-luggage"></i> Ambil / Kurir Sendiri
            </h3>
            <ul class="guide-checklist">
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Menunjukkan bukti screenshot konfirmasi penerimaan dokumen dari resepsionis Lawgika Office.
                </li>
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Melakukan tanda tangan pada formulir serah terima dokumen di lokasi resepsionis.
                </li>
            </ul>
        </div>

        <!-- 2. Pickup oleh Ojol -->
        <div class="guide-card">
            <span class="guide-badge badge-metode-2">
                <i class="fa-solid fa-motorcycle"></i> Metode 2
            </span>
            <h3 class="guide-card-title">
                <i class="fa-solid fa-truck-fast"></i> Pickup oleh Ojol (Gojek / Grab / Maxim, dll.)
            </h3>
            <ul class="guide-checklist">
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Melakukan konfirmasi terlebih dahulu melalui WhatsApp kepada resepsionis dengan mengirimkan screenshot nama driver dan nomor order.
                </li>
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Memberikan catatan khusus kepada driver mengenai rincian dokumen yang akan diambil beserta nama lengkap penerima.
                </li>
            </ul>
          
        </div>

        <!-- 3. Special Request -->
        <div class="guide-card">
            <span class="guide-badge badge-metode-3">
                <i class="fa-solid fa-envelope-open-text"></i> Metode 3
            </span>
            <h3 class="guide-card-title">
                <i class="fa-solid fa-envelope-circle-check"></i> Special Request (Digital Scan / Foto)
            </h3>
            <p class="text-muted mb-3">
                Untuk mempermudah urusan Anda tanpa perlu datang ke lokasi, tim Lawgika dapat membantu membuka dan memfoto/memindai (scan) isi surat atau dokumen.
            </p>
            <h6 class="fw-bold text-dark mb-2">Syarat & Ketentuan:</h6>
            <ul class="guide-checklist">
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Kirim permintaan melalui email ke: 
                    <a href="mailto:informasi@lawgika.co.id" class="fw-bold text-primary text-decoration-underline">informasi@lawgika.co.id</a>
                    dengan Subject: <strong>SPECIAL REQUEST</strong>
                </li>
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Sertakan pernyataan tertulis bahwa Anda memberikan kuasa resmi kepada Lawgika Office untuk membuka dokumen tersebut.
                </li>
                <li>
                    <i class="fa-solid fa-circle-check check-icon"></i>
                    Sertakan instruksi penanganan akhir: 
                    <span class="badge bg-light text-dark border">Simpan untuk diambil</span> ATAU <span class="badge bg-light text-dark border">Destroy (hancurkan) setelah selesai dipindai</span>.
                </li>
            </ul>
        </div>

        <!-- Info Box: Catatan Penting -->
        <div class="info-box">
            <h5>
                <i class="fa-solid fa-circle-info"></i> CATATAN PENTING
            </h5>
            <ul>
                <li>
                    <i class="fa-solid fa-circle-exclamation"></i>
                    Bila Anda ingin menambah atau mengubah PIC penerima notifikasi surat, silakan langsung membalas pesan WhatsApp resmi kami.
                </li>
                <li>
                    <i class="fa-solid fa-clock"></i>
                    Untuk pengambilan sendiri maupun melalui Ojol, mohon konfirmasi kepada tim resepsionis <strong>minimal 1 jam sebelumnya</strong> agar dokumen dapat dipersiapkan terlebih dahulu.
                </li>
                <li>
                    <i class="fa-solid fa-file-signature"></i>
                    Permintaan melalui email pada poin Special Request menjadi bukti persetujuan dan tanggung jawab penuh dari klien.
                </li>
            </ul>
        </div>

        <!-- Penutup & CTA -->
        <div class="closing-box">
            <p>
                Terima kasih telah mempercayakan layanan Virtual Office kepada <strong>Lawgika.co.id</strong>.<br>
                Kami mendoakan agar bisnis dan usaha Anda senantiasa berkembang dan semakin sukses. 🙏
            </p>

            <a href="https://wa.me/6281138800688?text=Halo%20Admin%20Lawgika%2C%20saya%20ingin%20bertanya%20mengenai%20pengambilan%20dokumen%20Virtual%20Office" 
               target="_blank" 
               rel="noopener noreferrer" 
               class="btn-wa-contact">
                <i class="fa-brands fa-whatsapp fs-4"></i> Hubungi Resepsionis / Admin Lawgika
            </a>
        </div>

    </div>
</section>
@endsection
