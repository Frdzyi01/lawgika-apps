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

@media(max-width:768px){.order-container{margin:30px 15px;padding:28px 18px;}}
</style>

<div class="container pt-5 pb-5" style="margin-top:80px!important;">
<div class="order-container">
    <div class="order-header">
        <div style="font-size:2.8rem;margin-bottom:10px;">🎙️</div>
        <h2 data-i18n="pr.reserv_title">Reservasi Ruang Podcast</h2>
        <p data-i18n="pr.reserv_subtitle">Lengkapi form di bawah, upload bukti transfer, lalu klik Pesan.</p>
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
                    <label for="jam"><span data-i18n="pr.label_start_time">Jam Mulai</span> <span class="text-danger">*</span></label>
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
                            value="{{ old('durasi', $durasi ?? 2) }}" readonly>
                        <button type="button" onclick="updateDurasi(1)">+</button>
                    </div>
                    <input type="hidden" id="durasi" name="durasi" value="{{ old('durasi', $durasi ?? 2) }}">
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

        {{-- ===== RINGKASAN BOOKING ===== --}}
        <div class="booking-summary" id="bookingSummary">
            <h5><i class="fa-solid fa-receipt" style="color:var(--primary);"></i> <span data-i18n="pr.summary_title">Ringkasan Booking</span></h5>

            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_date">📅 Tanggal</span>
                <span class="value" id="sumTanggal">–</span>
            </div>
            <div class="summary-row">
                <span class="label" data-i18n="pr.sum_start_time">⏰ Jam Mulai</span>
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

            <div class="summary-row" id="rowHargaDasar">
                <span class="label" data-i18n="pr.sum_base_price">💰 Harga Paket (1–2 Jam)</span>
                <span class="value" id="sumHargaDasar">–</span>
            </div>
            <div class="summary-row" id="rowTambahan" style="display:none;">
                <span class="label" data-i18n="pr.sum_add_hours">➕ Tambahan Jam</span>
                <span class="value" id="sumTambahan">–</span>
            </div>

            <div class="summary-total">
                <span class="label" data-i18n="pr.sum_total">💳 Total Pembayaran</span>
                <span class="value" id="sumTotal">Rp –</span>
            </div>
        </div>

        {{-- ===== PRICE NOTES ===== --}}
        <div class="price-note">
            <p>📌 <strong data-i18n="pr.price_rules_title">Aturan Harga:</strong> <span data-i18n="pr.price_rules_desc_part1">Durasi 2 jam pertama menggunakan harga paket</span> <strong>Rp 800.000</strong>. <span data-i18n="pr.price_rules_desc_part2">Setelah melewati 2 jam, dikenakan tambahan</span> <strong>Rp 300.000 per jam</strong>.</p>
            <p>⏰ <strong data-i18n="pr.prep_time_hint">Harap datang 15 menit sebelum jadwal dimulai</strong> <span data-i18n="pr.prep_time_hint_part2">untuk persiapan dan pengecekan peralatan.</span></p>
        </div>

        @if(isset($quota) && !now()->greaterThan($quota->expired_at) && $quota->remaining_seconds > 0)
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

        {{-- Benefit Choice (jika user punya active benefit dari paket PT) --}}
        @if(isset($activeBenefit) && $activeBenefit)
            <div id="benefitChoiceBox" style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:20px; margin-bottom:20px;">
                <h5 style="color:#15803d; font-weight:700; margin-bottom:8px;"><i class="fa-solid fa-gift"></i> <span data-i18n="pr.benefit_box_title">Anda Memiliki Benefit Paket</span></h5>
                <p style="color:#166534; margin-bottom:14px; font-size:.9rem;">
                    Benefit: <strong>{{ $activeBenefit->paket }}</strong> — <span data-i18n="pr.remaining">Sisa:</span> <strong>{{ \App\Models\RoomBenefit::formatMinutes($activeBenefit->remaining_minutes) }}</strong>
                    (Berlaku hingga {{ $activeBenefit->expired_at ? $activeBenefit->expired_at->format('d M Y') : __('mr.no_expired') }})
                </p>
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <label style="cursor:pointer; padding:10px 18px; border:2px solid #16a34a; border-radius:8px; font-weight:600; font-size:.88rem; display:flex; align-items:center; gap:6px;">
                        <input type="radio" name="benefit_choice" value="use_benefit" id="useBenefitRadio"
                            {{ old('benefit_choice','use_benefit') === 'use_benefit' ? 'checked' : '' }}
                            onchange="onBenefitChoiceChange()" style="margin:0;">
                        <span style="color:#15803d;" data-i18n="pr.use_benefit_free">✅ Gunakan Benefit Gratis</span>
                    </label>
                    <label style="cursor:pointer; padding:10px 18px; border:2px solid #94a3b8; border-radius:8px; font-weight:600; font-size:.88rem; display:flex; align-items:center; gap:6px;">
                        <input type="radio" name="benefit_choice" value="pay_manual" id="payManualRadio"
                            {{ old('benefit_choice') === 'pay_manual' ? 'checked' : '' }}
                            onchange="onBenefitChoiceChange()" style="margin:0;">
                        <span style="color:#374151;" data-i18n="pr.pay_manual_label"> Bayar Mandiri (Upload Bukti Transfer)</span>
                    </label>
                </div>
            </div>
            <input type="hidden" name="pay_manually" id="payManuallyInput" value="{{ old('benefit_choice') === 'pay_manual' ? '1' : '0' }}">
        @endif

        {{-- Payment Info --}}
        <div id="payment-section">
            <div class="bank-box">
                <h5><i class="fa-solid fa-building-columns me-1"></i> <span data-i18n="order.payment_instruction_transfer">Instruksi Pembayaran (Transfer Bank)</span></h5>
                <p style="font-size:.88rem;color:var(--gray);margin-bottom:12px;" data-i18n="order.transfer_instruction">Silakan lakukan pembayaran ke rekening berikut:</p>
                <div class="bank-row"><span style="color:#64748b;">Bank</span><strong>BCA (Bank Central Asia)</strong></div>
                <div class="bank-row"><span style="color:#64748b;">No. Rekening</span><strong style="letter-spacing:1px;font-size:1.05rem;">869 123 4567</strong></div>
                <div class="bank-row last"><span style="color:#64748b;">Atas Nama</span><strong>PT Lawgika Associates</strong></div>
                <div style="margin-top:14px;text-align:right;">
                    <span style="color:var(--gray);font-size:.9rem;margin-right:8px;" data-i18n="order.total_bill">Total Tagihan:</span>
                    <strong style="color:var(--primary);font-size:1.4rem;" id="totalDisplay">Rp –</strong>
                </div>
            </div>

            <div class="form-group">
                <label for="payment_proof_click"><span data-i18n="order.upload_payment_proof">Upload Bukti Pembayaran</span> <span class="text-danger">*</span></label>
                <div style="border:2px dashed #e2e8f0;border-radius:10px;padding:20px;text-align:center;cursor:pointer;"
                    onclick="document.getElementById('payment_proof').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--primary);display:block;margin-bottom:8px;"></i>
                    <p style="color:var(--gray);margin:0;font-size:.88rem;" data-i18n="pr.upload_proof_click">Klik untuk upload bukti transfer</p>
                    <p style="color:#94a3b8;margin:4px 0 0;font-size:.78rem;" data-i18n="order.upload_proof_hint_2mb">JPG, PNG, JPEG — Maks. 2MB</p>
                    <p id="fileName" style="color:var(--primary);font-weight:600;margin:8px 0 0;font-size:.88rem;display:none;"></p>
                </div>
            </div>
            <input type="file" id="payment_proof" name="payment_proof"
                accept="image/jpg,image/jpeg,image/png" required style="display:none;" onchange="showFile(this)">
        </div>

        <div style="background:#fef9c3;border:1px solid #fde047;border-radius:10px;padding:13px;font-size:.88rem;color:#713f12;margin-bottom:18px;">
            <strong data-i18n="order.info_label">⚡ Info:</strong> <span data-i18n="mr.info_desc_part1">Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran</span> <strong><span data-i18n="mr.info_desc_part2">disetujui</span></strong>.
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-calendar-check me-1"></i> <span data-i18n="pr.submit_btn">Pesan Ruang Podcast Sekarang</span>
        </button>
        <div style="text-align:center;margin-top:14px;">
            <a href="{{ url('/sewa-ruang-podcast') }}" style="color:var(--gray);font-size:.88rem;text-decoration:none;">
                <i class="fa-solid fa-arrow-left me-1"></i> <span data-i18n="pr.back_to_slots">Kembali ke Pilih Slot</span>
            </a>
        </div>
    </form>
