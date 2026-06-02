@extends('layout.app')
@section('content')
<style>
  :root {
    --m: #800000;
    --md: #4a0000;
    --bg: #f8f9fb;
    --text: #1e293b;
    --muted: #64748b;
    --green: #10b981;
  }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
  }

  /* Hero strip */
  .jpp-strip {
    background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%);
    padding: 160px 0 60px;
  }

  .bc a {
    color: rgba(255, 255, 255, .65);
    text-decoration: none;
    font-size: .82rem;
  }

  .bc a:hover {
    color: #fff;
  }

  .bc span {
    color: rgba(255, 255, 255, .3);
    margin: 0 6px;
  }

  .bc .cur {
    color: #fff;
    font-size: .82rem;
  }

  /* Summary card */
  .summary-pill {
    background: rgba(255, 255, 255, .08);
    border: 1px solid rgba(255, 255, 255, .15);
    border-radius: 16px;
    padding: 20px 28px;
    display: inline-flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 24px;
  }

  .sp-label {
    color: rgba(255, 255, 255, .55);
    font-size: .78rem;
    text-transform: uppercase;
    letter-spacing: 1px;
  }

  .sp-val {
    color: #fff;
    font-weight: 700;
    font-size: 1rem;
  }

  .sp-price {
    color: #fbbf24;
    font-weight: 800;
    font-size: 1.3rem;
  }

  .sp-divider {
    width: 1px;
    height: 40px;
    background: rgba(255, 255, 255, .15);
  }

  /* Form card */
  .form-card {
    background: #fff;
    border-radius: 24px;
    padding: 44px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, .08);
    border: 1px solid #f0e4e8;
    max-width: 720px;
    margin: 0 auto;
  }

  /* Inputs */
  .f-group {
    margin-bottom: 20px;
  }

  .f-group label {
    display: block;
    font-weight: 600;
    font-size: .86rem;
    color: var(--text);
    margin-bottom: 7px;
  }

  .f-group label .req {
    color: var(--m);
    margin-left: 2px;
  }

  .f-input {
    width: 100%;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 15px;
    font-size: .93rem;
    font-family: inherit;
    color: black !important;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
  }

  .f-input:focus {
    border-color: black !important;
    box-shadow: 0 0 0 3px rgba(128, 0, 0, .07);
  }

  .f-input::placeholder {
    color: #b0bec5;
  }

  .f-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
  }

  .f-hint {
    font-size: .78rem;
    color: var(--muted);
    margin-top: 4px;
  }

  .f-error-box {
    background: #fff0f0;
    border: 1px solid #fca5a5;
    border-radius: 10px;
    padding: 14px 18px;
    margin-bottom: 22px;
  }

  .f-error-box p {
    font-weight: 700;
    color: #dc2626;
    margin-bottom: 6px;
    font-size: .9rem;
  }

  .f-error-box ul {
    margin: 0;
    padding-left: 16px;
    color: #dc2626;
    font-size: .85rem;
  }

  .f-err {
    color: #dc2626;
    font-size: .8rem;
    margin-top: 4px;
  }

  .is-invalid {
    border-color: #dc2626 !important;
  }

  /* Divider */
  .f-div {
    border: none;
    border-top: 1px solid #f0e4e8;
    margin: 24px 0;
  }

  /* Submit */
  .btn-sub {
    width: 100%;
    background: var(--m);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 15px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s, transform .18s, box-shadow .18s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 6px;
  }

  .btn-sub:hover {
    background: var(--md);
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(128, 0, 0, .26);
  }

  @media(max-width:640px) {
    .f-row {
      grid-template-columns: 1fr;
    }

    .form-card {
      padding: 26px 18px;
    }

    .jpp-strip {
      padding: 120px 0 50px;
    }

    .summary-pill {
      gap: 12px;
    }

    .sp-divider {
      display: none;
    }
  }
</style>

{{-- STRIP HEADER --}}
<section class="jpp-strip">
  <div class="container">
    <div class="bc mb-3">
      <a href="{{ url('/') }}" data-i18n="nav.home">Beranda</a><span>/</span>
      <a href="{{ url('/jasa-pembukuan-perpajakan') }}" data-i18n="nav.services.pembukuan">Jasa Pembukuan & Perpajakan</a><span>/</span>
      <span class="cur">{{ $kategoriInfo['label'] }} – {{ $paketInfo['label'] }}</span>
    </div>
    <span style="display:inline-block;background:rgba(212,175,55,.15);color:#fbbf24;border:1px solid rgba(212,175,55,.25);border-radius:50px;padding:4px 16px;font-size:.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;margin-bottom:12px;" data-i18n="order.form_title">Form Order</span>
    <h1 class="text-white fw-bold mb-0" style="font-size:clamp(1.6rem,3.5vw,2.4rem);">
      <span data-i18n="order.order_package">Order Paket</span> {{ $paketInfo['label'] }}<br>
      <span style="color:rgba(255,255,255,.65);font-size:.65em;font-weight:500;">{{ $kategoriInfo['label'] }} · {{ $kategoriInfo['subtitle'] }}</span>
    </h1>

    <div class="summary-pill">
      <div>
        <div class="sp-label" data-i18n="order.category">Kategori</div>
        <div class="sp-val">{{ $kategoriInfo['label'] }}</div>
      </div>
      <div class="sp-divider"></div>
      <div>
        <div class="sp-label" data-i18n="order.package">Paket</div>
        <div class="sp-val">{{ $paketInfo['label'] }}</div>
      </div>
      <div class="sp-divider"></div>
      <div>
        <div class="sp-label" data-i18n="order.price_per_month">Harga / Bulan</div>
        <div class="sp-price">Rp {{ number_format($paketInfo['harga'],0,',','.') }}</div>
      </div>
    </div>
  </div>
