<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('public/template/education.png')}}">
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style926d.css') }}">
</head>
<body class="nk-body" data-sidebar-collapse="lg" data-navbar-collapse="lg">
    <div id="app">
        <div class="nk-app-root">
            <div class="nk-main"> 
        </div>
        <div class="nk-wrap">
            @yield('content')
            <div class="nk-footer">
            <div class="container-fluid">
              <div class="nk-footer-wrap">
               <div class="nk-footer-copyright"> &copy; 2024 SifuTutor.
                <div class="nk-footer-links">
                  <ul class="nav nav-sm">
                    <li class="nav-item">
                      <a href="#" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Support</a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">Blog</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
            </div>
        </div>
    </div>
    </body>
  <script src="{{ asset('template/assets/js/bundle.js') }}"></script>
  <script src="{{ asset('template/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('template/assets/js/charts/analytics-chart.js') }}"></script>
</html>
