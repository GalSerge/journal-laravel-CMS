@extends('page')

@section('header')
    <meta name="description" content="{{ __('journal.archive_title') }}" />
    <title>{{ __('journal.archive_title') }}</title>
@endsection

@section('content')
    <h1>{{ __('journal.archive_title') }}</h1>
    @foreach($archive as $year => $issues)
        <div class="archive-year">
            <h4>{{ $year }}</h4>
            @foreach($issues as $issue)
                <span><a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$year.'/issue/'.$issue['journal_number']) }}">
                        No. {{ $issue['journal_number'] }}
                        @if(isset($issue['alt_number']) && $issue['alt_number'] != 0)
                            ({{ $issue['alt_number'] }})
                        @endif
                    </a>
                </span>
            @endforeach
            @if(!$loop->last)
                <hr>
            @endif
        </div>
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