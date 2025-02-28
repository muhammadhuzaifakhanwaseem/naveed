@php
    $contact = content('contact.content');
    $footersociallink = element('footer.element');
@endphp

<!-- header-section start  -->
<header class="header">
    <div class="header-bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl p-0 align-items-center">

                <a class="site-logo site-title" href="{{ route('home') }}">
                    @if (@$general->logo)
                        <img class="img-fluid rounded sm-device-img text-align"
                            src="{{ getFile('logo', @$general->logo) }}" width="100%" alt="pp">
                    @else
                        {{ __('No Logo Found') }}
                    @endif
                </a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse mt-lg-0 mt-3" id="mainNavbar">
                    <ul class="nav navbar-nav sp_main_menu me-auto">
                        <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}"><a class="nav-link"
                                href="{{ route('home') }}">{{ __('Home') }}</a></li>

                        <li class="nav-item"><a class="nav-link"
                                href="{{ route('investmentplan') }}">{{ __('Investment Plans') }}</a>
                        </li>

                        @forelse ($pages as $page)
                            <li class="nav-item"><a class="nav-link"
                                    href="{{ route('pages', $page->slug) }}">{{ __($page->name) }}</a>
                            </li>
                        @empty
                        @endforelse

                        <li class="nav-item"><a class="nav-link" href="{{ route('allblog') }}">{{ __('Blog') }}</a>
                        </li>

                    </ul>
                    <div class="navbar-action">
                        <select class="changeLang me-3" aria-label="Default select example">
                            @foreach ($language_top as $top)
                                <option value="{{ $top->short_code }}"
                                    {{ session('locale') == $top->short_code ? 'selected' : '' }}>
                                    {{ __(ucwords($top->name)) }}
                                </option>
                            @endforeach
                        </select>
                        @if (Auth::user())
                            <a class="btn main-btn btn-sm"
                                href="{{ route('user.dashboard') }}">{{ __('Dashboard') }}</a>
                        @else
                            <a class="text-white me-3" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                            <a href="{{ route('user.register') }}" class="btn main-btn btn-sm">Sign up <i
                                    class="las la-long-arrow-alt-right ms-2"></i></a>
                        @endif
                    </div>
                </div>
            </nav>
        </div>
    </div><!-- header-bottom end -->
</header>
<!-- header-section end  -->


{{-- <header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center justify-content-lg-between">
        <div class="logo me-auto me-lg-0">
        </div>
        <nav id="navbar" class="navbar order-last order-lg-0">
            <ul>
                <li class=" d-sm-block d-md-block d-lg-none">
                    <select class="custom-select-form selectric ms-3 rounded changeLang nav-link scrollto"
                        aria-label="Default select example">
                        @foreach ($language_top as $top)
                            <option value="{{ $top->short_code }}"
                                {{ session('locale') == $top->short_code ? 'selected' : '' }}>
                                {{ __(ucwords($top->name)) }}
                            </option>
                        @endforeach
                    </select>
                </li>
            </ul>
            <i class="bi bi-list mobile-nav-toggle"></i>
        </nav>
        <div class="header-right d-flex d-none  d-md-none d-lg-block">
            @if (Auth::user())
                <a href="{{ route('user.dashboard') }}" class="btn-border btn-sm me-3">{{ __('Dashboard') }}</a>
            @else
                <a href="{{ route('user.login') }}" class="btn-border btn-sm me-3">{{ __('Login') }}</a>
            @endif
            <select class="changeLang" aria-label="Default select example">
                @foreach ($language_top as $top)
                    <option value="{{ $top->short_code }}"
                        {{ session('locale') == $top->short_code ? 'selected' : '' }}>
                        {{ __(ucwords($top->name)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</header> --}}
<!----------------------Bottom Navigation------------------------------>
<style>
    .bottom_nav {
        overflow: hidden;
        background-color: #1c1c1ce0;
        position: fixed;
        bottom: 0;
        left: 50%;
        width: 95%;
        height: 70px;
        border-top-left-radius: 16px;
        display: flex;
        backdrop-filter: blur(5px);
        justify-content: space-around;
        align-items: center;
        z-index: 999;
        border-top-right-radius: 16px;
        transform: translateX(-50%);
    }

    .bottom_nav a {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #ccc;
        font-size: 9px;
        flex-grow: 1;
        transition: all 0.3s ease;
    }

    .bottom_nav a img {
        width: 17px;
        height: 17px;
        margin-bottom: 4px;
    }

    .bottom_nav a.active {
        color: white;
        font-weight: bold;
    }

    .bottom_nav a.active img {
        background: linear-gradient(135deg, #65b96b, #e73433, #194668);
        border-radius: 50%;
        padding: 5px;
    }

    .bottom_nav a:hover {
        color: white;
    }

    @media screen and (min-width: 769px) {
        .bottom_nav {
            display: none;
        }
    }

    @media screen and (max-width: 768px) {
        .bottom_nav {
            display: flex;
        }
    }
</style>

<div class="bottom_nav">
    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/home.png" alt="Home Icon">
        {{ __('Home') }}
    </a>
    <a class="nav-link {{ request()->routeIs('investmentplan') ? 'active' : '' }}"
        href="{{ route('investmentplan') }}">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/money-bag.png" alt="Investment Icon">
        {{ __('Investment') }}
    </a>
    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('pages', 'about') }}">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/info.png" alt="About Icon">
        {{ __('About') }}
    </a>
    <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('pages', 'contact') }}">
        <img src="https://img.icons8.com/ios-filled/50/ffffff/phone.png" alt="Contact Icon">
        {{ __('Contact') }}
    </a>
    @if (Auth::user())
        <a class="nav-link" href="{{ route('user.dashboard') }}">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/dashboard.png" alt="Dashboard Icon">
            {{ __('Dashboard') }}
        </a>
    @else
        <a href="{{ route('user.login') }}">
            <img src="https://img.icons8.com/ios-filled/50/ffffff/login-rounded-right.png" alt="Login Icon">
            {{ __('Login') }}
        </a>
    @endif
</div>


<!--Floating Whatsapp ICon-->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .whatsapp-float {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .whatsapp-float:hover {
            background-color: #1ebe5d;
            transform: scale(1.1);
        }
    </style>
    <a href="https://wa.me/+923285412930" target="_blank" class="whatsapp-float">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!--End Floating Whatsapp icon-->