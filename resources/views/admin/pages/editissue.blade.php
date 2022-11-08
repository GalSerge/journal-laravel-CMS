@extends('admin.page')

@section('header')
    <meta name="description" content="{{ $title }}" />
    <title>{{ $title }}</title>
@endsection

@section('content')
    <h1>{{ $title }}</h1>
    <form action="{{ url(Request::url()) }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            Номер выпуска в году:
            <input type="text" name="journal_number" value="{{ $issue['journal_number'] ?? '' }}" class="form-control" required>
        </div>
        <div class="form-group">
            Сквозной номер:
            <input type="text" name="alt_number" value="{{ $issue['alt_number'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Год:
            <input type="text" name="year" value="{{ $issue['year'] ?? '' }}" class="form-control" required>
        </div>
        <div class="form-group">
            Дата публикации:
            <input type="date" name="published" value="{{ $issue['published'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Код doi:
            <input type="text" name="doi_code" value="{{ $issue['doi_code'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Аннотация:
            <textarea class="form-control text-area-in-pr" rows="10" name="annotation" maxlength="5000"></textarea>
        </div>
        <div class="form-group">
            Обложка:
            <input type="text" name="path_cover" value="{{ $issue['path_cover'] ?? '' }}" class="form-control">
        </div>
        <div class="form-group">
            Полный выпуск:
            <input type="file" name="fullnumber_file" class="form-control" accept=".pdf">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
        <div class="form-group">
            <strong>Последнее редактирование: </strong>{{ $issue['updated_at'] ?? date('Y-m-d H:i:s') }}<br>
        </div>
    </form>
@endsection