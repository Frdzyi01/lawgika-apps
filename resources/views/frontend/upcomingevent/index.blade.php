@extends('layout.app')
@section('title', 'Beranda | Lawgika - Konsultan Legal & Bisnis')
@section('meta_description', 'Layanan Beranda terbaik dan terpercaya di Indonesia oleh Lawgika.co.id. Proses cepat, legal, dan aman untuk kebutuhan bisnis Anda.')
@section('meta_keywords', 'Beranda, Jasa Beranda, Konsultan Beranda, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


@section('content')

{{-- Breadcrumb / Header Area untuk Upcoming Event --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">
                        <i class="fas fa-calendar-alt me-1"></i> <span data-i18n="event.hero_eyebrow">Agenda Lawgika</span>
                    </span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="event.hero_title">Upcoming Event</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="event.hero_desc">
                        Ikuti berbagai event menarik seputar hukum, bisnis, dan pengembangan diri bersama para ahli di bidangnya.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}" class="text-white text-decoration-none">
                                <i class="fas fa-home me-1"></i><span data-i18n="nav.home">Beranda</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="event.hero_title">
                            Upcoming Event
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- MAIN CONTENT - DAFTAR EVENT --}}
<section class="event-list-area py-5">
    <div class="container">
        <div class="row">
            <!-- MAIN CONTENT - KIRI (2/3) -->
            <div class="col-lg-12">
                <div class="event-header mb-4 d-flex justify-content-between align-items-center flex-wrap">
                    <h2 class="fw-bold mb-2 mb-lg-0">
                        <i class="fas fa-calendar-alt text-danger me-2"></i> <span data-i18n="event.section_title">Semua Event</span>
                    </h2>
                    <span class="badge bg-danger rounded-pill px-3 py-2">
                        {{ $events->count() }} <span data-i18n="event.available">Event Tersedia</span>
                    </span>
                </div>

                <hr> <br>
                @forelse($events as $event)
                <div class="event-card card mb-4 border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="row g-0">
                        @if($event->banner)
                        <div class="col-md-4">
                            <div class="h-100 w-100 overflow-hidden" style="aspect-ratio: 1 / 1; cursor: pointer;" onclick="openEventPopup({{ $event->id }})">
                                <img loading="lazy" src="{{ asset('storage/' . $event->banner) }}"
                                    alt="{{ $event->nama_event }}"
                                    class="w-100 h-100 object-fit-cover"
                                    style="aspect-ratio: 1 / 1; object-fit: cover;">
                            </div>
                        </div>
                        @endif
                        <div class="col-md-8">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <h3 class="card-title fw-bold mb-2" role="button" onclick="openEventPopup({{ $event->id }})" style="cursor: pointer;">{{ $event->nama_event }}</h3>
                                    @if($event->status_aktif)
                                    <span class="badge bg-success rounded-pill px-3 py-1" data-i18n="event.status_active">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary rounded-pill px-3 py-1" data-i18n="event.status_completed">Selesai</span>
                                    @endif
                                </div>
                                <div class="event-meta mb-3">
                                    <span class="me-3 text-muted small">
                                        <i class="fas fa-calendar-alt text-danger me-1"></i>
                                        {{ \Carbon\Carbon::parse($event->tanggal_mulai)->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="me-3 text-muted small">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        {{ $event->lokasi ?? __('event.location_tbd') }}
                                    </span>
                                </div>
                                <p class="card-text text-muted mb-3">
                                    {{ Str::limit($event->deskripsi, 120) }}
                                </p>

                                <!-- INFORMASI TAMBAHAN DI CARD -->
                                <div class="ue-card-meta mb-3">
                                    @if($event->harga > 0)
                                    <span class="ue-price">
                                        <i class="fas fa-tag"></i>
                                        Rp {{ number_format($event->harga, 0, ',', '.') }}
                                    </span>
                                    @else
                                    <span class="ue-price-free">
                                        <i class="fas fa-gift"></i> <span data-i18n="event.free">Gratis</span>
                                    </span>
                                    @endif

                                    @if($event->kapasitas)
                                    <span class="ue-capacity">
                                        <i class="fas fa-users"></i>
                                        <span data-i18n="event.max_prefix">Max</span> {{ $event->kapasitas }} <span data-i18n="event.max_suffix">org</span>
                                    </span>
                                    @endif
                                </div>

                                <button
                                    type="button"
                                    onclick="openEventPopup({{ $event->id }})"
                                    class="btn btn-danger rounded-pill px-4"
                                    style="cursor: pointer; background: #4e0616; border-color: #4e0616;">
                                    <span data-i18n="event.view_detail">Lihat Detail</span> <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 bg-light rounded-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted" data-i18n="event.empty_title">Belum Ada Event</h4>
                    <p class="text-muted" data-i18n="event.empty_desc">Saat ini belum ada event yang tersedia. Pantau terus website kami ya!</p>
                </div>
                @endforelse
            </div>

            <!-- SIDEBAR - KANAN (1/3) -->
           
        </div>
    </div>
</section>

<!-- MODAL POPUP DETAIL EVENT -->
<div id="eventModal" class="event-modal-overlay">
    <div class="event-modal-container">
        <button class="event-modal-close" onclick="closeEventModal()">&times;</button>
        <div id="eventModalContent">
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-danger"></i>
                <p class="mt-2 text-muted" data-i18n="event.loading_detail">Memuat data event...</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== MODAL POPUP STYLE — OPTIMIZED (NO BLUR, NO HEAVY FX) ===== */
    .event-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        /* NO backdrop-filter — causes severe lag on mobile */
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .event-modal-overlay.active {
        display: flex;
    }

    .event-modal-container {
        background: #fff;
        border-radius: 24px;
        max-width: 560px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        /* Optimasi scroll — ringan */
        -webkit-overflow-scrolling: touch;
        overscroll-behavior: contain;
    }

    .event-modal-close {
        position: absolute;
        top: 16px;
        right: 20px;
        font-size: 28px;
        font-weight: 400;
        color: white;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 20;
    }

    .event-modal-close:hover {
        background: #4e0616;
        color: white;
    }

    .modal-event-img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        border-radius: 24px 24px 0 0;
    }

    .modal-event-body {
        padding: 28px 30px 32px;
    }

    .modal-event-title {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: #111827;
    }

    .modal-event-badge {
        display: inline-block;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 30px;
        margin-left: 10px;
        vertical-align: middle;
    }

    .modal-event-badge.aktif {
        background: #28a745;
        color: white;
    }

    .modal-event-badge.selesai {
        background: #6c757d;
        color: white;
    }

    .modal-event-date {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 20px;
        border-bottom: 1px solid #f0f0f0;
        padding-bottom: 12px;
    }

    .modal-event-desc {
        color: #4a5568;
        line-height: 1.7;
        margin-bottom: 24px;
        background: #f8f9fa;
        padding: 16px;
        border-radius: 16px;
    }

    /* ADDITIONAL BADGES */
    .modal-event-badge.berbayar {
        background: #fd7e14;
        color: white;
    }
    .modal-event-badge.gratis {
        background: #28a745;
        color: white;
    }
    .ue-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    .ue-price, .ue-price-free, .ue-capacity {
        font-size: 0.73rem;
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .ue-price {
        background: #fff5f5;
        color: #4e0616;
        border: 1px solid #feb2b2;
    }
    .ue-price-free {
        background: #f0fff4;
        color: #28a745;
        border: 1px solid #9ae6b4;
    }
    .ue-capacity {
        background: #f7fafc;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }

    .modal-event-info {
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .modal-event-info-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border-bottom: 1px solid #f0f2f5;
    }

    .modal-event-info-item:last-child {
        border-bottom: none;
    }

    .modal-event-info-item i {
        width: 24px;
        color: #4e0616;
    }

    .modal-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-daftar {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        text-align: center;
        padding: 14px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
    }

    .btn-daftar:hover {
        box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-lihat-semua {
        background: #4e0616;
        color: white;
        text-align: center;
        padding: 13px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 700;
    }

    .btn-lihat-semua:hover {
        background: #b91c1c;
        color: white;
    }

    @media (max-width: 768px) {
        .modal-event-body {
            padding: 20px;
        }

        .modal-event-title {
            font-size: 1.3rem;
        }

        .modal-event-img {
            height: 170px;
        }
    }
</style>

<script>
    const t = (key, fallback) => (window.LwI18n && typeof window.LwI18n.t === 'function') ? window.LwI18n.t(key) : fallback;

    function openEventPopup(eventId) {
        console.log('[EventPopup] Opening event ID:', eventId);

        const modalContent = document.getElementById('eventModalContent');
        const modal = document.getElementById('eventModal');

        if (!modalContent || !modal) return;

        modalContent.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-danger"></i>
                <p class="mt-2 text-muted">${t('event.loading_detail', 'Memuat data event...')}</p>
            </div>
        `;
        modal.classList.add('active');

        fetch(`/event-upcoming/${eventId}/detail`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Event tidak ditemukan');
                }
                return response.json();
            })
            .then(event => {
                console.log('[EventPopup] Event data:', event);

                let formattedDate = t('event.date_tbd', 'Belum ditentukan');
                if (event.tanggal_mulai) {
                    const date = new Date(event.tanggal_mulai);
                    formattedDate = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }

                let bannerHtml = '';
                if (event.banner) {
                    bannerHtml = `<img loading="lazy" src="/storage/${event.banner}" alt="${escapeHtml(event.nama_event)}" class="modal-event-img">`;
                } else {
                    bannerHtml = `<img loading="lazy" src="{{ asset('lawgika/home/event-placeholder.webp') }}" alt="${escapeHtml(event.nama_event)}" class="modal-event-img">`;
                }

                let isAktif = false;
                if (event.status_aktif === true ||
                    event.status_aktif === 1 ||
                    event.status_aktif === '1' ||
                    event.status_aktif === 'true' ||
                    Number(event.status_aktif) === 1) {
                    isAktif = true;
                }
                if (event.label_status) {
                    isAktif = (event.label_status === 'Aktif');
                }

                let statusBadge = isAktif ?
                    `<span class="modal-event-badge aktif">● ${t('event.status_active', 'Aktif')}</span>` :
                    `<span class="modal-event-badge selesai">● ${t('event.status_completed', 'Selesai')}</span>`;

                let hargaText = '';
                if (event.harga && event.harga > 0) {
                    hargaText = `Rp ${Number(event.harga).toLocaleString('id-ID')}`;
                } else {
                    hargaText = t('event.free', 'Gratis');
                }

                let narasumberText = '-';
                if (event.narasumber) {
                    if (typeof event.narasumber === 'string') {
                        narasumberText = event.narasumber;
                    } else if (Array.isArray(event.narasumber)) {
                        narasumberText = event.narasumber.join(', ');
                    }
                }

                let tipeEventBadge = (event.tipe_event === 'berbayar' || event.harga > 0) ?
                    '<span class="modal-event-badge berbayar">💰 Berbayar</span>' :
                    `<span class="modal-event-badge gratis">🎉 ${t('event.free', 'Gratis')}</span>`;

                const noDesc = t('event.no_description', 'Tidak ada deskripsi');
                const locLabel = t('event.location', 'Lokasi');
                const timeLabel = t('event.time', 'Waktu');
                const timeValue = escapeHtml(event.waktu_event) || escapeHtml(event.waktu) || t('event.tbd', 'Belum ditentukan');
                const capLabel = t('event.capacity', 'Kapasitas');
                const capValue = event.kapasitas ? `${Number(event.kapasitas).toLocaleString()} ${t('event.participants', 'peserta')}` : t('event.unlimited', 'Tidak terbatas');
                const speakerLabel = t('event.speaker', 'Narasumber');
                const priceLabel = t('event.price', 'Harga');
                const regNow = t('event.register_now', 'Daftar Sekarang');
                const viewAll = t('event.view_all', 'Lihat Semua Event');

                modalContent.innerHTML = `
                    ${bannerHtml}
                    <div class="modal-event-body">
                        <h3 class="modal-event-title">
                            ${escapeHtml(event.nama_event)} ${statusBadge} ${tipeEventBadge}
                        </h3>
                        <div class="modal-event-date">
                            <i class="fas fa-calendar-alt me-2 text-danger"></i> ${formattedDate}
                        </div>
                        <div class="modal-event-desc">
                            ${escapeHtml(event.deskripsi) || `<em class="text-muted">${noDesc}</em>`}
                        </div>
                        <div class="modal-event-info">
                            <div class="modal-event-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span class="info-label">${locLabel}</span>
                                <span class="info-value">${escapeHtml(event.lokasi) || '-'}</span>
                            </div>
                            <div class="modal-event-info-item">
                                <i class="fas fa-clock"></i>
                                <span class="info-label">${timeLabel}</span>
                                <span class="info-value">${timeValue}</span>
                            </div>
                            <div class="modal-event-info-item">
                                <i class="fas fa-users"></i>
                                <span class="info-label">${capLabel}</span>
                                <span class="info-value">${capValue}</span>
                            </div>
                            <div class="modal-event-info-item">
                                <i class="fas fa-chalkboard-user"></i>
                                <span class="info-label">${speakerLabel}</span>
                                <span class="info-value">${escapeHtml(narasumberText)}</span>
                            </div>
                            <div class="modal-event-info-item">
                                <i class="fas fa-ticket"></i>
                                <span class="info-label">${priceLabel}</span>
                                <span class="info-value ${event.harga > 0 ? 'text-danger fw-bold' : 'text-success fw-bold'}">${hargaText}</span>
                            </div>
                        </div>
                        <div class="modal-buttons">
                            <a href="https://wa.me/6281111111?text=Halo%20Lawgika,%20saya%20ingin%20mendaftar%20event%20${encodeURIComponent(event.nama_event)}" target="_blank" class="btn-daftar"><i class="fas fa-ticket-alt me-2"></i> ${regNow}</a>
                            <a href="{{ route('upcoming.event') }}" class="btn-lihat-semua"><i class="fas fa-calendar-week me-2"></i> ${viewAll}</a>
                        </div>
                    </div>
                `;
            })
            .catch(error => {
                console.error('[EventPopup] Error:', error);
                const failedText = t('event.failed_load', 'Gagal memuat detail event');
                const closeText = t('event.close', 'Tutup');
                modalContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        <p class="mt-2 text-danger">${failedText}</p>
                        <button onclick="closeEventModal()" class="btn btn-secondary mt-3 rounded-pill px-4">${closeText}</button>
                    </div>
                `;
            });
    }

    function closeEventModal() {
        const modal = document.getElementById('eventModal');
        if (modal) {
            modal.classList.remove('active');
        }
    }

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('eventModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) closeEventModal();
            });
        }
    });
</script>

@endsection