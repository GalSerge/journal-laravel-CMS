<!doctype html>
<html lang="{{ App::currentLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/images/asu_logo.png" type="image/svg+xml">
    <link rel="stylesheet" href={{ asset("css/bootstrap.css") }}>
    <link rel="stylesheet" href={{ asset("css/main.css") }}>
    
    <meta name="description" content="{{ __('journal.cover_alt') }}" />
    <title>{{ __('journal.cover_alt') }}</title>
</head>
<body>
@include('components.topmenu')
<div class="mainpage-head">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12">
                <img style="width: 90%;" src="{{ url('/public/images/journal-cover.jpg') }}" alt="Научный журнал КАСПИЙСКИЙ РЕГИОН">
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                {{ __('journal.about') }}
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-sm-12 col-xs-12">
            <div class="card">
                @include('components.currentissue')
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12">
            <div class="card">
                @include('components.indexcard')
            </div>
            <div class="card">
                @include('components.popularcard')
            </div>
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
<script src={{ asset("js/bootstrap.min.js") }}></script>
<script src={{ asset("js/app.js") }}></script>
</body>
</html>