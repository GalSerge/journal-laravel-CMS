@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            Фамилия:
            <input type="text" name="sname" value="{{ $user['sname'] ?? '' }}" class="form-control" required>
        </div>
        <div class="form-group">
            Имя:
            <input type="text" name="fname" value="{{ $user['fname'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            E-mail:
            <input type="text" name="email" value="{{ $user['email'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Логин:
            <input type="text" name="username" value="{{ $user['username'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Пароль:
            <input type="text" name="password" value="" class="form-control">
        </div>
        <div class="form-group">
            Подтвердить рароль:
            <input type="text" name="password2" value="" class="form-control">
        </div>
        <div class="form-group">
            @if(isset($user['active']) && $user['active'])
                <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault">
            @endif
            Имеет доступ к редактированию
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
@endsection

