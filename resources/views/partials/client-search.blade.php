{{--
    Reusable Client Search Component
    
    Usage: @include('partials.client-search', ['inputId' => 'client_search', 'showQuota' => true])
    
    Required: hidden input #selected_user_id is set when client is selected.
    Optional: JS callback window.onClientSelected(clientData) is called when a client is selected.
--}}

<div class="card border-primary mb-3" id="client-search-card">
    <div class="card-header bg-primary text-white py-2">
        <h6 class="mb-0"><ion-icon name="search-outline"></ion-icon> Cari Client</h6>
    </div>
    <div class="card-body">
        <div class="position-relative">
            <input type="text" 
                   class="form-control form-control-lg" 
                   id="{{ $inputId ?? 'client_search' }}" 
                   placeholder="🔍 Ketik nama PT, PIC, telepon, atau email..." 
                   autocomplete="off">
            <input type="hidden" name="user_id" id="selected_user_id" value="">
            
            {{-- Dropdown results --}}
            <div id="client-search-results" class="list-group position-absolute w-100 shadow-lg" style="z-index:1050; max-height:300px; overflow-y:auto; display:none;"></div>
        </div>

        {{-- Selected client card --}}
        <div id="client-info-card" class="mt-3" style="display:none;">
            <div class="card border-success">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="text-success mb-0">
                            <ion-icon name="checkmark-circle"></ion-icon>
                            <span id="ci-company">-</span>
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearClientSelection()">
                            <ion-icon name="close-outline"></ion-icon> Ganti
                        </button>
                    </div>
                    
                    <div class="row g-2">
                        <div class="col-md-6">
                            <small class="text-muted d-block">PIC</small>
                            <strong id="ci-pic">-</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">WhatsApp / Telepon</small>
                            <strong id="ci-phone">-</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <strong id="ci-email">-</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bidang Usaha</small>
                            <strong id="ci-business">-</strong>
                        </div>
                    </div>

                    @if($showQuota ?? true)
                    <hr class="my-2">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="card bg-light border-0 p-2">
                                <small class="text-muted fw-bold">📋 Quota Meeting Room</small>
                                <div class="d-flex gap-3 mt-1">
                                    <small>Total: <strong id="ci-meeting-total">0</strong> jam</small>
                                    <small>Dipakai: <strong id="ci-meeting-used">0</strong> jam</small>
                                    <small class="text-success">Sisa: <strong id="ci-meeting-remaining">0</strong> jam</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0 p-2">
                                <small class="text-muted fw-bold">🎙️ Quota Podcast Room</small>
                                <div class="d-flex gap-3 mt-1">
                                    <small>Total: <strong id="ci-podcast-total">0</strong> jam</small>
                                    <small>Dipakai: <strong id="ci-podcast-used">0</strong> jam</small>
                                    <small class="text-success">Sisa: <strong id="ci-podcast-remaining">0</strong> jam</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Active packages --}}
                    <div id="ci-orders-section" class="mt-2" style="display:none;">
                        <small class="text-muted fw-bold">📦 Paket Aktif</small>
                        <div id="ci-orders-list" class="mt-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const searchInput = document.getElementById('{{ $inputId ?? "client_search" }}');
    const resultsDiv  = document.getElementById('client-search-results');
    const infoCard    = document.getElementById('client-info-card');
    const hiddenInput = document.getElementById('selected_user_id');
    let debounceTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        const q = this.value.trim();
        
        if (q.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch('{{ route("admin.users.search") }}?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(clients => {
                    resultsDiv.innerHTML = '';
                    if (clients.length === 0) {
                        resultsDiv.innerHTML = '<div class="list-group-item text-muted text-center py-3">Tidak ditemukan. <a href="{{ route("admin.users.create") }}">Tambah Client Baru</a></div>';
                    } else {
                        clients.forEach(c => {
                            const item = document.createElement('a');
                            item.href = '#';
                            item.className = 'list-group-item list-group-item-action py-2';
                            item.innerHTML = `
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${c.company_name || '-'}</strong>
                                        <br><small class="text-muted">PIC: ${c.pic_name || c.name} | ${c.phone || '-'} | ${c.email || '-'}</small>
                                    </div>
                                    <ion-icon name="chevron-forward-outline" class="text-muted"></ion-icon>
                                </div>
                            `;
                            item.addEventListener('click', function(e) {
                                e.preventDefault();
                                selectClient(c);
                            });
                            resultsDiv.appendChild(item);
                        });
                    }
                    resultsDiv.style.display = 'block';
                });
        }, 300);
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
            resultsDiv.style.display = 'none';
        }
    });

    function selectClient(c) {
        hiddenInput.value = c.id;
        searchInput.value = c.company_name || c.name;
        searchInput.readOnly = true;
        resultsDiv.style.display = 'none';

        // Fill info card
        document.getElementById('ci-company').textContent = c.company_name || '-';
        document.getElementById('ci-pic').textContent = c.pic_name || c.name;
        document.getElementById('ci-phone').textContent = c.phone || '-';
        document.getElementById('ci-email').textContent = c.email || '-';
        
        const biz = document.getElementById('ci-business');
        if (biz) biz.textContent = c.business_type || '-';

        // Quota (in minutes → convert to hours for display)
        const setQuota = (prefix, data) => {
            const totalEl = document.getElementById(`ci-${prefix}-total`);
            const usedEl  = document.getElementById(`ci-${prefix}-used`);
            const remEl   = document.getElementById(`ci-${prefix}-remaining`);
            if (totalEl && data) {
                totalEl.textContent = Math.round(data.total / 60);
                usedEl.textContent  = Math.round(data.used / 60);
                remEl.textContent   = Math.round(data.remaining / 60);
            }
        };
        setQuota('meeting', c.meeting_quota);
        setQuota('podcast', c.podcast_quota);

        // Active orders
        const ordersSection = document.getElementById('ci-orders-section');
        const ordersList    = document.getElementById('ci-orders-list');
        if (ordersSection && c.active_orders && c.active_orders.length > 0) {
            ordersList.innerHTML = c.active_orders.map(o => 
                `<span class="badge bg-success me-1 mb-1">${o.service_name} (${o.created_at})</span>`
            ).join('');
            ordersSection.style.display = 'block';
        } else if (ordersSection) {
            ordersSection.style.display = 'none';
        }

        infoCard.style.display = 'block';

        // Callback for parent form
        if (typeof window.onClientSelected === 'function') {
            window.onClientSelected(c);
        }
    }

    // Expose selectClient function globally for auto-selecting on page load (e.g. Renew mode)
    window.selectClientDirectly = selectClient;

    window.clearClientSelection = function() {
        hiddenInput.value = '';
        searchInput.value = '';
        searchInput.readOnly = false;
        searchInput.focus();
        infoCard.style.display = 'none';

        // Callback for parent form
        if (typeof window.onClientCleared === 'function') {
            window.onClientCleared();
        }
    };
})();
</script>
@endpush
