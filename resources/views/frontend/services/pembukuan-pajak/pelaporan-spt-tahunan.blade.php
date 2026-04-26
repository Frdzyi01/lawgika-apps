@extends('layout.app')
@section('content')
<style>
  :root {
    --maroon: #800000;
    --maroon-dk: #4a0000;
    --maroon-lt: #a52a2a;
    --gold: #D4AF37;
    --bg: #f8f9fb;
    --text: #1e293b;
    --muted: #64748b;
    --green: #10b981;
  }

  * {
    box-sizing: border-box;
  }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    color: var(--text);
    background: var(--bg);
  }

  /* Hero */
  .spt-hero {
    background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%);
    padding: 180px 0 80px;
    position: relative;
    overflow: hidden;
  }

  .spt-hero::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 60px;
    background: var(--bg);
    clip-path: ellipse(55% 100% at 50% 100%);
  }

  /* Breadcrumb */
  .bc a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: .85rem;
  }

  .bc a:hover {
    color: #fff;
  }

  .bc span {
    color: rgba(255, 255, 255, 0.4);
    margin: 0 6px;
  }

  .bc .active {
    color: #fff;
    font-size: .85rem;
  }

  /* Section tag */
  .s-tag {
    display: inline-block;
    background: linear-gradient(135deg, #fff5f5, #ffe8e8);
    color: var(--maroon);
    padding: 5px 18px;
    border-radius: 50px;
    font-size: .8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 14px;
  }

  /* Cards */
  .why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
    margin-top: 40px;
  }

  .why-card {
    background: #fff;
    border-radius: 20px;
    padding: 30px 24px;
    border: 1px solid #f0e4e8;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
    transition: transform .3s, box-shadow .3s;
  }

  .why-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(128, 0, 0, .1);
  }

  .why-num {
    width: 44px;
    height: 44px;
    background: var(--maroon);
    color: #fff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 1.2rem;
    margin-bottom: 16px;
  }

  .why-card h4 {
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 8px;
  }

  .why-card p {
    color: var(--muted);
    font-size: .9rem;
    margin: 0;
    line-height: 1.6;
  }

  /* Form card */
  .form-wrap {
    background: #fff;
    border-radius: 28px;
    padding: 48px;
    box-shadow: 0 24px 64px rgba(0, 0, 0, .08);
    border: 1px solid #f0e4e8;
    max-width: 780px;
    margin: 0 auto;
  }

  .form-title {
    font-size: 1.6rem;
    font-weight: 800;
    margin-bottom: 4px;
  }

  .form-sub {
    color: var(--muted);
    margin-bottom: 32px;
    font-size: .95rem;
    line-height: 1.6;
  }

  /* Subject tabs */
  .subj-tabs {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
    margin-bottom: 30px;
  }

  .subj-tab {
    border: 2px solid #e5e7eb;
    border-radius: 16px;
    padding: 20px;
    cursor: pointer;
    transition: all .25s;
    text-align: center;
    background: #fff;
  }

  .subj-tab input[type=radio] {
    display: none;
  }

  .subj-tab .tab-icon {
    font-size: 2rem;
    margin-bottom: 8px;
    display: block;
  }

  .subj-tab .tab-label {
    font-weight: 700;
    font-size: .95rem;
    display: block;
  }

  .subj-tab .tab-desc {
    color: var(--muted);
    font-size: .8rem;
    margin-top: 4px;
    display: block;
  }

  .subj-tab:has(input:checked) {
    border-color: var(--maroon);
    background: linear-gradient(135deg, #fff5f5, #ffe8e8);
  }

  .subj-tab:has(input:checked) .tab-label {
    color: var(--maroon);
  }

  /* Inputs */
  .f-group {
    margin-bottom: 20px;
  }

  .f-group label {
    display: block;
    font-weight: 600;
    font-size: .88rem;
    color: var(--text);
    margin-bottom: 7px;
  }

  .f-group label .req {
    color: var(--maroon);
    margin-left: 2px;
  }

  .f-input {
    width: 100%;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 13px 16px;
    font-size: .95rem;
    font-family: inherit;
    color: black !important;
    background: #fff;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
  }

  .f-input:focus {
    border-color: var(--maroon);
    box-shadow: 0 0 0 3px rgba(128, 0, 0, .08);
  }

  .f-input::placeholder {
    color: #b0bec5;
  }

  select.f-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23800000' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 40px;
  }

  .f-hint {
    font-size: .8rem;
    color: var(--muted);
    margin-top: 5px;
  }

  .f-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  /* Dynamic sections */
  .dyn-section {
    display: none;
  }

  .dyn-section.show {
    display: block;
  }

  /* Divider */
  .f-divider {
    border: none;
    border-top: 1px solid #f0e4e8;
    margin: 28px 0;
  }

  /* Submit btn */
  .btn-submit {
    width: 100%;
    background: var(--maroon);
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 16px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: background .25s, transform .2s, box-shadow .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 8px;
  }

  .btn-submit:hover {
    background: var(--maroon-dk);
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(128, 0, 0, .28);
  }

  .btn-submit i {
    font-size: 1.1rem;
  }

  /* Error */
  .f-error {
    color: #dc2626;
    font-size: .82rem;
    margin-top: 5px;
  }

  .is-invalid {
    border-color: #dc2626 !important;
  }

  /* FAQ */
  .faq-item {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #f0e4e8;
    margin-bottom: 12px;
    overflow: hidden;
  }

  .faq-q {
    padding: 20px 24px;
    font-weight: 700;
    font-size: .95rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    user-select: none;
    transition: background .2s;
  }

  .faq-q:hover {
    background: #fff5f5;
  }

  .faq-q i {
    color: var(--maroon);
    transition: transform .3s;
    flex-shrink: 0;
  }

  .faq-a {
    max-height: 0;
    overflow: hidden;
    transition: max-height .35s ease, padding .35s;
    color: var(--muted);
    line-height: 1.75;
    font-size: .9rem;
    padding: 0 24px;
  }

  .faq-item.open .faq-q {
    background: #fff5f5;
    color: var(--maroon);
  }

  .faq-item.open .faq-q i {
    transform: rotate(180deg);
  }

  .faq-item.open .faq-a {
    max-height: 400px;
    padding: 0 24px 20px;
  }

  /* Responsive */
  @media(max-width:640px) {
    .f-row {
      grid-template-columns: 1fr;
    }

    .form-wrap {
      padding: 28px 20px;
    }

    .spt-hero {
      padding: 130px 0 60px;
    }
  }
