<?php

use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PromoControllerFrontend;
use App\Http\Controllers\PeraturanKBLIController;
use App\Http\Controllers\PeraturanFrontendController;
use App\Http\Controllers\EventUpComingController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\EventUpComingFrontendController;
use App\Http\Controllers\UniversalOrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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
Route::get('/pendirian-pt->-1m', [ServicesController::class, 'pendirianPtdibawah1M']);
Route::get('/pendirian-pt-<-1m', [ServicesController::class, 'pendirianPtdiatas1M']);
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
Route::get('/pelaporan-spt-badan', [ServicesController::class, 'pelaporanSptBadan']);
Route::get('/pelaporan-spt-pribadi', [ServicesController::class, 'pelaporanSptPribadi']);
Route::get('/pendaftaran-npwp', [ServicesController::class, 'pendaftaranNpwp']);
Route::get('/audit-pajak', [ServicesController::class, 'auditPajak']);


// layanan pendukung bisnis
Route::get('/sewa-meeting-room', [ServicesController::class, 'sewaMeetingRoom']);
Route::get('/sewa-ruang-podcast', [ServicesController::class, 'sewaRuangPodcast']);
Route::get('/layanan-visa-kitas', [ServicesController::class, 'layananVisaKitas']);
Route::get('/layanan-call-answering', [ServicesController::class, 'layananCallAnswering']);
Route::get('/layanan-konsultasi-bisnis', [ServicesController::class, 'layananKonsultasiBisnis']);
Route::get('/virtual-office', [ServicesController::class, 'virtualOffice']);

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
});

Route::middleware(['auth', 'role:customer'])->prefix('dashboard')->name('customer.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('orders', \App\Http\Controllers\Customer\OrderController::class)->only(['index', 'show']);
    Route::post('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'index'])->name('documents.index');
    Route::post('orders/{order}/payment-proof', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('orders.payment-proof');
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
});