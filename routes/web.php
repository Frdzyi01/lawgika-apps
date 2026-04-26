<?php

use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PromoControllerFrontend;
use App\Http\Controllers\PeraturanKBLIController;
use App\Http\Controllers\PeraturanFrontendController;
use App\Http\Controllers\EventUpComingController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KarirController;
use App\Http\Controllers\EventUpComingFrontendController;
use App\Http\Controllers\UniversalOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeetingRoomController;

Route::get('/', function () {
    $promos = \App\Models\Promo::where('status', true)
        ->where('tanggal_berakhir', '>=', now())
        ->latest()
        ->limit(4)
        ->get();

    $events = \App\Models\EventUpComing::where(function ($q) {
            $q->where('status', true)
              ->orWhere('tanggal_selesai', '>=', now());
        })
        ->latest()
        ->limit(4)
        ->get();

    $peraturan = \App\Models\peraturanKBLI::where('status', 'aktif')
        ->latest()
        ->limit(18)
        ->get();

    $beritas = \App\Models\Berita::whereNotNull('published_at')
        ->where('published_at', '<=', now())
        ->latest('published_at')
        ->limit(3)
        ->get();

    return view('frontend.dashboard', compact('promos', 'events', 'peraturan', 'beritas'));
});


// tentang kami
Route::get('/tentang-kami', [ServicesController::class, 'tentangKami']);

// pendirian badan usaha
Route::get('/pendirian-pt-perorangan', [ServicesController::class, 'pendirianPtPerorangan']);
Route::get('/pendirian-pt', [ServicesController::class, 'pendirianPt']);
Route::get('/pendirian-pt-pma', [ServicesController::class, 'pendirianPtPma']);
Route::get('/pendirian-cv', [ServicesController::class, 'pendirianCv']);
Route::get('/pendirian-yayasan', [ServicesController::class, 'pendirianYayasan']);
Route::get('/pendirian-firma', [ServicesController::class, 'pendirianFirma']);

// perizinan dokumen & hukum
Route::get('/perubahan-anggaran-dasar', [ServicesController::class, 'perubahanAnggaranDasar']);
Route::get('/haki', [ServicesController::class, 'haki']);
Route::get('/penutupan-perusahaan', [ServicesController::class, 'penutupanPerusahaan']);
ROute::get('/nib-dan-oss', [ServicesController::class, 'nibdanoss']);
Route::get('/pendaftaran-tdp', [ServicesController::class, 'pendaftaranTdp']);
Route::get('/sbu-sijuk', [ServicesController::class, 'sbuSijuk']);
Route::get('/laporan-lkpm', [ServicesController::class, 'laporanLkpm']);
Route::get('/sertifikat-iso', [ServicesController::class, 'sertifikatIso']);
Route::get('/surat-keterangan-tidak-pailit', [ServicesController::class, 'suratKeteranganTidakPailit']);
Route::get('/drafting-review-perjanjian-bisnis', [ServicesController::class, 'draftingReviewPerjanjianBisnis']);

// pembukuan dan perpajakan
Route::get('/jasa-pembukuan-perpajakan', [ServicesController::class, 'jasaPembukuanPerpajakan'])->name('jasa-pembukuan-perpajakan');
Route::get('/langganan-jasa-pembukuan', [ServicesController::class, 'langgananJasaPembukuan']);
Route::get('/langganan-jasa-perpajakan', [ServicesController::class, 'langgananJasaPerpajakan']);
Route::get('/layanan-payroll', [ServicesController::class, 'layananPayroll']);
Route::get('/point-of-sales-fnb', [ServicesController::class, 'pointOfSalesFnb']);
Route::get('/audit-laporan-keuangan', [ServicesController::class, 'auditLaporanKeuangan']);
Route::get('/pengurusan-pkp', [ServicesController::class, 'pengurusanPkp']);
// Unified SPT Tahunan (Pribadi + Badan)
Route::get('/pelaporan-spt-tahunan', [ServicesController::class, 'pelaporanSptTahunan'])->name('spt-tahunan.index');
Route::post('/spt-tahunan/store', [\App\Http\Controllers\SptTahunanController::class, 'store'])->name('spt-tahunan.store')->middleware('auth');
Route::get('/spt-tahunan/success', [\App\Http\Controllers\SptTahunanController::class, 'success'])->name('spt-tahunan.success')->middleware('auth');
// Legacy redirects
Route::get('/pelaporan-spt-badan', fn() => redirect('/pelaporan-spt-tahunan', 301));
Route::get('/pelaporan-spt-pribadi', fn() => redirect('/pelaporan-spt-tahunan', 301));
Route::post('/spt-badan/store', [\App\Http\Controllers\SptBadanController::class, 'store'])->name('spt-badan.store')->middleware('auth');
Route::get('/pendaftaran-npwp', [ServicesController::class, 'pendaftaranNpwp']);
Route::get('/audit-pajak', [ServicesController::class, 'auditPajak']);


