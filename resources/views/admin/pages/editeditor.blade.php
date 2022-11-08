@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <div class="tab">
        <button class="btn-link" onclick="openTab(event, 'rus_edit', 'tabcontent')">Русский</button>
        <button class="btn-link" onclick="openTab(event, 'eng_edit', 'tabcontent')">English</button>
    </div>
    <div id="rus_edit" class="tabcontent" style="display: block;">
        <h3>Информация на русском языке</h3>
        <form action="{{ Request::url() }}" method="post" class="editform" enctype="multipart/form-data">
            @csrf
            {{ $editor_rus[''] ?? '' }}
            <input type="hidden" name="lang_id" value="{{ $editor_rus['lang_id'] ?? 1 }}">
            <div class="form-group">
                Фамилия:
                <input type="text" name="surname" value="{{ $editor_rus['surname'] ?? '' }}" class="form-control" required>
            </div>
            <div class="form-group">
                Инициалы:
                <input type="text" name="initials" value="{{ $editor_rus['initials'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                ORCID:
                <input type="text" name="orcid_code" value="{{ $editor_rus['orcid_code'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Ученая степень:
                <input type="text" name="academic_degree" value="{{ $editor_rus['academic_degree'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Страна, город:
                <input type="text" name="country_city" value="{{ $editor_rus['country_city'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Университет:
                <input type="text" name="university" value="{{ $editor_rus['university'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Должность:
                <input type="text" name="post" value="{{ $editor_rus['post'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Научные интересы:
                <textarea name="scientific_interests">{{ $editor_rus['scientific_interests'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Научная специализация:
                <textarea name="scientific_spec">{{ $editor_rus['scientific_spec'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Важные публикации:
                <textarea name="important_publics">{{ $editor_rus['important_publics'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Грантовая деятельность:
                <textarea name="grant_activities">{{ $editor_rus['grant_activities'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Эспертная деятельность:
                <textarea name="expert_activities">{{ $editor_rus['expert_activities'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Дополнительная информация:
                <textarea name="more_information">{{ $editor_rus['more_information'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Контакты:
                <input type="text" name="contacts" value="{{ $editor_rus['contacts'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Фотография:<br><br>
                @if($editor_rus['path_img'] == '')
                    <img class="editor-img" src="{{ url('/public/images/icons/person.svg') }}">
                @else
                    <img class="editor-img" src="{{ $editor_rus['path_img'] }}">
                @endif
                <br><br>
                <input type="file" name="file_img" value="" class="form-control">
                <input type="hidden" name="path_img" value="{{ $editor_rus['path_img'] ?? '' }}">
            </div>
            <div class="form-group">
                @if(isset($editor_rus['active']) && $editor_rus['active'])
                    <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault">
                @endif
                Информация доступна
            </div>
            <div class="form-group">
                @if(isset($editor_rus['is_main']) && $editor_rus['is_main'])
                    <input class="form-check-input" type="checkbox" name="is_main" value="1" id="flexCheckDefault" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="is_main" value="1" id="flexCheckDefault">
                @endif
                Главный редактор
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить на русском</button>
            </div>
            <div class="form-group">
                <strong>Последнее редактирование: </strong>{{ $editor_rus['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
            </div>
        </form>
    </div>
    <div id="eng_edit" class="tabcontent">
        <h3>Информация на английском языке</h3>
        <form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
            @csrf
            {{ $editor_eng[''] ?? '' }}
            <input type="hidden" name="lang_id" value="{{ $editor_eng['lang_id'] ?? 2 }}">
            <div class="form-group">
                Фамилия:
                <input type="text" name="surname" value="{{ $editor_eng['surname'] ?? '' }}" class="form-control" required>
            </div>
            <div class="form-group">
                Инициалы:
                <input type="text" name="initials" value="{{ $editor_eng['initials'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                ORCID:
                <input type="text" name="orcid_code" value="{{ $editor_eng['orcid_code'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Ученая степень:
                <input type="text" name="academic_degree" value="{{ $editor_eng['academic_degree'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Страна, город:
                <input type="text" name="country_city" value="{{ $editor_eng['country_city'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Университет:
                <input type="text" name="university" value="{{ $editor_eng['university'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Должность:
                <input type="text" name="post" value="{{ $editor_eng['post'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Научные интересы:
                <textarea name="scientific_interests">{{ $editor_eng['scientific_interests'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Научная специализация:
                <textarea name="scientific_spec">{{ $editor_eng['scientific_spec'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Важные публикации:
                <textarea name="important_publics">{{ $editor_eng['important_publics'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Грантовая деятельность:
                <textarea name="grant_activities">{{ $editor_eng['grant_activities'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Эспертная деятельность:
                <textarea name="expert_activities">{{ $editor_eng['expert_activities'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Дополнительная информация:
                <textarea name="more_information">{{ $editor_eng['more_information'] ?? '' }}</textarea>
            </div>
            <div class="form-group">
                Контакты:
                <input type="text" name="contacts" value="{{ $editor_eng['contacts'] ?? '' }}" class="form-control">
            </div>
            <div class="form-group">
                Фотография:<br><br>
                @if($editor_eng['path_img'] == '')
                    <img class="editor-img" src="{{ url('/public/images/icons/person.svg') }}">
                @else
                    <img class="editor-img" src="{{ $editor_eng['path_img'] }}">
                @endif
                <br><br>
                <input type="file" name="file_img" value="{{ $editor_eng['path_img'] ?? '' }}" class="form-control">
                <input type="hidden" name="path_img" value="{{ $editor_eng['path_img'] ?? '' }}">
            </div>
            <div class="form-group">
                @if(isset($editor_eng['active']) && $editor_eng['active'])
                    <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="active" value="1" id="flexCheckDefault">
                @endif
                Информация доступна
            </div>
            <div class="form-group">
                @if(isset($editor_eng['is_main']) && $editor_eng['is_main'])
                    <input class="form-check-input" type="checkbox" name="is_main" value="1" id="flexCheckDefault" checked>
                @else
                    <input class="form-check-input" type="checkbox" name="is_main" value="1" id="flexCheckDefault">
                @endif
                Главный редактор
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Сохранить на английском</button>
            </div>
            <div class="form-group">
                <strong>Последнее редактирование: </strong>{{ $editor_eng['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
            </div>
        </form>
    </div>
    <style>
        .tabcontent {
            display: none;
            border-top: none;
        }

        .tabcontent_author {
            display: none;
            border-top: none;
        }
    </style>

    <script>
        CKEDITOR.replaceAll();
    </script>
@endsection

