// кнопка показать больше
function readMore(paragraph)
{
    var dots = document.getElementById(paragraph).querySelector("#dots");
    var moreText = document.getElementById(paragraph).querySelector("#more");
    var btnText = document.getElementById(paragraph).querySelector("#readBtn");

    if (dots.style.display === "none")
    {
        dots.style.display = "inline";
        if (document.documentElement.lang.toLowerCase() == "ru")
            btnText.innerHTML = "Показать больше";
        else
            btnText.innerHTML = "Read more";

        moreText.style.display = "none";
    } else
    {
        dots.style.display = "none";
        if (document.documentElement.lang.toLowerCase() == "ru")
            btnText.innerHTML = "Показать меньше";
        else
            btnText.innerHTML = "Read less";

        moreText.style.display = "inline";
    }
}

// кнопка скопировать для цитирования
function copyText(id)
{
    var copyText = document.getElementById(id).innerText;
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(copyText).select();
    document.execCommand("copy");
    $temp.remove();
}

// кнопка статьи выпуска в панели администрирования
function getArticles(issue_id)
{
    $.ajax({
        type:"GET",
        url:"/admin/get-articles/" + issue_id,
        success:function(data) {
            $("#art-list-"+issue_id).html(data.list);
        }
    });
}

// переключение вкладок
function openTab(evt, tabName, tabContentName, activeClass="active")
{
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName(tabContentName);
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" " + activeClass, "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " " + activeClass;
}

// при редактировании статьи в модальном окне сохраняет значения
function updateInput(input, value)
{
    input.val(value);
    input.innerHTML = value;
}

//обновляет кнопки для редактирования авторов
//закрывает модальное окно
function saveAuthor(order_author)
{
    $(`#AuthorModal${order_author}_edit`).modal('hide');

    let author_name_rus = document.getElementsByName(`authors_rus[${order_author}][surname]`)[0].value + ' ' +
        document.getElementsByName(`authors_rus[${order_author}][initials]`)[0].value;

    let author_name_eng = document.getElementsByName(`authors_eng[${order_author}][surname]`)[0].value + ' ' +
        document.getElementsByName(`authors_eng[${order_author}][initials]`)[0].value;

    if (author_name_rus == ' ')
        author_name_rus = 'Новый_автор_' + order_author;

    if (author_name_eng == ' ')
        author_name_eng = 'New_author_' + order_author;

    document.getElementById(`AuthorModal${order_author}_edit_rus_btn`).innerHTML = author_name_rus;
    document.getElementById(`AuthorModal${order_author}_edit_eng_btn`).innerHTML = author_name_eng;
}

// удаляет форму с автором и соответствующие кнопки
function deleteAuthor(order_author)
{
    $(`#AuthorModal${order_author}_edit`).modal('hide');

    $(`#AuthorModal${order_author}_edit`).on('hidden.bs.modal', function () {
        $(this).remove();
    });
    $(`#AuthorModal${order_author}_edit_eng_btn`).remove();
    $(`#AuthorModal${order_author}_edit_rus_btn`).remove();
}

// добавляет форму с автором и соответствующие кнопки
function addAuthor()
{
    const i = document.getElementsByClassName('author_edit').length;
    document.getElementById(`authors_list_rus`).innerHTML +=
        `
<a href="#AuthorModal${i}_edit" id="AuthorModal${i}_edit_rus_btn" data-toggle="modal">Новый_автор_${i}</a>
`;

    document.getElementById(`authors_list_eng`).innerHTML +=
        `
<a href="#AuthorModal${i}_edit" id="AuthorModal${i}_edit_eng_btn" data-toggle="modal">New_author_${i}</a>
`;

    document.getElementById(`authors_list`).innerHTML +=
        `
<div id="AuthorModal${i}_edit" class="modal fade author_edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Редактировать автора</h4>
            </div>
            <div class="modal-body">
                <div class="tab">
                    <a class="btn-link" href="##" onclick="openTab(event, 'rus_author${i}_edit', 'tabcontent_author')">Русский</a>
                    <a class="btn-link" href="##" onclick="openTab(event, 'eng_author${i}_edit', 'tabcontent_author')">English</a>
                </div>
                <div id="rus_author${i}_edit" class="tabcontent_author" style="display: block;">
                    <input type="hidden" name="authors_rus[${i}][lang_id]" value="1">
                    <input type="hidden" name="authors_rus[${i}][author_id]" value="" class="form-control">
                    <div class="form-group">
                        ORCID:
                        <input type="text" name="authors_rus[${i}][orcid_code]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Фамилия:
                        <input type="text" name="authors_rus[${i}][surname]" value="Новый_автор_${i}" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Инициалы:
                        <input type="text" name="authors_rus[${i}][initials]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Ученая степень:
                        <input type="text" name="authors_rus[${i}][academic_degree]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Должность:
                        <input type="text" name="authors_rus[${i}][post]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Университет:
                        <input type="text" name="authors_rus[${i}][university]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Город:
                        <input type="text" name="authors_rus[${i}][city]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        E-mail:
                        <input type="text" name="authors_rus[${i}][email]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                </div>
                <div id="eng_author${i}_edit" class="tabcontent_author">
                    <input type="hidden" name="authors_eng[${i}][lang_id]" value="2">
                    <input type="hidden" name="authors_eng[${i}][author_id]" value="" class="form-control">
                    <div class="form-group">
                        ORCID:
                        <input type="text" name="authors_eng[${i}][orcid_code]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Фамилия:
                        <input type="text" name="authors_eng[${i}][surname]" value="New_author_${i}" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Инициалы:
                        <input type="text" name="authors_eng[${i}][initials]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Ученая степень:
                        <input type="text" name="authors_eng[${i}][academic_degree]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Должность:
                        <input type="text" name="authors_eng[${i}][post]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Университет:
                        <input type="text" name="authors_eng[${i}][university]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        Город:
                        <input type="text" name="authors_eng[${i}][city]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                    <div class="form-group">
                        E-mail:
                        <input type="text" name="authors_eng[${i}][email]" value="" onchange="updateInput(this, this.value)" class="form-control">
                    </div>
                </div>
                <button type="button" onclick="saveAuthor(${i}, 'rus')" class="btn btn-primary">Сохранить</button>
                <button type="button" onclick="deleteAuthor(${i}, 'rus')" class="btn btn-danger">Удалить</button>
            </div>
        </div>
    </div>
</div>
`;
}

// подстановка авторов в форму при редатировании статьи
function addAuthors()
{
    $('#authors_list').appendTo('.editform')
}

// флаг отправленного запроса
var isSendQuery = false;
// отправка поискового запроса и вывод результатов
function sendQuery(batch_i)
{
    // новый запрос игнорируется, пока не будет выполнен предыдущий
    if (window.isSendQuery)
        return;

    window.isSendQuery = true;
    document.getElementById("search-res").innerHTML = '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
    document.getElementById("search-pages").innerHTML = '';
    window.scrollTo(0, 0);
    
    lang = document.documentElement.lang;

    $.ajax({
        url: "https://"+window.location.hostname+"/"+lang+"/answer",
        type: "GET",
        data: {'q': $('#query_search').val(), 'batch_i': batch_i-1},
        success:function(out) {
            $("#search-out").html(out);
            window.isSendQuery = false;
        }
    });
}

// отправляет поисковый запрос по нажатию Enter
function sendEnter(event)
{
    if (event.which == 13 || event.keyCode == 13) {
        sendQuery(1);
    }
}

// отправляет поисковый запрос по ключевым словам
function sendKeyword(word)
{

}