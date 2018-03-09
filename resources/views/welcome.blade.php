<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>XCP DEX</title>

    <!-- Styles -->
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <!-- Styles -->
    <style>
        html {
            background-color: #343a40;
        }

        body {
            background-color: rgba(0, 0, 0, 0.25);
            color: #FFF;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 68px;
            font-weight: bold;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                XCP DEX
            </div>
            <div class="links">
                <a href="https://twitter.com/xcpdex" class="btn btn-primary" target="_blank"><i class="fa fa-twitter"></i> Follow @xcpdex</a>
                <a href="https://t.me/xcpdex" class="btn btn-primary ml-1" target="_blank"><i class="fa fa-telegram"></i> Join Telegram</a>
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