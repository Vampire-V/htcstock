<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('assets/css/w3.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/nicepage.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/Page-2.css') }}">
    <link id="u-theme-google-font" rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i">
    <link id="u-page-google-font" rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i,900,900i">
</head>

<body>
    <section class="u-clearfix u-section-1" id="carousel_5783">
        <div class="w3-top">
            <div class="w3-bar" id="myNavbar">
                <a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right"
                    href="javascript:void(0);" onclick="toggleFunction()" title="Toggle Navigation Menu">
                    <i class="fa fa-bars"></i>
                </a>
                @if (Route::has('login'))
                @auth
                <a href="{{ route('logout') }}" class="w3-bar-item w3-button w3-hide-small" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="fa fa-user"></i> LOGOUT</a>
                @can('super-admin')
                <a href="{{ url('log-viewer') }}" class="w3-bar-item w3-button w3-hide-small"><i
                    class="fa fa-industry"></i>LOG-VIEWER</a>
                @endcan
                @else
                <a href="{{ route('login') }}" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-user"></i>
                    LOGIN</a>
                @endauth
                @endif
            </div>

            <!-- Navbar on small screens -->
            <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
                @if (Route::has('login'))
                @auth
                <a href="{{ route('logout') }}" class="w3-bar-item w3-button" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><i class="fa fa-user"></i> LOGOUT</a>
                @else
                <a href="{{ route('login') }}" class="w3-bar-item w3-button" onclick="toggleFunction()"><i
                        class="fa fa-user"></i> LOGIN</a>
                @endauth
                @endif
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>

        <div class="u-clearfix u-sheet u-valign-middle-xl u-sheet-1">
            <img src="{{ asset('assets/images/pexelsphoto3178818.jpeg') }}" alt=""
                class="u-image u-image-default u-image-1">
            <div class="u-container-style u-group u-group-1">
                <div class="u-container-layout u-valign-top u-container-layout-1">
                    <h1 class="u-custom-font u-font-playfair-display u-text u-text-1">Haier<br><b>All System</b>
                        <br>
                    </h1>
                    <p class="u-text u-text-2">Adding items that have that natural feel or are from organic roots would
                        give our
                        homes the warmth of nature. </p>
                </div>
            </div>
            <div class="u-expanded-width-xs u-list u-repeater u-list-1">
                @isset($systems)
                @foreach ($systems as $item)

                <div style="cursor: pointer;" onclick="goTo('systemset/{{$item->slug}}')"
                    class="u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-1">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-2">

                        <i class="{{$item->icon}}" aria-hidden="true" style="margin: auto"></i>
                        <h5 class="u-align-center u-custom-font u-font-playfair-display u-text u-text-default u-text-3"
                            style="margin: auto">
                            {{$item->name}}</h5>
                    </div>
                </div>
                @endforeach
                @endisset
                {{-- <div style="cursor: pointer;" onclick="goTo('it/dashboard')"
                    class="u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-1">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-2">

                        <i class="fa fa-dropbox fa-5x" aria-hidden="true" style="margin: auto"></i>
                        <h5 class="u-align-center u-custom-font u-font-playfair-display u-text u-text-default u-text-3"
                            style="margin: auto">
                            IT STOCK</h5>
                    </div>
                </div>
                <div style="cursor: pointer;" onclick="goTo('legal/dashboard')"
                    class="u-align-center u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-list-item-2">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-3">
                        <i class="fa fa-gavel fa-5x" aria-hidden="true" style="margin: auto"></i>

                        <h5 class="u-custom-font u-font-playfair-display u-text u-text-default u-text-4"
                            style="margin: auto">CONTRACT LEGAL
                        </h5>
                    </div>
                </div> --}}
                {{-- <div style="cursor: pointer;"
                    class="u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-3">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-4">

                        <h5 class="u-align-center u-custom-font u-font-playfair-display u-text u-text-default u-text-5" style="margin: auto">
                            Contact</h5>
                    </div>
                </div>
                <div style="cursor: pointer;"
                    class="u-align-center u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-4">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-5">

                        <h5 class="u-custom-font u-font-playfair-display u-text u-text-default u-text-6" style="margin: auto">Setting</h5>
                    </div>
                </div>
                <div style="cursor: pointer;"
                    class="u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-5">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-6">

                        <h5 class="u-align-center u-custom-font u-font-playfair-display u-text u-text-default u-text-7" style="margin: auto">
                            Support</h5>
                    </div>
                </div>
                <div style="cursor: pointer;"
                    class="u-container-style u-list-item u-palette-5-light-2 u-repeater-item u-video-cover u-list-item-6">
                    <div class="u-container-layout u-similar-container u-valign-middle u-container-layout-7">

                        <h5 class="u-align-center u-custom-font u-font-playfair-display u-text u-text-default u-text-8" style="margin: auto">
                            Design</h5>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>




    <script type="text/javascript">
        // Change style of navbar on scroll
        window.onscroll = function() {myFunction()};
        function myFunction() {
            let navbar = document.getElementById("myNavbar");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                navbar.className = "w3-bar" + " w3-card" + " w3-animate-top" + " w3-white";
            } else {
                navbar.className = navbar.className.replace(" w3-card w3-animate-top w3-white", "");
            }
        }
        
        // Used to toggle the menu on small screens when clicking on the menu button
        function toggleFunction() {
            let x = document.getElementById("navDemo");
            if (x.className.indexOf("w3-show") == -1) {
                x.className += " w3-show";
            } else {
                x.className = x.className.replace(" w3-show", "");
            }
        }
        function goTo(uri) {
            window.location = uri
        }
    </script>

</body>

</html>