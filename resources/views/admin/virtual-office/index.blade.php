@extends('layouts-admin.admin')

@section('title', 'Manajemen Virtual Office')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manajemen Virtual Office</h4>
        <small class="text-muted">Mengelola seluruh client Virtual Office yang sudah aktif</small>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('admin.orders.create', ['service' => 'virtual-office']) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <ion-icon name="add-outline"></ion-icon> Tambah Data
        </a>
        {{-- Filter Status --}}
        <form method="GET" class="d-flex gap-2 m-0">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><ion-icon name="search-outline"></ion-icon></span>
                <input type="text" name="search" class="form-control" placeholder="Cari Nama / PT..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
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
                        <th>Perusahaan & PIC</th>
                        <th>No WhatsApp</th>
                        <th>Paket VO</th>
                        <th>Masa Aktif</th>
                        <th>Status</th>
                        <th>Meeting</th>
                        <th>Podcast</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($virtualOffices as $vo)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-primary small">{{ $vo->order_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}</div>
                            <small class="text-muted">{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}</small>
                        </td>
                        <td>
                            <small>{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $vo->vo_package }}
                            </span>
                        </td>
                        <td>
                            @if($vo->tanggal_aktif && $vo->tanggal_expired)
                            <small class="d-block">Mulai: {{ \Carbon\Carbon::parse($vo->tanggal_aktif)->format('d M Y') }}</small>
                            <small class="d-block text-muted">Hingga: {{ \Carbon\Carbon::parse($vo->tanggal_expired)->format('d M Y') }}</small>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $vo->vo_status_color }} px-3 py-1 fw-semibold" style="color: {{ in_array($vo->vo_status_color, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
                                {{ $vo->vo_status }}
                            </span>
                        </td>
                        <td>
                            @if($vo->benefit_meeting)
                            <span class="text-success fw-bold">{{ $vo->benefit_meeting->remaining_minutes / 60 }} Jam</span>
                            @else
                            <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td>
                            @if($vo->benefit_podcast)
                            <span class="text-success fw-bold">{{ $vo->benefit_podcast->remaining_minutes / 60 }} Jam</span>
                            @else
                            <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                    class="btn btn-sm btn-info text-white btn-mail-notification d-flex align-items-center gap-1"
                                    data-id="{{ $vo->id }}"
                                    data-company="{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}"
                                    data-pic="{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}"
                                    data-phone="{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}"
                                    data-url="{{ route('admin.virtual-office.mail-notification.store', $vo->id) }}">
                                    📨 Surat Masuk
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-warning text-dark btn-guest-notification d-flex align-items-center gap-1"
                                    data-id="{{ $vo->id }}"
                                    data-company="{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}"
                                    data-pic="{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}"
                                    data-phone="{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}"
                                    data-url="{{ route('admin.virtual-office.guest-notification.store', $vo->id) }}">
                                    👥 Tamu Datang
                                </button>

                                {{-- Action Renew: aktif hanya saat H-30 hari atau Expired --}}
                                @if($vo->isRenewable())
                                    <a href="{{ route('admin.orders.create', ['service' => $vo->form_data['service'] ?? 'virtual-office', 'renew_order_id' => $vo->id]) }}"
                                       class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                       title="Perpanjang Layanan Virtual Office (H-30 / Expired)">
                                        🔄 Renew
                                    </a>
                                @else
                                    @php
                                        $voRenewMsg = !$vo->isValidated()
                                            ? 'Renew hanya tersedia setelah pesanan disetujui (Approved)'
                                            : 'Renew hanya dapat digunakan jika layanan H-30 atau sudah Expired (Saat ini sisa ' . ($vo->sisa_hari ?? 0) . ' hari)';
                                    @endphp
                                    <button type="button"
                                            class="btn btn-sm btn-secondary opacity-50 d-flex align-items-center gap-1"
                                            disabled
                                            title="{{ $voRenewMsg }}">
                                        🔄 Renew
                                    </button>
                                @endif

                                <a href="{{ route('admin.orders.show', $vo->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">Belum ada data Virtual Office.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $virtualOffices->appends(request()->query())->links() }}
</div>

@include('admin.partials.vo-notification-modals')
@endsection
