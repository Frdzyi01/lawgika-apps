@extends('layouts-customer.app')

@section('title', 'Riwayat SPT Badan')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Riwayat Pengajuan SPT Badan</h4>
    </div>
    
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">No.</th>
                            <th class="py-3">Nama Perusahaan</th>
                            <th class="py-3">Jenis Usaha</th>
                            <th class="py-3">Tahun Pajak</th>
                            <th class="py-3">Laporan Keuangan</th>
                            <th class="pe-4 py-3">Status Pesanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $index => $request)
                        <tr>
                            <td class="ps-4 py-3"><small class="text-muted">{{ $index + 1 }}</small></td>
                            <td class="py-3 fw-semibold">{{ $request->perusahaan }}</td>
                            <td class="py-3">{{ $request->jenis_usaha }}</td>
                            <td class="py-3">{{ $request->tahun_pajak }}</td>
                            <td class="py-3">{{ ucfirst($request->laporan_keuangan) }}</td>
                            <td class="pe-4 py-3">
                                @php
                                    $badge = match($request->status_pesanan) {
                                        'Pending'  => 'warning',
                                        'Diproses' => 'info',
                                        'Selesai'  => 'success',
                                        default    => 'secondary',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badge }} bg-opacity-20 text-{{ $badge }} border border-{{ $badge }}">
                                    {{ $request->status_pesanan }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <ion-icon name="document-text-outline" style="font-size:3rem; color:#d1d5db;"></ion-icon>
                                <p class="mt-2 mb-0">Belum ada riwayat pengajuan SPT Badan.</p>
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
