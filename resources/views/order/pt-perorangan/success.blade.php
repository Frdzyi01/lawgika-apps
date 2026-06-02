@extends('layout.app')

@section('content')

<style>
    :root {
        --primary: #4e0516;
        --primary-l: #7a0a23;
        --accent: #c9a03d;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg: #fdf8f5;
        --border: #e8d9dd;
    }

    .success-page {
        background: var(--bg);
        min-height: 80vh;
        padding: 70px 0 100px;
    }

    /* ── Icon circle ─────────────────────────────────────── */
    .success-icon-wrap {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
        box-shadow: 0 0 0 12px rgba(34, 197, 94, .1);
    }

    .success-icon-wrap i {
        font-size: 3rem;
        color: #16a34a;
    }

    /* ── Header text ─────────────────────────────────────── */
    .success-title {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .success-subtitle {
        font-size: 1.05rem;
        color: var(--gray);
        max-width: 520px;
        margin: 0 auto 40px;
        line-height: 1.6;
    }

    /* ── Cards ───────────────────────────────────────────── */
    .success-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid var(--border);
        box-shadow: 0 6px 25px rgba(78, 5, 22, .06);
        padding: 32px 36px;
        margin-bottom: 20px;
        text-align: left;
    }

    .success-card-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--primary);
        border-bottom: 2px solid var(--border);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    /* ── Package summary ─────────────────────────────────── */
    .pkg-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .pkg-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
    }

    .pkg-price {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary);
    }

    .pkg-badge {
        display: inline-block;
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.78rem;
        font-weight: 700;
        color: #7a5200;
        margin-top: 6px;
    }

    /* ── Order number ────────────────────────────────────── */
    .order-number-box {
        background: var(--bg);
        border: 1.5px dashed var(--border);
        border-radius: 12px;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .order-number-box .label {
        font-size: 0.82rem;
        color: var(--gray);
        font-weight: 600;
    }

    .order-number-box .number {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--primary);
        letter-spacing: 1px;
        font-family: monospace;
    }

    /* ── Payment instructions ────────────────────────────── */
    .payment-steps {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .payment-steps li {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 0;
        border-bottom: 1px dashed var(--border);
        font-size: 0.93rem;
        color: var(--dark);
        line-height: 1.5;
    }

    .payment-steps li:last-child {
        border-bottom: none;
    }

    .step-num {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%);
        color: #fff;
        font-size: 0.78rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    /* ── WA Highlight ────────────────────────────────────── */
    .wa-highlight {
        background: #f0fdf4;
        border: 1.5px solid #86efac;
        border-radius: 14px;
        padding: 20px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .wa-highlight i {
        font-size: 2rem;
        color: #16a34a;
        flex-shrink: 0;
    }

    .wa-highlight .wa-text strong {
        display: block;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 2px;
    }

    .wa-highlight .wa-text span {
        font-size: 0.88rem;
        color: var(--gray);
    }

    /* ── Action buttons ──────────────────────────────────── */
    .btn-wa {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        background: #25d366;
        color: #fff;
        padding: 14px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(37, 211, 102, .35);
        transition: background .2s, transform .15s;
    }

    .btn-wa:hover {
        background: #1ebe57;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-dashboard {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        background: #fff;
        color: var(--primary);
        padding: 14px 30px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        border: 2px solid var(--primary);
        transition: background .2s, color .2s;
    }

    .btn-dashboard:hover {
        background: var(--primary);
        color: #fff;
    }
</style>

{{-- ── BREADCRUMB ── --}}
<section style="background:#fff; border-bottom:1px solid var(--border); padding:14px 0;">
    <div class="container">
        <nav style="font-size:.88rem; color:var(--gray);">
            <a href="{{ url('/') }}" style="color:var(--primary); text-decoration:none; font-weight:500">
                <i class="fa-solid fa-house" style="font-size:.8rem"></i> <span data-i18n="nav.home">Beranda</span>
            </a>
            <span class="mx-2">›</span>
            <a href="{{ url('/pendirian-pt-perorangan') }}" style="color:var(--primary); text-decoration:none; font-weight:500" data-i18n="nav.services.pt_perorangan">Pendirian PT Perorangan</a>
            <span class="mx-2">›</span>
            <span data-i18n="order.success_page_title">Order Berhasil</span>
        </nav>
    </div>
</section>

{{-- ── MAIN ── --}}
<section class="success-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 text-center">

                {{-- Success Icon --}}
                <div class="success-icon-wrap">
                    <i class="fa-solid fa-check"></i>
                </div>

                <h1 class="success-title" data-i18n="order.success_title">Order Berhasil Dikirim! 🎉</h1>
                <p class="success-subtitle">
                    Terima kasih, <strong>{{ auth()->user()->name }}</strong>!<br><span data-i18n="order.pt_per.received_desc">Order Anda telah kami terima dan sedang diproses oleh tim Lawgika.</span>
                </p>

                {{-- Order Number --}}
                @if($orderNumber)
                <div class="success-card mb-4">
                    <div class="success-card-title"><i class="fa-solid fa-receipt me-1"></i> <span data-i18n="order.order_number_title">Nomor Order</span></div>
                    <div class="order-number-box">
                        <div>
                            <div class="label" data-i18n="order.order_number_label">Simpan nomor order ini sebagai referensi</div>
                            <div class="number">{{ $orderNumber }}</div>
                        </div>
                        <i class="fa-solid fa-copy" style="color:var(--gray); cursor:pointer; font-size:1.2rem"
                            onclick="navigator.clipboard.writeText('{{ $orderNumber }}').then(() => this.style.color='#16a34a')"
                            title="Salin nomor order" data-i18n-title="order.order_number_copy_title"></i>
                    </div>
                </div>
                @endif

                {{-- Package Summary --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-solid fa-box me-1"></i> <span data-i18n="order.summary_title">Ringkasan Paket</span></div>
                    <div class="pkg-row">
                        <div>
                            <div class="pkg-label">{{ $packageInfo['label'] }}</div>
                            <div class="pkg-badge" data-i18n="nav.services.pt_perorangan">Pendirian PT Perorangan</div>
                        </div>
                        <div class="pkg-price">{{ $packageInfo['price'] }}</div>
                    </div>
                </div>

                {{-- Payment Instructions --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-solid fa-money-bill-transfer me-1"></i> <span data-i18n="order.payment_instruction_title">Instruksi Pembayaran</span></div>

                    <ul class="payment-steps">
                        <li>
                            <span class="step-num">1</span>
                            <span><span data-i18n="order.pt_per.step_transfer_amount">Lakukan pembayaran sebesar <strong>{{ $packageInfo['price'] }}</strong> ke rekening atau metode pembayaran yang akan dikonfirmasi oleh tim kami via WhatsApp.</span></span>
                        </li>
                        <li>
                            <span class="step-num">2</span>
                            <span><span data-i18n="order.pt_per.step_screenshot">Setelah transfer, ambil screenshot / foto bukti pembayaran Anda.</span></span>
                        </li>
                        <li>
                            <span class="step-num">3</span>
                            <span><span data-i18n="order.pt_per.step_send_proof">Kirimkan bukti pembayaran ke WhatsApp kami beserta nomor order.</span></span>
                        </li>
                        <li>
                            <span class="step-num">4</span>
                            <span><span data-i18n="order.pt_per.step_confirm_and_process">Tim kami akan mengkonfirmasi dan mulai memproses pendirian PT Perorangan Anda.</span></span>
                        </li>
                    </ul>
                </div>

                {{-- WhatsApp Highlight --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-brands fa-whatsapp me-1"></i> <span data-i18n="order.send_payment_proof_title">Kirim Bukti Pembayaran</span></div>
                    <div class="wa-highlight mb-4">
                        <i class="fa-brands fa-whatsapp"></i>
                        <div class="wa-text text-start">
                            <strong data-i18n="order.whatsapp_lawgika_title">WhatsApp Lawgika</strong>
                            <span data-i18n="order.pt_per.wa_label">Silakan kirim bukti pembayaran ke nomor di bawah ini. Kami siap merespons secepatnya.</span>
                        </div>
                    </div>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--primary); margin-bottom: 20px; letter-spacing:1px;">
                        081112088600
                    </div>
                    <a href="https://wa.me/6281112088600?text={{ urlencode('Halo Lawgika, saya sudah transfer untuk order ' . $packageInfo['label'] . ' PT Perorangan. Nomor Order: ' . $orderNumber . '. Berikut bukti pembayaran saya.') }}"
                        target="_blank" class="btn-wa">
                        <i class="fa-brands fa-whatsapp" style="font-size:1.3rem"></i>
                        <span data-i18n="order.send_proof_wa_btn">Kirim Bukti via WhatsApp</span>
                    </a>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn-dashboard">
                        <i class="fa-solid fa-gauge-high"></i> <span data-i18n="order.pt_per.view_dashboard">Lihat Dashboard Order</span>
                    </a>
                    <a href="{{ url('/pendirian-pt-perorangan') }}" class="btn-dashboard" style="border-color:var(--gray); color:var(--gray);">
                        <i class="fa-solid fa-arrow-left"></i> <span data-i18n="order.pt_per.back_to_services">Kembali ke Layanan</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection