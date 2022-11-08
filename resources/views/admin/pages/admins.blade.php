@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <a href="{{ url('/admin/add-admin') }}">
        <img src="{{ url('/public/images/icons/add.svg') }}">
        Добавить администратора
    </a><hr>
    <ul class="parent">
        @foreach($users as $user)
            <li class="edit-item">
                <a href="{{ url('/admin/edit-admin/'.$user['user_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>
                @if($user['active'])
                    &nbsp;&nbsp;{{ $user['sname'] }} {{ $user['fname'] }}
                @else
                    <span style="color: #9b9b9b;">&nbsp;&nbsp;{{ $user['sname'] }} {{ $user['fname'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection