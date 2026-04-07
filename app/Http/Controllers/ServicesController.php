<?php

namespace App\Http\Controllers;

use App\Models\services;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function pendirianPtPerorangan()
    {
        return view('services.pendirian-badan-usaha.pendirian-pt-perorangan');
    }

    public function pendirianPt()
    {
        return view('services.pendirian-badan-usaha.pendirian-pt');
    }

    public function pendirianPtPma()
    {
        return view('services.pendirian-badan-usaha.pendirian-pt-pma');
    }

    public function pendirianCv()
    {
        return view('services.pendirian-badan-usaha.pendirian-cv');
    }

    public function pendirianYayasan()
    {
        return view('services.pendirian-badan-usaha.pendirian-yayasan');
    }

    public function pendirianFirma()
    {
        return view('services.pendirian-badan-usaha.pendirian-firma');
    }

    // perizinan dokumen & hukum
    public function perubahanAnggaranDasar()
    {
        return view('services.perizinan-dokumen-hukum.perubahan-anggaran-dasar');
    }

    public function haki()
    {
        return view('services.perizinan-dokumen-hukum.haki');
    }

    public function penutupanPerusahaan()
    {
        return view('services.perizinan-dokumen-hukum.penutupan-perusahaan');
    }

    public function nibdanoss()
    {
        return view('services.perizinan-dokumen-hukum.nib&oss');
    }

    public function pendaftaranTdp()
    {
        return view('services.perizinan-dokumen-hukum.pendaftaran-TDPSE');
    }

    public function sbuSijuk()
    {
        return view('services.perizinan-dokumen-hukum.SBU&SIJUK');
    }

    public function laporanLkpm()
    {
        return view('services.perizinan-dokumen-hukum.laporan-lkpm');
    }

    public function sertifikatIso()
    {
        return view('services.perizinan-dokumen-hukum.sertifikat-iso');
    }

    public function suratKeteranganTidakPailit()
    {
        return view('services.perizinan-dokumen-hukum.surat-keterangan-tidak-pailit');
    }

    public function draftingReviewPerjanjianBisnis()
    {
        return view('services.perizinan-dokumen-hukum.drafting&review-perjanjian-bisnis');
    }

    // pembukuan dan perpajakan
    public function langgananJasaPembukuan()
    {
        return view('services.pembukuan-pajak.langganan-jasa-pembukuan');
    }

    public function langgananJasaPerpajakan()
    {
        return view('services.pembukuan-pajak.langganan-jasa-perpajakan');
    }

    public function layananPayroll()
    {
        return view('services.pembukuan-pajak.layanan-payroll');
    }

    public function pointOfSalesFnb()
    {
        return view('services.pembukuan-pajak.point-of-sales-fnb');
    }

    public function auditLaporanKeuangan()
    {
        return view('services.pembukuan-pajak.audit-laporan-keuangan');
    }

    public function pengurusanPkp()
    {
        return view('services.pembukuan-pajak.pengurusan-pkp');
    }

    public function pelaporanSptBadan()
    {
        return view('services.pembukuan-pajak.pelaporan-spt-badan');
    }

    public function pelaporanSptPribadi()
    {
        return view('services.pembukuan-pajak.pelaporan-spt-pribadi');
    }

    public function pendaftaranNpwp()
    {
        return view('services.pembukuan-pajak.pendaftaran-npwp');
    }

    public function auditPajak()
    {
        return view('services.pembukuan-pajak.audit-pajak');
    }

    // layanan pendukung bisnis
    public function sewaMeetingRoom()
    {
        return view('services.layanan-pendukung-bisnis.sewa-meeting-room');
    }
    public function sewaRuangPodcast()
    {
        return view('services.layanan-pendukung-bisnis.sewa-ruang-podcast');
    }
    public function layananVisaKitas()
    {
        return view('services.layanan-pendukung-bisnis.layanan-visa-kitas');
    }
    public function layananCallAnswering()
    {
        return view('services.layanan-pendukung-bisnis.layanan-call-answering');
    }
    public function layananKonsultasiBisnis()
    {
        return view('services.layanan-pendukung-bisnis.layanan-konsultasi-bisnis');
    }
    public function virtualOffice()
    {
        return view('services.layanan-pendukung-bisnis.virtual-office');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(services $services)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(services $services)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, services $services)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(services $services)
    {
        //
    }
}
