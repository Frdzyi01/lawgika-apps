@extends('layouts-admin.admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-1"><ion-icon name="speedometer-outline" class="align-middle"></ion-icon> Dashboard CRM Lawgika</h4>
        <p class="text-muted">Ringkasan operasional harian dan manajemen client.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Top Cards --}}
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-info">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Master Client</p>
                        <h4 class="my-1 text-info">{{ $totalCustomers }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Order Pending</p>
                        <h4 class="my-1 text-warning">{{ $pendingOrders }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Surat Masuk (Unread)</p>
                        <h4 class="my-1 text-danger">{{ $pendingSurat }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                        <ion-icon name="mail-unread-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card radius-10 border-start border-0 border-4 border-success">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Aktif / Sedang Check-in</p>
                        <h4 class="my-1 text-success">{{ $activeCheckins }}</h4>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                        <ion-icon name="log-in-outline"></ion-icon>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0">Pesanan Masuk Terbaru (5 Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No Order</th>
                                <th>Client</th>
                                <th>Layanan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><span class="fw-semibold text-primary small">{{ $order->order_number }}</span></td>
                                <td>
                                    {{ $order->user->name ?? '—' }}<br>
                                    <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $order->service_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted">Belum ada pesanan terbaru.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0">Tindakan Menunggu (Pending)</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <a href="{{ url('admin/meeting-room') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="business-outline" class="align-middle me-1 text-primary"></ion-icon> Meeting Room
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $pendingMeetings }}</span>
                    </a>
                    <a href="{{ url('admin/podcast-room') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="mic-outline" class="align-middle me-1 text-info"></ion-icon> Podcast Room
                        </div>
                        <span class="badge bg-info text-dark rounded-pill">{{ $pendingPodcasts }}</span>
                    </a>
                    <a href="{{ route('admin.surat-menyurat.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="mail-unread-outline" class="align-middle me-1 text-danger"></ion-icon> Surat Menyurat
                        </div>
                        <span class="badge bg-danger rounded-pill">{{ $pendingSurat }}</span>
                    </a>
                    <a href="{{ route('admin.orders.index', ['status' => 'waiting_verification']) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <div>
                            <ion-icon name="document-text-outline" class="align-middle me-1 text-warning"></ion-icon> Verifikasi Order
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $pendingOrders }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
