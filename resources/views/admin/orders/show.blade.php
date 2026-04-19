@extends('layouts-admin.admin')

@section('title', 'Detail Pesanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
        &larr; Kembali
    </a>
    <h4 class="mb-0">Detail Pesanan</h4>
    <small class="text-muted">{{ $order->order_number }}</small>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">
    {{-- Left: Order Info --}}
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Informasi Pesanan</h6>
            </div>
            <div class="card-body px-4">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%" class="text-muted">No. Pesanan</td>
                        <td class="fw-semibold">{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Layanan</td>
                        <td class="fw-semibold">{{ $order->service_name ?? ($order->service->name ?? '—') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Customer</td>
                        <td>{{ $order->user->name ?? '—' }}<br><small class="text-muted">{{ $order->user->email ?? '' }}</small><br><small class="text-muted">{{ $order->user->phone ?? '' }}</small></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal</td>
                        <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    @if($order->notes)
                    <tr>
                        <td class="text-muted">Catatan Customer</td>
                        <td>{{ $order->notes }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Documents --}}
        @if($order->documents->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Dokumen yang Diunggah</h6>
            </div>
            <div class="card-body px-4">
                <ul class="list-group list-group-flush">
                    @foreach($order->documents as $doc)
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-secondary me-2">{{ strtoupper($doc->type) }}</span>
                            {{ $doc->original_name }}
                        </div>
                        <span class="badge bg-{{ $doc->status == 'verified' ? 'success' : 'warning' }}">{{ $doc->status }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
    </div>

    {{-- Right: Update Status --}}
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4">
                <h6 class="fw-bold mb-0">Update Status</h6>
            </div>
            <div class="card-body px-4">
                @php
                    $badge = match($order->status) {
                        'pending'    => 'warning',
                        'approved'   => 'success',
                        'processing' => 'info',
                        'rejected'   => 'danger',
                        'completed'  => 'primary',
                        'cancelled'  => 'secondary',
                        default      => 'secondary',
                    };
                @endphp
                <p>Status sekarang: <span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span></p>

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Ubah Status</label>
                        <select name="status" class="form-select">
                            @foreach(['pending', 'approved', 'processing', 'rejected', 'completed', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Catatan Admin (opsional)</label>
                        <textarea name="admin_notes" class="form-control" rows="3" placeholder="Pesan untuk customer...">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
