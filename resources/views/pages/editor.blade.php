@extends('page')

@section('header')
    <meta name="description" content="{{ __('journal.editboard_title') }} | {{ $editor['surname'] }} {{ $editor['initials'] }}" />
    <title>{{ __('journal.editboard_title') }} | {{ $editor['surname'] }} {{ $editor['initials'] }}</title>
@endsection

@section('content')
    <h1>{{ $editor['surname'] }} {{ $editor['initials'] }}</h1>
    <div class="row">
        <div class="col-md-4 col-sm-12 col-xs-12">
            @if($editor['path_img'] == '')
                <img class="editor-img" src="{{ url('/public/images/icons/person.svg') }}" alt="{{ $editor['surname'] }} {{ $editor['initials'] }}">
            @else
                <img class="editor-img" src="{{ $editor['path_img'] }}" alt="{{ $editor['surname'] }} {{ $editor['initials'] }}">
            @endif
        </div>
        <div class="col-md-8 col-sm-12 col-xs-12">
            <ul class="article-meta-info">
                @if($editor['orcid_code'] != '')<li><a href="{{ $editor['orcid_code'] }}">ORCID</a></li>@endif
                <li>{{ $editor['country_city'] }}</li>
                <li>{{ $editor['university'] }}</li>
                <li>{{ $editor['academic_degree'] }}</li>
                <li>{{ $editor['post'] }}</li>
                @if($editor['scientific_interests'] != '')
                    <li><span class="item_label">{{ __('journal.scientific_interests') }} </span>{!! $editor['scientific_interests'] !!}</li>
                @endif
                @if($editor['scientific_spec'])
                    <li><span class="item_label">{{ __('journal.scientific_spec') }} </span>{!! $editor['scientific_spec'] !!}</li>
                @endif
                <li>{!! $editor['reseacher_index_full'] !!}</li>
            </ul>
        </div>
    </div>
    @if($editor['important_publics'] != '')
        <h4>{{ __('journal.important_publics') }}</h4>
        <p>{!! $editor['important_publics'] !!}</p>
    @endif
    @if($editor['grant_activities'] != '')
        <h4>{{ __('journal.grant_activities') }}</h4>
        <p>{!! $editor['grant_activities'] !!}</p>
    @endif
    @if($editor['expert_activities'] != '')
        <h4>{{ __('journal.expert_activities') }}</h4>
        <p>{!! $editor['expert_activities'] !!}</p>
    @endif
    @if($editor['more_information'] != '')
        <h4>{{ __('journal.more_information') }}</h4>
        <p>{!! $editor['more_information'] !!}</p>
    @endif
    @if($editor['contacts'] != '')
        <h4>{{ __('journal.contacts') }}</h4>
        <p>{{ $editor['contacts'] }}</p>
    @endif
@endsection

@section('right-section')
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection