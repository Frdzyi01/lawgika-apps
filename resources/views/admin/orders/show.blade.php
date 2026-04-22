@extends('layouts-admin.admin')

@section('title', 'Detail Pesanan')

@section('content')
<style>
    html.dark-theme .order-detail-table td {
        color: #f1f2f4 !important;
    }
    html:not(.dark-theme) .order-detail-table td {
        color: #4f5d70 !important;
    }
</style>

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
                <table class="table table-borderless text-body order-detail-table">
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

        {{-- PT Perorangan Professional: Company & Director Data --}}
        @if(!empty($order->form_data['director_name']))
        @php
            $ptProLabels = [
                'director_name'       => 'Nama Direktur',
                'director_phone'      => 'No. HP Direktur',
                'company_name'        => 'Nama Perusahaan',
                'company_email'       => 'Email Perusahaan',
                'pic_name'            => 'Nama PIC',
                'pic_phone'           => 'No. HP PIC',
                'business_field'      => 'Bidang Usaha',
                'operational_address' => 'Alamat Operasional',
            ];
        @endphp
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #4e0516 !important;">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0"><i class="fa fa-building me-1" style="color:#4e0516"></i>Data PT Perorangan Professional</h6>
                <span class="badge" style="background:#4e0516;font-size:.72rem;">Professional</span>
            </div>
            <div class="card-body px-4 pb-4">
                <table class="table table-borderless text-body order-detail-table">
                    @foreach($ptProLabels as $key => $label)
                        @if(!empty($order->form_data[$key]))
                        <tr>
                            <td width="40%" class="text-muted">{{ $label }}</td>
                            <td class="fw-semibold">{{ $order->form_data[$key] }}</td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
        @endif

        {{-- Payment Proof --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Bukti Pembayaran</h6>
                <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
            </div>
            <div class="card-body px-4 pb-4">
                @if($order->payment_proof)
                    <div class="mb-4">
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-eye me-1"></i> Lihat Bukti Pembayaran
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-4">Belum ada bukti pembayaran yang diunggah.</p>
                @endif

                <form action="{{ route('admin.orders.payment-status', $order->id) }}" method="POST" class="d-flex align-items-center gap-2">
                    @csrf
                    <label class="fw-semibold mb-0" style="white-space: nowrap">Ubah Status:</label>
                    <select name="payment_status" class="form-select form-select-sm" style="max-width: 200px">
                        @foreach(App\Models\Order::PAYMENT_STATUSES as $key => $data)
                            <option value="{{ $key }}" {{ $order->payment_status == $key ? 'selected' : '' }}>{{ $data['label'] }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </div>
        </div>

        {{-- Documents --}}
        @if($order->documents->count())
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-2 px-4 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0">Dokumen yang Diunggah</h6>
                <span class="badge bg-secondary">{{ $order->documents->count() }} file</span>
            </div>
            <div class="card-body px-4 pb-4">

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <div class="d-flex flex-column gap-3">
                @foreach($order->documents as $doc)
                    @php
                        $statusColor = match($doc->status) {
                            'verified' => 'success',
                            'rejected' => 'danger',
                            default    => 'warning',
                        };
                        $statusIcon = match($doc->status) {
                            'verified' => 'fa-circle-check',
                            'rejected' => 'fa-circle-xmark',
                            default    => 'fa-clock',
                        };
                        $typeColors = ['ktp' => 'primary', 'npwp' => 'info', 'other' => 'secondary'];
                        $typeColor  = $typeColors[$doc->type] ?? 'secondary';
                    @endphp

                    <div class="border border-{{ $statusColor }} rounded-3 p-3 bg-{{ $statusColor }} bg-opacity-10">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">

                            {{-- Left: type badge + filename --}}
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="badge bg-{{ $typeColor }} text-uppercase" style="font-size:.7rem; letter-spacing:.5px">
                                    {{ $doc->type }}
                                </span>
                                <span class="fw-semibold" style="font-size:.9rem">{{ $doc->original_name }}</span>
                                {{-- Preview link --}}
                                <a href="{{ asset('storage/' . $doc->path) }}" target="_blank"
                                   class="text-muted" style="font-size:.8rem" title="Lihat file">
                                    <i class="fa fa-eye"></i> Lihat
                                </a>
                            </div>

                            {{-- Right: status badge + action buttons --}}
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $statusColor }}" style="font-size:.78rem">
                                    <i class="fa {{ $statusIcon }} me-1"></i>
                                    {{ ucfirst($doc->status) }}
                                </span>

                                {{-- Approve --}}
                                @if($doc->status !== 'verified')
                                <form action="{{ route('admin.documents.status', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="verified">
                                    <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('Approve dokumen ini?')"
                                            title="Approve">
                                        <i class="fa fa-check me-1"></i> Approve
                                    </button>
                                </form>
                                @endif

                                {{-- Reject --}}
                                @if($doc->status !== 'rejected')
                                <form action="{{ route('admin.documents.status', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Reject dokumen ini?')"
                                            title="Reject">
                                        <i class="fa fa-xmark me-1"></i> Reject
                                    </button>
                                </form>
                                @endif

                                {{-- Reset to pending (if already approved/rejected) --}}
                                @if(in_array($doc->status, ['verified', 'rejected']))
                                <form action="{{ route('admin.documents.status', $doc->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="btn btn-outline-secondary btn-sm"
                                            title="Reset ke Pending">
                                        <i class="fa fa-rotate-left"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>

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
