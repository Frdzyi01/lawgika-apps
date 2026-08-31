<!doctype html>
<html lang="en" class="light-theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- loader-->
  <!--plugins-->
  <link href="{{ asset('template-admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="{{ asset('template-admin/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/css/style.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/css/icons.css') }}" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet" />
  <!--Theme Styles-->
  <link href="{{ asset('template-admin/assets/css/dark-theme.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/css/semi-dark.css') }}" rel="stylesheet" />
  <link href="{{ asset('template-admin/assets/css/header-colors.css') }}" rel="stylesheet" />
  <title>@yield('title', __('nav.dashboard') . ' - Lawgika')</title>
  <style>
    /* Language Switcher Styles for Customer Portal */
    .lw-lang-switcher {
      position: relative;
      display: inline-flex;
      align-items: center;
    }
    .lw-lang-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #ffffff;
      border: 1px solid rgba(0, 0, 0, 0.15);
      border-radius: 50px;
      padding: 6px 12px;
      color: #333333;
      font-size: 0.82rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.22s ease;
      white-space: nowrap;
      line-height: 1;
    }
    .lw-lang-btn:hover {
      background: rgba(0,0,0,0.05);
      border-color: #4e0616;
      color: #4e0616;
    }
    .lw-lang-btn .lw-lang-arrow {
      font-size: 0.65rem;
      transition: transform 0.22s ease;
      opacity: 0.8;
    }
    .lw-lang-dropdown {
      display: none;
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      min-width: 175px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.14), 0 2px 8px rgba(0,0,0,0.06);
      border: 1px solid #f0f0f0;
      z-index: 99999;
      overflow: hidden;
      opacity: 0;
      transform: translateY(8px);
      transition: opacity 0.18s ease, transform 0.18s ease;
      pointer-events: none;
    }
    .lw-lang-dropdown.lw-lang-open {
      display: block;
      opacity: 1;
      transform: translateY(0);
      pointer-events: auto;
    }
    .lw-lang-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 11px 16px;
      font-size: 0.875rem;
      font-weight: 500;
      color: #1e293b;
      cursor: pointer;
      text-decoration: none;
      transition: background 0.15s ease, color 0.15s ease;
      border-bottom: 1px solid #f5f5f5;
    }
    .lw-lang-item:last-child { border-bottom: none; }
    .lw-lang-item:hover { background: #fff5f6; color: #4e0616; }
    .lw-lang-item.lw-lang-active {
      background: #fff5f6;
      color: #4e0616;
      font-weight: 700;
    }
    .lw-lang-item.lw-lang-active::after {
      content: '✓';
      margin-left: auto;
      font-size: 0.75rem;
      color: #4e0616;
    }
    .lw-lang-flag { font-size: 1.1rem; line-height: 1; }
  </style>
</head>

<body>
  <!--start wrapper-->
  <div class="wrapper">
    <!--start sidebar -->
    <aside class="sidebar-wrapper" data-simplebar="true">
      <div class="sidebar-header">
        <div>
          <img src="{{ asset('template-admin/assets/images/logo-icon-2.webp') }}" class="logo-icon" alt="logo icon" />
        </div>
        <div>
          <h4 class="logo-text">Lawgika</h4>
        </div>
      </div>
      <!--navigation-->
      <ul class="metismenu" id="menu">
        <li>
          <a href="{{ route('customer.dashboard') }}">
            <div class="parent-icon"><ion-icon name="home-outline"></ion-icon></div>
            <div class="menu-title">{{ __('nav.dashboard') }}</div>
          </a>
        </li>
        <li class="menu-label">{{ __('customer.sidebar.transaction') }}</li>
        <li>
          <a href="{{ route('customer.orders.index') }}">
            <div class="parent-icon"><ion-icon name="cart-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.orders') }}</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.documents.index') }}">
            <div class="parent-icon"><ion-icon name="document-text-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.documents') }}</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.surat-menyurat.index') }}">
            <div class="parent-icon"><ion-icon name="mail-unread-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.correspondence') }}</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.meeting-room.index') }}">
            <div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.meeting_room') }}</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.podcast-room.index') }}">
            <div class="parent-icon"><ion-icon name="mic-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.podcast_room') }}</div>
          </a>
        </li>
        <li class="menu-label">{{ __('customer.sidebar.settings') }}</li>
        <li>
          <a href="javascript:;">
            <div class="parent-icon"><ion-icon name="person-outline"></ion-icon></div>
            <div class="menu-title">{{ __('customer.sidebar.profile') }}</div>
          </a>
        </li>
      </ul>
      <!--end navigation-->
    </aside>
    <!--end sidebar -->

    <!--start top header-->
    <header class="top-header">
      <nav class="navbar navbar-expand gap-3">
        <div class="ms-2">
            <div class="lw-lang-switcher" id="lw-lang-switcher-desktop">
                <button class="lw-lang-btn" id="lw-lang-trigger" type="button">
                  <span id="lw-lang-current-flag">🇮🇩</span>
                  <span id="lw-lang-current-name">Indonesia</span>
                  <span class="lw-lang-arrow">▼</span>
                </button>
                <div class="lw-lang-dropdown" id="lw-lang-dropdown">
                  <div class="lw-lang-item" data-lw-lang="id">
                    <span class="lw-lang-flag">🇮🇩</span>
                    <span data-i18n="lang.id">Bahasa Indonesia</span>
                  </div>
                  <div class="lw-lang-item" data-lw-lang="en">
                    <span class="lw-lang-flag">🇺🇸</span>
                    <span data-i18n="lang.en">English</span>
                  </div>
                  <div class="lw-lang-item" data-lw-lang="zh">
                    <span class="lw-lang-flag">🇨🇳</span>
                    <span data-i18n="lang.zh">中文</span>
                  </div>
                </div>
            </div>
        </div>
        <div class="toggle-icon">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
        <form class="searchbar">
          <div class="position-absolute top-50 translate-middle-y search-icon ms-3">
            <ion-icon name="search-outline"></ion-icon>
          </div>
          <input class="form-control" type="text" placeholder="{{ __('customer.nav.search_placeholder') }}" />
          <div class="position-absolute top-50 translate-middle-y search-close-icon">
            <ion-icon name="close-outline"></ion-icon>
          </div>
        </form>
        <div class="top-navbar-right ms-auto">
          <ul class="navbar-nav align-items-center">
            <li class="nav-item">
              <a class="nav-link" style="margin: 45px;" href="{{ url('/') }}" title="{{ __('customer.nav.back_to_main') }}">
                <div class=""><ion-icon name="globe-outline"></ion-icon></div>
              </a>
            </li>



            <li class="nav-item">
              <a class="nav-link mobile-search-button" href="javascript:;">
                <div class=""><ion-icon name="search-outline"></ion-icon></div>
              </a>
            </li>

            <li class="nav-item dropdown dropdown-large dropdown-apps">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class=""><ion-icon name="apps-outline"></ion-icon></div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark">
                <div class="row row-cols-3 g-3 p-3">
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-purple text-white"><ion-icon name="cart-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.orders') }}</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-info text-white"><ion-icon name="people-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.teams') }}</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-success text-white"><ion-icon name="shield-checkmark-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.tasks') }}</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-danger text-white"><ion-icon name="videocam-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.media') }}</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-warning text-white"><ion-icon name="file-tray-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.files') }}</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-branding text-white"><ion-icon name="notifications-outline"></ion-icon></div>
                    <div class="app-title">{{ __('customer.nav.apps.alerts') }}</div>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="position-relative">
                  <span class="notify-badge">{{ isset($customer_notifications) ? $customer_notifications->count() : 0 }}</span>
                  <ion-icon name="notifications-outline"></ion-icon>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                  <div class="msg-header">
                    <p class="msg-header-title">{{ __('customer.nav.notif.title') }}</p>
                    <p class="msg-header-clear ms-auto">{{ __('customer.nav.notif.mark_read') }}</p>
                  </div>
                </a>
                <div class="header-notifications-list">
                  @if(isset($customer_notifications) && $customer_notifications->count() > 0)
                      @foreach($customer_notifications as $notif)
                      <a class="dropdown-item" href="{{ $notif['url'] }}">
                        <div class="d-flex align-items-center">
                          <div class="notify text-{{ $notif['color'] }}"><ion-icon name="{{ $notif['icon'] }}"></ion-icon></div>
                          <div class="flex-grow-1">
                            <h6 class="msg-name">{{ $notif['title'] }} <span class="msg-time float-end">{{ $notif['time']->diffForHumans() }}</span></h6>
                            <p class="msg-info">{{ $notif['desc'] }}</p>
                          </div>
                        </div>
                      </a>
                      @endforeach
                  @else
                      <a class="dropdown-item" href="javascript:;">
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1 text-center text-muted">
                            <p class="msg-info">Belum ada notifikasi baru</p>
                          </div>
                        </div>
                      </a>
                  @endif
                </div>
                <a href="{{ route('customer.notifications.index') }}">
                  <div class="text-center msg-footer">{{ __('customer.nav.notif.view_all') }}</div>
                </a>
              </div>
            </li>
            @auth
            <li class="nav-item dropdown dropdown-user-setting">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="user-setting">
                  <img src="{{ asset('template-admin/assets/images/avatars/06.webp') }}" class="user-img" alt="" />
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex flex-row align-items-center gap-2">
                      <img src="{{ asset('template-admin/assets/images/avatars/06.webp') }}" alt="" class="rounded-circle" width="54" height="54" />
                      <div class="">
                        <h6 class="mb-0 dropdown-user-name">{{ Auth::user()->name }}</h6>
                        <small class="mb-0 dropdown-user-designation text-secondary">{{ Auth::user()->email }}</small>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="person-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.profile') }}</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="settings-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.setting') }}</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="speedometer-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.dashboard') }}</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="wallet-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.earnings') }}</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="cloud-download-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.downloads') }}</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="log-out-outline"></ion-icon></div>
                      <div class="ms-3"><span>{{ __('customer.nav.user.logout') }}</span></div>
                    </div>
                  </a>
                </li>
              </ul>
            </li>
            @endauth
          </ul>
        </div>
      </nav>

      {{-- Form logout tersembunyi (wajib POST + CSRF untuk Laravel) --}}
      @auth
      <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
      </form>
      @endauth

    </header>
    <!--end top header-->

    <!-- start page content wrapper-->
    <div class="page-content-wrapper">
      <!-- start page content-->
      <div class="page-content">
        @yield('content')
      </div>
      <!-- end page content-->
    </div>
    <!--end page content wrapper-->

    <!--start footer-->
    <footer class="footer">
      <div class="footer-text">{{ __('customer.footer.copyright') }}</div>
    </footer>
    <!--end footer-->

    <!--Start Back To Top Button-->
    <a href="javaScript:;" class="back-to-top">
      <ion-icon name="arrow-up-outline"></ion-icon>
    </a>
    <!--End Back To Top Button-->



    <!--start overlay-->
    <div class="overlay nav-toggle-icon"></div>
    <!--end overlay-->
  </div>
  <!--end wrapper-->

  <!-- JS Files-->
  <script src="{{ asset('template-admin/assets/js/jquery.min.js') }}"></script>
  <script src="{{ asset('template-admin/assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
  <script src="{{ asset('template-admin/assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
  <script src="{{ asset('template-admin/assets/js/bootstrap.bundle.min.js') }}"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <!--plugins-->
  <script src="{{ asset('template-admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('template-admin/assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
  <script src="{{ asset('template-admin/assets/plugins/easyPieChart/jquery.easypiechart.js') }}"></script>
  <script src="{{ asset('template-admin/assets/plugins/chartjs/chart.min.js') }}"></script>
  <script src="{{ asset('template-admin/assets/js/index.js') }}"></script>
  <!-- Main JS-->
  <script src="{{ asset('template-admin/assets/js/main.js') }}"></script>
  @stack('scripts')
  <!-- ======== i18n Engine: inline translations ======== -->
  <script>
  (function() {
    var _id = @json(json_decode(file_get_contents(resource_path('lang/id.json')), true));
    var _en = @json(json_decode(file_get_contents(resource_path('lang/en.json')), true));
    var _zh = @json(json_decode(file_get_contents(resource_path('lang/zh.json')), true));
    window.__lwTranslations = { id: _id, en: _en, zh: _zh };
  })();
  </script>
  <script src="{{ asset('buyer-file/assets/js/i18n.js') }}"></script>
  <!-- ======== End i18n ======== -->
</body>

</html>