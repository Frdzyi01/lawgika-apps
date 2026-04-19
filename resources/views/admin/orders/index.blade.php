@extends('layouts-admin.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manajemen Pesanan</h4>
        <small class="text-muted">Daftar semua pesanan masuk dari pelanggan</small>
    </div>
    {{-- Filter Status --}}
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(['pending','approved','processing','rejected','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </form>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No Order</th>
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-primary small">{{ $order->order_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name ?? '—' }}</div>
                            <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                        </td>
                        <td>{{ $order->service_name ?? ($order->service->name ?? '—') }}</td>
                        <td>
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
                            <span class="badge bg-{{ $badge }} bg-opacity-20 text-{{ $badge }} border border-{{ $badge }} fw-semibold">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($order->total_price > 0)
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $order->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">Belum ada pesanan masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $orders->appends(request()->query())->links() }}
</div>
@endsection
