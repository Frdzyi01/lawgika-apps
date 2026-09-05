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
use App\Http\Controllers\Admin\CorrespondenceController as AdminCorrespondenceController;
use App\Http\Controllers\Customer\CorrespondenceController as CustomerCorrespondenceController;

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
// Jasa Pembukuan – Multi package order flow
Route::get('/order/jasa-pembukuan-perpajakan/{kategori}/{paket}', [\App\Http\Controllers\JasaPembukuanController::class, 'create'])->name('jpp.create')->middleware('auth');
Route::post('/order/jasa-pembukuan-perpajakan', [\App\Http\Controllers\JasaPembukuanController::class, 'store'])->name('jpp.store')->middleware('auth');
Route::get('/order/jasa-pembukuan-perpajakan/success', [\App\Http\Controllers\JasaPembukuanController::class, 'success'])->name('jpp.success')->middleware('auth');
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
Route::get('/virtual-office/panduan-pengambilan-dokumen', [ServicesController::class, 'panduanPengambilanDokumen'])->name('virtual-office.panduan-pengambilan-dokumen');
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

Auth::routes(['register' => false]);

Route::match(['get', 'post'], '/logout', function (\Illuminate\Http\Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/?login=1');
})->name('logout');

// Prevent direct access to /login and /register pages, redirecting to home with a trigger parameter
Route::get('/login', function() { return redirect('/?login=1'); })->name('login');
// Route::get('/register', function() { return redirect('/?register=1'); })->name('register');

// Public Order Route (guest + logged in) — modal quick form
Route::post('/order-quick', [\App\Http\Controllers\PublicOrderController::class, 'store'])->name('public.order.store');

// Public QR Code Portal & Service Verification Pages
Route::get('/qr', [\App\Http\Controllers\QrOrderController::class, 'index'])->name('qr.index');
Route::post('/qr/verify', [\App\Http\Controllers\QrOrderController::class, 'verify'])->name('qr.verify');
Route::get('/qr/{token}', [\App\Http\Controllers\QrOrderController::class, 'show'])->name('qr.show');
Route::get('/qr/{token}/image', [\App\Http\Controllers\QrOrderController::class, 'image'])->name('qr.image');

// Redirect /home
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->hasAdminAccess()) return redirect()->route('admin.dashboard');
    return redirect()->route('customer.dashboard');
})->middleware('auth')->name('home');

Route::middleware(['auth', 'role:admin,admin1,admin2'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/home', function() { return redirect()->route('admin.dashboard'); })->name('home'); // backward compatibility
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)->except(['destroy']);
    Route::get('orders/document-requirements/{serviceKey}', [\App\Http\Controllers\Admin\OrderController::class, 'getDocumentRequirements'])->name('orders.document-requirements');
    Route::post('documents/{document}/status',  [\App\Http\Controllers\Admin\DocumentController::class, 'updateStatus'])->name('documents.status');
    Route::post('documents/{document}/approve', [\App\Http\Controllers\Admin\DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('documents/{document}/reject',  [\App\Http\Controllers\Admin\DocumentController::class, 'reject'])->name('documents.reject');
    Route::post('documents/{document}/reset',   [\App\Http\Controllers\Admin\DocumentController::class, 'reset'])->name('documents.reset');
    Route::post('orders/{order}/payment-status', [\App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.payment-status');
    Route::post('orders/{order}/send-payment-reminder', [\App\Http\Controllers\Admin\OrderController::class, 'sendPaymentReminder'])->name('orders.send-payment-reminder');
    Route::post('orders/{order}/generate-qr', [\App\Http\Controllers\Admin\OrderController::class, 'generateQr'])->name('orders.generate-qr');
    Route::get('orders/{order}/download-qr', [\App\Http\Controllers\Admin\OrderController::class, 'downloadQr'])->name('orders.download-qr');
    
    // Virtual Office Module
    Route::get('virtual-office', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'index'])->name('virtual-office.index');
    Route::get('virtual-office/{id}', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'show'])->name('virtual-office.show');
    Route::post('virtual-office/{id}/mail-notification', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'sendMailNotification'])->name('virtual-office.mail-notification.store');
    Route::post('virtual-office/{id}/guest-notification', [\App\Http\Controllers\Admin\VirtualOfficeController::class, 'sendGuestNotification'])->name('virtual-office.guest-notification.store');

    // ── Room Benefit System (NEW) ─────────────────────────────────────────────
    Route::post('orders/{order}/approve-benefit', [\App\Http\Controllers\Admin\RoomBenefitController::class, 'approve'])->name('orders.approve-benefit');
    Route::post('benefits/{benefit}/checkin/{roomType}', [\App\Http\Controllers\Admin\RoomBenefitController::class, 'checkin'])->name('benefits.checkin');
    Route::post('benefits/{benefit}/checkout/{roomType}', [\App\Http\Controllers\Admin\RoomBenefitController::class, 'checkout'])->name('benefits.checkout');
    Route::get('benefits/{benefit}/detail', [\App\Http\Controllers\Admin\RoomBenefitController::class, 'showDetail'])->name('benefits.detail');

    Route::resource('promo', PromoController::class);
    Route::resource('event-upcoming', EventUpComingController::class);
    Route::resource('peraturan-kbli', PeraturanKBLIController::class);
    Route::resource('berita', BeritaController::class)->parameters([
        'berita' => 'berita'
    ]);

    // ── Meeting Room ──────────────────────────────────────────────────────────
    Route::get('/meeting-room', [MeetingRoomController::class, 'adminIndex']);
    Route::get('/meeting-room/calendar', [MeetingRoomController::class, 'calendar'])->name('meeting-room.calendar');
    Route::get('/meeting-room/calendar-events', [MeetingRoomController::class, 'calendarEvents'])->name('meeting-room.calendar-events');
    Route::get('/meeting-room/booked-slots', [MeetingRoomController::class, 'getBookedSlots'])->name('meeting-room.booked-slots');
    Route::get('/meeting-room/create', [MeetingRoomController::class, 'adminCreate'])->name('meeting-room.create');
    Route::post('/meeting-room/store', [MeetingRoomController::class, 'adminStore'])->name('meeting-room.store');
    Route::post('/meeting-room/create-session', [MeetingRoomController::class, 'createSession'])->name('meeting-room.create-session');
    Route::get('/meeting-room/{id}/detail', [MeetingRoomController::class, 'adminDetail']);
    Route::match(['get', 'post'], '/meeting-room/{id}/checkin', [MeetingRoomController::class, 'checkin']);
    Route::match(['get', 'post'], '/meeting-room/{id}/checkout', [MeetingRoomController::class, 'checkout']);
    Route::match(['get', 'post'], '/meeting-room/{id}/approve-payment', [MeetingRoomController::class, 'approvePayment'])->name('admin.meeting-room.approve');
    Route::match(['get', 'post'], '/meeting-room/{id}/reject-payment', [MeetingRoomController::class, 'rejectPayment'])->name('admin.meeting-room.reject');
    Route::match(['get', 'post'], '/meeting-room/{id}/benefit-approve', [MeetingRoomController::class, 'approveBenefitReservation'])->name('admin.meeting-room.benefit-approve');
    Route::match(['get', 'post'], '/meeting-room/{id}/benefit-reject', [MeetingRoomController::class, 'rejectBenefitReservation'])->name('admin.meeting-room.benefit-reject');

    // ── Podcast Room ──────────────────────────────────────────────────────────
    Route::get('/podcast-room', [\App\Http\Controllers\PodcastRoomController::class, 'adminIndex'])->name('podcast-room.index');
    Route::get('/podcast-room/calendar', [\App\Http\Controllers\PodcastRoomController::class, 'calendar'])->name('podcast-room.calendar');
    Route::get('/podcast-room/calendar-events', [\App\Http\Controllers\PodcastRoomController::class, 'calendarEvents'])->name('podcast-room.calendar-events');
    Route::get('/podcast-room/booked-slots', [\App\Http\Controllers\PodcastRoomController::class, 'getBookedSlots'])->name('podcast-room.booked-slots');
    Route::get('/podcast-room/create', [\App\Http\Controllers\PodcastRoomController::class, 'adminCreate'])->name('podcast-room.create');
    Route::post('/podcast-room/store', [\App\Http\Controllers\PodcastRoomController::class, 'adminStore'])->name('podcast-room.store');
    Route::post('/podcast-room/create-session', [\App\Http\Controllers\PodcastRoomController::class, 'createSession'])->name('podcast-room.create-session');
    Route::get('/podcast-room/{id}/detail', [\App\Http\Controllers\PodcastRoomController::class, 'adminDetail']);
    Route::match(['get', 'post'], '/podcast-room/{id}/checkin', [\App\Http\Controllers\PodcastRoomController::class, 'checkin']);
    Route::match(['get', 'post'], '/podcast-room/{id}/checkout', [\App\Http\Controllers\PodcastRoomController::class, 'checkout']);
    Route::match(['get', 'post'], '/podcast-room/{id}/approve-payment', [\App\Http\Controllers\PodcastRoomController::class, 'approvePayment'])->name('admin.podcast-room.approve');
    Route::match(['get', 'post'], '/podcast-room/{id}/reject-payment', [\App\Http\Controllers\PodcastRoomController::class, 'rejectPayment'])->name('admin.podcast-room.reject');
    Route::match(['get', 'post'], '/podcast-room/{id}/benefit-approve', [\App\Http\Controllers\PodcastRoomController::class, 'approveBenefitReservation'])->name('admin.podcast-room.benefit-approve');
    Route::match(['get', 'post'], '/podcast-room/{id}/benefit-reject', [\App\Http\Controllers\PodcastRoomController::class, 'rejectBenefitReservation'])->name('admin.podcast-room.benefit-reject');

    Route::get('/spt-badan', [\App\Http\Controllers\SptTahunanController::class, 'adminDashboard'])->name('spt-badan.index');
    Route::post('/spt-badan/{id}/status', [\App\Http\Controllers\SptTahunanController::class, 'updateStatus'])->name('spt-badan.status');

    // ── Surat Menyurat Dokumen Legal ──────────────────────────────────────────
    Route::get('/surat-menyurat', [AdminCorrespondenceController::class, 'index'])->name('surat-menyurat.index');
    Route::get('/surat-menyurat/create', [AdminCorrespondenceController::class, 'create'])->name('surat-menyurat.create');
    Route::post('/surat-menyurat/store', [AdminCorrespondenceController::class, 'store'])->name('surat-menyurat.store');
    Route::get('/surat-menyurat/{id}', [AdminCorrespondenceController::class, 'show'])->name('surat-menyurat.show');
    Route::post('/surat-menyurat/reply/{id}', [AdminCorrespondenceController::class, 'reply'])->name('surat-menyurat.reply');
    Route::post('/surat-menyurat/status/{id}', [AdminCorrespondenceController::class, 'updateStatus'])->name('surat-menyurat.status');

    // ── Master Client: Search API untuk semua Admin ───────────────────────────
    Route::get('/users/search', [\App\Http\Controllers\Admin\UserController::class, 'search'])->name('users.search');

    // ── Master Client: CRUD hanya SPV dan Admin 1 ────────────────────────────
    Route::middleware('role:admin,spv,admin1')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    });
});

