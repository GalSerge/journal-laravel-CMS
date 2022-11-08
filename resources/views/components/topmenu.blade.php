<div class="navbar-inverse navbar-fixed-top navbar-first">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <a class="navbar-brand" href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/') }}">
                    <span class="journal-maintitle">{{ __('journal.kaspy_big_title') }}</span><br>
                    <span class="journal-subtitle">{{ __('journal.kaspy_subtitle') }}</span>
                </a>
            </div>
            <div class="col-md-4 btn-locale">
                <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/search') }}"><img class="search-img" src="{{ url('/public/images/icons/search_white.svg') }}"><span class="top-word-hidden">&nbsp;{{__('search.poisk_title') }}</span></a>
                <span class="top-vl"></span>
                <a href="{{ route('setlocale', ['lang' => 'en']) }}" class="{{ App\Http\Middleware\LocaleMiddleware::getLocale() === 'en' ? 'active' : '' }}">En<span class="top-word-hidden">glish</span></a>
                <a href="{{ route('setlocale', ['lang' => 'ru']) }}" class="{{ App\Http\Middleware\LocaleMiddleware::getLocale() === null ? 'active' : '' }}">Ру<span class="top-word-hidden">сский</span></a>
            </div>
        </div>
    </div>
</div>
<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-left">
                <li class="active">
                    <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/archive/'.$current_issue['year'].'/issue/'.$current_issue['journal_number']) }}">
                        {{ __('journal.current_issue_title') }}
                    </a>
                </li>
                @foreach($pages as $page)
                    @if(!isset($page['subpages']))
                        <li><a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/'.$page['address']) }}">{{ $page['title'] }}</a></li>
                    @else
                        <li class="dropdown">
                            <a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/'.$page['address']) }}" class="dropdown-toggle disabled" data-toggle="dropdown">{{ $page['title'] }}<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                @foreach($page['subpages'] as $subpage)
                                    <li><a href="{{ url(App\Http\Middleware\LocaleMiddleware::getLocale().'/'.$subpage['address']) }}">{{ $subpage['title'] }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>
