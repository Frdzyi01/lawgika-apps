<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $order->service_name ?? ($order->service?->name ?? 'Layanan Client') }} - Lawgika Official Service Pass</title>
    
    <!-- Meta SEO -->
    <meta name="description" content="Halaman Verifikasi Resmi Layanan Lawgika untuk Order #{{ $order->order_number }}. Cek status legalitas, benefit, dan kuota layanan.">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lawgika/logo.webp') }}">

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --navy-dark: #070d1e;
            --navy-primary: #0f1c3f;
            --navy-surface: #16254c;
            --gold-primary: #c59b27;
            --gold-light: #fef5d8;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
            --navy-gradient: linear-gradient(145deg, #081126 0%, #122046 60%, #182c5e 100%);
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --success-light: #ecfdf5;
            --bg-body: #f4f6fb;
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
        }

        /* ── Top Corporate Navbar ── */
        .top-navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }

        .nav-container {
            width: 100%;
            max-width: 640px;
            margin: 0 auto;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .brand-img {
            height: 34px;
            width: auto;
            display: block;
        }

        .header-secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 0.74rem;
            font-weight: 700;
            padding: 5px 11px;
            border-radius: 999px;
            letter-spacing: 0.3px;
        }

        /* ── Main Container ── */
        .main-container {
            width: 100%;
            max-width: 640px;
            margin: 20px auto 40px;
            padding: 0 16px;
        }

        /* ── Hero Header Card (No overlap, perfectly bounded) ── */
        .hero-card {
            background: var(--navy-gradient);
            color: #ffffff;
            border-radius: 20px;
            padding: 24px 22px;
            margin-bottom: 16px;
            position: relative;
            border-top: 4px solid var(--gold-primary);
            border-left: 1px solid rgba(255, 255, 255, 0.1);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 25px -5px rgba(8, 17, 38, 0.25);
        }

        .hero-card-eyebrow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .hero-pass-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(212, 175, 55, 0.15);
            border: 1px solid rgba(212, 175, 55, 0.35);
            color: #fde047;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .hero-live-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #86efac;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .pulse-dot-green {
            width: 7px;
            height: 7px;
            background-color: #22c55e;
            border-radius: 50%;
            box-shadow: 0 0 8px #22c55e;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .hero-service-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 14px;
            line-height: 1.25;
            letter-spacing: -0.2px;
        }

        .hero-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            padding-top: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            font-size: 0.82rem;
        }

        .hero-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .hero-meta-label {
            color: #94a3b8;
        }

        .hero-meta-val {
            color: #ffffff;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
        }

        .btn-copy-mini {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-radius: 5px;
            padding: 2px 6px;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy-mini:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* ── White Card Box ── */
        .card-box {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 18px -2px rgba(0, 0, 0, 0.05);
            padding: 22px;
            margin-bottom: 16px;
        }

        /* ── Big Prominent Active / Expired Hero Banner ── */
        .status-hero-banner {
            border-radius: 16px;
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 18px;
        }

        .status-hero-banner.active-banner {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #10b981;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.12);
        }

        .status-hero-banner.expired-banner {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border: 2px solid #ef4444;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.12);
        }

        .status-hero-icon {
            font-size: 2.3rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .status-hero-icon.active-icon {
            color: #059669;
        }

        .status-hero-icon.expired-icon {
            color: #dc2626;
        }

        .status-hero-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 3px 9px;
            border-radius: 999px;
            margin-bottom: 4px;
        }

        .status-hero-pill.active-pill {
            background: #059669;
            color: #ffffff;
        }

        .status-hero-pill.expired-pill {
            background: #dc2626;
            color: #ffffff;
        }

        .status-pulse-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 0 6px #ffffff;
            animation: pulse 1.5s infinite;
        }

        .status-hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.32rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: 0.2px;
        }

        .text-success-dark {
            color: #065f46;
        }

        .text-danger-dark {
            color: #991b1b;
        }

        .status-hero-desc {
            font-size: 0.83rem;
            color: #475569;
            margin-top: 3px;
            line-height: 1.35;
        }

        /* Client & Order Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            font-weight: 600;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
        }

        .info-value.highlight {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--navy-primary);
            font-size: 1.08rem;
        }

        /* Status Badge Group */
        .status-badge-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .badge-verified {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .badge-waiting {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .badge-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        /* ── Benefit / Quota Section ── */
        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--navy-primary);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title i {
            color: var(--gold-primary);
        }

        .benefit-card {
            background: #ffffff;
            border: 1.5px solid var(--border-color);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
            overflow: hidden;
        }

        .benefit-card:last-child {
            margin-bottom: 0;
        }

        .benefit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 4px;
            background: var(--gold-gradient);
        }

        .benefit-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .benefit-type {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 0.98rem;
            color: var(--navy-primary);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .benefit-status-badge {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .benefit-status-badge.active {
            background: #dcfce7;
            color: #15803d;
        }

        .benefit-status-badge.expired {
            background: #fee2e2;
            color: #b91c1c;
        }

        .quota-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            background: #f8fafc;
            border-radius: 10px;
            padding: 10px 12px;
            text-align: center;
            margin-bottom: 12px;
        }

        .quota-stat-item .stat-label {
            font-size: 0.68rem;
            color: var(--text-muted);
            text-transform: uppercase;
            font-weight: 600;
        }

        .quota-stat-item .stat-val {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .quota-stat-item .stat-val.remaining {
            color: #059669;
            font-size: 1.05rem;
        }

        .progress-bar-wrap {
            width: 100%;
            height: 7px;
            background-color: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 999px;
            transition: width 0.5s ease;
        }

        .benefit-footer-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.74rem;
            color: var(--text-muted);
        }

        /* ── Spesifikasi Layanan (5 Field) ── */
        .spec-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .spec-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            gap: 14px;
        }

        .spec-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .spec-key {
            color: var(--text-muted);
            font-weight: 500;
            flex-shrink: 0;
            max-width: 45%;
        }

        .spec-val {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
            word-break: break-word;
        }

        /* ── Security Stamp ── */
        .security-badge {
            background: linear-gradient(135deg, #fef9ee 0%, #ffffff 100%);
            border: 1.5px dashed var(--gold-primary);
            border-radius: 16px;
            padding: 16px;
            text-align: center;
            margin-bottom: 16px;
        }

        .security-badge i.shield-icon {
            font-size: 1.8rem;
            color: var(--gold-primary);
            margin-bottom: 6px;
            display: inline-block;
        }

        .security-text {
            font-size: 0.82rem;
            color: #475569;
            margin-bottom: 4px;
        }

        .security-timestamp {
            font-size: 0.72rem;
            color: #94a3b8;
            font-family: monospace;
        }

        /* ── Actions ── */
        .action-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-wa {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: #25d366;
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 13px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.3);
            transition: all 0.2s;
        }

        .btn-wa:hover {
            background: #22bf5b;
            color: #ffffff;
        }

        .btn-website {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: var(--navy-primary);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px 20px;
            border-radius: 12px;
            transition: all 0.2s;
        }

        .btn-website:hover {
            background: var(--navy-dark);
            color: #ffffff;
        }

        .page-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.74rem;
            color: #94a3b8;
        }

        .toast-notify {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: rgba(15, 23, 42, 0.92);
            color: #fff;
            padding: 10px 20px;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s cubic-bezier(0.18, 0.89, 0.32, 1.28);
            z-index: 9999;
            backdrop-filter: blur(8px);
        }

        .toast-notify.show {
            transform: translateX(-50%) translateY(0);
        }
    </style>
