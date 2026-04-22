@extends('layouts-admin.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">Semua Pengajuan SPT Badan</h4>
    </div>
    
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Customer</th>
                            <th class="py-3">Perusahaan</th>
                            <th class="py-3">Tahun Pajak</th>
                            <th class="py-3">Status Laporan</th>
                            <th class="py-3">Status Pesanan</th>
                            <th class="pe-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $request)
                        <tr>
                            <td class="ps-4 py-3 fw-bold">{{ $request->user->name ?? $request->nama }}</td>
                            <td class="py-3">{{ $request->perusahaan }}</td>
                            <td class="py-3">{{ $request->tahun_pajak }}</td>
                            <td class="py-3 text-capitalize">{{ $request->status_lapor }}</td>
                            <td class="py-3">
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
                            <td class="pe-4 py-3 text-center">
                                <form action="{{ route('admin.spt-badan.status', $request->id) }}" method="POST" class="d-flex gap-2 justify-content-center">
                                    @csrf
                                    <select name="status_pesanan" class="form-select form-select-sm" style="width: 120px;">
                                        <option value="Pending" {{ $request->status_pesanan == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Diproses" {{ $request->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ $request->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary px-3">Update</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <ion-icon name="folder-open-outline" style="font-size:3rem; color:#d1d5db;"></ion-icon>
                                <p class="mt-2 mb-0">Belum ada pengajuan masuk.</p>
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
