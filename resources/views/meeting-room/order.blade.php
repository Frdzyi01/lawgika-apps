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
                <input type="date" id="tanggal" name="tanggal" class="form-control" required value="{{ old('tanggal') }}">
            </div>

            <div class="form-group">
                <label for="jam">Jam Penggunaan</label>
                <input type="time" id="jam" name="jam" class="form-control" required value="{{ old('jam') }}">
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
                        <input type="number" id="durasi" name="durasi" class="form-control" min="1" required placeholder="Contoh: 2" value="{{ old('durasi') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="payment_proof">Bukti Pembayaran</label>
                <div style="border:2px dashed #e2e8f0; border-radius:10px; padding:20px; text-align:center; cursor:pointer;" onclick="document.getElementById('payment_proof').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:2rem; color:var(--primary); margin-bottom:8px; display:block;"></i>
                    <p style="color:var(--gray); margin:0; font-size:0.9rem;">Klik untuk upload bukti transfer / pembayaran</p>
                    <p style="color:#94a3b8; margin:4px 0 0; font-size:0.8rem;">JPG, PNG, JPEG — Maks. 2MB</p>
                    <p id="file-name" style="color:var(--primary); font-weight:600; margin:8px 0 0; font-size:0.9rem; display:none;"></p>
                </div>
                <input type="file" id="payment_proof" name="payment_proof" accept="image/jpg,image/jpeg,image/png" required style="display:none;" onchange="showFileName(this)">
            </div>

            <div style="background:#fef9c3; border:1px solid #fde047; border-radius:10px; padding:14px; margin-bottom:20px; font-size:0.9rem; color:#713f12;">
                <strong>⚡ Info:</strong> Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran <strong>disetujui</strong>.
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-calendar-check"></i> Pesan Sekarang
            </button>
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
</script>
    </div>
</div>


@endsection