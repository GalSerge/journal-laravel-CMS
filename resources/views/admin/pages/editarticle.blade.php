@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <div class="tab">
        <button class="btn-link" onclick="openTab(event, 'rus_edit', 'tabcontent')">Русский</button>
        <button class="btn-link" onclick="openTab(event, 'eng_edit', 'tabcontent')">English</button>
    </div>
    <div id="rus_edit" class="tabcontent" style="display: block;">
        <h3>Информация на русском языке</h3>
        <form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="issue_id" value="{{ $article_rus['issue_id'] ?? $issue_id }}">
            <input type="hidden" name="lang_id" value="{{ $article_rus['lang_id'] ?? 1 }}">
            <div class="form-group">
                Предметная область:
                <select class="form-control" name="subject_id" onchange="updateInput(this, this.value)">
                    @foreach($subjects_rus as $sub)
                        @if(isset($article_rus['subject_id']) && $sub['subject_id'] == $article_rus['subject_id'])
                            <option value="{{ $sub['subject_id'] }}" selected>{{ $sub['title'] }}</option>
                        @else
                            <option value="{{ $sub['subject_id'] }}">{{ $sub['title'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                Код doi:
                <input type="text" name="doi_code" onchange="updateInput(this, this.value)" value="{{ $article_rus['doi_code'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Код УДК:
                <input type="text" name="udk_code" onchange="updateInput(this, this.value)" value="{{ $article_rus['udk_code'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Название статьи:
                <input type="text" name="title" onchange="updateInput(this, this.value)" value="{{ $article_rus['title'] ?? ''}}" class="form-control" required>
            </div>
            <div class="form-group">
                Страницы в журнале (должны совпадать с именем pdf файла статьи):
                <input type="text" name="pages" onchange="updateInput(this, this.value)" value="{{ $article_rus['pages'] ?? ''}}" class="form-control" required>
            </div>
            <div class="form-group">
                Дата поступления:
                <input type="date" name="submitted" onchange="updateInput(this, this.value)" value="{{ $article_rus['submitted'] ?? ''}}" class="form-control" dataformatas="YYYY-mm-dd">
            </div>
            <div class="form-group">
                Дата одобрения:
                <input type="date" name="approved" onchange="updateInput(this, this.value)" value="{{ $article_rus['approved'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Дата принятия к публикации:
                <input type="date" name="accepted" onchange="updateInput(this, this.value)" value="{{ $article_rus['accepted'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Дата публикации:
                <input type="date" name="published" onchange="updateInput(this, this.value)" value="{{ $article_rus['published'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Аннотация:
                <textarea name="annotation" class="form-control text-area-in-pr" onchange="updateInput(this, this.value)" rows="10" maxlength="5000">{{ $article_rus['annotation'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Благодарности:
                <textarea name="thanks" class="form-control text-area-in-pr" onchange="updateInput(this, this.value)" rows="10" maxlength="5000">{{ $article_rus['thanks'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Вклад авторов:
                <textarea name="authors_contribution" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">{{ $article_rus['authors_contribution'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Для цитирования:
                <textarea name="quotation" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">{{ $article_rus['quotation'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Источники (разделяются пустыми строками):
                <textarea name="references" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">
@if(isset($article_rus['references']))
{!! implode("\r\n\r\n", $article_rus['references'])  !!}
@endif
                </textarea>
            </div>
            <br>
            <div class="form-group">
                Ключевые слова (каждое слово/словосочетание с новой строки):
                <textarea name="keywords" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">
@if(isset($article_rus['keywords']))
{!! implode("\r\n", $article_rus['keywords']) !!}
@endif
                </textarea>
                </div>
            <br>
            <div id="authors_list_rus">
                Авторы:
                @if(isset($article_rus['authors']))
                    @for($i = 0; $i < count($article_rus['authors']); $i++)
                        <a href="#AuthorModal{{ $i }}_edit" id="AuthorModal{{ $i }}_edit_rus_btn" data-toggle="modal">
                            {{ $article_rus['authors'][$i]['surname'].' '.$article_rus['authors'][$i]['initials'] ?? 'Новый_автор_'.$i }}
                        </a>
                    @endfor
                @endif
            </div>
            <button type="button" onclick="addAuthor()" class="btn btn-primary" data-toggle="modal">Добавить автора</button>
            <br><br>
            <div class="form-group">
                Текст (не отображается, используется для поиска):
                <textarea class="form-control text-area-in-pr" rows="10" name="text" maxlength="5000">{{ $article_rus['text'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Текст статьи в PDF:
                <input type="file" name="article_file" class="form-control" accept=".pdf">
            </div>
            <div class="form-group">
                <button type="submit" onclick="addAuthors()" class="btn btn-primary">Сохранить на русском</button>
            </div>
            <div class="form-group">
                <strong>Последнее редактирование: </strong>{{ $article_rus['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
            </div>
        </form>
    </div>
    <div id="eng_edit" class="tabcontent">
        <h3>Информация на английском языке</h3>
        <form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="issue_id" value="{{ $article_eng['issue_id'] ?? $issue_id }}">
            <input type="hidden" name="lang_id" value="{{ $article_eng['lang_id'] ?? 1 }}">
            <div class="form-group">
                Предметная область:
                <select class="form-control" name="subject_id" onchange="updateInput(this, this.value)">
                    @foreach($subjects_eng as $sub)
                        @if(isset($article_eng['subject_id']) && $sub['subject_id'] == $article_eng['subject_id'])
                            <option value="{{ $sub['subject_id'] }}" selected>{{ $sub['title'] }}</option>
                        @else
                            <option value="{{ $sub['subject_id'] }}">{{ $sub['title'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                Код doi:
                <input type="text" name="doi_code" onchange="updateInput(this, this.value)" value="{{ $article_eng['doi_code'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Код УДК:
                <input type="text" name="udk_code" onchange="updateInput(this, this.value)" value="{{ $article_eng['udk_code'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Название статьи:
                <input type="text" name="title" onchange="updateInput(this, this.value)" value="{{ $article_eng['title'] ?? ''}}" class="form-control" required>
            </div>
            <div class="form-group">
                Страницы в журнале (должны совпадать с именем pdf файла статьи):
                <input type="text" name="pages" onchange="updateInput(this, this.value)" value="{{ $article_eng['pages'] ?? ''}}" class="form-control" required>
            </div>
            <div class="form-group">
                Дата поступления:
                <input type="date" name="submitted" onchange="updateInput(this, this.value)" value="{{ $article_eng['submitted'] ?? ''}}" class="form-control" dataformatas="YYYY-mm-dd">
            </div>
            <div class="form-group">
                Дата одобрения:
                <input type="date" name="approved" onchange="updateInput(this, this.value)" value="{{ $article_eng['approved'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Дата принятия к публикации:
                <input type="date" name="accepted" onchange="updateInput(this, this.value)" value="{{ $article_eng['accepted'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Дата публикации:
                <input type="date" name="published" onchange="updateInput(this, this.value)" value="{{ $article_eng['published'] ?? ''}}" class="form-control">
            </div>
            <div class="form-group">
                Аннотация:
                <textarea name="annotation" class="form-control text-area-in-pr" onchange="updateInput(this, this.value)" rows="10" maxlength="5000">{{ $article_eng['annotation'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Благодарности:
                <textarea name="thanks" class="form-control text-area-in-pr" onchange="updateInput(this, this.value)" rows="10" maxlength="5000">{{ $article_eng['thanks'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Вклад авторов:
                <textarea name="authors_contribution" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">{{ $article_eng['authors_contribution'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Для цитирования:
                <textarea name="quotation" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">{{ $article_eng['quotation'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Источники (разделяются пустыми строками):
                <textarea name="references" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">
@if(isset($article_eng['references']))
    {!! implode("\r\n\r\n", $article_eng['references'])  !!}
@endif
                </textarea>
            </div>
            <br>
            <div class="form-group">
                Ключевые слова (каждое слово/словосочетание с новой строки):
                <textarea name="keywords" onchange="updateInput(this, this.value)" class="form-control text-area-in-pr" rows="10" maxlength="5000">
@if(isset($article_eng['keywords']))
    {!! implode("\r\n", $article_eng['keywords']) !!}
@endif
                </textarea>
            </div>
            <br>
            <div id="authors_list_eng">
                Авторы:
                @if(isset($article_eng['authors']))
                    @for($i = 0; $i < count($article_eng['authors']); $i++)
                        <a href="#AuthorModal{{ $i }}_edit" id="AuthorModal{{ $i }}_edit_eng_btn" data-toggle="modal">
                            {{ $article_eng['authors'][$i]['surname'].' '.$article_eng['authors'][$i]['initials'] ?? 'New_author_'.$i }}
                        </a>
                    @endfor
                @endif
            </div>
            <button type="button" onclick="addAuthor()" class="btn btn-primary" data-toggle="modal">Добавить автора</button>
            <br><br>
            <div class="form-group">
                Текст (не отображается, используется для поиска):
                <textarea class="form-control text-area-in-pr" rows="10" name="text" maxlength="5000">{{ $article_eng['text'] ?? ''}}</textarea>
            </div>
            <div class="form-group">
                Текст статьи в PDF:
                <input type="file" name="article_file" class="form-control" accept=".pdf">
            </div>
            <div class="form-group">
                <button type="submit" onclick="addAuthors()" class="btn btn-primary">Сохранить на английском</button>
            </div>
            <div class="form-group">
                <strong>Последнее редактирование: </strong>{{ $article_eng['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
            </div>
        </form>
    </div>

    <div id="authors_list">
        @if(isset($article_rus['authors']))
            @for($i = 0; $i < count($article_rus['authors']); $i++)
                <div id="AuthorModal{{ $i }}_edit" class="modal fade author_edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">Редактировать автора</h4>
                            </div>
                            <div class="modal-body">
                                <div class="tab">
                                    <a class="btn-link" href="##" onclick="openTab(event, 'rus_author{{ $i }}_edit', 'tabcontent_author')">Русский</a>&nbsp;&nbsp;
                                    <a class="btn-link" href="##" onclick="openTab(event, 'eng_author{{ $i }}_edit', 'tabcontent_author')">English</a>
                                </div>
                                <div id="rus_author{{ $i }}_edit" class="tabcontent_author" style="display: block;">
                                    <input type="hidden" name="authors_rus[{{ $i }}][lang_id]" value="1">
                                    <input type="hidden" name="authors_rus[{{ $i }}][author_id]" value="{{ $article_rus['authors'][$i]['author_id'] ?? '' }}" class="form-control">

                                    <div class="form-group">
                                        ORCID:
                                        <input type="text" name="authors_rus[{{ $i }}][orcid_code]" value="{{ $article_rus['authors'][$i]['orcid_code'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Фамилия:
                                        <input type="text" name="authors_rus[{{ $i }}][surname]" value="{{ $article_rus['authors'][$i]['surname'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Инициалы:
                                        <input type="text" name="authors_rus[{{ $i }}][initials]" value="{{ $article_rus['authors'][$i]['initials'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Ученая степень:
                                        <input type="text" name="authors_rus[{{ $i }}][academic_degree]" value="{{ $article_rus['authors'][$i]['academic_degree'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Должность:
                                        <input type="text" name="authors_rus[{{ $i }}][post]" value="{{ $article_rus['authors'][$i]['post'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Университет:
                                        <input type="text" name="authors_rus[{{ $i }}][university]" value="{{ $article_rus['authors'][$i]['university'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Город:
                                        <input type="text" name="authors_rus[{{ $i }}][city]" value="{{ $article_rus['authors'][$i]['city'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        E-mail:
                                        <input type="text" name="authors_rus[{{ $i }}][email]" value="{{ $article_rus['authors'][$i]['email'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                </div>
                                <div id="eng_author{{ $i }}_edit" class="tabcontent_author">
                                    <input type="hidden" name="authors_eng[{{ $i }}][lang_id]" value="2">
                                    <input type="hidden" name="authors_eng[{{ $i }}][author_id]" value="{{ $article_rus['authors'][$i]['author_id'] ?? '' }}" class="form-control">
                                    <div class="form-group">
                                        ORCID:
                                        <input type="text" name="authors_eng[{{ $i }}][orcid_code]" value="{{ $article_eng['authors'][$i]['orcid_code'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Фамилия:
                                        <input type="text" name="authors_eng[{{ $i }}][surname]" value="{{ $article_eng['authors'][$i]['surname'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Инициалы:
                                        <input type="text" name="authors_eng[{{ $i }}][initials]" value="{{ $article_eng['authors'][$i]['initials'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Ученая степень:
                                        <input type="text" name="authors_eng[{{ $i }}][academic_degree]" value="{{ $article_eng['authors'][$i]['academic_degree'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Должность:
                                        <input type="text" name="authors_eng[{{ $i }}][post]" value="{{ $article_eng['authors'][$i]['post'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Университет:
                                        <input type="text" name="authors_eng[{{ $i }}][university]" value="{{ $article_eng['authors'][$i]['university'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        Город:
                                        <input type="text" name="authors_eng[{{ $i }}][city]" value="{{ $article_eng['authors'][$i]['city'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        E-mail:
                                        <input type="text" name="authors_eng[{{ $i }}][email]" value="{{ $article_eng['authors'][$i]['email'] ?? '' }}" onchange="updateInput(this, this.value)" class="form-control">
                                    </div>
                                </div>
                                <button type="button" onclick="saveAuthor({{ $i }})" class="btn btn-primary">Сохранить</button>
                                <button type="button" onclick="deleteAuthor({{ $i }})" class="btn btn-danger">Удалить</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
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
@endsection

