<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SocialV </title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('/images/template/favicon.ico') }}" />
    <link rel="stylesheet" href="{{ asset('/css/libs.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/socialv.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('/vendor/remixicon/fonts/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/vanillajs-datepicker/dist/css/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/font-awesome-line-awesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/vendor/toastr/toastr.min.css') }}">
    @yield('style')

    <style>
        span#timerDisplay {
            background: #449ad9;
            color: #ffff;
            padding: 3px 3px 3px 3px;
        }
    </style>
</head>

<body class="  ">
    <!-- loader Start -->
    <div id="loading">
        <div id="loading-center">
        </div>
    </div>
    <!-- loader END -->
    <!-- Wrapper Start -->
    <div class="wrapper">
        <div class="iq-sidebar  sidebar-default ">
            <div id="sidebar-scrollbar">
                <nav class="iq-sidebar-menu">
                    <ul id="iq-sidebar-toggle" class="iq-menu">
                        <li class="{{ Request::segment(1) == '' ? 'active' : '' }}">
                            <a href="{{ route('homePage') }}" class=" ">
                                <i class="las la-newspaper"></i><span>Newsfeed</span>
                            </a>
                        </li>
                        <li class="{{ Request::segment(1) == 'profile' ? 'active' : '' }}">
                            <a href="{{ route('profile') }}" class=" ">
                                <i class="las la-user"></i><span>Profile</span>
                            </a>
                        </li>
                        <li class="{{ Request::segment(1) == 'chat' ? 'active' : '' }}">
                            <a href="{{ route('chat.index') }}" class=" ">
                                <i class="ri-mail-line"></i><span>Chat</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="#mailbox" data-bs-toggle="collapse" class="  collapsed" aria-expanded="false">
                                <i class="las la-chart-pie"></i><span>Analytics</span><i
                                    class="ri-arrow-right-s-line iq-arrow-right"></i>
                            </a>
                            <ul id="mailbox" class="iq-submenu collapse" data-bs-parent="#iq-sidebar-toggle">
                                <li class="{{ Request::segment(1) == 'user-engagement' ? 'active' : '' }}">
                                    <a href="{{ route('userEngagementDataView') }}" class=" ">
                                        <i class="las la-chart-pie"></i><span>User Engagement</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <div class="p-5"></div>
            </div>
        </div>

        <div class="iq-top-navbar">
            <div class="iq-navbar-custom">
                <nav class="navbar navbar-expand-lg navbar-light p-0">
                    <div class="iq-navbar-logo d-flex justify-content-between">
                        <a href="{{ route('homePage') }}">
                            <img src="{{ asset('/images/template/logo.png') }}" class="img-fluid" alt="">
                            <span>SocialV</span>
                        </a>
                        <div class="iq-menu-bt align-self-center">
                            <div class="wrapper-menu">
                                <div class="main-circle"><i class="ri-menu-line"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="iq-search-bar device-search">
                        <form action="{{ route('search') }}" class="searchbox">
                            <a class="search-link" href="#"><i class="ri-search-line"></i></a>
                            <input type="text" name="query" class="text search-input" placeholder="Search here...">
                        </form>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-label="Toggle navigation">
                        <i class="ri-menu-3-line"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav  ms-auto navbar-list">
                            <li>
                                <div id="timerDisplayDiv">
                                    <span id="timerDisplay">
                                        Timer: 0 seconds
                                    </span>
                                </div>

                                {{-- <a href="../dashboard/index.html" class="  d-flex align-items-center">
                                    <i class="ri-home-line"></i>
                                </a> --}}
                            </li>
                            <li class="nav-item dropdown">
                                <a href="{{ route('friend.friendrequest') }}"
                                    aria-haspopup="true" aria-expanded="false"><i class="ri-group-line"></i></a>

                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="search-toggle   dropdown-toggle" id="notification-drop"
                                    data-bs-toggle="dropdown">
                                    <i class="ri-notification-4-line"></i>
                                </a>
                                <div class="sub-drop dropdown-menu" aria-labelledby="notification-drop">
                                    <div class="card shadow-none m-0">
                                        <div class="card-header d-flex justify-content-between bg-primary">
                                            <div class="header-title bg-primary">
                                                <h5 class="mb-0 text-white">All Notifications</h5>
                                            </div>
                                            <small class="badge  bg-light text-dark">4</small>
                                        </div>
                                        <div class="card-body p-0">
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <img class="avatar-40 rounded"
                                                            src="{{ asset('/images/template/user/01.jpg') }}" alt="">
                                                    </div>
                                                    <div class="ms-3 w-100">
                                                        <h6 class="mb-0 ">Emma Watson Bni</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0">95 MB</p>
                                                            <small class="float-right font-size-12">Just Now</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <img class="avatar-40 rounded"
                                                            src="{{ asset('/images/template/user/02.jpg') }}" alt="">
                                                    </div>
                                                    <div class="ms-3 w-100">
                                                        <h6 class="mb-0 ">New customer is join</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0">Cyst Bni</p>
                                                            <small class="float-right font-size-12">5 days ago</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <img class="avatar-40 rounded"
                                                            src="{{ asset('/images/template/user/03.jpg') }}" alt="">
                                                    </div>
                                                    <div class="ms-3 w-100">
                                                        <h6 class="mb-0 ">Two customer is left</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0">Cyst Bni</p>
                                                            <small class="float-right font-size-12">2 days ago</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="#" class="iq-sub-card">
                                                <div class="d-flex align-items-center">
                                                    <div class="">
                                                        <img class="avatar-40 rounded"
                                                            src="{{ asset('/images/template/user/04.jpg') }}" alt="">
                                                    </div>
                                                    <div class="w-100 ms-3">
                                                        <h6 class="mb-0 ">New Mail from Fenny</h6>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <p class="mb-0">Cyst Bni</p>
                                                            <small class="float-right font-size-12">3 days ago</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="{{ route('chat.index') }}"  id="mail-drop"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ri-mail-line"></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a href="#" class="   d-flex align-items-center dropdown-toggle"
                                    id="drop-down-arrow" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                   <!-- Assuming this is part of your layout file -->

                                   <div class="user-img" >
                                    @if($profilePicture && $profilePicture->file_path)
                                        <img src="{{ Storage::url($profilePicture->file_path) }}" alt="profile-img" class="avatar-40 img-fluid rounded-circle" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;"  />
                                    @else
                                        <img src="{{ asset('/images/template/user/Noprofile.jpg') }}" alt="profile-img" class="avatar-60 img-fluid rounded-circle"  style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;" />
                                    @endif
                                </div>

                                    <div class="caption">
                                        {{-- $firstName = strstr($name, ' ', true); // Get the substring before the first occurrence of space --}}
                                        <h6 class="mb-0 line-height">{{ strstr(auth()->user()->name, ' ', true) }}
                                        </h6>
                                    </div>
                                </a>
                                <div class="sub-drop dropdown-menu caption-menu" aria-labelledby="drop-down-arrow">
                                    <div class="card shadow-none m-0">
                                        <div class="card-header  bg-primary">
                                            <div class="header-title">
                                                <h5 class="mb-0 text-white">Hello {{ auth()->user()->name }}</h5>
                                                <span class="text-white font-size-12">Available</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-0 ">
                                            <a href="{{route('profile')}}" class="iq-sub-card iq-bg-primary-hover">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded card-icon bg-soft-primary">
                                                        <i class="ri-file-user-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">My Profile</h6>
                                                        <p class="mb-0 font-size-12">View personal profile details.</p>
                                                    </div>
                                                </div>
                                            </a>
                                            {{-- <a href="../app/profile-edit.html"
                                                class="iq-sub-card iq-bg-warning-hover">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded card-icon bg-soft-warning">
                                                        <i class="ri-profile-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">Edit Profile</h6>
                                                        <p class="mb-0 font-size-12">Modify your personal details.</p>
                                                    </div>
                                                </div>
                                            </a> --}}
                                            {{-- <a href="../app/account-setting.html"
                                                class="iq-sub-card iq-bg-info-hover">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded card-icon bg-soft-info">
                                                        <i class="ri-account-box-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">Account settings</h6>
                                                        <p class="mb-0 font-size-12">Manage your account parameters.
                                                        </p>
                                                    </div>
                                                </div>
                                            </a> --}}
                                            {{-- <a href="../app/privacy-setting.html"
                                                class="iq-sub-card iq-bg-danger-hover">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded card-icon bg-soft-danger">
                                                        <i class="ri-lock-line"></i>
                                                    </div>
                                                    <div class="ms-3">
                                                        <h6 class="mb-0 ">Privacy Settings</h6>
                                                        <p class="mb-0 font-size-12">Control your privacy parameters.
                                                        </p>
                                                    </div>
                                                </div>
                                            </a> --}}
                                            <div class="d-inline-block w-100 text-center p-3">
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                    style="display: none;">
                                                    @csrf
                                                </form>
                                                <a class="btn btn-primary iq-sign-btn" href="#"
                                                    role="button"onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    Sign out
                                                    <i class="ri-login-box-line ms-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div id="content-page" class="content-page">
            <div class="container">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- Wrapper End-->
    <footer class="iq-footer bg-white">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="../dashboard/privacy-policy.html">Privacy Policy</a>
                        </li>
                        <li class="list-inline-item"><a href="../dashboard/terms-of-service.html">Terms of Use</a>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 d-flex justify-content-end">
                    Copyright 2020 <a href="#">SocialV</a> All Rights Reserved.
                </div>
            </div>
        </div>
    </footer> <!-- Backend Bundle JavaScript -->
    <script src="{{ asset('/js/libs.min.js') }}"></script>
    <!-- slider JavaScript -->
    <script src="{{ asset('/js/slider.js') }}"></script>
    <!-- masonry JavaScript -->
    <script src="{{ asset('/js/masonry.pkgd.min.js') }}"></script>
    <!-- SweetAlert JavaScript -->
    <script src="{{ asset('/js/enchanter.js') }}"></script>
    <!-- SweetAlert JavaScript -->
    <script src="{{ asset('/js/sweetalert.js') }}"></script>
    <!-- app JavaScript -->
    <script src="{{ asset('/js/charts/weather-chart.js') }}"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/vendor/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('/vendor/vanillajs-datepicker/dist/js/datepicker.min.js') }}"></script>
    <script src="{{ asset('/js/lottie.js') }}"></script>
    {{-- Preview js --}}
    <script src="{{ asset('/js/preview.js') }}"></script>

    <script src="{{ asset('/js/profilepreview.js') }}"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>


    {{-- create a csrf token to post form in user-engagement.js file --}}
    <script>
        let csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('/js/user-engagement.js') }}"></script>


    @if(session('message'))
        <script>
            toastr.success('{{ session('message') }}');
        </script>
    @endif
    {{-- @if ($errors->any())
        <script>
            toastr.error('{{$errors->first()}}');
        </script>
    @endif --}}
    @yield('script')
    <!-- offcanvas start -->

    <div class="offcanvas offcanvas-bottom share-offcanvas" tabindex="-1" id="share-btn"
        aria-labelledby="shareBottomLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="shareBottomLabel">Share</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body small">
            <div class="d-flex flex-wrap align-items-center">
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/08.png') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>Facebook</h6>
                </div>
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/09.png') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>Twitter</h6>
                </div>
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/10.png') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>Instagram</h6>
                </div>
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/Noprofile.jpg') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>Google Plus</h6>
                </div>
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/13.png') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>In</h6>
                </div>
                <div class="text-center me-3 mb-3">
                    <img src="{{ asset('/images/template/icon/12.png') }}" class="img-fluid rounded mb-2" alt="">
                    <h6>YouTube</h6>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
