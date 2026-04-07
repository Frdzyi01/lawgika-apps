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
                        <h4 class="mb-0">{{ $totalEvent }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-success">Total semua event</span>
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
                        <h4 class="mb-0">{{ $eventAktif }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-info">Event berlangsung</span>
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
                        <h4 class="mb-0">{{ $eventSelesai }}</h4>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-light text-danger">Event selesai</span>
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
                    @forelse($events as $event)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div
                                class="product-box border bg-light rounded d-flex justify-content-center align-items-center"
                                style="width: 50px; height: 50px">
                                @if($event->banner)
                                    <img src="{{ asset('storage/' . $event->banner) }}"
                                         alt="{{ $event->nama_event }}"
                                         style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                @else
                                    <ion-icon
                                        name="calendar-outline"
                                        class="fs-4 text-primary"></ion-icon>
                                @endif
                            </div>
                        </td>
                        <td>
                            <h6 class="mb-0">{{ $event->nama_event }}</h6>
                        </td>
                        <td>{{ Str::limit($event->deskripsi, 40) }}</td>
                        <td>{{ $event->lokasi }}</td>
                        <td>{{ $event->tanggal_mulai->translatedFormat('d M Y') }}</td>
                        <td>{{ $event->tanggal_selesai->translatedFormat('d M Y') }}</td>
                        <td>
                            @if($event->status_aktif)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-3 fs-6">
                                {{-- Edit --}}
                                <a
                                    href="javascript:;"
                                    class="text-warning btn-edit-event"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="bottom"
                                    title="Edit info"
                                    data-id="{{ $event->id }}"
                                    data-nama="{{ e($event->nama_event) }}"
                                    data-deskripsi="{{ e($event->deskripsi) }}"
                                    data-lokasi="{{ e($event->lokasi) }}"
                                    data-mulai="{{ $event->tanggal_mulai->format('Y-m-d') }}"
                                    data-selesai="{{ $event->tanggal_selesai->format('Y-m-d') }}"
                                    data-status="{{ $event->status ? 1 : 0 }}"
                                ><ion-icon name="pencil-outline"></ion-icon></a>
                                {{-- Delete --}}
                                <form action="{{ route('admin.event-upcoming.destroy', $event->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Hapus event ini?')">
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
                        <td colspan="9" class="text-center text-muted py-4">Belum ada data event.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Event -->
<div
    class="modal fade"
    id="tambahEventModal"
    tabindex="-1"
    aria-labelledby="tambahEventModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5
                    class="modal-title d-flex align-items-center gap-2"
                    id="tambahEventModalLabel">
                    <ion-icon name="calendar-outline"></ion-icon> Tambah Event Baru
                </h5>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahEvent" action="{{ route('admin.event-upcoming.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload Banner</label>
                        <input
                            type="file"
                            class="form-control"
                            name="banner"
                            accept="image/*" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input
                            type="text"
                            class="form-control"
                            name="nama_event"
                            placeholder="Masukkan nama event" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea
                            class="form-control"
                            name="deskripsi"
                            rows="3"
                            placeholder="Deskripsi singkat event"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input
                            type="text"
                            class="form-control"
                            name="lokasi"
                            placeholder="Masukkan lokasi event" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="1">Aktif</option>
                            <option value="0">Selesai</option>
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
                    form="formTambahEvent"
                    class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Tambah Event -->

<!-- Modal Edit Event -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2" id="editEventModalLabel">
                    <ion-icon name="pencil-outline"></ion-icon> Edit Event
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditEvent" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Upload Banner Baru (opsional)</label>
                        <input type="file" class="form-control" name="banner" accept="image/*" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Event</label>
                        <input type="text" class="form-control" name="nama_event" id="edit_nama_event" placeholder="Masukkan nama event" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="edit_deskripsi_event" rows="3" placeholder="Deskripsi singkat event"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" id="edit_lokasi" placeholder="Masukkan lokasi event" />
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" id="edit_tanggal_mulai_event" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="tanggal_selesai" id="edit_tanggal_selesai_event" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status_event">
                            <option value="1">Aktif</option>
                            <option value="0">Selesai</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary border-0" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="formEditEvent" class="btn btn-primary d-flex align-items-center gap-2">
                    <ion-icon name="save-outline"></ion-icon> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Event -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-event').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id       = this.dataset.id;
            var nama     = this.dataset.nama;
            var desk     = this.dataset.deskripsi;
            var lokasi   = this.dataset.lokasi;
            var mulai    = this.dataset.mulai;
            var selesai  = this.dataset.selesai;
            var status   = this.dataset.status;

            var form = document.getElementById('formEditEvent');
            form.action = '/admin/event-upcoming/' + id;

            document.getElementById('edit_nama_event').value           = nama;
            document.getElementById('edit_deskripsi_event').value      = desk;
            document.getElementById('edit_lokasi').value               = lokasi;
            document.getElementById('edit_tanggal_mulai_event').value  = mulai;
            document.getElementById('edit_tanggal_selesai_event').value = selesai;
            document.getElementById('edit_status_event').value         = status;

            var modal = new bootstrap.Modal(document.getElementById('editEventModal'));
            modal.show();
        });
    });
});
</script>
@endpush

@endsection