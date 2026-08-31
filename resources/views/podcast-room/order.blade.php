@extends('layout.app')
@section('content')
<style>
:root{--primary:#4e0516;--primary-l:#7a0a23;--accent:#c9a03d;--dark:#1e1b2b;--gray:#64748b;--bg:#fdf8f5;}
body{font-family:'Inter',-apple-system,sans-serif;background:var(--bg);}
.order-container{max-width:640px;margin:60px auto;background:#fff;border-radius:20px;box-shadow:0 10px 40px rgba(0,0,0,.05);border:1px solid #f0e4e8;padding:40px;}
.order-header{text-align:center;margin-bottom:28px;}
.order-header h2{font-size:1.7rem;font-weight:800;color:var(--dark);margin-bottom:8px;}
.order-header p{color:var(--gray);font-size:.95rem;}
.form-group{margin-bottom:18px;}
.form-group label{display:block;font-weight:600;margin-bottom:7px;color:var(--dark);font-size:.93rem;}
.form-control{width:100%;padding:12px 15px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:.95rem;color:#334155;transition:border-color .2s,box-shadow .2s;}
.form-control:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(78,5,22,.08);}
.form-control[readonly]{background:#f8f5f6;color:var(--gray);cursor:not-allowed;}
.btn-submit{display:block;width:100%;padding:14px;background:var(--primary);color:#fff;border:none;border-radius:50px;font-size:1rem;font-weight:700;cursor:pointer;transition:background .2s;margin-top:24px;}
.btn-submit:hover{background:var(--primary-l);}
.bank-box{background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:18px;margin-bottom:20px;}
.bank-box h5{font-size:1rem;font-weight:700;color:var(--dark);margin-bottom:12px;}
.bank-row{display:flex;justify-content:space-between;margin-bottom:7px;font-size:.9rem;}
.bank-row.last{margin-bottom:0;border-top:1px dashed #e2e8f0;padding-top:8px;margin-top:8px;}

/* Booking Summary */
.booking-summary {
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
.booking-summary h5 {
    font-size: 1rem;
    font-weight: 800;
    color: var(--dark);
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.88rem;
}
.summary-row:last-child { border-bottom: none; }
.summary-row .label { color: var(--gray); }
.summary-row .value { font-weight: 600; color: var(--dark); }
.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0 0;
    margin-top: 8px;
    border-top: 2px solid #e2e8f0;
}
.summary-total .label { font-weight: 700; color: var(--dark); font-size: 0.95rem; }
.summary-total .value { font-weight: 800; color: var(--primary); font-size: 1.25rem; }

/* Price notes */
.price-note {
    background: #fafafa;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 14px 16px;
    margin-bottom: 18px;
    font-size: 0.83rem;
    color: #374151;
    line-height: 1.6;
}
.price-note p { margin: 0 0 4px; }
.price-note p:last-child { margin: 0; }

/* Durasi stepper */
.durasi-stepper {
    display: flex;
    align-items: center;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}
.durasi-stepper button {
    background: #f8fafc;
    border: none;
    padding: 12px 22px;
    color: var(--dark);
    font-weight: 800;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background .2s;
    flex-shrink: 0;
}
.durasi-stepper button:hover { background: #f0e4e8; color: var(--primary); }
.durasi-stepper input {
    border: none;
    border-left: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    border-radius: 0;
    text-align: center;
    font-weight: 700;
    font-size: 1.05rem;
    pointer-events: none;
    background: #fff;
    flex: 1;
}

/* Modal Syarat & Ketentuan Podcast */
.podcast-terms-modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.7);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 10500;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.podcast-terms-modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}
.podcast-terms-modal {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 680px;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(226, 232, 240, 0.8);
    overflow: hidden;
    transform: scale(0.95) translateY(10px);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.podcast-terms-modal-overlay.active .podcast-terms-modal {
    transform: scale(1) translateY(0);
}
.ptm-header {
    padding: 20px 24px;
    background: #fdf8f9;
    border-bottom: 1px solid #f1e2e5;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.ptm-close {
    background: transparent;
    border: none;
    font-size: 1.8rem;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    transition: color 0.2s;
    padding: 0 4px;
}
.ptm-close:hover {
    color: #4e0516;
}
.ptm-body {
    padding: 22px 24px;
    overflow-y: auto;
    flex: 1;
}
.p-terms-list {
    display: flex;
    flex-direction: column;
    gap: 11px;
}
.p-terms-item {
    display: flex;
    gap: 14px;
    padding: 12px 15px;
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    transition: all 0.2s ease;
}
.p-terms-item:hover {
    background: #fdf2f4;
    border-color: #fce7eb;
    transform: translateX(3px);
}
.p-terms-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: #4e0516;
    color: #fff;
    font-weight: 700;
    font-size: 0.82rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.p-terms-text {
    font-size: 0.88rem;
    line-height: 1.55;
    color: #334155;
}
.p-terms-text strong {
    color: #1e293b;
    display: block;
    margin-bottom: 2px;
}
.p-terms-text p {
    margin-bottom: 0;
    color: #475569;
}
.ptm-footer {
    padding: 16px 24px;
    border-top: 1px solid #f1e2e5;
    background: #fafafa;
    display: flex;
    justify-content: flex-end;
}
.btn-ptm-agree {
    background: #4e0516;
    color: #fff;
    border: none;
    padding: 12px 26px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.92rem;
    cursor: pointer;
    transition: all 0.2s;
}
.btn-ptm-agree:hover {
    background: #6e0c24;
    box-shadow: 0 4px 14px rgba(78, 5, 22, 0.25);
}

/* ===== Clean & Professional Add-On Component ===== */
.addon-clean-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 16px 18px;
    margin-bottom: 22px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
}
.addon-clean-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    margin-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.addon-clean-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
}
.addon-clean-hint {
    font-size: 0.76rem;
    color: #64748b;
    font-weight: 500;
    background: #f8fafc;
    padding: 3px 8px;
    border-radius: 6px;
    border: 1px solid #edf2f7;
}
.addon-clean-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.addon-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1.5px solid #edf2f7;
    border-radius: 10px;
    transition: all 0.2s ease;
    cursor: pointer;
    user-select: none;
}
.addon-row:hover {
    background: #f1f5f9;
    border-color: #cbd5e1;
}
.addon-row.active {
    background: #fdf6f7;
    border-color: #f1c7cf;
}
.addon-row-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.addon-icon-circle {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #4e0516;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
    transition: all 0.2s ease;
}
.addon-row.active .addon-icon-circle {
    background: #4e0516;
    color: #ffffff;
    border-color: #4e0516;
}
.addon-item-name {
    font-size: 0.88rem;
    font-weight: 700;
    color: #1e293b;
    display: block;
    line-height: 1.3;
}
.addon-limit-tag {
    font-size: 0.7rem;
    font-weight: 600;
    color: #64748b;
    background: #e2e8f0;
    padding: 1px 6px;
    border-radius: 4px;
    margin-left: 4px;
}
.addon-item-price {
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 500;
}
.addon-row.active .addon-item-price {
    color: #4e0516;
    font-weight: 700;
}
.addon-row-right {
    flex-shrink: 0;
}
.btn-addon-check {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1.5px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.addon-row.active .btn-addon-check {
    background: #4e0516;
    border-color: #4e0516;
    color: #ffffff;
}
.mini-stepper {
    display: inline-flex;
    align-items: center;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 20px;
    padding: 2px 4px;
    gap: 4px;
    transition: border-color 0.2s;
}
.addon-row.active .mini-stepper {
    border-color: #4e0516;
}
.btn-mini-step {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    border: none;
    background: #f1f5f9;
    color: #1e293b;
    font-weight: 700;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s;
    padding: 0;
}
.btn-mini-step:hover:not(:disabled) {
    background: #4e0516;
    color: #fff;
}
.btn-mini-step:disabled {
    opacity: 0.35;
    cursor: not-allowed;
}
.mini-step-val {
    min-width: 24px;
    text-align: center;
    font-size: 0.86rem;
    font-weight: 800;
    color: #1e293b;
}
.addon-row.active .mini-step-val {
    color: #4e0516;
}

@media(max-width:768px){
    .order-container{margin:30px 15px;padding:28px 18px;}
    .addon-row{padding:10px 12px;}
}
</style>

<div class="container pt-5 pb-5" style="margin-top:80px!important;">
<div class="order-container">
    <div class="order-header">
        <div style="font-size:2.8rem;margin-bottom:10px;">🎙️</div>
        @if(($package ?? '') == 'paket' || request('package') == 'paket')
            <h2 data-i18n="pr.buy_package_title">Beli Paket Podcast Room</h2>
            <p data-i18n="pr.buy_package_subtitle">Dapatkan akses kuota 20 jam podcast studio untuk 1 tahun</p>
        @else
            <h2 data-i18n="pr.reserv_title">Reservasi Ruang Podcast</h2>
            <p data-i18n="pr.reserv_subtitle">Lengkapi form di bawah, upload bukti transfer, lalu klik Pesan.</p>
        @endif
    </div>

    @if($errors->any())
    <div style="padding:14px;border-radius:10px;margin-bottom:18px;background:#fee2e2;color:#991b1b;border:1px solid #f87171;">
        <ul class="mb-0" style="padding-left:18px;">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('podcast-room.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Benefit Choice (hanya muncul saat reservasi jika user punya active benefit) --}}
        @if(($package ?? '') !== 'paket' && isset($activeBenefit) && $activeBenefit)
            <div id="benefitChoiceBox" style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:12px; padding:20px; margin-bottom:24px;">
                <h5 style="color:#15803d; font-weight:800; margin-bottom:8px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-gift" style="color:#16a34a;"></i>
                    <span data-i18n="pr.benefit_box_title">Anda Memiliki Benefit Paket</span>
                </h5>
                <p style="color:#166534; margin-bottom:14px; font-size:.92rem; line-height:1.5;">
                    Benefit: <strong>{{ $activeBenefit->paket }}</strong> — <span data-i18n="pr.remaining">Sisa:</span> <strong>{{ \App\Models\RoomBenefit::formatMinutes($activeBenefit->remaining_minutes) }}</strong>
                    (Berlaku hingga {{ $activeBenefit->expired_at ? $activeBenefit->expired_at->format('d M Y') : __('mr.no_expired') }})
                </p>
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    <label style="cursor:pointer; padding:10px 18px; border:2px solid #16a34a; border-radius:8px; font-weight:600; font-size:.88rem; display:flex; align-items:center; gap:8px; background:#fff;">
                        <input type="radio" name="benefit_choice" value="use_benefit" id="useBenefitRadio"
                            {{ old('benefit_choice','use_benefit') === 'use_benefit' ? 'checked' : '' }}
                            onchange="onBenefitChoiceChange()" style="margin:0;">
                        <span style="color:#15803d;" data-i18n="pr.use_benefit_free">✅ Gunakan Benefit Gratis (Potong Kuota)</span>
                    </label>
                    <label style="cursor:pointer; padding:10px 18px; border:2px solid #cbd5e1; border-radius:8px; font-weight:600; font-size:.88rem; display:flex; align-items:center; gap:8px; background:#fff;">
                        <input type="radio" name="benefit_choice" value="pay_manual" id="payManualRadio"
                            {{ old('benefit_choice') === 'pay_manual' ? 'checked' : '' }}
                            onchange="onBenefitChoiceChange()" style="margin:0;">
                        <span style="color:#374151;" data-i18n="pr.pay_manual_label">Bayar Mandiri (Upload Bukti Transfer)</span>
                    </label>
                </div>
            </div>
            <input type="hidden" name="pay_manually" id="payManuallyInput" value="{{ old('benefit_choice') === 'pay_manual' ? '1' : '0' }}">
        @endif

        @if(($package ?? '') !== 'paket' && isset($quota) && !now()->greaterThan($quota->expired_at) && $quota->remaining_seconds > 0)
            <div style="background:#fdf2f8; border:1px solid #fbcfe8; border-radius:10px; padding:20px; margin-bottom:20px;">
                <h5 style="color:#be185d; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-gem"></i> <span data-i18n="mr.has_quota_title">Anda Memiliki Quota Ruangan!</span></h5>
                <p style="margin-bottom:15px; color:#831843;"><span data-i18n="mr.remaining_quota">Sisa quota Anda:</span> <strong>{{ $quota->formatted_remaining_time }}</strong>
                    (Berlaku hingga {{ \Carbon\Carbon::parse($quota->expired_at)->format('d M Y') }})</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="use_quota" id="use_quota" value="1" checked onchange="togglePaymentProof()">
                    <label class="form-check-label fw-bold text-dark" style="margin-bottom:0;" for="use_quota">
                        <span data-i18n="mr.use_quota_label">Gunakan Quota untuk Reservasi ini (Bebas Biaya)</span>
                    </label>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label for="nama"><span data-i18n="order.label_fullname">Nama Lengkap</span> <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control" required
                placeholder="Masukkan nama Anda"
                value="{{ old('nama', auth()->user()->name ?? '') }}" readonly>
        </div>

        <div class="form-group">
            <label for="podcast_title"><span data-i18n="pr.label_podcast_title">Judul / Nama Podcast</span> <span style="font-size:.8rem;color:var(--gray);" data-i18n="order.optional">(opsional)</span></label>
            <input type="text" id="podcast_title" name="podcast_title" class="form-control"
                placeholder="Misal: The Business Talk" data-i18n-placeholder="pr.placeholder_podcast_title" value="{{ old('podcast_title') }}">
        </div>

        @if(($package ?? '') === 'paket' || request('package') === 'paket')
            {{-- Package Purchase Box (Khusus Pembelian Paket 20 Jam Baru) --}}
            <input type="hidden" name="package" value="paket">
            <input type="hidden" name="durasi" id="durasi" value="20">
            <input type="hidden" id="durasi_display" value="20">
            <input type="hidden" id="tanggal" name="tanggal" value="">
            <input type="hidden" id="jam" name="jam" value="">
            <input type="hidden" id="jam_selesai_display" value="">

            <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:12px; padding:20px; margin-bottom:24px;">
                <h5 style="color:#15803d; font-weight:800; margin-bottom:12px; display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-box-open" style="color:#16a34a;"></i>
                    <span data-i18n="pr.package_box_title">Paket Podcast Room (20 Jam / 1 Tahun)</span>
                </h5>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="color:#166534;" data-i18n="pr.package_duration">Total Kuota:</span>
                    <strong style="color:#14532d;">20 Jam</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="color:#166534;" data-i18n="pr.package_validity">Masa Berlaku:</span>
                    <strong style="color:#14532d;">1 Tahun (12 Bulan)</strong>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <span style="color:#166534;" data-i18n="pr.package_benefit_note">Fasilitas:</span>
                    <strong style="color:#14532d;">Studio Lengkap, Operator, AC, WiFi, Pantry</strong>
                </div>
                <div style="display:flex; justify-content:space-between; border-top:1px dashed #86efac; padding-top:10px; margin-top:10px;">
                    <span style="color:#166534;" data-i18n="pr.package_price">Harga Paket:</span>
                    <strong style="color:#15803d; font-size:1.25rem;">Rp 5.000.000</strong>
                </div>
            </div>
        @else
            {{-- Reservation Booking Mode (Jadwal Tanggal & Jam Booking untuk Benefit maupun Bayar Mandiri) --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tanggal"><span data-i18n="mr.label_use_date">Tanggal Penggunaan</span> <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('tanggal', $tanggal ?? '') }}"
                            onchange="updateSummary()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="jam"><span data-i18n="pr.label_start_time">Jam Booking</span> <span class="text-danger">*</span></label>
                        <input type="time" id="jam" name="jam" class="form-control" required
                            value="{{ old('jam', $jam ?? '') }}"
                            onchange="updateSummary()">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><span data-i18n="pr.label_duration">Durasi (Jam)</span> <span class="text-danger">*</span></label>
                        <div class="durasi-stepper">
                            <button type="button" onclick="updateDurasi(-1)">−</button>
                            <input type="text" id="durasi_display" class="form-control"
                                value="{{ old('durasi', ($durasi == 20 ? 2 : ($durasi ?? 2))) }}" readonly>
                            <button type="button" onclick="updateDurasi(1)">+</button>
                        </div>
                        <input type="hidden" id="durasi" name="durasi" value="{{ old('durasi', ($durasi == 20 ? 2 : ($durasi ?? 2))) }}">
                        <div style="font-size:.78rem;color:var(--gray);margin-top:5px;" data-i18n="pr.min_duration_hint">Minimum 1 jam</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label data-i18n="pr.label_end_time">Jam Selesai</label>
                        <input type="text" id="jam_selesai_display" class="form-control" readonly
                            placeholder="Otomatis terhitung" data-i18n-placeholder="pr.placeholder_auto_calc" style="background:#f8f5f6;color:var(--gray);">
                    </div>
                </div>
            </div>

            {{-- ===== ADD-ON PERLENGKAPAN & OPERATOR (OPSIONAL) ===== --}}
            <div id="addonSection" class="addon-clean-card">
                <div class="addon-clean-header">
                    <span class="addon-clean-title"><i class="fa-solid fa-sliders text-muted me-1"></i> Add-On Tambahan <small class="text-muted fw-normal">(Opsional)</small></span>
                    <span class="addon-clean-hint"><i class="fa-regular fa-clock me-1"></i> Tarif per jam durasi</span>
                </div>

                <div class="addon-clean-list">
                    {{-- 1. Mikrofon (+1 Unit Maks) --}}
                    <div class="addon-row" id="row_mic" onclick="toggleAddonSingle('addon_mic', 'row_mic')">
                        <div class="addon-row-left">
                            <div class="addon-icon-circle"><i class="fa-solid fa-microphone-lines"></i></div>
                            <div>
                                <span class="addon-item-name">Mikrofon Tambahan <span class="addon-limit-tag">Maks. 1</span></span>
                                <span class="addon-item-price">+ Rp 50.000 / jam</span>
                            </div>
                        </div>
                        <div class="addon-row-right">
                            <button type="button" class="btn-addon-check" id="btn_toggle_mic">
                                <i class="fa-solid fa-plus icon-state"></i> <span class="state-text">Pilih</span>
                            </button>
                            <input type="hidden" name="addon_mic" id="addon_mic" value="{{ old('addon_mic', 0) }}">
                        </div>
                    </div>

                    {{-- 2. Headphone (+1 Unit Maks) --}}
                    <div class="addon-row" id="row_headphone" onclick="toggleAddonSingle('addon_headphone', 'row_headphone')">
                        <div class="addon-row-left">
                            <div class="addon-icon-circle"><i class="fa-solid fa-headphones-simple"></i></div>
                            <div>
                                <span class="addon-item-name">Headphone Tambahan <span class="addon-limit-tag">Maks. 1</span></span>
                                <span class="addon-item-price">+ Rp 50.000 / jam</span>
                            </div>
                        </div>
                        <div class="addon-row-right">
                            <button type="button" class="btn-addon-check" id="btn_toggle_headphone">
                                <i class="fa-solid fa-plus icon-state"></i> <span class="state-text">Pilih</span>
                            </button>
                            <input type="hidden" name="addon_headphone" id="addon_headphone" value="{{ old('addon_headphone', 0) }}">
                        </div>
                    </div>

                    {{-- 3. Kamera Tambahan (Maks 2 Unit) --}}
                    <div class="addon-row" id="row_camera">
                        <div class="addon-row-left">
                            <div class="addon-icon-circle"><i class="fa-solid fa-video"></i></div>
                            <div>
                                <span class="addon-item-name">Kamera Tambahan <span class="addon-limit-tag">Maks. 2</span></span>
                                <span class="addon-item-price">+ Rp 150.000 / unit / jam</span>
                            </div>
                        </div>
                        <div class="addon-row-right">
                            <div class="mini-stepper">
                                <button type="button" class="btn-mini-step" onclick="changeCamQty(-1)" id="btn_cam_minus" disabled>−</button>
                                <span class="mini-step-val" id="val_addon_camera">{{ old('addon_camera', 0) }}</span>
                                <button type="button" class="btn-mini-step" onclick="changeCamQty(1)" id="btn_cam_plus">+</button>
                            </div>
                            <input type="hidden" name="addon_camera" id="addon_camera" value="{{ old('addon_camera', 0) }}">
                        </div>
                    </div>

                    {{-- 4. Operator Podcast (Maks 1) --}}
                    <div class="addon-row" id="row_operator" onclick="toggleAddonSingle('addon_operator', 'row_operator')">
                        <div class="addon-row-left">
                            <div class="addon-icon-circle"><i class="fa-solid fa-headset"></i></div>
                            <div>
                                <span class="addon-item-name">Operator Podcast <span class="addon-limit-tag">Maks. 1</span></span>
                                <span class="addon-item-price">+ Rp 100.000 / jam</span>
                            </div>
                        </div>
                        <div class="addon-row-right">
                            <button type="button" class="btn-addon-check" id="btn_toggle_operator">
                                <i class="fa-solid fa-plus icon-state"></i> <span class="state-text">Pilih</span>
                            </button>
                            <input type="hidden" name="addon_operator" id="addon_operator" value="{{ old('addon_operator', 0) }}">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== RINGKASAN BOOKING ===== --}}
        <div class="booking-summary" id="bookingSummary">
            <h5><i class="fa-solid fa-receipt" style="color:var(--primary);"></i> <span data-i18n="pr.summary_title">Ringkasan Booking</span></h5>

            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_date">📅 Tanggal</span>
                <span class="value" id="sumTanggal">–</span>
            </div>
            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_start_time">⏰ Jam Booking</span>
                <span class="value" id="sumJamMulai">–</span>
            </div>
            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_duration">⏱️ Durasi</span>
                <span class="value" id="sumDurasi">–</span>
            </div>
            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_end_time">🏁 Jam Selesai</span>
                <span class="value" id="sumJamSelesai">–</span>
            </div>

            <div class="summary-row" id="rowBiayaBenefit" style="display:none;">
                <span class="label">💳 Biaya Ruang Studio</span>
                <span class="value text-success fw-bold">Rp 0 (Memotong Kuota Benefit)</span>
            </div>

            <div class="summary-row" id="rowHargaDasar">
                <span class="label" data-i18n="pr.sum_base_price">💰 Harga Paket (1–2 Jam)</span>
                <span class="value" id="sumHargaDasar">–</span>
            </div>
            <div class="summary-row" id="rowTambahan" style="display:none;">
                <span class="label" data-i18n="pr.sum_add_hours">➕ Tambahan Jam</span>
                <span class="value" id="sumTambahan">–</span>
            </div>

            {{-- Row Detail Add-On --}}
            <div id="rowAddonDetails" style="display:none; flex-direction:column; gap:5px; background:#fdf2f4; border:1px dashed #f1e2e5; padding:10px 14px; border-radius:8px; margin:8px 0;">
                <div style="display:flex; justify-content:space-between; font-weight:700; color:#4e0516; font-size:0.86rem;">
                    <span>✨ Add-On Perlengkapan &amp; Operator:</span>
                    <span id="sumAddonTotal">Rp 0</span>
                </div>
                <div id="addonItemsList" style="font-size:0.82rem; color:#475569; padding-left:2px; display:flex; flex-direction:column; gap:2px;">
                </div>
            </div>

            <div class="summary-total" id="summaryTotalBlock" style="flex-direction:column; align-items:stretch;">
                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                    <span class="label" style="font-weight:normal; font-size:0.9rem;">Subtotal</span>
                    <span class="value" id="sumSubtotal">Rp –</span>
                </div>
                <div style="display:flex; justify-content:space-between; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
                    <span class="label" style="font-weight:normal; font-size:0.9rem;">PPN 11%</span>
                    <span class="value" id="sumPpn">Rp –</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <span class="label" data-i18n="pr.sum_total">💳 Total Pembayaran</span>
                    <span class="value" id="sumTotal" style="font-size:1.2rem;">Rp –</span>
                </div>
            </div>
        </div>

        {{-- ===== PRICE NOTES ===== --}}
        <div class="price-note" id="priceNote">
            <p>📌 <strong data-i18n="pr.price_rules_title">Aturan Harga:</strong> <span data-i18n="pr.price_rules_desc_part1">Durasi 2 jam pertama menggunakan harga paket</span> <strong>Rp 700.000</strong>. <span data-i18n="pr.price_rules_desc_part2">Setelah melewati 2 jam, dikenakan tambahan</span> <strong>Rp 300.000 per jam</strong>.</p>
            <p>⏰ <strong data-i18n="pr.prep_time_hint">Harap datang 15 menit sebelum jadwal dimulai</strong> <span data-i18n="pr.prep_time_hint_part2">untuk persiapan dan pengecekan peralatan.</span></p>
        </div>

        {{-- Payment Info --}}
        <div id="payment-section">
            <div class="bank-box">
                <h5><i class="fa-solid fa-building-columns me-1"></i> <span data-i18n="order.payment_instruction_transfer">Instruksi Pembayaran (Transfer Bank)</span></h5>
                <p style="font-size:.88rem;color:var(--gray);margin-bottom:12px;" data-i18n="order.transfer_instruction">Silakan lakukan pembayaran ke rekening berikut:</p>
                <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom: 10px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Bank</span>
                        <strong style="color:#1e1b2b;">Mandiri</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">No. Rekening</span>
                        <strong style="color:#1e1b2b; font-size:1.1rem; letter-spacing:1px;">1760005097561</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Atas Nama</span>
                        <strong style="color:#1e1b2b;">PT Lawgika Associates</strong>
                    </div>
                </div>

                <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e2e8f0;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Bank</span>
                        <strong style="color:#1e1b2b;">BCA (Bank Central Asia)</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">No. Rekening</span>
                        <strong style="color:#1e1b2b; font-size:1.1rem; letter-spacing:1px;">664-0800389</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Atas Nama</span>
                        <strong style="color:#1e1b2b;">PT Lawgika Associates</strong>
                    </div>
                </div>
                <div style="margin-top:14px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                        <span style="color:var(--gray);font-size:.9rem;">Subtotal:</span>
                        <strong style="color:var(--dark);font-size:1.05rem;" id="subtotalDisplay">Rp –</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
                        <span style="color:var(--gray);font-size:.9rem;">PPN 11%:</span>
                        <strong style="color:var(--dark);font-size:1.05rem;" id="ppnDisplay">Rp –</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:var(--gray);font-size:1rem;font-weight:700;" data-i18n="order.total_bill">Total Tagihan:</span>
                        <strong style="color:var(--primary);font-size:1.4rem;" id="totalDisplay">Rp –</strong>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="payment_proof"><span data-i18n="order.upload_payment_proof">Upload Bukti Pembayaran</span> <span class="text-danger">*</span></label>
                <div id="dropzone_box" style="border:2px dashed #cbd5e1; border-radius:12px; padding:20px; text-align:center; cursor:pointer; background:#fafafa; transition:all 0.2s ease;"
                    onclick="document.getElementById('payment_proof').click()">
                    
                    {{-- Empty / Initial State --}}
                    <div id="dropzone_default">
                        <i class="fa-solid fa-cloud-arrow-up"
                            style="font-size:2.2rem; color:var(--primary); margin-bottom:8px; display:block;"></i>
                        <p style="color:var(--gray); margin:0; font-size:0.9rem; font-weight:600;" data-i18n="pr.upload_proof_click">Klik untuk upload bukti transfer</p>
                        <p style="color:#94a3b8; margin:4px 0 0; font-size:0.8rem;" data-i18n="order.upload_proof_hint_2mb">JPG, PNG, JPEG — Maks. 2MB</p>
                    </div>

                    {{-- Preview State --}}
                    <div id="dropzone_preview" style="display:none;">
                        <div style="position:relative; display:inline-block; margin-bottom:10px;">
                            <img id="image_preview_img" src="" alt="Bukti Transfer" style="max-height:140px; max-width:100%; border-radius:8px; border:1px solid #e2e8f0; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-success" style="font-size:.78rem; color:#fff !important;"><i class="fa fa-circle-check me-1"></i>File Terpilih</span>
                            <span id="file_name_display" style="color:var(--dark); font-weight:600; font-size:0.9rem;"></span>
                            <span id="file_size_display" class="text-muted" style="font-size:0.8rem;"></span>
                        </div>
                        <p class="text-muted mb-0 mt-2" style="font-size:0.78rem;"><i class="fa fa-rotate me-1"></i>Klik untuk mengganti gambar</p>
                    </div>
                </div>
                <input type="file" id="payment_proof" name="payment_proof"
                    accept="image/jpeg,image/png,.jpg,.jpeg,.png" required style="display:none;" onchange="showFile(this)">
            </div>
        </div>

        {{-- Syarat & Ketentuan Notice Banner --}}
        <div style="background:#f8fafc; border:1.5px dashed #cbd5e1; border-radius:12px; padding:14px 18px; margin-bottom:18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
            <div style="display:flex; align-items:center; gap:10px;">
                <span style="font-size:1.3rem;">📌</span>
                <span style="font-size:0.88rem; color:#334155; font-weight:500;">
                    Dengan memesan, Anda menyetujui <strong style="color:#4e0516;">Syarat &amp; Ketentuan Sewa Studio Podcast</strong>.
                </span>
            </div>
            <button type="button" onclick="openPodcastTermsModal()" style="background:#fff; border:1.5px solid #4e0516; color:#4e0516; padding:8px 16px; border-radius:8px; font-weight:700; font-size:0.84rem; cursor:pointer; transition:all .2s; display:inline-flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-file-contract"></i> <span>Baca Syarat &amp; Ketentuan</span>
            </button>
        </div>

        <div id="infoAlert" style="background:#fef9c3;border:1px solid #fde047;border-radius:10px;padding:13px;font-size:.88rem;color:#713f12;margin-bottom:18px;">
            <strong data-i18n="order.info_label">⚡ Info:</strong> <span id="infoAlertText">Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran disetujui.</span>
        </div>

        <div style="display:flex; gap:15px;">
            <button type="submit" class="btn-submit" style="flex:1; margin-top:0;">
                <i class="fa-solid fa-calendar-check me-1"></i> <span id="submitBtnText" data-i18n="pr.submit_btn">Pesan Ruang Podcast Sekarang</span>
            </button>
            <button type="button" class="btn-submit" onclick="sendWhatsApp()"
                style="flex:1; margin-top:0; background:#25D366; color:#fff; border:none;">
                <i class="fa-brands fa-whatsapp me-1"></i> <span data-i18n="order.contact_wa_btn">Hubungi WhatsApp</span>
            </button>
        </div>
        <div style="text-align:center;margin-top:14px;">
            <a href="{{ url('/sewa-ruang-podcast') }}" style="color:var(--gray);font-size:.88rem;text-decoration:none;">
                <i class="fa-solid fa-arrow-left me-1"></i> <span data-i18n="pr.back_to_slots">Kembali ke Pilih Slot</span>
            </a>
        </div>
    </form>
</div>
</div>

{{-- ===== MODAL SYARAT & KETENTUAN SEWA STUDIO PODCAST ===== --}}
<div class="podcast-terms-modal-overlay" id="podcastTermsModalOverlay" style="display:none;" onclick="if(event.target===this) closePodcastTermsModal()">
    <div class="podcast-terms-modal">
        <div class="ptm-header">
            <div class="d-flex align-items-center gap-2">
                <span style="font-size: 1.35rem;">📌</span>
                <h4 class="mb-0 fw-bold" style="color: #4e0516; font-size: 1.18rem;">Syarat &amp; Ketentuan Sewa Studio Podcast Lawgika</h4>
            </div>
            <button type="button" class="ptm-close" onclick="closePodcastTermsModal()">&times;</button>
        </div>
        <div class="ptm-body">
            <p class="text-muted small mb-3">Harap membaca dan memahami 19 butir syarat &amp; ketentuan penggunaan studio podcast Lawgika berikut:</p>
            <div class="p-terms-list">
                <div class="p-terms-item">
                    <div class="p-terms-num">1</div>
                    <div class="p-terms-text">
                        <strong>Waktu Reservasi</strong>
                        <p>Reservasi wajib dilakukan dan dikonfirmasi selambat-lambatnya <strong>1 (satu) hari</strong> sebelum jadwal penggunaan studio.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">2</div>
                    <div class="p-terms-text">
                        <strong>Validitas Reservasi</strong>
                        <p>Reservasi dianggap valid setelah pembayaran <strong>diterima oleh Lawgika</strong>.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">3</div>
                    <div class="p-terms-text">
                        <strong>Waktu Kehadiran</strong>
                        <p>Penyewa <strong>wajib hadir 30 menit</strong> sebelum jadwal dimulai.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">4</div>
                    <div class="p-terms-text">
                        <strong>Keterlambatan Kedatangan</strong>
                        <p>Keterlambatan kedatangan <strong>tidak menambah</strong> durasi penggunaan studio.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">5</div>
                    <div class="p-terms-text">
                        <strong>Media Penyimpanan Data</strong>
                        <p>Customer disarankan membawa/menyediakan media penyimpanan data (flashdisk/SSD/HDD external) untuk mentransfer file audio dan visual.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">6</div>
                    <div class="p-terms-text">
                        <strong>Properti &amp; Peralatan Tambahan</strong>
                        <p>Apabila penyewa ingin membawa properti, peralatan, dekorasi, atau kebutuhan tambahan lainnya, <strong>wajib menginformasikannya kepada Lawgika</strong> sebelum hari penggunaan.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">7</div>
                    <div class="p-terms-text">
                        <strong>Penggunaan TV Studio</strong>
                        <p>Apabila memerlukan penggunaan TV mohon kirimkan file ukuran <strong>4K dengan format MP4</strong> sebelum hari penggunaan.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">8</div>
                    <div class="p-terms-text">
                        <strong>Perpanjangan Waktu (Overtime)</strong>
                        <p>Perpanjangan waktu penggunaan (overtime) akan dikenakan biaya tambahan sesuai tarif yang berlaku dan ditagihkan setelah sesi podcast selesai.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">9</div>
                    <div class="p-terms-text">
                        <strong>Ketentuan Reschedule</strong>
                        <p>Reschedule dapat dilakukan maksimal <strong>1 (satu) kali</strong> dengan pemberitahuan minimal <strong>1 x 24 jam</strong> sebelum jadwal penggunaan dan bergantung pada ketersediaan studio.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">10</div>
                    <div class="p-terms-text">
                        <strong>Kebijakan Pembatalan (Non-Refundable)</strong>
                        <p>Pembatalan setelah pembayaran dilakukan <strong>tidak dapat dikembalikan (non-refundable)</strong>.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">11</div>
                    <div class="p-terms-text">
                        <strong>Tanggung Jawab Fasilitas</strong>
                        <p>Penyewa <strong>bertanggung jawab</strong> atas setiap kerusakan atau kehilangan fasilitas yang disebabkan oleh penyewa maupun pihak yang dibawa oleh penyewa.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">12</div>
                    <div class="p-terms-text">
                        <strong>Bantuan Operator Awal</strong>
                        <p>Operator podcast hanya membantu untuk setup audio dan visual di awal sesuai permintaan customer.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">13</div>
                    <div class="p-terms-text">
                        <strong>Setup Khusus &amp; Bantuan Tambahan</strong>
                        <p>Jika membutuhkan setup khusus atau bantuan operator, mohon diinformasikan saat reservasi.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">14</div>
                    <div class="p-terms-text">
                        <strong>Penggunaan Tanpa Operator Lawgika</strong>
                        <p>Bagi customer yang tidak menggunakan jasa operator dari Lawgika, maka setiap kendala setelah video take bukan menjadi tanggung jawab dari Lawgika.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">15</div>
                    <div class="p-terms-text">
                        <strong>Antisipasi Kendala Teknis / Recording</strong>
                        <p>Dimohon untuk mengantisipasi kemungkinan kamera mati, overheat, hal yang berkaitan dengan recording berhenti.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">16</div>
                    <div class="p-terms-text">
                        <strong>Kualifikasi Operator Mandiri</strong>
                        <p>Untuk customer yang tidak menggunakan layanan operator disarankan untuk menyediakan operator yang berpengalaman dengan podcast.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">17</div>
                    <div class="p-terms-text">
                        <strong>Batasan Tanggung Jawab Konten</strong>
                        <p>Lawgika hanya bertindak sebagai penyedia fasilitas studio podcast dan tidak bertanggung jawab atas isi, materi, pernyataan, maupun konsekuensi hukum dari konten yang dibuat atau disampaikan oleh penyewa.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">18</div>
                    <div class="p-terms-text">
                        <strong>Ketertiban &amp; Kepatuhan Norma Hukum</strong>
                        <p>Dilarang menggunakan studio untuk kegiatan yang bertentangan dengan peraturan perundang-undangan, ketertiban umum, atau norma yang berlaku.</p>
                    </div>
                </div>
                <div class="p-terms-item">
                    <div class="p-terms-num">19</div>
                    <div class="p-terms-text">
                        <strong>Persetujuan Penggunaan Fasilitas</strong>
                        <p>Dengan melakukan pembayaran dan/atau menggunakan fasilitas studio, penyewa dianggap telah <strong>membaca, memahami, dan menyetujui</strong> seluruh syarat dan ketentuan ini.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="ptm-footer">
            <button type="button" class="btn-ptm-agree" onclick="closePodcastTermsModal()">
                <i class="fa-solid fa-check me-1"></i> Saya Mengerti &amp; Setuju
            </button>
        </div>
    </div>
</div>

<script>
// ===== Podcast Pricing Formula =====
function calcPodcastPrice(jam) {
    jam = parseInt(jam) || 0;
    if (jam <= 0) return 0;
    if (jam === 20) return 5000000;
    if (jam === 1) return 500000;
    if (jam === 2) return 700000;
    return 700000 + ((jam - 2) * 300000);
}

function formatRupiah(amount) {
    return 'Rp ' + amount.toLocaleString('id-ID');
}

// ===== Durasi stepper =====
function updateDurasi(step) {
    const h = document.getElementById('durasi');
    const d = document.getElementById('durasi_display');
    let val = parseInt(h.value) || 2;
    val += step;
    if (val < 1) val = 1;
    if (val > 20) val = 20;
    h.value = val;
    d.value = val;
    updateSummary();
}

// ===== Add-On Single Toggle Handler (Mic, Headphone, Operator) =====
function toggleAddonSingle(inputId, rowId) {
    const input = document.getElementById(inputId);
    const row = document.getElementById(rowId);
    if (!input) return;

    let current = parseInt(input.value) || 0;
    current = current === 1 ? 0 : 1;
    input.value = current;

    const btnKey = inputId.replace('addon_', '');
    const btn = document.getElementById('btn_toggle_' + btnKey);

    if (current === 1) {
        if (row) row.classList.add('active');
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-check icon-state"></i> <span class="state-text">Terpilih</span>';
        }
    } else {
        if (row) row.classList.remove('active');
        if (btn) {
            btn.innerHTML = '<i class="fa-solid fa-plus icon-state"></i> <span class="state-text">Pilih</span>';
        }
    }

    updateSummary();
}

// ===== Add-On Camera Stepper Handler (Max 2) =====
function changeCamQty(delta) {
    const input = document.getElementById('addon_camera');
    const display = document.getElementById('val_addon_camera');
    const row = document.getElementById('row_camera');
    const minusBtn = document.getElementById('btn_cam_minus');
    const plusBtn = document.getElementById('btn_cam_plus');
    if (!input) return;

    let current = parseInt(input.value) || 0;
    current += delta;
    if (current < 0) current = 0;
    if (current > 2) current = 2; // Max 2 camera

    input.value = current;
    if (display) display.textContent = current;
    if (minusBtn) minusBtn.disabled = (current <= 0);
    if (plusBtn) plusBtn.disabled = (current >= 2);

    if (row) {
        if (current > 0) row.classList.add('active');
        else row.classList.remove('active');
    }

    updateSummary();
}

// ===== Update Ringkasan & Harga =====
function updateSummary() {
    const isPackage = {{ (($package ?? '') === 'paket' || request('package') === 'paket') ? 'true' : 'false' }};
    const tanggalEl = document.getElementById('tanggal');
    const jamEl     = document.getElementById('jam');
    const durasiEl  = document.getElementById('durasi');

    const tanggal  = tanggalEl ? tanggalEl.value : '';
    const jam      = jamEl ? jamEl.value : '';
    const durasi   = isPackage ? 20 : (parseInt(durasiEl ? durasiEl.value : 2) || 2);

    // Add-On values (Capped: max 1 mic, max 1 headphone, max 2 camera, max 1 operator)
    const addonMicEl       = document.getElementById('addon_mic');
    const addonHeadphoneEl = document.getElementById('addon_headphone');
    const addonCameraEl    = document.getElementById('addon_camera');
    const addonOperatorEl  = document.getElementById('addon_operator');

    const micQty = addonMicEl ? Math.min(1, Math.max(0, parseInt(addonMicEl.value) || 0)) : 0;
    const hpQty  = addonHeadphoneEl ? Math.min(1, Math.max(0, parseInt(addonHeadphoneEl.value) || 0)) : 0;
    const camQty = addonCameraEl ? Math.min(2, Math.max(0, parseInt(addonCameraEl.value) || 0)) : 0;
    const opQty  = addonOperatorEl ? Math.min(1, Math.max(0, parseInt(addonOperatorEl.value) || 0)) : 0;

    const micCost       = micQty * 50000 * durasi;
    const hpCost        = hpQty * 50000 * durasi;
    const camCost       = camQty * 150000 * durasi;
    const opCost        = opQty * 100000 * durasi;
    const totalAddon    = micCost + hpCost + camCost + opCost;

    // Addon details UI in summary
    const rowAddon       = document.getElementById('rowAddonDetails');
    const addonItemsList = document.getElementById('addonItemsList');
    const sumAddonTotal  = document.getElementById('sumAddonTotal');

    if (totalAddon > 0 && !isPackage) {
        if (rowAddon) rowAddon.style.display = 'flex';
        if (sumAddonTotal) sumAddonTotal.textContent = formatRupiah(totalAddon);
        if (addonItemsList) {
            let itemsHtml = '';
            if (micQty > 0) itemsHtml += `<div>• Mikrofon: ${micQty} unit × ${durasi} jam = <strong>${formatRupiah(micCost)}</strong></div>`;
            if (hpQty > 0)  itemsHtml += `<div>• Headphone: ${hpQty} unit × ${durasi} jam = <strong>${formatRupiah(hpCost)}</strong></div>`;
            if (camQty > 0) itemsHtml += `<div>• Kamera: ${camQty} unit × ${durasi} jam = <strong>${formatRupiah(camCost)}</strong></div>`;
            if (opQty > 0)  itemsHtml += `<div>• Operator: 1 orang × ${durasi} jam = <strong>${formatRupiah(opCost)}</strong></div>`;
            addonItemsList.innerHTML = itemsHtml;
        }
    } else {
        if (rowAddon) rowAddon.style.display = 'none';
    }

    // Jam Selesai
    let jamSelesaiStr = '–';
    if (jam && !isPackage) {
        const [hh, mm] = jam.split(':').map(Number);
        const selesai  = new Date(0, 0, 0, hh + durasi, mm);
        jamSelesaiStr  = selesai.getHours().toString().padStart(2,'0') + ':' + selesai.getMinutes().toString().padStart(2,'0');
    }

    const jamSelesaiDisplay = document.getElementById('jam_selesai_display');
    if (jamSelesaiDisplay) {
        jamSelesaiDisplay.value = jamSelesaiStr !== '–' ? jamSelesaiStr : '';
    }

    // Tanggal formatted
    let tanggalFormatted = '–';
    if (isPackage) {
        tanggalFormatted = 'Fleksibel (Berlaku 1 Tahun)';
    } else if (tanggal) {
        const d = new Date(tanggal + 'T00:00:00');
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        tanggalFormatted = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    }

    // Populate common Summary rows
    if (document.getElementById('sumTanggal')) {
        document.getElementById('sumTanggal').textContent = tanggalFormatted;
    }
    if (document.getElementById('sumJamMulai')) {
        document.getElementById('sumJamMulai').textContent = isPackage ? 'Sesuai Reservasi Nanti' : (jam ? jam + ' WIB' : '–');
    }
    if (document.getElementById('sumDurasi')) {
        document.getElementById('sumDurasi').textContent = durasi === 20 ? '20 Jam (Paket 1 Tahun)' : durasi + ' Jam';
    }
    if (document.getElementById('sumJamSelesai')) {
        document.getElementById('sumJamSelesai').textContent = isPackage ? 'Masa Aktif 12 Bulan' : (jamSelesaiStr !== '–' ? jamSelesaiStr + ' WIB' : '–');
    }

    const useBenefitRadio = document.getElementById('useBenefitRadio');
    const payManualRadio  = document.getElementById('payManualRadio');
    const hasBenefitBox   = document.getElementById('benefitChoiceBox');
    const isUsingBenefit  = !isPackage && hasBenefitBox && (useBenefitRadio ? useBenefitRadio.checked : true) && (!payManualRadio || !payManualRadio.checked);

    const paymentSection    = document.getElementById('payment-section');
    const paymentProofInput = document.getElementById('payment_proof');
    const submitBtnText     = document.getElementById('submitBtnText');
    const infoAlertText     = document.getElementById('infoAlertText');
    const rowBiayaBenefit   = document.getElementById('rowBiayaBenefit');
    const rowHargaDasar     = document.getElementById('rowHargaDasar');
    const rowTambahan       = document.getElementById('rowTambahan');
    const priceNote         = document.getElementById('priceNote');

    if (isPackage) {
        // PACKAGE PURCHASE
        const subtotal = 5000000;
        const ppn      = Math.round(subtotal * 0.11);
        const total    = subtotal + ppn;

        if (rowBiayaBenefit) rowBiayaBenefit.style.display = 'none';
        if (rowHargaDasar) {
            rowHargaDasar.style.display = 'flex';
            document.getElementById('sumHargaDasar').textContent = 'Rp 5.000.000 (Paket 20 Jam / 1 Tahun)';
        }
        if (rowTambahan) rowTambahan.style.display = 'none';

        if (document.getElementById('sumSubtotal')) document.getElementById('sumSubtotal').textContent = formatRupiah(subtotal);
        if (document.getElementById('sumPpn')) document.getElementById('sumPpn').textContent = formatRupiah(ppn);
        if (document.getElementById('sumTotal')) document.getElementById('sumTotal').textContent = formatRupiah(total);

        if (document.getElementById('subtotalDisplay')) document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        if (document.getElementById('ppnDisplay')) document.getElementById('ppnDisplay').textContent = formatRupiah(ppn);
        if (document.getElementById('totalDisplay')) document.getElementById('totalDisplay').textContent = formatRupiah(total);

        if (paymentSection) paymentSection.style.display = 'block';
        if (paymentProofInput) paymentProofInput.required = true;
        if (submitBtnText) submitBtnText.textContent = 'Beli Paket Podcast Sekarang';
        return;
    }

    if (isUsingBenefit) {
        // BENEFIT MODE: Ruangan Rp 0 (Potong Kuota) + Hanya Bayar Add-On jika ada
        if (rowBiayaBenefit) rowBiayaBenefit.style.display = 'flex';
        if (rowHargaDasar) rowHargaDasar.style.display = 'none';
        if (rowTambahan) rowTambahan.style.display = 'none';
        if (priceNote) priceNote.style.display = 'none';

        const subtotal = totalAddon;
        const ppn      = Math.round(subtotal * 0.11);
        const total    = subtotal + ppn;

        if (document.getElementById('sumSubtotal')) document.getElementById('sumSubtotal').textContent = formatRupiah(subtotal);
        if (document.getElementById('sumPpn')) document.getElementById('sumPpn').textContent = formatRupiah(ppn);
        if (document.getElementById('sumTotal')) document.getElementById('sumTotal').textContent = formatRupiah(total);

        if (document.getElementById('subtotalDisplay')) document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        if (document.getElementById('ppnDisplay')) document.getElementById('ppnDisplay').textContent = formatRupiah(ppn);
        if (document.getElementById('totalDisplay')) document.getElementById('totalDisplay').textContent = formatRupiah(total);

        if (totalAddon > 0) {
            // Ada Add-on yang harus dibayar
            if (paymentSection) paymentSection.style.display = 'block';
            if (paymentProofInput) paymentProofInput.required = true;
            if (submitBtnText) submitBtnText.textContent = 'Kirim Reservasi & Bayar Add-On';
            if (infoAlertText) infoAlertText.textContent = `Ruangan gratis menggunakan kuota benefit Anda. Mohon transfer pembayaran Add-On sebesar ${formatRupiah(total)} (termasuk PPN 11%) dan upload bukti transfer.`;
        } else {
            // Bebas biaya 100%
            if (paymentSection) paymentSection.style.display = 'none';
            if (paymentProofInput) paymentProofInput.required = false;
            if (submitBtnText) submitBtnText.textContent = 'Kirim Reservasi Benefit (Gratis)';
            if (infoAlertText) infoAlertText.textContent = 'Reservasi ini menggunakan kuota benefit Anda dan akan langsung disetujui (bebas biaya tambahan).';
        }
    } else {
        // MANUAL / PAID MODE: Biaya Ruangan + Biaya Add-On
        if (rowBiayaBenefit) rowBiayaBenefit.style.display = 'none';
        if (rowHargaDasar) rowHargaDasar.style.display = 'flex';
        if (priceNote) priceNote.style.display = 'block';

        const baseRoomPrice = calcPodcastPrice(durasi);
        const subtotal      = baseRoomPrice + totalAddon;
        const ppn           = Math.round(subtotal * 0.11);
        const total         = subtotal + ppn;

        if (durasi === 1) {
            rowHargaDasar.querySelector('.label').textContent = '💰 Harga Sewa (1 Jam)';
            if (document.getElementById('sumHargaDasar')) document.getElementById('sumHargaDasar').textContent = formatRupiah(500000);
            if (rowTambahan) rowTambahan.style.display = 'none';
        } else if (durasi === 2) {
            rowHargaDasar.querySelector('.label').textContent = '💰 Harga Paket (2 Jam)';
            if (document.getElementById('sumHargaDasar')) document.getElementById('sumHargaDasar').textContent = formatRupiah(700000);
            if (rowTambahan) rowTambahan.style.display = 'none';
        } else {
            rowHargaDasar.querySelector('.label').textContent = '💰 Harga Paket (2 Jam Pertama)';
            if (document.getElementById('sumHargaDasar')) document.getElementById('sumHargaDasar').textContent = formatRupiah(700000);
            const extraJam   = durasi - 2;
            const extraCost  = extraJam * 300000;
            if (rowTambahan) {
                rowTambahan.style.display = 'flex';
                document.getElementById('sumTambahan').textContent = `${extraJam} jam × Rp 300.000 = ${formatRupiah(extraCost)}`;
            }
        }

        if (document.getElementById('sumSubtotal')) document.getElementById('sumSubtotal').textContent = formatRupiah(subtotal);
        if (document.getElementById('sumPpn')) document.getElementById('sumPpn').textContent = formatRupiah(ppn);
        if (document.getElementById('sumTotal')) document.getElementById('sumTotal').textContent = formatRupiah(total);

        if (document.getElementById('subtotalDisplay')) document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
        if (document.getElementById('ppnDisplay')) document.getElementById('ppnDisplay').textContent = formatRupiah(ppn);
        if (document.getElementById('totalDisplay')) document.getElementById('totalDisplay').textContent = formatRupiah(total);

        if (paymentSection) paymentSection.style.display = 'block';
        if (paymentProofInput) paymentProofInput.required = true;
        if (submitBtnText) submitBtnText.textContent = 'Pesan Ruang Podcast Sekarang';
        if (infoAlertText) infoAlertText.textContent = 'Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran disetujui.';
    }
}

function showFile(input) {
    const defaultState = document.getElementById('dropzone_default');
    const previewState = document.getElementById('dropzone_preview');
    const imgPreview   = document.getElementById('image_preview_img');
    const nameDisplay  = document.getElementById('file_name_display');
    const sizeDisplay  = document.getElementById('file_size_display');
    const box          = document.getElementById('dropzone_box');

    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        if (nameDisplay) nameDisplay.textContent = file.name;
        if (sizeDisplay) {
            const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
            sizeDisplay.textContent = `(${sizeInMB} MB)`;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (imgPreview) imgPreview.src = e.target.result;
            if (defaultState) defaultState.style.display = 'none';
            if (previewState) previewState.style.display = 'block';
            if (box) {
                box.style.border = '2px solid #22c55e';
                box.style.background = '#f0fdf4';
            }
        };
        reader.readAsDataURL(file);
    }
}

function togglePaymentProof() {
    updateSummary();
}

function onBenefitChoiceChange() {
    const payManually = document.getElementById('payManualRadio');
    const payManInput = document.getElementById('payManuallyInput');

    if (payManInput) {
        payManInput.value = (payManually && payManually.checked) ? '1' : '0';
    }

    updateSummary();
}

function sendWhatsApp() {
    const isPackage = {{ (($package ?? '') === 'paket' || request('package') === 'paket') ? 'true' : 'false' }};
    const nama = document.getElementById('nama') ? document.getElementById('nama').value : '';
    const podcast_title = document.getElementById('podcast_title') ? document.getElementById('podcast_title').value : '';
    const tanggal = document.getElementById('tanggal') ? document.getElementById('tanggal').value : '';
    const jam = document.getElementById('jam') ? document.getElementById('jam').value : '';
    const durasi = isPackage ? '20' : (document.getElementById('durasi') ? document.getElementById('durasi').value : '2');

    if (!nama) {
        alert('Mohon lengkapi nama Anda terlebih dahulu.');
        return;
    }

    // Add-on info
    const addonMicEl       = document.getElementById('addon_mic');
    const addonHeadphoneEl = document.getElementById('addon_headphone');
    const addonCameraEl    = document.getElementById('addon_camera');
    const addonOperatorEl  = document.getElementById('addon_operator');

    const micQty = addonMicEl ? (parseInt(addonMicEl.value) || 0) : 0;
    const hpQty  = addonHeadphoneEl ? (parseInt(addonHeadphoneEl.value) || 0) : 0;
    const camQty = addonCameraEl ? (parseInt(addonCameraEl.value) || 0) : 0;
    const opQty  = addonOperatorEl ? (parseInt(addonOperatorEl.value) || 0) : 0;

    let addonDetails = [];
    if (micQty > 0) addonDetails.push(`Mikrofon (${micQty} Unit)`);
    if (hpQty > 0)  addonDetails.push(`Headphone (${hpQty} Unit)`);
    if (camQty > 0) addonDetails.push(`Kamera (${camQty} Unit)`);
    if (opQty > 0)  addonDetails.push(`Operator Podcast`);
    const addonText = addonDetails.length > 0 ? `\n- Add-On: ${addonDetails.join(', ')}` : '';

    const totalEl = document.getElementById('sumTotal');
    const totalText = totalEl ? totalEl.textContent : '–';

    let text = '';
    if (isPackage) {
        text = `Halo Admin Lawgika, saya ingin konfirmasi pemesanan Paket Podcast Room:

- Nama: ${nama || '-'}
- Jenis Paket: Paket Podcast Studio (20 Jam / 1 Tahun)
- Judul Podcast: ${podcast_title || '-'}
- Total Tagihan: Rp 5.550.000 (Termasuk PPN 11%)

Mohon info konfirmasi pembayaran. Terima kasih.`;
    } else {
        if (!tanggal || !jam) {
            alert('Mohon lengkapi tanggal dan jam booking terlebih dahulu.');
            return;
        }

        const useBenefitRadio = document.getElementById('useBenefitRadio');
        const hasBenefitBox   = document.getElementById('benefitChoiceBox');
        const isUsingBenefit  = hasBenefitBox && (useBenefitRadio ? useBenefitRadio.checked : true);

        text = `Halo Admin Lawgika, saya ingin memverifikasi pemesanan Sewa Ruang Podcast:

- Nama: ${nama || '-'}
- Judul Podcast: ${podcast_title || '-'}
- Tanggal: ${tanggal}
- Jam Mulai: ${jam} WIB
- Durasi: ${durasi} Jam
- Tipe: ${isUsingBenefit ? 'Gunakan Benefit Gratis' : 'Bayar Mandiri'}${addonText}
- Total Tagihan: ${totalText}

Mohon konfirmasi pembayaran & reservasi ini. Terima kasih.`;
    }

    const phone = '6281112088600';
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

function initAddonStates() {
    ['addon_mic', 'addon_headphone', 'addon_operator'].forEach(id => {
        const input = document.getElementById(id);
        const btnKey = id.replace('addon_', '');
        const row = document.getElementById('row_' + btnKey);
        const btn = document.getElementById('btn_toggle_' + btnKey);
        if (input && parseInt(input.value) === 1) {
            if (row) row.classList.add('active');
            if (btn) btn.innerHTML = '<i class="fa-solid fa-check icon-state"></i> <span class="state-text">Terpilih</span>';
        }
    });

    const camInput = document.getElementById('addon_camera');
    const camRow = document.getElementById('row_camera');
    const camMinus = document.getElementById('btn_cam_minus');
    const camPlus = document.getElementById('btn_cam_plus');
    if (camInput) {
        const val = parseInt(camInput.value) || 0;
        if (val > 0 && camRow) camRow.classList.add('active');
        if (camMinus) camMinus.disabled = (val <= 0);
        if (camPlus) camPlus.disabled = (val >= 2);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    initAddonStates();
    updateSummary();
    onBenefitChoiceChange();
    togglePaymentProof();

    // Min date = today (jika ada input tanggal date)
    const tglInput = document.getElementById('tanggal');
    if (tglInput && tglInput.type === 'date') {
        tglInput.min = new Date().toISOString().split('T')[0];
    }
});

// ===== Syarat & Ketentuan Modal Handlers =====
function openPodcastTermsModal() {
    const overlay = document.getElementById('podcastTermsModalOverlay');
    if (!overlay) return;
    overlay.style.display = 'flex';
    void overlay.offsetWidth;
    overlay.classList.add('active');
}

function closePodcastTermsModal() {
    const overlay = document.getElementById('podcastTermsModalOverlay');
    if (!overlay) return;
    overlay.classList.remove('active');
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 300);
}
</script>
@endsection
