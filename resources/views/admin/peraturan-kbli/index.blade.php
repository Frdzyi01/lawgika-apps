@extends('layouts.admin')

@section('title', 'Peraturan KBLI - Lawgika Admin')

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
                <li class="breadcrumb-item active" aria-current="page">
                    Peraturan KBLI
                </li>
            </ol>
        </nav>
    </div>

</div>
<!--end breadcrumb-->

<div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">
    <div class="col">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-start gap-2">
                    <div>
                        <p class="mb-0 fs-6">Total Peraturan</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-purple">
                        <ion-icon name="document-text-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalPeraturan }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">Total semua</span>
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
                        <p class="mb-0 fs-6">Peraturan Aktif</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-info">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalAktif }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">Aktif</span>
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
                        <p class="mb-0 fs-6">Peraturan Direvisi</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-danger">
                        <ion-icon name="create-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalDirevisi }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-warning">Direvisi</span>
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
                        <p class="mb-0 fs-6">Peraturan Dicabut</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-success">
                        <ion-icon name="close-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalDicabut }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">Dicabut</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0">Manajemen Peraturan KBLI</h4>
    <button
        class="btn btn-primary d-flex align-items-center gap-2"
        data-bs-toggle="modal"
        data-bs-target="#tambahKbliModal">
        <ion-icon name="document-attach-outline"></ion-icon> Tambah Peraturan
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- jgn di apus -->
<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Daftar Peraturan KBLI</h6>
            <div class="fs-5 ms-auto dropdown">
                <div
                    class="dropdown-toggle dropdown-toggle-nocaret cursor-pointer"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-three-dots"></i>
                </div>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Action</a></li>
                    <li>
                        <a class="dropdown-item" href="#">Another action</a>
                    </li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="table-responsive mt-2">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode KBLI</th>
                        <th>Judul Peraturan</th>
                        <th>Deskripsi Singkat</th>
                        <th>Tanggal Berlaku</th>
                        <th>Status</th>
                        <th>File Dokumen</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <h6 class="mb-0">{{ $item->kode_kbli }}</h6>
                        </td>
                        <td>{{ $item->judul_peraturan }}</td>
                        <td>{{ Str::limit($item->deskripsi, 50) }}</td>
                        <td>{{ $item->tanggal_berlaku->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($item->status == 'aktif')
                                <span class="badge bg-success">Aktif</span>
                            @elseif($item->status == 'direvisi')
                                <span class="badge bg-warning text-dark">Direvisi</span>
                            @else
                                <span class="badge bg-danger">Dicabut</span>
                            @endif
                        </td>
                        <td>
                            @if($item->file_dokumen)
                                <a href="{{ asset('storage/' . $item->file_dokumen) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                                   style="width: max-content;">
                                    <ion-icon name="document-outline"></ion-icon> Lihat PDF
                                </a>
                            @else
                                <span class="text-muted small">Tidak ada file</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                {{-- Edit --}}
                                <a href="javascript:;"
                                   class="text-warning btn-edit-kbli"
                                   data-bs-toggle="tooltip"
                                   data-bs-placement="bottom"
                                   title="Edit info"
                                   data-id="{{ $item->id }}"
                                   data-kode="{{ e($item->kode_kbli) }}"
                                   data-judul="{{ e($item->judul_peraturan) }}"
                                   data-deskripsi="{{ e($item->deskripsi) }}"
                                   data-tanggal="{{ $item->tanggal_berlaku->format('Y-m-d') }}"
                                   data-status="{{ $item->status }}"
                                ><ion-icon name="pencil-outline"></ion-icon></a>
                                {{-- Delete --}}
                                <form action="{{ route('admin.peraturan-kbli.destroy', $item->id) }}"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Hapus peraturan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn p-0 border-0 bg-transparent text-danger"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="bottom"
                                            title="Delete">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">Belum ada data peraturan KBLI.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Peraturan -->
<div
    class="modal fade"
    id="tambahKbliModal"
    tabindex="-1"
    aria-labelledby="tambahKbliModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5
                    class="modal-title d-flex align-items-center gap-2"
                    id="tambahKbliModalLabel">
                    <ion-icon name="document-attach-outline"></ion-icon> Tambah Peraturan
                    Baru
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahKbli" action="{{ route('admin.peraturan-kbli.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode KBLI</label>
                        <input type="text" class="form-control" name="kode_kbli" placeholder="Contoh: 62011" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Peraturan</label>
                        <input type="text" class="form-control" name="judul_peraturan" placeholder="Masukkan judul peraturan" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi singkat peraturan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File PDF</label>
                        <input type="file" class="form-control" name="file_dokumen" accept="application/pdf" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control" name="tanggal_berlaku" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="aktif">Aktif</option>
                                <option value="direvisi">Direvisi</option>
                                <option value="dicabut">Dicabut</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-secondary border-0"
                    data-bs-dismiss="modal">
                    Batal
                </button>
                <button
                    type="submit"
                    form="formTambahKbli"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Tambah Peraturan -->

<!-- Modal Edit Peraturan -->
<div class="modal fade" id="editKbliModal" tabindex="-1" aria-labelledby="editKbliModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="editKbliModalLabel">
                    <ion-icon name="pencil-outline"></ion-icon> Edit Peraturan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditKbli" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kode KBLI</label>
                        <input type="text" class="form-control" name="kode_kbli" id="edit_kode_kbli" placeholder="Contoh: 62011" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Peraturan</label>
                        <input type="text" class="form-control" name="judul_peraturan" id="edit_judul_peraturan" placeholder="Masukkan judul peraturan" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="edit_deskripsi_kbli" rows="3" placeholder="Deskripsi singkat peraturan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File PDF Baru (opsional)</label>
                        <input type="file" class="form-control" name="file_dokumen" accept="application/pdf" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control" name="tanggal_berlaku" id="edit_tanggal_berlaku" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status_kbli">
                                <option value="aktif">Aktif</option>
                                <option value="direvisi">Direvisi</option>
                                <option value="dicabut">Dicabut</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="formEditKbli" class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Peraturan -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-kbli').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id      = this.dataset.id;
            var kode    = this.dataset.kode;
            var judul   = this.dataset.judul;
            var desk    = this.dataset.deskripsi;
            var tanggal = this.dataset.tanggal;
            var status  = this.dataset.status;

            var form = document.getElementById('formEditKbli');
            form.action = '/admin/peraturan-kbli/' + id;

            document.getElementById('edit_kode_kbli').value       = kode;
            document.getElementById('edit_judul_peraturan').value  = judul;
            document.getElementById('edit_deskripsi_kbli').value   = desk;
            document.getElementById('edit_tanggal_berlaku').value  = tanggal;
            document.getElementById('edit_status_kbli').value      = status;

            var modal = new bootstrap.Modal(document.getElementById('editKbliModal'));
            modal.show();
        });
    });
});
</script>
@endpush

@endsection