<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use App\Repositories\SectionRepository;
use App\Models\Section;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

URL::forceRootUrl('https://kaspy.asu.edu.ru');

Route::get('/test', [\App\Http\Controllers\SectionController::class, 'test']);

Route::any('/ckfinder/connector', [\CKSource\CKFinderBridge\Controller\CKFinderController::class, 'requestAction'])
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser', [\CKSource\CKFinderBridge\Controller\CKFinderController::class, 'browserAction'])
    ->name('ckfinder_browser');

//адрес переключения языков
Route::get('setlocale/{lang}', function ($lang) {

    $referer = Redirect::back()->getTargetUrl(); //URL предыдущей страницы
    $parse_url = parse_url($referer, PHP_URL_PATH); //URL предыдущей страницы

    //разбиваем на массив по разделителю
    $segments = explode('/', $parse_url);

    //если URL (где нажали на переключение языка) содержал корректную метку языка, удаляем метку
    if (in_array($segments[1], App\Http\Middleware\LocaleMiddleware::$languages))
        unset($segments[1]);
    
    //проверка на главную страницу
    if (count($segments) >= 2 && $segments[array_keys($segments)[1]] != '')
    {
        //проверяется, существует ли страница с таким адресом на выбранном языке
        $is_active_page = (new SectionRepository(new Section()))->isActiveSection($segments[array_keys($segments)[1]], $lang);

        // костыль для поиска
        if ($segments[array_keys($segments)[1]] == 'search')
            $is_active_page = true;

        if(!$is_active_page)
            return redirect('/'.$lang);
    }

    //добавляем метку языка в URL (если выбран не язык по-умолчанию)
    if ($lang != App\Http\Middleware\LocaleMiddleware::$mainLanguage)
        array_splice($segments, 1, 0, $lang);

    //формируем полный URL
    $url = Request::root().implode("/", $segments);

    if ($url[-1] == '/')
        $url = substr($url, 0, -1);

    //если были еще GET-параметры - добавляем их
    if(parse_url($referer, PHP_URL_QUERY))
        $url = $url.'?'. parse_url($referer, PHP_URL_QUERY);


    return redirect($url); //Перенаправляем назад на ту же страницу
})->name('setlocale');

Route::get('admin/login', [\App\Http\Controllers\AuthController::class, 'index'])->name('loginForm');
Route::post('admin/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('loginAction');

Route::group(['prefix' => 'admin', 'middleware' => ['authAdmin']], function(){
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index']);

    // ajax
    Route::get('/get-articles/{issue_id}',[\App\Http\Controllers\AdminController::class, 'getArticles']);

    Route::get('/xml', [\App\Http\Controllers\AdminController::class, 'addXML']);
    Route::post('/xml', [\App\Http\Controllers\AdminController::class, 'addXMLAction']);

    Route::get('/issues', [\App\Http\Controllers\AdminController::class, 'getIssues']);
    Route::get('/add-issue', [\App\Http\Controllers\AdminController::class, 'addIssue']);
    Route::post('/add-issue', [\App\Http\Controllers\AdminController::class, 'addIssueAction']);
    Route::get('/edit-issue/{issue_id}', [\App\Http\Controllers\AdminController::class, 'editIssue']);
    Route::post('/edit-issue/{issue_id}', [\App\Http\Controllers\AdminController::class, 'editIssueAction']);

    Route::get('/add-article/{issue_id}', [\App\Http\Controllers\AdminController::class, 'addArticle']);
    Route::post('/add-article/{issue_id}', [\App\Http\Controllers\AdminController::class, 'addArticleAction']);
    Route::get('/edit-article/{article_id}', [\App\Http\Controllers\AdminController::class, 'editArticle']);
    Route::post('/edit-article/{article_id}', [\App\Http\Controllers\AdminController::class, 'editArticleAction']);

    Route::get('/editors/', [\App\Http\Controllers\AdminController::class, 'getEditors']);
    Route::get('/add-editor/', [\App\Http\Controllers\AdminController::class, 'addEditor']);
    Route::post('/add-editor/', [\App\Http\Controllers\AdminController::class, 'addEditorAction']);
    Route::get('/edit-editor/{editor_id}', [\App\Http\Controllers\AdminController::class, 'editEditor']);
    Route::post('/edit-editor/{editor_id}', [\App\Http\Controllers\AdminController::class, 'editEditorAction']);

    Route::get('/sections/', [\App\Http\Controllers\AdminController::class, 'getSections']);
    Route::get('/add-section/', [\App\Http\Controllers\AdminController::class, 'addSection']);
    Route::post('/add-section/', [\App\Http\Controllers\AdminController::class, 'addSectionAction']);
    Route::get('/edit-section/{section_id}', [\App\Http\Controllers\AdminController::class, 'editSection']);
    Route::post('/edit-section/{section_id}', [\App\Http\Controllers\AdminController::class, 'editSectionAction']);

    Route::get('/admins/', [\App\Http\Controllers\AdminController::class, 'getAdmins']);
    Route::get('/add-admin/', [\App\Http\Controllers\AdminController::class, 'addAdmin']);
    Route::post('/add-admin/', [\App\Http\Controllers\AdminController::class, 'addAdminAction']);
    Route::get('/edit-admin/{admin_id}', [\App\Http\Controllers\AdminController::class, 'editAdmin']);
    Route::post('/edit-admin/{admin_id}', [\App\Http\Controllers\AdminController::class, 'editAdminAction']);
});

// ajax
Route::get('/answer',[\App\Http\Controllers\SectionController::class, 'getAnswer']);
Route::get('/en/answer',[\App\Http\Controllers\SectionController::class, 'getAnswer']);

Route::group(['prefix' => App\Http\Middleware\LocaleMiddleware::getLocale()], function(){

//    Route::get('/', function () {
//        return view('welcome');
//    });

    Route::get('/', [\App\Http\Controllers\SectionController::class, 'openMain']);
    Route::get('/archive', [\App\Http\Controllers\SectionController::class, 'openArchive']);
    Route::get('/archive/{year}/issue/{number_issue}', [\App\Http\Controllers\SectionController::class, 'openIssue']);
    Route::get('/archive/{year}/issue/{number_issue}/article/{article_id}',
        [\App\Http\Controllers\SectionController::class, 'openArticle'])->name('article');
    Route::get('/editorial-board', [\App\Http\Controllers\SectionController::class, 'openEditboard']);
    Route::get('/editorial-board/{editor_id}', [\App\Http\Controllers\SectionController::class, 'openEditor']);
    Route::get('/search', [\App\Http\Controllers\SectionController::class, 'openSearch']);
    Route::get('/send-article', [\App\Http\Controllers\SectionController::class, 'sendArticle']);

    // используется для простоты формирования поисковой выдачи
    // с этого адреса происходит редирект на правильный
    // Route::get('/searcharticle/{article_id}', [\App\Http\Controllers\SectionController::class, 'openSearchingArticle']);

    Route::get('/{address}', [\App\Http\Controllers\SectionController::class, 'openSection']);
});

//    сейчас используется всплывающее окно вместо отдельной страницы
//    Route::get('/archive/{year}/issue/{number_issue}/article/{article_id}/author/{author_id}',
//        [\App\Http\Controllers\SectionController::class, 'openAuthor']);



