@extends('layout.app')

@section('content')



<!-- About Section Start -->
<section class="about-section section-padding fix">
    <div class="container">
        <div class="about-wrapper">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="about-image-items">
                        <div class="about-sub">
                            Pendirian CV
                        </div>
                        <div class="shape-image">
                            <img
                                src="{{ asset('buyer-file/assets/img/about/shape.png') }}"
                                alt="img" />
                        </div>
                        <div
                            class="about-image-1 wow fadeInLeft"
                            data-wow-delay=".3s">
                            <img
                                src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80"
                                alt="img" />
                            <div
                                class="about-image-2 wow fadeInUp"
                                data-wow-delay=".5s">
                                <img
                                    src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80"
                                    alt="img" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <div class="section-title">
                            <span class="wow fadeInUp">Jasa Pendirian CV</span>
                            <h2
                                class="wow fadeInUp"
                                data-wow-delay=".3s">
                                Jasa Konsultan Legalitas & Pendirian Badan Usaha Terpercaya.
                            </h2>
                        </div>
                        <p
                            class="mt-3 mt-md-0 wow fadeInUp"
                            data-wow-delay=".5s">
                            There are many variations of passages of
                            Lorem Ipsum available, but the majority have
                            suffered alteration in some form, by
                            injected humour, or randomised words which
                            don't look even slightly believable.
                        </p>
                        <div class="about-counter-items">
                            <div
                                class="counter-items wow fadeInUp"
                                data-wow-delay=".3s">
                                <h2><span class="count">1.2</span>k+</h2>
                                <h6>Klien Terdaftar</h6>
                            </div>
                            <div
                                class="counter-items ps-0 wow fadeInUp"
                                data-wow-delay=".5s">
                                <h2><span class="count">100</span>%</h2>
                                <h6>Legalitas Valid</h6>
                            </div>
                            <div
                                class="video-thumb wow fadeInUp"
                                data-wow-delay=".7s">
                                <img
                                    src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=800&q=80"
                                    alt="video-img" />
                                <div class="video-box">
                                    <span
                                        class="button-text wow fadeInUp">
                                        <a
                                            href="https://www.youtube.com/watch?v=Cn4G2lZ_g2I"
                                            class="video-btn ripple video-popup">
                                            <i
                                                class="fa-solid fa-play"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Section Start -->
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
                    <br>
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
                        <li><span><i class="fa-solid fa-check"></i> Fasilitas Ruang Meeting atau podcast 12 jam / bulan</span></li>
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

<!-- Faq Section Start -->
<section class="faq-section fix section-padding pt-5">
    <div class="container">
        <div class="faq-wrapper">
            <div class="row g-4">
                <div class="col-lg-6 wow fadeInUp" data-wow-delay=".3s">
                    <div class="faq-image">
                        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=615&h=592&q=80" alt="img">
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

<!-- Testimonia Section Start -->
<section class="testimonial-section fix section-padding">
    <div class="container">
        <div class="section-title text-center">
            <span class="style-border wow fadeInUp">Testimonials</span>
            <h2 class="mb-3 wow fadeInUp" data-wow-delay=".3s">
                Impressions & Feedback
            </h2>
            <p class="wow fadeInUp" data-wow-delay=".5s">
                Aliquam viverra accumsan lectus in dignissim ante
                interdum eu Sed odio massa
            </p>
        </div>
        <div class="swiper testimonial-slider">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-card-items-2">
                        <div class="icon">
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                        <div class="client-info">
                            <div
                                class="client-img bg-cover"
                                style="
                                            background-image: url(&quot;https://i.pravatar.cc/150?img=11&quot;);
                                        "></div>
                            <div class="content">
                                <h4>Ronald Richards</h4>
                                <span>Direktur CV</span>
                            </div>
                        </div>
                        <p>
                            Praesent ut lacus a velit tincidunt aliquam
                            a eget urna. Sed ullamcorper tristique nisl
                            at pharetra turpis accumsan et etiam eu
                            sollicitudin eros. In imperdiet accumsan
                            felis sed.
                        </p>
                        <div class="client-bottom">
                            <div class="star">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card-items-2">
                        <div class="icon">
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                        <div class="client-info">
                            <div
                                class="client-img bg-cover"
                                style="
                                            background-image: url(&quot;https://i.pravatar.cc/150?img=12&quot;);
                                        "></div>
                            <div class="content">
                                <h4>Esther Howard</h4>
                                <span>Founder Startup</span>
                            </div>
                        </div>
                        <p>
                            Praesent ut lacus a velit tincidunt aliquam
                            a eget urna. Sed ullamcorper tristique nisl
                            at pharetra turpis accumsan et etiam eu
                            sollicitudin eros. In imperdiet accumsan
                            felis sed.
                        </p>
                        <div class="client-bottom">
                            <div class="star">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card-items-2">
                        <div class="icon">
                            <i class="fa-solid fa-quote-right"></i>
                        </div>
                        <div class="client-info">
                            <div
                                class="client-img bg-cover"
                                style="
                                            background-image: url(&quot;https://i.pravatar.cc/150?img=13&quot;);
                                        "></div>
                            <div class="content">
                                <h4>Courtney Henry</h4>
                                <span>Komisaris PT</span>
                            </div>
                        </div>
                        <p>
                            Praesent ut lacus a velit tincidunt aliquam
                            a eget urna. Sed ullamcorper tristique nisl
                            at pharetra turpis accumsan et etiam eu
                            sollicitudin eros. In imperdiet accumsan
                            felis sed.
                        </p>
                        <div class="client-bottom">
                            <div class="star">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-regular fa-star"></i>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="testimonial-click-text wow fadeInUp"
            data-wow-delay=".3s">
            <p>Explore more testimonials by</p>
            <a href="contact.html"> Click Here</a>
        </div>
    </div>
</section>


@endsection