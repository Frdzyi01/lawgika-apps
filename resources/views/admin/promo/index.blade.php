@extends('layouts-admin.admin')

@section('title', 'Promo - Lawgika Admin')

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
                    Promo
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
                        <p class="mb-0 fs-6">Total Promo</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-purple">
                        <ion-icon name="pricetag-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $totalPromo }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">Total semua promo</span>
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
                        <p class="mb-0 fs-6">Promo Aktif</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-info">
                        <ion-icon name="flash-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $promoAktif }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">Promo aktif</span>
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
                        <p class="mb-0 fs-6">Promo Berakhir</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-danger">
                        <ion-icon name="time-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">{{ $promoBerakhir }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-danger">Promo nonaktif</span>
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
                        <p class="mb-0 fs-6">Total Digunakan</p>
                    </div>
                    <div
                        class="ms-auto widget-icon-small text-white bg-gradient-success">
                        <ion-icon name="trending-up-outline"></ion-icon>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-3">
                    <div>
                        <h4 class="mb-0">1,245</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">+15% penggunaan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0">Promo Management</h4>
    <button
        class="btn btn-primary d-flex align-items-center gap-2"
        data-bs-toggle="modal"
        data-bs-target="#tambahPromoModal">
        <ion-icon name="add-outline"></ion-icon> Tambah Promo
    </button>
</div>

<!-- jgn di apus -->
<div class="card radius-10 w-100">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <h6 class="mb-0">Daftar Promo</h6>
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
                        <th>Gambar Promo</th>
                        <th>Judul Promo</th>
                        <th>Deskripsi Singkat</th>
                        <th>Diskon</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promos as $promo)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                @if($promo->gambar)
                                    <img src="{{ asset('storage/' . $promo->gambar) }}"
                                         alt="{{ $promo->judul }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                @else
                                    <ion-icon
                                        name="pricetag-outline"
                                        class="fs-4 text-primary"></ion-icon>
                                @endif
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">{{ $promo->judul }}</h6>
                        </td>
                        <td>{{ Str::limit($promo->deskripsi, 40) }}</td>
                        <td>
                            @if($promo->tipe_diskon === 'persen')
                                {{ number_format($promo->diskon, 0) }}%
                            @else
                                Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $promo->tanggal_mulai->format('d M Y') }}</td>
                        <td>{{ $promo->tanggal_berakhir->format('d M Y') }}</td>
                        <td>
                            @if($promo->status)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                {{-- Edit --}}
                                <a
                                    href="javascript:;"
                                    class="text-warning btn-edit-promo"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"
                                    data-id="{{ $promo->id }}"
                                    data-judul="{{ e($promo->judul) }}"
                                    data-deskripsi="{{ e($promo->deskripsi) }}"
                                    data-tipe="{{ $promo->tipe_diskon }}"
                                    data-diskon="{{ $promo->diskon }}"
                                    data-mulai="{{ $promo->tanggal_mulai->format('Y-m-d') }}"
                                    data-berakhir="{{ $promo->tanggal_berakhir->format('Y-m-d') }}"
                                    data-status="{{ $promo->status ? 1 : 0 }}"
                                ><ion-icon name="pencil-outline"></ion-icon></a>
                                {{-- Delete --}}
                                <form action="{{ route('admin.promo.destroy', $promo->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Hapus promo ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0 border-0 bg-transparent text-danger"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                        <ion-icon name="trash-outline"></ion-icon>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data promo.</td>
                    </tr>
                    @endforelse
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
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                <form id="formTambahPromo" action="{{ route('admin.promo.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Gambar</label>
                        <input
                            type="file"
                            class="form-control"
                            name="gambar"
                            accept="image/*" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Promo</label>
                        <input
                            type="text"
                            class="form-control"
                            name="judul"
                            placeholder="Masukkan judul promo" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            class="form-control"
                            name="deskripsi"
                            rows="3"
                            placeholder="Deskripsi singkat promo"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Diskon</label>
                        <select class="form-select" name="tipe_diskon">
                            <option value="persen">Persen (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Diskon</label>
                        <input
                            type="number"
                            class="form-control"
                            name="diskon"
                            placeholder="Contoh: 20" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" name="tanggal_berakhir" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
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
                    type="submit"
                    form="formTambahPromo"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Tambah Promo -->

<!-- Modal Edit Promo -->
<div class="modal fade" id="editPromoModal" tabindex="-1" aria-labelledby="editPromoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="editPromoModalLabel">
                    <ion-icon name="pencil-outline"></ion-icon> Edit Promo
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditPromo" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Upload Gambar Baru (opsional)</label>
                        <input type="file" class="form-control" name="gambar" accept="image/*" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Judul Promo</label>
                        <input type="text" class="form-control" name="judul" id="edit_judul" placeholder="Masukkan judul promo" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3" placeholder="Deskripsi singkat promo"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Diskon</label>
                        <select class="form-select" name="tipe_diskon" id="edit_tipe_diskon">
                            <option value="persen">Persen (%)</option>
                            <option value="nominal">Nominal (Rp)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nilai Diskon</label>
                        <input type="number" class="form-control" name="diskon" id="edit_diskon" placeholder="Contoh: 20" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" name="tanggal_berakhir" id="edit_tanggal_berakhir" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="formEditPromo" class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Promo -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-promo').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id       = this.dataset.id;
            var judul    = this.dataset.judul;
            var desk     = this.dataset.deskripsi;
            var tipe     = this.dataset.tipe;
            var diskon   = this.dataset.diskon;
            var mulai    = this.dataset.mulai;
            var berakhir = this.dataset.berakhir;
            var status   = this.dataset.status;

            var form = document.getElementById('formEditPromo');
            form.action = '/admin/promo/' + id;

            document.getElementById('edit_judul').value           = judul;
            document.getElementById('edit_deskripsi').value       = desk;
            document.getElementById('edit_tipe_diskon').value     = tipe;
            document.getElementById('edit_diskon').value          = diskon;
            document.getElementById('edit_tanggal_mulai').value   = mulai;
            document.getElementById('edit_tanggal_berakhir').value = berakhir;
            document.getElementById('edit_status').value          = status;

            var modal = new bootstrap.Modal(document.getElementById('editPromoModal'));
            modal.show();
        });
    });
});
</script>
@endpush

@endsection