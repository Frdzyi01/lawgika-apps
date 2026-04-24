@extends('layout.app')
@section('content')
<style>
:root{--primary:#4e0516;--primary-l:#7a0a23;--accent:#c9a03d;--dark:#1e1b2b;--gray:#64748b;--bg:#fdf8f5;}
body{font-family:'Inter',-apple-system,sans-serif;background:var(--bg);}
.order-container{max-width:620px;margin:60px auto;background:#fff;border-radius:20px;box-shadow:0 10px 40px rgba(0,0,0,.05);border:1px solid #f0e4e8;padding:40px;}
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
.bank-row:last-child{margin-bottom:0;border-top:1px dashed #e2e8f0;padding-top:8px;margin-top:8px;}
@media(max-width:768px){.order-container{margin:30px 15px;padding:28px 18px;}}
</style>

<div class="container pt-5 pb-5" style="margin-top:80px!important;">
<div class="order-container">
    <div class="order-header">
        <div style="font-size:2.8rem;margin-bottom:10px;">🎙️</div>
        <h2>Reservasi Ruang Podcast</h2>
        <p>Lengkapi form di bawah, upload bukti transfer, lalu klik Pesan.</p>
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
            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="nama" name="nama" class="form-control" required placeholder="Masukkan nama Anda" value="{{ old('nama', auth()->user()->name ?? '') }}">
        </div>

        <div class="form-group">
            <label for="podcast_title">Judul / Nama Podcast <span style="font-size:.8rem;color:var(--gray);">(opsional)</span></label>
            <input type="text" id="podcast_title" name="podcast_title" class="form-control" placeholder="Misal: The Business Talk" value="{{ old('podcast_title') }}">
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="tanggal">Tanggal Penggunaan <span class="text-danger">*</span></label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ old('tanggal', $tanggal ?? '') }}" readonly>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="jam">Jam Mulai <span class="text-danger">*</span></label>
                    <input type="time" id="jam" name="jam" class="form-control" required value="{{ old('jam', $jam ?? '') }}" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="durasi_display">Durasi (Jam) <span class="text-danger">*</span></label>
                    <div class="d-flex align-items-center" style="border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden;">
                        <button type="button" onclick="updateDurasi(-2)" style="background:#f8fafc;border:none;padding:12px 20px;color:var(--dark);font-weight:bold;cursor:pointer;border-right:1px solid #e2e8f0;">-</button>
                        <input type="text" id="durasi_display" class="form-control" style="border:none;border-radius:0;text-align:center;font-weight:700;pointer-events:none;background:#fff;" value="{{ old('durasi', $durasi ?? 2) }}" readonly>
                        <button type="button" onclick="updateDurasi(2)" style="background:#f8fafc;border:none;padding:12px 20px;color:var(--dark);font-weight:bold;cursor:pointer;border-left:1px solid #e2e8f0;">+</button>
                    </div>
                    <input type="hidden" id="durasi" name="durasi" value="{{ old('durasi', $durasi ?? 2) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="peserta">Jumlah Peserta <span class="text-danger">*</span></label>
                    <input type="number" id="peserta" name="peserta" class="form-control" min="1" max="4" required placeholder="Maks. 4 orang" value="{{ old('peserta') }}">
                </div>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="bank-box">
            <h5><i class="fa-solid fa-building-columns me-1"></i> Instruksi Pembayaran (Transfer Bank)</h5>
            <p style="font-size:.88rem;color:var(--gray);margin-bottom:12px;">Silakan lakukan pembayaran ke rekening berikut:</p>
            <div class="bank-row"><span style="color:#64748b;">Bank</span><strong>BCA (Bank Central Asia)</strong></div>
            <div class="bank-row"><span style="color:#64748b;">No. Rekening</span><strong style="letter-spacing:1px;font-size:1.05rem;">869 123 4567</strong></div>
            <div class="bank-row"><span style="color:#64748b;">Atas Nama</span><strong>PT Lawgika Associates</strong></div>
            <div style="margin-top:14px;text-align:right;">
                <span style="color:var(--gray);font-size:.9rem;margin-right:8px;">Total Tagihan:</span>
                <strong style="color:var(--primary);font-size:1.4rem;" id="totalDisplay">Rp –</strong>
            </div>
        </div>

        <div class="form-group">
            <label for="payment_proof_click">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
            <div style="border:2px dashed #e2e8f0;border-radius:10px;padding:20px;text-align:center;cursor:pointer;" onclick="document.getElementById('payment_proof').click()">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem;color:var(--primary);display:block;margin-bottom:8px;"></i>
                <p style="color:var(--gray);margin:0;font-size:.88rem;">Klik untuk upload bukti transfer</p>
                <p style="color:#94a3b8;margin:4px 0 0;font-size:.78rem;">JPG, PNG, JPEG — Maks. 2MB</p>
                <p id="fileName" style="color:var(--primary);font-weight:600;margin:8px 0 0;font-size:.88rem;display:none;"></p>
            </div>
            <input type="file" id="payment_proof" name="payment_proof" accept="image/jpg,image/jpeg,image/png" required style="display:none;" onchange="showFile(this)">
        </div>

        <div style="background:#fef9c3;border:1px solid #fde047;border-radius:10px;padding:13px;font-size:.88rem;color:#713f12;margin-bottom:18px;">
            <strong>⚡ Info:</strong> Admin akan konfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran <strong>disetujui</strong>.
        </div>

        <button type="submit" class="btn-submit">
            <i class="fa-solid fa-calendar-check me-1"></i> Pesan Ruang Podcast Sekarang
        </button>
        <div style="text-align:center;margin-top:14px;">
            <a href="{{ url('/sewa-ruang-podcast') }}" style="color:var(--gray);font-size:.88rem;text-decoration:none;">
                <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Pilih Slot
            </a>
        </div>
    </form>
</div>
</div>

<script>
function updateDurasi(step){
    const h = document.getElementById('durasi');
    const d = document.getElementById('durasi_display');
    let val = parseInt(h.value) || 2;
    val += step;
    if(val < 2) val = 2; // Batas minimum 2 jam
    h.value = val;
    d.value = val;
    hitungTotal();
}
function hitungTotal(){
    const d = parseInt(document.getElementById('durasi').value) || 2;
    const total = (d / 2) * 500000;
    document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
function showFile(input){
    const lbl = document.getElementById('fileName');
    if(input.files&&input.files[0]){lbl.textContent='✅ '+input.files[0].name;lbl.style.display='block';}
}
document.addEventListener('DOMContentLoaded', hitungTotal);
</script>
@endsection
