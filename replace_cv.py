import sys
import re

file_path = '/Applications/XAMPP/xamppfiles/htdocs/lawgika-website/resources/views/services/pendirian-badan-usaha/pendirian-cv.blade.php'

try:
    with open(file_path, 'r', encoding='utf-8') as f:
        text = f.read()

    # 1. Hero
    breadcrumb_repl = '''       <!--<< Breadcrumb Section Start >>-->
        <div
            class="breadcrumb-wrapper bg-cover"
            style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1920&q=80');"
        >
            <div class="container">
                <div class="page-heading">
                    <div class="page-header-left">
                        <h1 class="wow fadeInUp" data-wow-delay=".3s">
                            Layanan Pendirian CV
                        </h1>
                        <ul
                            class="breadcrumb-items wow fadeInUp"
                            data-wow-delay=".5s"
                        >
                            <li>
                                <a href="{{('/')}}"> Home </a>
                            </li>
                            <li>
                                <i class="fa-regular fa-chevrons-right"></i>
                            </li>
                            <li>Layanan Pendirian CV</li>
                        </ul>
                    </div>
                    <div
                        class="breadcrumb-image wow fadeInUp"
                        data-wow-delay=".4s"
                    >
                        <img src="{{ asset('buyer-file/assets/img/breadcrumb.png') }}" alt="img" />
                    </div>
                </div>
            </div>
        </div>

'''
    text = re.sub(r'<!--<< Breadcrumb Section Start >>-->.*?<!-- About Section Start -->', breadcrumb_repl + '        <!-- About Section Start -->', text, flags=re.DOTALL)

    # 2. About
    text = text.replace('assets/img/about/01.jpg', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80')
    text = text.replace('assets/img/about/02.png', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80')
    text = text.replace('assets/img/about/video-img.jpg', 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80')

    text = re.sub(r'Choose a Website Hosting Partner Right\s*Now\.', 'Jasa Konsultan Legalitas & Pendirian Badan Usaha Terpercaya.', text)
    text = re.sub(r'<h2><span class="count">50</span>k</h2>\s*<h6>Global Customers</h6>', r'<h2><span class="count">1.2</span>k+</h2>\n                                        <h6>Klien Terdaftar</h6>', text)
    text = re.sub(r'<h2><span class="count">98</span>%</h2>\s*<h6>Success Case</h6>', r'<h2><span class="count">100</span>%</h2>\n                                        <h6>Legalitas Valid</h6>', text)

    # 3. Pricing
    pricing_repl = '''        <!-- Pricing Section Start -->
        <section class="pricing-section fix section-padding section-bg">
            <div class="pricing-shape">
                <img src="{{ asset('buyer-file/assets/img/pricing-shape.png') }}" alt="img" />
            </div>
            <div class="container">
                <div class="section-title text-center">
                    <span class="wow fadeInUp">Pricing Plans</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">
                        Pilih Paket <br />
                        Layanan Pendirian CV
                    </h2>
                </div>
                
                <div class="row">
                    <!-- Paket 1 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="pricing-items style-2">
                            <h6 class="top-text">PENDIRIAN PT PMA</h6>
                            <div class="pricing-header-2">
                                <h2>Rp 8.000.000</h2>
                            </div>
                            <ul class="pricing-list">
                                <li><span><i class="fa-solid fa-check"></i> Pengecekan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Pemesanan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Akta Pendirian CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> SK Menkumham</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Rekening PT Bank OCBC</span></li>
                            </ul>
                            <p class="mt-4 text-start">Layanan Pendirian CV area tertentu (Jabodetabek/Medan/Batam)</p>
                            <p class="text-muted text-start" style="font-size: 12px;">*PKKPR & Sertifikat Standar diproses sesuai dengan KBLI & modal yang dipersyaratkan. *EFIN Hanya khusus wilayah Jabodetabek, Medan dan Batam</p>
                            <div class="pricing-button">
                                <a href="#" class="pricing-btn">Pilih Paket <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Paket 2 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                        <div class="pricing-items style-2 active">
                            <h6 class="top-text">PENDIRIAN PT PMA + VO</h6>
                            <div class="pricing-header-2">
                                <h2>Rp 11.000.000</h2>
                            </div>
                            <ul class="pricing-list">
                                <li><span><i class="fa-solid fa-check"></i> Pengecekan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Pemesanan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Akta Pendirian CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> SK Menkumham</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Rekening PT Bank OCBC</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 6 jam / bulan</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Akses Wifi dan Smart TV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Layanan Print Scan dan Fotocopy</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Layanan Resepsionis</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Dashboard Login Klien</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Storage cloud dokumen</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Meeting Note AI</span></li>
                            </ul>
                            <p class="mt-4 text-start">Layanan Pendirian CV area tertentu (Jabodetabek/Medan/Batam)</p>
                            <p class="text-muted text-start" style="font-size: 12px;">*PKKPR & Sertifikat Standar diproses sesuai dengan KBLI & modal yang dipersyaratkan. *EFIN Hanya khusus wilayah Jabodetabek, Medan dan Batam</p>
                            <div class="pricing-button">
                                <a href="#" class="pricing-btn">Pilih Paket <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Paket 3 -->
                    <div class="col-xl-4 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".6s">
                        <div class="pricing-items style-2">
                            <h6 class="top-text">PENDIRIAN PT PMA + VO + CALL HANDLING</h6>
                            <div class="pricing-header-2">
                                <h2>Rp 13.500.000</h2>
                            </div>
                            <ul class="pricing-list">
                                <li><span><i class="fa-solid fa-check"></i> Pengecekan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Pemesanan Nama CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Akta Pendirian CV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> SK Menkumham</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NPWP & SKT & EFIN*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> NIB & Sertifikat Standar* & PKKPR*</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Rekening PT Bank OCBC</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Alamat Bisnis Eksklusif</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 60 jam / tahun</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Akses Wifi dan Smart TV</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Layanan Print Scan dan Fotocopy</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Pengelolaan surat/paket masuk</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Notifikasi Surat/Paket Masuk</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Surat Keterangan Domisili</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Gratis akses komunitas bisnis</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Layanan Resepsionis</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Dashboard Login Klien</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Storage cloud dokumen</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Meeting Note AI</span></li>
                                <li><span><i class="fa-solid fa-check"></i> Layanan Call Handling</span></li>
                            </ul>
                            <p class="mt-4 text-start">Layanan Pendirian CV area tertentu (Jabodetabek/Medan/Batam)</p>
                            <p class="text-muted text-start" style="font-size: 12px;">*PKKPR & Sertifikat Standar diproses sesuai dengan KBLI & modal yang dipersyaratkan. *EFIN Hanya khusus wilayah Jabodetabek, Medan dan Batam</p>
                            <div class="pricing-button">
                                <a href="#" class="pricing-btn">Pilih Paket <i class="fa-solid fa-arrow-right-long"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

'''
    text = re.sub(r'<!-- Pricing Section Start -->.*?<!-- Faq Section Start -->', pricing_repl + '        <!-- Faq Section Start -->', text, flags=re.DOTALL)

    # 4. FAQ
    faq_repl = '''        <!-- Faq Section Start -->
        <section class="faq-section fix section-padding pt-4">
            <div class="container">
                <div class="faq-wrapper">
                    <div class="row g-4">
                        <div class="col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                            <div class="faq-image">
                                <img src="{{ asset('buyer-file/assets/img/faq.png') }}" alt="img">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="faq-content">
                                <div class="section-title">
                                    <span class="wow fadeInUp">FAQ’S</span>
                                    <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                        Pertanyaan Seputar Legalitas & Pendirian CV
                                    </h2>
                                </div>
                                <div class="faq-accordion mt-4 mt-md-0">
                                    <div class="accordion" id="accordion">
                                        <div class="accordion-item wow fadeInUp" data-wow-delay=".3s">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1">
                                                    Berapa lama proses pendirian CV?
                                                </button>
                                            </h5>
                                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                                <div class="accordion-body">
                                                    Proses pendirian CV biasanya memakan waktu sekitar 3 sampai 7 hari kerja setelah semua dokumen persyaratan lengkap dan nama CV telah disetujui.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item wow fadeInUp" data-wow-delay=".5s">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2">
                                                    Apa syarat dokumen yang dibutuhkan?
                                                </button>
                                            </h5>
                                            <div id="faq2" class="accordion-collapse show" data-bs-parent="#accordion">
                                                <div class="accordion-body">
                                                    Secara umum, persyaratan dokumen meliputi fotokopi e-KTP dan NPWP para pendiri (minimal 2 orang: sekutu aktif dan pasif), surat keterangan domisili usaha, serta surat pernyataan modal.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item wow fadeInUp" data-wow-delay=".7s">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3">
                                                    Apakah CV bisa didirikan tanpa NPWP perusahaan?
                                                </button>
                                            </h5>
                                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                                <div class="accordion-body">
                                                    Tidak bisa. NPWP perusahaan menjadi salah satu syarat mutlak setelah Akta Notaris diterbitkan agar CV bisa sah beroperasi dan membayar pajak perusahaan. Kami akan membantu pengurusannya.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="accordion-item wow fadeInUp" data-wow-delay=".7s">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4">
                                                    Apa bedanya PT dan CV?
                                                </button>
                                            </h5>
                                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordion">
                                                <div class="accordion-body">
                                                    Perbedaan mendasar adalah jika terjadi kerugian, pemodal atau sekutu pasif CV hanya bertanggung jawab sebatas modal yang disetorkan. Namun, sekutu aktif bertanggung jawab penuh hingga harta pribadi.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

'''
    text = re.sub(r'<!-- Faq Section Start -->.*?<!-- Testimonia Section Start -->', faq_repl + '        <!-- Testimonia Section Start -->', text, flags=re.DOTALL)

    # 5. Testimonials
    text = text.replace('assets/img/testimonial/client-5.jpg', 'https://i.pravatar.cc/150?img=11')
    text = text.replace('assets/img/testimonial/client-6.jpg', 'https://i.pravatar.cc/150?img=12')
    text = text.replace('assets/img/testimonial/client-7.jpg', 'https://i.pravatar.cc/150?img=13')

    text = text.replace('Web Designer', 'Direktur CV')
    text = text.replace('President of Sales', 'Founder Startup')
    text = text.replace('Nursing Assistant', 'Komisaris PT')

    # Remove bottom logos
    text = re.sub(r'<img\s+src="assets/img/testimonial/wpbeginner-dark\.png"[^>]*>', '', text)
    text = re.sub(r'<img\s+src="assets/img/testimonial/hostadvice-dark\.png"[^>]*>', '', text)
    text = re.sub(r'<img\s+src="assets/img/testimonial/capterra\.png"[^>]*>', '', text)

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(text)
    
    print("Successfully replaced CV file contents")
except Exception as e:
    print(f"Error: {e}")
