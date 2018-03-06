<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') &ndash; {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

@yield('header')
</head>
<body>
<div id="app">

    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="{{ url('/') }}">
        <img src="{{ asset('/img/logo.png') }}" alt="{{ env('APP_NAME') }}" class="mt-2 mr-1" /> {{ env('APP_NAME') }}
      </a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      @if(Auth::check())
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a href="{{ route('logout') }}" class="nav-link" 
            onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
            {{ __('Signout') }}
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
          </form>
        </li>
      </ul>
      @else
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a href="https://t.me/xcpdex" class="nav-link" target="_blank">
            {{ __('Telegram') }}
          </a>
        </li>
      </ul>
      @endif
    </nav>

    <div class="container-fluid">
      <div class="row">

        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          @yield('sidebar')
          <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Counterparty</span>
          </h6>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('assets.index')) }}">
                Assets
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('blocks.index')) }}">
                Blocks
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('markets.index')) }}">
                Markets
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link" href="{{ url(route('matches.index')) }}">
                Matches
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="{{ url(route('orders.index')) }}">
                Orders
              </a>
             </li>
          </ul>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          @yield('content')
          <hr class="mt-4 mb-4" />
          <p class="text-center">
            <i class="fa fa-envelope text-secondary"></i> <a href="mailto:info@xcpdex.com" target="_blank">Contact</a>
            <i class="fa fa-github ml-3 text-secondary"></i> <a href="https://github.com/xcpdex/xcpdex" target="_blank">Github</a>
            <i class="fa fa-telegram ml-3 text-secondary"></i> <a href="https://t.me/xcpdex" target="_blank">Telegram</a>
            <i class="fa fa-twitter ml-3 text-secondary"></i> <a href="https://twitter.com/xcpdex" target="_blank">Twitter</a>
          </p>
          <p class="text-center">
              <a href="https://xcpdex.com/privacy">Privacy Policy</a>
              <a href="https://xcpdex.com/terms" class="ml-3">Terms of Use</a>
          </p>
        </main>

      </div>
    </div>

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
