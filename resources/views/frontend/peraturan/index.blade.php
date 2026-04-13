@extends('layout.app')

@section('content')

<style>
  /* ============================================================
     DATABASE PERATURAN PAGE — CLEAN PROFESSIONAL UI
  ============================================================ */

  /* Page wrapper */
  #db-peraturan-page {
    background: #f7f6f8;
    min-height: 60vh;
    padding: 40px 0 72px;
  }

  /* ---- Page Header ---- */
  .dp-page-header {
    margin-bottom: 36px;
  }

  .dp-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #dc3545;
    background: rgba(220, 53, 69, 0.08);
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 12px;
  }

  .dp-page-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1a0208;
    letter-spacing: -0.4px;
    margin: 0 0 8px;
    line-height: 1.2;
  }

  .dp-page-subtitle {
    font-size: 0.95rem;
    color: #6b7280;
    margin: 0;
  }

  /* ---- Filter Card ---- */
  .dp-filter-card {
    background: #fff;
    border: 1px solid #e9ecf4;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04);
  }

  .dp-filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #9ca3af;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    margin-bottom: 10px;
  }

  .dp-search-input {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    padding: 10px 14px 10px 40px;
    font-size: 0.9rem;
    color: #111827 !important;
    background: #f9fafb;
    transition: border-color 0.18s ease-out, box-shadow 0.18s ease-out;
    outline: none;
  }

  .dp-search-input:focus {
    border-color: #dc3545;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.10);
  }

  .dp-search-wrap {
    position: relative;
  }

  .dp-search-icon {
    position: absolute;
    left: 13px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 0.85rem;
    pointer-events: none;
  }

  .dp-select {
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    padding: 10px 14px;
    font-size: 0.875rem;
    color: #374151;
    background: #f9fafb;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 14px;
    cursor: pointer;
    transition: border-color 0.18s ease-out;
    outline: none;
  }

  .dp-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.10);
  }

  .dp-filter-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 9px;
    padding: 10px 20px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.18s ease-out, background 0.18s ease-out;
    white-space: nowrap;
  }

  .dp-filter-btn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
  }

  .nice-select .option {
    background: white !important;
  }

  .dp-reset-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #6b7280;
    background: transparent;
    border: 1px solid #d1d5db;
    border-radius: 9px;
    padding: 10px 16px;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    text-decoration: none;
    transition: color 0.18s ease-out, border-color 0.18s ease-out;
    white-space: nowrap;
  }

  .dp-reset-btn:hover {
    color: #374151;
    border-color: #9ca3af;
  }

  /* ---- Table Card ---- */
  .dp-table-card {
    background: #fff;
    border: 1px solid #f0edf3;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
  }

  .dp-table-header-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 18px 24px 14px;
    border-bottom: 1px solid #f1f3f8;
  }

  .dp-table-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
  }

  .dp-result-count {
    font-size: 0.8rem;
    color: #9ca3af;
    font-weight: 500;
  }

  /* Table */
  .dp-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
  }

  .dp-table thead th {
    background: rgba(220, 53, 69, 0.04);
    color: #4b1c24;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    padding: 12px 16px;
    border-bottom: 1px solid rgba(220, 53, 69, 0.09);
    white-space: nowrap;
  }

  .dp-table thead th:first-child {
    padding-left: 24px;
    border-radius: 0;
  }

  .dp-table thead th:last-child {
    padding-right: 24px;
    text-align: center;
  }

  .dp-table tbody tr {
    border-bottom: 1px solid #f1f3f8;
    transition: background 0.15s ease-out;
  }

  .dp-table tbody tr:last-child {
    border-bottom: none;
  }

  .dp-table tbody tr:hover {
    background: rgba(220, 53, 69, 0.04);
  }

  .dp-table tbody td {
    padding: 14px 16px;
    color: #374151;
    vertical-align: middle;
  }

  .dp-table tbody td:first-child {
    padding-left: 24px;
  }

  .dp-table tbody td:last-child {
    padding-right: 24px;
    text-align: center;
  }

  /* Row number */
  .dp-row-no {
    font-size: 0.8rem;
    color: #9ca3af;
    font-weight: 500;
  }

  /* KBLI Badge */
  .dp-kbli-badge {
    display: inline-block;
    background: rgba(220, 53, 69, 0.08);
    color: #9b1c2c;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    letter-spacing: 0.4px;
    white-space: nowrap;
  }

  /* Title cell */
  .dp-judul {
    font-weight: 600;
    color: #1a0208;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .dp-judul-link {
    color: inherit;
    text-decoration: none;
  }

  .dp-judul-link:hover {
    color: #dc3545;
  }

  /* Date */
  .dp-date {
    color: #6b7280;
    font-size: 0.83rem;
    white-space: nowrap;
  }

  /* Status Badge */
  .dp-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 4px 11px;
    border-radius: 999px;
    white-space: nowrap;
    letter-spacing: 0.2px;
  }

  .dp-status-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    flex-shrink: 0;
  }

  .dp-status-aktif {
    background: rgba(16, 185, 129, 0.10);
    color: #059669;
  }

  .dp-status-aktif::before {
    background: #10b981;
  }

  .dp-status-inactive {
    background: rgba(220, 53, 69, 0.08);
    color: #b91c1c;
  }

  .dp-status-inactive::before {
    background: #dc3545;
  }

  /* Download button */
  .dp-download-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #dc3545;
    color: #fff;
    border: none;
    border-radius: 7px;
    padding: 6px 14px;
    font-size: 0.78rem;
    font-weight: 600;
    text-decoration: none;
    transition: transform 0.18s ease-out, background 0.18s ease-out, box-shadow 0.18s ease-out;
    cursor: pointer;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.22);
  }

  .dp-download-btn:hover {
    background: #b91c1c;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.32);
  }

  .dp-no-file {
    color: #d1d5db;
    font-size: 0.78rem;
  }

  /* ---- Empty State ---- */
  .dp-empty {
    text-align: center;
    padding: 64px 24px;
  }

  .dp-empty-icon {
    width: 64px;
    height: 64px;
    background: #f3f4f6;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.75rem;
    color: #d1d5db;
  }

  .dp-empty-title {
    font-size: 1rem;
    font-weight: 700;
    color: #374151;
    margin-bottom: 6px;
  }

  .dp-empty-sub {
    font-size: 0.875rem;
    color: #9ca3af;
  }

  /* ---- Pagination ---- */
  .dp-pagination-wrap {
    padding: 16px 24px;
    border-top: 1px solid #f1f3f8;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
  }

  .dp-pagination-info {
    font-size: 0.8rem;
    color: #9ca3af;
  }

  .dp-pagination-wrap .pagination {
    margin: 0;
    gap: 4px;
    display: flex;
    flex-wrap: wrap;
  }

  .dp-pagination-wrap .page-link {
    border: 1px solid #e9ecf4;
    border-radius: 8px !important;
    color: #374151;
    font-size: 0.82rem;
    font-weight: 600;
    padding: 6px 12px;
    transition: background 0.15s ease-out, color 0.15s ease-out, border-color 0.15s ease-out;
    line-height: 1.5;
  }

  .dp-pagination-wrap .page-link:hover {
    background: rgba(220, 53, 69, 0.06);
    border-color: rgba(220, 53, 69, 0.25);
    color: #dc3545;
  }

  .dp-pagination-wrap .page-item.active .page-link {
    background: #dc3545;
    border-color: #dc3545;
    color: #fff;
  }

  .dp-pagination-wrap .page-item.disabled .page-link {
    background: #f8f9fc;
    color: #d1d5db;
  }

  /* Responsive */
  @media (max-width: 767.98px) {
    #db-peraturan-page {
      padding: 40px 0 56px;
    }

    .dp-page-title {
      font-size: 1.55rem;
    }

    .dp-table thead th,
    .dp-table tbody td {
      padding: 10px 10px;
    }

    .dp-table thead th:first-child,
    .dp-table tbody td:first-child {
      padding-left: 14px;
    }

    .dp-table {
      font-size: 0.82rem;
    }

    .dp-filter-card {
      padding: 16px;
    }

    .dp-table-header-bar {
      padding: 14px 16px 12px;
    }

    .dp-pagination-wrap {
      padding: 12px 16px;
      flex-direction: column;
      align-items: flex-start;
    }
  }
