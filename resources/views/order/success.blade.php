@extends('layout.app')

@section('content')

<style>
    :root {
        --primary:   #4e0516;
        --primary-l: #7a0a23;
        --accent:    #c9a03d;
        --dark:      #1e1b2b;
        --gray:      #64748b;
        --bg:        #fdf8f5;
        --border:    #e8d9dd;
    }

    .success-page { background: var(--bg); min-height: 80vh; padding: 70px 0 100px; }

    .success-icon-wrap {
        width: 100px; height: 100px;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border-radius: 50%; display: flex; align-items: center;
        justify-content: center; margin: 0 auto 28px;
        box-shadow: 0 0 0 14px rgba(34,197,94,.1);
    }
    .success-icon-wrap i { font-size: 3rem; color: #16a34a; }

    .success-title { font-size: 2.2rem; font-weight: 800; color: var(--dark); margin-bottom: 10px; }
    .success-subtitle { font-size: 1.05rem; color: var(--gray); max-width: 520px; margin: 0 auto 40px; line-height: 1.6; }

    .success-card {
        background: #fff; border-radius: 20px; border: 1px solid var(--border);
        box-shadow: 0 6px 25px rgba(78,5,22,.06); padding: 32px 36px;
        margin-bottom: 20px; text-align: left;
    }
    .success-card-title {
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: var(--primary);
        border-bottom: 2px solid var(--border); padding-bottom: 10px; margin-bottom: 20px;
    }

    /* Summary */
    .summary-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .summary-service { font-size: 1rem; font-weight: 700; color: var(--dark); }
    .summary-package { font-size: .85rem; font-weight: 600; color: var(--gray); }
    .summary-badge {
        display: inline-block; background: #fff3cd; border: 1px solid #ffc107;
        border-radius: 20px; padding: 4px 14px; font-size: .78rem; font-weight: 700; color: #7a5200;
    }

    /* Order number */
    .order-num-box {
        background: var(--bg); border: 1.5px dashed var(--border); border-radius: 12px;
        padding: 14px 20px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .order-num-box .label { font-size: .82rem; color: var(--gray); font-weight: 600; }
    .order-num-box .number { font-size: 1.05rem; font-weight: 800; color: var(--primary); letter-spacing: 1px; font-family: monospace; }

    /* Steps */
    .payment-steps { list-style: none; padding: 0; margin: 0; }
    .payment-steps li {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 0; border-bottom: 1px dashed var(--border);
        font-size: .93rem; color: var(--dark); line-height: 1.5;
    }
    .payment-steps li:last-child { border-bottom: none; }
    .step-num {
        width: 28px; height: 28px; border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%);
        color: #fff; font-size: .78rem; font-weight: 800;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;
    }

    /* WA */
    .wa-highlight {
        background: #f0fdf4; border: 1.5px solid #86efac; border-radius: 14px;
        padding: 20px 24px; display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
    }
    .wa-highlight i { font-size: 2rem; color: #16a34a; flex-shrink: 0; }
    .wa-highlight .wa-text strong { display: block; font-size: 1.1rem; font-weight: 700; color: var(--dark); margin-bottom: 2px; }
    .wa-highlight .wa-text span { font-size: .88rem; color: var(--gray); }

    .btn-wa {
        display: inline-flex; align-items: center; gap: 9px;
        background: #25d366; color: #fff; padding: 14px 30px;
        border-radius: 50px; font-weight: 700; font-size: 1rem;
        text-decoration: none; box-shadow: 0 4px 15px rgba(37,211,102,.35);
        transition: background .2s, transform .15s;
    }
    .btn-wa:hover { background: #1ebe57; color: #fff; transform: translateY(-2px); }

    .btn-outline-dark-brand {
        display: inline-flex; align-items: center; gap: 9px;
        background: #fff; color: var(--primary); padding: 14px 30px;
        border-radius: 50px; font-weight: 700; font-size: 1rem;
        text-decoration: none; border: 2px solid var(--primary);
        transition: background .2s, color .2s;
    }
    .btn-outline-dark-brand:hover { background: var(--primary); color: #fff; }

    .btn-outline-gray {
        display: inline-flex; align-items: center; gap: 9px;
        background: #fff; color: var(--gray); padding: 14px 30px;
        border-radius: 50px; font-weight: 700; font-size: 1rem;
        text-decoration: none; border: 2px solid var(--gray);
        transition: background .2s, color .2s;
    }
    .btn-outline-gray:hover { background: var(--gray); color: #fff; }
</style>

{{-- Breadcrumb --}}
<section style="background:#fff;border-bottom:1px solid var(--border);padding:14px 0;">
    <div class="container">
        <nav style="font-size:.88rem;color:var(--gray);">
            <a href="{{ url('/') }}" style="color:var(--primary);text-decoration:none;font-weight:500">
                <i class="fa-solid fa-house" style="font-size:.8rem"></i> Beranda
            </a>
            <span class="mx-2">›</span>
            <a href="{{ url($serviceInfo['url']) }}" style="color:var(--primary);text-decoration:none;font-weight:500">
                {{ $serviceInfo['label'] }}
            </a>
            <span class="mx-2">›</span>
            <span>Order Berhasil</span>
        </nav>
    </div>
</section>

<section class="success-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9 text-center">

                <div class="success-icon-wrap">
                    <i class="fa-solid fa-check"></i>
                </div>

                <h1 class="success-title">Order Berhasil Dikirim! 🎉</h1>
                <p class="success-subtitle">
                    Terima kasih, <strong>{{ auth()->user()->name }}</strong>!<br>
                    Order Anda telah kami terima dan sedang diproses oleh tim Lawgika.
                </p>

                {{-- Order Number --}}
                @if($orderNumber)
                <div class="success-card mb-4">
                    <div class="success-card-title"><i class="fa-solid fa-receipt me-1"></i>Nomor Order</div>
                    <div class="order-num-box">
                        <div>
                            <div class="label">Simpan nomor order ini sebagai referensi</div>
                            <div class="number">{{ $orderNumber }}</div>
                        </div>
                        <i class="fa-solid fa-copy" style="color:var(--gray);cursor:pointer;font-size:1.2rem"
                           onclick="navigator.clipboard.writeText('{{ $orderNumber }}').then(()=>this.style.color='#16a34a')"
                           title="Salin nomor order"></i>
                    </div>
                </div>
                @endif

                {{-- Summary --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-solid fa-box me-1"></i>Ringkasan Order</div>
                    <div class="summary-row">
                        <div class="text-start">
                            <div class="summary-service">{{ $serviceInfo['label'] }}</div>
                            <div class="summary-package">{{ $packageLabel }}</div>
                        </div>
                        <span class="summary-badge">Pendirian Badan Usaha</span>
                    </div>
                </div>

                {{-- Payment Steps --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-solid fa-money-bill-transfer me-1"></i>Instruksi Pembayaran</div>
                    <ul class="payment-steps">
                        <li>
                            <span class="step-num">1</span>
                            <span>Tim kami akan menghubungi Anda via WhatsApp untuk menginformasikan rekening pembayaran dan total biaya sesuai paket yang dipilih.</span>
                        </li>
                        <li>
                            <span class="step-num">2</span>
                            <span>Lakukan transfer pembayaran sesuai instruksi dari tim Lawgika.</span>
                        </li>
                        <li>
                            <span class="step-num">3</span>
                            <span>Ambil screenshot / foto bukti pembayaran Anda.</span>
                        </li>
                        <li>
                            <span class="step-num">4</span>
                            <span>Kirim bukti pembayaran ke WhatsApp kami beserta nomor order <strong>{{ $orderNumber }}</strong>.</span>
                        </li>
                        <li>
                            <span class="step-num">5</span>
                            <span>Tim kami akan mengkonfirmasi dan mulai memproses layanan Anda.</span>
                        </li>
                    </ul>
                </div>

                {{-- WhatsApp --}}
                <div class="success-card">
                    <div class="success-card-title"><i class="fa-brands fa-whatsapp me-1"></i>Kirim Bukti Pembayaran</div>
                    <div class="wa-highlight mb-4">
                        <i class="fa-brands fa-whatsapp"></i>
                        <div class="wa-text text-start">
                            <strong>WhatsApp Lawgika</strong>
                            <span>Silakan kirim bukti pembayaran ke nomor di bawah ini setelah melakukan transfer.</span>
                        </div>
                    </div>
                    <div style="font-size:2rem;font-weight:800;color:var(--primary);margin-bottom:20px;letter-spacing:1px;">
                        081219110199
                    </div>
                    <a href="https://wa.me/6281219110199?text={{ urlencode('Halo Lawgika, saya sudah transfer untuk order ' . $serviceInfo['label'] . ' – ' . $packageLabel . '. Nomor Order: ' . $orderNumber . '. Berikut bukti pembayaran saya.') }}"
                       target="_blank" class="btn-wa">
                        <i class="fa-brands fa-whatsapp" style="font-size:1.3rem"></i>
                        Kirim Bukti via WhatsApp
                    </a>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn-outline-dark-brand">
                        <i class="fa-solid fa-gauge-high"></i> Lihat Dashboard
                    </a>
                    <a href="{{ url($serviceInfo['url']) }}" class="btn-outline-gray">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Layanan
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection
