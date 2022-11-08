@extends('page')

@section('header')
    <meta name="description" content="{{ __('journal.editboard_title') }}" />
    <title>{{ __('journal.editboard_title') }}</title>
@endsection

@section('content')
    <h1>{{ __('journal.editboard_title') }}</h1>
    <h3>{{ __('journal.main_editor_title') }}</h3>
    @if(isset($editors['main']))
        <div class="row">
            <div class="col-md-3 col-sm-12 col-xs-12">
                @if($editors['main']['path_img'] == '')
                    <img class="editor-img" src="{{ url('/public/images/icons/person.svg') }}" alt="{{ $editors['main']['surname'] }} {{ $editors['main']['initials'] }}">
                @else
                    <img class="editor-img" src="{{ $editors['main']['path_img'] }}" alt="{{ $editors['main']['surname'] }} {{ $editors['main']['initials'] }}">
                @endif
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                <h4><a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/editorial-board/'.$editors['main']['editor_id']) }}">{{ $editors['main']['surname'] }} {{ $editors['main']['initials'] }}</a></h4>
                <ul class="article-meta-info">
                    <li>{{ $editors['main']['country_city'] }}</li>
                    <li>{{ $editors['main']['academic_degree'] }}</li>
                    @if($editors['main']['orcid_code'] != '')
                        <li><a href="{{ $editors['main']['orcid_code'] }}">ORCID</a></li>
                    @endif
                </ul>
            </div>
        </div>
    @endif
    <hr>
    <h3>{{ __('journal.editors_council') }}</h3>
    @foreach($editors['all'] as $editor)
        @if($editor['active'])
            <div class="row editor-row">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    @if($editor['path_img'] == '')
                        <img class="editor-img" src="{{ url('/public/images/icons/person.svg') }}" alt="{{ $editor['surname'] }} {{ $editor['initials'] }}">
                    @else
                        <img class="editor-img" src="{{ $editor['path_img'] }}" alt="{{ $editor['surname'] }} {{ $editor['initials'] }}">
                    @endif
                </div>
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <h4><a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/editorial-board/'.$editor['editor_id']) }}">{{ $editor['surname'] }} {{ $editor['initials'] }}</a></h4>
                    <ul class="article-meta-info">
                        <li>{{ $editor['country_city'] }}</li>
                        <li>{{ $editor['academic_degree'] }}</li>
                        @if($editor['orcid_code'] != '')
                            <li><a href="{{ $editor['orcid_code'] }}">ORCID</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        @endif
    @endforeach
@endsection

@section('right-section')
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection