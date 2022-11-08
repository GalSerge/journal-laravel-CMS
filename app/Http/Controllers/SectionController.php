<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IssueService;
use App\Services\SectionService;
use App\Services\EditorService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\App;

class SectionController extends Controller
{
    protected $issue;
    protected $section;

    public function __construct(IssueService $issue, SectionService $section)
    {
        $this->issue = $issue;
        $this->section = $section;
    }

    public function test(IssueService $issue, SectionService $section)
    {
        $all_articles = $issue->getPopularArticles(40)->toarray();
        $rand_keys = array_rand($all_articles, 5);

        $articles = array();
        foreach ($rand_keys as $key)
            $articles[] = $all_articles[$key];


        return view('components.popularcard', ['popular_articles' => $articles]);
    }

    /**
     * Собирает все необходимы для отображения страницы компоненты и отправляет их в полученный шаблон
     * @param string $template название шаблона
     * @param array $page_params дополнительные параметры для конкретного раздела
     * @return mixed
     */
    protected function viewDefaultPage(string $template, array $page_params=array())
    {
        $sections = $this->section->getSectionsForMenu();
        $current_issue = $this->issue->getCurrentIssue();

        //список страниц для генерации меню
        $page_params['pages'] = $sections;

        //если вызвана главная страница, то необходимо содержание текущего выпуска
        //оно в getCurrentIssue() отсутствует
        if ($template == 'main')
            $current_issue = $this->issue->getIssue($current_issue['year'], $current_issue['journal_number']);

        $page_params['current_issue'] = $current_issue;

        $page_params['popular_articles'] = $this->issue->getPopularArticles();

        return view($template, $page_params);
    }

    public function openMain()
    {
        return $this->viewDefaultPage('main');
    }

    /** Делает запрос к поиску. В случае успеха отправляет из в сервис выпусков
     * для получения дополнительных сведений, а потом передает их в представление.
     * В случае ошибки сразу передает сообщение в представление
     * @param Request $request
     * @return mixed
     */
    public function getAnswer(Request $request)
    {
        $api_key = 'NMsoLoXgfyrKDWVZyFPVkNZgNYFTfcoVaZttqZvp';

        try {
            $response = Http::withHeaders(['APP-KEY' => $api_key])
                //->timeout(1)
                ->accept('application/json')
                ->get('https://search.asu.edu.ru/answer', [
                    'q' => $request->q,
                    'batch_size' => 30,
                    'batch_i' => $request->batch_i]);
        } catch (\Exception $e)
        {
            $returnHTML = view('components.searchmessage')->with(['type_msg' => 'wrong'])->render();
            return response()->json($returnHTML);
        }

        $response = $response->json();

        if (array_key_exists('full_size', $response) && $response['full_size'] == 0)
        {
            $returnHTML = view('components.searchmessage')->with(['type_msg' => 'nothing'])->render();
            return response()->json($returnHTML);
        }

        $full_size = $response['full_size'];
        $result = $response['result'];
        $answer = $this->issue->searchArticles($result);

        $returnHTML = view('components.searchresults')->with([
            'answer' => $answer,
            'full_size' => $full_size,
            'cur_page' => $request->batch_i+1])->render();

        return response()->json($returnHTML);
    }

    /*
     * Все функции ниже работают по одному принципу
     * Они обращаются к сервису соответствующего раздела, получают данные именно этого раздела
     * и отдают их в viewDefaultPage()
     */
    public function openSection($address)
    {
        $section = $this->section->getSection($address);
        return $this->viewDefaultPage('pages.section', ['section' => $section]);
    }

//    public function openAuthor($year, $issue_id, $article_id, $author_id)
//    {
//        $author = $this->issue->getAuthor($author_id);
//        return $this->viewDefaultPage('pages.author', ['author' => $author]);
//    }

    public function openArticle($year, $journal_number, $article_id)
    {
        $article = $this->issue->getArticle($article_id);
        return $this->viewDefaultPage('pages.article', ['article' => $article]);
    }

//    public function openSearchingArticle($article_id)
//    {
//        $article = $this->issue->getArticle($article_id);
//
//        return redirect()->route('article', ['year' => $article['year'],
//            'number_issue' => $article['journal_number'],
//            'article_id' => $article['article_id']]);
//
//        return redirect()->route('archive/'.$article['year'].'/issue/'.$article['journal_number'].'/article/'.$article['article_id']);
//    }

    public function openIssue($year, $journal_number)
    {
        $this_issue = $this->issue->getIssue($year, $journal_number);
        return $this->viewDefaultPage('pages.issue', ['issue' => $this_issue]);
    }

    public function openArchive()
    {
        $archive = $this->issue->getArchive();
        return $this->viewDefaultPage('pages.archive', ['archive' => $archive]);
    }

    public function openEditboard(EditorService $editor)
    {
        $editors = $editor->getAllEditors();
        return $this->viewDefaultPage('pages.editboard', ['editors' => $editors]);
    }

    public function openEditor(EditorService $editor, $editor_id)
    {
        $editor = $editor->getEditor($editor_id);
        return $this->viewDefaultPage('pages.editor', ['editor' => $editor]);
    }

    public function openSearch(Request $request)
    {
        $section = $this->section->getSection('search');

        // если переход на поиск происходит по нажатию на ключевое слово или автора,
        // они передаются в качестве предложения для нового поискового запроса q
        $section['text'] = view('components.search')->with(['q' => $request->q])->render();

        return $this->viewDefaultPage('pages.section', ['section' => $section]);
    }

    public function sendArticle(Request $request)
    {
        $section = $this->section->getSection('send-article');

        // если редирект на поиск происходит по нажатию на ключевое слово или автора,
        // они передаются в качестве предложения для нового поискового запроса q

        $section['text'] = view('components.sendarticle')->render();
        return $this->viewDefaultPage('pages.section', ['section' => $section]);
    }

}
