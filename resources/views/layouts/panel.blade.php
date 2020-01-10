<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
 
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,400i,700,700i&display=swap&subset=cyrillic" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('css/panel/style.css') }}" rel="stylesheet">
    
    <!-- app js -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <title>{{ (isset($title) && empty(!$title)) ? $title.' — ' : '' }}AdMount (панель управления)</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="{{ route('panel') }}">AdMount</a>
                
                <div class="ml-auto" id="navbarSupportedContent">
                    <!-- right side of navbar -->
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link dropdown-toggle nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-user nav-user__icon"></i><span class="nav-user__text">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name">{{ Auth::user()->name }}</h5>
                                    <span class="nav-user-email">{{ Auth::user()->email }}</span>
                                </div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-power-off mr-2"></i>Выход</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                    <!-- end right side of navbar -->
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
            <div class="menu-list">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="d-xl-none d-lg-none" href="#">Меню</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            {{--<li class="nav-divider">
                                Аккаунт
                            </li>--}}
                            <li class="nav-item ">
                                <a class="nav-link {{ \Route::currentRouteName() == 'panel' ? 'active' : '' }}" href="{{ route('panel') }}" aria-expanded="false"><i class="fa fa-fw fa-chart-pie"></i>Панель</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ \Route::currentRouteName() == 'profile' ? 'active' : '' }}" href="{{ route('profile') }}" aria-expanded="false"><i class="fa fa-fw fa-address-card"></i>Профиль</a>
                            </li>
                            {{--<li class="nav-divider">
                                Системы
                            </li>--}}
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-6" aria-controls="submenu-6"><i class="fas fa-fw fa-file"></i>Яндекс Директ</a>
                                <div id="submenu-6" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link {{ \Route::currentRouteName() == 'yandex-review' ? 'active' : '' }}" href="{{ route('yandex-review') }}">Статистика</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ \Route::currentRouteName() == 'yandex-run' ? 'active' : '' }}" href="{{ route('yandex-run') }}">Расписание и запуск</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link {{ \Route::currentRouteName() == 'yandex-settings' ? 'active' : '' }}" href="{{ route('yandex-settings') }}">Настройки</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">{{ $title }}</h2>
                                <p class="pageheader-text"></p>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <!-- content -->
                    @yield('content')
                    <!-- end content -->
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 col-sm-4">
                             © {{ now()->year }} AdMount
                        </div>
                        <div class="col-8">
                            <div class="text-right footer-links d-none d-sm-block">
                                <a class="video-play-link" href="https://www.youtube.com/watch?v=dmrGbFs88Mo">Как это работает</a>
                                <a href="mailto:{{ config('global.email_support') }}">Поддержка</a>
                                {{--<a href="#">Контакты</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- JavaScript -->
    <!-- slimscroll js -->
    <script src="{{ asset('js/jquery-slimscroll.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('js/panel/main-js.js') }}"></script>
</body>
 
</html>