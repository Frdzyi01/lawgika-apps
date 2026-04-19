@extends('layout.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-body p-5 text-center">
                    <h2 class="fw-bold mb-3">{{ $service->name }}</h2>
                    <p class="text-muted mb-4">{{ $service->description ?? 'Layanan profesional kami siap membantu kebutuhan bisnis Anda.' }}</p>
                    
                    @if($service->price)
                        <h4 class="text-primary mb-4">Rp {{ number_format($service->price, 0, ',', '.') }}</h4>
                    @endif

                    <!-- Tombol Pemicu -->
                    <button type="button" @click="$dispatch('open-order-modal', { id: {{ $service->id }}, name: '{{ $service->name }}' })" class="btn btn-primary btn-lg px-5">
                        Pesan Sekarang
                    </button>
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="text-decoration-none">Kembali ke Beranda</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Komponen Alpine.js Modal (Sangat ringan, x-cloak mencegah flicker) -->
<div x-data="orderManager()" 
     @open-order-modal.window="openModal($event.detail)" 
     x-show="isOpen" 
     x-cloak 
     class="modal fade show custom-modal-overlay" 
     style="display: block; background: rgba(0,0,0,0.5);"
     x-transition.opacity.duration.150ms>
     
    <div class="modal-dialog modal-dialog-centered" @click.away="isOpen = false">
        <div class="modal-content shadow-lg border-0 rounded-3">
        
            <!-- Jika Belum Login -->
            <template x-if="!isAuth">
                <div class="modal-body text-center p-5">
                    <h5 class="mb-4">Silakan login atau register untuk melanjutkan pemesanan.</h5>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                    </div>
                    <button type="button" @click="isOpen = false" class="btn btn-link mt-3 text-muted text-decoration-none">Tutup</button>
                </div>
            </template>

            <!-- Form Order (Jika Auth) -->
            <template x-if="isAuth && !isSuccess">
                <form @submit.prevent="submitOrder">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" x-text="serviceName"></h5>
                        <button type="button" class="btn-close" @click="isOpen = false" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Loading indicator per-field fetch -->
                        <div x-show="isFetchingFields" class="text-muted small mb-3">Memuat formulir dinamis...</div>

                        <template x-for="(field, index) in configFields" :key="index">
                            <div class="mb-3">
                                <label x-text="field.label" class="form-label fw-bold small"></label>
                                <input :type="field.type" x-model="formData[field.name]" :required="field.required" class="form-control">
                            </div>
                        </template>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Catatan Tambahan (Opsional)</label>
                            <textarea x-model="notes" rows="2" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="button" @click="isOpen = false" class="btn btn-light">Batal</button>
                        <button type="submit" :disabled="isLoading || configFields.length === 0" class="btn btn-primary">
                            <span x-text="isLoading ? 'Memproses...' : 'Kirim Pesanan'"></span>
                        </button>
                    </div>
                </form>
            </template>

            <!-- State Sukses -->
            <template x-if="isSuccess">
                <div class="modal-body text-center p-5">
                    <div class="text-success mb-3">
                        <ion-icon name="checkmark-circle" style="font-size: 64px;"></ion-icon>
                    </div>
                    <h4 class="fw-bold mb-2">Pesanan Berhasil</h4>
                    <p class="text-muted mb-4">Pesanan Anda telah kami terima dan akan segera diproses oleh Admin.</p>
                    <a href="{{ route('customer.orders.index') }}" class="btn btn-primary px-4">Lihat Pesanan Saya</a>
                </div>
            </template>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .custom-modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1050; overflow-x: hidden; overflow-y: auto; outline: 0; }
</style>
@endsection

@push('scripts')
<!-- Add AlpineJS via CDN logically -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('orderManager', () => ({
        isOpen: false,
        isAuth: {{ auth()->check() ? 'true' : 'false' }},
        isLoading: false,
        isFetchingFields: false,
        isSuccess: false,
        serviceId: null,
        serviceName: '',
        configFields: [],
        formData: {},
        notes: '',

        openModal(detail) {
            this.serviceId = detail.id;
            this.serviceName = detail.name;
            this.isSuccess = false;
            this.isOpen = true;
            
            if(this.isAuth && this.configFields.length === 0) {
                this.loadFields();
            }
        },

        async loadFields() {
            this.isFetchingFields = true;
            try {
                const res = await fetch(`/api/services/${this.serviceId}/fields`);
                const data = await res.json();
                this.configFields = data.fields;
                this.configFields.forEach(f => this.formData[f.name] = '');
            } catch (err) {
                console.error('Gagal memuat form');
            } finally {
                this.isFetchingFields = false;
            }
        },

        async submitOrder() {
            this.isLoading = true;
            try {
                const payload = {
                    service_id: this.serviceId,
                    form_data: this.formData,
                    notes: this.notes
                };

                const res = await fetch('/api/orders', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                if(res.ok) {
                    this.isSuccess = true;
                    this.formData = {}; this.notes = ''; // reset
                } else {
                    const err = await res.json();
                    alert(err.message || 'Gagal mengirim pesanan');
                }
            } catch (err) {
                alert('Koneksi terputus');
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
</script>
@endpush