</head>
<body>

    <!-- TOP CORPORATE NAVBAR -->
    <header class="top-navbar">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ asset('buyer-file/assets/img/logo-removebg.webp') }}" alt="Lawgika" class="brand-img">
            </a>
            <div class="header-secure-badge">
                <i class="fa-solid fa-shield-halved text-success"></i>
                <span>Official Verification Pass</span>
            </div>
        </div>
    </header>

    <!-- MAIN WRAPPER -->
    <main class="main-container">

        <!-- HERO HEADER CARD (Clean, bounded, official certificate style) -->
        <div class="hero-card">
            <div class="hero-card-eyebrow">
                <span class="hero-pass-tag">
                    <i class="fa-solid fa-certificate text-warning"></i>
                    SERTIFIKAT LAYANAN RESMI
                </span>
                <span class="hero-live-tag">
                    <span class="pulse-dot-green"></span>
                    SERVER VERIFIED
                </span>
            </div>

            <h1 class="hero-service-title">
                {{ $order->service_name ?? ($order->service?->name ?? 'Layanan Lawgika') }}
            </h1>

            <div class="hero-card-meta">
                <div class="hero-meta-item">
                    <span class="hero-meta-label">No. Registrasi:</span>
                    <span class="hero-meta-val">#{{ $order->order_number }}</span>
                    <button type="button" class="btn-copy-mini" onclick="copyOrderNumber('{{ $order->order_number }}')" title="Salin No. Order">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
                <div class="hero-meta-item">
                    <span class="hero-meta-label">Tanggal Terbit:</span>
                    <span class="hero-meta-val">{{ $order->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>
        </div>

        <!-- CARD 1: ORDER & CLIENT INFO WITH BIG ACTIVE/EXPIRED VERDICT -->
        <div class="card-box">
            <!-- KALIMAT BESAR STATUS LAYANAN (ACTIVE / EXPIRED) -->
            @if(!$isExpired)
                <div class="status-hero-banner active-banner">
                    <div class="status-hero-icon active-icon">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="status-hero-pill active-pill">
                            <span class="status-pulse-dot"></span>
                            VERIFIED &amp; ACTIVE
                        </div>
                        <div class="status-hero-title text-success-dark">STATUS: ACTIVE / LAYANAN AKTIF</div>
                        <div class="status-hero-desc">
                            Layanan resmi terverifikasi dan aktif di Lawgika
                            @if($expiredDate)
                                s/d <strong>{{ $expiredDate->translatedFormat('d F Y') }}</strong>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="status-hero-banner expired-banner">
                    <div class="status-hero-icon expired-icon">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="status-hero-pill expired-pill">
                            EXPIRED PASS
                        </div>
                        <div class="status-hero-title text-danger-dark">STATUS: EXPIRED / KADALUARSA</div>
                        <div class="status-hero-desc">
                            Masa berlaku layanan telah berakhir
                            @if($expiredDate)
                                pada <strong>{{ $expiredDate->translatedFormat('d F Y') }}</strong>
                            @endif
                            . Silakan hubungi tim Lawgika untuk perpanjangan.
                        </div>
                    </div>
                </div>
            @endif

            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Nama Klien / Perusahaan</span>
                    <span class="info-value highlight">
                        {{ $order->user?->company_name ?? ($order->user?->name ?? 'Klien Lawgika') }}
                    </span>
                    @if($order->user?->company_name && $order->user?->name)
                        <small class="text-muted" style="font-size: 0.78rem;">PIC: {{ $order->user->name }}</small>
                    @endif
                </div>

                <div class="info-item">
                    <span class="info-label">Layanan</span>
                    <span class="info-value">{{ $order->service_name ?? ($order->service?->name ?? 'Layanan Umum') }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">Kategori</span>
                    <span class="info-value">{{ ucfirst($order->service?->category ?? 'Bisnis & Legal') }}</span>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="status-badge-group">
                @php
                    $isPayVerified = $order->payment_status === 'verified';
                @endphp

                <div class="badge-pill {{ !$isExpired ? 'badge-verified' : 'badge-waiting' }}">
                    <i class="fa-solid {{ !$isExpired ? 'fa-shield-halved' : 'fa-triangle-exclamation' }}"></i>
                    <span>Status Layanan: {{ !$isExpired ? 'Aktif' : 'Expired' }}</span>
                </div>

                <div class="badge-pill {{ $isPayVerified ? 'badge-verified' : ($order->payment_status === 'unpaid' ? 'badge-secondary' : 'badge-waiting') }}">
                    <i class="fa-solid {{ $isPayVerified ? 'fa-circle-check' : 'fa-money-bill-wave' }}"></i>
                    <span>Pembayaran: {{ $order->payment_status_label }}</span>
                </div>
            </div>
        </div>

        <!-- CARD 2: BENEFIT RUANGAN / KUOTA (GENERIC) -->
        @if($order->roomBenefits->isNotEmpty())
        <div class="card-box">
            <div class="section-heading">
                <h2 class="section-title">
                    <i class="fa-solid fa-gem"></i>
                    Benefit &amp; Akses Ruangan
                </h2>
                <span class="text-muted" style="font-size: 0.76rem; font-weight: 600;">
                    {{ $order->roomBenefits->count() }} Fasilitas
                </span>
            </div>

            @foreach($order->roomBenefits as $benefit)
                @php
                    $isBenefitActive = $benefit->is_active && (!$benefit->expired_at || $benefit->expired_at->isFuture());
                    $totalMins       = max(1, (int)$benefit->total_minutes);
                    $remainMins      = max(0, (int)$benefit->remaining_minutes);
                    $usedMins        = max(0, (int)$benefit->used_minutes);
                    $percentRemain   = min(100, max(0, round(($remainMins / $totalMins) * 100)));
                    $benefitIcon     = $benefit->type === 'podcast' ? 'fa-microphone-lines' : 'fa-door-open';
                    $benefitName     = $benefit->type === 'podcast' ? 'Podcast Room' : 'Meeting Room';
                @endphp

                <div class="benefit-card">
                    <div class="benefit-top">
                        <div class="benefit-type">
                            <i class="fa-solid {{ $benefitIcon }} text-warning me-1"></i>
                            {{ $benefitName }}
                            @if($benefit->paket)
                                <small class="text-muted fw-normal">({{ $benefit->paket }})</small>
                            @endif
                        </div>
                        <span class="benefit-status-badge {{ $isBenefitActive ? 'active' : 'expired' }}">
                            {{ $isBenefitActive ? 'Aktif' : 'Non-aktif / Expired' }}
                        </span>
                    </div>

                    <div class="quota-stats">
                        <div class="quota-stat-item">
                            <div class="stat-label">Total Kuota</div>
                            <div class="stat-val">{{ \App\Models\RoomBenefit::formatMinutes($totalMins) }}</div>
                        </div>
                        <div class="quota-stat-item">
                            <div class="stat-label">Sisa Kuota</div>
                            <div class="stat-val remaining">{{ \App\Models\RoomBenefit::formatMinutes($remainMins) }}</div>
                        </div>
                        <div class="quota-stat-item">
                            <div class="stat-label">Terpakai</div>
                            <div class="stat-val">{{ \App\Models\RoomBenefit::formatMinutes($usedMins) }}</div>
                        </div>
                    </div>

                    <div class="progress-bar-wrap">
                        <div class="progress-bar-fill" style="width: {{ $percentRemain }}%;"></div>
                    </div>

                    <div class="benefit-footer-meta">
                        <span>Tersisa {{ $percentRemain }}% kuota</span>
                        <span>
                            <i class="fa-regular fa-clock me-1"></i>
                            Berlaku s/d: {{ $benefit->expired_at ? $benefit->expired_at->translatedFormat('d M Y') : '–' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <!-- CARD 3: SPESIFIKASI LAYANAN (HANYA 5 FIELD) -->
        @if(!empty($specifications))
        <div class="card-box">
            <div class="section-heading">
                <h2 class="section-title">
                    <i class="fa-solid fa-list-check"></i>
                    Spesifikasi Layanan
                </h2>
            </div>
            <div class="spec-list">
                @foreach($specifications as $label => $val)
                    <div class="spec-row">
                        <span class="spec-key">{{ $label }}</span>
                        <span class="spec-val">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SECURITY STAMP -->
        <div class="security-badge">
            <i class="fa-solid fa-shield-halved shield-icon"></i>
            <div class="security-text">
                <strong>Verifikasi Real-Time Server Lawgika</strong><br>
                Informasi layanan ini sah dan terhubung langsung ke database resmi Lawgika Indonesia.
            </div>
            <div class="security-timestamp">
                Token: {{ substr($order->qr_token, 0, 16) }}•••••••• | Dipindai: {{ now()->translatedFormat('d M Y, H:i') }} WIB
            </div>
        </div>

        <!-- CALL TO ACTIONS -->
        @php
            $waClientName = $order->user?->company_name ?? ($order->user?->name ?? 'Klien');
            $waServiceName = $order->service_name ?? ($order->service?->name ?? 'Layanan');
            $waMsg = "Halo Tim Lawgika, saya ingin konfirmasi layanan terkait Order #{$order->order_number} ({$waServiceName}) atas nama {$waClientName}.";
            $waUrl = "https://wa.me/6281112088600?text=" . urlencode($waMsg);
        @endphp

        <div class="action-group">
            <a href="{{ $waUrl }}" target="_blank" class="btn-wa">
                <i class="fa-brands fa-whatsapp fa-lg"></i>
                <span>Hubungi Tim Support Lawgika</span>
            </a>

            <a href="{{ url('/') }}" class="btn-website">
                <i class="fa-solid fa-globe"></i>
                <span>Kunjungi Website Resmi Lawgika</span>
            </a>
        </div>

        <!-- FOOTER -->
        <footer class="page-footer">
            <p>&copy; {{ date('Y') }} Lawgika Indonesia (PT Lawgika Digital Indonesia). All Rights Reserved.</p>
        </footer>
    </main>

    <!-- TOAST NOTIFICATION -->
    <div id="copyToast" class="toast-notify">
        <i class="fa-solid fa-circle-check text-success"></i>
        <span>Nomor order berhasil disalin!</span>
    </div>

    <script>
        function copyOrderNumber(orderNum) {
            navigator.clipboard.writeText(orderNum).then(function() {
                const toast = document.getElementById('copyToast');
                if (toast) {
                    toast.classList.add('show');
                    setTimeout(() => {
                        toast.classList.remove('show');
                    }, 2400);
                }
            }).catch(function() {
                alert('Nomor order: ' + orderNum);
            });
        }
    </script>
</body>
</html>