</style>

{{-- HERO --}}
<section class="spt-hero">
  <div class="container position-relative z-1">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <div class="bc mb-4">
          <a href="{{ url('/') }}">Beranda</a><span>/</span>
          <a href="#">Layanan</a><span>/</span>
          <span class="active">Pelaporan SPT Tahunan</span>
        </div>
        <span class="s-tag" style="background:rgba(212,175,55,.15);color:var(--gold);border:1px solid rgba(212,175,55,.25);">Layanan Perpajakan</span>
        <h1 class="text-white fw-bold mt-2 mb-3" style="font-size:clamp(2rem,4vw,3rem);letter-spacing:-1px;">Pelaporan SPT Tahunan</h1>
        <p class="text-white-50" style="font-size:1.05rem;max-width:520px;line-height:1.7;">Satu layanan untuk semua kebutuhan pelaporan pajak tahunan Anda — baik pribadi maupun badan usaha. Proses cepat, aman, dan sesuai ketentuan DJP.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:12px 20px;">
            <div style="color:var(--gold);font-weight:700;font-size:1.3rem;">500+</div>
            <div style="color:rgba(255,255,255,.6);font-size:.8rem;">SPT Terlapor</div>
          </div>
          <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:12px 20px;">
            <div style="color:var(--gold);font-weight:700;font-size:1.3rem;">100%</div>
            <div style="color:rgba(255,255,255,.6);font-size:.8rem;">Tepat Waktu</div>
          </div>
          <div style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);border-radius:12px;padding:12px 20px;">
            <div style="color:var(--gold);font-weight:700;font-size:1.3rem;">0</div>
            <div style="color:rgba(255,255,255,.6);font-size:.8rem;">Kasus Sanksi</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- WHY US --}}
<section style="padding:70px 0;background:var(--bg);">
  <div class="container">
    <div class="text-center mb-2"><span class="s-tag">Mengapa Kami</span></div>
    <h2 class="text-center fw-bold" style="font-size:2rem;letter-spacing:-.5px;">Keuntungan Menggunakan Layanan Kami</h2>
    <div class="why-grid">
      <div class="why-card">
        <div class="why-num">1</div>
        <h4>Satu Platform, Dua Kebutuhan</h4>
        <p>Tangani SPT Pribadi (1770/1770S) dan SPT Badan (1771) dalam satu alur pengajuan yang mudah.</p>
      </div>
      <div class="why-card">
        <div class="why-num">2</div>
        <h4>Tim Konsultan Berpengalaman</h4>
        <p>Ditangani oleh konsultan pajak bersertifikat yang memahami regulasi terbaru dari DJP.</p>
      </div>
      <div class="why-card">
        <div class="why-num">3</div>
        <h4>Tepat Waktu, Bebas Denda</h4>
        <p>Kami memastikan pelaporan selesai sebelum batas waktu agar Anda terhindar dari sanksi administrasi.</p>
      </div>
      <div class="why-card">
        <div class="why-num">4</div>
        <h4>Proses Transparan</h4>
        <p>Pantau status pengajuan Anda secara real-time melalui dashboard pelanggan kapan saja.</p>
      </div>
    </div>
  </div>
