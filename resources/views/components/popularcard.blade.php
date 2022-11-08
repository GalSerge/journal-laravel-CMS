<h4>{{ __('journal.popular') }}</h4>
@foreach($popular_articles as $article)
    <div class="popular-articles">
        <span class="popular-title">
            <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$article['year'].'/issue/'.$article['journal_number'].'/article/'.$article['article_id']) }}" target="_blank">
                {{ $article['title'] }}
            </a>
        </span>
        <span class="popular-issue">
            {{ __('journal.issue') }} {{ $article['year'] }} No. {{ $article['journal_number'] }}
            @if(isset($article['alt_number']) && $article['alt_number'] != 0)
                ({{ $article['alt_number'] }})
            @endif
        </span>
        @if(!$loop->last)
            <hr>
        @endif
    </div>
@endforeach