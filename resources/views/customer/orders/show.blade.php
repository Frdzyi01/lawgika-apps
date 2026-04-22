@extends('layouts-customer.app')

@section('content')
<style>
    .order-detail-table td {
        color: inherit !important;
    }
</style>

<h4>Detail Pesanan: {{ $order->order_number }}</h4>
<div class="row mt-3">
    <div class="col-md-7">
        <div class="card mb-3">
            <div class="card-body">
                <h6>Status: {{ strtoupper($order->status) }}</h6>
                <hr>
                <p><strong>Jasa:</strong> {{ $order->service?->name ?? $order->service_name ?? '-' }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                @if($order->form_data)
                @php
                    $labels = [
                        'service'              => 'Layanan',
                        'package'              => 'Paket',
                        'phone'                => 'No. HP / WhatsApp',
                        'name'                 => 'Nama',
                        'email'                => 'Email',
                        'notes'                => 'Catatan',
                        'director_name'        => 'Nama Direktur',
                        'director_phone'       => 'No. HP Direktur',
                        'company_name'         => 'Nama Perusahaan',
                        'pic_name'             => 'Nama PIC',
                        'pic_phone'            => 'No. HP PIC',
                        'company_email'        => 'Email Perusahaan',
                        'operational_address'  => 'Alamat Operasional',
                        'business_field'       => 'Bidang Usaha',
                    ];
                    $internalKeys = ['service', 'package'];
                    $ptProKeys    = ['director_name','director_phone','company_name','pic_name','pic_phone','company_email','operational_address','business_field'];
                    $isPtPro      = !empty($order->form_data['director_name']);
                @endphp

                {{-- General info table (excludes internal & PT-Pro-only keys) --}}
                <p><strong>Detail Pesanan:</strong></p>
                <table class="table table-sm table-bordered text-body order-detail-table" style="font-size:.88rem;">
                    @foreach((array)$order->form_data as $key => $val)
                        @if(!empty($val) && !in_array($key, $internalKeys) && !($isPtPro && in_array($key, $ptProKeys)))
                        <tr>
                            <td class="fw-semibold" style="white-space:nowrap; width:40%">
                                {{ $labels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                            </td>
                            <td>{{ ucfirst(str_replace('-', ' ', $val)) }}</td>
                        </tr>
                        @endif
                    @endforeach
                </table>

                {{-- PT Perorangan Professional: dedicated company/director block --}}
                @if($isPtPro)
                <div class="card border-0 bg-light mt-3 mb-2">
                    <div class="card-body py-3">
                        <h6 class="fw-bold mb-3" style="font-size:.92rem;">
                            <i class="fa fa-building me-1"></i> Data Perusahaan &amp; Direktur
                        </h6>
                        <table class="table table-sm table-bordered text-body order-detail-table mb-0" style="font-size:.88rem;">
                            @foreach($ptProKeys as $key)
                                @if(!empty($order->form_data[$key]))
                                <tr>
                                    <td class="fw-semibold" style="white-space:nowrap; width:40%">
                                        {{ $labels[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                                    </td>
                                    <td>{{ $order->form_data[$key] }}</td>
                                </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif
                @endif
                @if($order->admin_notes)
                <div class="alert alert-warning mt-2">
                    <strong>Catatan Admin:</strong> {{ $order->admin_notes }}
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Pembayaran</h6>
                <div class="mb-3">
                    <strong>Status Pembayaran:</strong> 
                    <span class="badge bg-{{ $order->payment_status_color }}">{{ $order->payment_status_label }}</span>
                </div>
                
                @if($order->payment_proof)
                    <div class="mb-3">
                        <strong>Bukti Pembayaran Tersimpan:</strong> <br>
                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="fa fa-eye"></i> Lihat Bukti Pembayaran
                        </a>
                    </div>
                @endif

                @if(!in_array($order->payment_status, ['verified']))
                    <hr>
                    <form action="{{ route('customer.orders.payment-proof', $order->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{{ $order->payment_proof ? 'Upload Bukti Pembayaran Baru (PDF/JPG/PNG)' : 'Upload Bukti Pembayaran (PDF/JPG/PNG)' }}</label>
                            <input type="file" name="payment_proof" class="form-control" required>
                            <small class="text-muted">Maksimal 5MB.</small>
                        </div>
                        <button class="btn btn-primary" type="submit">Kirim Bukti Pembayaran</button>
                    </form>
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
