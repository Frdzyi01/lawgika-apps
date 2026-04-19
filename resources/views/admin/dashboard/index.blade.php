@extends('layouts-admin.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <h4>Admin Dashboard</h4>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Pesanan Pending</h5>
                        <h3>{{ $pendingOrders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Total Customer</h5>
                        <h3>{{ $totalCustomers }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Total Jasa</h5>
                        <h3>{{ $totalServices }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
