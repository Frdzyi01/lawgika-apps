@extends('layouts-admin.admin')

@section('title', 'Manajemen Berita - Lawgika Admin')

@section('content')
<!--start breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.home') }}"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Manajemen Berita</li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="row row-cols-1 row-cols-lg-3 row-cols-xxl-3 d-flex justify-content-center">
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-start gap-2">
                    <div>
                        <p class="mb-0 fs-6">Total Berita</p>
                    </div>
                    <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
                        <ion-icon name="newspaper-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalBerita }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">Total semua record</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-start gap-2">
                    <div>
                        <p class="mb-0 fs-6">Berita Terbit</p>
                    </div>
                    <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $published }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">Sudah di-publish</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-start gap-2">
                    <div>
                        <p class="mb-0 fs-6">Berita Draft/Tunda</p>
                    </div>
                    <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $draft }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-danger">Belum tayang</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4 mt-3">
    <h4 class="mb-0">Manajemen Berita & Artikel</h4>
    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <ion-icon name="add-outline"></ion-icon> Tambah Berita
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Daftar Berita</h6>
        </div>
        <div class="table-responsive mt-2">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Thumbnail</th>
                        <th width="25%">Judul</th>
                        <th width="15%">Kategori</th>
                        <th width="15%">Penulis</th>
                        <th width="15%">Tanggal Terbit</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($beritas as $item)
                    <tr>
                        <td>{{ $beritas->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="product-box border bg-light rounded d-flex justify-content-center align-items-center" style="width: 80px; height: 50px;">
                                @if($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->judul }}" style="width:80px;height:50px;object-fit:cover;border-radius:4px;">
                                @else
                                    <ion-icon name="image-outline" class="fs-4 text-primary"></ion-icon>
                                @endif
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0 text-truncate" style="max-width: 250px;" title="{{ $item->judul }}">{{ $item->judul }}</h6>
                            <small class="text-muted">{{ $item->slug }}</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $item->kategori }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $item->penulis }}</div>
                            <small class="text-muted">{{ $item->jabatan_penulis }}</small>
                        </td>
                        <td>
                            @if($item->published_at)
                                @if($item->published_at <= now())
                                    <span class="badge bg-success mb-1">Published</span><br>
                                @else
                                    <span class="badge bg-warning text-dark mb-1">Scheduled</span><br>
                                @endif
                                {{ $item->published_at->translatedFormat('d M Y, H:i') }}
                            @else
                                <span class="badge bg-danger">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="{{ route('admin.berita.edit', $item->id) }}" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">
                                    <ion-icon name="pencil-outline"></ion-icon>
                                </a>
                                <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data berita.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            {{ $beritas->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection