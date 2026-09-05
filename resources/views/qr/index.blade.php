<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pusat Verifikasi Layanan & Dokumen Resmi - Lawgika Indonesia</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Pusat Verifikasi Resmi Layanan Lawgika Indonesia. Periksa keabsahan layanan hukum, legalitas usaha, status pesanan, dan benefit kuota ruangan secara real-time.">
    <meta name="robots" content="index, follow">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lawgika/logo.webp') }}">

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- HTML5-QRCode Scanner Library (Lightweight) -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <style>
        :root {
            --navy-dark: #0a1128;
            --navy-primary: #121e42;
            --navy-surface: #1a2b5a;
            --gold-primary: #c59b27;
            --gold-light: #fef5d8;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
            --navy-gradient: linear-gradient(165deg, #0a1128 0%, #162450 60%, #1c3066 100%);
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --bg-body: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-primary);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 100%;
            max-width: 580px;
            margin: 0 auto;
            padding: 16px;
        }

        /* ── Header Area ── */
        .hero-header {
            background: var(--navy-gradient);
            color: #ffffff;
            padding: 32px 20px 75px 20px;
            border-bottom-left-radius: 32px;
            border-bottom-right-radius: 32px;
            text-align: center;
            position: relative;
            box-shadow: 0 10px 30px rgba(10, 17, 40, 0.25);
        }

        .hero-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.4), transparent);
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.08);
            padding: 8px 18px;
            border-radius: 999px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 18px;
        }

        .brand-logo img {
            height: 28px;
            width: auto;
            display: block;
        }

        .brand-logo span {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .badge-portal {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(212, 175, 55, 0.18);
            border: 1px solid rgba(212, 175, 55, 0.4);
            color: #f6d860;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.55rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 6px;
            line-height: 1.25;
        }

        .hero-subtitle {
            font-size: 0.88rem;
            color: #cbd5e1;
            max-width: 440px;
            margin: 0 auto;
        }

        /* ── Main Content Card ── */
        .content-wrapper {
            margin-top: -50px;
            flex: 1;
        }

        .card-box {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.08);
            padding: 24px 20px;
            margin-bottom: 16px;
        }

        .card-title-custom {
            font-family: 'Outfit', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy-surface);
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }

        .card-desc-custom {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 18px;
        }

        /* Search / Input Form */
        .search-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
        }

        .input-control {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border-radius: 12px;
            border: 1.5px solid var(--border-color);
            font-size: 0.92rem;
            font-family: inherit;
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .input-control:focus {
            background: #ffffff;
            border-color: var(--navy-surface);
            box-shadow: 0 0 0 3px rgba(26, 43, 90, 0.12);
        }

        .btn-submit {
            background: var(--navy-gradient);
            color: #ffffff;
            border: none;
            padding: 13px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            opacity: 0.95;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .divider-or {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 18px 0;
            color: #94a3b8;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .divider-or::before,
        .divider-or::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .divider-or:not(:empty)::before {
            margin-right: .75em;
        }

        .divider-or:not(:empty)::after {
            margin-left: .75em;
        }

        .btn-scan-camera {
            background: #f8fafc;
            border: 1.5px dashed var(--navy-surface);
            color: var(--navy-surface);
            padding: 12px 18px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-scan-camera:hover {
            background: #eef2ff;
        }

        #qr-reader-container {
            display: none;
            margin-top: 16px;
            border-radius: 14px;
            overflow: hidden;
            border: 2px solid var(--navy-surface);
        }

        /* Alert Box */
        .alert-error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 0.84rem;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        /* ── Info Features Grid ── */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .feature-item {
            background: #f8fafc;
            border: 1px solid #edf2f7;
            border-radius: 14px;
            padding: 14px;
            text-align: center;
        }

        .feature-item i {
            font-size: 1.4rem;
            color: var(--gold-primary);
            margin-bottom: 8px;
            display: inline-block;
        }

        .feature-title {
            font-family: 'Outfit', sans-serif;
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--navy-surface);
            margin-bottom: 3px;
        }

        .feature-desc {
            font-size: 0.74rem;
            color: var(--text-muted);
            line-height: 1.35;
        }

        /* ── Actions ── */
        .btn-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #25d366;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.92rem;
            padding: 13px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.25);
            transition: all 0.2s;
            margin-bottom: 10px;
        }

        .btn-website {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--navy-surface);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 11px 20px;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-website:hover {
            background: #f1f5f9;
        }

        .page-footer {
            text-align: center;
            padding: 20px 16px;
            font-size: 0.74rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- HERO HEADER -->
    <header class="hero-header">
        <div class="brand-logo">
            <img src="{{ asset('template-admin/assets/images/logo-icon-2.webp') }}" alt="Lawgika">
            <span>Lawgika</span>
        </div>
        
        <div>
            <span class="badge-portal">
                <i class="fa-solid fa-shield-halved me-1"></i>
                Official Verification Portal
            </span>
        </div>

        <h1 class="hero-title">Verifikasi Layanan Klien</h1>
        <p class="hero-subtitle">Periksa keaslian dokumen legalitas, status pengerjaan, dan akses kuota ruangan Lawgika Indonesia.</p>
    </header>

    <!-- MAIN CONTENT -->
    <div class="container content-wrapper">

        <!-- SEARCH / VERIFICATION CARD -->
        <div class="card-box">
            <h2 class="card-title-custom">
                <i class="fa-solid fa-magnifying-glass text-warning"></i>
                Cari &amp; Periksa Pesanan
            </h2>
            <p class="card-desc-custom">
                Masukkan Kode Token QR atau Nomor Pesanan (misal: <code>ORD-PT-...</code>) yang tertera pada dokumen atau QR Code Anda.
            </p>

            @if(session('error'))
                <div class="alert-error">
                    <i class="fa-solid fa-circle-exclamation fs-5 mt-0.5"></i>
                    <div>{{ session('error') }}</div>
                </div>
            @endif

            <form action="{{ route('qr.verify') }}" method="POST" class="search-group">
                @csrf
                <div class="input-wrap">
                    <i class="fa-solid fa-barcode"></i>
                    <input type="text" name="query" class="input-control" placeholder="Nomor Pesanan / Kode Token QR..." required autofocus value="{{ old('query') }}">
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Verifikasi Layanan</span>
                </button>
            </form>

            <div class="divider-or">atau</div>

            <button type="button" class="btn-scan-camera" id="btnStartScan">
                <i class="fa-solid fa-camera fa-lg"></i>
                <span>Pindai QR Code Menggunakan Kamera</span>
            </button>

            <!-- Video Camera Scanner Container -->
            <div id="qr-reader-container">
                <div id="qr-reader" style="width: 100%;"></div>
                <div style="padding: 10px; text-align: center; background: #fff;">
                    <button type="button" id="btnStopScan" style="background:#ef4444; color:#fff; border:none; padding:6px 14px; border-radius:8px; font-weight:600; font-size:0.8rem; cursor:pointer;">
                        Tutup Kamera
                    </button>
                </div>
            </div>
        </div>

        <!-- INFO FEATURES -->
        <div class="card-box">
            <h2 class="card-title-custom">
                <i class="fa-solid fa-certificate text-warning"></i>
                Standar Keamanan Lawgika
            </h2>
            <p class="card-desc-custom">Setiap QR Code dan Layanan Lawgika dilindungi enkripsi verifikasi real-time server resmi.</p>

            <div class="feature-grid">
                <div class="feature-item">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <div class="feature-title">Legalitas Sah</div>
                    <div class="feature-desc">Terhubung langsung dengan berkas sah AHU &amp; Kemenkumham.</div>
                </div>

                <div class="feature-item">
                    <i class="fa-solid fa-gem"></i>
                    <div class="feature-title">Benefit Ruangan</div>
                    <div class="feature-desc">Cek kuota live Meeting Room &amp; Podcast Room.</div>
                </div>

                <div class="feature-item">
                    <i class="fa-solid fa-fingerprint"></i>
                    <div class="feature-title">Anti-Pemalsuan</div>
                    <div class="feature-desc">Token unik acak non-sekuensial 48 karakter.</div>
                </div>

                <div class="feature-item">
                    <i class="fa-solid fa-bolt"></i>
                    <div class="feature-title">Verifikasi Real-Time</div>
                    <div class="feature-desc">Data diambil langsung dari server Lawgika terbaru.</div>
                </div>
            </div>
        </div>

        <!-- SUPPORT -->
        <div class="card-box">
            <h2 class="card-title-custom">
                <i class="fa-solid fa-headset text-warning"></i>
                Butuh Bantuan?
            </h2>
            <p class="card-desc-custom">Hubungi Customer Support resmi Lawgika jika Anda memiliki pertanyaan mengenai layanan Anda.</p>

            <a href="https://wa.me/6281112088600?text=Halo%20Tim%20Lawgika,%20saya%20ingin%20bertanya%20seputar%20verifikasi%20layanan%20order." target="_blank" class="btn-wa">
                <i class="fa-brands fa-whatsapp fa-lg"></i>
                <span>Hubungi WhatsApp Resmi: 0811-1208-8600</span>
            </a>

            <a href="{{ url('/') }}" class="btn-website">
                <i class="fa-solid fa-house me-1"></i>
                <span>Kembali ke Beranda Lawgika</span>
            </a>
        </div>

        <footer class="page-footer">
            &copy; {{ date('Y') }} PT Lawgika Digital Indonesia. Hak Cipta Dilindungi.
        </footer>
    </div>

    <!-- Script HTML5 QR Code Scanner -->
    <script>
        let html5QrCode = null;

        document.getElementById('btnStartScan').addEventListener('click', function() {
            const container = document.getElementById('qr-reader-container');
            container.style.display = 'block';

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qr-reader");
            }

            const qrCodeSuccessCallback = (decodedText, decodedResult) => {
                // Stop scanner
                html5QrCode.stop().then(() => {
                    container.style.display = 'none';
                    // If scanned text is a URL to /qr/...
                    if (decodedText.includes('/qr/')) {
                        window.location.href = decodedText;
                    } else {
                        // Submit as query in form
                        document.querySelector('input[name="query"]').value = decodedText;
                        document.querySelector('form.search-group').submit();
                    }
                }).catch(err => {
                    console.error("Failed to stop scanner", err);
                });
            };

            const config = { fps: 10, qrbox: { width: 250, height: 250 } };

            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .catch(err => {
                    alert("Tidak dapat mengakses kamera: " + err);
                    container.style.display = 'none';
                });
        });

        document.getElementById('btnStopScan').addEventListener('click', function() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    document.getElementById('qr-reader-container').style.display = 'none';
                }).catch(err => {
                    console.error(err);
                    document.getElementById('qr-reader-container').style.display = 'none';
                });
            } else {
                document.getElementById('qr-reader-container').style.display = 'none';
            }
        });
    </script>
</body>
</html>
