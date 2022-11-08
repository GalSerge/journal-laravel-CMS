<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">{{ $author['surname'] }} {{ $author['initials'] }}</h4>
    </div>
    <div class="modal-body">
        <ul class="article-meta-info">
            @if($author['orcid_code'] != '')
                <li><a href="https://orcid.org/{{ $author['orcid_code'] }}">ORCID</a></li>
            @endif
            <li>{{ $author['academic_degree'] }}</li>
            <li>{{ $author['city'] }}</li>
            <li>{{ $author['university'] }}</li>
            <li>{{ $author['post'] }}</li>
            <li>{{ $author['email'] }}</li>
        </ul>
        <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/search?q='.$author['surname'].' '.$author['initials']) }}">{{ __('journal.search_author') }}</a>
    </div>
</div>
