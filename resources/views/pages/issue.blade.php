@extends('page')

@php
    $issue_number = __('journal.issue').' '.$issue['year'].' No. '.$issue['journal_number'];
    if (isset($issue['alt_number']) && $issue['alt_number'] != 0)
        $issue_number .= ' ('.$issue['alt_number'].')';
@endphp

@section('header')
    <meta name="description" content="{{ $issue_number }}" />
    <title>{{ $issue_number }}</title>
@endsection

@section('content')
    <h1>{{ __('journal.issue') }} {{ $issue['year'] }}
        No. {{ $issue['journal_number'] }}
        @if(isset($issue['alt_number']) && $issue['alt_number'] != 0)
            ({{ $issue['alt_number'] }})
        @endif
    </h1>
    @foreach($issue['articles'] as $subject)
            <h3>{{ $subject['subject_title'] }}</h3>
            @foreach($subject['articles_list'] as $article)
                <div class="issue-article">
                    <h4>
                        <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$issue['year'].'/issue/'.$issue['journal_number'].'/article/'.$article['article_id']) }}">
                            {{ $article['title'] }}
                        </a>
                    </h4>
                    <span class="authors-under-title">
                        @foreach($article['authors'] as $author)
                                @if(!$loop->last)
                                    @php echo trim($author['surname']).' '.trim($author['initials']).', '; @endphp
                                @else
                                    @php echo trim($author['surname']).' '.trim($author['initials']); @endphp
                                @endif
                        @endforeach
                    </span><br>
                    <a class="doi-under-title" href="https://doi.org/{{ $article['doi_code'] }}">{{ $article['doi_code'] }}</a>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4 issue-article-pdf">
                            <span>
                                @if ($article['path_pdf'] == '')
                                    <a class="pdf-button pdf-button-disabled">PDF</a>
                                @else
                                    <a class="pdf-button pdf-button-active" href="{{  Storage::url( $article['path_pdf'] ) }}" target="_blank">PDF</a>
                                @endif
                            </span>
                        </div>
                        <div class="col-md-8 col-sm-8 col-xs-8 issue-article-views">
                            <img src="{{ url('/public/images/icons/pages.svg') }}">
                            <span>{{ $article['pages'] }}</span>
                            &nbsp;
                            <img src="{{ url('/public/images/icons/views.svg') }}">
                            <span>{{ $article['views'] }}</span>
                        </div>
                    </div>
                    @if($article['annotation'] != '')
                        <p class="annotation" id="{{'annot'.$loop->parent->index.'-'.$loop->index}}">
                            {{ mb_substr($article['annotation'], 0, 300) }}<span style="display:inline" id="dots">...</span><span style="display:none" id="more">{{ mb_substr($article['annotation'], 300) }}</span><br><a href="##" onClick="readMore('{{'annot'.$loop->parent->index.'-'.$loop->index}}')" id="readBtn">{{ __('journal.read_more_button') }}</a>
                        </p>
                    @endif
                </div>
            @endforeach
            @if(!$loop->last)
                <hr>
            @endif
    @endforeach
@endsection

@section('right-section')
    <div class="card card-info">
        <img class="article-cover" src="{{ url('/public/images/journal-cover.jpg') }}" alt="{{ __('journal.cover_alt') }}">
        <br>
        {{--@if ($issue['path_pdf'] == '')
            <a class="pdf-button pdf-button-disabled">PDF</a>
        @else
            <a class="pdf-button pdf-button-active" href="{{  Storage::url( $issue['path_pdf'] ) }}" target="_blank">PDF</a>
        @endif--}}
        @if($issue['doi_code'] != '' ||
            $issue['published'] != '0000-00-00')
            <h4>{{ __('journal.issue_info') }}</h4>
            <ul class="article-meta-info">
                @if($issue['doi_code'] != '')
                    <li><span class="item_label">DOI: </span><a href="https://doi.org/{{ $issue['doi_code'] }}">{{ $issue['doi_code'] }}</a></li>
                @endif
                @if($issue['published'] != '0000-00-00')
                    <li><span class="item_label">{{ __('journal.date_published') }} </span>{{ $issue['published'] }}</li>
                @endif
            </ul>
        @endif
    </div>
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection