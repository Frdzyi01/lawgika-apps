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
            --navy-dark: #0a1128;
            --navy-primary: #121e42;
            --navy-surface: #1a2b5a;
            --navy-card: #ffffff;
            --gold-primary: #c59b27;
            --gold-light: #fef5d8;
            --gold-gradient: linear-gradient(135deg, #d4af37 0%, #aa7c11 100%);
            --navy-gradient: linear-gradient(165deg, #0a1128 0%, #162450 60%, #1c3066 100%);
            --text-primary: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --success-color: #10b981;
            --success-light: #ecfdf5;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
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
            padding-bottom: 40px;
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
            padding: 28px 20px 70px 20px;
            border-bottom-left-radius: 28px;
            border-bottom-right-radius: 28px;
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

        .verified-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #34d399;
            font-size: 0.76rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .verified-pill .pulse-dot {
            width: 7px;
            height: 7px;
            background-color: #34d399;
            border-radius: 50%;
            box-shadow: 0 0 8px #34d399;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.3); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
            line-height: 1.25;
        }

        .hero-subtitle {
            font-size: 0.86rem;
            color: #cbd5e1;
        }

        /* ── Floating Main Card ── */
        .content-wrapper {
            margin-top: -46px;
        }

        .card-box {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px -4px rgba(0, 0, 0, 0.06);
            padding: 22px;
            margin-bottom: 16px;
        }

        /* Order Header Row */
        .order-meta-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 16px;
            border-bottom: 1px dashed var(--border-color);
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .order-tag {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--navy-surface);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-copy {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            border-radius: 6px;
            padding: 3px 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .order-date-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Client & Service Info */
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
            color: var(--navy-surface);
            font-size: 1.05rem;
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
            color: var(--navy-surface);
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
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
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
            width: 5px;
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
            font-size: 1rem;
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

        /* ── Dynamic Form Data / Parameter List ── */
        .param-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .param-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
            gap: 12px;
        }

        .param-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .param-key {
            color: var(--text-muted);
            font-weight: 500;
            flex-shrink: 0;
            max-width: 45%;
        }

        .param-val {
            font-weight: 600;
            color: var(--text-primary);
            text-align: right;
            word-break: break-word;
        }

        /* ── Security & Stamp Area ── */
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
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(37, 211, 102, 0.35);
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
            background: var(--navy-surface);
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

    <!-- HERO HEADER -->
    <header class="hero-header">
        <div class="brand-logo">
            <img src="{{ asset('template-admin/assets/images/logo-icon-2.webp') }}" alt="Lawgika">
            <span>Lawgika</span>
        </div>
        
        <div>
            <span class="verified-pill">
                <span class="pulse-dot"></span>
                Official Service Pass
            </span>
        </div>

        <h1 class="hero-title">{{ $order->service_name ?? ($order->service?->name ?? 'Layanan Lawgika') }}</h1>
        <p class="hero-subtitle">Verifikasi Dokumen &amp; Status Layanan Klien</p>
    </header>

    <!-- CONTENT WRAPPER -->
    <div class="container content-wrapper">

        <!-- CARD 1: ORDER & CLIENT INFO -->
        <div class="card-box">
            <div class="order-meta-header">
                <div class="order-tag">
                    <i class="fa-solid fa-receipt text-warning"></i>
                    <span>#{{ $order->order_number }}</span>
                    <button class="btn-copy" onclick="copyOrderNumber('{{ $order->order_number }}')" title="Salin No. Order">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
                <div class="order-date-label">
                    <i class="fa-regular fa-calendar me-1"></i>
                    {{ $order->created_at->translatedFormat('d F Y') }}
                </div>
            </div>

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
                    $docStatusLabel = $order->status_label;
                    $isDocVerified  = in_array($order->status, ['verified', 'completed', 'approved']);
                    $isPayVerified  = $order->payment_status === 'verified';
                @endphp

                <div class="badge-pill {{ $isDocVerified ? 'badge-verified' : 'badge-waiting' }}">
                    <i class="fa-solid {{ $isDocVerified ? 'fa-circle-check' : 'fa-clock' }}"></i>
                    <span>Dokumen: {{ $docStatusLabel }}</span>
                </div>

                <div class="badge-pill {{ $isPayVerified ? 'badge-verified' : ($order->payment_status === 'unpaid' ? 'badge-secondary' : 'badge-waiting') }}">
                    <i class="fa-solid {{ $isPayVerified ? 'fa-shield-halved' : 'fa-money-bill-wave' }}"></i>
                    <span>Bayar: {{ $order->payment_status_label }}</span>
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

        <!-- CARD 3: DETAIL SPESIFIKASI LAYANAN (GENERIC FORM_DATA) -->
        @if(!empty($displayFields))
        <div class="card-box">
            <div class="section-heading">
                <h2 class="section-title">
                    <i class="fa-solid fa-list-check"></i>
                    Spesifikasi Layanan
                </h2>
            </div>
            <div class="param-list">
                @foreach($displayFields as $label => $val)
                    <div class="param-row">
                        <span class="param-key">{{ $label }}</span>
                        <span class="param-val">{{ $val }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- CARD 4: STATUS DOKUMEN & VERIFIKASI -->
        <div class="card-box">
            <div class="section-heading">
                <h2 class="section-title">
                    <i class="fa-solid fa-folder-closed"></i>
                    Berkas &amp; Dokumen
                </h2>
                <span class="badge-pill badge-secondary" style="font-size: 0.72rem;">
                    {{ $order->documents->count() }} Berkas
                </span>
            </div>

            @if($order->documents->isEmpty())
                <p class="text-muted" style="font-size: 0.85rem;">Belum ada berkas terlampir pada sistem.</p>
            @else
                <div class="param-list">
                    @foreach($order->documents as $doc)
                        @php
                            $isDocApproved = in_array($doc->status, ['approved', 'verified']);
                        @endphp
                        <div class="param-row">
                            <span class="param-key">
                                <i class="fa-regular fa-file-lines me-1 text-muted"></i>
                                {{ $doc->original_name ?? ($doc->document_type ?? 'Dokumen') }}
                            </span>
                            <span class="param-val">
                                @if($isDocApproved)
                                    <span class="badge-pill badge-verified" style="padding: 3px 8px; font-size: 0.72rem;">
                                        <i class="fa-solid fa-check"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="badge-pill badge-waiting" style="padding: 3px 8px; font-size: 0.72rem;">
                                        {{ ucfirst($doc->status ?? 'Diproses') }}
                                    </span>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

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
    </div>

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
