@extends('page')

@section('header')
    <meta name="description" content="{{ $article['title'] }}" />
    <title>{{ $article['title'] }}</title>
@endsection

@section('content')
    <h1>{{ $article['title'] }}</h1>
    <span class="authors-under-title">
        @foreach($article['authors'] as $author)
            @if(!$loop->last)
                <a href="{{'#AuthorModal'.$loop->index}}" data-toggle="modal">
                    @php echo trim($author['surname']).' '.trim($author['initials']).', '; @endphp
                </a>
            @else
                <a href="{{'#AuthorModal'.$loop->index}}" data-toggle="modal">
                    @php echo trim($author['surname']).' '.trim($author['initials']); @endphp
                </a>
            @endif
        @endforeach
    </span><br>
    <p><a class="doi-under-title" href="https://doi.org/{{ $article['doi_code'] }}">{{ $article['doi_code'] }}</a></p>
    @if($article['annotation'] != '')
        <h4>{{ __('journal.annotation') }}</h4>
        <p>{{ $article['annotation'] }}</p>
    @endif
    @if(!empty($article['keywords']))
        <h4>{{ __('journal.keywords') }}</h4>
        <ul class="article-keywords-list">
            @foreach($article['keywords'] as $word)
                <li>
                    <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/search?q='.$word) }}">{{ $word }}</a>
                </li>
            @endforeach
            <div class="keywords-info">{{ __('journal.keywords_info') }}</div>
        </ul>
    @endif
    @if($article['authors_contribution'] != '')
        <h4>{{ __('journal.authors_contribution') }}</h4>
        <p>
            {{ $article['authors_contribution'] }}
        </p>
    @endif
@endsection

@section('right-section')
    <div class="card card-info">
        <img class="article-cover" src="{{ url('/public/images/journal-cover.jpg') }}" alt="{{ __('journal.cover_alt') }}">
        <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$article['year'].'/'.'issue/'.$article['journal_number']) }}">
            <h3>
                {{ __('journal.issue') }} {{ $article['year'] }}
                No. {{ $article['journal_number'] }}
                @if(isset($article['alt_number']) && $article['alt_number'] != 0)
                    ({{ $article['alt_number'] }})
                @endif
            </h3>
        </a>
        
        @if ($article['path_pdf'] == '')
            <a class="pdf-button pdf-button-disabled">PDF</a>
        @else
            <a class="pdf-button pdf-button-active" href="{{  Storage::url( $article['path_pdf'] ) }}" target="_blank">PDF</a>
        @endif
        <ul class="article-meta-info">
            <li><span class="item_label">{{ __('journal.views') }} </span>{{ $article['views'] }}</li>
            {{--<li><span class="item_label">{{ __('journal.downloads') }} </span>{{ $article['downloads'] }}</li>--}}

        </ul>
        @if($article['doi_code'] != '' || $article['doi_code'] != '')
            <h4>{{ __('journal.article_info') }}</h4>
            <ul class="article-meta-info">
                @if($article['doi_code'] != '')
                    <li><span class="item_label">DOI: </span><a href="https://doi.org/{{ $article['doi_code'] }}">{{ $article['doi_code'] }}</a></li>
                @endif
                @if($article['udk_code'] != '')
                    <li><span class="item_label">{{ __('journal.udk_upper') }} </span>{{ $article['udk_code'] }}</li>
                @endif
            </ul>
        @endif
        @if($article['submitted'] != '0000-00-00' ||
            $article['approved'] != '0000-00-00' ||
            $article['accepted'] != '0000-00-00' ||
            $article['published'] != '0000-00-00')
            <h4>{{ __('journal.history_article') }}</h4>
            <ul class="article-meta-info">
                @if($article['submitted'] != '0000-00-00')
                    <li><span class="item_label">{{ __('journal.history_submitted') }} </span>{{ $article['submitted'] }}</li>
                @endif
                @if($article['approved'] != '0000-00-00')
                    <li><span class="item_label">{{ __('journal.history_approved') }} </span>{{ $article['approved'] }}</li>
                @endif
                @if($article['accepted'] != '0000-00-00')
                    <li><span class="item_label">{{ __('journal.history_accepted') }} </span>{{ $article['accepted'] }}</li>
                @endif
                @if($article['published'] != '0000-00-00')
                    <li><span class="item_label">{{ __('journal.history_published') }} </span>{{ $article['published'] }}</li>
                @endif
            </ul>
        @endif
        <h4>{{ __('journal.authors') }}</h4>
        <ul class="article-meta-info">
            @foreach($article['authors'] as $author)
                <li>
                    <a href="{{'#AuthorModal'.$loop->index}}" data-toggle="modal">
                        {{ $author['surname']}} {{ $author['initials'] }}
                    </a>
                    <div id="{{'AuthorModal'.$loop->index}}" class="modal fade">
                        <div class="modal-dialog">
                            @include('components.authormodal')
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        @if($article['quotation'])
            <h4>{{ __('journal.for_quotation') }}&nbsp;<button class="btn-copy" href="##" onclick="copyText('quot')" title="{{ __('journal.copy_button') }}"><img src="{{ url('/public/images/icons/copy.svg') }}"></button></h4>
            <p id="quot">{{ $article['quotation'] }}</p>
        @endif
        @if(!empty($article['references']))
            <h4>{{ __('journal.references') }}</h4>
            <ol class="references-list">
                @foreach($article['references'] as $ref)
                    <li>
                        {{ $ref }}
                    </li>
                @endforeach
            </ol>
        @endif
        <h4>{{ __('journal.license_title') }}</h4>
        {!! __('journal.license_text') !!}
    </div>
    <div class="card">
        @include('components.indexcard')
    </div>
    <div class="card">
        @include('components.popularcard')
    </div>
@endsection