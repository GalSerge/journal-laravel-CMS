@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <a href="{{ url('/admin/add-section') }}">
        <img src="{{ url('/public/images/icons/add.svg') }}">
        Добавить страницу
    </a>
    <hr>
    <ul class="parent">
        @foreach($sections as $section)
            <li class="edit-item">
                <a href="{{ url('/admin/edit-section/'.$section['section_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>&nbsp;&nbsp;
                @if($section['is_module'])
                    <img src="{{ url('/public/images/icons/module.svg') }}" title="Страница является модулем">
                @endif
                @if($section['active'])
                    <a target="_blank" href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/'.$section['address']) }}">{{ $section['title'] }}</a>
                @else
                    <span style="color: #9b9b9b;">{{ $section['title'] }}</span>
                @endif
            </li>
            @if(isset($section['subpages']))
                <ul>
                    @foreach($section['subpages'] as $subpage)
                        <li class="edit-item">
                            <a href="{{ url('/admin/edit-section/'.$subpage['section_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>&nbsp;&nbsp;
                            @if($subpage['is_module'])
                                <img src="{{ url('/public/images/icons/module.svg') }}" title="Страница является модулем">
                            @endif
                            @if($subpage['active'])
                                <a target="_blank" href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/'.$subpage['address']) }}">{{ $subpage['title'] }}</a>
                            @else
                                <span style="color: #9b9b9b;">{{ $subpage['title'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        @endforeach
    </ul>
@endsection