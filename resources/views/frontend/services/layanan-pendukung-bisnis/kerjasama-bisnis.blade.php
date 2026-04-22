@extends('layout.app')

@section('content')

<style>
    :root {
        --primary: #4e0516;
        --primary-light: #7a0a23;
        --accent: #c9a03d;
        --dark: #1e1b2b;
        --gray: #64748b;
        --bg-light: #fdf8f5;
    }

    body {
        font-family: 'Inter', -apple-system, sans-serif;
        color: var(--dark);
    }

    /* ===== SOLUTION SECTION ===== */
    .pt-solution {
        padding: 80px 0;
        background: #fff;
    }

    .pt-solution h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 15px;
    }

    .pt-solution p {
        color: var(--gray);
        font-size: 1.05rem;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .solution-list {
        list-style: none;
        padding: 0;
        margin-bottom: 30px;
    }

    .solution-list li {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        color: #334155;
        font-weight: 500;
    }

    .solution-list li i {
        color: #10b981;
        font-size: 1.2rem;
    }

    .img-fluid-rounded {
        border-radius: 20px;
        max-width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 10px 30px rgba(78, 5, 22, 0.08);
    }

    /* ===== FAQ SECTION ===== */
    .pt-faq {
        padding: 80px 0;
        background: var(--bg-light);
    }

    .faq-item {
        background: #fff;
        border-radius: 12px;
        margin-bottom: 12px;
        border: 1px solid #f0e4e8;
        overflow: hidden;
    }

    .faq-question {
        padding: 20px 25px;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        user-select: none;
    }

    .faq-question i {
        transition: transform 0.2s ease;
        color: var(--accent);
        flex-shrink: 0;
    }

    .faq-answer {
        padding: 0 25px 20px;
        color: var(--gray);
        display: none;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    .section-badge {
        display: inline-block;
        color: var(--accent);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.82rem;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }


    .pt-form {
        padding: 80px 0;
        background: var(--bg-light);
    }

    .form-card {
        max-width: 680px;
        margin: 0 auto;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 12px 48px rgba(78, 5, 22, 0.07);
        border: 1px solid #f0e4e8;
        padding: 50px 48px;
    }

    .form-intro {
        background: linear-gradient(135deg, #fef9c3, #fef3c7);
        border: 1px solid #fde047;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 32px;
        font-size: 0.95rem;
        color: #713f12;
        line-height: 1.6;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: var(--dark);
        font-size: 0.95rem;
    }

    .form-group label .required {
        color: var(--primary);
        margin-left: 3px;
    }

    .form-control {
        width: 100%;
        padding: 13px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 1rem;
        color: #334155;
        background: #fff;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        font-family: inherit;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(78, 5, 22, 0.08);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .file-upload-area {
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s ease, background 0.2s ease;
    }

    .file-upload-area:hover {
        border-color: var(--primary);
        background: #fdf8f5;
    }

    .file-upload-area i {
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 8px;
        display: block;
    }

    .file-upload-area p {
        color: var(--gray);
        margin: 0;
        font-size: 0.9rem;
    }

    .file-name-display {
        color: var(--primary);
        font-weight: 600;
        margin-top: 8px;
        font-size: 0.88rem;
        display: none;
    }

    .btn-submit {
        display: block;
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 1.05rem;
        font-weight: 700;
        cursor: pointer;
        transition: opacity 0.2s ease, transform 0.15s ease;
        text-align: center;
        margin-top: 32px;
        letter-spacing: 0.3px;
    }

    .btn-submit:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-submit i {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .form-card {
            padding: 32px 24px;
        }
    }
</style>

{{-- ===== HERO / BREADCRUMB ===== --}}
<section class="page-title-area position-relative"
    style="
        background-image: linear-gradient(135deg, rgba(26, 2, 8, 0.78) 0%, rgba(45, 6, 16, 0.75) 50%, rgba(26, 2, 8, 0.78) 100%),
                          url('https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=1400&q=80');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding-top: 200px;
        padding-bottom: 100px;
    ">
    <div class="container position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="page-title-content">
                    <span class="text-white bg-danger rounded-pill px-3 py-1 fw-medium mb-3 d-inline-block shadow-sm"
                        style="font-size: 0.85rem">Lawgika | Kerjasama Bisnis</span>
                    <h1 class="text-white fw-bold mb-3 display-4">Bangun Kolaborasi Bersama Kami</h1>
                    <p class="text-white-50 form-text d-md-block d-none" style="font-size: 1.1rem">
                        Kami membuka peluang kerjasama dengan berbagai pihak. Isi form di bawah untuk mengajukan kolaborasi bersama Lawgika.
                    </p>
                </div>
            </div>
            <div class="col-lg-5 text-lg-end mt-4 mt-lg-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-lg-end justify-content-start mb-0">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-white text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">Kerjasama Bisnis</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

{{-- ===== SOLUSI KERJASAMA ===== --}}
<section class="pt-solution">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-badge">Mengapa Bermitra dengan Kami</span>
                <h2>Kolaborasi yang Saling Menguntungkan</h2>
                <p>Lawgika.co.id adalah platform layanan hukum dan bisnis yang terus berkembang. Kami membuka peluang kerjasama dengan berbagai pihak — mulai dari afiliasi, co-branding, hingga kemitraan strategis jangka panjang.</p>
                <ul class="solution-list">
                    <li><i class="fa-regular fa-circle-check"></i> Jaringan klien bisnis yang luas & aktif</li>
                    <li><i class="fa-regular fa-circle-check"></i> Platform digital yang terus berkembang</li>
                    <li><i class="fa-regular fa-circle-check"></i> Tim profesional yang responsif & transparan</li>
                    <li><i class="fa-regular fa-circle-check"></i> Model kerjasama fleksibel sesuai kebutuhan Anda</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80"
                    alt="Kerjasama Bisnis" class="img-fluid-rounded">
            </div>
        </div>
    </div>
</section>

{{-- ===== FORM KERJASAMA ===== --}}
<section class="pt-form">
    <div class="container">
        <div class="form-card">
            <div style="text-align:center; margin-bottom:32px;">
                <span style="display:inline-block; background:rgba(78,5,22,0.08); color:var(--primary); font-size:0.82rem; font-weight:700; letter-spacing:1.5px; text-transform:uppercase; border-radius:50px; padding:6px 18px; margin-bottom:14px;">
                    Formulir Kerjasama
                </span>
                <h2 style="font-size:1.75rem; font-weight:800; color:var(--dark); margin-bottom:10px;">Ajukan Kerjasama Anda</h2>
            </div>

            <div class="form-intro">
                <strong>Terima kasih atas minat Anda untuk bekerjasama dengan Lawgika.co.id.</strong><br>
                Silakan isi informasi berikut dan kami akan segera menghubungi Anda.
            </div>

            <form id="kerjasamaForm">
                <div class="form-group">
                    <label for="nama">Nama Lengkap <span class="required">*</span></label>
                    <input type="text" id="nama" name="nama" class="form-control" required placeholder="Masukkan nama lengkap Anda">
                </div>

                <div class="form-group">
                    <label for="perusahaan">Perusahaan / Brand <span class="required">*</span></label>
                    <input type="text" id="perusahaan" name="perusahaan" class="form-control" required placeholder="Nama perusahaan atau brand Anda">
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis Kerjasama <span class="required">*</span></label>
                    <textarea id="jenis" name="jenis" class="form-control" required placeholder="Contoh: Kerjasama afiliasi, co-branding, penyediaan layanan hukum, dll."></textarea>
                </div>

                <div class="form-group">
                    <label for="kontak">Nomor Kontak <span class="required">*</span></label>
                    <input type="text" id="kontak" name="kontak" class="form-control" required placeholder="Contoh: 08123456789">
                </div>

                <div class="form-group">
                    <label for="proposal">Proposal <span style="color:var(--gray); font-weight:400; font-size:0.88rem;">(opsional)</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('proposal').click()">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        <p>Klik untuk upload proposal Anda</p>
                        <p style="color:#94a3b8; font-size:0.8rem; margin-top:4px;">PDF, DOC, DOCX — Maks. 5MB</p>
                        <p id="proposalName" class="file-name-display"></p>
                    </div>
                    <input type="file" id="proposal" name="proposal" accept=".pdf,.doc,.docx"
                        style="display:none;" onchange="showProposalName(this)">
                </div>

                <button type="button" class="btn-submit" onclick="kirimKeWhatsApp()">
                    <i class="fa-brands fa-whatsapp"></i> Kirim Pengajuan Kerjasama
                </button>
            </form>
        </div>
    </div>
</section>

@include('layout.partials.layanan-kami')


{{-- ===== FAQ KERJASAMA ===== --}}
<section class="pt-faq">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">Pertanyaan Umum</span>
            <h2 style="font-size:2.2rem; font-weight:800; color:var(--dark); margin:0 0 12px;">FAQ Kerjasama Bisnis</h2>
            <p style="color:var(--gray); font-size:1.05rem;">Hal-hal yang sering ditanyakan seputar peluang kolaborasi bersama Lawgika</p>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="faq-item">
                    <div class="faq-question">Jenis kerjasama apa saja yang bisa diajukan? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Kami membuka berbagai jenis kerjasama, termasuk program afiliasi, co-branding, kemitraan layanan hukum, referral partner, sponsorship konten, hingga kerjasama teknis dan integrasi platform.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">Berapa lama proses review pengajuan kerjasama? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Tim kami akan menghubungi Anda dalam 1–3 hari kerja setelah pengajuan diterima. Proses evaluasi kerjasama biasanya membutuhkan waktu 3–7 hari kerja tergantung kompleksitas proposal.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">Apakah ada minimum skala bisnis untuk bisa mengajukan kerjasama? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Tidak ada syarat skala minimum. Kami terbuka untuk kerjasama dengan individu, UMKM, startup, maupun perusahaan besar — selama model kolaborasinya saling menguntungkan.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">Apakah perlu mengirim proposal tertulis? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Proposal opsional namun sangat membantu mempercepat proses evaluasi. Anda bisa mengisi form di atas terlebih dahulu, lalu melampirkan proposal saat tim kami menghubungi Anda.</div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">Bagaimana jika pengajuan saya ditolak? <i class="fa-solid fa-chevron-down"></i></div>
                    <div class="faq-answer">Kami akan memberikan feedback secara profesional. Anda tetap bisa mengajukan kembali dengan model kerjasama yang berbeda di kemudian hari.</div>
                </div>

            </div>
        </div>
    </div>
</section>


<script>
    function showProposalName(input) {
        const label = document.getElementById('proposalName');
        if (input.files && input.files[0]) {
            label.textContent = '📎 ' + input.files[0].name;
            label.style.display = 'block';
        }
    }

    function kirimKeWhatsApp() {
        const nama = document.getElementById('nama').value.trim();
        const perusahaan = document.getElementById('perusahaan').value.trim();
        const jenis = document.getElementById('jenis').value.trim();
        const kontak = document.getElementById('kontak').value.trim();

        if (!nama || !perusahaan || !jenis || !kontak) {
            alert('Mohon lengkapi semua field yang wajib diisi (*) sebelum mengirim.');
            return;
        }

        const pesan = `Halo Lawgika, saya ingin mengajukan kerjasama:

Nama: ${nama}
Perusahaan / Brand: ${perusahaan}
Jenis Kerjasama: ${jenis}
Kontak: ${kontak}

Mohon ditindaklanjuti, terima kasih.`;

        const encoded = encodeURIComponent(pesan);
        window.open(`https://wa.me/6281234567890?text=${encoded}`, '_blank');
    }
</script>

@endsection