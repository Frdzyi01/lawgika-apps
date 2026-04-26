@extends('layout.app')

@section('content')

<style>
    /* ===== MINIMAL CSS ===== */
    :root {
        --primary: #4e0516;
        --primary-light: #7a0a23;
        --accent: #c9a03d;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg-light: #fdf8f5;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--dark);
        background: var(--bg-light);
    }

    .order-container {
        max-width: 600px;
        margin: 60px auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
        border: 1px solid #f0e4e8;
        padding: 40px;
    }

    .order-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .order-header h2 {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 10px;
    }

    .order-header p {
        color: var(--gray);
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        color: #334155;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(78, 5, 22, 0.1);
    }

    .btn-submit {
        display: block;
        width: 100%;
        padding: 14px;
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease;
        text-align: center;
        margin-top: 30px;
    }

    .btn-submit:hover {
        background: var(--primary-light);
    }

    .btn-submit i {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .order-container {
            margin: 30px 15px;
            padding: 30px 20px;
        }
    }
</style>

<div class="container pt-5 pb-5 mt-5" style="margin-top: 80px !important">
    <div class="order-container">
        <div class="order-header">
            <h2>Reservasi Meeting Room</h2>
            <p>Untuk melakukan reservasi, silakan isi informasi berikut:</p>
        </div>

        @if($errors->any())
            <div style="padding:15px;border-radius:10px;margin-bottom:20px;background:#fee2e2;color:#991b1b;border:1px solid #f87171;">
                <ul class="mb-0" style="padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('meeting-room.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" class="form-control" required placeholder="Masukkan nama Anda" value="{{ old('nama') }}">
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Penggunaan</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ old('tanggal', $tanggal ?? '') }}" readonly>
            </div>

            <div class="form-group">
                <label for="jam">Jam Penggunaan</label>
                <input type="time" id="jam" name="jam" class="form-control" required value="{{ old('jam', $jam ?? '') }}" readonly>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="peserta">Jumlah Peserta</label>
                        <input type="number" id="peserta" name="peserta" class="form-control" min="1" required placeholder="Contoh: 8" value="{{ old('peserta') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="durasi">Durasi Sewa (Jam)</label>
                        <!-- Defaulting to what we got from query parameter or 1 -->
                        <input type="number" id="durasi" name="durasi" class="form-control" min="1" required placeholder="Contoh: 2" value="{{ old('durasi', $durasi ?? 1) }}" onchange="updateTotal()">
                    </div>
                </div>
            </div>

            @if(isset($quota) && !now()->greaterThan($quota->expired_at) && $quota->remaining_seconds > 0)
                <div style="background:#fdf2f8; border:1px solid #fbcfe8; border-radius:10px; padding:20px; margin-bottom:20px;">
                    <h5 style="color:#be185d; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-gem"></i> Anda Memiliki Quota Ruangan!</h5>
                    <p style="margin-bottom:15px; color:#831843;">Sisa quota Anda: <strong>{{ $quota->formatted_remaining_time }}</strong> (Berlaku hingga {{ \Carbon\Carbon::parse($quota->expired_at)->format('d M Y') }})</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="use_quota" id="use_quota" value="1" checked onchange="togglePaymentProof()">
                        <label class="form-check-label fw-bold text-dark" style="margin-bottom:0;" for="use_quota">
                            Gunakan Quota untuk Reservasi ini (Bebas Biaya)
                        </label>
                    </div>
                </div>
            @endif

            <!-- Manual Transfer Instructions -->
            <div id="payment-section">
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:20px;">
                    <h5 style="font-size:1.05rem; font-weight:700; color:var(--dark); margin-bottom:15px;"><i class="fa-solid fa-building-columns"></i> Instruksi Pembayaran (Transfer Bank)</h5>
                <p style="font-size:0.95rem; color:var(--gray); margin-bottom:10px;">Silakan lakukan pembayaran ke rekening berikut:</p>
                <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e2e8f0;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Bank</span>
                        <strong style="color:#1e1b2b;">BCA (Bank Central Asia)</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">No. Rekening</span>
                        <strong style="color:#1e1b2b; font-size:1.1rem; letter-spacing:1px;">869 123 4567</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:8px;">
                        <span style="color:#64748b; font-size:0.9rem;">Atas Nama</span>
                        <strong style="color:#1e1b2b;">PT Lawgika Associates</strong>
                    </div>
                </div>
                
                <div style="margin-top:15px; text-align:right;">
                    <span style="color:#64748b; font-size:0.95rem; margin-right:10px;">Total Tagihan:</span>
                    <strong style="color:var(--primary); font-size:1.4rem;" id="totalAmountDisplay">Rp 150.000</strong>
                    <input type="hidden" id="package" value="{{ $package ?? 'reservasi' }}">
                </div>
            </div>

            <div class="form-group">
                <label for="payment_proof">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                <div style="border:2px dashed #e2e8f0; border-radius:10px; padding:20px; text-align:center; cursor:pointer;" onclick="document.getElementById('payment_proof').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem; color:var(--primary); margin-bottom:8px; display:block;"></i>
                    <p style="color:var(--gray); margin:0; font-size:0.9rem;">Klik untuk upload bukti transfer / pembayaran</p>
                    <p style="color:#94a3b8; margin:4px 0 0; font-size:0.8rem;">JPG, PNG, JPEG — Maks. 2MB</p>
                    <p id="file-name" style="color:var(--primary); font-weight:600; margin:8px 0 0; font-size:0.9rem; display:none;"></p>
                </div>
                <input type="file" id="payment_proof" name="payment_proof" accept="image/jpg,image/jpeg,image/png" required style="display:none;" onchange="showFileName(this)">
                </div>
            </div>

            <div style="background:#fef9c3; border:1px solid #fde047; border-radius:10px; padding:14px; margin-bottom:20px; font-size:0.9rem; color:#713f12;">
                <strong>⚡ Info:</strong> Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran <strong>disetujui</strong>.
            </div>

            <div style="display:flex; gap:15px;">
                <button type="submit" class="btn-submit" style="flex:1; margin-top:0;">
                    <i class="fa-solid fa-calendar-check"></i> Pesan Sekarang
                </button>
                <button type="button" class="btn-submit" onclick="sendWhatsApp()" style="flex:1; margin-top:0; background:#25D366; color:#fff; border:none;">
                    <i class="fa-brands fa-whatsapp"></i> Hubungi WhatsApp
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showFileName(input) {
    const label = document.getElementById('file-name');
    if (input.files && input.files[0]) {
        label.textContent = '✅ ' + input.files[0].name;
        label.style.display = 'block';
    }
}

const hargaPerJam = 150000;

function updateTotal() {
    const packageType = document.getElementById('package').value;
    const durasi = parseInt(document.getElementById('durasi').value) || 1;
    let total = durasi * hargaPerJam;
    
    // Jika paket badan usaha (reservasi), harganya bisa disesuaikan atau dianggap gratis jika menggunakan kuota.
    // Asumsi: di halaman reservasi, harga default kita tampilkan berdasarkan durasi. 
    // Untuk paket yang sudah dibeli kuotanya, admin yang akan verify.
    // Di sini kita tampilkan estimasi normal jika belum punya paket, atau kita beri note.
    
    document.getElementById('totalAmountDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
}

function sendWhatsApp() {
    const nama = document.getElementById('nama').value;
    const tanggal = document.getElementById('tanggal').value;
    const jam = document.getElementById('jam').value;
    const durasi = document.getElementById('durasi').value;
    const peserta = document.getElementById('peserta').value;
    const packageType = document.getElementById('package').value;
    
    if(!tanggal || !jam || !durasi) {
        alert('Mohon lengkapi tanggal, jam, dan durasi terlebih dahulu.');
        return;
    }
    
    const text = `Halo Admin Lawgika, saya ingin memverifikasi pemesanan Meeting Room:
    
- Nama: ${nama || '-'}
- Tanggal: ${tanggal}
- Jam: ${jam}
- Durasi: ${durasi} Jam
- Peserta: ${peserta} Orang
- Tipe Pemesanan: ${packageType === 'paket' ? 'Beli Paket 60 Jam' : 'Reservasi Reguler/Gunakan Kuota'}

Mohon konfirmasinya. Terima kasih.`;

    const phone = '628111234567'; // Ganti dengan nomor WA admin yang sebenarnya
    const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

function togglePaymentProof() {
    const useQuota = document.getElementById('use_quota');
    const paymentSection = document.getElementById('payment-section');
    const paymentProofInput = document.getElementById('payment_proof');
    if (useQuota && useQuota.checked) {
        paymentSection.style.display = 'none';
        paymentProofInput.required = false;
    } else {
        paymentSection.style.display = 'block';
        paymentProofInput.required = true;
    }
}

// Inisialisasi total saat load
document.addEventListener('DOMContentLoaded', () => {
    updateTotal();
    togglePaymentProof();
});
</script>
    </div>
</div>


@endsection