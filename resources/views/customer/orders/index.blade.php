@extends('layouts-customer.app')

@section('content')
<h4>{{ __('customer.orders.index.title') }}</h4>
<div class="card mt-3">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('customer.orders.index.order_number') }}</th>
                    <th>{{ __('customer.orders.index.service') }}</th>
                    <th>{{ __('customer.orders.index.status') }}</th>
                    <th>{{ __('customer.orders.index.payment_total') }}</th>
                    <th>{{ __('customer.orders.index.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->service->name ?? '-' }}</td>
                    <td><span class="badge bg-info">{{ $order->status }}</span></td>
                    <td>Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td><a href="{{ route('customer.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">{{ __('customer.orders.index.btn_view') }}</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection