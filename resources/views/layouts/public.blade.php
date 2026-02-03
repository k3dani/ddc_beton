<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ asset('images/betonasplius.jpg') }}"/>
    <title>@yield('title', 'Betonas Plius') | Duna-Dráva Cement</title>
    <link href="https://fonts.googleapis.com/css2?family=Yantramanav:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vendor/bootstrap-theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>

<header>
    <div class="container-fluid">
        <div class="wrap">
            <div class="logo">
                <a href="{{ route('homepage') }}">
                    <img src="{{ asset('images/betonas-logo.svg') }}" alt="Betonas Plius">
                </a>
            </div>
            <div class="menu">
                <ul>
                    <li class="{{ request()->routeIs('homepage') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('homepage') }}">Homepage</a>
                    </li>
                    <li class="{{ request()->routeIs('ecoblock') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('ecoblock') }}">ECOBlock</a>
                    </li>
                    <li class="{{ request()->routeIs('ecocrete') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('ecocrete') }}">EcoCrete</a>
                    </li>
                    <li class="{{ request()->routeIs('about') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('about') }}">Magunkról</a>
                    </li>
                    <li class="{{ request()->routeIs('quality') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('quality') }}">Minőség</a>
                    </li>
                    <li class="{{ request()->routeIs('shop*') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('shop') }}">Rendelés</a>
                    </li>
                    <li class="{{ request()->routeIs('tips') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('tips') }}">Tanácsok</a>
                    </li>
                    <li class="{{ request()->routeIs('news') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('news') }}">Újdonságok</a>
                    </li>
                    <li class="{{ request()->routeIs('shipping') ? 'current-menu-item' : '' }}">
                        <a href="{{ route('shipping') }}">Szállítási feltételek</a>
                    </li>
                </ul>
            </div>
            <div class="resp-btn">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
</header>

@yield('content')

<footer id="susisiekite">
    <div class="container-fluid">
        <div class="logo">
            <a title="Betonas plius" href="{{ route('homepage') }}">
                <img src="{{ asset('images/logo-footer.svg') }}" alt="Betonas plius">
            </a>
        </div>
        <div class="wrap">
            <div class="left">
                <div class="text">
                    <p>A DDC beton+ egyszerű és kényelmes lehetőség beton<br>
                    rendeléséhez nem szerződött partnereink számára.</p>
                </div>
                <div class="socials">
                    <!-- Social media linkek -->
                </div>
            </div>
            <div class="right">
                <div class="single-column">
                    <h5>További információra lenne szükésge? Keressen minket!</h5>
                    <p><a href="mailto:ddcbeton@duna-drava.hu">ddcbeton@duna-drava.hu</a></p>
                    <p><a href="https://www.ddcbeton.hu/hu/betonuzemeink">https://www.ddcbeton.hu/hu/betonuzemeink</a></p>
                </div>
                <div class="single-column">
                    <h5>Duna-Dráva Cement Kft.</h5>
                    <p>Székhely: 2600 Vác, Kőhídpart dűlő 2.<br>
                    Cégjegyzékszám. 13-09-060842<br>
                    Adószám. 10324602-2-44.<br>
                    Unicredit Bank</p>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>{{ date('Y') }} © Duna-Dráva Cement Kft., Vác. Minden jog fenntartva.</p>
        </div>
    </div>
</footer>

<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
@stack('scripts')
</body>
</html>
