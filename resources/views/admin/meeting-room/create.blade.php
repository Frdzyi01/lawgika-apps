@extends('layouts-admin.admin')

@section('title', 'Tambah Reservasi Meeting Room - Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Meeting Room</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ url('admin/meeting-room') }}">Reservasi Meeting Room</a>
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

        {{-- Container untuk memunculkan info detail Client yang dipilih --}}
        <div id="client_info_container" style="display:none;" class="mb-4">
            <div class="alert alert-info border-0 bg-info bg-opacity-10 mb-0">
                <div class="d-flex align-items-start">
                    <div class="fs-3 text-info me-3">
                        <ion-icon name="information-circle-outline"></ion-icon>
                    </div>
                    <div class="w-100" id="client_info_content">
                        <!-- JavaScript will inject content here -->
                    </div>
                </div>
            </div>
        </div>

        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0"><ion-icon name="calendar-outline" class="align-middle"></ion-icon> Form Reservasi (Admin)</h6>
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

                <div class="position-relative">
                    <div id="form_overlay" style="position: absolute; top:0; left:0; width:100%; height:100%; z-index: 10; background: rgba(255,255,255,0.5); cursor: not-allowed;" onclick="alert('Silakan cari dan pilih client terlebih dahulu pada kotak pencarian di atas.\n\nJika client belum ada, silakan kembali ke menu Data Client dan pilih Tambah Data Akun.')"></div>
                    <form action="{{ route('admin.meeting-room.store') }}" method="POST">
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
                                            @php
                                                $allRooms = ['Ruang Meetingroom 1', 'Ruang Meetingroom 2', 'Ruang Meetingroom 3'];
                                                $occupiedList = $occupiedRooms ?? [];
                                            @endphp
                                            @foreach($allRooms as $roomOption)
                                                @php $isOccupied = in_array($roomOption, $occupiedList); @endphp
                                                <option value="{{ $roomOption }}" {{ $isOccupied ? 'disabled' : '' }}>
                                                    {{ $roomOption }} {{ $isOccupied ? '🔴 (Sedang Dipakai / Check-In)' : '🟢 (Tersedia)' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @if(!empty($occupiedList))
                                        <small class="text-danger mt-1 d-block fw-semibold">
                                            <ion-icon name="warning-outline" class="align-middle me-1"></ion-icon>
                                            Ruangan berlabel <strong>(Sedang Dipakai / Check-In)</strong> sedang di-disable dan tidak dapat dipilih hingga sesi client sebelumnya selesai Check Out.
                                        </small>
                                        @endif
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Paket Booking <span class="text-danger">*</span></label>
                                        <select name="paket" class="form-select" required>
                                            <option value="Meeting Room Package (60 Jam / 1 Tahun)" selected>Meeting Room Package (60 Jam / 1 Tahun)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Reservasi <span class="text-danger">*</span></label>
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
                                        <a href="{{ url('admin/meeting-room') }}" class="btn btn-secondary px-4">Batal</a>
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
            if (paymentMethodContainer) paymentMethodContainer.style.display = 'none';
            if (document.getElementById('payment_method')) document.getElementById('payment_method').value = '';
        } else {
            container.style.display = 'none';
            select.required = false;
            if (paymentMethodContainer) paymentMethodContainer.style.display = 'block';
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

    // Capture the client selected from the partial component
    window.onClientSelected = function(clientData) {
        document.getElementById('form_user_id').value = clientData.id;

        const benefitSelect = document.getElementById('benefit_id');
        benefitSelect.innerHTML = '<option value="">-- Pilih Benefit --</option>';

        let hasBenefit = false;
        let benefitHtml = '';

        if (clientData.room_benefits && clientData.room_benefits.length > 0) {
            clientData.room_benefits.forEach(b => {
                if (b.type === 'meeting' || b.type === 'shared') {
                    hasBenefit = true;
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.text = b.paket;
                    benefitSelect.appendChild(opt);

                    benefitHtml += `
                        <div class="mt-2 p-2 bg-white rounded border border-info">
                            <strong>Paket Aktif:</strong> ${b.paket} <br>
                            <strong>Kuota Meeting:</strong> ${Math.round(b.total_minutes/60)} Jam <br>
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
                <div class="mt-2 text-danger fw-bold">Client tidak memiliki benefit Meeting Room.</div>
                <div class="mt-1 text-primary fw-bold">Silakan isi data booking di bawah ini.</div>
            `;
            if (benefitOption) benefitOption.disabled = true;
        } else {
            if (benefitOption) benefitOption.disabled = false;
        }

        // Remove overlay so form can be filled
        const overlay = document.getElementById('form_overlay');
        if (overlay) overlay.style.display = 'none';

        // Render client info
        document.getElementById('client_info_content').innerHTML = `
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

        // Show info container
        document.getElementById('client_info_container').style.display = 'block';

        if (hasBenefit) {
            // Auto select benefit source type if they have benefits
            let sourceType = document.getElementById('source_type');
            if (sourceType) sourceType.value = 'benefit';
        } else {
            let sourceType = document.getElementById('source_type');
            if (sourceType) sourceType.value = 'manual';
        }

        toggleBenefitField();
    };

    // Add event listener to benefit_id select
    document.getElementById('benefit_id').addEventListener('change', toggleBookingFields);
</script>
@endpush