// layanan pendukung bisnis
Route::get('/sewa-meeting-room', [MeetingRoomController::class, 'index']);
Route::get('/meeting-room/booked-slots', [MeetingRoomController::class, 'getBookedSlots'])->name('meeting-room.booked-slots');
Route::get('/meeting-room/order', [MeetingRoomController::class, 'order'])->name('meeting-room.order')->middleware('auth');
Route::post('/meeting-room/store', [MeetingRoomController::class, 'store'])->name('meeting-room.store')->middleware('auth');
Route::get('/sewa-ruang-podcast', [\App\Http\Controllers\PodcastRoomController::class, 'index'])->name('podcast-room.index');
Route::get('/podcast-room/booked-slots', [\App\Http\Controllers\PodcastRoomController::class, 'getBookedSlots'])->name('podcast-room.booked-slots');
Route::get('/podcast-room/order', [\App\Http\Controllers\PodcastRoomController::class, 'order'])->name('podcast-room.order')->middleware('auth');
Route::post('/podcast-room/store', [\App\Http\Controllers\PodcastRoomController::class, 'store'])->name('podcast-room.store')->middleware('auth');
Route::get('/layanan-konsultasi-bisnis', function() { return redirect('/sewa-ruang-podcast', 301); });
Route::get('/layanan-visa-kitas', [ServicesController::class, 'layananVisaKitas']);
Route::get('/layanan-call-answering', [ServicesController::class, 'layananCallAnswering']);
Route::get('/virtual-office', [ServicesController::class, 'virtualOffice']);
Route::get('/kerjasama-bisnis', [ServicesController::class, 'kerjasamaBisnis']);
Route::get('/perizinan-dan-hukum', [ServicesController::class, 'perizinanDanHukum']);

Route::get('/karir', [KarirController::class, 'Karir']);

// Frontend promo routes
Route::get('/promo', [PromoControllerFrontend::class, 'index'])->name('promo.index');
Route::get('/promo/{id}', [PromoControllerFrontend::class, 'show'])
    ->name('promo.show')
    ->middleware(\App\Http\Middleware\FixAssetPath::class);

// Frontend Database Peraturan
Route::get('/database-peraturan', [PeraturanFrontendController::class, 'index'])->name('peraturan.index');

Auth::routes();

// Public Order Route (guest + logged in) — modal quick form
Route::post('/order-quick', [\App\Http\Controllers\PublicOrderController::class, 'store'])->name('public.order.store');

// Redirect /home
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->role === 'admin') return redirect()->route('admin.dashboard');
    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function() { return redirect()->route('admin.dashboard'); })->name('home'); // backward compatibility
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store', 'destroy']);
    Route::post('documents/{document}/status', [\App\Http\Controllers\Admin\DocumentController::class, 'updateStatus'])->name('documents.status');
    Route::post('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');

    Route::resource('promo', PromoController::class);
    Route::resource('event-upcoming', EventUpComingController::class);
    Route::resource('peraturan-kbli', PeraturanKBLIController::class);
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita'
    ]);

    Route::get('/meeting-room', [MeetingRoomController::class, 'adminIndex']);
    Route::post('/meeting-room/{id}/checkin', [MeetingRoomController::class, 'checkin']);
    Route::post('/meeting-room/{id}/checkout', [MeetingRoomController::class, 'checkout']);
    Route::post('/meeting-room/{id}/approve-payment', [MeetingRoomController::class, 'approvePayment'])->name('admin.meeting-room.approve');
    Route::post('/meeting-room/{id}/reject-payment', [MeetingRoomController::class, 'rejectPayment'])->name('admin.meeting-room.reject');

    Route::get('/podcast-room', [\App\Http\Controllers\PodcastRoomController::class, 'adminIndex'])->name('podcast-room.index');
    Route::post('/podcast-room/{id}/checkin', [\App\Http\Controllers\PodcastRoomController::class, 'checkin']);
    Route::post('/podcast-room/{id}/checkout', [\App\Http\Controllers\PodcastRoomController::class, 'checkout']);
    Route::post('/podcast-room/{id}/approve-payment', [\App\Http\Controllers\PodcastRoomController::class, 'approvePayment'])->name('admin.podcast-room.approve');
    Route::post('/podcast-room/{id}/reject-payment', [\App\Http\Controllers\PodcastRoomController::class, 'rejectPayment'])->name('admin.podcast-room.reject');

    Route::get('/spt-badan', [\App\Http\Controllers\SptTahunanController::class, 'adminDashboard'])->name('spt-badan.index');
    Route::post('/spt-badan/{id}/status', [\App\Http\Controllers\SptTahunanController::class, 'updateStatus'])->name('spt-badan.status');
});

Route::middleware(['auth', 'role:customer'])->prefix('dashboard')->name('customer.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', \App\Http\Controllers\Customer\OrderController::class)->only(['index', 'show']);
    Route::post('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'index'])->name('documents.index');
    Route::post('orders/{order}/payment-proof', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('orders.payment-proof');

    Route::get('/meeting-room', [MeetingRoomController::class, 'customerIndex'])->name('meeting-room.index');
    Route::post('/meeting-room/{id}/checkin', [MeetingRoomController::class, 'checkin']);
    Route::post('/meeting-room/{id}/checkout', [MeetingRoomController::class, 'checkout']);

    Route::get('/podcast-room', [\App\Http\Controllers\PodcastRoomController::class, 'customerIndex'])->name('podcast-room.index');

    Route::get('/spt-badan', [\App\Http\Controllers\SptTahunanController::class, 'customerDashboard'])->name('spt-badan.index');
});

Route::get('/layanan/{slug}', [\App\Http\Controllers\PublicServiceController::class, 'show'])->name('services.show');
// Frontend Berita
Route::get('/berita', [\App\Http\Controllers\BeritaController::class, 'frontendIndex'])->name('berita.index');
Route::get('/berita/{slug}', [\App\Http\Controllers\BeritaController::class, 'frontendShow'])->name('berita.show');

// ROUTE FRONTEND (TAMBAHKAN INI)
Route::get('/upcoming-event', [EventUpComingController::class, 'frontendIndex'])->name('upcoming.event');
Route::get('/event-upcoming/{id}/detail', [EventUpComingController::class, 'detail'])->name('event.detail');

// ── Universal Order Flow (auth required) ─────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/order/{service}/{package}', [UniversalOrderController::class, 'create'])
        ->name('order.create');
    Route::post('/order', [UniversalOrderController::class, 'store'])
        ->name('order.store');
    Route::get('/order/success', [UniversalOrderController::class, 'success'])
        ->name('order.success');

    // ── PT Perorangan dedicated order flow ────────────────────────────────────
    Route::get('/order/pt-perorangan/{package}', [\App\Http\Controllers\PtPeroranganOrderController::class, 'create'])
        ->name('order.pt-perorangan.create');
    Route::post('/order/pt-perorangan', [\App\Http\Controllers\PtPeroranganOrderController::class, 'store'])
        ->name('order.pt-perorangan.store');
    Route::get('/order/pt-perorangan/success', [\App\Http\Controllers\PtPeroranganOrderController::class, 'success'])
        ->name('order.pt-perorangan.success');
});