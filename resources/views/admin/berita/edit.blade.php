@extends('layouts.admin')

@section('title', 'Edit Berita - Lawgika Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Berita</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}"><ion-icon name="home-outline"></ion-icon></a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.berita.index') }}">Manajemen Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit: {{ Str::limit($berita->judul, 20) }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card radius-10 w-100">
    <div class="card-body">
        <h5 class="mb-4">Edit Berita</h5>
        
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

        <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" name="judul" class="form-control" value="{{ old('judul', $berita->judul) }}" required placeholder="Masukkan judul berita yang menarik">
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Excerpt / Ringkasan Pendek</label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Akan ditampilkan pada card di halaman utama (opsional)">{{ old('excerpt', $berita->excerpt) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">Konten Artikel</label>
                        <textarea name="konten" id="konten" class="form-control" rows="15" placeholder="Isi detail berita...">{{ old('konten', $berita->konten) }}</textarea>
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
                                    <option value="Berita Hukum" {{ old('kategori', $berita->kategori) == 'Berita Hukum' ? 'selected' : '' }}>Berita Hukum</option>
                                    <option value="Bisnis" {{ old('kategori', $berita->kategori) == 'Bisnis' ? 'selected' : '' }}>Bisnis</option>
                                    <option value="Update Perizinan" {{ old('kategori', $berita->kategori) == 'Update Perizinan' ? 'selected' : '' }}>Update Perizinan</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Terbit <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', $berita->published_at ? $berita->published_at->format('Y-m-d\TH:i') : null) }}" required>
                            </div>

                            <hr>
                            <h6 class="mb-3 mt-3">Author</h6>

                            <div class="mb-3">
                                <label class="form-label">Nama Penulis</label>
                                <input type="text" name="penulis" class="form-control" value="{{ old('penulis', $berita->penulis) }}" placeholder="Default: Anda">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <input type="text" name="jabatan_penulis" class="form-control" value="{{ old('jabatan_penulis', $berita->jabatan_penulis) }}" required placeholder="Contoh: Co, Founder">
                            </div>

                            <hr>
                            <h6 class="mb-3 mt-3">Thumbnail Image</h6>

                            @if($berita->gambar)
                            <div class="mb-3">
                                <label class="form-label">Gambar Saat Ini:</label>
                                <img src="{{ asset('storage/'.$berita->gambar) }}" class="img-fluid rounded mb-2 border d-block w-100" style="max-height: 200px; object-fit: cover;" alt="Thumbnail">
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Upload Gambar Baru</label>
                                <input type="file" name="gambar" class="form-control mb-2" accept="image/png, image/jpeg, image/jpg">
                                <small class="text-muted d-block">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                                <small class="text-muted d-block text-warning mt-1">Jika diisi, akan otomatis di-resize ke: <strong>450x294 px</strong>.</small>
                            </div>

                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning">
                            <ion-icon name="save-outline"></ion-icon> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.berita.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
