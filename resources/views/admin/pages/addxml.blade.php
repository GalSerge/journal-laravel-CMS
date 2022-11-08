@extends('admin.page')

@section('content')
    <h1>{{ $title }}</h1>
    <form action="{{ Request::url() }}" method="post" enctype="multipart/form-data">
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
            Файл xml:
            <input type="file" name="xml_file" class="form-control" accept=".xml" required>
        </div>
        <div class="form-group">
            Полный выпуск:
            <input type="file" name="fullnumber_file" class="form-control" accept=".pdf" >
        </div>
        <div class="form-group">
            Статьи:
            <input type="file" name="article_files[]" class="form-control" accept=".pdf" required multiple>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </div>
    </form>
    <div>
        {{ $log ?? ''}}
    </div>
@endsection