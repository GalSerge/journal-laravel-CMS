<div id="search-res">
    <div class="tab">
        @foreach($answer as $cat => $a)
            @if ($loop->first)
                <button class="tablinks search-cat-tab search-cat-tab-active" onclick="openTab(event, '{{ $cat }}', 'tabcontent', 'search-cat-tab-active')">{{ __('search.'.$cat) }}</button>
            @else
                <button class="tablinks search-cat-tab" onclick="openTab(event, '{{ $cat }}', 'tabcontent', 'search-cat-tab-active')">{{ __('search.'.$cat) }}</button>
            @endif
        @endforeach
    </div>
    @foreach($answer as $cat => $a)
        <div id="{{ $cat }}" class="tabcontent" style="display: block;">
            @foreach($a as $article)
                <div class="issue-article">
                    <h4>
                        <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$article['year'].'/issue/'.$article['journal_number'].'/article/'.$article['article_id']) }}" target="_blank">
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
                            <span class="search-issue">
                                <span class="search-issue-word">{{ __('journal.issue') }}</span> {{ $article['year'] }} No. {{ $article['journal_number'] }}
                                @if(isset($article['alt_number']) && $article['alt_number'] != 0)
                                    ({{ $article['alt_number'] }})
                                @endif
                            </span>
                            <span class="search-article-pages"><img src="{{ url('/public/images/icons/pages.svg') }}">{{ $article['pages'] }}</span>
                        </div>
                    </div>
                    @if($article['annotation'] != '')
                        <p class="annotation" id="{{'annot'.$loop->parent->index.'-'.$loop->index}}">
                            {{ mb_substr($article['annotation'], 0, 300) }}<span style="display:inline" id="dots">...</span><span style="display:none" id="more">{{ mb_substr($article['annotation'], 300) }}</span><br><a href="##" onClick="readMore('{{'annot'.$loop->parent->index.'-'.$loop->index}}')" id="readBtn">{{ __('journal.read_more_button') }}</a>
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>
<div id="search-pages">
    @if($full_size > 30)
        @php
            $start_page = max(1, $cur_page-3);
            $end_page = min($cur_page+3, ceil($full_size / 30));
        @endphp
        @for($i = $start_page; $i <= $end_page; $i++)
            @if($i == $start_page && $i != 1)
                <span class="search-page-num"><a href="##" onclick="sendQuery({{ $cur_page-1 }})">{{ __('search.preview') }}</a> ...</span>
            @endif

            @if($i == $cur_page)
                <span class="search-page-num">{{ $i }}</span>
            @else
                <span class="search-page-num"><a href="##" onclick="sendQuery({{ $i }})">{{ $i }}</a></span>
            @endif

            @if($i == $end_page && $i != ceil($full_size / 30))
                <span class="search-page-num">... <a href="##" onclick="sendQuery({{ $cur_page+1 }})">{{ __('search.next') }}</a></span>
            @endif
        @endfor
    @endif
</div>
