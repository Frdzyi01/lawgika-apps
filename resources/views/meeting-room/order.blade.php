@extends('layout.app')

@section('content')

    <style>
        /* ===== MINIMAL CSS ===== */
        :root {
            --primary: #4e0516;
            --primary-light: #7a0a23;
            --accent: #c9a03d;
            --dark: #1e1b2b;
            --gray: #64748b;
            --bg-light: #fdf8f5;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            color: var(--dark);
            background: var(--bg-light);
        }

        .order-container {
            max-width: 600px;
            margin: 60px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0e4e8;
            padding: 40px;
        }

        .order-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .order-header h2 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .order-header p {
            color: var(--gray);
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            color: #334155;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(78, 5, 22, 0.1);
        }

        .btn-submit {
            display: block;
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s ease;
            text-align: center;
            margin-top: 30px;
        }

        .btn-submit:hover {
            background: var(--primary-light);
        }

        .btn-submit i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .order-container {
                margin: 30px 15px;
                padding: 30px 20px;
            }
        }
    </style>

    <div class="container pt-5 pb-5 mt-5" style="margin-top: 80px !important">
        <div class="order-container">
            <div class="order-header">
                @if (request('package') == 'paket')
                    <h2 data-i18n="mr.buy_package_title">Beli Paket Meeting Room</h2>
                    <p data-i18n="mr.buy_package_subtitle">Dapatkan akses 60 jam meeting room untuk 1 tahun</p>
                @else
                    <h2 data-i18n="mr.reserv_title">Reservasi Meeting Room</h2>
                    <p data-i18n="mr.reserv_subtitle">Untuk melakukan reservasi, silakan isi informasi berikut:</p>
                @endif
            </div>

            @if ($errors->any())
                <div
                    style="padding:15px;border-radius:10px;margin-bottom:20px;background:#fee2e2;color:#991b1b;border:1px solid #f87171;">
                    <ul class="mb-0" style="padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div style="padding:15px;border-radius:10px;margin-bottom:20px;background:#fee2e2;color:#991b1b;border:1px solid #f87171;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('meeting-room.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="nama" data-i18n="order.label_fullname">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" required
                        placeholder="Masukkan nama Anda" data-i18n-placeholder="mr.placeholder_fullname" value="{{ old('nama', auth()->user()->name ?? '') }}" readonly>
                </div>

                <div class="form-group">
                    <label for="nama_perusahaan" data-i18n="order.label_company_name">Nama Perusahaan</label>
                    <input type="text" id="nama_perusahaan" name="nama_perusahaan" class="form-control" required
                        placeholder="Masukkan nama perusahaan" data-i18n-placeholder="mr.placeholder_company_name" value="{{ old('nama_perusahaan', request('package') == 'reservasi' ? ($ptData['company_name'] ?? '') : '') }}" {{ request('package') == 'reservasi' ? 'readonly' : '' }}>
                </div>

                <div class="form-group">
                    <label for="email" data-i18n="order.label_email">Alamat Email</label>
                    <input type="email" id="email" name="email" class="form-control" required
                        placeholder="Masukkan alamat email" data-i18n-placeholder="mr.placeholder_email" value="{{ old('email', request('package') == 'reservasi' ? ($ptData['company_email'] ?? '') : '') }}" {{ request('package') == 'reservasi' ? 'readonly' : '' }}>
                </div>

                <div class="form-group">
                    <label for="alamat_usaha" data-i18n="mr.label_business_address">Alamat Aktivitas Usaha</label>
                    <textarea id="alamat_usaha" name="alamat_usaha" class="form-control" required
                        placeholder="Masukkan alamat aktivitas usaha" data-i18n-placeholder="mr.placeholder_business_address" rows="3" {{ request('package') == 'reservasi' ? 'readonly' : '' }}>{{ old('alamat_usaha', request('package') == 'reservasi' ? ($ptData['operational_address'] ?? '') : '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="bidang_usaha" data-i18n="order.label_business_field">Bidang Usaha</label>
                    <input type="text" id="bidang_usaha" name="bidang_usaha" class="form-control" required
                        placeholder="Masukkan bidang usaha" data-i18n-placeholder="mr.placeholder_business_field" value="{{ old('bidang_usaha', request('package') == 'reservasi' ? ($ptData['business_field'] ?? '') : '') }}" {{ request('package') == 'reservasi' ? 'readonly' : '' }}>
                </div>

                <div class="form-group">
                    <label for="keperluan" data-i18n="mr.label_meeting_purpose">Keperluan Meeting Room</label>
                    <textarea id="keperluan" name="keperluan" class="form-control" required
                        placeholder="Masukkan keperluan penggunaan meeting room" data-i18n-placeholder="mr.placeholder_meeting_purpose" rows="3">{{ old('keperluan') }}</textarea>
                </div>

                @if (request('package') == 'paket')

                    <!-- Hidden fields for package purchase -->
                    <input type="hidden" name="package" value="paket">
                    <input type="hidden" name="durasi" value="60">
                    <input type="hidden" name="peserta" value="1">

                    <div
                        style="background:#f0fdf4; border:1px solid #86efac; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <h5 style="color:#15803d; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-box"></i>
                            <span data-i18n="mr.package_box_title">Paket Meeting Room</span></h5>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="color:#166534;" data-i18n="mr.package_duration">Durasi Paket</span>
                            <strong style="color:#14532d;">60 Jam</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="color:#166534;" data-i18n="mr.package_validity">Masa Berlaku</span>
                            <strong style="color:#14532d;">1 Tahun</strong>
                        </div>
                        <div
                            style="display:flex; justify-content:space-between; border-top:1px dashed #86efac; padding-top:8px; margin-top:8px;">
                            <span style="color:#166534;" data-i18n="mr.package_price">Harga Paket</span>
                            <strong style="color:#15803d; font-size:1.2rem;">Rp 4.800.000</strong>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <label for="tanggal" data-i18n="mr.label_use_date">Tanggal Penggunaan</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control" required
                            value="{{ old('tanggal', $tanggal ?? '') }}">
                    </div>

                    <div class="form-group">
                        <label for="jam" data-i18n="mr.label_use_time">Jam Penggunaan</label>
                        <input type="time" id="jam" name="jam" class="form-control" required
                            value="{{ old('jam', $jam ?? '') }}">
                    </div>

                    @if (request('package') == 'reservasi')
                        <input type="hidden" name="peserta" value="1">
                        <input type="hidden" name="durasi" value="1">
                    @else
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="peserta" data-i18n="mr.label_participants">Jumlah Peserta</label>
                                    <input type="number" id="peserta" name="peserta" class="form-control" min="1"
                                        required placeholder="Contoh: 8" data-i18n-placeholder="mr.placeholder_participants" value="{{ old('peserta') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="durasi" data-i18n="mr.label_rent_duration">Durasi Sewa (Jam)</label>
                                    <input type="number" id="durasi" name="durasi" class="form-control" min="1"
                                        required placeholder="Contoh: 2" data-i18n-placeholder="mr.placeholder_rent_duration" value="{{ old('durasi', $durasi ?? 1) }}"
                                        onchange="updateTotal()">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if(request('package') == 'reservasi')
                    <div
                        style="background:#fdf2f8; border:1px solid #fbcfe8; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <h5 style="color:#be185d; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-gem"></i> <span data-i18n="mr.benefit_box_title">Benefit Paket Pendirian PT</span></h5>
                        <p style="margin-bottom:8px; color:#831843;" data-i18n="mr.benefit_box_desc">Anda memiliki benefit reservasi Meeting Room dari Paket Pendirian PT. Silakan lengkapi form reservasi atas waktu dan tanggal yang diinginkan, dan pesanan akan diteruskan ke admin.</p>
                        @if(isset($activeBenefit))
                            <p style="margin-bottom:0; color:#831843;">
                                <span data-i18n="mr.remaining_quota">Sisa quota Anda:</span> <strong>{{ \App\Models\RoomBenefit::formatMinutes($activeBenefit->remaining_minutes) }}</strong>
                                (Berlaku hingga {{ $activeBenefit->expired_at ? $activeBenefit->expired_at->format('d M Y') : __('mr.no_expired') }})
                            </p>
                        @endif
                        <input type="hidden" name="use_quota" value="1">
                    </div>
                @elseif (isset($quota) && !now()->greaterThan($quota->expired_at) && $quota->remaining_seconds > 0)
                    <div
                        style="background:#fdf2f8; border:1px solid #fbcfe8; border-radius:10px; padding:20px; margin-bottom:20px;">
                        <h5 style="color:#be185d; font-weight:700; margin-bottom:10px;"><i class="fa-solid fa-gem"></i> <span data-i18n="mr.has_quota_title">Anda Memiliki Quota Ruangan!</span></h5>
                        <p style="margin-bottom:15px; color:#831843;"><span data-i18n="mr.remaining_quota">Sisa quota Anda:</span>
                            <strong>{{ $quota->formatted_remaining_time }}</strong> (Berlaku hingga
                            {{ \Carbon\Carbon::parse($quota->expired_at)->format('d M Y') }})
                        </p>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="use_quota" id="use_quota" value="1"
                                checked onchange="togglePaymentProof()">
                            <label class="form-check-label fw-bold text-dark" style="margin-bottom:0;" for="use_quota">
                                <span data-i18n="mr.use_quota_label">Gunakan Quota untuk Reservasi ini (Bebas Biaya)</span>
                            </label>
                        </div>
                    </div>
                @endif

                @if(request('package') != 'reservasi')
                    <!-- Manual Transfer Instructions -->
                    <div id="payment-section">
                        <div
                            style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:20px; margin-bottom:20px;">
                            <h5 style="font-size:1.05rem; font-weight:700; color:var(--dark); margin-bottom:15px;"><i
                                    class="fa-solid fa-building-columns"></i> <span data-i18n="order.payment_instruction_transfer">Instruksi Pembayaran (Transfer Bank)</span></h5>
                            <p style="font-size:0.95rem; color:var(--gray); margin-bottom:10px;" data-i18n="order.transfer_instruction">Silakan lakukan pembayaran ke rekening berikut:</p>
                            <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom: 10px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">Bank</span>
                                    <strong style="color:#1e1b2b;">Mandiri</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">No. Rekening</span>
                                    <strong style="color:#1e1b2b; font-size:1.1rem; letter-spacing:1px;">1760005097561</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">Atas Nama</span>
                                    <strong style="color:#1e1b2b;">PT Lawgika Associates</strong>
                                </div>
                            </div>

                            <div style="background:#fff; padding:15px; border-radius:8px; border:1px solid #e2e8f0;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">Bank</span>
                                    <strong style="color:#1e1b2b;">BCA (Bank Central Asia)</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">No. Rekening</span>
                                    <strong style="color:#1e1b2b; font-size:1.1rem; letter-spacing:1px;">664-0800389</strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:8px;">
                                    <span style="color:#64748b; font-size:0.9rem;">Atas Nama</span>
                                    <strong style="color:#1e1b2b;">PT Lawgika Associates</strong>
                                </div>
                            </div>

                            <div style="margin-top:15px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:5px;">
                                    <span style="color:#64748b; font-size:0.95rem;">Subtotal:</span>
                                    <strong style="color:#1e1b2b; font-size:1.1rem;" id="subtotalDisplay">
                                        @if (request('package') == 'paket')
                                            Rp 4.800.000
                                        @else
                                            Rp 150.000
                                        @endif
                                    </strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:10px; border-bottom:1px solid #e2e8f0; padding-bottom:10px;">
                                    <span style="color:#64748b; font-size:0.95rem;">PPN 11%:</span>
                                    <strong style="color:#1e1b2b; font-size:1.1rem;" id="ppnDisplay">
                                        @if (request('package') == 'paket')
                                            Rp 528.000
                                        @else
                                            Rp 16.500
                                        @endif
                                    </strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <span style="color:#1e1b2b; font-size:1.1rem; font-weight:700;" data-i18n="order.total_bill">Total Pembayaran:</span>
                                    <strong style="color:var(--primary); font-size:1.4rem;" id="totalAmountDisplay">
                                        @if (request('package') == 'paket')
                                            Rp 5.328.000
                                        @else
                                            Rp 166.500
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            <input type="hidden" id="package" value="{{ request('package', 'reservasi') }}">
                        </div>

                        <div class="form-group">
                            <label for="payment_proof"><span data-i18n="order.upload_payment_proof">Upload Bukti Pembayaran</span> <span class="text-danger">*</span></label>
                            <div id="dropzone_box" style="border:2px dashed #cbd5e1; border-radius:12px; padding:20px; text-align:center; cursor:pointer; background:#fafafa; transition:all 0.2s ease;"
                                onclick="document.getElementById('payment_proof').click()">
                                
                                {{-- Empty / Initial State --}}
                                <div id="dropzone_default">
                                    <i class="fa-solid fa-cloud-arrow-up"
                                        style="font-size:2.2rem; color:var(--primary); margin-bottom:8px; display:block;"></i>
                                    <p style="color:var(--gray); margin:0; font-size:0.9rem; font-weight:600;" data-i18n="order.upload_proof_click">Klik untuk upload bukti transfer / pembayaran</p>
                                    <p style="color:#94a3b8; margin:4px 0 0; font-size:0.8rem;" data-i18n="order.upload_proof_hint_2mb">JPG, PNG, JPEG — Maks. 2MB</p>
                                </div>

                                {{-- Preview State --}}
                                <div id="dropzone_preview" style="display:none;">
                                    <div style="position:relative; display:inline-block; margin-bottom:10px;">
                                        <img id="image_preview_img" src="" alt="Bukti Transfer" style="max-height:140px; max-width:100%; border-radius:8px; border:1px solid #e2e8f0; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                                    </div>
                                    <div class="d-flex align-items-center justify-content-center gap-2 flex-wrap">
                                        <span class="badge bg-success" style="font-size:.78rem; color:#fff !important;"><i class="fa fa-circle-check me-1"></i>File Terpilih</span>
                                        <span id="file_name_display" style="color:var(--dark); font-weight:600; font-size:0.9rem;"></span>
                                        <span id="file_size_display" class="text-muted" style="font-size:0.8rem;"></span>
                                    </div>
                                    <p class="text-muted mb-0 mt-2" style="font-size:0.78rem;"><i class="fa fa-rotate me-1"></i>Klik untuk mengganti gambar</p>
                                </div>
                            </div>
                            <input type="file" id="payment_proof" name="payment_proof"
                                accept="image/jpeg,image/png,.jpg,.jpeg,.png" required style="display:none;"
                                onchange="showFileName(this)">
                        </div>
                    </div>

                    <div
                        style="background:#fef9c3; border:1px solid #fde047; border-radius:10px; padding:14px; margin-bottom:20px; font-size:0.9rem; color:#713f12;">
                        <strong data-i18n="order.info_label">⚡ Info:</strong> <span data-i18n="mr.info_desc_part1">Setelah reservasi, admin akan mengkonfirmasi pembayaran Anda. Check In hanya bisa dilakukan setelah pembayaran</span> <strong><span data-i18n="mr.info_desc_part2">disetujui</span></strong>.
                    </div>
                @else
                    <div
                        style="background:#fef9c3; border:1px solid #fde047; border-radius:10px; padding:14px; margin-bottom:20px; font-size:0.9rem; color:#713f12;">
                        <strong data-i18n="order.info_label">⚡ Info:</strong> <span data-i18n="mr.info_desc_direct">Pesanan reservasi ini akan langsung diteruskan ke Admin untuk proses konfirmasi jadwal.</span>
                    </div>
                @endif

                <div style="display:flex; gap:15px;">
                    <button type="submit" class="btn-submit" style="flex:1; margin-top:0;">
                        <i class="fa-solid fa-calendar-check"></i> <span data-i18n="mr.submit_btn">Pesan Sekarang</span>
                    </button>
                    <button type="button" class="btn-submit" onclick="sendWhatsApp()"
                        style="flex:1; margin-top:0; background:#25D366; color:#fff; border:none;">
                        <i class="fa-brands fa-whatsapp"></i> <span data-i18n="order.contact_wa_btn">Hubungi WhatsApp</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showFileName(input) {
            const defaultState = document.getElementById('dropzone_default');
            const previewState = document.getElementById('dropzone_preview');
            const imgPreview   = document.getElementById('image_preview_img');
            const nameDisplay  = document.getElementById('file_name_display');
            const sizeDisplay  = document.getElementById('file_size_display');
            const box          = document.getElementById('dropzone_box');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                if (nameDisplay) nameDisplay.textContent = file.name;
                if (sizeDisplay) {
                    const sizeInMB = (file.size / (1024 * 1024)).toFixed(2);
                    sizeDisplay.textContent = `(${sizeInMB} MB)`;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (imgPreview) imgPreview.src = e.target.result;
                    if (defaultState) defaultState.style.display = 'none';
                    if (previewState) previewState.style.display = 'block';
                    if (box) {
                        box.style.border = '2px solid #22c55e';
                        box.style.background = '#f0fdf4';
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        const hargaPerJam = 150000;
        const hargaPaket = 4800000;

        function updateTotal() {
            const packageEl = document.getElementById('package');
            if (!packageEl) return;
            const packageType = packageEl.value;
            let subtotal = 0;

            if (packageType === 'paket') {
                subtotal = hargaPaket;
            } else {
                const durasiEl = document.getElementById('durasi');
                const durasi = durasiEl ? (parseInt(durasiEl.value) || 1) : 1;
                subtotal = durasi * hargaPerJam;
            }
            
            const ppn = Math.round(subtotal * 0.11);
            const total = subtotal + ppn;

            const subtotalDisp = document.getElementById('subtotalDisplay');
            const ppnDisp = document.getElementById('ppnDisplay');
            const totalAmountDisp = document.getElementById('totalAmountDisplay');

            if (subtotalDisp) subtotalDisp.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            if (ppnDisp) ppnDisp.innerText = 'Rp ' + ppn.toLocaleString('id-ID');
            if (totalAmountDisp) totalAmountDisp.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function sendWhatsApp() {
            const nama = document.getElementById('nama') ? document.getElementById('nama').value : '';
            const nama_perusahaan = document.getElementById('nama_perusahaan') ? document.getElementById('nama_perusahaan').value : '';
            const email = document.getElementById('email') ? document.getElementById('email').value : '';
            const alamat_usaha = document.getElementById('alamat_usaha') ? document.getElementById('alamat_usaha').value : '';
            const bidang_usaha = document.getElementById('bidang_usaha') ? document.getElementById('bidang_usaha').value : '';
            const keperluan = document.getElementById('keperluan') ? document.getElementById('keperluan').value : '';
            const packageEl = document.getElementById('package');
            const packageType = packageEl ? packageEl.value : 'reservasi';

            if (packageType === 'paket') {
                if (!nama_perusahaan || !email || !alamat_usaha || !bidang_usaha || !keperluan) {
                    alert('Mohon lengkapi semua data formulir terlebih dahulu.');
                    return;
                }

                const text = `Halo Admin Lawgika, saya ingin memverifikasi pembelian Paket Meeting Room:

- Nama: ${nama || '-'}
- Nama Perusahaan: ${nama_perusahaan}
- Email: ${email}
- Alamat Usaha: ${alamat_usaha}
- Bidang Usaha: ${bidang_usaha}
- Keperluan: ${keperluan}
- Paket: 60 Jam Meeting Room
- Harga: Rp 4.800.000
- Masa Berlaku: 1 Tahun

Mohon konfirmasinya. Terima kasih.`;

                const phone = '6281112088600';
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
                window.open(url, '_blank');
            } else {
                const tanggal = document.getElementById('tanggal') ? document.getElementById('tanggal').value : '-';
                const jam = document.getElementById('jam') ? document.getElementById('jam').value : '-';
                const durasi = document.getElementById('durasi') ? document.getElementById('durasi').value : '-';
                const peserta = document.getElementById('peserta') ? document.getElementById('peserta').value : '-';

                if (!nama_perusahaan || !email || !alamat_usaha || !bidang_usaha || !keperluan) {
                    alert('Mohon lengkapi semua data formulir terlebih dahulu.');
                    return;
                }

                const text = `Halo Admin Lawgika, saya ingin memverifikasi pemesanan Meeting Room:

- Nama: ${nama || '-'}
- Nama Perusahaan: ${nama_perusahaan}
- Email: ${email}
- Alamat Usaha: ${alamat_usaha}
- Bidang Usaha: ${bidang_usaha}
- Keperluan: ${keperluan}
- Tanggal: ${tanggal}
- Jam: ${jam}
- Durasi: ${durasi} Jam
- Peserta: ${peserta} Orang
- Tipe Pemesanan: Reservasi Reguler/Gunakan Kuota

Mohon konfirmasinya. Terima kasih.`;

                const phone = '6281112088600';
                const url = `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
                window.open(url, '_blank');
            }
        }

        function togglePaymentProof() {
            const useQuota = document.getElementById('use_quota');
            const paymentSection = document.getElementById('payment-section');
            const paymentProofInput = document.getElementById('payment_proof');
            if (useQuota && useQuota.checked) {
                paymentSection.style.display = 'none';
                paymentProofInput.required = false;
            } else {
                paymentSection.style.display = 'block';
                paymentProofInput.required = true;
            }
        }

        // Inisialisasi total saat load
        document.addEventListener('DOMContentLoaded', () => {
            updateTotal();
            togglePaymentProof();
        });
    </script>
    </div>
    </div>


@endsection
