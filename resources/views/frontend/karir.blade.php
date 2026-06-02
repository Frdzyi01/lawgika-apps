@extends('layout.app')
@section('title', 'Karir & Lowongan Kerja | Lawgika')
@section('meta_description', 'Bergabunglah dengan Lawgika. Kami mencari talenta terbaik untuk berkembang bersama di ekosistem hukum dan bisnis digital.')
@section('meta_keywords', 'Karir, Jasa Karir, Konsultan Karir, Lawgika, Legalitas Usaha, Jasa Hukum Bisnis')


@section('content')

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem" data-i18n="karir.hero.badge">Karir</span>
                    <h1 class="text-white fw-bold mb-3 display-4" data-i18n="karir.hero.badge">Karir</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem" data-i18n="karir.hero.desc">Bergabunglah bersama tim Lawgika dan berkontribusi dalam mewujudkan ekosistem hukum digital di Indonesia.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none" data-i18n="nav.home">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page" data-i18n="karir.hero.badge">Karir</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== LOWONGAN KERJA ===== --}}
<section style="padding:80px 0; background:#fafbfc;">
    <div class="container">

        <div class="text-center mb-5">
            <span style="color:#800000; font-weight:700; letter-spacing:2px; font-size:0.85rem;" data-i18n="karir.hiring.eyebrow">WE ARE HIRING</span>
            <h2 style="font-weight:800; font-size:2.3rem; margin-top:10px;" data-i18n="karir.hiring.title">Bergabung Bersama Lawgika</h2>
            <p style="color:#64748b; max-width:600px; margin:0 auto;" data-i18n="karir.hiring.desc">
                Kami membuka kesempatan bagi talenta terbaik untuk berkembang bersama dalam ekosistem hukum digital.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            {{-- CARD 1 --}}
            <div class="col-lg-5">
                <div style="background:#fff; border-radius:20px; padding:30px; border:1px solid #f0e4e8; box-shadow:0 10px 30px rgba(0,0,0,0.05); height:100%;">

                    <span style="background:#ffe8e8; color:#800000; padding:6px 14px; border-radius:50px; font-size:0.8rem; font-weight:600;" data-i18n="karir.job1.type">
                        Full Time
                    </span>

                    <h4 style="margin-top:15px; font-weight:700;" data-i18n="karir.job1.title">Legal Consultant</h4>
                    <p style="color:#64748b; font-size:0.95rem;" data-i18n="karir.job1.desc">
                        Bertanggung jawab memberikan konsultasi hukum kepada klien terkait kebutuhan bisnis dan legalitas perusahaan.
                    </p>

                    <h6 style="margin-top:20px; font-weight:700;" data-i18n="karir.requirement.heading">Requirement:</h6>
                    <ul style="padding-left:18px; color:#334155; font-size:0.9rem;">
                        <li data-i18n="karir.job1.req1">S1 Hukum (diutamakan)</li>
                        <li data-i18n="karir.job1.req2">Memahami legalitas perusahaan</li>
                        <li data-i18n="karir.job1.req3">Komunikatif &amp; problem solving</li>
                        <li data-i18n="karir.job1.req4">Pengalaman min. 1 tahun (lebih disukai)</li>
                    </ul>

                    <a href="mailto: hiring@lawgika.co.id" class="btn mt-3" style="background:#800000; color:#fff; border-radius:10px; padding:10px 20px;" data-i18n="karir.apply">
                        Lamar Sekarang
                    </a>

                </div>
            </div>

            {{-- CARD 2 --}}
            <div class="col-lg-5">
                <div style="background:#fff; border-radius:20px; padding:30px; border:1px solid #f0e4e8; box-shadow:0 10px 30px rgba(0,0,0,0.05); height:100%;">

                    <span style="background:#e0f2fe; color:#0369a1; padding:6px 14px; border-radius:50px; font-size:0.8rem; font-weight:600;" data-i18n="karir.job2.type">
                        Internship
                    </span>

                    <h4 style="margin-top:15px; font-weight:700;" data-i18n="karir.job2.title">Tax &amp; Accounting Intern</h4>
                    <p style="color:#64748b; font-size:0.95rem;" data-i18n="karir.job2.desc">
                        Membantu tim dalam proses administrasi perpajakan dan pelaporan keuangan klien.
                    </p>

                    <h6 style="margin-top:20px; font-weight:700;" data-i18n="karir.requirement.heading">Requirement:</h6>
                    <ul style="padding-left:18px; color:#334155; font-size:0.9rem;">
                        <li data-i18n="karir.job2.req1">Mahasiswa/A fresh graduate Akuntansi</li>
                        <li data-i18n="karir.job2.req2">Memahami dasar perpajakan</li>
                        <li data-i18n="karir.job2.req3">Teliti &amp; detail oriented</li>
                        <li data-i18n="karir.job2.req4">Mampu bekerja dalam tim</li>
                    </ul>

                <a href="mailto: hiring@lawgika.co.id" class="btn mt-3" style="background:#800000; color:#fff; border-radius:10px; padding:10px 20px;" data-i18n="karir.apply">
                        Lamar Sekarang
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection