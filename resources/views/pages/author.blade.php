@extends('page')

@section('header')
    <meta name="description" content="{{ __('journal.about_article') }} | {{ $author['surname'] }} {{ $author['initials'] }}" />
    <title>{{ __('journal.about_article') }} | {{ $author['surname'] }} {{ $author['initials'] }}</title>
@endsection

@section('content')
    <h1>{{ $author['surname'] }} {{ $author['initials'] }}</h1>
    <ul class="article-meta-info">
        <li>{{ $author['academic_degree'] }}</li>
        <li>{{ $author['university'] }}</li>
    </ul>
@endsection

@section('right-section')
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection