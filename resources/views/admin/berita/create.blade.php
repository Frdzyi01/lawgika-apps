@extends('layouts.admin')

@section('title', 'Tambah Berita - Lawgika Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Berita</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><ion-icon name="home-outline"></ion-icon></a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.berita.index') }}">Manajemen Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card radius-10 w-100">
    <div class="card-body">
        <h5 class="mb-4">Tambah Berita Baru</h5>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong class="font-bold">Ada kesalahan!</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" required placeholder="Masukkan judul berita yang menarik">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Excerpt / Ringkasan Pendek</label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Akan ditampilkan pada card di halaman utama (opsional)">{{ old('excerpt') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Konten Artikel</label>
                        <textarea name="konten" id="konten" class="form-control" rows="15" placeholder="Isi detail berita...">{{ old('konten') }}</textarea>
                        {{-- Note: TinyMCE/CKEditor bisa di-mount ke ID konten ini --}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="mb-3">Pengaturan Berita</h6>
                            
                            <div class="mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori" class="form-select" required>
                                    <option value="">Pilih Kategori...</option>
                                    <option value="Berita Hukum" {{ old('kategori') == 'Berita Hukum' ? 'selected' : '' }}>Berita Hukum</option>
                                    <option value="Bisnis" {{ old('kategori') == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                                    <option value="Update Perizinan" {{ old('kategori') == 'Update Perizinan' ? 'selected' : '' }}>Update Perizinan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            <hr>
                            <h6 class="mb-3 mt-3">Author</h6>

                            <div class="mb-3">
                                <label class="form-label">Nama Penulis</label>
                                <input type="text" name="penulis" class="form-control" value="{{ old('penulis', Auth::user()->name) }}" placeholder="Default: Anda">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" name="jabatan_penulis" class="form-control" value="{{ old('jabatan_penulis', 'Admin') }}" required placeholder="Contoh: Co, Founder">
                            </div>

                            <hr>
                            <h6 class="mb-3 mt-3">Thumbnail Image</h6>

                            <div class="mb-3">
                                <label class="form-label">Upload Gambar <span class="text-danger">*</span></label>
                                <input type="file" name="gambar" class="form-control mb-2" accept="image/png, image/jpeg, image/jpg" required>
                                <small class="text-muted d-block">Akan otomatis di-resize ke: <strong>450x294 px</strong>. Maksimal 2MB.</small>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <ion-icon name="save-outline"></ion-icon> Simpan Berita
                        </button>
                        <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
