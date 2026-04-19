@extends('layouts-customer.app')

@section('content')
<h4>Detail Pesanan: {{ $order->order_number }}</h4>
<div class="row mt-3">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-body">
                <h6>Status: {{ strtoupper($order->status) }}</h6>
                <hr>
                <p><strong>Jasa:</strong> {{ $order->service->name }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                <p><strong>Form Submit Anda:</strong></p>
                <pre>{{ json_encode($order->form_data, JSON_PRETTY_PRINT) }}</pre>
                @if($order->admin_notes)
                <div class="alert alert-warning mt-2">
                    <strong>Catatan Admin:</strong> {{ $order->admin_notes }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h6>Upload Dokumen Persyaratan</h6>
                <form action="{{ route('customer.documents.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="mb-3">
                        <label>Jenis Dokumen</label>
                        <select name="type" class="form-control" required>
                            <option value="">Pilih...</option>
                            <option value="ktp">KTP</option>
                            <option value="npwp">NPWP</option>
                            <option value="company_nib">NIB Perusahaan</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>File Dokumen (PDF/JPG/PNG)</label>
                        <input type="file" name="document" class="form-control" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Upload Dokumen</button>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6>Dokumen Diunggah</h6>
                <ul class="list-group">
                    @foreach($order->documents as $doc)
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ $doc->type }} - {{ $doc->original_name }}</span>
                            <span class="badge bg-{{ $doc->status == 'verified' ? 'success' : 'secondary' }}">{{ $doc->status }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