</section>

{{-- FORM --}}
<section style="padding:50px 0 80px;">
  <div class="container">
    <div class="form-card">

      @if($errors->any())
      <div class="f-error-box">
        <p><i class="bi bi-exclamation-triangle-fill me-2"></i><span data-i18n="order.validation_error_header">Mohon perbaiki kesalahan berikut:</span></p>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
      @endif

      <form action="{{ route('jpp.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kategori" value="{{ $kategori }}">
        <input type="hidden" name="paket" value="{{ $paket }}">

        <p style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--m);margin-bottom:14px;"><span data-i18n="order.personal_data">Data Diri</span></p>

        <div class="f-row">
          <div class="f-group">
            <label><span data-i18n="order.label_fullname">Nama Lengkap</span> <span class="req">*</span></label>
            <input type="text" name="nama_lengkap" class="f-input {{ $errors->has('nama_lengkap')?'is-invalid':'' }}" placeholder="Nama sesuai KTP" data-i18n-placeholder="order.placeholder_ktp_name" value="{{ old('nama_lengkap') }}">
            @error('nama_lengkap')<p class="f-err">{{ $message }}</p>@enderror
          </div>
          <div class="f-group">
            <label><span data-i18n="order.label_email">Email</span> <span class="req">*</span></label>
            <input type="email" name="email" class="f-input {{ $errors->has('email')?'is-invalid':'' }}" placeholder="email@perusahaan.com" data-i18n-placeholder="order.placeholder_email" value="{{ old('email') }}">
            @error('email')<p class="f-err">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="f-group">
          <label><span data-i18n="order.label_whatsapp">No. WhatsApp</span> <span class="req">*</span></label>
          <input type="text" name="no_whatsapp" class="f-input {{ $errors->has('no_whatsapp')?'is-invalid':'' }}" placeholder="08xxxxxxxxxx" data-i18n-placeholder="order.placeholder_wa" value="{{ old('no_whatsapp') }}" style="max-width:320px;">
          <p class="f-hint" data-i18n="order.wa_hint">Kami akan menghubungi Anda melalui nomor ini</p>
          @error('no_whatsapp')<p class="f-err">{{ $message }}</p>@enderror
        </div>

        <hr class="f-div">
        <p style="font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;color:var(--m);margin-bottom:14px;"><span data-i18n="order.company_data">Data Perusahaan</span></p>

        <div class="f-row">
          <div class="f-group">
            <label><span data-i18n="order.label_company_name">Nama Perusahaan</span> <span class="req">*</span></label>
            <input type="text" name="nama_perusahaan" class="f-input {{ $errors->has('nama_perusahaan')?'is-invalid':'' }}" placeholder="PT / CV / UD ..." data-i18n-placeholder="order.placeholder_company_name" value="{{ old('nama_perusahaan') }}">
            @error('nama_perusahaan')<p class="f-err">{{ $message }}</p>@enderror
          </div>
          <div class="f-group">
            <label><span data-i18n="order.label_business_type">Jenis Usaha</span> <span class="req">*</span></label>
            <input type="text" name="jenis_usaha" class="f-input {{ $errors->has('jenis_usaha')?'is-invalid':'' }}" placeholder="Perdagangan, Jasa, Manufaktur ..." data-i18n-placeholder="order.placeholder_business_type" value="{{ old('jenis_usaha') }}">
            @error('jenis_usaha')<p class="f-err">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="f-group">
          <label data-i18n="order.form_section_notes">Catatan Tambahan</label>
          <textarea name="catatan" class="f-input" rows="3" placeholder="Ceritakan kondisi bisnis Anda atau pertanyaan khusus ..." data-i18n-placeholder="order.placeholder_notes">{{ old('catatan') }}</textarea>
        </div>

        @auth
        <button type="submit" class="btn-sub">
          <i class="bi bi-send-fill"></i> <span data-i18n="order.submit_btn">Kirim Order Sekarang</span>
        </button>
        @else
        <button type="button" class="btn-sub" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="bi bi-lock-fill"></i> <span data-i18n="order.login_to_submit">Masuk untuk Mengirim Order</span>
        </button>
        @endauth

      </form>
    </div>
  </div>
</section>
@endsection