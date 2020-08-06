<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117674626-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-117674626-1');
    </script>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">

                <a class="navbar-brand" href="{{ url('/') }}">
                    <img class="d-none d-lg-block" src="/images/dykkeprat.png">
                    <img class="d-lg-none" width="150" src="/images/dykkeprat.png">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="btn btn-primary" href="https://www.facebook.com/groups/dykkeprat"
                                role="button">Dykkeprat på Facebook</a>
                        </li>
                        <li class="nav-item">
                            <search class="d-md-none w-100 mt-2" tag="{{ $search_tag_placeholder }}"></search>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="py-4 bg-dykkeprat d-none d-md-block">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-2">
                        <a href="{{ url('/') }}"><img src="/images/dykkeprat_logo.png"></a>
                    </div>
                    <div class="col-10">
                        <search class="mt-3 w-75 position-absolute position-search-lg" tag="{{ $search_tag_placeholder }}"></search>
                        <p style="margin-top: 4.5rem !important" class="lead text-white d-none d-md-block">Forumet ble
                            <a style="color: whitesmoke !important; text-decoration: underline;"
                                href="{{ '/forum/posts/5485' }}">stengt</a> i oktober 2015. Her kan du søke i alle
                            tråder og innlegg fra 2009 til 2015.</p>
                    </div>
                </div>
            </div>
        </div>

        <main class="py-4">
            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('includes.footer')
    </div>
</body>

</html>