Route::middleware(['auth', 'role:customer'])->prefix('dashboard')->name('customer.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifications', [\App\Http\Controllers\Customer\NotificationController::class, 'index'])->name('notifications.index');
    Route::resource('orders', \App\Http\Controllers\Customer\OrderController::class)->only(['index', 'show']);
    Route::post('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'store'])->name('documents.store');
    Route::get('documents', [\App\Http\Controllers\Customer\DocumentController::class, 'index'])->name('documents.index');
    Route::post('orders/{order}/payment-proof', [\App\Http\Controllers\Customer\OrderController::class, 'uploadPaymentProof'])->name('orders.payment-proof');

    Route::get('/meeting-room', [MeetingRoomController::class, 'customerIndex'])->name('meeting-room.index');
    Route::get('/meeting-room/{id}/detail', [MeetingRoomController::class, 'customerDetail'])->name('meeting-room.detail');
    Route::post('/meeting-room/{id}/checkin', [MeetingRoomController::class, 'checkin']);
    Route::post('/meeting-room/{id}/checkout', [MeetingRoomController::class, 'checkout']);

    Route::get('/podcast-room', [\App\Http\Controllers\PodcastRoomController::class, 'customerIndex'])->name('podcast-room.index');
    Route::get('/podcast-room/{id}/detail', [\App\Http\Controllers\PodcastRoomController::class, 'customerDetail'])->name('podcast-room.detail');

    // ── Room Benefit Detail (NEW — read-only for customer) ────────────────────
    Route::get('benefits/{benefit}/detail', [\App\Http\Controllers\Customer\RoomBenefitController::class, 'showDetail'])->name('benefits.detail');

    Route::get('/spt-badan', [\App\Http\Controllers\SptTahunanController::class, 'customerDashboard'])->name('spt-badan.index');

    // ── Surat Menyurat Dokumen Legal (NEW) ────────────────────────────────────
    Route::get('/surat-menyurat', [CustomerCorrespondenceController::class, 'index'])->name('surat-menyurat.index');
    Route::get('/surat-menyurat/create', [CustomerCorrespondenceController::class, 'create'])->name('surat-menyurat.create');
    Route::post('/surat-menyurat', [CustomerCorrespondenceController::class, 'store'])->name('surat-menyurat.store');
    Route::get('/surat-menyurat/{id}', [CustomerCorrespondenceController::class, 'show'])->name('surat-menyurat.show');
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

Route::get('/buat-jembatan', function () {
    // 1. Folder sumber (tempat gambar asli disimpan di lawgika-apps)
    $targetFolder = storage_path('app/public');
    
    // Jika foldernya belum ada, sistem akan otomatis membuatkannya
    if (!file_exists($targetFolder)) {
        mkdir($targetFolder, 0755, true);
    }

    // 2. Folder tujuan (menggunakan pendeteksi otomatis document root server)
    $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';

    // Bersihkan dulu jika ada jembatan lama yang rusak atau nyangkut
    if (file_exists($linkFolder) || is_link($linkFolder)) {
        @unlink($linkFolder);
    }

    try {
        symlink($targetFolder, $linkFolder);
        return 'Sukses! Jembatan gambar berhasil dibuat ke: ' . $linkFolder;
    } catch (\Exception $e) {
        return 'Gagal lagi: ' . $e->getMessage();
    }
});
