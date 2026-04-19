@extends('layouts-customer.app')

@section('content')
<h4>Riwayat Pesanan Saya</h4>
<div class="card mt-3">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Jasa</th>
                    <th>Status</th>
                    <th>Total Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->service->name ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $order->status }}</span></td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Lihat Detail & Upload</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection