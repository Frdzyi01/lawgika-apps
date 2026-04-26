@extends('layouts-admin.admin')
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="mb-1 fw-bold">Pengajuan SPT Tahunan</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">Semua pengajuan SPT Pribadi &amp; Badan masuk di sini.</p>
    </div>
    <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size:.85rem;">
      {{ $requests->count() }} Pengajuan
    </span>
  </div>

  @if(session('success'))
  <div class="alert alert-success border-0 rounded-3 mb-4">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4 py-3">Pemohon</th>
              <th class="py-3">Subject</th>
              <th class="py-3">Nama / Perusahaan</th>
              <th class="py-3">Tahun Pajak</th>
              <th class="py-3">Lap. Keuangan</th>
              <th class="py-3">Status Lapor</th>
              <th class="py-3">Status Pesanan</th>
              <th class="pe-4 py-3 text-center">Ubah Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $r)
            <tr>
              <td class="ps-4 py-3">
                <div class="fw-semibold">{{ $r->user->name ?? '-' }}</div>
                <small class="text-muted">{{ $r->user->email ?? '' }}</small>
              </td>
              <td class="py-3">
                @if($r->subject_type === 'pribadi')
                  <span class="badge bg-info bg-opacity-15 text-info border border-info" style="font-size:.78rem;">👤 Pribadi</span>
                @else
                  <span class="badge bg-primary bg-opacity-15 text-primary border border-primary" style="font-size:.78rem;">🏢 Badan</span>
                @endif
              </td>
              <td class="py-3">{{ $r->display_name }}</td>
              <td class="py-3">{{ $r->tahun_pajak }}</td>
              <td class="py-3 text-capitalize">{{ $r->laporan_keuangan }}</td>
              <td class="py-3 text-capitalize">{{ $r->status_lapor }}</td>
              <td class="py-3">
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
              <td class="pe-4 py-3 text-center">
                <form action="{{ route('admin.spt-badan.status', $r->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                  @csrf
                  <select name="status_pesanan" class="form-select form-select-sm" style="width:145px;">
                    @foreach(['Menunggu Proses','Diproses','Selesai'] as $s)
                    <option value="{{ $s }}" {{ $r->status_pesanan==$s?'selected':'' }}>{{ $s }}</option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn btn-sm btn-primary px-3">Update</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2.5rem;color:#d1d5db;display:block;margin-bottom:8px;"></i>
                Belum ada pengajuan masuk.
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
