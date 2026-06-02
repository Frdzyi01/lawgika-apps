@extends('layouts-customer.app')

@section('content')
<h4>{{ __('customer.documents.title') }}</h4>
<div class="card mt-3">
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('customer.documents.filename') }}</th>
                    <th>{{ __('customer.documents.type') }}</th>
                    <th>{{ __('customer.documents.related_order') }}</th>
                    <th>{{ __('customer.documents.status') }}</th>
                    <th>{{ __('customer.documents.upload_date') }}</th>
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
