@extends('layouts-admin.admin')

@section('title', 'Edit Client - Lawgika Admin')

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
                    Edit: {{ $user->company_name ?: $user->name }}
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
                <h5 class="mb-0"><ion-icon name="create-outline" class="align-middle"></ion-icon> Edit Client: {{ $user->company_name ?: $user->name }}</h5>
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

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- ── Role ────────────────────────────── --}}
                        <div class="col-12">
                            <label class="form-label fw-semibold">Role / Jabatan <span class="text-danger">*</span></label>
                            @if(auth()->user()->isSPV())
                            <select name="role" class="form-select" required>
                                <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Pelanggan (Client)</option>
                                <option value="admin1"   {{ old('role', $user->role) === 'admin1'   ? 'selected' : '' }}>Admin Order (Admin 1)</option>
                                <option value="admin2"   {{ old('role', $user->role) === 'admin2'   ? 'selected' : '' }}>Admin Konten (Admin 2)</option>
                                <option value="admin"    {{ old('role', $user->role) === 'admin'    ? 'selected' : '' }}>SPV (Super Admin)</option>
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ $user->roleLabel() }}" readonly disabled>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                            @endif
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="business-outline" class="align-middle"></ion-icon> Data Perusahaan</h6>

                        <div class="col-12">
                            <label class="form-label">Nama Perusahaan / PT</label>
                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $user->company_name) }}" placeholder="PT / CV / Nama Perusahaan">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="business_type" class="form-control" value="{{ old('business_type', $user->business_type) }}" placeholder="Contoh: Teknologi, Hukum">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">NPWP</label>
                            <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $user->npwp) }}" placeholder="XX.XXX.XXX.X-XXX.XXX">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-control" rows="2" placeholder="Alamat lengkap...">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="person-outline" class="align-middle"></ion-icon> Data PIC</h6>

                        <div class="col-md-6">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nama PIC / Contact Person</label>
                            <input type="text" name="pic_name" class="form-control" value="{{ old('pic_name', $user->pic_name) }}" placeholder="Jika berbeda dari nama PJ">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Telepon / WA</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <hr class="my-2">
                        <h6 class="text-primary mb-0"><ion-icon name="document-text-outline" class="align-middle"></ion-icon> Catatan Internal</h6>

                        <div class="col-12">
                            <label class="form-label">Catatan Admin</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan internal...">{{ old('notes', $user->notes) }}</textarea>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline" class="align-middle"></ion-icon> Update Client
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
