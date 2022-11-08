@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <a href="{{ url('/admin/add-editor') }}">
        <img src="{{ url('/public/images/icons/add.svg') }}">
        Добавить редактора
    </a>
    <h3>Главный редактор</h3>
        <span>
            <a href="{{ url('/admin/edit-editor/'.$editors['main']['editor_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>
            &nbsp;&nbsp;{{ $editors['main']['surname'] }} {{ $editors['main']['initials'] }}
        </span>
    <hr>
    <h3>Редсовет</h3>
    <ul class="parent">
        @foreach($editors['all'] as $editor)
            <li class="edit-item">
                <a href="{{ url('/admin/edit-editor/'.$editor['editor_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>
                @if($editor['active'])
                    &nbsp;&nbsp;{{ $editor['surname'] }} {{ $editor['initials'] }}
                @else
                    <span style="color: #9b9b9b;">&nbsp;&nbsp;{{ $editor['surname'] }} {{ $editor['initials'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endsection