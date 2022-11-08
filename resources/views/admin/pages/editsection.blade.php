@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
        @csrf
        {{ $section_rus[''] ?? '' }}
        <div class="form-group">
            Адрес:
            <input type="text" name="address" value="{{ $section_rus['address'] ?? '' }}" class="form-control" required>
        </div>
        <div class="form-group">
            @if(isset($section_rus['is_module']) && $section_rus['is_module'])
                <input class="form-check-input" type="checkbox" name="is_module" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="is_module" value="1" id="flexCheckDefault">
            @endif
            Страница является модулем
        </div>

        <h3>Версия на русском</h3>
        <input type="hidden" name="section_rus[lang_id]" value="1">
        <div class="form-group">
            Заголовок:
            <input type="text" name="section_rus[title]" value="{{ $section_rus['title'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Описание:
            <input type="text" name="section_rus[description]" value="{{ $section_rus['description'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Содержимое страницы (не отображается, если страница является модулем):
            <textarea name="section_rus[text]" id="content_rus">{{ $section_rus['text'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            Родительский раздел:
            <select class="form-control" name="section_rus[parent_id]">
                @foreach($parents_rus as $parent)
                    @if(isset($section_rus['parent_id']) && $parent['section_id'] == $section_rus['parent_id'])
                        <option value="{{ $parent['section_id'] }}" selected>{{ $parent['title'] }}</option>
                    @else
                        <option value="{{ $parent['section_id'] }}">{{ $parent['title'] }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            @if(isset($section_rus['show_in_menu']) && $section_rus['show_in_menu'])
                <input class="form-check-input" type="checkbox" name="section_rus[show_in_menu]" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="section_rus[show_in_menu]" value="1" id="flexCheckDefault">
            @endif
            Страница отображается в меню
        </div>
        <div class="form-group">
            Порядок в меню:
            <input type="text" name="section_rus[order]" value="{{ $section_rus['order'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            @if(isset($section_rus['active']) && $section_rus['active'])
                <input class="form-check-input" type="checkbox" name="section_rus[active]" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="section_rus[active]" value="1" id="flexCheckDefault">
            @endif
            Страница доступна по ссылке
        </div>
        <hr>
        <h3>Версия на английском</h3>
        <input type="hidden" name="section_eng[lang_id]" value="2">
        <div class="form-group">
            Заголовок:
            <input type="text" name="section_eng[title]" value="{{ $section_eng['title'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Описание:
            <input type="text" name="section_eng[description]" value="{{ $section_eng['description'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Содержимое страницы (не отображается, если страница является модулем):
            <textarea name="section_eng[text]" id="content_eng">{{ $section_eng['text'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            Родительский раздел:
            <select class="form-control" name="section_eng[parent_id]">
                @foreach($parents_eng as $parent)
                    @if(isset($section_eng['parent_id']) && $parent['section_id'] == $section_eng['parent_id'])
                        <option value="{{ $parent['section_id'] }}" selected>{{ $parent['title'] }}</option>
                    @else
                        <option value="{{ $parent['section_id'] }}">{{ $parent['title'] }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            @if(isset($section_eng['show_in_menu']) && $section_eng['show_in_menu'])
                <input class="form-check-input" type="checkbox" name="section_eng[show_in_menu]" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="section_eng[show_in_menu]" value="1" id="flexCheckDefault">
            @endif
            Страница отображается в меню
        </div>
        <div class="form-group">
            Порядок в меню:
            <input type="text" name="section_eng[order]" value="{{ $section_eng['order'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            @if(isset($section_eng['active']) && $section_eng['active'])
                <input class="form-check-input" type="checkbox" name="section_eng[active]" value="1" id="flexCheckDefault" checked>
            @else
                <input class="form-check-input" type="checkbox" name="section_eng[active]" value="1" id="flexCheckDefault">
            @endif
            Страница доступна по ссылке
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
        <div class="form-group">
            <strong>Последнее редактирование: </strong>{{ $section_rus['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
        </div>
    </form>
    <script>
        CKEDITOR.replace( 'content_rus', {
            filebrowserBrowseUrl: '{{ asset(route('ckfinder_browser')) }}',
            filebrowserImageBrowseUrl: '{{ asset(route('ckfinder_browser')) }}?type=Images',
            filebrowserUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Images',
        });
        CKEDITOR.replace( 'content_eng', {
            filebrowserBrowseUrl: '{{ asset(route('ckfinder_browser')) }}',
            filebrowserImageBrowseUrl: '{{ asset(route('ckfinder_browser')) }}?type=Images',
            filebrowserUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Files',
            filebrowserImageUploadUrl: '{{ asset(route('ckfinder_connector')) }}?command=QuickUpload&type=Images',
        });
    </script>
    @include('ckfinder::setup')
@endsection

