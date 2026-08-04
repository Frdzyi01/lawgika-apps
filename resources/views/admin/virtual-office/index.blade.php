@extends('layouts-admin.admin')

@section('title', 'Manajemen Virtual Office')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manajemen Virtual Office</h4>
        <small class="text-muted">Mengelola seluruh client Virtual Office yang sudah aktif</small>
    </div>

    <div class="d-flex gap-2 align-items-center">
        <a href="{{ route('admin.orders.create', ['service' => 'virtual-office']) }}" class="btn btn-sm btn-success d-flex align-items-center gap-1">
            <ion-icon name="add-outline"></ion-icon> Tambah Data
        </a>
        {{-- Filter Status --}}
        <form method="GET" class="d-flex gap-2 m-0">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><ion-icon name="search-outline"></ion-icon></span>
                <input type="text" name="search" class="form-control" placeholder="Cari Nama / PT..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No Order</th>
                        <th>Perusahaan & PIC</th>
                        <th>No WhatsApp</th>
                        <th>Paket VO</th>
                        <th>Masa Aktif</th>
                        <th>Status</th>
                        <th>Meeting</th>
                        <th>Podcast</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($virtualOffices as $vo)
                    <tr>
                        <td class="ps-4">
                            <span class="fw-semibold text-primary small">{{ $vo->order_number }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}</div>
                            <small class="text-muted">{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}</small>
                        </td>
                        <td>
                            <small>{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $vo->vo_package }}
                            </span>
                        </td>
                        <td>
                            @if($vo->tanggal_aktif && $vo->tanggal_expired)
                            <small class="d-block">Mulai: {{ \Carbon\Carbon::parse($vo->tanggal_aktif)->format('d M Y') }}</small>
                            <small class="d-block text-muted">Hingga: {{ \Carbon\Carbon::parse($vo->tanggal_expired)->format('d M Y') }}</small>
                            @else
                            <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $vo->vo_status_color }} px-3 py-1 fw-semibold" style="color: {{ in_array($vo->vo_status_color, ['warning', 'light', 'info']) ? '#000' : '#fff' }} !important;">
                                {{ $vo->vo_status }}
                            </span>
                        </td>
                        <td>
                            @if($vo->benefit_meeting)
                            <span class="text-success fw-bold">{{ $vo->benefit_meeting->remaining_minutes / 60 }} Jam</span>
                            @else
                            <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td>
                            @if($vo->benefit_podcast)
                            <span class="text-success fw-bold">{{ $vo->benefit_podcast->remaining_minutes / 60 }} Jam</span>
                            @else
                            <span class="text-muted small">–</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button type="button"
                                    class="btn btn-sm btn-info text-white btn-mail-notification d-flex align-items-center gap-1"
                                    data-id="{{ $vo->id }}"
                                    data-company="{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}"
                                    data-pic="{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}"
                                    data-phone="{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}"
                                    data-url="{{ route('admin.virtual-office.mail-notification.store', $vo->id) }}">
                                    📨 Surat Masuk
                                </button>
                                <button type="button"
                                    class="btn btn-sm btn-warning text-dark btn-guest-notification d-flex align-items-center gap-1"
                                    data-id="{{ $vo->id }}"
                                    data-company="{{ $vo->user->company_name ?? ($vo->form_data['company_name'] ?? '—') }}"
                                    data-pic="{{ $vo->user->pic_name ?? ($vo->form_data['pic_name'] ?? '—') }}"
                                    data-phone="{{ $vo->user->phone ?? ($vo->form_data['pic_phone'] ?? '—') }}"
                                    data-url="{{ route('admin.virtual-office.guest-notification.store', $vo->id) }}">
                                    👥 Tamu Datang
                                </button>

                                {{-- Action Renew --}}
                                @php
                                    $isRenewable = in_array($vo->vo_status, ['Segera Berakhir', 'Expired']) || (isset($vo->sisa_hari) && $vo->sisa_hari <= 30);
                                @endphp

                                @if($isRenewable)
                                    <a href="{{ route('admin.orders.create', ['service' => 'virtual-office', 'renew_order_id' => $vo->id]) }}" 
                                       class="btn btn-sm btn-success d-flex align-items-center gap-1" 
                                       title="Perpanjang Layanan Virtual Office (H-30 / Expired)">
                                        🔄 Renew
                                    </a>
                                @else
                                    <button type="button" 
                                            class="btn btn-sm btn-secondary opacity-50 d-flex align-items-center gap-1" 
                                            disabled 
                                            title="Renew hanya dapat digunakan jika layanan H-30 atau sudah Expired (Saat ini sisa {{ $vo->sisa_hari }} hari)">
                                        🔄 Renew
                                    </button>
                                @endif

                                <a href="{{ route('admin.orders.show', $vo->id) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">Belum ada data Virtual Office.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    {{ $virtualOffices->appends(request()->query())->links() }}
