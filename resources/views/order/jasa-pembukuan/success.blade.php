@extends('layout.app')
@section('content')
<style>
  :root {
    --m: #800000;
    --md: #4a0000;
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

  .wrap {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 16px;
  }

  .card-s {
    background: #fff;
    border-radius: 28px;
    padding: 50px 44px;
    max-width: 620px;
    width: 100%;
    box-shadow: 0 24px 64px rgba(0, 0, 0, .08);
    border: 1px solid #f0e4e8;
    text-align: center;
  }

  .ring {
    width: 86px;
    height: 86px;
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 22px;
    box-shadow: 0 12px 32px rgba(16, 185, 129, .18);
  }

  .ring i {
    font-size: 2.3rem;
    color: var(--green);
  }

  .card-s h2 {
    font-size: 1.75rem;
    font-weight: 800;
    margin-bottom: 6px;
  }

  .card-s>p {
    color: var(--muted);
    line-height: 1.7;
    margin-bottom: 28px;
    font-size: .95rem;
  }

  .order-code {
    display: inline-block;
    background: #f8f9fb;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 8px 18px;
    font-family: monospace;
    font-size: .9rem;
    font-weight: 700;
    color: var(--m);
    margin-bottom: 28px;
    letter-spacing: .5px;
  }

  .sum-box {
    background: #f8f9fb;
    border-radius: 16px;
    padding: 22px;
    text-align: left;
    margin-bottom: 26px;
    border: 1px solid #f0e4e8;
  }

  .sum-box h4 {
    font-size: .72rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--m);
    font-weight: 700;
    margin-bottom: 14px;
  }

  .sr {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f0e4e8;
    gap: 10px;
  }

  .sr:last-child {
    border-bottom: none;
  }

  .sr-l {
    color: var(--muted);
    font-size: .86rem;
  }

  .sr-v {
    font-weight: 600;
    font-size: .88rem;
    text-align: right;
  }

  .price-highlight {
    color: var(--m);
    font-size: 1.1rem;
    font-weight: 800;
  }

  .info-note {
    background: linear-gradient(135deg, #fff5f5, #ffe8e8);
    border: 1px solid rgba(128, 0, 0, .12);
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 24px;
    display: flex;
    gap: 10px;
    align-items: flex-start;
    text-align: left;
  }

  .info-note i {
    color: var(--m);
    font-size: 1.1rem;
    flex-shrink: 0;
    margin-top: 2px;
  }

  .info-note p {
    margin: 0;
    color: #5a1010;
    font-size: .86rem;
    line-height: 1.6;
  }

  .btn-g {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 12px;
    padding: 12px 26px;
    font-weight: 700;
    font-size: .92rem;
    text-decoration: none;
    transition: all .2s;
  }

  .btn-wa {
    background: #25D366;
    color: #fff;
    border: none;
  }

  .btn-wa:hover {
    background: #1da851;
    transform: translateY(-2px);
    color: #fff;
  }

  .btn-out {
    background: transparent;
    color: var(--text);
    border: 1.5px solid #e5e7eb;
  }

  .btn-out:hover {
    border-color: var(--m);
    color: var(--m);
  }

  .btn-dash {
    background: var(--m);
    color: #fff;
    border: none;
  }

  .btn-dash:hover {
    background: var(--md);
    transform: translateY(-2px);
    color: #fff;
  }

  @media(max-width:480px) {
    .card-s {
      padding: 30px 18px;
    }

    .card-s h2 {
      font-size: 1.4rem;
    }
  }
</style>

<div class="wrap">
  <div class="card-s">
    <div class="ring"><i class="bi bi-check-lg"></i></div>
    <h2>Order Berhasil Dikirim! 🎉</h2>
    <p>Terima kasih! Order Anda telah kami terima. Tim Lawgika akan menghubungi Anda melalui WhatsApp dalam <strong>1×24 jam kerja</strong>.</p>

    <div class="order-code">{{ $data['order_number'] }}</div>

    <div class="sum-box">
      <h4>Ringkasan Order</h4>
      <div class="sr">
        <span class="sr-l">Layanan</span>
        <span class="sr-v">Jasa Pembukuan & Perpajakan</span>
      </div>
      <div class="sr">
        <span class="sr-l">Kategori</span>
        <span class="sr-v">{{ $data['kategori_label'] }}</span>
      </div>
      <div class="sr">
        <span class="sr-l">Paket</span>
        <span class="sr-v">{{ $data['paket_label'] }}</span>
      </div>
      <div class="sr">
        <span class="sr-l">Nama</span>
        <span class="sr-v">{{ $data['nama_lengkap'] }}</span>
      </div>
      <div class="sr">
        <span class="sr-l">Perusahaan</span>
        <span class="sr-v">{{ $data['nama_perusahaan'] }}</span>
      </div>
      <div class="sr">
        <span class="sr-l">WhatsApp</span>
        <span class="sr-v">{{ $data['no_whatsapp'] }}</span>
      </div>
      <div class="sr">
        <span class="sr-l">Total Biaya / Bulan</span>
        <span class="sr-v price-highlight">Rp {{ number_format($data['harga'],0,',','.') }}</span>
      </div>
    </div>

    <div class="info-note">
      <i class="bi bi-info-circle-fill"></i>
      <p>Silakan konfirmasi order Anda via WhatsApp agar tim kami dapat segera memproses dan menyiapkan dokumen kontrak layanan.</p>
    </div>

    @php
    $waText = urlencode(
    "Halo Lawgika, saya telah melakukan order:\n\n" .
    "- Order ID: {$data['order_number']}\n" .
    "- Paket: {$data['paket_label']}\n" .
    "- Kategori: {$data['kategori_label']}\n\n" .
    "Mohon informasi lebih lanjut. Terima kasih."
    );
    @endphp

    <div class="d-flex flex-wrap gap-3 justify-content-center">
      <a href="{{ url('/') }}" class="btn-g btn-out">
        <i class="bi bi-house"></i> Beranda
      </a>
      <a href="https://wa.me/6281112088600?text={{ $waText }}" target="_blank" rel="noopener" class="btn-g btn-wa">
        <i class="bi bi-whatsapp"></i> Konfirmasi via WA
      </a>
      <a href="{{ route('customer.dashboard') }}" class="btn-g btn-dash">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
    </div>
  </div>
</div>
@endsection