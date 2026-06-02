@extends('layouts-customer.app')
@section('title', '{{ __('customer.spt.index.title') }}')
@section('content')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
      <h4 class="mb-1 fw-bold">{{ __('customer.spt.index.header') }}</h4>
      <p class="text-muted mb-0" style="font-size:.88rem;">{{ __('customer.spt.index.desc') }}</p>
    </div>
    <a href="/pelaporan-spt-tahunan#formPengajuan" class="btn btn-sm btn-danger px-3 fw-semibold">
      <i class="bi bi-plus-lg me-1"></i> {{ __('customer.spt.index.btn_new') }}
    </a>
  </div>

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="ps-4 py-3">{{ __('customer.spt.index.no') }}</th>
              <th class="py-3">{{ __('customer.spt.index.subject') }}</th>
              <th class="py-3">{{ __('customer.spt.index.name') }}</th>
              <th class="py-3">{{ __('customer.spt.index.year') }}</th>
              <th class="py-3">{{ __('customer.spt.index.report') }}</th>
              <th class="pe-4 py-3">{{ __('customer.spt.index.status') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($requests as $i => $r)
            <tr>
              <td class="ps-4 py-3"><small class="text-muted">{{ $i + 1 }}</small></td>
              <td class="py-3">
                @if($r->subject_type === 'pribadi')
                  <span class="badge bg-info bg-opacity-15 text-info border border-info" style="font-size:.78rem;">{{ __('customer.spt.index.subject_personal') }}</span>
                @else
                  <span class="badge bg-primary bg-opacity-15 text-primary border border-primary" style="font-size:.78rem;">{{ __('customer.spt.index.subject_company') }}</span>
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
                <span class="badge bg-{{ $badge }} px-3 py-1" style="color: {{ in_array($badge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
                  {{ $r->status_pesanan }}
                </span>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="6" class="text-center py-5 text-muted">
                <i class="bi bi-file-earmark-text" style="font-size:2.5rem;color:#d1d5db;display:block;margin-bottom:8px;"></i>
                {{ __('customer.spt.index.no_history') }}<br>
                <a href="/pelaporan-spt-tahunan" class="text-danger fw-semibold mt-2 d-inline-block">{{ __('customer.spt.index.file_now') }}</a>
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
