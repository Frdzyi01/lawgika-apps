@extends('layouts-admin.admin')

@section('title', 'Tambah Client - Lawgika Admin')

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
                    <a href="{{ route('admin.users.index') }}">Master Client</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Tambah Client
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
                <h5 class="mb-0"><ion-icon name="person-add-outline" class="align-middle"></ion-icon> Tambah Client Baru</h5>
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
                        {{-- ── Role ────────────────────────────── --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Role / Jabatan <span class="text-danger">*</span></label>
                            @if(auth()->user()->isSPV())
                            <select name="role" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="customer" {{ old('role', 'customer') === 'customer' ? 'selected' : '' }}>Pelanggan (Client)</option>
                                <option value="admin1"   {{ old('role') === 'admin1'   ? 'selected' : '' }}>Admin Order (Admin 1)</option>
                                <option value="admin2"   {{ old('role') === 'admin2'   ? 'selected' : '' }}>Admin Konten (Admin 2)</option>
                                <option value="admin"    {{ old('role') === 'admin'    ? 'selected' : '' }}>SPV (Super Admin)</option>
                            </select>
                            <small class="text-muted">Role menentukan hak akses pengguna di dashboard admin.</small>
                            @else
                            <input type="text" class="form-control" value="Pelanggan (Client)" readonly disabled>
                            <input type="hidden" name="role" value="customer">
                            <small class="text-muted">Sebagai Admin Order, akun baru yang ditambahkan otomatis ber-role Pelanggan (Client).</small>
                            @endif
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="business-outline" class="align-middle"></ion-icon> Data Perusahaan</h6>

                        {{-- ── Nama Perusahaan ─────────────────── --}}
                        <div class="col-12">
                            <label class="form-label">Nama Perusahaan / PT <span class="text-danger">*</span></label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name') }}" placeholder="PT / CV / Nama Perusahaan">
                        </div>

                        {{-- ── Bidang Usaha ─────────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="business_type" class="form-control" value="{{ old('business_type') }}" placeholder="Contoh: Teknologi, Hukum, Konsultan">
                        </div>

                        {{-- ── NPWP ─────────────────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp') }}" placeholder="XX.XXX.XXX.X-XXX.XXX">
                        </div>

                        {{-- ── Alamat ──────────────────────────── --}}
                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap perusahaan...">{{ old('address') }}</textarea>
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="person-outline" class="align-middle"></ion-icon> Data PIC (Person In Charge)</h6>

                        {{-- ── Nama Lengkap / PJ ─────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Nama penanggung jawab">
                        </div>

                        {{-- ── PIC Name ─────────────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Nama PIC / Contact Person</label>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name') }}" placeholder="Jika berbeda dari nama PJ">
                            <small class="text-muted">Isi jika contact person berbeda dari penanggung jawab</small>
                        </div>

                        {{-- ── Email ────────────────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="email@example.com">
                        </div>

                        {{-- ── Telepon / WA ────────────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon / WA</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx">
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="lock-closed-outline" class="align-middle"></ion-icon> Akses Login</h6>

                        {{-- ── Password (opsional) ────────────── --}}
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter">
                            <small class="text-muted">Kosongkan untuk auto-generate default password</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="document-text-outline" class="align-middle"></ion-icon> Catatan Internal</h6>

                        {{-- ── Notes ────────────────────────────── --}}
                        <div class="col-12">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan internal untuk client ini (tidak terlihat oleh client)...">{{ old('notes') }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline" class="align-middle"></ion-icon> Simpan Client
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
