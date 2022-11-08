<h1>{{ __('journal.current_issue_title') }} {{ $current_issue['year'] }}
    No. {{ $current_issue['journal_number'] }}
    @if(isset($current_issue['alt_number']) && $current_issue['alt_number'] != 0)
        ({{ $current_issue['alt_number'] }})
    @endif
</h1>
@foreach($current_issue['articles'] as $subject)
    <h3>{{ $subject['subject_title'] }}</h3>
    @foreach($subject['articles_list'] as $article)
        <div class="issue-article">
            <h4>
                <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$current_issue['year'].'/issue/'.$current_issue['journal_number'].'/article/'.$article['article_id']) }}">
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