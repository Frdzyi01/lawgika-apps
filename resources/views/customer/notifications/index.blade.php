@extends('layouts-customer.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <ion-icon name="notifications-outline" class="align-middle"></ion-icon> Semua Notifikasi Anda
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <ion-icon name="list-outline" class="align-middle"></ion-icon> Riwayat Transaksi Terbaru
            </h6>
            <span class="badge bg-primary">{{ $notifications->total() }} Notifikasi</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Ikon</th>
                            <th width="20%">Waktu</th>
                            <th width="25%">Jenis Layanan</th>
                            <th width="40%">Keterangan</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notif)
                        <tr>
                            <td class="text-center align-middle">
                                <div class="notify text-{{ $notif['color'] }}" style="font-size: 1.5rem;">
                                    <ion-icon name="{{ $notif['icon'] }}"></ion-icon>
                                </div>
                            </td>
                            <td class="align-middle">
                                <strong>{{ $notif['time']->format('d M Y, H:i') }}</strong><br>
                                <small class="text-muted">{{ $notif['time']->diffForHumans() }}</small>
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-{{ $notif['color'] }}">{{ $notif['title'] }}</span>
                            </td>
                            <td class="align-middle">
                                {{ $notif['desc'] }}
                            </td>
                            <td class="text-center align-middle">
                                <a href="{{ $notif['url'] }}" class="btn btn-sm btn-info text-white">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Anda belum memiliki riwayat transaksi atau notifikasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
