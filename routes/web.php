<?php

use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\PromoControllerFrontend;
use App\Http\Controllers\PeraturanKBLIController;
use App\Http\Controllers\EventUpComingController;
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

    return view('dashboard', compact('promos', 'events', 'peraturan'));
});

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
Route::get('/promo/{id}', [PromoControllerFrontend::class, 'show'])->name('promo.show');

Auth::routes();

// Route admin - hanya bisa diakses setelah login
Route::get('/admin', function () {
    return view('admin.home');
})->middleware('auth')->name('admin.home');

// Redirect /home ke /admin supaya tidak ada konflik
Route::get('/home', function () {
    return redirect('/admin');
})->middleware('auth')->name('home');


Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('promo', PromoController::class);
    Route::resource('event-upcoming', EventUpComingController::class);
    Route::resource('peraturan-kbli', PeraturanKBLIController::class);
});

