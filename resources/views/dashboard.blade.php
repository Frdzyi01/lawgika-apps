 @extends('layout.app')

 @section('content')

 {{-- ===== PERFORMANCE OVERRIDE: buat halaman ringan & smooth ===== --}}
 <style>
   /*
    * 1. Matikan animasi WOW.js secara paksa:
    *    WOW.js menyembunyikan elemen dengan opacity:0 via animate.css.
    *    Override di sini → semua elemen langsung terlihat, tidak nunggu trigger.
    */
   .animated {
     animation-duration: 0.01ms !important;
     animation-delay: 0.01ms !important;
     animation-iteration-count: 1 !important;
     opacity: 1 !important;
     visibility: visible !important;
   }

   /* Pastikan elemen WOW yang belum di-animate juga terlihat */
   [class*="wow"] {
     opacity: 1 !important;
     visibility: visible !important;
     transform: none !important;
   }

   /*
    * 2. Matikan custom mouse cursor (berjalan setiap mousemove event).
    *    Mouse cursor asli lebih ringan.
    */
   .mouse-cursor,
   .cursor-inner,
   .cursor-outer {
     display: none !important;
   }

   /*
    * 3. content-visibility: auto → browser skip render section off-screen.
    *    Ini salah satu optimasi terbesar untuk halaman panjang.
    */
   .feature-hosting-section,
   .cta-contact-section,
   .data-center-section,
   #upcoming-event-section,
   .testimonial-section,
   .faq-section,
   .blog-section {
     content-visibility: auto;
     contain-intrinsic-size: 0 600px;
   }

   /*
    * 4. Kurangi box-shadow kompleks di semua section luar hero.
    *    Mengurangi paint complexity.
    */
   .sp-card,
   .ue-card,
   .layanan-card,
   .testimonial-item {
     box-shadow: 0 1px 6px rgba(0, 0, 0, 0.08) !important;
   }

   .sp-card:hover,
   .ue-card:hover,
   .layanan-card:hover {
     box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12) !important;
   }

   /*
    * 5. Pastikan marquee GPU-composited saja, tidak repaint.
    */
   .clients-marquee-track {
     will-change: transform;
   }

   /*
    * 6. Scroll behavior smooth — tapi hanya untuk click anchor,
    *    bukan force smooth-scroll yang bisa lag.
    */
   html {
     scroll-behavior: smooth;
   }
 </style>

 <script>
   /*
    * Override WOW.js SEBELUM main.js diload:
    * — Ganti konstruktor WOW dengan no-op agar new WOW().init() di main.js tidak jalan
    * — Semua elemen langsung visible tanpa perlu WOW
    */
   window.WOW = function() {
     return {
       init: function() {}
     };
   };

   /*
    * Matikan mousecursor() di main.js:
    * main.js memanggil mousecursor() yang pasang window.onmousemove handler.
    * Kita override querySelector agar cursor element tidak ditemukan.
    */
   (function() {
     var _orig = document.querySelector.bind(document);
     document.querySelector = function(sel) {
       if (sel === '.cursor-inner' || sel === '.cursor-outer') return null;
       return _orig(sel);
     };
     /* Restore setelah main.js selesai (500ms cukup) */
     setTimeout(function() {
       document.querySelector = _orig;
     }, 600);
   })();
 </script>

 <!-- ===== HERO CAROUSEL SECTION ===== -->

 <style>
   /* ---- Performance: GPU layers hanya untuk transform & opacity ---- */
   :root {
     --header-h: 110px;
   }

   .hero-carousel-section {
     background: #0d0d0d;
     padding-top: var(--header-h);
     padding-bottom: 0;
     overflow: hidden;
     /* Sembunyikan hingga JS siap – cegah flicker */
     visibility: hidden;
   }

   /* JS akan menambahkan class ini setelah page load */
   .hero-carousel-section.hero-ready {
     visibility: visible;
   }

   .hero-card {
     background: transparent;
     border-radius: 0;
     box-shadow: none;
     overflow: visible;
     position: relative;
     padding-bottom: 0;
   }

   /* ---- Slides wrapper ---- */
   .hero-slides-wrapper {
     position: relative;
     overflow: hidden;
     min-height: 580px;
     cursor: grab;
   }

   .hero-slides-wrapper:active {
     cursor: grabbing;
   }

   /* ---- Slide base: hidden by default ---- */
   .hero-slide {
     display: none;
     position: relative;
   }

   .hero-slide.active {
     display: flex;
   }

   /* ---- Background: NO Ken Burns (hapus scale transition berat) ---- */
   .hero-slide-bg {
     position: absolute;
     inset: 0;
     background-size: cover;
     background-position: center;
     background-repeat: no-repeat;
     z-index: 0;
   }

   /* ---- Dark gradient overlay ---- */
   .hero-slide-overlay {
     position: absolute;
     inset: 0;
     background: linear-gradient(105deg,
         rgba(5, 5, 12, 0.80) 0%,
         rgba(5, 5, 12, 0.55) 45%,
         rgba(5, 5, 12, 0.15) 100%);
     z-index: 1;
   }

   /* ---- Content row ---- */
   .hero-slide-inner {
     position: relative;
     z-index: 2;
     display: flex;
     align-items: center;
     width: 100%;
     min-height: 580px;
     padding: 90px 0 130px;
   }

   /* ---- Text block: animasi ringan HANYA pada slide aktif ---- */
   .hero-slide-text {
     max-width: 640px;
     /* Default: tidak ada animasi */
     opacity: 1;
     transform: none;
   }

   /* Animasi fade+slide ringan – hanya dipicu saat slide baru aktif */
   .hero-slide.active .hero-slide-text {
     animation: heroIn 0.50s ease both;
   }

   @keyframes heroIn {
     from {
       opacity: 0;
       transform: translateY(22px);
     }

     to {
       opacity: 1;
       transform: translateY(0);
     }
   }

   /* ---- Badge (tanpa backdrop-filter – berat di GPU) ---- */
   .hero-slide-badge {
     display: inline-flex;
     align-items: center;
     gap: 7px;
     background: rgba(220, 53, 69, 0.22);
     border: 1px solid rgba(220, 53, 69, 0.45);
     color: #ff8a99;
     font-size: 0.72rem;
     font-weight: 700;
     letter-spacing: 1.6px;
     text-transform: uppercase;
     border-radius: 50px;
     padding: 6px 16px;
     margin-bottom: 22px;
   }

   /* ---- Headline ---- */
   .hero-slide-title {
     font-size: 3.2rem;
     font-weight: 800;
     color: #ffffff;
     line-height: 1.12;
     margin-bottom: 20px;
     letter-spacing: -0.5px;
   }

   .hero-slide-title span {
     color: #ff8a99;
   }

   /* ---- Desc ---- */
   .hero-slide-desc {
     color: rgba(255, 255, 255, 0.78);
     font-size: 1.05rem;
     line-height: 1.75;
     margin-bottom: 34px;
     max-width: 500px;
   }

   /* ---- CTA ---- */
   .hero-slide-cta {
     display: inline-flex;
     align-items: center;
     gap: 10px;
     background: #dc3545;
     color: #fff !important;
     font-weight: 700;
     font-size: 0.95rem;
     padding: 14px 34px;
     border-radius: 50px;
     text-decoration: none;
     transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease;
     box-shadow: 0 6px 20px rgba(220, 53, 69, 0.40);
     letter-spacing: 0.3px;
     border: none;
     will-change: transform;
   }

   .hero-slide-cta:hover {
     transform: translateY(-3px);
     box-shadow: 0 14px 32px rgba(220, 53, 69, 0.52);
     background: #c82333;
     color: #fff !important;
   }

   .hero-slide-cta i {
     transition: transform 0.20s;
   }

   .hero-slide-cta:hover i {
     transform: translateX(4px);
   }

   /* ---- Trust badges ---- */
   .hero-trust {
     display: flex;
     align-items: center;
     flex-wrap: wrap;
     gap: 22px;
     margin-top: 30px;
   }

   .hero-trust-item {
     display: flex;
     align-items: center;
     gap: 7px;
     color: rgba(255, 255, 255, 0.80);
     font-size: 0.87rem;
     font-weight: 600;
   }

   .hero-trust-item i {
     color: #4ade80;
     font-size: 0.95rem;
   }

   /* ---- Pagination dots ---- */
   .hero-pagination {
     position: absolute;
     bottom: 26px;
     left: 0;
     right: 0;
     z-index: 5;
     display: flex;
     justify-content: center;
   }

   .hero-pagination-inner {
     display: flex;
     gap: 10px;
     align-items: center;
   }

   .hero-dot {
     width: 10px;
     height: 10px;
     border-radius: 50%;
     background: rgba(255, 255, 255, 0.35);
     border: none;
     padding: 0;
     cursor: pointer;
     transition: width 0.28s ease, background 0.28s ease;
     outline: none;
   }

   .hero-dot.active {
     background: #fff;
     width: 28px;
     border-radius: 6px;
   }

   /* ---- Floating search bar ---- */
   .hero-search-bar {
     position: relative;
     z-index: 10;
     margin-top: -38px;
   }

   .hero-search-card {
     background: #fff;
     border-radius: 18px;
     box-shadow: 0 16px 48px rgba(0, 0, 0, 0.13), 0 4px 16px rgba(0, 0, 0, 0.06);
     padding: 22px 26px;
     display: flex;
     align-items: center;
     gap: 14px;
     flex-wrap: wrap;
   }

   .hero-search-label {
     font-size: 0.95rem;
     font-weight: 700;
     color: #1a0208;
     white-space: nowrap;
   }

   .hero-search-divider {
     width: 1px;
     height: 30px;
     background: #e5e7eb;
     flex-shrink: 0;
   }

   .hero-search-input-wrap {
     flex: 1;
     min-width: 180px;
     display: flex;
     align-items: center;
     gap: 10px;
     background: #f4f2f7;
     border-radius: 10px;
     padding: 11px 16px;
   }

   .hero-search-input-wrap i {
     color: #9ca3af;
   }

   .hero-search-input-wrap input {
     border: none;
     background: transparent;
     outline: none;
     font-size: 0.92rem;
     color: #374151;
     width: 100%;
   }

   .hero-search-input-wrap input::placeholder {
     color: #9ca3af;
   }

   .hero-search-btn {
     display: inline-flex;
     align-items: center;
     gap: 8px;
     background: #dc3545;
     color: #fff;
     font-weight: 700;
     font-size: 0.91rem;
     padding: 12px 26px;
     border-radius: 10px;
     text-decoration: none;
     border: none;
     cursor: pointer;
     transition: background 0.22s, transform 0.18s;
     white-space: nowrap;
   }

   .hero-search-btn:hover {
     background: #b91c1c;
     color: #fff;
     transform: translateY(-1px);
   }

   /* ---- Responsive ---- */
   @media (max-width: 991.98px) {
     .hero-slide-title {
       font-size: 2.4rem;
     }

     .hero-slide-inner {
       padding: 68px 0 96px;
       min-height: 460px;
     }

     .hero-slides-wrapper {
       min-height: 460px;
     }
   }

   @media (max-width: 767.98px) {
     .hero-slide-title {
       font-size: 1.95rem;
     }

     .hero-slide-desc {
       font-size: 0.97rem;
     }

     .hero-slide-inner {
       padding: 52px 0 78px;
       min-height: 390px;
     }

     .hero-slides-wrapper {
       min-height: 390px;
     }

     .hero-trust {
       gap: 13px;
     }

     .hero-trust-item {
       font-size: 0.81rem;
     }

     .hero-search-card {
       padding: 16px;
     }

     .hero-search-divider {
       display: none;
     }
   }

   @media (max-width: 575.98px) {
     .hero-slide-title {
       font-size: 1.7rem;
     }

     .hero-slide-inner {
       padding: 42px 0 68px;
     }

     .hero-search-bar {
       margin-top: -22px;
     }

     .hero-pagination {
       bottom: 14px;
     }
   }
 </style>

 <script>
   /* ---- Dynamic header offset ---- */
   (function() {
     function setH() {
       var hdr = document.querySelector('.header-section-1');
       if (!hdr) return;
       document.documentElement.style.setProperty('--header-h', hdr.getBoundingClientRect().height + 'px');
     }
     setH();
     window.addEventListener('load', setH);
     var t;
     window.addEventListener('resize', function() {
       clearTimeout(t);
       t = setTimeout(setH, 100);
     });
     if (window.ResizeObserver) {
       var hdr = document.querySelector('.header-section-1');
       if (hdr) new ResizeObserver(setH).observe(hdr);
     }
   })();
 </script>

 <section class="hero-carousel-section" id="heroSection">
   <div class="hero-card">
     <div class="hero-slides-wrapper" id="heroSlidesWrapper">

       <!-- Slide 1: Konsultasi Bisnis -->
       <div class="hero-slide active" id="heroSlide0">
         <div class="hero-slide-bg" style="background-image:url('https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=1400&q=75&auto=format&fit=crop');"></div>
         <div class="hero-slide-overlay"></div>
         <div class="hero-slide-inner">
           <div class="container" style="max-width:1200px;">
             <div class="hero-slide-text">
               <div class="hero-slide-badge"><i class="fas fa-star"></i> Layanan Unggulan</div>
               <h1 class="hero-slide-title">Konsultasi <span>Bisnis &amp; Legal</span><br>Profesional</h1>
               <p class="hero-slide-desc">Dapatkan panduan hukum dan bisnis dari para ahli berpengalaman. Kami membantu Anda mengelola regulasi, risiko hukum, dan strategi bisnis secara komprehensif.</p>
               <a href="/layanan-konsultasi-bisnis" class="hero-slide-cta">Mulai Konsultasi <i class="fas fa-arrow-right"></i></a>
               <div class="hero-trust">
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> 500+ Klien</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Konsultan Profesional</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Legal &amp; Terpercaya</div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <!-- Slide 2: Pendirian PT/CV -->
       <div class="hero-slide" id="heroSlide1">
         <div class="hero-slide-bg" style="background-image:url('https://images.unsplash.com/photo-1531973576160-7125cd663d86?w=1400&q=75&auto=format&fit=crop');"></div>
         <div class="hero-slide-overlay"></div>
         <div class="hero-slide-inner">
           <div class="container" style="max-width:1200px;">
             <div class="hero-slide-text">
               <div class="hero-slide-badge"><i class="fas fa-building"></i> Pendirian Badan Usaha</div>
               <h1 class="hero-slide-title">Pendirian <span>PT / CV</span><br>Mudah &amp; Cepat</h1>
               <p class="hero-slide-desc">Kami mengurus seluruh proses pendirian perusahaan Anda dari awal hingga dokumen resmi siap. Mulai dari pembuatan akta, NIB, hingga izin usaha lengkap.</p>
               <a href="/pendirian-pt" class="hero-slide-cta">Daftarkan Perusahaan <i class="fas fa-arrow-right"></i></a>
               <div class="hero-trust">
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> 500+ Klien</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Konsultan Profesional</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Legal &amp; Terpercaya</div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <!-- Slide 3: Virtual Office -->
       <div class="hero-slide" id="heroSlide2">
         <div class="hero-slide-bg" style="background-image:url('https://images.unsplash.com/photo-1497366216548-37526070297c?w=1400&q=75&auto=format&fit=crop');"></div>
         <div class="hero-slide-overlay"></div>
         <div class="hero-slide-inner">
           <div class="container" style="max-width:1200px;">
             <div class="hero-slide-text">
               <div class="hero-slide-badge"><i class="fas fa-map-marker-alt"></i> Layanan Pendukung</div>
               <h1 class="hero-slide-title">Virtual Office <span>Premium</span><br>Lokasi Strategis</h1>
               <p class="hero-slide-desc">Miliki alamat kantor prestisius di pusat bisnis tanpa biaya sewa kantoran besar. Paket lengkap dengan layanan penanganan surat, telepon, dan ruang rapat.</p>
               <a href="/virtual-office" class="hero-slide-cta">Lihat Paket VO <i class="fas fa-arrow-right"></i></a>
               <div class="hero-trust">
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> 500+ Klien</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Konsultan Profesional</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Legal &amp; Terpercaya</div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <!-- Slide 4: Perizinan & Legal -->
       <div class="hero-slide" id="heroSlide3">
         <div class="hero-slide-bg" style="background-image:url('https://images.unsplash.com/photo-1521791055366-0d553872952f?w=1400&q=75&auto=format&fit=crop');"></div>
         <div class="hero-slide-overlay"></div>
         <div class="hero-slide-inner">
           <div class="container" style="max-width:1200px;">
             <div class="hero-slide-text">
               <div class="hero-slide-badge"><i class="fas fa-balance-scale"></i> Perizinan &amp; Hukum</div>
               <h1 class="hero-slide-title">Perizinan &amp; <span>Layanan Hukum</span><br>Terpercaya</h1>
               <p class="hero-slide-desc">Kami membantu pengurusan NIB, HAKI, perubahan anggaran dasar, drafting perjanjian bisnis, dan berbagai kebutuhan legal perusahaan Anda secara profesional.</p>
               <a href="/nib-dan-oss" class="hero-slide-cta">Urus Perizinan <i class="fas fa-arrow-right"></i></a>
               <div class="hero-trust">
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> 500+ Klien</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Konsultan Profesional</div>
                 <div class="hero-trust-item"><i class="fas fa-check-circle"></i> Legal &amp; Terpercaya</div>
               </div>
             </div>
           </div>
         </div>
       </div>

       <!-- Pagination -->
       <div class="hero-pagination" id="heroPagination">
         <div class="hero-pagination-inner">
           <button class="hero-dot active" data-slide="0" aria-label="Slide 1"></button>
           <button class="hero-dot" data-slide="1" aria-label="Slide 2"></button>
           <button class="hero-dot" data-slide="2" aria-label="Slide 3"></button>
           <button class="hero-dot" data-slide="3" aria-label="Slide 4"></button>
         </div>
       </div>

     </div><!-- /hero-slides-wrapper -->
   </div><!-- /hero-card -->

   <!-- Floating search bar -->
   <div class="hero-search-bar">
     <div class="container" style="max-width:1200px;">
       <div class="hero-search-card">
         <span class="hero-search-label">Cari Layanan</span>
         <div class="hero-search-divider"></div>
         <div class="hero-search-input-wrap">
           <i class="fas fa-search"></i>
           <input type="text" placeholder="Ketik layanan yang Anda butuhkan...">
         </div>
         <a href="#layanan-kami-section" class="hero-search-btn">
           <i class="fas fa-search"></i> Telusuri
         </a>
       </div>
     </div>
   </div>
 </section>

 <script>
   /* ============================================================
       HERO SLIDER – Performance-optimized
       Flow: page load → show section → start auto-slide
    ============================================================ */
   (function() {
     'use strict';

     var TOTAL = 4;
     var current = 0;
     var interval = null;
     var DELAY = 5000;
     var touchX = 0;
     var mouseDown = false;

     /* ---- DOM refs (cached once) ---- */
     var slides = document.querySelectorAll('.hero-slide');
     var dots = document.querySelectorAll('.hero-dot');
     var wrapper = document.getElementById('heroSlidesWrapper');
     var section = document.getElementById('heroSection');

     /* ---- Show a specific slide (no animation re-trigger issue) ---- */
     function showSlide(idx) {
       if (idx === current) return; // skip if same slide
       slides.forEach(function(el, i) {
         el.classList.toggle('active', i === idx);
       });
       dots.forEach(function(el, i) {
         el.classList.toggle('active', i === idx);
       });
       current = idx;
     }

     /* ---- Auto-slide ---- */
     function startAuto() {
       if (interval) return;
       interval = setInterval(function() {
         showSlide((current + 1) % TOTAL);
       }, DELAY);
     }

     function stopAuto() {
       clearInterval(interval);
       interval = null;
     }

     function resetAuto() {
       stopAuto();
       startAuto();
     }

     /* ---- Dot clicks ---- */
     dots.forEach(function(dot) {
       dot.addEventListener('click', function() {
         showSlide(parseInt(this.dataset.slide, 10));
         resetAuto();
       });
     });

     /* ---- Touch swipe ---- */
     if (wrapper) {
       wrapper.addEventListener('touchstart', function(e) {
         touchX = e.changedTouches[0].screenX;
       }, {
         passive: true
       });

       wrapper.addEventListener('touchend', function(e) {
         var diff = touchX - e.changedTouches[0].screenX;
         if (Math.abs(diff) > 44) {
           showSlide(diff > 0 ?
             (current + 1) % TOTAL :
             (current - 1 + TOTAL) % TOTAL);
           resetAuto();
         }
       }, {
         passive: true
       });

       /* ---- Mouse drag ---- */
       wrapper.addEventListener('mousedown', function(e) {
         mouseDown = true;
         touchX = e.screenX;
       });
       wrapper.addEventListener('mouseup', function(e) {
         if (!mouseDown) return;
         mouseDown = false;
         var diff = touchX - e.screenX;
         if (Math.abs(diff) > 54) {
           showSlide(diff > 0 ?
             (current + 1) % TOTAL :
             (current - 1 + TOTAL) % TOTAL);
           resetAuto();
         }
       });
       wrapper.addEventListener('mouseleave', function() {
         mouseDown = false;
       });
     }

     /* ---- Wait for page load, THEN reveal hero & start slider ---- */
     function initHero() {
       if (section) section.classList.add('hero-ready');
       startAuto();
     }

     if (document.readyState === 'complete') {
       initHero();
     } else {
       window.addEventListener('load', initHero);
     }

   })();
 </script>

 <!-- ===== CLIENT LOGOS MARQUEE SECTION ===== -->
 <style>
   /* ---- Section wrapper ---- */
   .clients-section {
     background: linear-gradient(180deg, #f7f6f8 0%, #ffffff 100%);
     padding: 36px 0 72px;
     position: relative;
     overflow: hidden;
   }

   /* ---- Header text ---- */
   .clients-header {
     text-align: center;
     margin-bottom: 12px;
   }

   .clients-eyebrow {
     display: inline-flex;
     align-items: center;
     gap: 10px;
     color: #9ca3af;
     font-size: 0.78rem;
     font-weight: 700;
     letter-spacing: 2px;
     text-transform: uppercase;
     margin-bottom: 14px;
   }

   .clients-eyebrow::before,
   .clients-eyebrow::after {
     content: '';
     display: block;
     width: 36px;
     height: 1.5px;
     background: #d1d5db;
     border-radius: 2px;
   }

   .clients-title {
     font-size: 1.55rem;
     font-weight: 800;
     color: #1a0208;
     letter-spacing: -0.3px;
     margin-bottom: 6px;
   }

   .clients-subtitle {
     font-size: 0.93rem;
     color: #9ca3af;
     margin-bottom: 0;
   }

   /* ---- Stats row ---- */
   .clients-stats {
     display: flex;
     justify-content: center;
     gap: 48px;
     margin: 36px 0 48px;
     flex-wrap: wrap;
   }

   .clients-stat-item {
     text-align: center;
   }

   .clients-stat-number {
     font-size: 1.9rem;
     font-weight: 900;
     color: #4e0516;
     line-height: 1;
     margin-bottom: 4px;
   }

   .clients-stat-label {
     font-size: 0.8rem;
     color: #9ca3af;
     font-weight: 600;
     letter-spacing: 0.5px;
   }

   /* ---- Marquee track ---- */
   .clients-marquee-outer {
     position: relative;
     overflow: hidden;
   }

   /* Fade edges */
   .clients-marquee-outer::before,
   .clients-marquee-outer::after {
     content: '';
     position: absolute;
     top: 0;
     bottom: 0;
     width: 120px;
     z-index: 2;
     pointer-events: none;
   }

   .clients-marquee-outer::before {
     left: 0;
     background: linear-gradient(to right, #f7f6f8, transparent);
   }

   .clients-marquee-outer::after {
     right: 0;
     background: linear-gradient(to left, #f7f6f8, transparent);
   }

   .clients-marquee-track {
     display: flex;
     align-items: center;
     gap: 20px;
     width: max-content;
     animation: clientsScroll 32s linear infinite;
     padding: 12px 0;
     will-change: transform;
   }

   .clients-marquee-track.paused {
     animation-play-state: paused;
   }

   @keyframes clientsScroll {
     from {
       transform: translateX(0);
     }

     to {
       transform: translateX(-50%);
     }
   }

   /* ---- Individual logo card ---- */
   .client-logo-card {
     flex-shrink: 0;
     background: #ffffff;
     border-radius: 16px;
     border: 1.5px solid #f0eaec;
     width: 148px;
     height: 82px;
     display: flex;
     align-items: center;
     justify-content: center;
     padding: 18px 22px;
     box-shadow: 0 2px 12px rgba(78, 5, 22, 0.05);
     transition: transform 0.25s ease, box-shadow 0.25s ease;
     cursor: default;
   }

   .client-logo-card:hover {
     transform: translateY(-4px);
     box-shadow: 0 10px 28px rgba(78, 5, 22, 0.11);
     border-color: #f5d0d9;
   }

   .client-logo-card img {
     max-height: 34px;
     max-width: 110px;
     width: auto;
     opacity: 0.55;
     transition: opacity 0.25s ease;
     display: block;
     margin: 0 auto;
   }

   .client-logo-card:hover img {
     opacity: 1;
   }

   /* ---- Fallback text logo (if SVG fails) ---- */
   .client-logo-text {
     font-size: 0.82rem;
     font-weight: 800;
     letter-spacing: 1px;
     color: #6b7280;
     text-transform: uppercase;
     opacity: 0.6;
     transition: opacity 0.3s, color 0.3s;
   }

   .client-logo-card:hover .client-logo-text {
     opacity: 1;
     color: #4e0516;
   }

   /* Mobile */
   @media (max-width: 576px) {
     .clients-section {
       padding: 56px 0 60px;
     }

     .clients-title {
       font-size: 1.3rem;
     }

     .clients-stat-number {
       font-size: 1.55rem;
     }

     .clients-stats {
       gap: 28px;
     }

     .clients-marquee-outer::before,
     .clients-marquee-outer::after {
       width: 60px;
     }

     .client-logo-card {
       width: 130px;
       height: 72px;
     }
   }
 </style>

 <section class="clients-section">
   <div class="container" style="max-width:1200px;">

     <!-- Header -->
     <div class="clients-header">
       <h2 class="clients-title">Dipercaya oleh Berbagai Bisnis &amp; Individu</h2>
       <p class="clients-subtitle">Konsultasi terpercaya untuk pertumbuhan bisnis yang berkelanjutan</p>
     </div>

     <!-- Marquee carousel -->
     <div class="clients-marquee-outer" id="clientsMarqueeOuter">
       <div class="clients-marquee-track" id="clientsMarqueeTrack">

         <!-- Set 1 (original) -->
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-placed.svg')}}" alt="Placed"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>PLACED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-cuebiq.svg')}}" alt="Cuebiq"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>CUEBIQ</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-factual.svg')}}" alt="Factual"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>FACTUAL</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-place-iq.svg')}}" alt="PlaceIQ"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>PLACE IQ</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-airmeet.svg')}}" alt="Airmeet"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>AIRMEET</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-spendflo.svg')}}" alt="Spendflo"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>SPENDFLO</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-reed-elsevier.svg')}}" alt="Reed Elsevier"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>REED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-vuori.svg')}}" alt="Vuori"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>VUORI</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-versed.svg')}}" alt="Versed"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>VERSED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-matrixian.svg')}}" alt="Matrixian"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>MATRIXIAN</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-klippa.svg')}}" alt="Klippa"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>KLIPPA</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-factual.svg')}}" alt="Factual 2"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>FACTUAL</span>'">
         </div>

         <!-- Set 2 (duplicate for seamless loop) -->
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-placed.svg')}}" alt="Placed"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>PLACED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-cuebiq.svg')}}" alt="Cuebiq"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>CUEBIQ</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-factual.svg')}}" alt="Factual"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>FACTUAL</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-place-iq.svg')}}" alt="PlaceIQ"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>PLACE IQ</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-airmeet.svg')}}" alt="Airmeet"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>AIRMEET</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-spendflo.svg')}}" alt="Spendflo"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>SPENDFLO</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-reed-elsevier.svg')}}" alt="Reed Elsevier"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>REED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-vuori.svg')}}" alt="Vuori"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>VUORI</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-versed.svg')}}" alt="Versed"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>VERSED</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-matrixian.svg')}}" alt="Matrixian"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>MATRIXIAN</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-klippa.svg')}}" alt="Klippa"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>KLIPPA</span>'">
         </div>
         <div class="client-logo-card">
           <img src="{{('buyer-file/assets/img/home/icons/icon-factual.svg')}}" alt="Factual 2"
             onerror="this.outerHTML='<span class=\'client-logo-text\'>FACTUAL</span>'">
         </div>

       </div><!-- end track -->
     </div><!-- end outer -->

   </div>
 </section>

 <script>
   (function() {
     var track = document.getElementById('clientsMarqueeTrack');
     if (!track) return;

     // Pause on mouse hover
     track.addEventListener('mouseenter', function() {
       track.classList.add('paused');
     });
     track.addEventListener('mouseleave', function() {
       track.classList.remove('paused');
     });

     // Pause on touch (mobile: pause while touching, resume after)
     track.addEventListener('touchstart', function() {
       track.classList.add('paused');
     }, {
       passive: true
     });
     track.addEventListener('touchend', function() {
       setTimeout(function() {
         track.classList.remove('paused');
       }, 800);
     }, {
       passive: true
     });
   })();
 </script>


 <!-- ===== SPECIAL PROMO SECTION ===== -->
 <style>
   #special-promo-section {
     background: #f8f9fa;
     padding: 64px 0 72px;
   }

   .sp-header {
     display: flex;
     align-items: flex-end;
     justify-content: space-between;
     flex-wrap: wrap;
     gap: 12px;
     margin-bottom: 28px;
   }

   .sp-eyebrow {
     display: inline-flex;
     align-items: center;
     gap: 6px;
     background: #fff1f3;
     color: #dc3545;
     font-size: 0.72rem;
     font-weight: 700;
     letter-spacing: 1.2px;
     text-transform: uppercase;
     border-radius: 50px;
     padding: 4px 12px;
     margin-bottom: 6px;
   }

   .sp-main-title {
     font-size: 1.9rem;
     font-weight: 700;
     color: #111827;
     letter-spacing: -0.3px;
     margin-bottom: 0;
     line-height: 1.2;
   }

   .sp-main-title span {
     color: #dc3545;
   }

   .sp-other-btn {
     display: inline-flex;
     align-items: center;
     gap: 6px;
     border: 1.5px solid #dc3545;
     color: #dc3545;
     background: transparent;
     font-size: 0.875rem;
     font-weight: 600;
     padding: 8px 20px;
     border-radius: 8px;
     text-decoration: none;
     transition: background 0.22s, color 0.22s;
     white-space: nowrap;
   }

   .sp-other-btn:hover {
     background: #dc3545;
     color: #fff;
   }

   /* ---- Promo cards: static by default, grid animates as one unit ---- */
   .sp-card {
     background: #fff;
     border-radius: 14px;
     box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);
     border: none;
     overflow: hidden;
     height: 100%;
     display: flex;
     flex-direction: column;
   }

   .sp-card:hover {
     box-shadow: 0 6px 20px rgba(0, 0, 0, 0.11);
   }

   /* Grid container fades in once */
   #spPromoGrid {
     opacity: 0;
     transform: translateY(16px);
   }

   #spPromoGrid.sp-grid-visible {
     opacity: 1;
     transform: translateY(0);
     transition: opacity 0.38s ease, transform 0.38s ease;
   }

   .sp-card-img-wrap {
     position: relative;
     overflow: hidden;
     flex-shrink: 0;
   }

   .sp-card-img-wrap img {
     width: 100%;
     height: 200px;
     object-fit: cover;
     display: block;
   }

   .sp-img-badge {
     position: absolute;
     top: 12px;
     left: 12px;
     background: #dc3545;
     color: #fff;
     font-size: 0.68rem;
     font-weight: 800;
     letter-spacing: 0.5px;
     text-transform: uppercase;
     padding: 4px 10px;
     border-radius: 50px;
     line-height: 1;
     z-index: 2;
   }

   .sp-img-badge.badge-gold {
     background: linear-gradient(135deg, #f5c842, #e6a800);
     color: #3a0310;
   }

   .sp-card-body {
     padding: 18px 18px 22px;
     flex: 1;
     display: flex;
     flex-direction: column;
   }

   .sp-card-title {
     font-size: 1rem;
     font-weight: 700;
     color: #111827;
     margin-bottom: 8px;
     line-height: 1.3;
   }

   .sp-card-desc {
     font-size: 0.83rem;
     color: #6b7280;
     line-height: 1.6;
     flex: 1;
     margin-bottom: 14px;
   }

   .sp-price-block {
     margin-bottom: 14px;
   }

   .sp-price-old {
     font-size: 0.75rem;
     color: #9ca3af;
     text-decoration: line-through;
     display: block;
     margin-bottom: 2px;
   }

   .sp-price-new {
     font-size: 1.1rem;
     font-weight: 700;
     color: #dc3545;
   }

   .sp-card-btn {
     background: #dc3545;
     color: #fff;
     border: none;
     border-radius: 8px;
     padding: 9px 0;
     font-size: 0.875rem;
     font-weight: 600;
     width: 100%;
     display: flex;
     align-items: center;
     justify-content: center;
     gap: 6px;
     text-decoration: none;
     cursor: pointer;
     transition: background 0.22s, transform 0.15s;
   }

   .sp-card-btn:hover {
     background: #b91c1c;
     color: #fff;
     transform: translateY(-1px);
   }

   .sp-card-btn i {
     font-size: 0.75rem;
     transition: transform 0.2s;
   }

   .sp-card-btn:hover i {
     transform: translateX(3px);
   }

   @media (max-width: 767.98px) {
     #special-promo-section {
       padding: 48px 0 56px;
     }

     .sp-main-title {
       font-size: 1.6rem;
     }

     .sp-header {
       flex-direction: column;
       align-items: flex-start;
     }
   }
 </style>

 <section id="special-promo-section" aria-label="Special Promo">
   <div class="container">
     <div class="sp-header">
       <div>
         <div class="sp-eyebrow"><i class="fas fa-bolt"></i> Penawaran Terbatas</div>
         <h2 class="sp-main-title">Special <span>Promo</span> Lawgika</h2>
       </div>
       <a href="#layanan-kami-section" class="sp-other-btn">
         Promo Lainnya <i class="fas fa-long-arrow-alt-right"></i>
       </a>
     </div>
     <div class="row g-4" id="spPromoGrid">
      @forelse($promos as $promo)
       <div class="col-12 col-md-6 col-lg-3">
         <div class="sp-card">
           <div class="sp-card-img-wrap">
             @if($promo->gambar)
               <img src="{{ asset('storage/' . $promo->gambar) }}" alt="{{ $promo->judul }}" loading="lazy">
             @else
               <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=500&q=80" alt="{{ $promo->judul }}" loading="lazy">
             @endif
             <span class="sp-img-badge">
               @if($promo->tipe_diskon === 'persen')
                 HEMAT {{ number_format($promo->diskon, 0) }}%
               @else
                 HEMAT Rp {{ number_format($promo->diskon, 0, ',', '.') }}
               @endif
             </span>
           </div>
           <div class="sp-card-body">
             <div class="sp-card-title">{{ $promo->judul }}</div>
             <div class="sp-card-desc">{{ Str::limit($promo->deskripsi, 100) }}</div>
             <div class="sp-price-block">
               <span class="sp-price-new">
                 @if($promo->tipe_diskon === 'persen')
                   Diskon {{ number_format($promo->diskon, 0) }}%
                 @else
                   Rp {{ number_format($promo->diskon, 0, ',', '.') }}
                 @endif
               </span>
             </div>
             <a href="#" class="sp-card-btn">Ambil Promo <i class="fas fa-arrow-right"></i></a>
           </div>
         </div>
       </div>
      @empty
       <div class="col-12 text-center text-muted py-4">Belum ada promo aktif saat ini.</div>
      @endforelse
     </div>
   </div>
 </section>

 <script>
   /* Promo: animate the GRID as one unit – 1 observer, 1 class toggle */
   (function() {
     'use strict';
     var grid = document.getElementById('spPromoGrid');
     if (!grid) return;

     if (!('IntersectionObserver' in window)) {
       grid.classList.add('sp-grid-visible');
       return;
     }

     var obs = new IntersectionObserver(function(entries) {
       if (entries[0].isIntersecting) {
         grid.classList.add('sp-grid-visible');
         obs.disconnect(); /* one-shot */
       }
     }, {
       threshold: 0.15
     });

     obs.observe(grid);
   })();
 </script>





 {{-- ========================================================= --}}
 {{-- SECTION: LAYANAN KAMI - Tab Navigation + Service Cards    --}}
 {{-- Include dengan: @include('layout.partials.layanan-kami')  --}}
 {{-- ========================================================= --}}

 <style>
   /* ==============================
       LAYANAN KAMI – Custom Styles
    ============================== */
   #layanan-kami-section {
     background-color: #fff;
     padding: 72px 0 80px;
   }

   /* ------- Section Title ------- */
   .layanan-section-title {
     font-size: 2rem;
     font-weight: 700;
     color: #111827;
     letter-spacing: -0.3px;
     margin-bottom: 0;
   }

   /* ------- Tab Nav ------- */
   .layanan-nav {
     border-bottom: 1.5px solid #e5e7eb;
     gap: 0;
     flex-wrap: nowrap;
     overflow-x: auto;
     scrollbar-width: none;
   }

   .layanan-nav::-webkit-scrollbar {
     display: none;
   }

   .layanan-nav .nav-link {
     color: #6b7280;
     font-size: 0.95rem;
     font-weight: 500;
     padding: 10px 0;
     margin-right: 36px;
     border: none;
     border-bottom: 2.5px solid transparent;
     background: transparent;
     border-radius: 0;
     white-space: nowrap;
     transition: color 0.2s, border-color 0.2s;
     display: flex;
     align-items: center;
     gap: 6px;
   }

   .layanan-nav .nav-link:hover {
     color: #dc3545;
   }

   .layanan-nav .nav-link.active {
     color: #dc3545;
     border-bottom-color: #dc3545;
     background: transparent;
     font-weight: 600;
   }

   .layanan-nav .nav-link .nav-arrow {
     font-size: 0.8rem;
     transition: transform 0.2s;
   }

   .layanan-nav .nav-link.active .nav-arrow {
     transform: translateX(3px);
   }

   /* ------- Banner / Sub-header ------- */
   .layanan-banner {
     background: linear-gradient(135deg, #f0f9ff 0%, #e8f5e9 100%);
     border-radius: 16px;
     padding: 36px 40px;
     margin-bottom: 36px;
     display: flex;
     align-items: center;
     justify-content: space-between;
     gap: 24px;
     min-height: 160px;
     position: relative;
     overflow: hidden;
   }

   .layanan-banner-text h2 {
     font-size: 1.5rem;
     font-weight: 700;
     color: #111827;
     line-height: 1.35;
     margin-bottom: 10px;
   }

   .layanan-banner-text p {
     font-size: 0.9rem;
     color: #4b5563;
     max-width: 480px;
     line-height: 1.65;
     margin-bottom: 0;
   }

   .layanan-banner-icon {
     flex-shrink: 0;
   }

   .layanan-banner-icon svg {
     width: 110px;
     height: 110px;
     opacity: 0.85;
   }

   /* ------- Navigation Arrows ------- */
   .layanan-nav-arrows {
     display: flex;
     justify-content: flex-end;
     gap: 10px;
     margin-bottom: 20px;
   }

   .layanan-arrow-btn {
     width: 38px;
     height: 38px;
     border-radius: 50%;
     border: 1.5px solid #d1d5db;
     background: #fff;
     color: #111827;
     display: flex;
     align-items: center;
     justify-content: center;
     cursor: pointer;
     font-size: 0.9rem;
     transition: border-color 0.2s, background 0.2s, color 0.2s;
   }

   .layanan-arrow-btn:hover {
     border-color: #dc3545;
     background: #dc3545;
     color: #fff;
   }

   /* ------- Cards ------- */
   .layanan-card {
     background: #fff;
     border-radius: 16px;
     box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
     border: none;
     overflow: hidden;
     transition: box-shadow 0.28s ease;
     height: 100%;
     display: flex;
     flex-direction: column;
   }

   .layanan-card:hover {
     box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
   }

   .layanan-card-img-wrap {
     position: relative;
     overflow: hidden;
   }

   .layanan-card-img-wrap img {
     width: 100%;
     height: 190px;
     object-fit: cover;
     display: block;
   }

   .layanan-card-badge {
     position: absolute;
     top: 12px;
     right: 12px;
     background: #dc3545;
     color: #fff;
     font-size: 0.7rem;
     font-weight: 700;
     padding: 3px 10px;
     border-radius: 20px;
     letter-spacing: 0.3px;
     text-transform: uppercase;
   }

   .layanan-card-body {
     padding: 20px 20px 24px;
     flex: 1;
     display: flex;
     flex-direction: column;
   }

   .layanan-card-title {
     font-size: 1.05rem;
     font-weight: 700;
     color: #111827;
     margin-bottom: 10px;
     display: flex;
     align-items: center;
     gap: 8px;
   }

   .layanan-card-desc {
     font-size: 0.875rem;
     color: #6b7280;
     line-height: 1.6;
     flex: 1;
     margin-bottom: 16px;
   }

   .layanan-card-price-label {
     font-size: 0.72rem;
     color: #9ca3af;
     text-transform: uppercase;
     letter-spacing: 0.5px;
     margin-bottom: 2px;
   }

   .layanan-card-price {
     font-size: 1rem;
     font-weight: 700;
     color: #dc3545;
     margin-bottom: 16px;
   }

   .layanan-card-btn {
     background: #dc3545;
     color: #fff;
     border: none;
     border-radius: 8px;
     padding: 10px 0;
     font-size: 0.875rem;
     font-weight: 600;
     width: 100%;
     cursor: pointer;
     transition: background 0.22s, transform 0.15s;
     text-align: center;
     display: block;
     text-decoration: none;
   }

   .layanan-card-btn:hover {
     background: #b91c1c;
     color: #fff;
     transform: translateY(-1px);
   }

   /* ------- Tab Content: single fade on container only ------- */
   .layanan-tab-pane {
     display: none;
   }

   .layanan-tab-pane.active-visible {
     display: block;
     animation: layananFadeIn 0.32s ease both;
   }

   @keyframes layananFadeIn {
     from {
       opacity: 0;
     }

     to {
       opacity: 1;
     }
   }

   /* ------- Responsive ------- */
   @media (max-width: 767.98px) {
     #layanan-kami-section {
       padding: 48px 0 56px;
     }

     .layanan-banner {
       flex-direction: column;
       align-items: flex-start;
       padding: 28px 24px;
     }

     .layanan-banner-icon svg {
       width: 80px;
       height: 80px;
     }

     .layanan-section-title {
       font-size: 1.6rem;
     }
   }

   @media (max-width: 575.98px) {
     .layanan-nav .nav-link {
       font-size: 0.85rem;
       margin-right: 20px;
     }
   }
 </style>

 {{-- =================== SECTION HTML =================== --}}
 <section id="layanan-kami-section" aria-label="Layanan Kami">
   <div class="container">

     {{-- ── Section Title ── --}}
     <h2 class="layanan-section-title mb-3">Layanan Kami</h2>

     {{-- ── Tab Navigation ── --}}
     <ul class="nav layanan-nav mb-4" id="layananTab" role="tablist">
       <li class="nav-item" role="presentation">
         <button class="nav-link active" id="tab-office"
           data-layanan-tab="office"
           type="button" role="tab"
           aria-controls="pane-office" aria-selected="true">
           Office &amp; Work Space
           <span class="nav-arrow">→</span>
         </button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" id="tab-business"
           data-layanan-tab="business"
           type="button" role="tab"
           aria-controls="pane-business" aria-selected="false">
           Business Services
           <span class="nav-arrow">→</span>
         </button>
       </li>
       <li class="nav-item" role="presentation">
         <button class="nav-link" id="tab-foreign"
           data-layanan-tab="foreign"
           type="button" role="tab"
           aria-controls="pane-foreign" aria-selected="false">
           Foreign Services
           <span class="nav-arrow">→</span>
         </button>
       </li>
     </ul>

     {{-- ── Tab Content Wrapper ── --}}
     <div id="layananTabContent">

       {{-- ═══════════════════════════════════
                 TAB 1 – Office & Work Space
            ═══════════════════════════════════ --}}
       <div class="layanan-tab-pane active-visible"
         id="pane-office" role="tabpanel" aria-labelledby="tab-office">

         {{-- Sub-banner --}}
         <div class="layanan-banner">
           <div class="layanan-banner-text">
             <h2>Saatnya Membuat Bisnis Anda Lebih Besar</h2>
             <p>Akan sangat disayangkan jika bisnis Anda tidak memiliki kantor dan ruang kerja
               yang mampu membuat Anda menjalankan bisnis secara efektif.</p>
           </div>
           <div class="layanan-banner-icon d-none d-md-block">
             {{-- Office SVG Illustration --}}
             <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
               <rect x="10" y="50" width="100" height="60" rx="4" stroke="#2CB67D" stroke-width="3" fill="none" />
               <rect x="25" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
               <rect x="75" y="65" width="20" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
               <line x1="60" y1="50" x2="60" y2="110" stroke="#2CB67D" stroke-width="2" />
               <rect x="35" y="90" width="50" height="20" rx="2" stroke="#2CB67D" stroke-width="2.5" fill="none" />
               <line x1="10" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
               <line x1="110" y1="50" x2="60" y2="20" stroke="#2CB67D" stroke-width="2.5" />
               <circle cx="60" cy="18" r="4" fill="#2CB67D" />
               <!-- Chair sketch -->
               <path d="M78 32 Q85 25 92 32" stroke="#2CB67D" stroke-width="2" fill="none" />
               <line x1="85" y1="32" x2="85" y2="45" stroke="#2CB67D" stroke-width="2" />
               <path d="M78 45 Q85 48 92 45" stroke="#2CB67D" stroke-width="2" fill="none" />
               <line x1="80" y1="45" x2="78" y2="55" stroke="#2CB67D" stroke-width="2" />
               <line x1="90" y1="45" x2="92" y2="55" stroke="#2CB67D" stroke-width="2" />
             </svg>
           </div>
         </div>

         {{-- Arrow Controls --}}
         <div class="layanan-nav-arrows">
           <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
           <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
         </div>

         {{-- Cards Grid --}}
         <div class="row g-4">
           {{-- Card 1: Virtual Office --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?w=500&q=80"
                   alt="Virtual Office – Gedung Perkantoran Modern" loading="lazy">
                 <span class="layanan-card-badge">Best Seller</span>
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Virtual Office</div>
                 <div class="layanan-card-desc">Hemat biaya operasional hingga 90% dengan alamat kantor prestisius tanpa sewa fisik.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 299.000/Bulan*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 2: Serviced Office --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=500&q=80"
                   alt="Serviced Office – Ruang Kantor Siap Pakai" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Serviced Office</div>
                 <div class="layanan-card-desc">Ruang kantor siap pakai yang ideal untuk tim Anda, dilengkapi fasilitas lengkap.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 4.500.000/Bulan*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 3: Meeting Room --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=500&q=80"
                   alt="Meeting Room – Ruang Rapat Profesional" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Meeting Room</div>
                 <div class="layanan-card-desc">Tempat yang cocok untuk melakukan pertemuan penting dengan klien atau mitra bisnis.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 255.000/Jam*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 4: Coworking Space --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1600508774634-4e11d34730e2?w=500&q=80"
                   alt="Coworking Space – Ruang Kerja Bersama" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Coworking Space</div>
                 <div class="layanan-card-desc">Ruang kerja bersama yang fleksibel dan produktif untuk freelancer maupun startup.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 150.000/Hari*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
         </div>{{-- /row --}}
       </div>{{-- /pane-office --}}


       {{-- ═══════════════════════════════════
                 TAB 2 – Business Services
            ═══════════════════════════════════ --}}
       <div class="layanan-tab-pane"
         id="pane-business" role="tabpanel" aria-labelledby="tab-business">

         {{-- Sub-banner --}}
         <div class="layanan-banner" style="background: linear-gradient(135deg, #fff7ed 0%, #fef3c7 100%);">
           <div class="layanan-banner-text">
             <h2>Wujudkan Bisnis Impian Anda Bersama Kami</h2>
             <p>Kami hadir untuk membantu Anda mengurus seluruh kebutuhan legalitas dan administrasi
               bisnis dengan cepat, tepat, dan terpercaya.</p>
           </div>
           <div class="layanan-banner-icon d-none d-md-block">
             <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
               <rect x="20" y="15" width="80" height="95" rx="6" stroke="#F59E0B" stroke-width="3" fill="none" />
               <line x1="35" y1="38" x2="85" y2="38" stroke="#F59E0B" stroke-width="2.5" />
               <line x1="35" y1="52" x2="85" y2="52" stroke="#F59E0B" stroke-width="2.5" />
               <line x1="35" y1="66" x2="70" y2="66" stroke="#F59E0B" stroke-width="2.5" />
               <line x1="35" y1="80" x2="65" y2="80" stroke="#F59E0B" stroke-width="2.5" />
               <circle cx="85" cy="88" r="18" fill="#fff7ed" stroke="#F59E0B" stroke-width="2.5" />
               <path d="M78 88 l4 5 l9-9" stroke="#F59E0B" stroke-width="3" stroke-linecap="round" fill="none" />
             </svg>
           </div>
         </div>

         <div class="layanan-nav-arrows">
           <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
           <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
         </div>

         <div class="row g-4">
           {{-- Card 1 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=500&q=80"
                   alt="Company Registration – Pendirian Perusahaan" loading="lazy">
                 <span class="layanan-card-badge">Populer</span>
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Company Registration</div>
                 <div class="layanan-card-desc">Proses pendirian badan usaha PT, CV, Firma dengan cepat dan sesuai regulasi yang berlaku.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 2.500.000/Paket*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 2 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=500&q=80"
                   alt="Legal Consulting – Konsultasi Hukum Bisnis" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Legal Consulting</div>
                 <div class="layanan-card-desc">Konsultasi hukum profesional untuk melindungi bisnis Anda dari risiko hukum yang merugikan.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 3 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=500&q=80"
                   alt="Tax Services – Layanan Pajak" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Tax Services</div>
                 <div class="layanan-card-desc">Pengurusan NPWP, pelaporan SPT Tahunan, dan konsultasi pajak bisnis yang akurat dan aman.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 750.000/Laporan*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 4 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=500&q=80"
                   alt="Business Licensing – Perizinan Usaha" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Business Licensing</div>
                 <div class="layanan-card-desc">Pengurusan NIB, OSS, SIUP, dan berbagai izin usaha lainnya secara cepat dan terpercaya.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 1.200.000/Izin*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
         </div>{{-- /row --}}
       </div>{{-- /pane-business --}}


       {{-- ═══════════════════════════════════
                 TAB 3 – Foreign Services
            ═══════════════════════════════════ --}}
       <div class="layanan-tab-pane"
         id="pane-foreign" role="tabpanel" aria-labelledby="tab-foreign">

         {{-- Sub-banner --}}
         <div class="layanan-banner" style="background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);">
           <div class="layanan-banner-text">
             <h2>Solusi Lengkap untuk Kebutuhan Imigrasi Anda</h2>
             <p>Kami membantu WNA dan ekspatriat mengurus seluruh dokumen keimigrasian
               di Indonesia dengan mudah, cepat, dan sesuai prosedur resmi.</p>
           </div>
           <div class="layanan-banner-icon d-none d-md-block">
             <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg">
               <circle cx="60" cy="60" r="44" stroke="#6366F1" stroke-width="3" fill="none" />
               <ellipse cx="60" cy="60" rx="24" ry="44" stroke="#6366F1" stroke-width="2.5" fill="none" />
               <line x1="16" y1="60" x2="104" y2="60" stroke="#6366F1" stroke-width="2.5" />
               <path d="M22 38 Q60 48 98 38" stroke="#6366F1" stroke-width="2" fill="none" />
               <path d="M22 82 Q60 72 98 82" stroke="#6366F1" stroke-width="2" fill="none" />
             </svg>
           </div>
         </div>

         <div class="layanan-nav-arrows">
           <button class="layanan-arrow-btn" title="Previous" aria-label="Previous">&#8592;</button>
           <button class="layanan-arrow-btn" title="Next" aria-label="Next">&#8594;</button>
         </div>

         <div class="row g-4">
           {{-- Card 1 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1551009175-15bdf9dcb580?w=500&q=80"
                   alt="Visa Application – Permohonan Visa" loading="lazy">
                 <span class="layanan-card-badge">Terlaris</span>
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Visa Application</div>
                 <div class="layanan-card-desc">Pengurusan visa kunjungan, bisnis, dan tinggal terbatas di Indonesia secara profesional.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 1.500.000/Proses*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 2 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&q=80"
                   alt="KITAS / KITAP – Izin Tinggal WNA" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">KITAS / KITAP</div>
                 <div class="layanan-card-desc">Pengurusan izin tinggal terbatas dan tetap untuk warga negara asing di Indonesia.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 3.500.000/Proses*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 3 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=500&q=80"
                   alt="Passport Assistance – Bantuan Paspor" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Passport Assistance</div>
                 <div class="layanan-card-desc">Bantuan persiapan, pengurusan, dan perpanjangan paspor untuk keperluan perjalanan internasional.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 800.000/Proses*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
           {{-- Card 4 --}}
           <div class="col-12 col-md-6 col-lg-3">
             <div class="layanan-card">
               <div class="layanan-card-img-wrap">
                 <img src="https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=500&q=80"
                   alt="Immigration Consulting – Konsultasi Imigrasi" loading="lazy">
               </div>
               <div class="layanan-card-body">
                 <div class="layanan-card-title">Immigration Consulting</div>
                 <div class="layanan-card-desc">Layanan konsultasi keimigrasian komprehensif untuk WNA yang akan tinggal atau bekerja di Indonesia.</div>
                 <div class="layanan-card-price-label">Price Start From</div>
                 <div class="layanan-card-price">Rp 500.000/Sesi*</div>
                 <a href="#" class="layanan-card-btn" role="button">Details</a>
               </div>
             </div>
           </div>
         </div>{{-- /row --}}
       </div>{{-- /pane-foreign --}}

     </div>{{-- /#layananTabContent --}}
   </div>{{-- /.container --}}
 </section>

 {{-- =================== JAVASCRIPT =================== --}}
 <script>
   (function() {
     'use strict';

     // Tab switching with fade + slide-up animation
     const tabBtns = document.querySelectorAll('[data-layanan-tab]');
     const tabPanes = document.querySelectorAll('.layanan-tab-pane');

     tabBtns.forEach(function(btn) {
       btn.addEventListener('click', function() {
         const target = btn.getAttribute('data-layanan-tab');

         // Update active button state
         tabBtns.forEach(function(b) {
           b.classList.remove('active');
           b.setAttribute('aria-selected', 'false');
         });
         btn.classList.add('active');
         btn.setAttribute('aria-selected', 'true');

         // Hide current pane
         tabPanes.forEach(function(pane) {
           if (pane.classList.contains('active-visible')) {
             pane.classList.remove('active-visible');
             pane.style.display = 'none';
           }
         });

         // Show target pane with animation
         const targetPane = document.getElementById('pane-' + target);
         if (targetPane) {
           targetPane.style.display = 'block';
           // Trigger reflow to re-run animation
           void targetPane.offsetWidth;
           targetPane.classList.add('active-visible');
         }
       });
     });
   })();
 </script>



 <!-- Feature Section Start -->
 <section class="feature-hosting-section fix section-padding">
   <div class="container">
     <div class="feature-content-warpper style-2">
       <div class="row g-4 justify-content-between align-items-center">
         <div class="col-xl-5 col-lg-6">
           <div class="feature-hosting-content">
             <div class="section-title">
               <span>Mengapa memilih kami?</span>
               <h2>Kekuatan dan Keahlian Lawgika</h2>
             </div>
             <p class="mt-3 mt-md-0">
               Di Lawgika, kami fokus menyediakan Tim Konsultan Profesional dan Solusi Bisnis Terpercaya. Dengan Penanganan Dokumen yang Aman serta Proses yang Efisien dan Transparan, kami memastikan klien kami menerima dukungan terbaik untuk kebutuhan legal dan bisnis mereka.</p>
             <p class="mt-3 mt-md-0">
               Baik Anda membutuhkan bantuan untuk pendirian perusahaan, pengurusan paspor, maupun pendirian ruang kantor profesional, para ahli kami selalu siap memandu Anda di setiap tahap perjalanan, memastikan kepatuhan hukum dan ketenangan pikiran Anda.
             </p>
             <div class="list-items">
               <ul>
                 <li>
                   <i class="far fa-check"></i>
                   Pendirian badan usaha (PT, CV, Yayasan, Firma)
                 </li>
                 <li>
                   <i class="far fa-check"></i>
                   Perizinan & dokumen hukum
                 </li>
               </ul>
               <ul>
                 <li>
                   <i class="far fa-check"></i>
                   Pembukuan & perpajakan
                 </li>
                 <li>
                   <i class="far fa-check"></i>
                   Konsultasi bisnis & Legal drafting
                 </li>
               </ul>
             </div>
             <a href="#" class="theme-btn">Explore More <i class="fas fa-long-arrow-alt-right"></i>
             </a>
           </div>
         </div>
         <div class="col-xl-6 col-lg-6">
           <div class="feature-hosting-image-2">
             <div class="icon-box-1">
               <img src="{{('buyer-file/assets/img/hosting/icon-1.png')}}" alt="img" loading="lazy">
             </div>
             <div class="icon-box-2">
               <img src="{{('buyer-file/assets/img/hosting/icon-3.png')}}" alt="img" loading="lazy">
             </div>
             <div class="thumb">
               <img src="https://images.unsplash.com/photo-1556761175-4b46a572b786?auto=format&fit=crop&w=800&q=80" alt="img" style="border-radius: 10px;" loading="lazy">
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>

 <!-- Cta contact Section Start -->
 <section class="cta-contact-section">
   <div class="container">
     <div class="cta-contact-wrapper mb-0">
       <div class="cta-contact-image">
         <img src="https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&amp;fit=crop&amp;w=800&amp;q=80" alt="img" style="margin-top: 20px;width: 36%;border-radius: 10px;" loading="lazy">
       </div>
       <div class="section-title mb-0">
         <span class="white-text">Ingin berkonsultasi dengan ahli hukum atau bisnis?</span>
         <h2 class="text-white" style="font-size: 20px !important;">Bangun bisnis tanpa ribet. Kami bantu urus pendirian perusahaan, kepatuhan hukum, paspor, hingga cari kantor yang pas buat Anda. </h2>
       </div>
       <a href="#" class="theme-btn bg-color-2 mt-xl-5">Hubungi Kami <i class="fas 
                        fa-long-arrow-alt-right"></i></a>
     </div>
   </div>
 </section>


 <!-- ===== UPCOMING EVENT SECTION ===== -->
 <style>
   /* ---- Upcoming Event – consistent with Layanan Kami ---- */
   #upcoming-event-section {
     background: #f8f9fa;
     padding: 64px 0 72px;
   }

   .ue-header {
     display: flex;
     align-items: center;
     justify-content: space-between;
     flex-wrap: wrap;
     gap: 12px;
     margin-bottom: 32px;
   }

   .ue-title {
     font-size: 1.9rem;
     font-weight: 700;
     color: #111827;
     letter-spacing: -0.3px;
     margin: 0;
   }

   .ue-all-btn {
     display: inline-flex;
     align-items: center;
     gap: 6px;
     border: 1.5px solid #dc3545;
     color: #dc3545;
     background: transparent;
     font-size: 0.875rem;
     font-weight: 600;
     padding: 8px 20px;
     border-radius: 8px;
     text-decoration: none;
     transition: background 0.2s, color 0.2s;
     white-space: nowrap;
   }

   .ue-all-btn:hover {
     background: #dc3545;
     color: #fff;
   }

   /* Card — matches .layanan-card proportions */
   .ue-card {
     background: #fff;
     border-radius: 14px;
     box-shadow: 0 1px 8px rgba(0, 0, 0, 0.07);
     border: none;
     overflow: hidden;
     height: 100%;
     display: flex;
     flex-direction: column;
     transition: transform 0.25s ease, box-shadow 0.25s ease;
   }

   .ue-card:hover {
     box-shadow: 0 6px 22px rgba(0, 0, 0, 0.11);
     transform: translateY(-4px);
   }

   /* Image wrap — fixed height like .layanan-card-img-wrap */
   .ue-img-wrap {
     position: relative;
     overflow: hidden;
     flex-shrink: 0;
   }

   .ue-img-wrap img {
     width: 100%;
     height: 200px;
     object-fit: cover;
     display: block;
   }

   /* Small badge at top-right corner */
   .ue-badge {
     position: absolute;
     top: 10px;
     right: 10px;
     font-size: 0.65rem;
     font-weight: 700;
     letter-spacing: 0.4px;
     text-transform: uppercase;
     padding: 3px 9px;
     border-radius: 20px;
     line-height: 1.4;
     z-index: 2;
   }

   .ue-badge-done {
     background: rgba(17, 24, 39, 0.72);
     color: #e5e7eb;
   }

   .ue-badge-upcoming {
     background: #dc3545;
     color: #fff;
   }

   /* Card body */
   .ue-card-body {
     padding: 16px 18px 20px;
     flex: 1;
     display: flex;
     flex-direction: column;
   }

   .ue-card-date {
     font-size: 0.75rem;
     color: #9ca3af;
     font-weight: 500;
     margin-bottom: 6px;
     display: flex;
     align-items: center;
     gap: 5px;
   }

   .ue-card-date i {
     font-size: 0.7rem;
   }

   .ue-card-title {
     font-size: 1rem;
     font-weight: 700;
     color: #111827;
     margin-bottom: 8px;
     line-height: 1.35;
   }

   .ue-card-desc {
     font-size: 0.83rem;
     color: #6b7280;
     line-height: 1.6;
     flex: 1;
     margin-bottom: 14px;
     display: -webkit-box;
     -webkit-line-clamp: 2;
     -webkit-box-orient: vertical;
     line-clamp: 2;
     overflow: hidden;
   }

   .ue-card-btn {
     display: inline-flex;
     align-items: center;
     gap: 6px;
     background: #dc3545;
     color: #fff;
     border: none;
     border-radius: 8px;
     padding: 9px 0;
     font-size: 0.875rem;
     font-weight: 600;
     width: 100%;
     justify-content: center;
     text-decoration: none;
     cursor: pointer;
     transition: background 0.2s;
   }

   .ue-card-btn:hover {
     background: #b91c1c;
     color: #fff;
   }

   .ue-card-btn i {
     font-size: 0.75rem;
     transition: transform 0.18s;
   }

   .ue-card-btn:hover i {
     transform: translateX(3px);
   }

   @media (max-width: 767.98px) {
     #upcoming-event-section {
       padding: 48px 0 56px;
     }

     .ue-title {
       font-size: 1.55rem;
     }

     .ue-header {
       flex-direction: column;
       align-items: flex-start;
     }
   }
 </style>

 <section id="upcoming-event-section" aria-label="Upcoming Event">
   <div class="container">

     <!-- Header -->
     <div class="ue-header">
       <div>
         <div class="sp-eyebrow mb-1"><i class="fas fa-calendar-alt"></i> Agenda Lawgika</div>
         <h2 class="ue-title">Upcoming Event</h2>
       </div>
       <a href="#" class="ue-all-btn">Lihat Event <i class="fas fa-long-arrow-alt-right"></i></a>
     </div>

     <!-- Card Grid -->
     <div class="row g-4">

      @forelse($events as $event)
       <div class="col-12 col-md-6 col-lg-3">
         <div class="ue-card">
           <div class="ue-img-wrap">
             @if($event->banner)
               <img src="{{ asset('storage/' . $event->banner) }}" alt="{{ $event->nama_event }}" loading="lazy">
             @else
               <img src="https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=500&q=75&auto=format&fit=crop"
                 alt="{{ $event->nama_event }}" loading="lazy">
             @endif
             @if($event->status_aktif)
               <span class="ue-badge ue-badge-upcoming">Aktif</span>
             @else
               <span class="ue-badge ue-badge-done">Selesai</span>
             @endif
           </div>
           <div class="ue-card-body">
             <div class="ue-card-date"><i class="fas fa-calendar"></i> {{ $event->tanggal_mulai->translatedFormat('d F Y') }}</div>
             <div class="ue-card-title">{{ $event->nama_event }}</div>
             <p class="ue-card-desc">{{ Str::limit($event->deskripsi, 100) }}</p>
             <a href="#" class="ue-card-btn">Lihat Detail <i class="fas fa-arrow-right"></i></a>
           </div>
         </div>
       </div>
      @empty
       <div class="col-12 text-center text-muted py-4">Belum ada event yang tersedia.</div>
      @endforelse
     </div><!-- /row -->
   </div><!-- /container -->
 </section>
 <!-- ===== END UPCOMING EVENT SECTION ===== -->


 <!-- Peraturan Start -->
 <section class="data-center-section fix section-padding section-bg">
   <div class="container">
     <div class="section-title text-center">
       <span class="style-bg">Database Peraturan Peraturan</span>
       <h2>Our Data Center </h2>
     </div>
     <div class="container">
       <div class="peraturan-container">

         <!-- Compact chip tags: 10 visible, 8 hidden behind toggle -->
         <style>
           .dc-tags-wrap {
             display: flex;
             flex-wrap: wrap;
             justify-content: center;
             gap: 7px;
             margin-bottom: 18px;
           }

           .dc-tag {
             display: inline-flex;
             align-items: center;
             background: #ffffff;
             border: 1px solid #e2e5ef;
             color: #4b5563;
             font-size: 0.73rem;
             font-weight: 500;
             padding: 4px 11px;
             border-radius: 20px;
             line-height: 1.4;
             transition: border-color 0.15s, color 0.15s;
             cursor: default;
             white-space: nowrap;
           }

           .dc-tag:hover {
             border-color: #6366f1;
             color: #4f46e5;
           }

           .dc-tag.dc-tag-hidden {
             display: none;
           }

           .dc-tag.dc-tag-hidden.dc-expanded {
             display: inline-flex;
           }

           .dc-show-more {
             display: inline-flex;
             align-items: center;
             gap: 4px;
             background: transparent;
             border: 1px dashed #9ca3af;
             color: #6b7280;
             font-size: 0.73rem;
             font-weight: 600;
             padding: 4px 11px;
             border-radius: 20px;
             cursor: pointer;
             transition: border-color 0.15s, color 0.15s;
           }

           .dc-show-more:hover {
             border-color: #6366f1;
             color: #4f46e5;
           }
         </style>

         <div class="dc-tags-wrap" id="dcTagsWrap">
            @forelse($peraturan->take(10) as $item)
              <span class="dc-tag">{{ Str::limit($item->judul_peraturan, 50) }}</span>
            @empty
              <span class="dc-tag">Belum ada data peraturan</span>
            @endforelse
            @if($peraturan->count() > 10)
              <!-- Hidden tags -->
              @foreach($peraturan->slice(10) as $item)
                <span class="dc-tag dc-tag-hidden">{{ Str::limit($item->judul_peraturan, 50) }}</span>
              @endforeach
              <button class="dc-show-more" id="dcShowMore" aria-label="Tampilkan lebih banyak">
                <i class="fas fa-plus" style="font-size:0.6rem;"></i>&nbsp;{{ $peraturan->count() - 10 }} lainnya
              </button>
            @endif
         </div>

         <script>
           (function() {
             var btn = document.getElementById('dcShowMore');
             var hidden = document.querySelectorAll('#dcTagsWrap .dc-tag-hidden');
             if (!btn || !hidden.length) return;
             var open = false;
             btn.addEventListener('click', function() {
               open = !open;
               hidden.forEach(function(t) {
                 t.classList.toggle('dc-expanded', open);
               });
               btn.innerHTML = open ?
                 '<i class="fas fa-minus" style="font-size:0.6rem;"></i>&nbsp;Lebih sedikit' :
                 '<i class="fas fa-plus"  style="font-size:0.6rem;"></i>&nbsp;8 lainnya';
             });
           })();
         </script>

         <!-- CTA Button -->
         <div class="button-row text-center mt-4">
           <button class="peraturan-btn">Lihat 95.000++ peraturan &rarr;</button>
         </div>

       </div>


     </div>
   </div>
 </section>


 <!-- Testimonial Section Start -->
 <section
   class="testimonial-section fix section-padding bg-cover"
   style="background-image: url('{{ asset('buyer-file/assets/img/hero/hero-bg-1.jpg') }}')">
   <div class="container">
     <div class="section-title-area">
       <div class="section-title">
         <span class="white-text">Testimonials</span>
         <h2 class="text-white">
           Latest Clients Feedback
         </h2>
       </div>
       <div class="array-button">
         <button class="array-prev">
           <i class="fa-solid fa-arrow-left-long"></i>
         </button>
         <button class="array-next">
           <i class="fa-solid fa-arrow-right-long"></i>
         </button>
       </div>
     </div>
     <div class="tesimonial-wrapper">
       <div class="swiper testimonial-slider">
         <div class="swiper-wrapper">
           <div class="swiper-slide">
             <div class="testimonial-card-items">
               <div class="star">
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-regular fa-star"></i>
                 <i class="fa-regular fa-star"></i>
               </div>
               <div class="icon">
                 <svg
                   xmlns="http://www.w3.org/2000/svg"
                   width="44"
                   height="33"
                   viewBox="0 0 44 33"
                   fill="none">
                   <path
                     d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z"
                     stroke="white" />
                 </svg>
               </div>
               <div
                 class="client-image bg-cover"
                 style="
                      background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80');
                    ">
                 <div class="circle-shape">
                   <img src="{{('buyer-file/assets/img/testimonial/circle.png')}}" alt="" loading="lazy" />
                 </div>
               </div>
               <p>
                 Pendampingan dari Lawgika membuat proses legalitas bisnis menjadi rapi
                 dan sesuai tenggat waktu yang ditentukan. Keahlian tim tentang regulasi
                 memberikan jaminan keamanan bagi operasional perusahaan kami secara penuh
               </p>
               <div class="client-info">
                 <h4>Cameron Williamson</h4>
                 <span>Marketing Coordinator</span>
               </div>
             </div>
           </div>
           <div class="swiper-slide">
             <div class="testimonial-card-items">
               <div class="star">
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-regular fa-star"></i>
                 <i class="fa-regular fa-star"></i>
               </div>
               <div class="icon">
                 <svg
                   xmlns="http://www.w3.org/2000/svg"
                   width="44"
                   height="33"
                   viewBox="0 0 44 33"
                   fill="none">
                   <path
                     d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z"
                     stroke="white" />
                 </svg>
               </div>
               <div
                 class="client-image bg-cover"
                 style="
                      background-image: url('https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80');
                    ">
                 <div class="circle-shape">
                   <img src="{{('buyer-file/assets/img/testimonial/circle.png')}}" alt="" loading="lazy" />
                 </div>
               </div>
               <p>
                 Pendampingan dari Lawgika membuat proses legalitas bisnis menjadi rapi
                 dan sesuai tenggat waktu yang ditentukan. Keahlian tim tentang regulasi
                 memberikan jaminan keamanan bagi operasional perusahaan kami secara penuh
               </p>
               <div class="client-info">
                 <h4>Cameron Williamson</h4>
                 <span>Marketing Coordinator</span>
               </div>
             </div>
           </div>
           <div class="swiper-slide">
             <div class="testimonial-card-items">
               <div class="star">
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-solid fa-star"></i>
                 <i class="fa-regular fa-star"></i>
                 <i class="fa-regular fa-star"></i>
               </div>
               <div class="icon">
                 <svg
                   xmlns="http://www.w3.org/2000/svg"
                   width="44"
                   height="33"
                   viewBox="0 0 44 33"
                   fill="none">
                   <path
                     d="M16 16.2929L0.5 31.7929V0.5H16V16.2929ZM43.5 16.2929L28 31.7929V0.5H43.5V16.2929Z"
                     stroke="white" />
                 </svg>
               </div>
               <div
                 class="client-image bg-cover"
                 style="
                      background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80');
                    ">
                 <div class="circle-shape">
                   <img src="{{('buyer-file/assets/img/testimonial/circle.png')}}" alt="" loading="lazy" />
                 </div>
               </div>
               <p>
                 Pendampingan dari Lawgika membuat proses legalitas bisnis menjadi rapi
                 dan sesuai tenggat waktu yang ditentukan. Keahlian tim tentang regulasi
                 memberikan jaminan keamanan bagi operasional perusahaan kami secara penuh
               </p>
               <div class="client-info">
                 <h4>Cameron Williamson</h4>
                 <span>Marketing Coordinator</span>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>
 <!-- Faq Section Start -->
 <style>
   /* Styling for the new FAQ section */
   .faq-modern-section {
     background-color: #ffffff;
   }

   .faq-info-card {
     background-color: #f5f5f5;
     border-radius: 1.5rem;
     padding: 3rem;
     height: 100%;
   }

   .faq-info-label {
     font-size: 0.75rem;
     color: #dc3545;
     text-transform: uppercase;
     letter-spacing: 1.5px;
     font-weight: 700;
     margin-bottom: 1rem;
     display: block;
   }

   .faq-info-title {
     font-size: 2rem;
     font-weight: 700;
     color: #111827;
     margin-bottom: 1rem;
     line-height: 1.2;
   }

   .faq-info-desc {
     color: #737b88;
     font-size: 0.95rem;
     line-height: 1.6;
     margin-bottom: 2.5rem;
   }

   .faq-stats-box {
     background-color: #ffffff;
     border-radius: 1rem;
     padding: 1.5rem 1rem;
     text-align: center;
     flex: 1;
     box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
   }

   .faq-stats-number {
     font-size: 1.75rem;
     font-weight: 700;
     color: #111827;
     margin-bottom: 0.25rem;
   }

   .faq-stats-label {
     font-size: 0.7rem;
     color: #6b7280;
     text-transform: uppercase;
     letter-spacing: 0.5px;
     font-weight: 600;
   }

   .faq-modern-accordion {
     --bs-accordion-bg: transparent;
     --bs-accordion-border-width: 0;
     --bs-accordion-inner-border-radius: 0;
     --bs-accordion-btn-focus-box-shadow: none;
   }

   .faq-modern-accordion .accordion-item {
     background-color: #f7f7f7;
     border: none;
     border-radius: 1.25rem !important;
     margin-bottom: 1rem;
     overflow: hidden;
     transition: background-color 0.3s;
   }

   .faq-modern-accordion .accordion-button {
     background-color: transparent;
     font-weight: 600;
     color: #111827;
     padding: 1.25rem 1.75rem;
     box-shadow: none !important;
     border-radius: 1.25rem !important;
   }

   .faq-modern-accordion .accordion-button:not(.collapsed) {
     color: #000;
     background-color: transparent;
     box-shadow: none;
   }

   .faq-modern-accordion .accordion-button::after {
     background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%236b7280'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
     transition: transform 0.3s ease;
   }

   .faq-modern-accordion .accordion-body {
     padding: 0 1.75rem 1.25rem;
     color: #6b7280;
     font-size: 0.95rem;
     line-height: 1.6;
   }

   @media (max-width: 991.98px) {
     .faq-info-card {
       margin-bottom: 2rem;
       padding: 2rem;
     }
   }
 </style>

 <section class="faq-modern-section section-padding">
   <div class="container">
     <div class="row">
       <!-- Kolom Kiri -->
       <div class="col-lg-4">
         <div class="faq-info-card d-flex flex-column">
           <div>
             <span class="faq-info-label">RINGKAS &amp; JELAS</span>
             <h2 class="faq-info-title">Jawaban penting sebelum Anda memulai</h2>
             <p class="faq-info-desc">Kami rangkum pertanyaan yang paling sering muncul agar Anda bisa menemukan informasi penting tanpa harus berpindah halaman.</p>
           </div>
           <div class="d-flex gap-3 mt-auto">
             <div class="faq-stats-box">
               <div class="faq-stats-number">15</div>
               <div class="faq-stats-label">TOPIK AKTIF</div>
             </div>
             <div class="faq-stats-box">
               <div class="faq-stats-number">24/7</div>
               <div class="faq-stats-label">AKSES INFORMASI</div>
             </div>
           </div>
         </div>
       </div>

       <!-- Kolom Kanan (FAQ Accordion) -->
       <div class="col-lg-8">
         <div class="accordion faq-modern-accordion" id="faqAccordionModern">

           <!-- Item 1 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading1">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                 Bagaimana sistem pembayaran Serviced Office? ( Serviced Office )
               </button>
             </h5>
             <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faqHeading1" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Untuk Serviced Office, pembayaran dapat dilakukan secara bulanan, kuartalan, atau tahunan dengan berbagai metode pembayaran seperti transfer bank maupun kartu kredit.
               </div>
             </div>
           </div>

           <!-- Item 2 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading2">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                 Apa Virtual Office sudah bisa PKP? ( Virtual Office )
               </button>
             </h5>
             <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faqHeading2" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Ya, sebagian besar lokasi Virtual Office kami dapat digunakan untuk keperluan pendaftaran PKP (Pengusaha Kena Pajak) sesuai dengan ketentuan yang berlaku.
               </div>
             </div>
           </div>

           <!-- Item 3 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading3">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                 Apakah semua fasilitas bisa digunakan di semua cabang?
               </button>
             </h5>
             <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faqHeading3" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Fasilitas dasar seperti coworking dan lounge dapat diakses di beberapa cabang tertentu jika Anda tergabung dalam paket member berskala nasional.
               </div>
             </div>
           </div>

           <!-- Item 4 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading4">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                 Apakah Virtual Office dapat disewa oleh Firma Hukum yang belum memiliki AKTA Perusahaan? ( Virtual Office )
               </button>
             </h5>
             <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faqHeading4" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Tentu. Pendaftaran dapat dilakukan atas nama perorangan atau pendiri terlebih dahulu, dan dapat disesuaikan kembali setelah legalitas perusahaan resmi terbit.
               </div>
             </div>
           </div>

           <!-- Item 5 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading5">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                 Apakah Virtual Office dapat disewa dengan harga bulanan? ( Virtual Office )
               </button>
             </h5>
             <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faqHeading5" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Pada umumnya penyewaan Virtual Office adalah berlangganan secara tahunan. Namun, kami menyediakan beberapa paket fleksibel yang dapat dibayar bertahap.
               </div>
             </div>
           </div>

           <!-- Item 6 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading6">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse6" aria-expanded="false" aria-controls="faqCollapse6">
                 Apakah ada perbedaan harga untuk setiap cabang? ( Virtual Office )
               </button>
             </h5>
             <div id="faqCollapse6" class="accordion-collapse collapse" aria-labelledby="faqHeading6" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Ya, harga dapat bervariasi bergantung pada lokasi cabang (zona premium atau standar) dan kelengkapan fasilitas tambahan pada area tersebut.
               </div>
             </div>
           </div>

           <!-- Item 7 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading7">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse7" aria-expanded="false" aria-controls="faqCollapse7">
                 Apakah terdapat foto dokumentasi Serviced Office yang dapat dilihat? ( Serviced Office )
               </button>
             </h5>
             <div id="faqCollapse7" class="accordion-collapse collapse" aria-labelledby="faqHeading7" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Tentu saja, dokumentasi foto untuk setiap ruangan Serviced Office tersedia di galeri website kami atau Anda dapat menjadwalkan tur lokasi secara langsung.
               </div>
             </div>
           </div>

           <!-- Item 8 -->
           <div class="accordion-item">
             <h5 class="accordion-header" id="faqHeading8">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse8" aria-expanded="false" aria-controls="faqCollapse8">
                 Apakah dapat menyewa Virtual Office dengan tidak/belum memiliki legalitas perusahaan? ( Virtual Office )
               </button>
             </h5>
             <div id="faqCollapse8" class="accordion-collapse collapse" aria-labelledby="faqHeading8" data-bs-parent="#faqAccordionModern">
               <div class="accordion-body">
                 Bisa. Anda tetap dapat menggunakan alamat Virtual Office kami selama masa pengurusan legalitas pendirian perusahaan Anda sedang berjalan.
               </div>
             </div>
           </div>

         </div>
       </div>
     </div>
   </div>
 </section>
 <!-- News Section Start -->
 <section class="news-section fix section-padding section-bg">
   <div class="container">
     <div class="section-title-area">
       <div class="section-title">
         <span>Our News</span>
         <h2>
           Explore Latest News
         </h2>
       </div>
       <a
         href="#"
         class="theme-btn">See All More <i class="fas fa-long-arrow-alt-right"></i></a>
     </div>
     <div class="row">
       <div
         class="col-xl-4 col-lg-6 col-md-6">
         <div class="news-card-items">
           <div class="news-image">
             <img src="{{('buyer-file/assets/img/news/01.jpg')}}" alt="news-img" loading="lazy" />
           </div>
           <div class="news-content">
             <div class="post-list">
               <span>Berita Hukum</span>
               <p>MARCH 24, 2024</p>
             </div>
             <h3>
               <a href="#">Pentingnya legal drafting dalam perjanjian bisnis</a>
             </h3>
             <div class="author-items">
               <div class="author-image">
                 <div
                   class="author-img bg-cover"
                   style="
                        background-image: url(&quot;assets/img/news/post-1.png&quot;);
                      "></div>
                 <div class="content">
                   <h6>Admin</h6>
                   <p>Co, Founder</p>
                 </div>
               </div>
               <a href="#" class="link-btn">
                 <i class="fa-solid fa-arrow-right"></i>
               </a>
             </div>
           </div>
         </div>
       </div>
       <div
         class="col-xl-4 col-lg-6 col-md-6">
         <div class="news-card-items">
           <div class="news-image">
             <img src="{{('buyer-file/assets/img/news/02.jpg')}}" alt="news-img" loading="lazy" />
           </div>
           <div class="news-content">
             <div class="post-list">
               <span>Bisnis</span>
               <p>MARCH 22, 2024</p>
             </div>
             <h3>
               <a href="#">Pentingnya Legalitas untuk Startup & UMKM</a>
             </h3>
             <div class="author-items">
               <div class="author-image">
                 <div
                   class="author-img bg-cover"
                   style="
                        background-image: url(&quot;assets/img/news/post-2.png&quot;);
                      "></div>
                 <div class="content">
                   <h6>Admin</h6>
                   <p>Co, Founder</p>
                 </div>
               </div>
               <a href="#" class="link-btn">
                 <i class="fa-solid fa-arrow-right"></i>
               </a>
             </div>
           </div>
         </div>
       </div>
       <div
         class="col-xl-4 col-lg-6 col-md-6">
         <div class="news-card-items">
           <div class="news-image">
             <img src="{{('buyer-file/assets/img/news/03.jpg')}}" alt="news-img" loading="lazy" />
           </div>
           <div class="news-content">
             <div class="post-list">
               <span>Update Perizinan</span>
               <p>MARCH 14, 2024</p>
             </div>
             <h3>
               <a href="#">Perubahan Aturan Pajak Terbaru Tahun Ini</a>
             </h3>
             <div class="author-items">
               <div class="author-image">
                 <div
                   class="author-img bg-cover"
                   style="
                        background-image: url(&quot;assets/img/news/post-3.png&quot;);
                      "></div>
                 <div class="content">
                   <h6>Admin</h6>
                   <p>Co, Founder</p>
                 </div>
               </div>
               <a href="#" class="link-btn">
                 <i class="fa-solid fa-arrow-right"></i>
               </a>
             </div>
           </div>
         </div>
       </div>
     </div>
   </div>
 </section>


 @endsection