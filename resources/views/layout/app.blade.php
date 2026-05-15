<!doctype html>
<html lang="en">
<!--<< Header Area >>-->

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- ======== SEO Optimization ======== -->
    <title>@yield('title', 'Lawgika - Legal, Tax, Accounting & Virtual Office Terpercaya')</title>
    <meta name="description" content="@yield('meta_description', 'Lawgika.co.id adalah konsultan profesional yang melayani pendirian PT, CV, Yayasan, Virtual Office, Jasa Pembukuan, dan Pelaporan Pajak di Indonesia. Proses cepat, legal, dan aman.')" />
    <meta name="keywords" content="@yield('meta_keywords', 'Jasa Pendirian PT, Pendirian CV, Pendirian Yayasan, Virtual Office Jakarta, Jasa Pembukuan, Konsultan Pajak, Pelaporan SPT Tahunan, Pengurusan PKP, Sewa Meeting Room, Sewa Podcast Room, Legalitas Usaha, Lawgika')" />
    <meta name="google-site-verification" content="4SifO5KpxUTqWPbYNR5_n02fvRYEikrp8D7nTz2X5D0" />
    <meta name="author" content="Lawgika" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:title" content="@yield('title', 'Lawgika - Legal, Tax, Accounting & Virtual Office Terpercaya')" />
    <meta property="og:description" content="@yield('meta_description', 'Lawgika.co.id adalah konsultan profesional yang melayani pendirian PT, CV, Yayasan, Virtual Office, Jasa Pembukuan, dan Pelaporan Pajak di Indonesia. Proses cepat, legal, dan aman.')" />
    <meta property="og:image" content="{{ asset('buyer-file/assets/img/logo-removebg.png') }}" />

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="{{ url()->current() }}" />
    <meta property="twitter:title" content="@yield('title', 'Lawgika - Legal, Tax, Accounting & Virtual Office Terpercaya')" />
    <meta property="twitter:description" content="@yield('meta_description', 'Lawgika.co.id adalah konsultan profesional yang melayani pendirian PT, CV, Yayasan, Virtual Office, Jasa Pembukuan, dan Pelaporan Pajak di Indonesia. Proses cepat, legal, dan aman.')" />
    <meta property="twitter:image" content="{{ asset('buyer-file/assets/img/logo-removebg.png') }}" />

    <!--<< Favcion >>-->
    <!-- <link rel="shortcut icon" href="{{ asset('buyer-file/assets/img/favicon.svg')}}" /> -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('buyer-file/assets/img/logo-removebg.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('buyer-file/assets/img/logo-removebg.png')}}">
    <!--<< Bootstrap min.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/bootstrap.min.css')}}" />
    <!--<< All Min Css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/all.min.css')}}" />
    <!--<< Animate.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/animate.css')}}" />
    <!--<< Magnific Popup.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/magnific-popup.css')}}" />
    <!--<< MeanMenu.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/meanmenu.css')}}" />
    <!--<< Swiper Bundle.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/swiper-bundle.min.css')}}" />
    <!--<< Nice Select.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/nice-select.css')}}" />
    <!--<< Color.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/color.css')}}" />
    <!--<< Main.css >>-->
    <link rel="stylesheet" href="{{ asset('buyer-file/assets/css/main.css')}}" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body>
    
    <!-- Preloader Start -->
    @if (request()->is('/'))
    <!-- <div id="preloader" class="preloader">
        <div class="animation-preloader">
            <div class="spinner"></div>
            <div class="txt-loading">
                <span data-text-preloader="L" class="letters-loading"> L </span>
                <span data-text-preloader="A" class="letters-loading"> A </span>
                <span data-text-preloader="W" class="letters-loading"> W </span>
                <span data-text-preloader="G" class="letters-loading"> G </span>
                <span data-text-preloader="I" class="letters-loading"> I </span>
                <span data-text-preloader="K" class="letters-loading"> K </span>
                <span data-text-preloader="A" class="letters-loading"> A </span>
            </div>
            <p class="text-center">Loading</p>
        </div>
        <div class="loader">
            <div class="row">
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-left">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
                <div class="col-3 loader-section section-right">
                    <div class="bg"></div>
                </div>
            </div>
        </div>
    </div> -->
    @endif

    <!--<< Mouse Cursor Start >>-->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    @include('layout.header')

    @yield('content')

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/6281112088600" target="_blank" class="floating-whatsapp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <style>
        .floating-whatsapp {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background-color: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 35px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .floating-whatsapp:hover {
            background-color: #128C7E;
            color: white;
            transform: scale(1.1);
        }
        
        .floating-whatsapp i {
            margin-top: 2px;
        }
    </style>

    @include('layout.footer')