<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js" defer></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"
        defer></script> --}}

    {{-- <script src="https://unpkg.com/axios/dist/axios.min.js" defer></script> --}}
    <script src="{{asset('assets/js/Sortable/Sortable.min.js')}}" referrerpolicy="no-referrer"></script>
    <script src="{{asset('assets/js/axios/axios.min.js')}}" defer></script>
    <script src="{{ asset('assets/js/template/app.js') }}" defer></script>
    <script src="{{ asset('assets/js/api/index.js') }}" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10" defer></script> --}}
    <script src="{{asset('assets/js/sweetalert2/sweetalert2.all.min.js')}}" defer></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" defer></script> --}}
    <script src="{{asset('assets/js/select2/select2.min.js')}}" defer></script>
    {{-- <script src="{{ asset('assets/js/index.js') }}" defer></script> --}}
    @yield('first-script')
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Sarabun" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/index.css') }}" rel="stylesheet">
    {{--
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="{{asset('assets/css/select2.min.css')}}">
    {{--
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    --}}
    <link rel="stylesheet" href="{{asset('assets/css/font-awesome.min.css')}}">
    {{--
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/css/OverlayScrollbars.css"
        integrity="sha512-Ho1L8FTfzcVPAlvfkL1BV/Lmy1JDUVAP82/LkhmKbRX5PnQ7CNDHAUp2GZe7ybBpovS+ssJDf+SlBOswrpFr8g=="
        crossorigin="anonymous" /> --}}
    <link rel="stylesheet" href="{{asset('assets/css/OverlayScrollbars.css')}}">
    @yield('style')
</head>

<body>
    <div id="app" class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        @include('includes.navbar.navigationbar')
        <div class="app-main">
            @yield('sidebar')
            <div class="app-main__outer">
                @include('partials.alerts')
                <div class="app-main__inner">
                    @yield('content')
                </div>
                {{-- @include('includes.footer') --}}
            </div>
        </div>

    </div>
    <div id="loading"></div>
    <div id="toast-container" class="toast-top-right">
    </div>
    @yield('modal')
    <!-- JS, Popper.js, and jQuery -->
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script> --}}
    <script src="{{asset('assets/js/jquery3.5.1/jquery-3.5.1.slim.min.js')}}"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.1/js/OverlayScrollbars.min.js"
        integrity="sha512-B1xv1CqZlvaOobTbSiJWbRO2iM0iii3wQ/LWnXWJJxKfvIRRJa910sVmyZeOrvI854sLDsFCuFHh4urASj+qgw=="
        crossorigin="anonymous"></script> --}}
    <script src="{{asset('assets/js/overlayscrollbars/1.13.1/OverlayScrollbars.min.js')}}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script> --}}

    <script src="{{asset('assets/js/popper/1.16.0/popper.min.js')}}"></script>
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script> --}}
    <script src="{{asset('assets/js/bootstrap4.5.0/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/index.js')}}"></script>
    <script defer>
        var locale = {!! json_encode(session('locale')) !!};
    </script>

    @yield('second-script')
</body>

</html>
