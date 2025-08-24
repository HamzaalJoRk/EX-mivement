<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="rtl">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title> معبر نصيب</title>
    <link rel="apple-touch-icon" href="{{asset('logo.jpg')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('logo.jpg')}}">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet"> -->

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/vendors-rtl.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/charts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/vendors/css/extensions/toastr.min.css') }}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/pages/dashboard-ecommerce.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/plugins/charts/chart-apex.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/app-assets/css-rtl/plugins/extensions/ext-component-toastr.css') }}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('/app-assets/css-rtl/custom-rtl.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style-rtl.css') }}">
    <!-- END: Custom CSS-->

    <!-- إضافة CSS الخاصة بـ DataTables -->
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- إضافة JS الخاصة بـ DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

    <style>
        .navigation-main .nav-item a {
            font-family: 'Cairo', sans-serif !important;
        }

        .main-menu.menu-light .navigation>li.active>a {
            background: -webkit-linear-gradient(208deg, #fff, #fff);
            background: linear-gradient(-118deg, #fff, #fff);
            color: #3c8dbc;
            box-shadow: 0 0 10px 1px #ffffff;
            font-weight: 800;
            border-radius: 4px;
        }

        .main-menu.menu-light .navigation>li>a {
            color: #fff;
            font-weight: 800;
        }

        .main-menu.menu-light .navigation {
            background-color: #3c8dbc;
        }

        .main-menu.menu-light {
            background-color: #3c8dbc;
        }

        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  menu-expanded" data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav
        class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon"
                                data-feather="menu"></i></a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li class="nav-item d-none d-lg-block"><a class="nav-link bookmark-star"></a>
                        <h2 style="font-weight: bold;font-family: 'Cairo', sans-serif;">
                            {{ auth()->user()->borderCrossing ? auth()->user()->borderCrossing->name : 'الادارة' }}
                            - الجمارك
                        </h2>
                    </li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                <li class="nav-item d-none d-lg-block"
                    style="font-family: 'Cairo', sans-serif !important; font-weight: bold;">
                    مرحبا. {{ auth()->user()->name }} - @foreach(auth()->user()->getRoleNames() as $v)
                        @if ($v == 'Admin')
                            المدير العام
                        @elseif ($v == 'CustomEntry')
                            جمارك دخول
                        @elseif ($v == 'CustomExit')
                            جمارك خروج
                        @elseif ($v == 'babExit')
                            موظف باب خروج
                        @elseif ($v == 'Finance')
                            موظف مالية
                        @elseif ($v == 'babEntry')
                            موظف باب دخول
                        @else
                            {{ $v }}
                        @endif
                    @endforeach
                </li>
                <li class="nav-item dropdown dropdown-user"><a class="nav-link dropdown-toggle dropdown-user-link"
                        id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder"></span><span
                                class="user-status"></span></div><span class="avatar"><img class="round"
                                src="{{asset('logo.jpg')}}" alt="avatar" height="40" width="40"><span
                                class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{ route('profile.edit') }}"
                            style="font-family: 'Cairo', sans-serif;"><i class="me-50" data-feather="user"></i>
                            العلومات الشخصية</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                style="font-family: 'Cairo', sans-serif;">
                                <i class="me-50" data-feather="power"></i> تسجيل الخروج
                            </x-responsive-nav-link>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <ul class="main-search-list-defaultlist-other-list d-none">
        <li class="auto-suggestion justify-content-between"><a
                class="d-flex align-items-center justify-content-between w-100 py-50">
                <div class="d-flex justify-content-start"><span class="me-75"
                        data-feather="alert-circle"></span><span>No results found.</span></div>
            </a></li>
    </ul>
    <!-- END: Header-->

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item me-auto"><a class="navbar-brand" href="/">
                        <span class="brand-logo">
                            <img src="{{ asset('logo.jpg') }}" alt="" />
                        </span>
                        <h2 class="brand-text" style="color: #fff;">
                            {{ auth()->user()->borderCrossing ? auth()->user()->borderCrossing->name : 'الادارة' }}
                        </h2>
                    </a></li>
                <li style="color: #fff;" class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0"
                        data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4"
                            data-feather="x"></i><i
                            class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary"
                            data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main">
                <li class="nav-item">
                    <a class="d-flex align-items-center" href="{{ route('entrySearch') }}">
                        <i class="fas fa-search me-1"></i>
                        <span>بحث عن حركة</span>
                    </a>
                </li>

                @if (auth()->user()->hasRole('Finance'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('finance.transactions.index') }}">
                            <i class="fas fa-cash-register me-1"></i>
                            <span>الصندوق المالي</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('finance.receipts.index') }}">
                            <i class="fas fa-cash-register me-1"></i>
                            <span>ايصالات الدفع</span>
                        </a>
                    </li>
                @elseif (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('finance.boxes.index') }}">
                            <i class="fas fa-box-open me-1"></i>
                            <span>الصندوق المالي</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('finance.receipts.index') }}">
                            <i class="fas fa-cash-register me-1"></i>
                            <span>ايصالات الدفع</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('dashboard') }}">
                            <i class="fas fa-random me-1"></i>
                            <span>حركات الدخول والخروج</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('CustomEntry') || auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('entry_statements.create') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            <span>تسجيل حركة دخول</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/entry-statements-book/create">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            <span> تسجيل حركة دخول لدفتر</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('CustomExit') || auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/exit-statements/create">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            <span>تسجيل حركة خروج</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="/exit-statements-book/create">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            <span> تسجيل حركة خروج لدفتر</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('violations.index') }}">
                            <i class="fas fa-gavel me-1"></i>
                            <span>المخالفات</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('border_crossing.index') }}">
                            <i class="fas fa-border-style me-1"></i>
                            <span>المعابر</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('users.index') }}">
                            <i class="fas fa-users me-1"></i>
                            <span>المستخدمين</span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasRole('Admin'))
                    <li class="nav-item">
                        <a class="d-flex align-items-center" href="{{ route('roles.index') }}">
                            <i class="fas fa-user-shield me-1"></i>
                            <span>الصلاحيات</span>
                        </a>
                    </li>
                @endif
            </ul>

        </div>
    </div>
    <!-- END: Main Menu-->

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light" style="background-color: white;">
        <p class=" mb-0"><span class="float-md-start d-none d-md-block">Copyright © 2025 الهيئة العامة للمنافذ البرية
                والبحرية . جميع الحقوق محفوظة.<a class="ms-25" href="{{asset('logo.jpg')}}" target="_blank"> </a> <img
                    class="round" src="{{asset('logo.jpg')}}" alt="avatar" height="20" width="20"></span></span><span
                class="float-md-end d-none d-md-block">الهيئة العامة للمنافذ البرية والبحرية - المكتب البرمجي <img
                    class="round" src="{{asset('logo.jpg')}}" alt="avatar" height="20" width="20"></span></p>
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->

    <!-- jQuery + DataTables Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/vendors.min.js') }}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('/app-assets/js/core/app.js') }}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
    <!-- END: Page JS-->

    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/extensions/polyfill.min.js"></script>
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Page JS-->
    <script src="../../../app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>
    <!-- END: Page JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(window).on('load', function () {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>

    <script>
        document.querySelector('.modern-nav-toggle').addEventListener('click', function () {
            document.body.classList.toggle('menu-collapsed');
        });

        document.addEventListener("DOMContentLoaded", function () {
            // الحصول على مسار الصفحة الحالي
            var currentPath = window.location.pathname;

            // الحصول على جميع عناصر القائمة
            var menuItems = document.querySelectorAll(".navigation-main .nav-item a");

            menuItems.forEach(function (item) {
                if (item.href.includes(currentPath)) {
                    item.parentElement.classList.add("active");
                }
            });
        });
    </script>
    @yield('scripts')
</body>
<!-- END: Body-->

</html>