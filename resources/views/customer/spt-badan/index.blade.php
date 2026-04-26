@extends('layouts-customer.app')
@section('title', 'Riwayat SPT Tahunan')
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">Riwayat Pengajuan SPT Tahunan</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">Pantau status pengajuan pelaporan pajak Anda.</p>
    </div>
    <a href="/pelaporan-spt-tahunan#formPengajuan" class="btn btn-sm btn-danger px-3 fw-semibold">
      <i class="bi bi-plus-lg me-1"></i> Ajukan Baru
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4 py-3">No.</th>
              <th class="py-3">Subject</th>
              <th class="py-3">Nama / Perusahaan</th>
              <th class="py-3">Tahun Pajak</th>
              <th class="py-3">Lap. Keuangan</th>
              <th class="pe-4 py-3">Status Pesanan</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $i => $r)
            <tr>
              <td class="ps-4 py-3"><small class="text-muted">{{ $i + 1 }}</small></td>
              <td class="py-3">
                @if($r->subject_type === 'pribadi')
                  <span class="badge bg-info bg-opacity-15 text-info border border-info" style="font-size:.78rem;">👤 Pribadi</span>
                @else
                  <span class="badge bg-primary bg-opacity-15 text-primary border border-primary" style="font-size:.78rem;">🏢 Badan</span>
                @endif
              </td>
              <td class="py-3 fw-semibold">{{ $r->display_name }}</td>
              <td class="py-3">{{ $r->tahun_pajak }}</td>
              <td class="py-3 text-capitalize">{{ $r->laporan_keuangan }}</td>
              <td class="pe-4 py-3">
                @php
                  $badge = match($r->status_pesanan) {
                    'Menunggu Proses' => 'warning',
                    'Diproses'        => 'info',
                    'Selesai'         => 'success',
                    default           => 'secondary',
                  };
                @endphp
                <span class="badge bg-{{ $badge }} bg-opacity-20 text-{{ $badge }} border border-{{ $badge }}">
                  {{ $r->status_pesanan }}
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem;color:#d1d5db;display:block;margin-bottom:8px;"></i>
                Belum ada pengajuan SPT Tahunan.<br>
                <a href="/pelaporan-spt-tahunan" class="text-danger fw-semibold mt-2 d-inline-block">Ajukan sekarang →</a>
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
