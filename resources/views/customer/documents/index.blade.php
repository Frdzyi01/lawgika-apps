@extends('layouts-customer.app')

@section('content')
<h4>Daftar Dokumen Saya</h4>
<div class="card mt-3">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Nama File</th>
                    <th>Tipe Dokumen</th>
                    <th>Order Terkait</th>
                    <th>Status</th>
                    <th>Tanggal Upload</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $doc)
                <tr>
                    <td>{{ $doc->original_name }}</td>
                    <td>{{ strtoupper($doc->type) }}</td>
                    <td>{{ $doc->order->order_number ?? '-' }}</td>
                    <td><span class="badge bg-{{ $doc->status == 'verified' ? 'success' : 'secondary' }}">{{ $doc->status }}</span></td>
                    <td>{{ $doc->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
