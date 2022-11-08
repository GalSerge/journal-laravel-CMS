<!doctype html>
    <html lang="{{ App::currentLocale() }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <link rel="stylesheet" href={{ asset("css/bootstrap.css") }}>
        <link rel="stylesheet" href={{ asset("css/admin.css") }}>
        <link rel="icon" href="https://asu.edu.ru/templates/aspunew/image/asu-logo.png" type="image/svg+xml">
        <meta name="description" content="{{ $title }}" />
        <script src="/js/ckeditor/ckeditor.js" type="text/javascript" charset="utf-8" ></script>
        <title>{{ $title }}</title>
    </head>
    <body>
    <div id="wrapper">
        @include('admin.menu')

        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="admin-name">
                        <b>{{ session('user')['fname'].' '.session('user')['sname'].' ('.session('user')['username'].')' }}</b>
                    </div>
                    <div class="col-lg-12 content">
                        @if (session('msg'))
                            <div class="alert alert-warning fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                                <strong>{{ session('msg')}}</strong>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src={{ asset("js/bootstrap.min.js") }}></script>
    <script src={{ asset("js/app.js") }}></script>
    </body>
</html>