</style>


<section id="db-peraturan-page" style="margin-top: 150px;">
  <div class="container">

    {{-- Page Header --}}
    <div class="dp-page-header">
      <div class="dp-eyebrow">
        <i class="fas fa-book-open"></i> Lawgika Legal Database
      </div>
      <h1 class="dp-page-title">Database Peraturan</h1>
      <p class="dp-page-subtitle">Daftar lengkap peraturan berdasarkan KBLI dan kategori terkait</p>
    </div>

    {{-- Search & Filter --}}
    <div class="dp-filter-card">
      <div class="dp-filter-label">Cari &amp; Filter Peraturan</div>
      <form method="GET" action="{{ url('/database-peraturan') }}" id="filterForm">
        <div class="row g-3 align-items-end">

          {{-- Search --}}
          <div class="col-12 col-md-5">
            <div class="dp-search-wrap">
              <i class="fas fa-search dp-search-icon"></i>
              <input
                type="text"
                name="search"
                id="dp-search"
                class="dp-search-input"
                placeholder="Cari judul peraturan atau kode KBLI..."
                value="{{ request('search') }}"
                autocomplete="off">
            </div>
          </div>

          {{-- Status Filter --}}
          <div class="col-6 col-md-2">
            <select name="status" id="dp-status" class="dp-select">
              <option value="">Semua Status</option>
              <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
              <option value="tidak aktif" {{ request('status') === 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
          </div>

          {{-- Year Filter --}}
          <div class="col-6 col-md-2">
            <select name="tahun" id="dp-tahun" class="dp-select">
              <option value="">Semua Tahun</option>
              @foreach($years as $tahun)
              <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
              @endforeach
            </select>
          </div>

          {{-- Actions --}}
          <div class="col-12 col-md-3 d-flex gap-2">
            <button type="submit" class="dp-filter-btn">
              <i class="fas fa-filter" style="font-size:0.75rem;"></i> Terapkan
            </button>
            @if(request()->hasAny(['search','status','tahun']))
            <a href="{{ url('/database-peraturan') }}" class="dp-reset-btn">
              <i class="fas fa-times" style="font-size:0.7rem;"></i> Reset
            </a>
            @endif
          </div>

        </div>
      </form>
    </div>

    {{-- Table Card --}}
    <div class="dp-table-card">

      {{-- Table Header Bar --}}
      <div class="dp-table-header-bar">
        <h2 class="dp-table-title">
          <i class="fas fa-list-alt" style="color:#dc3545;margin-right:7px;font-size:0.9rem;"></i>
          Daftar Peraturan
        </h2>
        <span class="dp-result-count">
          {{ $peraturan->total() }} peraturan ditemukan
          @if(request()->hasAny(['search','status','tahun']))
          &nbsp;·&nbsp;
          <a href="{{ url('/database-peraturan') }}" style="color:#dc3545;text-decoration:none;font-weight:600;">Tampilkan semua</a>
          @endif
        </span>
      </div>

      {{-- Data or Empty State --}}
      @if($peraturan->total() > 0)
      <div class="table-responsive">
        <table class="dp-table">
          <thead>
            <tr>
              <th style="width:52px;">No</th>
              <th style="width:110px;">Kode KBLI</th>
              <th>Judul Peraturan</th>
              <th style="width:130px;">Tgl. Berlaku</th>
              <th style="width:110px;">Status</th>
              <th style="width:110px;">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @foreach($peraturan as $index => $item)
            <tr>

              {{-- No --}}
              <td>
                <span class="dp-row-no">{{ $peraturan->firstItem() + $loop->index }}</span>
              </td>

              {{-- Kode KBLI --}}
              <td>
                @if($item->kode_kbli)
                <span class="dp-kbli-badge">{{ $item->kode_kbli }}</span>
                @else
                <span style="color:#d1d5db;font-size:0.78rem;">—</span>
                @endif
              </td>

              {{-- Judul --}}
              <td>
                <div class="dp-judul" title="{{ $item->judul_peraturan }}">
                  {{ $item->judul_peraturan }}
                </div>
              </td>

              {{-- Tanggal Berlaku --}}
              <td>
                @if($item->tanggal_berlaku)
                <span class="dp-date">{{ \Carbon\Carbon::parse($item->tanggal_berlaku)->translatedFormat('d M Y') }}</span>
                @else
                <span style="color:#d1d5db;font-size:0.78rem;">—</span>
                @endif
              </td>

              {{-- Status --}}
              <td>
                @php
                $statusNorm = strtolower(trim($item->status ?? ''));
                $isAktif = $statusNorm === 'aktif';
                @endphp
                <span class="dp-status-badge {{ $isAktif ? 'dp-status-aktif' : 'dp-status-inactive' }}">
                  {{ $isAktif ? 'Aktif' : 'Tidak Aktif' }}
                </span>
              </td>

              {{-- Aksi --}}
              <td>
                @if($item->file_dokumen)
                <a
                  href="{{ asset('storage/' . $item->file_dokumen) }}"
                  class="dp-download-btn"
                  target="_blank"
                  rel="noopener noreferrer"
                  download
                  title="Download dokumen">
                  <i class="fas fa-download" style="font-size:0.7rem;"></i> PDF
                </a>
                @else
                <span class="dp-no-file">
                  <i class="fas fa-minus"></i>
                </span>
                @endif
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if($peraturan->hasPages())
      <div class="dp-pagination-wrap">
        <span class="dp-pagination-info">
          Menampilkan {{ $peraturan->firstItem() }}–{{ $peraturan->lastItem() }} dari {{ $peraturan->total() }} data
        </span>
        {{ $peraturan->links() }}
      </div>
      @endif

      @else
      {{-- Empty State --}}
      <div class="dp-empty">
        <div class="dp-empty-icon">
          <i class="fas fa-folder-open"></i>
        </div>
        <div class="dp-empty-title">Data peraturan belum tersedia</div>
        <p class="dp-empty-sub">
          @if(request()->hasAny(['search','status','tahun']))
          Tidak ada data yang sesuai dengan filter. <a href="{{ url('/database-peraturan') }}" style="color:#dc3545;">Reset pencarian</a>
          @else
          Belum ada peraturan yang terdaftar dalam database.
          @endif
        </p>
      </div>
      @endif

    </div>
    {{-- /Table Card --}}

  </div>
</section>

@endsection