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
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary">Settings</button>
            <button type="button" class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <a class="dropdown-item" href="javascript:;">Action</a>
                <a class="dropdown-item" href="javascript:;">Another action</a>
                <a class="dropdown-item" href="javascript:;">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
        </div>
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
                        <h4 class="mb-0">32</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">+5 terbaru</span>
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
                        <h4 class="mb-0">20</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">+3 aktif</span>
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
                        <h4 class="mb-0">7</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-warning">+2 revisi</span>
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
                        <h4 class="mb-0">5</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">-1 bulan ini</span>
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
                    <tr>
                        <td>1</td>
                        <td>
                            <h6 class="mb-0">62011</h6>
                        </td>
                        <td>Aktivitas Pemrograman Komputer</td>
                        <td>Mengatur kegiatan pengembangan software</td>
                        <td>12 Jan 2024</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" style="width: max-content;">
                                <ion-icon name="document-outline"></ion-icon> Lihat PDF
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <h6 class="mb-0">63122</h6>
                        </td>
                        <td>Portal Web dan Platform Digital</td>
                        <td>Ketentuan platform portal web komersial</td>
                        <td>01 Mar 2024</td>
                        <td><span class="badge bg-warning text-dark">Direvisi</span></td>
                        <td>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" style="width: max-content;">
                                <ion-icon name="document-outline"></ion-icon> Lihat PDF
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <h6 class="mb-0">70209</h6>
                        </td>
                        <td>Konsultasi Manajemen Lainnya</td>
                        <td>Jasa konsultasi perizinan umum</td>
                        <td>15 Apr 2023</td>
                        <td><span class="badge bg-danger">Dicabut</span></td>
                        <td>
                            <a href="javascript:;" class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1" style="width: max-content;">
                                <ion-icon name="document-outline"></ion-icon> Lihat PDF
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a href="javascript:;" class="text-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-warning" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a href="javascript:;" class="text-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>

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
                <form>
                    <div class="mb-3">
                        <label class="form-label">Kode KBLI</label>
                        <input type="text" class="form-control" placeholder="Contoh: 62011" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Peraturan</label>
                        <input type="text" class="form-control" placeholder="Masukkan judul peraturan" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" rows="3" placeholder="Deskripsi singkat peraturan"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload File PDF</label>
                        <input type="file" class="form-control" accept="application/pdf" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berlaku</label>
                            <input type="date" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select">
                                <option value="Aktif">Aktif</option>
                                <option value="Direvisi">Direvisi</option>
                                <option value="Dicabut">Dicabut</option>
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
                    type="button"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Tambah Peraturan -->
@endsection