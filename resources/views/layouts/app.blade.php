<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') &ndash; {{ config('app.name', 'Laravel') }}</title>
    <meta name="description" content="@yield('description')">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?time={{ time() }}" rel="stylesheet">

@yield('header')
</head>
<body>
<div id="app">

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-5 col-sm-3 col-md-3 col-lg-2 mr-0" href="{{ url(route('home')) }}">
        <img src="{{ asset('/img/logo.png') }}" alt="{{ env('APP_NAME', 'XCP DEX') }}" class="mt-2 mr-1" /> {{ env('APP_NAME', 'XCP DEX') }}
      </a>
      <div class="col-7 col-sm-9 col-md-4 col-lg-3 p-0">
        <auto-suggest></auto-suggest>
      </div>
      <div class="col-md-5 col-lg-7">
        <ul class="nav nav-pills float-right">
          <li class="nav-item">
            <a class="nav-link px-2" href="https://t.me/xcpdex" target="_blank">
              <i class="fa fa-telegram fa-lg text-primary"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link px-3" href="https://twitter.com/xcpdex" target="_blank">
              <i class="fa fa-twitter fa-lg text-text"></i>
            </a>
          </li>
        </ul>
        <ul class="nav nav-pills">
          <li class="nav-item">
            <a class="nav-link" href="{{ url(route('home')) }}">
              Home
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url(route('markets.index')) }}">
              Markets
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url(route('projects.index')) }}">
              Projects
            </a>
          </li>
          @if (Auth::check())
          <li class="nav-item d-none d-sm-inline d-md-none d-lg-inline">
            <a href="{{ route('logout') }}" class="nav-link"
              onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>
          </li>
          @endif
        </ul>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">

        <nav class="col-md-3 col-lg-2 pb-5 d-none d-md-block bg-light sidebar">
          @yield('sidebar')
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Counterparty</span>
          </h6>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('markets.index')) }}">
                Markets
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('projects.index')) }}">
                Projects
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('orders.index')) }}">
                Orders
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('matches.index')) }}">
                Matches
              </a>
            </li>
          </ul>
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Blockchain</span>
          </h6>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('blocks.index')) }}">
                Blocks
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('mempool.index')) }}">
                Mempool
              </a>
            </li>
          </ul>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          @yield('content')
          <hr class="mt-4 mb-4" />
          <p class="text-center">
            <i class="fa fa-envelope text-secondary"></i> <a href="mailto:info@xcpdex.com" target="_blank">Contact</a>
            <i class="fa fa-github ml-3 text-secondary d-none d-sm-inline"></i> <a href="https://github.com/xcpdex/xcpdex" class="d-none d-sm-inline" target="_blank">Github</a>
            <i class="fa fa-telegram ml-3 text-secondary"></i> <a href="https://t.me/xcpdex" target="_blank">Telegram</a>
            <i class="fa fa-twitter ml-3 text-secondary"></i> <a href="https://twitter.com/xcpdex" target="_blank">Twitter</a>
          </p>
          <p class="text-center">
              <a href="https://xcpdex.com/disclaimer">Disclaimer</a>
              <a href="https://xcpdex.com/privacy" class="ml-3">Privacy Policy</a>
              <a href="https://xcpdex.com/terms" class="ml-3">Terms of Use</a>
          </p>
        </main>

      </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}?time={{ time() }}"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112477384-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{ env('GA_TRACKING_ID') }}');
</script>
</body>
</html>
