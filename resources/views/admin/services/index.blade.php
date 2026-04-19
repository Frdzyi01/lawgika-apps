@extends('layouts-admin.admin')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h4>Daftar Jasa Layanan</h4>
</div>
<div class="card">
    <div class="card-body">
        <p class="text-muted">Manajemen jasa dapat ditambahkan di sini. Silakan kembangkan form CRUD jika diperlukan.</p>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Slug</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $s)
                <tr>
                    <td>{{ $s->name }}</td>
                    <td>{{ $s->slug }}</td>
                    <td>{{ $s->price }}</td>
                    <td>-</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