</div>

<!-- Modal Notifikasi Surat Masuk -->
<div class="modal fade" id="mailNotificationModal" tabindex="-1" aria-labelledby="mailNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="mailNotificationModalLabel">
                    <ion-icon name="mail-outline" class="me-1"></ion-icon> Notifikasi Surat Masuk
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="mailNotificationForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Readonly Section: Info Client -->
                    <div class="bg-light p-3 rounded mb-4 border">
                        <h6 class="fw-bold mb-3 text-secondary">Informasi Client (Read-only)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nama Perusahaan / PT</label>
                                <input type="text" class="form-control form-control-sm bg-white fw-semibold" id="modal_company_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nama PIC</label>
                                <input type="text" class="form-control form-control-sm bg-white" id="modal_pic_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nomor WhatsApp</label>
                                <input type="text" class="form-control form-control-sm bg-white" id="modal_phone" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Input Section -->
                    <h6 class="fw-bold mb-3 text-primary">Detail Surat / Dokumen Masuk</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="received_date" class="form-label fw-semibold">Tanggal Terima <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="received_date" name="received_date" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="received_time" class="form-label fw-semibold">Jam Terima <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="received_time" name="received_time" required value="{{ date('H:i') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="sender_name" class="form-label fw-semibold">Pengirim <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="Contoh: PT Bank Central Asia / Kurir JNE" required>
                        </div>
                        <div class="col-md-6">
                            <label for="document_type" class="form-label fw-semibold">Jenis Dokumen <span class="text-danger">*</span></label>
                            <select class="form-select" id="document_type" name="document_type" required>
                                <option value="Surat" selected>Surat</option>
                                <option value="Paket">Paket</option>
                                <option value="Kartu Kredit">Kartu Kredit</option>
                                <option value="Dokumen Legal">Dokumen Legal</option>
                                <option value="Sertifikat">Sertifikat</option>
                                <option value="Invoice">Invoice</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="tracking_number" class="form-label">Nomor Resi <small class="text-muted">(Optional - Hanya disimpan ke database)</small></label>
                            <input type="text" class="form-control" id="tracking_number" name="tracking_number" placeholder="Contoh: JNE123456789">
                        </div>
                        <div class="col-md-12">
                            <label for="internal_note" class="form-label">Catatan Internal <small class="text-muted">(Optional - Hanya disimpan ke database)</small></label>
                            <textarea class="form-control" id="internal_note" name="internal_note" rows="2" placeholder="Catatan internal admin (misal: ditaruh di meja resepsionis)..."></textarea>
                        </div>
                    </div>

                    <!-- WhatsApp Live Preview Section -->
                    <div class="mt-4">
                        <label class="form-label fw-bold text-success d-flex align-items-center gap-1">
                            <ion-icon name="logo-whatsapp"></ion-icon> Live Preview Pesan WhatsApp Client
                        </label>
                        <div class="card bg-light border-success shadow-sm">
                            <div class="card-body p-3 font-monospace text-dark" style="font-size: 0.88rem; line-height: 1.6; white-space: pre-wrap;" id="whatsapp_preview_content">
                                <!-- Preview generated dynamically via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-1" id="btnSubmitMail">
                        <ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Notifikasi Tamu Datang -->
<div class="modal fade" id="guestNotificationModal" tabindex="-1" aria-labelledby="guestNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title text-dark fw-bold" id="guestNotificationModalLabel">
                    <ion-icon name="people-outline" class="me-1"></ion-icon> Notifikasi Tamu Datang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="guestNotificationForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Readonly Section: Info Client -->
                    <div class="bg-light p-3 rounded mb-4 border">
                        <h6 class="fw-bold mb-3 text-secondary">Informasi Client (Read-only)</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nama Perusahaan / PT</label>
                                <input type="text" class="form-control form-control-sm bg-white fw-semibold" id="modal_guest_company_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nama PIC</label>
                                <input type="text" class="form-control form-control-sm bg-white" id="modal_guest_pic_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small text-muted">Nomor WhatsApp Client</label>
                                <input type="text" class="form-control form-control-sm bg-white" id="modal_guest_phone" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Input Section -->
                    <h6 class="fw-bold mb-3 text-dark">Detail Data Tamu</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="guest_name" class="form-label fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="guest_name" name="guest_name" placeholder="Contoh: Bpk. Ahmad Subagyo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="guest_phone" class="form-label fw-semibold">Nomor HP Tamu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="guest_phone" name="guest_phone" placeholder="Contoh: 081234567890" required>
                        </div>
                        <div class="col-md-6">
                            <label for="guest_company" class="form-label fw-semibold">Instansi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="guest_company" name="guest_company" placeholder="Contoh: PT Telkom Indonesia / Pribadi" required>
                        </div>
                        <div class="col-md-6">
                            <label for="arrival_time" class="form-label fw-semibold">Jam Datang <span class="text-danger">*</span></label>
                            <input type="time" class="form-control" id="arrival_time" name="arrival_time" required value="{{ date('H:i') }}">
                        </div>
                        <div class="col-md-12">
                            <label for="purpose" class="form-label fw-semibold">Keperluan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="purpose" name="purpose" rows="2" placeholder="Contoh: Diskusi penawaran kerjasama dan penyerahan proposal" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="guest_internal_note" class="form-label">Catatan Internal <small class="text-muted">(Optional - Hanya disimpan ke database)</small></label>
                            <textarea class="form-control" id="guest_internal_note" name="internal_note" rows="2" placeholder="Catatan internal admin (misal: tamu menunggu di lobby utama)..."></textarea>
                        </div>
                    </div>

                    <!-- WhatsApp Live Preview Section -->
                    <div class="mt-4">
                        <label class="form-label fw-bold text-success d-flex align-items-center gap-1">
                            <ion-icon name="logo-whatsapp"></ion-icon> Live Preview Pesan WhatsApp Client
                        </label>
                        <div class="card bg-light border-success shadow-sm">
                            <div class="card-body p-3 font-monospace text-dark" style="font-size: 0.88rem; line-height: 1.6; white-space: pre-wrap;" id="guest_whatsapp_preview_content">
                                <!-- Preview generated dynamically via JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning px-4 fw-semibold text-dark d-flex align-items-center gap-1" id="btnSubmitGuest">
                        <ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ── Surat Masuk Modal Handler ─────────────────────────────────────────
        const modalEl = document.getElementById('mailNotificationModal');
        const modal = new bootstrap.Modal(modalEl);
        const form = document.getElementById('mailNotificationForm');

        const companyInput = document.getElementById('modal_company_name');
        const picInput = document.getElementById('modal_pic_name');
        const phoneInput = document.getElementById('modal_phone');
        const dateInput = document.getElementById('received_date');
        const timeInput = document.getElementById('received_time');
        const senderInput = document.getElementById('sender_name');
        const docTypeInput = document.getElementById('document_type');
        const previewEl = document.getElementById('whatsapp_preview_content');

        function formatDatePreview(dateStr) {
            if (!dateStr) return '-';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return dateStr;
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const day = String(d.getDate()).padStart(2, '0');
            const month = months[d.getMonth()];
            const year = d.getFullYear();
            return `${day} ${month} ${year}`;
        }

        function updatePreview() {
            const company = companyInput.value || '[Nama PT]';
            const rawDate = dateInput.value;
            const dateFormatted = formatDatePreview(rawDate);
            const time = timeInput.value || '[Jam]';
            const sender = senderInput.value || '[Pengirim]';

            previewEl.innerHTML = `<strong>Halo Ibu/Bapak, ${company}</strong>

Pemberitahuan: Kami telah menerima dokumen / surat masuk untuk perusahaan Anda dengan rincian berikut:

📌 <strong>DETAIL SURAT / DOKUMEN MASUK:</strong>
🏢 <strong>Perusahaan</strong>  : ${company}
📅 <strong>Tanggal Terima</strong>: ${dateFormatted}
🕒 <strong>Jam Terima</strong>    : ${time} WIB
👤 <strong>Pengirim</strong>      : ${sender}

━━━━━━━━━━━━━━━━━━
Apabila lebih dari 14 hari sejak notifikasi ini dokumen tidak diambil, maka kehilangan bukan menjadi tanggung jawab kami.

📖 Panduan lengkap pengambilan dokumen:
https://lawgika.co.id/virtual-office/panduan-pengambilan-dokumen

Terima kasih atas perhatian Anda! 😊

Salam,
<strong>Lawgika.co.id</strong>`;
        }

        document.querySelectorAll('.btn-mail-notification').forEach(button => {
            button.addEventListener('click', function() {
                const company = this.getAttribute('data-company');
                const pic = this.getAttribute('data-pic');
                const phone = this.getAttribute('data-phone');
                const actionUrl = this.getAttribute('data-url');

                companyInput.value = company;
                picInput.value = pic;
                phoneInput.value = phone;
                form.action = actionUrl;

                senderInput.value = '';
                document.getElementById('tracking_number').value = '';
                document.getElementById('internal_note').value = '';

                const now = new Date();
                dateInput.value = now.toISOString().split('T')[0];
                timeInput.value = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

                updatePreview();
                modal.show();
            });
        });

        dateInput.addEventListener('input', updatePreview);
        timeInput.addEventListener('input', updatePreview);
        senderInput.addEventListener('input', updatePreview);
        docTypeInput.addEventListener('change', updatePreview);

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmitMail');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...`;

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi`;

                    if (data.success) {
                        modal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Notifikasi surat berhasil dikirim ke WhatsApp Client.',
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan saat mengirim notifikasi.',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi`;
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Server',
                        text: 'Gagal terhubung ke server. Silakan coba lagi.',
                        confirmButtonColor: '#dc3545'
                    });
                });
        });

        // ── Tamu Datang Modal Handler ─────────────────────────────────────────
        const guestModalEl = document.getElementById('guestNotificationModal');
        const guestModal = new bootstrap.Modal(guestModalEl);
        const guestForm = document.getElementById('guestNotificationForm');

        const guestCompanyInput = document.getElementById('modal_guest_company_name');
        const guestPicInput     = document.getElementById('modal_guest_pic_name');
        const guestPhoneInput   = document.getElementById('modal_guest_phone');

        const guestNameInput    = document.getElementById('guest_name');
        const guestContactInput = document.getElementById('guest_phone');
        const guestInstansiInput= document.getElementById('guest_company');
        const arrivalTimeInput  = document.getElementById('arrival_time');
        const purposeInput      = document.getElementById('purpose');
        const guestPreviewEl    = document.getElementById('guest_whatsapp_preview_content');

        function updateGuestPreview() {
            const company  = guestCompanyInput.value || '[Nama PT]';
            const guest    = guestNameInput.value || '[Nama Tamu]';
            const contact  = guestContactInput.value || '[Kontak Tamu]';
            const instansi = guestInstansiInput.value || '[Instansi]';
            const time     = arrivalTimeInput.value || '[Jam Datang]';
            const purpose  = purposeInput.value || '[Keperluan]';

            guestPreviewEl.innerHTML = `<strong>Halo Ibu/Bapak, ${company}</strong>

Kami informasikan bahwa saat ini terdapat tamu yang datang dan menanyakan perusahaan Anda.

━━━━━━━━━━━━━━━━━━

Berikut data tamu:

👤 <strong>Nama</strong>       : ${guest}
📱 <strong>Kontak</strong>     : ${contact}
🏢 <strong>Instansi</strong>   : ${instansi}
🕒 <strong>Jam Datang</strong> : ${time} WIB
📝 <strong>Keperluan</strong>  : ${purpose}

━━━━━━━━━━━━━━━━━━

Silakan segera menghubungi tamu yang bersangkutan.

Terima kasih. 🙏

Salam,
<strong>Lawgika.co.id</strong>`;
        }

        document.querySelectorAll('.btn-guest-notification').forEach(button => {
            button.addEventListener('click', function() {
                const company   = this.getAttribute('data-company');
                const pic       = this.getAttribute('data-pic');
                const phone     = this.getAttribute('data-phone');
                const actionUrl = this.getAttribute('data-url');

                guestCompanyInput.value = company;
                guestPicInput.value     = pic;
                guestPhoneInput.value   = phone;
                guestForm.action        = actionUrl;

                guestNameInput.value    = '';
                guestContactInput.value = '';
                guestInstansiInput.value= '';
                purposeInput.value      = '';
                document.getElementById('guest_internal_note').value = '';

                const now = new Date();
                arrivalTimeInput.value = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');

                updateGuestPreview();
                guestModal.show();
            });
        });

        guestNameInput.addEventListener('input', updateGuestPreview);
        guestContactInput.addEventListener('input', updateGuestPreview);
        guestInstansiInput.addEventListener('input', updateGuestPreview);
        arrivalTimeInput.addEventListener('input', updateGuestPreview);
        purposeInput.addEventListener('input', updateGuestPreview);

        guestForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('btnSubmitGuest');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...`;

            const formData = new FormData(guestForm);

            fetch(guestForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi`;

                    if (data.success) {
                        guestModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Notifikasi tamu berhasil dikirim ke WhatsApp Client.',
                            confirmButtonColor: '#0d6efd'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message || 'Terjadi kesalahan saat mengirim notifikasi.',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = `<ion-icon name="paper-plane-outline"></ion-icon> Kirim Notifikasi`;
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Server',
                        text: 'Gagal terhubung ke server. Silakan coba lagi.',
                        confirmButtonColor: '#dc3545'
                    });
                });
        });
    });
</script>
@endpush
@endsection