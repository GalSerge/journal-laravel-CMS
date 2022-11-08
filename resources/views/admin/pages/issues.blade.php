@extends('admin.page')

@section('content')
    <h1>Выпуски</h1>
    <a href="{{ url('/admin/add-issue') }}">
        <img src="{{ url('/public/images/icons/add.svg') }}">
        Добавить выпуск
    </a>
    @foreach($archive as $year => $issues)
            <h4>{{ $year }}</h4>
            <ul class="parent">
                @foreach($issues as $issue)
                    <li class="edit-item">
                        <a href="{{ url('/admin/edit-issue/'.$issue['issue_id']) }}"><img src="{{ url('/public/images/icons/edit.svg') }}" title="Редактировать"></a>
                            No. {{ $issue['journal_number'] }}
                            @if(isset($issue['alt_number']) && $issue['alt_number'] != 0)
                                ({{ $issue['alt_number'] }})
                            @endif
                        <a href="##" onClick='getArticles({{ $issue['issue_id'] }})'>Статьи</a>&nbsp;&nbsp;
                        <a href="{{ url('/admin/add-article/'.$issue['issue_id']) }}"><img src="{{ url('/public/images/icons/add.svg') }}">&nbsp;Добавить статью</a>
                        <div id="art-list-{{ $issue['issue_id'] }}"></div>
                    </li>
                @endforeach
            </ul>
            @if(!$loop->last)
                <hr>
            @endif
    @endforeach
@endsection