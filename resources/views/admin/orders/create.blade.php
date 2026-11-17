@extends('layouts-admin.admin')

@section('title', 'Buat Pesanan Baru - Admin')

@section('content')
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Manajemen Pesanan</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item">
                    <a href="javascript:;"><ion-icon name="home-outline"></ion-icon></a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">Semua Pesanan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Buat Pesanan Baru
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        
        @if(isset($renewOrder) && $renewOrder)
        <div class="alert alert-info border-0 shadow-sm d-flex align-items-center justify-content-between mb-3">
            <div>
                <h6 class="alert-heading mb-1 text-info fw-bold"><ion-icon name="refresh-circle-outline" class="align-middle me-1" style="font-size: 1.3rem;"></ion-icon> Form Perpanjangan (Renew) Layanan</h6>
                <small class="mb-0">Memperpanjang layanan <strong>{{ $renewOrder->service_name ?? 'Virtual Office' }}</strong> untuk <strong>{{ $preselectedClient->company_name ?? ($preselectedClient->pic_name ?? $preselectedClient->name) }}</strong> (Order Referensi: <code>{{ $renewOrder->order_number }}</code>).</small>
            </div>
            <span class="badge bg-primary fs-6">Mode Renew</span>
        </div>
        @endif

        {{-- Search Component --}}
        @include('partials.client-search', ['inputId' => 'search_client'])

        <div class="card radius-10">
            <div class="card-header py-3">
                <h6 class="mb-0"><ion-icon name="document-text-outline" class="align-middle"></ion-icon> Form Buat Pesanan (Admin)</h6>
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
                    <form action="{{ route('admin.orders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                    
                    {{-- Hidden input for user_id --}}
                    <input type="hidden" name="user_id" id="form_user_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Invoice</label>
                            <input type="text" name="invoice_number" class="form-control" value="{{ old('invoice_number') }}" placeholder="Contoh: INV-20261113-001 (kosongkan untuk otomatis)">
                            <small class="text-muted">Jika dikosongkan, sistem otomatis membuat nomor invoice unik.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Transaksi <span class="text-danger">*</span></label>
                            <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                            <small class="text-muted">Tanggal transaksi menjadi acuan tanggal mulai &amp; masa berlaku benefit sesuai durasi paket.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Layanan <span class="text-danger">*</span></label>
                            <select name="service" class="form-select" id="service_select" required>
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($services as $key => $service)
                                    <option value="{{ $key }}" {{ (old('service') ?? request('service')) == $key ? 'selected' : '' }}>{{ $service['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Paket Layanan <span class="text-danger">*</span></label>
                            <select name="package" class="form-select" id="package_select" required>
                                <option value="">-- Pilih Paket --</option>
                                {{-- Options akan diisi via JavaScript berdasarkan Layanan --}}
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Durasi Paket (Bulan) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="duration_months" id="duration_months_input" class="form-control"
                                    value="{{ old('duration_months', 12) }}" min="1" max="120" step="1" required
                                    placeholder="Masukkan jumlah bulan (misal: 12, 13, 24, 26)">
                                <span class="input-group-text">Bulan</span>
                            </div>
                            <div id="duration_info_box" class="small mt-1 px-2 py-1 rounded bg-light border text-dark"></div>
                        </div>

                        <div class="col-md-12" id="modal_dasar_container" style="display:none;">
                            <label class="form-label">Skala Modal Dasar (Khusus Pendirian PT)</label>
                            <select name="modal_dasar" class="form-select">
                                <option value="">-- Pilih Skala --</option>
                                <option value="Di bawah 1 Miliar">Di bawah 1 Miliar</option>
                                <option value="Di atas 1 Miliar">Di atas 1 Miliar</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Total Harga (Kosongkan untuk otomatis) <small class="text-muted">Gunakan angka saja, misal 5000000</small></label>
                            <input type="number" name="total_price" class="form-control" value="{{ old('total_price') }}" placeholder="Contoh: 5000000">
                        </div>

                        <hr class="my-3">

                        <div class="col-md-6">
                            <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                            <select name="payment_status" class="form-select" required>
                                @foreach(\App\Models\Order::ADMIN_PAYMENT_OPTIONS as $psKey => $psInfo)
                                    <option value="{{ $psKey }}" {{ old('payment_status', 'verified') === $psKey ? 'selected' : '' }}>{{ $psInfo['label'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Status Pesanan <span class="text-danger">*</span></label>
                            @if(auth()->user()->canApproveOrders())
                            <select name="status" class="form-select" required>
                                <option value="pending_approval" {{ old('status', 'pending_approval') === 'pending_approval' ? 'selected' : '' }}>Pending Approval (Menunggu Persetujuan)</option>
                                <option value="approved" {{ old('status') === 'approved' ? 'selected' : '' }}>Approved (Setujui &amp; Aktifkan Benefit Sesuai Tgl Transaksi)</option>
                            </select>
                            @else
                            <input type="text" class="form-control bg-light" value="Pending Approval (Menunggu Admin 3 / SPV)" readonly>
                            <input type="hidden" name="status" value="pending_approval">
                            <small class="text-muted">Pesanan yang disubmit Admin 1 otomatis berstatus Pending Approval.</small>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <label class="form-label">Bukti Pembayaran (Opsional - JPG/PNG/PDF, max 10MB)</label>
                            <input type="file" name="payment_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label">Catatan Internal (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Catatan internal admin...">{{ old('notes') }}</textarea>
                        </div>
                        
                        <div class="col-12" id="dynamic_document_requirements">
                            <!-- File inputs akan dirender di sini via JS -->
                        </div>

                        <div class="col-12 mt-4 text-end">
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <ion-icon name="save-outline" class="align-middle"></ion-icon> Simpan Pesanan
                            </button>
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
    // Capture the client selected from the partial component
    window.onClientSelected = function(clientData) {
        document.getElementById('form_user_id').value = clientData.id;
        
        // Remove overlay so form can be filled
        const overlay = document.getElementById('form_overlay');
        if (overlay) overlay.style.display = 'none';
    };

    window.onClientCleared = function() {
        document.getElementById('form_user_id').value = '';
        
        // Show overlay again
        const overlay = document.getElementById('form_overlay');
        if (overlay) overlay.style.display = 'block';
    };

    // Form submit validation to ensure client is selected
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!document.getElementById('form_user_id').value) {
            e.preventDefault();
            alert('Silakan Cari dan Pilih Client terlebih dahulu di bagian atas sebelum menyimpan pesanan.');
            document.getElementById('search_client').focus();
        }
    });
    
    // Data paket dari backend
    const allPackages = @json($packages);
    const activePackagesPerService = @json($activePackages);
    const oldPackage = "{{ old('package') }}";

    function updatePackageDropdown(service) {
        const packageSelect = document.getElementById('package_select');
        packageSelect.innerHTML = '<option value="">-- Pilih Paket --</option>';
        
        if (service && activePackagesPerService[service]) {
            const allowedKeys = activePackagesPerService[service];
            allowedKeys.forEach(function(key) {
                // Untuk virtual-office kita pakai penamaan spesifik
                let label = allPackages[key] || key;
                if (service === 'virtual-office') {
                    if (key === 'premium') label = 'Paket Premium';
                    else if (key === 'eksklusif') label = 'Paket Eksklusif';
                    else if (key === 'enterprise') label = 'Paket Enterprise';
                }

                const option = document.createElement('option');
                option.value = key;
                option.textContent = `${label} (${key})`;
                
                if (oldPackage === key) {
                    option.selected = true;
                }
                
                packageSelect.appendChild(option);
            });
        }
    }

    function fetchDocumentRequirements(service) {
        const container = document.getElementById('dynamic_document_requirements');
        container.innerHTML = ''; // Kosongkan dulu
        
        if (!service) return;

        fetch(`/admin/orders/document-requirements/${service}`)
            .then(res => res.json())
            .then(reqs => {
                if (reqs && reqs.length > 0) {
                    let html = '<hr class="my-3"><h6 class="fw-bold mb-3">Upload Dokumen Persyaratan (Opsional)</h6><div class="row g-3">';
                    reqs.forEach(req => {
                        html += `
                            <div class="col-md-6">
                                <label class="form-label">${req.label} <small class="text-muted">(${req.document_type})</small></label>
                                <input type="file" name="documents[${req.document_type}][]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                }
            })
            .catch(err => console.error('Gagal mengambil syarat dokumen', err));
    }

    const serviceSelect = document.getElementById('service_select');
    
    // Toggle modal dasar for pendirian-pt
    serviceSelect.addEventListener('change', function() {
        if(this.value === 'pendirian-pt') {
            document.getElementById('modal_dasar_container').style.display = 'block';
        } else {
            document.getElementById('modal_dasar_container').style.display = 'none';
        }

        // Update dropdown paket
        updatePackageDropdown(this.value);
        // Update form upload
        fetchDocumentRequirements(this.value);
    });

    // Jalankan saat load jika ada old value
    if (serviceSelect.value) {
        updatePackageDropdown(serviceSelect.value);
        fetchDocumentRequirements(serviceSelect.value);
    }

    const durationInput = document.getElementById('duration_months_input');
    const durationInfoBox = document.getElementById('duration_info_box');
    function updateDurationSummary() {
        if (!durationInput || !durationInfoBox) return;
        const raw = parseInt(durationInput.value, 10);
        if (isNaN(raw) || raw < 1) {
            durationInfoBox.innerHTML = '<i class="fa fa-info-circle text-primary me-1"></i>Default <strong>1 Tahun (12 Bulan)</strong>. Benefit ruangan sesuai 1 tahun.';
            return;
        }
        if (raw < 12) {
            durationInfoBox.innerHTML = `<i class="fa fa-circle-info text-warning me-1"></i>Input <strong>${raw} bulan (&lt; 12 bulan)</strong> &rarr; Paket tetap dihitung <strong>1 Tahun (12 Bulan)</strong> dan benefit hanya sesuai <strong>12 bulan</strong>.`;
            return;
        }
        const years = Math.floor(raw / 12);
        const bonus = raw % 12;
        if (bonus === 0) {
            durationInfoBox.innerHTML = `<i class="fa fa-check-circle text-success me-1"></i>Paket: <strong>${years} Tahun (${raw} Bulan)</strong>. Kuota benefit &amp; estimasi harga otomatis untuk <strong>${years} tahun</strong>.`;
        } else {
            durationInfoBox.innerHTML = `<i class="fa fa-gift text-success me-1"></i>Paket: <strong>${years} Tahun (${years * 12} Bulan) + Bonus ${bonus} Bulan dari Lawgika</strong> (Total Masa Aktif: <strong>${raw} Bulan</strong>). Benefit dasar sesuai ${years} tahun.`;
        }
    }
    if (durationInput) {
        durationInput.addEventListener('input', updateDurationSummary);
        durationInput.addEventListener('change', updateDurationSummary);
        updateDurationSummary();
    }

    // Auto-fill and pre-select data if preselectedClient or renewOrder is passed
    @if(isset($preselectedClient) && $preselectedClient)
        const preselectedClientData = {
            id: {{ $preselectedClient->id }},
            company_name: "{{ e($preselectedClient->company_name ?? '') }}",
            name: "{{ e($preselectedClient->name ?? '') }}",
            pic_name: "{{ e($preselectedClient->pic_name ?? $preselectedClient->name) }}",
            phone: "{{ e($preselectedClient->phone ?? '') }}",
            email: "{{ e($preselectedClient->email ?? '') }}",
            business_type: "{{ e($preselectedClient->business_type ?? '') }}"
        };

        setTimeout(() => {
            if (typeof window.selectClientDirectly === 'function') {
                window.selectClientDirectly(preselectedClientData);
            }
        }, 100);
    @endif

    @if(isset($renewOrder) && $renewOrder)
        // Set Layanan based on renewOrder
        const renewService = "{{ $renewOrder->form_data['service'] ?? 'virtual-office' }}";
        if (renewService) {
            serviceSelect.value = renewService;
            updatePackageDropdown(renewService);
            fetchDocumentRequirements(renewService);
        }

        // Set Paket based on renewOrder
        const renewPackage = "{{ $renewOrder->form_data['package'] ?? '' }}";
        if (renewPackage) {
            const packageSelect = document.getElementById('package_select');
            packageSelect.value = renewPackage;
        }
    @endif
</script>
@endpush
