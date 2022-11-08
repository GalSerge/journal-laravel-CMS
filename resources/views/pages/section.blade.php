@extends('page')

@section('header')
    <meta name="description" content="{{ $section['title'] }}" />
    <title>{{ $section['title'] }}</title>
@endsection

@section('content')
    <h1>{{ $section['title'] }}</h1>
    {!! $section['text'] !!}
@endsection

@section('right-section')
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection