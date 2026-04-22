<!doctype html>
<html lang="en" class="light-theme">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- loader-->
  <link href="{{ asset('template-admin/assets/css/pace.min.css') }}" rel="stylesheet" />
  <script src="{{ asset('template-admin/assets/js/pace.min.js') }}"></script>
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
  <title>@yield('title', 'Lawgika - Dashboard Customer')</title>
</head>

<body>
  <!--start wrapper-->
  <div class="wrapper">
    <!--start sidebar -->
    <aside class="sidebar-wrapper" data-simplebar="true">
      <div class="sidebar-header">
        <div>
          <img src="{{ asset('template-admin/assets/images/logo-icon-2.png') }}" class="logo-icon" alt="logo icon" />
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
            <div class="menu-title">Dashboard</div>
          </a>
        </li>
        <li class="menu-label">Transaksi</li>
        <li>
          <a href="{{ route('customer.orders.index') }}">
            <div class="parent-icon"><ion-icon name="cart-outline"></ion-icon></div>
            <div class="menu-title">Riwayat Order</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.documents.index') }}">
            <div class="parent-icon"><ion-icon name="document-text-outline"></ion-icon></div>
            <div class="menu-title">Dokumen Saya</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.meeting-room.index') }}">
            <div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>
            <div class="menu-title">Reservasi Meeting Room</div>
          </a>
        </li>
        <li>
          <a href="{{ route('customer.spt-badan.index') }}">
            <div class="parent-icon"><ion-icon name="document-outline"></ion-icon></div>
            <div class="menu-title">Laporan SPT Badan</div>
          </a>
        </li>
        <li class="menu-label">Pengaturan</li>
        <li>
          <a href="javascript:;">
            <div class="parent-icon"><ion-icon name="person-outline"></ion-icon></div>
            <div class="menu-title">Profil Saya</div>
          </a>
        </li>
      </ul>
      <!--end navigation-->
    </aside>
    <!--end sidebar -->

    <!--start top header-->
    <header class="top-header">
      <nav class="navbar navbar-expand gap-3">
        <div class="toggle-icon">
          <ion-icon name="menu-outline"></ion-icon>
        </div>
        <form class="searchbar">
          <div class="position-absolute top-50 translate-middle-y search-icon ms-3">
            <ion-icon name="search-outline"></ion-icon>
          </div>
          <input class="form-control" type="text" placeholder="Search for anything" />
          <div class="position-absolute top-50 translate-middle-y search-close-icon">
            <ion-icon name="close-outline"></ion-icon>
          </div>
        </form>
        <div class="top-navbar-right ms-auto">
          <ul class="navbar-nav align-items-center">
            <li class="nav-item">
              <a class="nav-link" style="margin: 45px;" href="{{ url('/') }}" title="Kembali ke Landing Utama">
                <span>landingpage</span>
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
                    <div class="app-title">Orders</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-info text-white"><ion-icon name="people-outline"></ion-icon></div>
                    <div class="app-title">Teams</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-success text-white"><ion-icon name="shield-checkmark-outline"></ion-icon></div>
                    <div class="app-title">Tasks</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-danger text-white"><ion-icon name="videocam-outline"></ion-icon></div>
                    <div class="app-title">Media</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-warning text-white"><ion-icon name="file-tray-outline"></ion-icon></div>
                    <div class="app-title">Files</div>
                  </div>
                  <div class="col text-center">
                    <div class="app-box mx-auto bg-gradient-branding text-white"><ion-icon name="notifications-outline"></ion-icon></div>
                    <div class="app-title">Alerts</div>
                  </div>
                </div>
              </div>
            </li>
            <li class="nav-item dropdown dropdown-large">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="position-relative">
                  <span class="notify-badge">8</span>
                  <ion-icon name="notifications-outline"></ion-icon>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                  <div class="msg-header">
                    <p class="msg-header-title">Notifications</p>
                    <p class="msg-header-clear ms-auto">Marks all as read</p>
                  </div>
                </a>
                <div class="header-notifications-list">
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-primary"><ion-icon name="cart-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min ago</span></h6>
                        <p class="msg-info">You have recived new orders</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-danger"><ion-icon name="person-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec ago</span></h6>
                        <p class="msg-info">5 new user registered</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-success"><ion-icon name="document-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min ago</span></h6>
                        <p class="msg-info">The pdf files generated</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-info"><ion-icon name="checkmark-done-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Product Approved <span class="msg-time float-end">2 hrs ago</span></h6>
                        <p class="msg-info">Your new product has approved</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-warning"><ion-icon name="send-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min ago</span></h6>
                        <p class="msg-info">5.1 min avarage time response</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-danger"><ion-icon name="chatbox-ellipses-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs ago</span></h6>
                        <p class="msg-info">New customer comments recived</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-primary"><ion-icon name="albums-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day ago</span></h6>
                        <p class="msg-info">24 new authors joined last week</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-success"><ion-icon name="shield-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs ago</span></h6>
                        <p class="msg-info">Successfully shipped your item</p>
                      </div>
                    </div>
                  </a>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class="notify text-warning"><ion-icon name="cafe-outline"></ion-icon></div>
                      <div class="flex-grow-1">
                        <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks ago</span></h6>
                        <p class="msg-info">45% less alerts last 4 weeks</p>
                      </div>
                    </div>
                  </a>
                </div>
                <a href="javascript:;">
                  <div class="text-center msg-footer">View All Notifications</div>
                </a>
              </div>
            </li>
            @auth
            <li class="nav-item dropdown dropdown-user-setting">
              <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;" data-bs-toggle="dropdown">
                <div class="user-setting">
                  <img src="{{ asset('template-admin/assets/images/avatars/06.png') }}" class="user-img" alt="" />
                </div>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex flex-row align-items-center gap-2">
                      <img src="{{ asset('template-admin/assets/images/avatars/06.png') }}" alt="" class="rounded-circle" width="54" height="54" />
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
                      <div class="ms-3"><span>Profile</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="settings-outline"></ion-icon></div>
                      <div class="ms-3"><span>Setting</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="speedometer-outline"></ion-icon></div>
                      <div class="ms-3"><span>Dashboard</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="wallet-outline"></ion-icon></div>
                      <div class="ms-3"><span>Earnings</span></div>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="dropdown-item" href="javascript:;">
                    <div class="d-flex align-items-center">
                      <div class=""><ion-icon name="cloud-download-outline"></ion-icon></div>
                      <div class="ms-3"><span>Downloads</span></div>
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
                      <div class="ms-3"><span>Logout</span></div>
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
      <div class="footer-text">Copyright &copy; 2026. All right reserved.</div>
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
</body>

</html>