<!doctype html>
<html lang="en">
<!--<< Header Area >>-->

<head>
    <!-- ========== Meta Tags ========== -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="gramentheme" />
    <meta name="description" content="Lawgika - Legal & Business Consulting Services " />
    <!-- ======== Page title ============ -->
    <title>Lawgika - Legal & Business Consulting Services</title>
    <!--<< Favcion >>-->
    <link rel="shortcut icon" href="{{ asset('buyer-file/assets/img/favicon.svg')}}" />
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
</head>

<body>
    <!-- Preloader Start -->
    <div id="preloader" class="preloader">
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
    </div>

    <!--<< Mouse Cursor Start >>-->
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>

    @include('layout.header')

    @yield('content')

    @include('layout.footer')