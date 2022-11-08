<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="/admin">
                <span class="big-title">{{ __('journal.kaspy_big_title') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ url('/admin/xml') }}">Добавить выпуск XML</a>
        </li>
        <li>
            <a href="{{ url('/admin/issues') }}">Выпуски</a>
        </li>
        <li>
            <a href="{{ url('/admin/editors') }}">Редакторы</a>
        </li>
        <li>
            <a href="{{ url('/admin/sections') }}">Страницы</a>
        </li>
        <li>
            <a href="{{ url('/admin/admins') }}">Администраторы</a>
        </li>
        <li class="exit">
            <a href="{{ url('/admin/logout') }}">Выход</a>
        </li>
    </ul>
</div>

