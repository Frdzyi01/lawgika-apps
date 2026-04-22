@extends('layout.app')

@section('content')

{{-- Breadcrumb / Header Area --}}
<section class="page-title-area position-relative" style="background: linear-gradient(135deg, #1a0208 0%, #2d0610 50%, #1a0208 100%); padding-top: 180px; padding-bottom: 80px;">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm" style="font-size: 0.85rem">Karir</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Karir</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">Bergabunglah bersama tim Lawgika dan berkontribusi dalam mewujudkan ekosistem hukum digital di Indonesia.</p>
                </div>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Karir</li>
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
            <span style="color:#800000; font-weight:700; letter-spacing:2px; font-size:0.85rem;">WE ARE HIRING</span>
            <h2 style="font-weight:800; font-size:2.3rem; margin-top:10px;">Bergabung Bersama Lawgika</h2>
            <p style="color:#64748b; max-width:600px; margin:0 auto;">
                Kami membuka kesempatan bagi talenta terbaik untuk berkembang bersama dalam ekosistem hukum digital.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            {{-- CARD 1 --}}
            <div class="col-lg-5">
                <div style="background:#fff; border-radius:20px; padding:30px; border:1px solid #f0e4e8; box-shadow:0 10px 30px rgba(0,0,0,0.05); height:100%;">

                    <span style="background:#ffe8e8; color:#800000; padding:6px 14px; border-radius:50px; font-size:0.8rem; font-weight:600;">
                        Full Time
                    </span>

                    <h4 style="margin-top:15px; font-weight:700;">Legal Consultant</h4>
                    <p style="color:#64748b; font-size:0.95rem;">
                        Bertanggung jawab memberikan konsultasi hukum kepada klien terkait kebutuhan bisnis dan legalitas perusahaan.
                    </p>

                    <h6 style="margin-top:20px; font-weight:700;">Requirement:</h6>
                    <ul style="padding-left:18px; color:#334155; font-size:0.9rem;">
                        <li>S1 Hukum (diutamakan)</li>
                        <li>Memahami legalitas perusahaan</li>
                        <li>Komunikatif & problem solving</li>
                        <li>Pengalaman min. 1 tahun (lebih disukai)</li>
                    </ul>

                    <a href="#" class="btn mt-3" style="background:#800000; color:#fff; border-radius:10px; padding:10px 20px;">
                        Lamar Sekarang
                    </a>

                </div>
            </div>

            {{-- CARD 2 --}}
            <div class="col-lg-5">
                <div style="background:#fff; border-radius:20px; padding:30px; border:1px solid #f0e4e8; box-shadow:0 10px 30px rgba(0,0,0,0.05); height:100%;">

                    <span style="background:#e0f2fe; color:#0369a1; padding:6px 14px; border-radius:50px; font-size:0.8rem; font-weight:600;">
                        Internship
                    </span>

                    <h4 style="margin-top:15px; font-weight:700;">Tax & Accounting Intern</h4>
                    <p style="color:#64748b; font-size:0.95rem;">
                        Membantu tim dalam proses administrasi perpajakan dan pelaporan keuangan klien.
                    </p>

                    <h6 style="margin-top:20px; font-weight:700;">Requirement:</h6>
                    <ul style="padding-left:18px; color:#334155; font-size:0.9rem;">
                        <li>Mahasiswa/A fresh graduate Akuntansi</li>
                        <li>Memahami dasar perpajakan</li>
                        <li>Teliti & detail oriented</li>
                        <li>Mampu bekerja dalam tim</li>
                    </ul>

                    <a href="#" class="btn mt-3" style="background:#800000; color:#fff; border-radius:10px; padding:10px 20px;">
                        Lamar Sekarang
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection