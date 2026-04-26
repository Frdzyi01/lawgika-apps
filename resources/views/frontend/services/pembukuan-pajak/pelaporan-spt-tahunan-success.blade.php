@extends('layout.app')
@section('content')
<style>
  :root {
    --maroon: #800000;
    --maroon-dk: #4a0000;
    --green: #10b981;
    --bg: #f8f9fb;
    --text: #1e293b;
    --muted: #64748b;
  }

  body {
    font-family: 'Inter', system-ui, sans-serif;
    background: var(--bg);
    color: var(--text);
  }

  .success-wrap {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 16px;
  }

  .success-card {
    background: #fff;
    border-radius: 28px;
    padding: 52px 44px;
    max-width: 600px;
    width: 100%;
    box-shadow: 0 24px 64px rgba(0, 0, 0, .08);
    border: 1px solid #f0e4e8;
    text-align: center;
  }

  .check-ring {
    width: 88px;
    height: 88px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    box-shadow: 0 12px 32px rgba(16, 185, 129, .2);
  }

  .check-ring i {
    font-size: 2.4rem;
    color: var(--green);
  }

  .success-card h2 {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 8px;
    color: var(--text);
  }

  .success-card>p {
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 32px;
  }

  .summary-box {
    background: #f8f9fb;
    border-radius: 16px;
    padding: 24px;
    text-align: left;
    margin-bottom: 32px;
    border: 1px solid #f0e4e8;
  }

  .summary-box h4 {
    font-size: .75rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--maroon);
    font-weight: 700;
    margin-bottom: 16px;
  }

  .summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #f0e4e8;
    gap: 12px;
  }

  .summary-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
  }

  .sr-label {
    color: var(--muted);
    font-size: .88rem;
  }

  .sr-val {
    font-weight: 600;
    font-size: .9rem;
    text-align: right;
  }

  .status-badge {
    background: #fffbeb;
    color: #92400e;
    border: 1px solid #fcd34d;
    border-radius: 20px;
    padding: 3px 12px;
    font-size: .82rem;
    font-weight: 700;
  }

  .info-note {
    background: linear-gradient(135deg, #fff5f5, #ffe8e8);
    border: 1px solid rgba(128, 0, 0, .12);
    border-radius: 14px;
    padding: 16px 20px;
    margin-bottom: 28px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    text-align: left;
  }

  .info-note i {
    color: var(--maroon);
    font-size: 1.2rem;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .info-note p {
    margin: 0;
    color: #5a1010;
    font-size: .88rem;
    line-height: 1.6;
  }

  .btn-primary-s {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--maroon);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 13px 28px;
    font-weight: 700;
    font-size: .95rem;
    cursor: pointer;
    text-decoration: none;
    transition: background .2s, transform .2s;
  }

  .btn-primary-s:hover {
    background: var(--maroon-dk);
    transform: translateY(-2px);
    color: #fff;
  }

  .btn-outline-s {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    color: var(--text);
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 13px 28px;
    font-weight: 600;
    font-size: .95rem;
    text-decoration: none;
    transition: border-color .2s, color .2s;
  }

  .btn-outline-s:hover {
    border-color: var(--maroon);
    color: var(--maroon);
  }

  @media(max-width:480px) {
    .success-card {
      padding: 32px 20px;
    }

    .success-card h2 {
      font-size: 1.5rem;
    }
  }
</style>

<div class="success-wrap">
  <div class="success-card">
    <div class="check-ring"><i class="bi bi-check-lg"></i></div>
    <h2>Pengajuan Berhasil! 🎉</h2>
    <p>Terima kasih telah mempercayakan pelaporan pajak Anda kepada Lawgika.<br>Tim kami akan meninjau dan menghubungi Anda segera.</p>

    <div class="summary-box">
      <h4>Ringkasan Pengajuan</h4>

      <div class="summary-row">
        <span class="sr-label">Subject Pajak</span>
        <span class="sr-val">
          @if($data['subject_type'] === 'pribadi')
            👤 Wajib Pajak Pribadi
          @else
            🏢 Wajib Pajak Badan
          @endif
        </span>
      </div>

      @if($data['subject_type'] === 'pribadi')
      <div class="summary-row">
        <span class="sr-label">Nama Lengkap</span>
        <span class="sr-val">{{ $data['nama_lengkap'] }}</span>
      </div>
      @if(!empty($data['nik']))
      <div class="summary-row">
        <span class="sr-label">NIK</span>
        <span class="sr-val">{{ $data['nik'] }}</span>
      </div>
      @endif
      @else
      <div class="summary-row">
        <span class="sr-label">Nama Perusahaan</span>
        <span class="sr-val">{{ $data['perusahaan'] }}</span>
      </div>
      @if(!empty($data['npwp_perusahaan']))
      <div class="summary-row">
        <span class="sr-label">NPWP Perusahaan</span>
        <span class="sr-val">{{ $data['npwp_perusahaan'] }}</span>
      </div>
      @endif
      @endif

      <div class="summary-row">
        <span class="sr-label">Tahun Pajak</span>
        <span class="sr-val">{{ $data['tahun_pajak'] }}</span>
      </div>
      <div class="summary-row">
        <span class="sr-label">Laporan Keuangan</span>
        <span class="sr-val">{{ $data['laporan_keuangan'] === 'sudah' ? '✅ Sudah ada' : '⏳ Perlu bantuan' }}</span>
      </div>
      <div class="summary-row">
        <span class="sr-label">Status Pelaporan</span>
        <span class="sr-val">{{ $data['status_lapor'] === 'sudah' ? 'Pernah lapor sebelumnya' : 'Pertama kali lapor' }}</span>
      </div>
      <div class="summary-row">
        <span class="sr-label">Status Pesanan</span>
        <span class="sr-val"><span class="status-badge">⏳ Menunggu Proses</span></span>
      </div>
    </div>

    <div class="info-note">
      <i class="bi bi-info-circle-fill"></i>
      <p>Tim Lawgika akan menghubungi Anda melalui email atau WhatsApp dalam <strong>1×24 jam kerja</strong> untuk menginformasikan langkah selanjutnya dan detail biaya layanan.</p>
    </div>

    @php
      $waName = $data['subject_type'] === 'pribadi' ? ($data['nama_lengkap'] ?? '') : ($data['perusahaan'] ?? '');
      $waMsg  = urlencode("Halo Lawgika, saya baru mengajukan Pelaporan SPT Tahunan atas nama *{$waName}* untuk tahun pajak *{$data['tahun_pajak']}*. Mohon informasi selanjutnya. Terima kasih.");
    @endphp
    <div class="d-flex flex-wrap gap-3 justify-content-center">
      <a href="{{ url('/') }}" class="btn-outline-s">
        <i class="bi bi-house"></i> Kembali ke Beranda
      </a>
      <a href="https://wa.me/6281219110199?text={{ $waMsg }}" target="_blank" rel="noopener"
         class="btn-primary-s" style="background:#25D366;">
        <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
      </a>
      <a href="{{ route('customer.dashboard') }}" class="btn-outline-s">
        <i class="bi bi-speedometer2"></i> Lihat Dashboard
      </a>
    </div>
  </div>
</div>
@endsection
