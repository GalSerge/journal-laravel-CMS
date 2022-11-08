<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/images/asu_logo.png" type="image/svg+xml">
    <link rel="stylesheet" href={{ asset("css/bootstrap.css") }}>
    <link rel="stylesheet" href={{ asset("css/admin.css") }}>
    <title>Панель администрования</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-body">
                        <div class="admin-enter-title">
                            <a href="https://asu.edu.ru"><img class="admin-enter-logo" src="/images/asu_logo.png"></a>
                            <p>Журнал Каспийский регион</p>
                        </div>
                        <form method="post" action="{{route('loginAction')}}">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Логин" name="username" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Пароль" name="password" type="password" >
                                </div>
                                <p style="color: red">{{session('msg')}}</p>
                                @if(session('attempts')>=4)
                                    <script src="https://www.google.com/recaptcha/api.js"></script>
                                    <div class="g-recaptcha" data-sitekey="6LcRgSEUAAAAABAkFqUeLnmug4zc-7ym_OV9ANnU"></div>
                                @endif
                                <input type="submit" class="btn btn-lg btn-success btn-block" value="Войти">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>