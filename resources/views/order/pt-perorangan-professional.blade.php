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

    .order-page { background: var(--bg); min-height: 80vh; padding: 80px 0 100px; }

    /* Breadcrumb */
    .order-breadcrumb { font-size: .88rem; color: var(--gray); margin-bottom: 30px; }
    .order-breadcrumb a { color: var(--primary); text-decoration: none; font-weight: 500; }
    .order-breadcrumb a:hover { text-decoration: underline; }

    /* Card */
    .order-card { background: #fff; border-radius: 20px; box-shadow: 0 8px 30px rgba(78,5,22,.07); border: 1px solid var(--border); overflow: hidden; }
    .order-card-header { background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%); padding: 30px 40px; color: #fff; }
    .order-card-header h2 { font-size: 1.6rem; font-weight: 800; margin: 0 0 4px; color: #fff; }
    .order-card-header p { margin: 0; font-size: .95rem; opacity: .85; }
    .order-card-body { padding: 40px; }

    /* Badges */
    .service-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f0f4ff; border: 1.5px solid #c7d7ff;
        border-radius: 50px; padding: 7px 18px;
        font-weight: 700; color: #1e3a8a; font-size: .88rem;
        margin-bottom: 8px;
    }
    .package-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #fff8ee 0%, #fdf3e0 100%);
        border: 1.5px solid var(--accent); border-radius: 50px;
        padding: 7px 18px; font-weight: 700; color: #7a5200;
        font-size: .88rem; margin-bottom: 24px;
    }
    .package-badge i, .service-badge i { font-size: .9rem; }

    /* Form sections */
    .form-section-title {
        font-size: .75rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 1px; color: var(--primary); margin-bottom: 16px;
        padding-bottom: 8px; border-bottom: 2px solid var(--border);
    }
    .form-label { font-size: .88rem; font-weight: 600; color: var(--dark); margin-bottom: 6px; }
    .form-label .req { color: #e11d48; }

    .form-control {
        border: 1.5px solid var(--border); border-radius: 10px;
        padding: 12px 16px; font-size: .95rem; color: var(--dark);
        background: #fff; transition: border-color .2s;
        width: 100%;
    }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(78,5,22,.08); outline: none; }
    .form-control[readonly] { background: #f8f5f6; color: var(--gray); cursor: not-allowed; }
    textarea.form-control { resize: vertical; min-height: 110px; }

    /* File upload */
    .file-upload-box {
        border: 2px dashed var(--border); border-radius: 12px; padding: 22px;
        text-align: center; cursor: pointer; transition: border-color .2s, background .2s;
        position: relative; background: #fdf8f5;
    }
    .file-upload-box:hover { border-color: var(--primary); background: #fdf5f7; }
    .file-upload-box.dragover { border-color: var(--primary); background: #fdf5f7; }
    .file-upload-box input[type="file"] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .file-upload-box .upload-icon { font-size: 1.8rem; color: var(--primary); margin-bottom: 6px; }
    .file-upload-box .upload-label { font-weight: 600; color: var(--dark); font-size: .88rem; }
    .file-upload-box .upload-hint { font-size: .76rem; color: var(--gray); margin-top: 3px; }
    .file-upload-box .file-name { margin-top: 7px; font-size: .82rem; color: #16a34a; font-weight: 600; }
    .file-upload-box.border-danger-upload { border-color: #e11d48 !important; }

    .req-badge {
        background: #fee2e2; color: #be123c; font-size: .7rem;
        font-weight: 700; border-radius: 20px; padding: 2px 8px;
        margin-left: 5px; vertical-align: middle;
    }
    .opt-badge {
        background: #e0f2fe; color: #0369a1; font-size: .7rem;
        font-weight: 700; border-radius: 20px; padding: 2px 10px;
        margin-left: 6px; vertical-align: middle;
    }

    /* Submit */
    .btn-order-submit {
        display: inline-flex; align-items: center; gap: 10px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%);
        color: #fff; padding: 15px 40px; border-radius: 50px;
        font-size: 1rem; font-weight: 700; border: none; cursor: pointer;
        box-shadow: 0 4px 15px rgba(78,5,22,.3);
        transition: box-shadow .2s, transform .15s; text-decoration: none;
    }
    .btn-order-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(78,5,22,.35); color: #fff; }
    .btn-order-submit:disabled { opacity: .7; cursor: not-allowed; transform: none; }

    /* Alert */
    .alert-order-error {
        background: #fff0f3; border: 1px solid #fecdd3; border-radius: 12px;
        padding: 16px 20px; color: #be123c; font-size: .9rem; margin-bottom: 24px;
    }
    .alert-order-error ul { margin: 8px 0 0; padding-left: 20px; }

    /* Sidebar */
    .order-info-box {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-l) 100%);
        border-radius: 20px; padding: 30px; color: #fff;
        position: sticky; top: 100px;
    }
    .order-info-box h5 { font-size: 1.1rem; font-weight: 700; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(255,255,255,.2); color: #fff; }
    .info-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; font-size: .9rem; }
    .info-item i { font-size: 1.1rem; color: var(--accent); margin-top: 2px; flex-shrink: 0; }
    .info-item strong { display: block; font-weight: 600; }
    .info-item span { opacity: .8; font-size: .83rem; }
    .info-wa-btn {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        background: #25d366; color: #fff; padding: 12px 20px; border-radius: 50px;
        font-weight: 700; text-decoration: none; margin-top: 25px; font-size: .9rem;
        transition: background .2s;
    }
    .info-wa-btn:hover { background: #1ebe57; color: #fff; }

    /* Doc grid helpers */
    .doc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 576px) { .doc-grid { grid-template-columns: 1fr; } }
    .doc-grid .doc-full { grid-column: 1 / -1; }

    .field-error { color: #be123c; font-size: .82rem; margin-top: 4px; }
</style>

{{-- Breadcrumb bar --}}
<section style="background:#fff;border-bottom:1px solid var(--border);padding:14px 0;">
    <div class="container">
        <nav class="order-breadcrumb">
            <a href="{{ url('/') }}"><i class="fa-solid fa-house" style="font-size:.8rem"></i> Beranda</a>
            <span class="mx-2">›</span>
            <a href="{{ url($serviceInfo['url']) }}">{{ $serviceInfo['label'] }}</a>
            <span class="mx-2">›</span>
            <span>Form Order – Paket Professional</span>
        </nav>
    </div>
</section>

<section class="order-page">
    <div class="container">
        <div class="row g-4 justify-content-center">

            {{-- ── FORM ── --}}
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
                        <h2><i class="fa-solid fa-building-columns me-2"></i>Form Order PT Perorangan</h2>
                        <p>Isi semua data perusahaan & direktur dengan benar. Tim kami akan memproses dalam 1×24 jam.</p>
                    </div>
                    <div class="order-card-body">

                        {{-- Badges --}}
                        <div class="mb-1">
                            <span class="service-badge">
                                <i class="fa-solid fa-building"></i>
                                {{ $serviceInfo['label'] }}
                            </span>
                        </div>
                        <div>
                            <span class="package-badge">
                                <i class="fa-solid fa-star"></i>
                                {{ $packageLabel }}
                            </span>
                        </div>

                        <form action="{{ route('order.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
                            @csrf
                            <input type="hidden" name="service" value="{{ $service }}">
                            <input type="hidden" name="package" value="{{ $package }}">

                            {{-- ── SECTION 1: Data Diri ── --}}
                            <p class="form-section-title"><i class="fa-solid fa-user me-1"></i>Data Diri</p>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email Akun</label>
                                    <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                </div>
                            </div>

                            {{-- ── SECTION 2: Data Perusahaan & Direktur ── --}}
                            <p class="form-section-title"><i class="fa-solid fa-building me-1"></i>Data Perusahaan & Direktur</p>
                            <div class="row g-3 mb-4">

                                {{-- Nama Direktur --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Direktur <span class="req">*</span>
                                    </label>
                                    <input type="text" name="director_name" id="director_name"
                                           value="{{ old('director_name') }}"
                                           class="form-control @error('director_name') border-danger @enderror"
                                           placeholder="Sesuai KTP Direktur" required>
                                    @error('director_name') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- No. HP Direktur --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        No. HP Direktur <span class="req">*</span>
                                    </label>
                                    <input type="text" name="director_phone" id="director_phone"
                                           value="{{ old('director_phone') }}"
                                           class="form-control @error('director_phone') border-danger @enderror"
                                           placeholder="Contoh: 081234567890" required>
                                    @error('director_phone') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Nama Perusahaan --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama Perusahaan <span class="req">*</span>
                                    </label>
                                    <input type="text" name="company_name" id="company_name"
                                           value="{{ old('company_name') }}"
                                           class="form-control @error('company_name') border-danger @enderror"
                                           placeholder="PT Nama Perusahaan Anda" required>
                                    @error('company_name') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Email Perusahaan --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Email Perusahaan <span class="req">*</span>
                                    </label>
                                    <input type="email" name="company_email" id="company_email"
                                           value="{{ old('company_email') }}"
                                           class="form-control @error('company_email') border-danger @enderror"
                                           placeholder="info@perusahaan.com" required>
                                    @error('company_email') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Nama PIC --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Nama PIC (Penghubung) <span class="req">*</span>
                                    </label>
                                    <input type="text" name="pic_name" id="pic_name"
                                           value="{{ old('pic_name') }}"
                                           class="form-control @error('pic_name') border-danger @enderror"
                                           placeholder="Nama Person In Charge" required>
                                    @error('pic_name') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- No. HP PIC --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        No. HP PIC <span class="req">*</span>
                                    </label>
                                    <input type="text" name="pic_phone" id="pic_phone"
                                           value="{{ old('pic_phone') }}"
                                           class="form-control @error('pic_phone') border-danger @enderror"
                                           placeholder="Contoh: 081234567890" required>
                                    @error('pic_phone') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Bidang Usaha --}}
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Bidang Usaha <span class="req">*</span>
                                    </label>
                                    <input type="text" name="business_field" id="business_field"
                                           value="{{ old('business_field') }}"
                                           class="form-control @error('business_field') border-danger @enderror"
                                           placeholder="Contoh: Perdagangan, Teknologi, Jasa" required>
                                    @error('business_field') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- Alamat Operasional --}}
                                <div class="col-12">
                                    <label class="form-label">
                                        Alamat Operasional <span class="req">*</span>
                                    </label>
                                    <textarea name="operational_address" id="operational_address"
                                              class="form-control @error('operational_address') border-danger @enderror"
                                              placeholder="Jl. Nama Jalan No. XX, Kelurahan, Kecamatan, Kota, Provinsi" required>{{ old('operational_address') }}</textarea>
                                    @error('operational_address') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            {{-- ── SECTION 3: Catatan ── --}}
                            <p class="form-section-title"><i class="fa-solid fa-note-sticky me-1"></i>Catatan Tambahan</p>
                            <div class="mb-4">
                                <label class="form-label">Catatan / Pertanyaan <span class="opt-badge">Opsional</span></label>
                                <textarea name="notes" class="form-control"
                                    placeholder="Tuliskan pertanyaan atau informasi tambahan untuk tim kami…">{{ old('notes') }}</textarea>
                            </div>

                            {{-- ── SECTION 4: Upload Dokumen Wajib ── --}}
                            <p class="form-section-title"><i class="fa-solid fa-upload me-1"></i>Upload Dokumen Wajib</p>
                            <p style="font-size:.85rem;color:var(--gray);margin-top:-8px;margin-bottom:16px;">
                                <i class="fa-solid fa-circle-info me-1"></i>
                                Semua 5 dokumen berikut <strong>wajib</strong> diunggah. Format: JPG, PNG, atau PDF · Maks. 5 MB per file.
                            </p>

                            <div class="doc-grid mb-4">

                                {{-- Akta Pendirian --}}
                                <div class="@error('akta_pendirian') border-danger-upload @enderror">
                                    <label class="form-label">
                                        Akta Pendirian <span class="req">*</span>
                                    </label>
                                    <div class="file-upload-box @error('akta_pendirian') border-danger-upload @enderror">
                                        <input type="file" name="akta_pendirian" accept=".jpg,.jpeg,.png,.pdf" required
                                               onchange="showFileName(this,'aktaName')">
                                        <div class="upload-icon"><i class="fa-solid fa-file-contract"></i></div>
                                        <div class="upload-label">Klik atau seret file</div>
                                        <div class="upload-hint">Akta Pendirian Perusahaan</div>
                                        <div class="file-name" id="aktaName"></div>
                                    </div>
                                    @error('akta_pendirian') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- NPWP Perusahaan --}}
                                <div>
                                    <label class="form-label">
                                        NPWP Perusahaan <span class="req">*</span>
                                    </label>
                                    <div class="file-upload-box @error('npwp_perusahaan') border-danger-upload @enderror">
                                        <input type="file" name="npwp_perusahaan" accept=".jpg,.jpeg,.png,.pdf" required
                                               onchange="showFileName(this,'npwpPerusahaanName')">
                                        <div class="upload-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                                        <div class="upload-label">Klik atau seret file</div>
                                        <div class="upload-hint">NPWP Perusahaan</div>
                                        <div class="file-name" id="npwpPerusahaanName"></div>
                                    </div>
                                    @error('npwp_perusahaan') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- SK Kemenkumham --}}
                                <div class="doc-full">
                                    <label class="form-label">
                                        SK Kemenkumham <span class="req">*</span>
                                    </label>
                                    <div class="file-upload-box @error('sk_kemenkumham') border-danger-upload @enderror">
                                        <input type="file" name="sk_kemenkumham" accept=".jpg,.jpeg,.png,.pdf" required
                                               onchange="showFileName(this,'skKemenkumhamName')">
                                        <div class="upload-icon"><i class="fa-solid fa-landmark"></i></div>
                                        <div class="upload-label">Klik atau seret file</div>
                                        <div class="upload-hint">Surat Keputusan dari Kemenkumham RI</div>
                                        <div class="file-name" id="skKemenkumhamName"></div>
                                    </div>
                                    @error('sk_kemenkumham') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- KTP Direktur --}}
                                <div>
                                    <label class="form-label">
                                        KTP Direktur <span class="req">*</span>
                                    </label>
                                    <div class="file-upload-box @error('ktp_direktur') border-danger-upload @enderror">
                                        <input type="file" name="ktp_direktur" accept=".jpg,.jpeg,.png,.pdf" required
                                               onchange="showFileName(this,'ktpDirName')">
                                        <div class="upload-icon"><i class="fa-solid fa-id-card"></i></div>
                                        <div class="upload-label">Klik atau seret file</div>
                                        <div class="upload-hint">KTP Direktur Utama</div>
                                        <div class="file-name" id="ktpDirName"></div>
                                    </div>
                                    @error('ktp_direktur') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                                {{-- NPWP Direktur --}}
                                <div>
                                    <label class="form-label">
                                        NPWP Direktur <span class="req">*</span>
                                    </label>
                                    <div class="file-upload-box @error('npwp_direktur') border-danger-upload @enderror">
                                        <input type="file" name="npwp_direktur" accept=".jpg,.jpeg,.png,.pdf" required
                                               onchange="showFileName(this,'npwpDirName')">
                                        <div class="upload-icon"><i class="fa-solid fa-file-invoice"></i></div>
                                        <div class="upload-label">Klik atau seret file</div>
                                        <div class="upload-hint">NPWP Pribadi Direktur</div>
                                        <div class="file-name" id="npwpDirName"></div>
                                    </div>
                                    @error('npwp_direktur') <div class="field-error">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            {{-- Submit --}}
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

            {{-- ── SIDEBAR ── --}}
            <div class="col-lg-4 d-none d-lg-block">
                <div class="order-info-box">
                    <h5><i class="fa-solid fa-circle-info me-2"></i>Informasi Order</h5>
                    <div class="info-item">
                        <i class="fa-solid fa-building"></i>
                        <div><strong>{{ $serviceInfo['label'] }}</strong><span>{{ $packageLabel }}</span></div>
                    </div>
                    <div class="info-item">
                        <i class="fa-solid fa-file-lines"></i>
                        <div><strong>Dokumen Wajib</strong><span>5 dokumen harus diunggah saat order</span></div>
                    </div>
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

                    {{-- Checklist dokumen --}}
                    <div style="background:rgba(255,255,255,.1);border-radius:12px;padding:14px;margin-top:10px;font-size:.83rem;">
                        <p style="font-weight:700;margin-bottom:8px;opacity:.9;"><i class="fa-solid fa-list-check me-1"></i>Checklist Dokumen</p>
                        <ul style="margin:0;padding-left:18px;opacity:.85;line-height:1.8;">
                            <li>Akta Pendirian</li>
                            <li>NPWP Perusahaan</li>
                            <li>SK Kemenkumham</li>
                            <li>KTP Direktur</li>
                            <li>NPWP Direktur</li>
                        </ul>
                    </div>

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
