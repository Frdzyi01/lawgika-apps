@extends('layouts-admin.admin')

@section('title', 'Tambah Reservasi Podcast Room - Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Podcast Room</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/podcast-room') }}">Reservasi Podcast Room</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Tambah Reservasi
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
                <h6 class="mb-0"><ion-icon name="mic-outline" class="align-middle"></ion-icon> Form Reservasi Podcast (Admin)</h6>
            </div>
            <div class="card-body">

                {{-- Client Info Container --}}
                <div id="client_info_container" class="alert alert-info" style="display:none;">
                    <h6 class="alert-heading fw-bold mb-2">Informasi Client</h6>
                    <div id="client_info_content"></div>
                </div>
                @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div class="position-relative">
                    <div id="form_overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 10; background: rgba(255,255,255,0.5); cursor: not-allowed;" onclick="alert('Silakan cari dan pilih client terlebih dahulu pada kotak pencarian di atas.\n\nJika client belum ada, silakan kembali ke menu Data Client dan pilih Tambah Data Akun.')"></div>
                    <form action="{{ route('admin.podcast-room.store') }}" method="POST">
                        @csrf

                        {{-- Hidden input for user_id --}}
                        <input type="hidden" name="user_id" id="form_user_id">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Tipe Sumber Reservasi <span class="text-danger">*</span></label>
                                <select name="source_type" class="form-select" id="source_type" required onchange="toggleBenefitField()">
                                    <option value="manual">Manual / Quota Standard</option>
                                    <option value="benefit">Benefit Paket PT (Gunakan Kuota Paket)</option>
                                </select>
                            </div>

                            <div class="col-md-12" id="benefit_container" style="display:none;">
                                <label class="form-label">Pilih Benefit Aktif (Jika Tipe Benefit)</label>
                                <select name="benefit_id" id="benefit_id" class="form-select">
                                    <option value="">-- Pilih Benefit --</option>
                                </select>
                                <small class="text-muted">Pastikan client yang dipilih memiliki benefit aktif.</small>
                            </div>

                            <div class="col-12" id="booking_fields_container" style="display:none;">
                                <hr class="my-2">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="form-label">Pilih Ruangan <span class="text-danger">*</span></label>
                                        <select name="room_name" class="form-select" required>
                                            <option value="Ruang Podcastroom Utama">Ruang Podcastroom Utama</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Paket Booking <span class="text-danger">*</span></label>
                                        <select name="paket" class="form-select" required>
                                            <option value="Podcast Room Package (12 Jam / 1 Tahun)" selected>Podcast Room Package (12 Jam / 1 Tahun)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Judul Podcast (Opsional)</label>
                                        <input type="text" name="podcast_title" class="form-control" placeholder="Materi podcast...">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Waktu Pemesanan Booking <span class="text-danger">*</span></label>
                                        <input type="time" name="start_time" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                                        <input type="number" name="participants" class="form-control" min="1" value="1" required>
                                    </div>

                                    <div class="col-md-6" id="payment_method_container">
                                        <label class="form-label">Metode Pembayaran</label>
                                        <select name="payment_method" class="form-select" id="payment_method">
                                            <option value="">-- Kosongkan / Lainnya --</option>
                                            <option value="Payment WA">Payment WA</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Catatan (Keperluan / Opsional)</label>
                                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan internal atau keperluan reservasi..."></textarea>
                                    </div>

                                    <div class="col-12 mt-4 text-end">
                                        <a href="{{ url('admin/podcast-room') }}" class="btn btn-secondary px-4">Batal</a>
                                        <button type="submit" class="btn btn-primary px-4" id="btn-submit">
                                            <ion-icon name="save-outline" class="align-middle"></ion-icon> Simpan Reservasi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleBenefitField() {
        const type = document.getElementById('source_type').value;
        const container = document.getElementById('benefit_container');
        const select = document.getElementById('benefit_id');
        const paymentMethodContainer = document.getElementById('payment_method_container');

        if (type === 'benefit') {
            container.style.display = 'block';
            select.required = true;
            paymentMethodContainer.style.display = 'none';
            document.getElementById('payment_method').value = '';
        } else {
            container.style.display = 'none';
            select.required = false;
            paymentMethodContainer.style.display = 'block';
            document.getElementById('booking_fields_container').style.display = 'block';
        }
        toggleBookingFields();
    }

    function toggleBookingFields() {
        const sourceType = document.getElementById('source_type').value;
        const benefitId = document.getElementById('benefit_id').value;
        const container = document.getElementById('booking_fields_container');

        if (sourceType === 'manual') {
            container.style.display = 'block';
        } else if (sourceType === 'benefit' && benefitId !== '') {
            container.style.display = 'block';
        } else {
            container.style.display = 'none';
        }
    }

    // Add event listener to benefit_id select
    document.getElementById('benefit_id').addEventListener('change', toggleBookingFields);

    // Capture the client selected from the partial component
    window.onClientSelected = function(clientData) {
        document.getElementById('form_user_id').value = clientData.id;

        // Show client info container
        const infoContainer = document.getElementById('client_info_container');
        const infoContent = document.getElementById('client_info_content');
        infoContainer.style.display = 'block';

        let hasBenefit = false;
        let benefitHtml = '';

        // Find if they have benefits
        const benefitSelect = document.getElementById('benefit_id');
        benefitSelect.innerHTML = '<option value="">-- Pilih Benefit --</option>';

        if (clientData.room_benefits && clientData.room_benefits.length > 0) {
            clientData.room_benefits.forEach(b => {
                if (b.type === 'podcast' || b.type === 'shared') {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.textContent = `[${b.type.toUpperCase()}] Sisa: ${Math.round(b.remaining_minutes/60)} jam`;
                    benefitSelect.appendChild(opt);
                    hasBenefit = true;

                    benefitHtml += `
                        <div class="mt-2 p-2 bg-white rounded border border-info">
                            <strong>Paket Aktif:</strong> ${b.paket} <br>
                            <strong>Kuota Podcast:</strong> ${Math.round(b.total_minutes/60)} Jam <br>
                            <strong>Dipakai:</strong> ${Math.round(b.used_minutes/60)} Jam <br>
                            <strong>Sisa:</strong> <span class="text-success fw-bold">${Math.round(b.remaining_minutes/60)} Jam</span>
                        </div>
                    `;
                }
            });
        }

        const benefitOption = document.querySelector('#source_type option[value="benefit"]');

        if (!hasBenefit) {
            benefitHtml = `
                <div class="mt-2 text-danger fw-bold">Client tidak memiliki benefit Podcast.</div>
                <div class="mt-1 text-primary fw-bold">Silakan isi data booking di bawah ini.</div>
            `;
            benefitOption.disabled = true;
        } else {
            benefitOption.disabled = false;
        }

        // Remove overlay so form can be filled
        const overlay = document.getElementById('form_overlay');
        if (overlay) overlay.style.display = 'none';

        // Render client info
        infoContent.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <strong>Nama PT:</strong><br>${clientData.company_name || '-'}
                </div>
                <div class="col-md-4">
                    <strong>PIC:</strong><br>${clientData.name}
                </div>
                <div class="col-md-4">
                    <strong>Nomor WA:</strong><br>${clientData.phone || '-'}
                </div>
            </div>
            ${benefitHtml}
        `;

        if (hasBenefit) {
            // Auto select benefit source type if they have benefits
            document.getElementById('source_type').value = 'benefit';
        } else {
            document.getElementById('source_type').value = 'manual';
        }

        toggleBenefitField();
    };

    // Initial load
    toggleBenefitField();
</script>
@endpush