</div>
</div>

<script>
// ===== Podcast Pricing Formula =====
function calcPodcastPrice(jam) {
    jam = parseInt(jam) || 0;
    if (jam <= 0) return 0;
    if (jam === 1) return 500000;
    if (jam === 2) return 800000;
    return 800000 + ((jam - 2) * 300000);
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
    if (val > 12) val = 12;
    h.value = val;
    d.value = val;
    updateSummary();
}

// ===== Update Ringkasan & Harga =====
function updateSummary() {
    const tanggal  = document.getElementById('tanggal').value;
    const jam      = document.getElementById('jam').value;
    const durasi   = parseInt(document.getElementById('durasi').value) || 2;
    const total    = calcPodcastPrice(durasi);

    // Jam Selesai
    let jamSelesaiStr = '–';
    if (jam) {
        const [hh, mm] = jam.split(':').map(Number);
        const selesai  = new Date(0, 0, 0, hh + durasi, mm);
        jamSelesaiStr  = selesai.getHours().toString().padStart(2,'0') + ':' + selesai.getMinutes().toString().padStart(2,'0');
    }

    // Update display jam selesai
    document.getElementById('jam_selesai_display').value = jamSelesaiStr !== '–' ? jamSelesaiStr : '';

    // Tanggal formatted
    let tanggalFormatted = '–';
    if (tanggal) {
        const d = new Date(tanggal + 'T00:00:00');
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        tanggalFormatted = d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    }

    // Populate Summary
    document.getElementById('sumTanggal').textContent   = tanggalFormatted;
    document.getElementById('sumJamMulai').textContent  = jam ? jam + ' WIB' : '–';
    document.getElementById('sumDurasi').textContent    = durasi + ' Jam';
    document.getElementById('sumJamSelesai').textContent = jamSelesaiStr !== '–' ? jamSelesaiStr + ' WIB' : '–';
    document.getElementById('sumTotal').textContent     = formatRupiah(total);

    // Harga Dasar
    const hargaDasar = durasi >= 2 ? 800000 : 500000;
    document.getElementById('sumHargaDasar').textContent = formatRupiah(hargaDasar);

    // Tambahan Jam
    const rowTambahan   = document.getElementById('rowTambahan');
    const sumTambahan   = document.getElementById('sumTambahan');
    if (durasi > 2) {
        const tambahanJam   = durasi - 2;
        const tambahanHarga = tambahanJam * 300000;
        sumTambahan.textContent = tambahanJam + ' jam × Rp 300.000 = ' + formatRupiah(tambahanHarga);
        rowTambahan.style.display = 'flex';
    } else {
        rowTambahan.style.display = 'none';
    }

    // Update total tagihan di bank box
    document.getElementById('totalDisplay').textContent = formatRupiah(total);
}

