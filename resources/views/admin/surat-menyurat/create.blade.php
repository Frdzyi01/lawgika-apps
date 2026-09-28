@extends('layouts-admin.admin')

@section('title', 'Kirim Surat Baru - Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Surat Menyurat</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.surat-menyurat.index') }}">Semua Surat</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Kirim Surat Baru
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        
        {{-- Search Component --}}
        @include('partials.client-search', ['inputId' => 'search_client'])

        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0 fw-bold"><i class="fa-solid fa-envelope-open-text text-primary me-2"></i> Form Kirim Surat ke Client (Admin)</h6>
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

                <form action="{{ route('admin.surat-menyurat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    {{-- Hidden input for user_id --}}
                    <input type="hidden" name="user_id" id="form_user_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Judul / Perihal Surat <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Misal: Draft Surat Perjanjian" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">File Surat (Wajib PDF, Maks 5MB) <span class="text-danger">*</span></label>
                            <input type="file" name="file" class="form-control" accept="application/pdf" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Catatan / Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="note" class="form-control" rows="4" placeholder="Tuliskan instruksi atau pesan untuk client di sini..." required>{{ old('note') }}</textarea>
                            <small class="text-muted">Pesan ini akan bisa dibaca langsung oleh client.</small>
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.surat-menyurat.index') }}" class="btn btn-secondary px-4 me-2">Batal</a>
                            <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-paper-plane"></i> <span>Kirim Surat</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Capture the client selected from the partial component if needed for UI feedback
    window.onClientSelected = function(clientData) {
        document.getElementById('form_user_id').value = clientData.id;
        // Just acknowledging that client is selected
        console.log("Client selected for correspondence:", clientData);
    };
</script>
@endpush
