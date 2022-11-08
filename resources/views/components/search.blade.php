<input class="search-input" id="query_search" name="text" onkeypress="return sendEnter(event)" placeholder="{{ __('search.placeholder') }}" type="text" value="{{ $q }}" autocomplete="off" autofocus=""/>
<button class="search-button" alt="{{ __('search.search_title') }}" onclick="sendQuery(1)" title="{{ __('search.search_title') }}" type="button">
    <img src="{{ url('/public/images/icons/search.svg') }}">
</button>

<div id="search-out">
    <div id="search-res"></div>
    <div id="search-pages"></div>
</div>

