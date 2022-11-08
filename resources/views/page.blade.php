<!doctype html>
<html lang="{{ App::currentLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/images/asu_logo.png" type="image/svg+xml">
    <link rel="stylesheet" href={{ asset("css/bootstrap.css") }}>
    <link rel="stylesheet" href={{ asset("css/main.css") }}>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <script src={{ asset("js/app.js?v=".time()) }}></script>
    @yield('header')
</head>
<body>
@include('components.topmenu')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                @yield('content')
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            @yield('right-section')
{{--            @if(Request::url() === 'search')--}}
{{--                // code--}}
{{--            @endif--}}
{{--            @if(isset($page) && $page['title'] == 'Поиск')--}}
{{--                @yield('right-section2')--}}
{{--            @endif--}}
        </div>
    </div>
</div>
<div class="footer-bottom">
    <div class="container">
        <div class="left">
            {!! __('journal.footer_info') !!}
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src={{ asset("/js/bootstrap.min.js") }}></script>
</body>
</html>