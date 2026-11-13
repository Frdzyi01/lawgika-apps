@extends('layouts-admin.admin')

@section('title', 'Edit Transaksi & Pesanan')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary mb-2">← Kembali ke Detail Pesanan</a>
    <h4 class="mb-0">Edit Transaksi &amp; Pesanan (Admin 3 / SPV)</h4>
    <small class="text-muted">Order: {{ $order->order_number }} | Client: {{ $order->user->company_name ?? $order->user->name ?? '-' }}</small>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor Invoice <span class="text-danger">*</span></label>
                    <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number', $order->invoice_number) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Transaksi <span class="text-danger">*</span></label>
                    <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', $order->effective_transaction_date->format('Y-m-d')) }}" required>
                    <small class="text-muted">Jika pesanan sudah disetujui, perubahan tanggal transaksi otomatis menyinkronkan tanggal mulai &amp; berakhir benefit.</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Layanan <span class="text-danger">*</span></label>
                    <select name="service" class="form-select" id="edit_service_select" required>
                        @foreach($services as $key => $svc)
                            <option value="{{ $key }}" {{ old('service', $order->form_data['service'] ?? '') === $key ? 'selected' : '' }}>{{ $svc['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Paket Layanan <span class="text-danger">*</span></label>
                    <select name="package" class="form-select" id="edit_package_select" required>
                        @foreach($packages as $pKey => $pLbl)
                            <option value="{{ $pKey }}" {{ old('package', $order->form_data['package'] ?? '') === $pKey ? 'selected' : '' }}>{{ $pLbl }} ({{ $pKey }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Total Harga (DPP / Nominal Dasar) <span class="text-danger">*</span></label>
                    <input type="number" name="total_price" class="form-control" value="{{ old('total_price', (int) $order->total_price) }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <select name="payment_method" class="form-select">
                        @foreach($paymentMethods as $pm)
                            <option value="{{ $pm }}" {{ old('payment_method', $order->payment_method) === $pm ? 'selected' : '' }}>{{ $pm }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pembayaran <span class="text-danger">*</span></label>
                    <select name="payment_status" class="form-select" required>
                        @foreach(\App\Models\Order::ALL_PAYMENT_STATUSES as $psKey => $psInfo)
                            <option value="{{ $psKey }}" {{ old('payment_status', $order->payment_status) === $psKey ? 'selected' : '' }}>{{ $psInfo['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Status Pesanan <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(['pending_approval' => 'Pending Approval', 'approved' => 'Approved (Aktifkan Benefit Sesuai Tgl Transaksi)', 'rejected' => 'Ditolak (Rejected)', 'draft' => 'Dokumen Belum Lengkap', 'completed' => 'Dokumen Terverifikasi', 'cancelled' => 'Dibatalkan'] as $stKey => $stLbl)
                            <option value="{{ $stKey }}" {{ old('status', $order->status) === $stKey ? 'selected' : '' }}>{{ $stLbl }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Upload Ulang Bukti Pembayaran (Opsional)</label>
                    <input type="file" name="payment_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Catatan Transaksi</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $order->notes) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Catatan Admin</label>
                    <textarea name="admin_notes" class="form-control" rows="2">{{ old('admin_notes', $order->admin_notes) }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold text-primary">Alasan Perubahan (Tercatat di Audit Trail) <span class="text-danger">*</span></label>
                    <input type="text" name="edit_reason" class="form-control" value="{{ old('edit_reason') }}" placeholder="Contoh: Koreksi tanggal transaksi atau nominal pembayaran" required>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary px-4">Simpan Perubahan &amp; Catat Audit Log</button>
            </div>
        </form>
    </div>
</div>
@endsection

