<form action="{{ url(Request::url()) }}" method="post" class="editform" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        {{ __('sendarticle.fio_rus') }}:
        <input type="text" name="fio_rus" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.fio_eng') }}:
        <input type="text" name="fio_eng" value="" class="form-control" required>
    </div>
    <div class="form-group">
        E-mail:
        <input type="text" name="email" value="" class="form-control" required>
    </div>
    <div class="form-group">
        ORCID:
        <input type="text" name="orcid" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.rank_rus') }}:
        <input type="text" name="rank_rus" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.rank_eng') }}:
        <input type="text" name="rank_eng" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.degree_rus') }}:
        <input type="text" name="degree_rus" value="" class="form-control">
    </div>
    <div class="form-group">
        {{ __('sendarticle.degree_eng') }}:
        <input type="text" name="degree_eng" value="" class="form-control">
    </div>
    <div class="form-group">
        {!! __('sendarticle.recommendation') !!}:
        <input type="file" name="degree_eng" value="" class="form-control" accept=".pdf">
    </div>
    <div class="form-group">
        {{ __('sendarticle.studywork_rus') }}:
        <input type="text" name="studywork_rus" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.studywork_eng') }}:
        <input type="text" name="studywork_eng" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.address_rus') }}:
        <input type="text" name="address_rus" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.address_eng') }}:
        <input type="text" name="address_eng" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.department_rus') }}:
        <input type="text" name="department_rus" value="" class="form-control">
    </div>
    <div class="form-group">
        {{ __('sendarticle.department_eng') }}:
        <input type="text" name="department_eng" value="" class="form-control">
    </div>
    <div class="form-group">
        {{ __('sendarticle.job_rus') }}:
        <input type="text" name="job_rus" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {{ __('sendarticle.job_eng') }}:
        <input type="text" name="job_eng" value="" class="form-control" required>
    </div>
    <div class="form-group">
        {!! __('sendarticle.study_certificate') !!}:
        <input type="file" name="study_certificate" value="" class="form-control" accept=".pdf">
    </div>
    <div class="form-group">
        {!! __('sendarticle.reviews') !!}:
        <input type="file" name="reviews" value="" class="form-control" accept=".pdf">
    </div>


    <div class="form-group">
        <button type="submit" class="btn btn-primary">Отправить</button>
    </div>
</form>

ФИО степень, звание, контакты orcid место учебы/работы, адрес организации, структурное подразделение, должность
справки об обучении в аспирантуре и рецензии научного руководителя