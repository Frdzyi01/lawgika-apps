  <!--<< Footer Section Start >>-->
  <footer
    class="footer-section fix bg-cover"
    style="background-image: url('{{ asset('buyer-file/assets/img/hero/hero-bg-1.webp') }}')">
    
    <style>
      /* ==========================================================================
         Lawgika Premium Modern Footer Redesign Styles
         ========================================================================== */
      
      .lw-footer-wrapper {
        padding-top: 100px;
        padding-bottom: 50px;
        position: relative;
        z-index: 2;
      }

      /* 4-Column Modern Grid for Desktop */
      .lw-footer-main {
        display: grid;
        grid-template-columns: 1.3fr 0.8fr 0.9fr 1.1fr;
        gap: 60px;
        align-items: start;
      }

      .lw-footer-col {
        display: flex;
        flex-direction: column;
      }

      /* Column 1: Brand & Logo */
      .lw-footer-logo {
        margin-bottom: 24px;
        display: inline-block;
        transition: transform 0.3s ease;
      }

      .lw-footer-logo:hover {
        transform: translateY(-2px);
      }

      .lw-footer-logo img {
        max-width: 250px;
        height: auto;
        display: block;
      }

      .lw-footer-desc {
        font-size: 16px;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 32px;
        font-weight: 400;
      }

      /* Premium Startup CTA Button */
      .lw-footer-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: flex-start;
        padding: 14px 30px;
        font-size: 14.5px;
        font-weight: 600;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      }

      .lw-footer-cta i {
        margin-left: 10px;
        font-size: 13px;
        transition: transform 0.3s ease;
      }

      .lw-footer-cta:hover {
        color: #4e0516; /* Brand Deep Red */
        background: #ffffff;
        border-color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      }

      .lw-footer-cta:hover i {
        transform: translateX(6px);
      }

      /* Elegant Column Headings */
      .lw-footer-title {
        font-size: 16px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #ffffff !important;
        margin-bottom: 30px;
        position: relative;
        display: inline-block;
        line-height: 1.2;
      }

      .lw-footer-title::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -8px;
        width: 30px;
        height: 2px;
        background-color: #ffa31a; /* Existing Gold Accent */
        border-radius: 2px;
      }

      /* Clean Navigation Links */
      .lw-footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 16px;
      }

      .lw-footer-links li a {
        display: inline-flex;
        align-items: center;
        font-size: 17px;
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none;
        transition: all 0.25s ease;
        font-weight: 450;
      }

      .lw-footer-links li a i {
        font-size: 10px;
        margin-right: 12px;
        opacity: 0.5;
        transition: all 0.25s ease;
      }

      .lw-footer-links li a:hover {
        color: #ffa31a !important;
        transform: translateX(6px);
      }

      .lw-footer-links li a:hover i {
        color: #ffa31a !important;
        opacity: 1;
      }

      /* Modern Contact Info Layout */
      .lw-footer-contact-info {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
      }

      .lw-footer-contact-info li {
        display: flex;
        align-items: flex-start;
        gap: 16px;
      }

      .lw-footer-contact-info li .lw-contact-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 50%;
        color: #ffa31a;
        font-size: 14px;
        flex-shrink: 0;
        margin-top: 2px;
        transition: all 0.3s ease;
      }

      .lw-footer-contact-info li:hover .lw-contact-icon {
        background: #ffa31a;
        color: #4e0516;
      }

      .lw-footer-contact-info li .lw-contact-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
      }

      .lw-footer-contact-info li .lw-contact-label {
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: rgba(255, 255, 255, 0.5);
      }

      .lw-footer-contact-info li .lw-contact-text span:not(.lw-contact-label) {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.5;
      }

      .lw-footer-contact-info li .lw-contact-text a {
        font-size: 16.5px;
        color: rgba(255, 255, 255, 0.85) !important;
        text-decoration: none;
        transition: color 0.2s ease;
        font-weight: 500;
      }

      .lw-footer-contact-info li .lw-contact-text a:hover {
        color: #ffa31a !important;
      }

      /* Premium Social Media Links */
      .lw-footer-social {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }

      .lw-footer-social a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.07);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ffffff !important;
        font-size: 15px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        text-decoration: none;
      }

      .lw-footer-social a:hover {
        background: #ffffff;
        color: #4e0516 !important;
        border-color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      }

      /* Modern Bottom Divider & Footer Bottom */
      .lw-footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 30px 0;
        position: relative;
        z-index: 2;
      }

      .lw-footer-bottom-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
      }

      .lw-footer-copyright {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
      }

      .lw-footer-copyright a {
        color: rgba(255, 255, 255, 0.85) !important;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
      }

      .lw-footer-copyright a:hover {
        color: #ffa31a !important;
      }

      .lw-footer-bottom-links {
        display: flex;
        align-items: center;
        gap: 16px;
        padding-right: 90px; /* Shifts links slightly left to avoid floating WhatsApp button */
      }

      .lw-footer-bottom-links a {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6) !important;
        text-decoration: none;
        transition: color 0.2s;
      }

      .lw-footer-bottom-links a:hover {
        color: #ffa31a !important;
      }

      .lw-footer-bottom-dot {
        color: rgba(255, 255, 255, 0.25);
        font-size: 10px;
        user-select: none;
      }

      /* Custom Scroll-up Override to Align with Modern Theme */
      .footer-bottom .scroll-icon {
        background-color: #ffa31a !important;
        border: 4px solid #ffffff !important;
        color: #4e0516 !important;
        width: 54px !important;
        height: 54px !important;
        line-height: 46px !important;
        position: fixed !important;
        bottom: 110px !important;
        right: 30px !important;
        left: auto !important;
        top: auto !important;
        transform: none !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        border-radius: 50% !important;
        z-index: 999 !important;
      }

      .footer-bottom .scroll-icon:hover {
        background-color: #ffffff !important;
        color: #8c0c1e !important;
        border-color: #ffa31a !important;
        transform: translateY(-4px) !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3) !important;
      }

      /* ==========================================================================
         Responsive Breakpoints
         ========================================================================== */

      @media (max-width: 1199px) {
        .lw-footer-main {
          grid-template-columns: 1.2fr 0.8fr 0.9fr 1.1fr;
          gap: 40px;
        }
      }

      @media (max-width: 991px) {
        .lw-footer-wrapper {
          padding-top: 80px;
          padding-bottom: 40px;
        }
        .lw-footer-main {
          grid-template-columns: repeat(2, 1fr);
          gap: 50px 30px;
        }
      }

      @media (max-width: 575px) {
        .lw-footer-wrapper {
          padding-top: 60px;
          padding-bottom: 40px;
        }
        .lw-footer-main {
          grid-template-columns: 1fr;
          gap: 45px;
          text-align: left;
        }
        .lw-footer-col {
          align-items: flex-start;
        }
        .lw-footer-logo {
          margin-bottom: 18px;
        }
        .lw-footer-desc {
          margin-bottom: 24px;
          text-align: left;
        }
        .lw-footer-cta {
          align-self: flex-start;
        }
        .lw-footer-title {
          margin-bottom: 24px;
        }
        .lw-footer-title::after {
          left: 0;
          transform: none;
        }
        .lw-footer-links {
          gap: 14px;
        }
        .lw-footer-contact-info {
          gap: 20px;
          width: 100%;
          max-width: none;
        }
        .lw-footer-contact-info li {
          flex-direction: row;
          align-items: flex-start;
          text-align: left;
          gap: 16px;
        }
        .lw-footer-contact-info li .lw-contact-icon {
          margin-top: 2px;
        }
        .lw-footer-social {
          width: 100%;
          justify-content: flex-start;
          margin-top: 24px;
        }
        .lw-footer-bottom-wrapper {
          flex-direction: column;
          align-items: flex-start;
          text-align: left;
          gap: 20px;
        }
        .lw-footer-bottom-links {
          flex-direction: row;
          flex-wrap: wrap;
          justify-content: flex-start;
          gap: 10px 20px;
          width: 100%;
          padding-right: 80px; /* Shift links left on mobile to avoid overlapping with floating WhatsApp */
        }
        .lw-footer-bottom-dot {
          display: none;
        }
        .footer-bottom .scroll-icon {
          bottom: 110px !important;
          right: 25px !important;
        }
      }
    </style>

    <div class="lw-footer-wrapper">
      <div class="container">
        <div class="lw-footer-main">
          
          <!-- 1️⃣ LEFT BRAND SECTION -->
          <div class="lw-footer-col lw-footer-brand">
            <a href="{{ url('/') }}" class="lw-footer-logo">
              <img src="{{ asset('buyer-file/assets/img/logo-removebg.webp') }}" alt="Lawgika Logo" width="250" height="63" loading="lazy" />
            </a>
            <p class="lw-footer-desc" data-i18n="footer.brand_desc">
              Lawgika Bisnis Indonesia siap mendampingi perjalanan bisnis dan legalitas perusahaan Anda dengan layanan profesional dan terpercaya.
            </p>
            <a href="https://wa.me/6281112088600" target="_blank" class="lw-footer-cta">
              <span data-i18n="footer.cta">Konsultasi Sekarang</span>
              <i class="fa-solid fa-arrow-right-long"></i>
            </a>
          </div>

          <!-- 2️⃣ COMPANY MENU -->
          <div class="lw-footer-col">
            <h4 class="lw-footer-title" data-i18n="footer.company_col">Perusahaan</h4>
            <ul class="lw-footer-links">
              <li><a href="{{ url('/tentang-kami') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.about">Tentang Kami</span></a></li>
              <!-- <li><a href="https://wa.me/6281112088600" target="_blank"><i class="fas fa-angle-double-right"></i>Hubungi Kami</a></li> -->
              <li><a href="{{ url('/kerjasama-bisnis') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.kerjasama">Kerjasama Bisnis</span></a></li>
              <li><a href="{{ url('/database-peraturan') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.database">Database KBLI</span></a></li>
              <li><a href="{{ url('/karir') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.karir">Karir</span></a></li>
              <li><a href="{{ url('/upcoming-event') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.event">Event Lawgika</span></a></li>
              <li><a href="{{ url('/perizinan-dan-hukum') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.perizinan">Layanan Perizinan & Hukum</span></a></li>
            </ul>
          </div>

          <!-- 3️⃣ SERVICES MENU -->
          <div class="lw-footer-col">
            <h4 class="lw-footer-title" data-i18n="footer.services_col">Layanan Utama</h4>
            <ul class="lw-footer-links">
              <li><a href="{{ url('/pendirian-pt') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.pt_perseroan">Pendirian PT Perseroan</span></a></li>
              <li><a href="{{ url('/pendirian-pt-perorangan') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.pt_perorangan">Pendirian PT Perorangan</span></a></li>
              <li><a href="{{ url('/pendirian-pt-pma') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.pt_pma">Pendirian PT PMA</span></a></li>
              <li><a href="{{ url('/pendirian-cv') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.cv">Pendirian CV</span></a></li>
              <li><a href="{{ url('/pendirian-firma') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.firma">Pendirian Firma</span></a></li>
              <li><a href="{{ url('/pendirian-yayasan') }}"><i class="fas fa-angle-double-right"></i><span data-i18n="footer.yayasan">Pendirian Yayasan</span></a></li>
            </ul>
          </div>
           

          <!-- 4️⃣ CONTACT SECTION -->
          <div class="lw-footer-col lw-footer-contact">
            <h4 class="lw-footer-title" data-i18n="footer.contact_col">Hubungi Kami</h4>
            <ul class="lw-footer-contact-info">
              <li>
                <div class="lw-contact-icon">
                  <svg width="16" height="16" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                </div>
                <div class="lw-contact-text">
                <span class="lw-contact-label" data-i18n="footer.contact.wa_label">WhatsApp</span>
                  <a href="https://wa.me/6281112088600" target="_blank">+62 811-1208-8600</a>
                </div>
              </li>
              <li>
                <div class="lw-contact-icon">
                  <i class="fas fa-phone"></i>
                </div>
                <div class="lw-contact-text">
                  <span class="lw-contact-label" data-i18n="footer.contact.phone_label">Telepon Kantor</span>
                  <a href="tel:02139706065">021-3970-6065</a>
                </div>
              </li>
              <li>
                <div class="lw-contact-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div class="lw-contact-text">
                  <span class="lw-contact-label" data-i18n="footer.contact.email_label">Email</span>
                  <a href="mailto:informasi@lawgika.co.id">informasi@lawgika.co.id</a>
                </div>
              </li>
              <li>
                <div class="lw-contact-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="lw-contact-text">
                  <span class="lw-contact-label" data-i18n="footer.contact.address_label">Alamat</span>
                  <span data-i18n="footer.contact.address_val">World Capital Tower Lt. 38 Unit 06-07, Mega Kuningan, Jakarta Selatan 12950</span>
                </div>
              </li>
            </ul>

            <div class="lw-footer-social">
              <a href="https://www.instagram.com/lawgika.co.id?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" title="Instagram" aria-label="Instagram"><svg width="15" height="15" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a>
              <a href="https://www.tiktok.com/@lawgika.co.id?is_from_webapp=1&sender_device=pc" target="_blank" title="TikTok" aria-label="TikTok"><svg width="14" height="14" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M448 209.9a210.1 210.1 0 0 1-122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg></a>
              <a href="https://web.facebook.com/lawgika.co.id/" title="Facebook" aria-label="Facebook"><svg width="14" height="14" viewBox="0 0 320 512" fill="currentColor" aria-hidden="true"><path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg></a>
              <a href="https://www.linkedin.com/company/97997770/admin/dashboard/" target="_blank" title="LinkedIn" aria-label="LinkedIn"><svg width="14" height="14" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true"><path d="M100.3 448H7.4V148.9h92.9zM53.8 108.1C24.1 108.1 0 83.5 0 53.8a53.8 53.8 0 0 1 107.6 0c0 29.7-24.1 54.3-53.8 54.3zM447.9 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448z"/></svg></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="lw-footer-bottom">
      <div class="container">
        <div class="lw-footer-bottom-wrapper">
          <p class="lw-footer-copyright">
            <span data-i18n="footer.copyright">© Hak Cipta 2026 oleh </span><a href="{{ url('/') }}">Lawgika Bisnis Indonesia</a>.
          </p>
          <div class="lw-footer-bottom-links">
            <a href="#" data-i18n="footer.tos">Ketentuan Layanan</a>
            <span class="lw-footer-bottom-dot">•</span>
            <a href="#" data-i18n="footer.privacy">Kebijakan Privasi</a>
            <span class="lw-footer-bottom-dot">•</span>
            <a href="https://wa.me/6281112088600" target="_blank" data-i18n="footer.contact_us">Hubungi Kami</a>
          </div>
        </div>
      </div>
    </div>

    <a href="#" id="scrollUp" class="scroll-icon" aria-label="Kembali ke atas">
      <i class="fas fa-arrow-up" aria-hidden="true"></i>
    </a>
  </footer>
  <!--<< All JS Plugins >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery-3.7.1.min.js') }}" defer></script>
  <!--<< Viewport Js >>-->
  <script src="{{ asset('buyer-file/assets/js/viewport.jquery.js') }}" defer></script>
  <!--<< Bootstrap Js >>-->
  <script src="{{ asset('buyer-file/assets/js/bootstrap.bundle.min.js') }}" defer></script>
  <!--<< Nice Select Js >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery.nice-select.min.js') }}" defer></script>
  <!--<< Waypoints Js >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery.waypoints.js') }}" defer></script>
  <!--<< Counterup Js >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery.counterup.min.js') }}" defer></script>
  <!--<< Swiper Slider Js >>-->
  <script src="{{ asset('buyer-file/assets/js/swiper-bundle.min.js') }}" defer></script>
  <!--<< MeanMenu Js >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery.meanmenu.min.js') }}" defer></script>
  <!--<< Magnific Popup Js >>-->
  <script src="{{ asset('buyer-file/assets/js/jquery.magnific-popup.min.js') }}" defer></script>
  <!--<< Wow Animation Js >>-->
  <script src="{{ asset('buyer-file/assets/js/wow.min.js') }}" defer></script>
  <!--<< Main.js >>-->
  <script src="{{ asset('buyer-file/assets/js/main.js') }}?v={{ filemtime(public_path('buyer-file/assets/js/main.js')) }}" defer></script>

  <!-- ======== i18n Engine: cache-busted external JSON (no 510KB inline bloat) ======== -->
  <script>
    window.__lwLangVer = {
      id: '{{ @filemtime(public_path("lang/id.json")) ?: 1 }}',
      en: '{{ @filemtime(public_path("lang/en.json")) ?: 1 }}',
      zh: '{{ @filemtime(public_path("lang/zh.json")) ?: 1 }}'
    };
  </script>
  <script src="{{ asset('buyer-file/assets/js/i18n.js') }}?v={{ filemtime(public_path('buyer-file/assets/js/i18n.js')) }}" defer></script>
  <!-- ======== End i18n ======== -->

  </body>

  </html>