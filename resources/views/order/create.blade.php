@extends('layout.app')

@section('content')

<style>
    :root { --primary:#4e0516;--primary-l:#7a0a23;--accent:#c9a03d;--dark:#1e1b2b;--gray:#64748b;--bg:#fdf8f5;--border:#e8d9dd; }
    .order-page{background:var(--bg);min-height:80vh;padding:80px 0 100px;}
    .order-breadcrumb{font-size:.88rem;color:var(--gray);margin-bottom:30px;}
    .order-breadcrumb a{color:var(--primary);text-decoration:none;font-weight:500;}
    .order-breadcrumb a:hover{text-decoration:underline;}
    .order-card{background:#fff;border-radius:20px;box-shadow:0 8px 30px rgba(78,5,22,.07);border:1px solid var(--border);overflow:hidden;}
    .order-card-header{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-l) 100%);padding:30px 40px;color:#fff;}
    .order-card-header h2{font-size:1.6rem;font-weight:800;margin:0 0 4px;color:#fff;}
    .order-card-header p{margin:0;font-size:.95rem;opacity:.85;}
    .order-card-body{padding:40px;}
    .service-badge{display:inline-flex;align-items:center;gap:8px;background:#f0f4ff;border:1.5px solid #c7d7ff;border-radius:50px;padding:7px 18px;font-weight:700;color:#1e3a8a;font-size:.88rem;margin-bottom:8px;}
    .package-badge{display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#fff8ee 0%,#fdf3e0 100%);border:1.5px solid var(--accent);border-radius:50px;padding:7px 18px;font-weight:700;color:#7a5200;font-size:.88rem;margin-bottom:24px;}
    .form-section-title{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--primary);margin-bottom:16px;padding-bottom:8px;border-bottom:2px solid var(--border);}
    .form-label{font-size:.88rem;font-weight:600;color:var(--dark);margin-bottom:6px;}
    .form-label .req{color:#e11d48;}
    .form-control{border:1.5px solid var(--border);border-radius:10px;padding:12px 16px;font-size:.95rem;color:var(--dark);background:#fff;transition:border-color .2s;width:100%;}
    .form-control:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(78,5,22,.08);outline:none;}
    .form-control[readonly]{background:#f8f5f6;color:var(--gray);cursor:not-allowed;}
    textarea.form-control{resize:vertical;min-height:100px;}
    .file-upload-box{border:2px dashed var(--border);border-radius:12px;padding:20px;text-align:center;cursor:pointer;transition:border-color .2s,background .2s;position:relative;background:#fdf8f5;}
    .file-upload-box:hover{border-color:var(--primary);background:#fdf5f7;}
    .file-upload-box input[type="file"]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
    .file-upload-box .upload-icon{font-size:2rem;color:var(--primary);margin-bottom:8px;}
    .file-upload-box .upload-label{font-weight:600;color:var(--dark);font-size:.9rem;}
    .file-upload-box .upload-hint{font-size:.78rem;color:var(--gray);margin-top:4px;}
    .file-upload-box .file-name{margin-top:8px;font-size:.82rem;color:#16a34a;font-weight:600;}
    .border-danger-upload{border-color:#e11d48!important;}
    .opt-badge{background:#e0f2fe;color:#0369a1;font-size:.7rem;font-weight:700;border-radius:20px;padding:2px 10px;margin-left:6px;vertical-align:middle;}
    .field-error{color:#be123c;font-size:.82rem;margin-top:4px;}
    .btn-order-submit{display:inline-flex;align-items:center;gap:10px;background:linear-gradient(135deg,var(--primary) 0%,var(--primary-l) 100%);color:#fff;padding:15px 40px;border-radius:50px;font-size:1rem;font-weight:700;border:none;cursor:pointer;box-shadow:0 4px 15px rgba(78,5,22,.3);transition:box-shadow .2s,transform .15s;text-decoration:none;}
    .btn-order-submit:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(78,5,22,.35);color:#fff;}
    .btn-order-submit:disabled{opacity:.7;cursor:not-allowed;transform:none;}
    .alert-order-error{background:#fff0f3;border:1px solid #fecdd3;border-radius:12px;padding:16px 20px;color:#be123c;font-size:.9rem;margin-bottom:24px;}
    .alert-order-error ul{margin:8px 0 0;padding-left:20px;}
    .order-info-box{background:linear-gradient(135deg,var(--primary) 0%,var(--primary-l) 100%);border-radius:20px;padding:30px;color:#fff;position:sticky;top:100px;}
    .order-info-box h5{font-size:1.1rem;font-weight:700;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid rgba(255,255,255,.2);color:#fff;}
    .info-item{display:flex;align-items:flex-start;gap:12px;margin-bottom:16px;font-size:.9rem;}
    .info-item i{font-size:1.1rem;color:var(--accent);margin-top:2px;flex-shrink:0;}
    .info-item strong{display:block;font-weight:600;}
    .info-item span{opacity:.8;font-size:.83rem;}
    .info-wa-btn{display:flex;align-items:center;justify-content:center;gap:8px;background:#25d366;color:#fff;padding:12px 20px;border-radius:50px;font-weight:700;text-decoration:none;margin-top:25px;font-size:.9rem;transition:background .2s;}
    .info-wa-btn:hover{background:#1ebe57;color:#fff;}
    .doc-checklist{background:rgba(255,255,255,.1);border-radius:12px;padding:14px;margin-top:10px;font-size:.83rem;}
    .doc-checklist p{font-weight:700;margin-bottom:8px;opacity:.9;}
    .doc-checklist ul{margin:0;padding-left:18px;opacity:.85;line-height:1.8;}
</style>

{{-- Breadcrumb --}}
<section style="background:#fff;border-bottom:1px solid var(--border);padding:14px 0;">
    <div class="container">
        <nav class="order-breadcrumb">
            <a href="{{ url('/') }}"><i class="fa-solid fa-house" style="font-size:.8rem"></i> Beranda</a>
            <span class="mx-2">›</span>
            <a href="{{ url($serviceInfo['url']) }}">{{ $serviceInfo['label'] }}</a>
            <span class="mx-2">›</span>
            <span>Form Order</span>
        </nav>
    </div>
</section>

<section class="order-page">
    <div class="container">
        <div class="row g-4 justify-content-center">

            {{-- FORM COLUMN --}}
            <div class="col-lg-8">

                @if ($errors->any())
                    <div class="alert-order-error">
                        <strong><i class="fa-solid fa-triangle-exclamation"></i> Mohon perbaiki isian berikut:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="order-card">
                    <div class="order-card-header">
                        <h2><i class="fa-solid fa-file-signature me-2"></i>Form Order Layanan</h2>
                        <p>Isi data di bawah ini dengan benar. Tim kami akan memproses dalam 1×24 jam.</p>
                    </div>
                    <div class="order-card-body">

                        <div class="mb-1">
                            <span class="service-badge"><i class="fa-solid fa-building"></i> {{ $serviceInfo['label'] }}</span>
                        </div>
                        <div>
                            <span class="package-badge"><i class="fa-solid fa-star"></i> {{ $packageLabel }}</span>
                        </div>

                        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                            @csrf
                            <input type="hidden" name="service" value="{{ $service }}">
                            <input type="hidden" name="package" value="{{ $package }}">

                            {{-- ═══════════════════════════════════════════════════════ --}}
                            {{-- FORM DETAIL (UNIVERSAL)                                --}}
                            {{-- ═══════════════════════════════════════════════════════ --}}
                            @include('order.partials.form-detail')
                            {{-- ═══════════════════════════════════════════════════════ --}}

                            {{-- Submit (shared) --}}
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <button type="submit" class="btn-order-submit" id="submitBtn">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Order Sekarang
                                </button>
                                <a href="{{ url($serviceInfo['url']) }}"
                                   style="color:var(--gray);font-size:.9rem;text-decoration:none">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4 d-none d-lg-block">
                <div class="order-info-box">
                    <h5><i class="fa-solid fa-circle-info me-2"></i>Informasi Order</h5>
                    <div class="info-item">
                        <i class="fa-solid fa-building"></i>
                        <div><strong>{{ $serviceInfo['label'] }}</strong><span>{{ $packageLabel }}</span></div>
                    </div>
                    @if($service === 'pt-perorangan' && $package === 'professional')
                    <div class="info-item">
                        <i class="fa-solid fa-file-lines"></i>
                        <div><strong>Dokumen Wajib</strong><span>5 dokumen harus diunggah saat order</span></div>
                    </div>
                    @endif
                    <div class="info-item">
                        <i class="fa-solid fa-clock"></i>
                        <div><strong>Estimasi Proses</strong><span>2 jam – 14 hari kerja</span></div>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        <div><strong>Dokumen Resmi</strong><span>SK dari Kemenkumham RI</span></div>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-headset"></i>
                        <div><strong>Konsultasi Gratis</strong><span>Tim kami siap membantu</span></div>
                    </div>
                    @if($service === 'pt-perorangan' && $package === 'professional')
                    <div class="doc-checklist">
                        <p><i class="fa-solid fa-list-check me-1"></i>Checklist Dokumen</p>
                        <ul>
                            <li>Akta Pendirian</li>
                            <li>NPWP Perusahaan</li>
                            <li>SK Kemenkumham</li>
                            <li>KTP Direktur</li>
                            <li>NPWP Direktur</li>
                        </ul>
                    </div>
                    @endif
                    <a href="https://wa.me/6281219110199?text={{ urlencode('Halo Lawgika, saya ingin konsultasi tentang ' . $serviceInfo['label'] . ' – ' . $packageLabel) }}"
                       target="_blank" class="info-wa-btn">
                        <i class="fa-brands fa-whatsapp" style="font-size:1.2rem"></i> Hubungi via WhatsApp
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<script>
    function showFileName(input, spanId) {
        document.getElementById(spanId).textContent = input.files[0] ? '✓ ' + input.files[0].name : '';
    }

    document.getElementById('orderForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim…';
    });
</script>

@endsection