function showFile(input) {
    const lbl = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        lbl.textContent = '✅ ' + input.files[0].name;
        lbl.style.display = 'block';
    }
}

function togglePaymentProof() {
    const useQuota          = document.getElementById('use_quota');
    const payManualRadio    = document.getElementById('payManualRadio');
    const paymentSection    = document.getElementById('payment-section');
    const paymentProofInput = document.getElementById('payment_proof');

    // Jika user memilih bayar mandiri (meski punya benefit)
    if (payManualRadio && payManualRadio.checked) {
        paymentSection.style.display = 'block';
        paymentProofInput.required   = true;
        return;
    }
    // Jika menggunakan quota lama
    if (useQuota && useQuota.checked) {
        paymentSection.style.display = 'none';
        paymentProofInput.required   = false;
    } else {
        paymentSection.style.display = 'block';
        paymentProofInput.required   = true;
    }
}

function onBenefitChoiceChange() {
    const useBenefit     = document.getElementById('useBenefitRadio');
    const payManually    = document.getElementById('payManualRadio');
    const payManInput    = document.getElementById('payManuallyInput');
    const paySection     = document.getElementById('payment-section');
    const payProofInput  = document.getElementById('payment_proof');

    if (payManually && payManually.checked) {
        // Pilih bayar mandiri
        if (payManInput) payManInput.value = '1';
        paySection.style.display  = 'block';
        payProofInput.required    = true;
    } else {
        // Pilih pakai benefit gratis
        if (payManInput) payManInput.value = '0';
        paySection.style.display  = 'none';
        payProofInput.required    = false;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateSummary();
    onBenefitChoiceChange();
    togglePaymentProof();

    // Min date = today
    document.getElementById('tanggal').min = new Date().toISOString().split('T')[0];
});
</script>
@endsection
