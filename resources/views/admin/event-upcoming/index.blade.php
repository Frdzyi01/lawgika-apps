@extends('layouts.admin')

@section('title', 'Event Upcoming - Lawgika Admin')

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
                    Event Upcoming
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
                        <p class="mb-0 fs-6">Total Event</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-purple">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">18</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">+2 Event baru</span>
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
                        <p class="mb-0 fs-6">Event Aktif</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-info">
                        <ion-icon name="play-circle-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">10</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">+3 berlangsung</span>
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
                        <p class="mb-0 fs-6">Event Selesai</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-danger">
                        <ion-icon name="checkmark-done-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">5</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-danger">-1 minggu lalu</span>
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
                        <p class="mb-0 fs-6">Total Peserta</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-success">
                        <ion-icon name="people-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">1,540</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">+20% partisipasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0">Event Management</h4>
    <button
        class="btn btn-primary d-flex align-items-center gap-2"
        data-bs-toggle="modal"
        data-bs-target="#tambahEventModal">
        <ion-icon name="calendar-outline"></ion-icon> Tambah Event
    </button>
</div>

<!-- jgn di apus -->
<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Daftar Event</h6>
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
                        <th>Banner Event</th>
                        <th>Nama Event</th>
                        <th>Deskripsi</th>
                        <th>Lokasi</th>
                        <th>Tanggal Event</th>
                        <th>Tanggal Selesai Event</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                <ion-icon
                                    name="pricetag-outline"
                                    class="fs-4 text-primary"></ion-icon>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">Promo Ramadhan</h6>
                        </td>
                        <td>Diskon spesial bulan puasa</td>
                        <td>20%</td>
                        <td>12 Mar 2024</td>
                        <td>12 Apr 2024</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a
                                    href="javascript:;"
                                    class="text-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                <ion-icon
                                    name="pricetag-outline"
                                    class="fs-4 text-primary"></ion-icon>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">Promo Cuci Helm Gratis</h6>
                        </td>
                        <td>Setiap layanan servis ganti oli</td>
                        <td>100%</td>
                        <td>01 Jan 2024</td>
                        <td>31 Des 2024</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a
                                    href="javascript:;"
                                    class="text-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                <ion-icon
                                    name="pricetag-outline"
                                    class="fs-4 text-primary"></ion-icon>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">Promo Member Baru</h6>
                        </td>
                        <td>Potongan harga untuk pendaftar awal</td>
                        <td>Rp 100.000</td>
                        <td>15 Feb 2024</td>
                        <td>15 Mar 2024</td>
                        <td><span class="badge bg-secondary">Nonaktif</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a
                                    href="javascript:;"
                                    class="text-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                <ion-icon
                                    name="pricetag-outline"
                                    class="fs-4 text-primary"></ion-icon>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">Jumat Berkah</h6>
                        </td>
                        <td>Diskon setiap hari jumat pagi</td>
                        <td>15%</td>
                        <td>01 Apr 2024</td>
                        <td>30 Apr 2024</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a
                                    href="javascript:;"
                                    class="text-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                <ion-icon
                                    name="pricetag-outline"
                                    class="fs-4 text-primary"></ion-icon>
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">Flash Sale Akhir Tahun</h6>
                        </td>
                        <td>Diskon besar-besaran akhir tahun</td>
                        <td>50%</td>
                        <td>25 Des 2023</td>
                        <td>31 Des 2023</td>
                        <td><span class="badge bg-secondary">Nonaktif</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                <a
                                    href="javascript:;"
                                    class="text-primary"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="View detail"><ion-icon name="eye-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-warning"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"><ion-icon name="pencil-outline"></ion-icon></a>
                                <a
                                    href="javascript:;"
                                    class="text-danger"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Delete"><ion-icon name="trash-outline"></ion-icon></a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Promo -->
<div
    class="modal fade"
    id="tambahPromoModal"
    tabindex="-1"
    aria-labelledby="tambahPromoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5
                    class="modal-title d-flex align-items-center gap-2"
                    id="tambahPromoModalLabel">
                    <ion-icon name="pricetag-outline"></ion-icon> Tambah Promo
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
                        <label class="form-label">Upload Gambar</label>
                        <input
                            type="file"
                            class="form-control"
                            accept="image/*" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Promo</label>
                        <input
                            type="text"
                            class="form-control"
                            placeholder="Masukkan judul promo" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            class="form-control"
                            rows="3"
                            placeholder="Deskripsi singkat promo"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Diskon (% atau Rp)</label>
                        <input
                            type="number"
                            class="form-control"
                            placeholder="Contoh: 20" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
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
<!-- End Modal Tambah Promo -->
@endsection