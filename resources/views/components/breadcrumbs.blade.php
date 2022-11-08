<div class="row">
    <div class="col-md-12 breadcrumbs">
        <a href="/" title="">Главная</a><span>/</span>
        @foreach ($breadcrumbs as $page)
            <a href="{{ $page['link'] }}" title="">{{ $page['title'] }}</a><span>/</span>
        @endforeach
    </div>
</div>