</section>

{{-- FORM --}}
<section style="padding:20px 0 80px;" id="formPengajuan">
  <div class="container">
    <div class="text-center mb-5">
      <span class="s-tag">Form Pengajuan</span>
      <h2 class="fw-bold mt-2" style="font-size:2rem;letter-spacing:-.5px;">Ajukan Pelaporan SPT Tahunan</h2>
      <p style="color:var(--muted);max-width:560px;margin:10px auto 0;line-height:1.7;">Isi data berikut untuk memulai proses pelaporan pajak tahunan Anda secara cepat dan aman. Tim kami akan menghubungi dalam 1×24 jam.</p>
    </div>

    <div class="form-wrap">

      @if($errors->any())
      <div style="background:#fff0f0;border:1px solid #fca5a5;border-radius:12px;padding:16px 20px;margin-bottom:24px;">
        <p style="font-weight:700;color:#dc2626;margin-bottom:8px;"><i class="bi bi-exclamation-triangle-fill me-2"></i>Mohon perbaiki kesalahan berikut:</p>
        <ul style="margin:0;padding-left:18px;color:#dc2626;font-size:.9rem;">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
      @endif



      <form action="{{ route('spt-tahunan.store') }}" method="POST" id="sptForm">
        @csrf

        {{-- STEP 1: Subject Pajak --}}
        <p style="font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;color:var(--maroon);margin-bottom:12px;">Langkah 1 — Pilih Subject Pajak</p>
        <div class="subj-tabs">
          <label class="subj-tab" for="tab_pribadi">
            <input type="radio" name="subject_type" id="tab_pribadi" value="pribadi" onchange="switchSubject('pribadi')" {{ old('subject_type')=='pribadi' ? 'checked' : '' }}>
            <span class="tab-icon">👤</span>
            <span class="tab-label">Pribadi</span>
            <span class="tab-desc">SPT Form 1770 / 1770S</span>
          </label>
          <label class="subj-tab" for="tab_badan">
            <input type="radio" name="subject_type" id="tab_badan" value="badan" onchange="switchSubject('badan')" {{ old('subject_type','badan')=='badan' ? 'checked' : '' }}>
            <span class="tab-icon">🏢</span>
            <span class="tab-label">Badan</span>
            <span class="tab-desc">SPT Form 1771 / 1771 $</span>
          </label>
        </div>
        @error('subject_type')<p class="f-error">{{ $message }}</p>@enderror

        <hr class="f-divider">

        {{-- PRIBADI fields --}}
        <div class="dyn-section" id="sec_pribadi">
          <p style="font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;color:var(--maroon);margin-bottom:16px;">Langkah 2 — Data Wajib Pajak Pribadi</p>
          <div class="f-row">
            <div class="f-group">
              <label>Nama Lengkap <span class="req">*</span></label>
              <input type="text" name="nama_lengkap" class="f-input {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" placeholder="Sesuai KTP" value="{{ old('nama_lengkap') }}">
              @error('nama_lengkap')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div class="f-group">
              <label>NIK (Opsional)</label>
              <input type="text" name="nik" class="f-input {{ $errors->has('nik') ? 'is-invalid' : '' }}" placeholder="16 digit NIK" maxlength="16" value="{{ old('nik') }}">
              <p class="f-hint">Membantu verifikasi data lebih cepat</p>
              @error('nik')<p class="f-error">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>

        {{-- BADAN fields --}}
        <div class="dyn-section" id="sec_badan">
          <p style="font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;color:var(--maroon);margin-bottom:16px;">Langkah 2 — Data Wajib Pajak Badan</p>
          <div class="f-row">
            <div class="f-group">
              <label>Nama Perusahaan <span class="req">*</span></label>
              <input type="text" name="perusahaan" class="f-input {{ $errors->has('perusahaan') ? 'is-invalid' : '' }}" placeholder="PT / CV / Firma ..." value="{{ old('perusahaan') }}">
              @error('perusahaan')<p class="f-error">{{ $message }}</p>@enderror
            </div>
            <div class="f-group">
              <label>NPWP Perusahaan (Opsional)</label>
              <input type="text" name="npwp_perusahaan" class="f-input" placeholder="00.000.000.0-000.000" value="{{ old('npwp_perusahaan') }}">
              <p class="f-hint">Membantu proses verifikasi</p>
            </div>
          </div>
        </div>

        <hr class="f-divider">

        {{-- SHARED fields --}}
        <p style="font-weight:700;font-size:.8rem;text-transform:uppercase;letter-spacing:1px;color:var(--maroon);margin-bottom:16px;">Langkah 3 — Detail Pelaporan</p>
        <div class="f-row">
          <div class="f-group">
            <label>Tahun Pajak <span class="req">*</span></label>
            <select name="tahun_pajak" class="f-input {{ $errors->has('tahun_pajak') ? 'is-invalid' : '' }}">
              <option value="">Pilih Tahun</option>
              @for($y = date('Y'); $y >= 2018; $y--)
              <option value="{{ $y }}" {{ old('tahun_pajak')==$y ? 'selected':'' }}>{{ $y }}</option>
              @endfor
            </select>
            @error('tahun_pajak')<p class="f-error">{{ $message }}</p>@enderror
          </div>
          <div class="f-group">
            <label>Sudah Ada Laporan Keuangan? <span class="req">*</span></label>
            <select name="laporan_keuangan" class="f-input">
              <option value="sudah" {{ old('laporan_keuangan')=='sudah'?'selected':'' }}>Sudah ada</option>
              <option value="belum" {{ old('laporan_keuangan')=='belum'?'selected':'' }}>Belum / Perlu bantuan</option>
            </select>
          </div>
        </div>
        <div class="f-group" style="max-width:380px;">
          <label>Status Pelaporan Sebelumnya <span class="req">*</span></label>
          <select name="status_lapor" class="f-input">
            <option value="sudah" {{ old('status_lapor')=='sudah'?'selected':'' }}>Sudah pernah lapor</option>
            <option value="belum" {{ old('status_lapor')=='belum'?'selected':'' }}>Belum pernah lapor</option>
          </select>
        </div>

        @auth
        <button type="submit" class="btn-submit" id="sptSubmitBtn">
          <i class="bi bi-send-fill"></i> Kirim Pengajuan
        </button>
        @else
        {{-- Trigger modal login header yang sama --}}
        <button type="button" class="btn-submit" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <i class="bi bi-lock-fill"></i> Masuk untuk Mengajukan
        </button>
        @endauth

      </form>
    </div>
  </div>
</section>

{{-- FAQ --}}
<section style="padding:0 0 80px;background:var(--bg);">
  <div class="container">
    <div class="text-center mb-5">
      <span class="s-tag">FAQ</span>
      <h2 class="fw-bold mt-2" style="font-size:1.9rem;">Pertanyaan Umum</h2>
    </div>
    <div style="max-width:780px;margin:0 auto;">
      @foreach([
      ['Kapan batas waktu SPT Pribadi?','Batas pelaporan SPT Tahunan Pribadi adalah 31 Maret. Untuk badan usaha, batas waktunya adalah 30 April.'],
      ['Apa bedanya SPT Pribadi dan Badan?','SPT Pribadi (Form 1770/1770S) digunakan oleh wajib pajak orang pribadi. SPT Badan (Form 1771) digunakan oleh perusahaan atau badan usaha.'],
      ['Dokumen apa yang perlu disiapkan?','Untuk Pribadi: bukti potong A1/A2, rekening tabungan, data penghasilan lain. Untuk Badan: laporan keuangan, SPT Masa, bukti potong PPh.'],
      ['Berapa sanksi jika terlambat lapor?','Denda keterlambatan: Rp 100.000 untuk SPT Pribadi, Rp 1.000.000 untuk SPT Badan. Belum termasuk sanksi bunga jika ada kurang bayar.'],
      ] as [$q,$a])
      <div class="faq-item">
        <div class="faq-q" onclick="toggleFaq(this)">
          <span>{{ $q }}</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a">{{ $a }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<script>
  function switchSubject(type) {
    document.getElementById('sec_pribadi').classList.toggle('show', type === 'pribadi');
    document.getElementById('sec_badan').classList.toggle('show', type === 'badan');
  }

  function toggleFaq(el) {
    const item = el.closest('.faq-item');
    const open = item.classList.contains('open');
    document.querySelectorAll('.faq-item.open').forEach(i => i.classList.remove('open'));
    if (!open) item.classList.add('open');
  }
  // Init on load
  (function() {
    const checked = document.querySelector('input[name="subject_type"]:checked');
    if (checked) switchSubject(checked.value);
    else switchSubject('badan');
  })();
</script>
@endsection