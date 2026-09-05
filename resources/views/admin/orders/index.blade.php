@extends('layouts-admin.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manajemen Pesanan</h4>
        <small class="text-muted">Daftar semua pesanan masuk dari pelanggan</small>
    </div>
    
    <div class="d-flex gap-2 align-items-center">
        {{-- Filter Status --}}
        <form method="GET" class="d-flex gap-2 m-0">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                @foreach(['pending','approved','processing','rejected','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="service" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua Layanan</option>
                <option value="virtual-office" {{ request('service') == 'virtual-office' ? 'selected' : '' }}>Virtual Office</option>
                <option value="pendirian-pt" {{ request('service') == 'pendirian-pt' ? 'selected' : '' }}>Pendirian PT</option>
                <option value="pt-perorangan" {{ request('service') == 'pt-perorangan' ? 'selected' : '' }}>PT Perorangan</option>
            </select>
        </form>

        <a href="{{ route('admin.orders.create') }}" class="btn btn-sm btn-primary shadow-sm text-nowrap">
            <ion-icon name="add-circle-outline" class="align-middle"></ion-icon> Buat Pesanan Baru
        </a>
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
                        <th>Customer</th>
                        <th>Layanan</th>
                        <th>Paket</th>
                        <th>Status</th>
                        <th>Podcast</th>
                        <th>Meeting</th>
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
                                $pkgSlug  = $order->form_data['package'] ?? '';
                                $pkgLabel = \App\Http\Controllers\UniversalOrderController::$packages[$pkgSlug] ?? ucfirst($pkgSlug) ?: '—';
                            @endphp
                            <span class="badge bg-light text-dark border">
                                {{ $pkgLabel }}
                            </span>
                        </td>
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
                            <span class="badge bg-{{ $badge }} px-3 py-1 fw-semibold" style="color: {{ in_array($badge, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @php $podcastB = $order->roomBenefits->where('type','podcast')->first(); @endphp
                            @if($podcastB && $podcastB->is_active)
                                <span class="text-success fw-bold">{{ $podcastB->remaining_minutes / 60 }} Jam</span>
                            @else
                                <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td>
                            @php $meetingB = $order->roomBenefits->where('type','meeting')->first(); @endphp
                            @if($meetingB && $meetingB->is_active)
                                <span class="text-success fw-bold">{{ $meetingB->remaining_minutes / 60 }} Jam</span>
                            @else
                                <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td>
                            @if($order->total_price > 0)
                                @php $ppnData = \App\Helpers\PpnHelper::calculate($order->total_price); @endphp
                                Rp {{ number_format($ppnData['grand_total'], 0, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <small>{{ $order->created_at->format('d M Y') }}</small><br>
                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary px-2" title="Detail Order">
                                    Detail
                                </a>
                                @if($order->qr_token)
                                    <a href="{{ url('/qr/' . $order->qr_token) }}" target="_blank" class="btn btn-sm btn-outline-dark d-inline-flex align-items-center justify-content-center px-2 py-1" title="Buka Halaman QR Layanan" style="height: 31px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                            <path d="M2 2h2v2H2z"/>
                                            <path d="M6 0v6H0V0zM1 1v4h4V1zm1 7h2v2H2z"/>
                                            <path d="M6 7v6H0V7zm-5 1v4h4V8zm-4 3h2v2h-2z"/>
                                            <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                                            <path d="M16 0v6h-6V0zm-1 1v4h-4V1zm-4 7h2v2h-2z"/>
                                            <path d="M16 7v6h-6V7zm-1 1v4h-4V8zm-2 3h2v2h-2z"/>
                                            <path d="M14 10v6h-4v-6zm-3 1v4h2v-4zm-8 2h2v2H3zm2 2h2v-2H5zm4-2h2v2H9zm2 2h2v-2h-2zm-4 0h2v2H7zm4-6h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2z"/>
                                            <path d="M10 9h2v2h-2z"/>
                                        </svg>
                                        <span>QR</span>
                                    </a>
                                    <a href="{{ route('admin.orders.download-qr', $order->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center justify-content-center px-2 py-1" title="Download QR Code (SVG)" style="height: 31px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16">
                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                                        </svg>
                                    </a>
                                @else
                                    <form action="{{ route('admin.orders.generate-qr', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center px-2 py-1" title="Generate QR Code Layanan" style="height: 31px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="currentColor" viewBox="0 0 16 16" class="me-1">
                                                <path d="M2 2h2v2H2z"/>
                                                <path d="M6 0v6H0V0zM1 1v4h4V1zm1 7h2v2H2z"/>
                                                <path d="M6 7v6H0V7zm-5 1v4h4V8zm-4 3h2v2h-2z"/>
                                                <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                                                <path d="M16 0v6h-6V0zm-1 1v4h-4V1zm-4 7h2v2h-2z"/>
                                                <path d="M16 7v6h-6V7zm-1 1v4h-4V8zm-2 3h2v2h-2z"/>
                                                <path d="M14 10v6h-4v-6zm-3 1v4h2v-4zm-8 2h2v2H3zm2 2h2v-2H5zm4-2h2v2H9zm2 2h2v-2h-2zm-4 0h2v2H7zm4-6h2v2h-2zm2 2h2v2h-2zm-2 2h2v2h-2z"/>
                                                <path d="M10 9h2v2h-2z"/>
                                            </svg>
                                            <span>+ QR</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">Belum ada pesanan masuk.</td>
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
