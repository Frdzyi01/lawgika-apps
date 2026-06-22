@extends('layouts-admin.admin')

@section('title', 'Tambah Akun - Lawgika Admin')

@section('content')
<!--start breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Dashboard</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.users.index') }}">Manajemen Akun</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Tambah Akun
                </li>
            </ol>
        </nav>
    </div>
</div>
<!--end breadcrumb-->

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card radius-10">
            <div class="card-header py-3">
                <h5 class="mb-0">Tambah Akun Baru</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Role / Jabatan <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Pelanggan</option>
                                <option value="admin1"   {{ old('role') === 'admin1'   ? 'selected' : '' }}>Admin Order (Admin 1)</option>
                                <option value="admin2"   {{ old('role') === 'admin2'   ? 'selected' : '' }}>Admin Konten (Admin 2)</option>
                                <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>SPV (Super Admin)</option>
                            </select>
                            <small class="text-muted">Role menentukan hak akses pengguna di dashboard admin.</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="email@example.com">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="Min. 8 karakter">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nomor Telepon / WA</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Perusahaan (opsional)</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="PT / CV / Nama Perusahaan">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Alamat lengkap...">{{ old('address') }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Simpan Akun</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
