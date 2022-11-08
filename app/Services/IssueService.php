<?php
namespace App\Services;

use App\Repositories\ArticleRepository;
use App\Repositories\IssueRepository;
use App\Repositories\AuthorRepository;
use App\Repositories\KeywordsRepository;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Cookie;


class IssueService
{
    protected $issueRepository;
    protected $articleRepository;
    protected $authorRepository;
//    protected $keywordsRepository;
    // subject - это модель, а не репозиторий
    // каких-то сложных операций с предметными областями выполнять не приходится,
    // поэтому надстройка в виде репозитория не используется
    protected $subject;

    /**
     * @param IssueRepository $issueRepository
     * @param ArticleRepository $articleRepository
     * @param AuthorRepository $authorRepository
     * @param KeywordsRepository $keywordsRepository
     * @param Subject $subject
     */
    public function __construct(IssueRepository $issueRepository,
                                ArticleRepository $articleRepository,
                                AuthorRepository $authorRepository,
//                                KeywordsRepository $keywordsRepository,
                                Subject $subject)
    {
        $this->issueRepository = $issueRepository;
        $this->articleRepository = $articleRepository;
        $this->authorRepository = $authorRepository;
//        $this->keywordsRepository = $keywordsRepository;
        $this->subject = $subject;
    }

//
//    public function addKeywords($article_id, $keywords, $lang_id)
//    {
//        $data_keywords = array();
//        foreach ($keywords as $keyword)
//        {
//            $data = array();
//            $data['lang_id'] = $lang_id;
//            $data['word'] = $keyword;
//            $data['article_id'] = $article_id;
//
//            $data_keywords[] = $data;
//        }
//        $this->keywordsRepository->add($data_keywords);
//    }

    /**
     * Возвращает id раздела (предметной области) по его названию
     * @param string $title название раздела (предметной области)
     * @return mixed
     */
    public function getSubjectId(string $title)
    {
        $subject = $this->subject->where('title', $title)->first();
        if (isset($subject))
            return $subject->subject_id;
        else
            return false;
    }

    public function getAllSubjects(int $lang_id)
    {
        return $this->subject->where('lang_id', $lang_id)->get();
    }

    /**
     * Создает новый раздел (предметную область)
     * @param string $title название раздела (предметной области)
     * @return Subject объект нового раздела
     */
    protected function createSubject(string $title)
    {
        $user = session()->get('user');
        if (empty($user))
            return redirect()->route('loginForm');
        else
            $user_id = $user->user_id;

        return $this->subject->create(['title' => $title,
                                        'user_id' => $user_id]);
    }

    /**
     * Возвращает массив параметров автора по его id
     * @param int $id id автора
     * @return array
     */
    public function getAuthor(int $id)
    {
        $lang = App::currentLocale();
        $data_author = $this->authorRepository->getAuthor($lang, $id);

        return $data_author->attributesToArray();
    }

    public function getArticles(int $issue_id)
    {
        $lang = App::currentLocale();

        $data_articles = $this->articleRepository->getAllArticles($lang, $issue_id);

        $articles = array();
        foreach($data_articles as $row)
        {
            if(!array_key_exists($row['subject_id'], $articles))
                $articles[$row['subject_id']] = array('subject_title' => $row['subject_title' ], 'articles_list' => array());

            $article = $row->attributesToArray();
            $articles[$row['subject_id']]['articles_list'][] = $article;
        }

        return $articles;
    }

    /**
     * Возвращает массив параметров статьи по ее id
     * @param int $article_id id статьи
     * @return array
     */
    public function getArticle(int $article_id, $lang=null, $for_search=false)
    {

        if(!$lang)
            $lang = App::currentLocale();

        $data_article = $this->articleRepository->getArticle($lang, $article_id);

        $data_authors = $this->authorRepository->getArticleAuthors($lang, $data_article->article_id);
//        $data_keywords = $this->keywordsRepository->getKeywords($lang, $data_article->article_id);

        $article = $data_article->attributesToArray();

        $article['authors'] = array();
        foreach($data_authors as $row)
            $article['authors'][] = $row->attributesToArray();

//        $article['keywords'] = array();
//        foreach($data_keywords as $row)
//            $article['keywords'][] = $row->attributesToArray();


        $article['keywords'] = json_decode($article['keywords'], JSON_UNESCAPED_UNICODE);
        $article['references'] = json_decode($article['references'], JSON_UNESCAPED_UNICODE);

        if ($article['path_pdf'] == '' || !Storage::exists($article['path_pdf']))
        {
            if ($article['alt_number'])
                $path_pdf = 'public/archive/'.$article['journal_number'].'('.$article['alt_number'].')/'.$article['pages'].'.pdf';
            else
                $path_pdf = 'public/archive/'.$article['journal_number'].'('.$article['year'].')/'.$article['pages'].'.pdf';

            if(Storage::exists($path_pdf))
                $article['path_pdf'] = $path_pdf;
            else
                $article['path_pdf'] = '';
        }

//        Cookie::forever('viewed_'.$article_id, '1');
//        dd(Cookie::get('viewed_'.$article_id));

        if (!$for_search)
            $this->articleRepository->updateViews($article_id, ++$data_article->views);

        $article['views']++;

        return $article;
    }

    /**
     * Возвращает массив параметров последнего выпуска
     * @return array
     */
    public function getCurrentIssue()
    {
        $last_issue = $this->issueRepository->getLastIssue();
        return $last_issue->toArray();
    }

    public function getIssueById(int $issue_id)
    {
        $data_issue = $this->issueRepository->getIssueById($issue_id);
        return $data_issue->attributesToArray();
    }

    /**
     * Возвращает массив параметров выпуска (полная информация)
     * @param int $year год выпуска
     * @param int $journal_number номер выпуска в году
     * @return array
     */
    public function getIssue(int $year, int $journal_number)
    {
        $lang = App::currentLocale();

        $data_issue = $this->issueRepository->getIssue($year, $journal_number);
        $data_articles = $this->articleRepository->getAllArticles($lang, $data_issue->issue_id);
        foreach ($data_articles as $iter => $article)
        {
            $authors = $this->authorRepository->getArticleAuthors($lang, $article->article_id);
            if (isset($authors))
                $data_articles[$iter]['authors'] = $authors->toArray();
        }

        $issue = $data_issue->attributesToArray();

        $articles = array();
        foreach($data_articles as $row)
        {
            if(!array_key_exists($row['subject_id'], $articles))
                $articles[$row['subject_id']] = array('subject_title' => $row['subject_title' ], 'articles_list' => array());

            $article = $row->attributesToArray();


            if ($article['path_pdf'] == '' || !Storage::exists($article['path_pdf']))
            {
                if ($issue['alt_number'])
                    $path_pdf = 'public/archive/'.$issue['journal_number'].'('.$issue['alt_number'].')/'.$article['pages'].'.pdf';
                else
                    $path_pdf = 'public/archive/'.$issue['journal_number'].'('.$issue['year'].')/'.$article['pages'].'.pdf';

                if(Storage::exists($path_pdf))
                    $article['path_pdf'] = $path_pdf;
                else
                    $article['path_pdf'] = '';
            }

            $articles[$row['subject_id']]['articles_list'][] = $article;
        }

        $issue['articles'] = $articles;

        if ($issue['path_pdf'] == '' || !Storage::exists($issue['path_pdf']))
        {
            if ($issue['alt_number'])
                $path_pdf = 'public/archive/'.$issue['journal_number'].'('.$issue['alt_number'].')/'.$issue['year'].'_'.$issue['journal_number'].'.pdf';
            else
                $path_pdf = 'public/archive/'.$issue['journal_number'].'('.$issue['year'].')/'.$issue['year'].'_'.$issue['journal_number'].'.pdf';

            if(Storage::exists($path_pdf))
                $issue['path_pdf'] = $path_pdf;
            else
                $issue['path_pdf'] = '';
        }

        return $issue;
    }

    /**
     * Возвращает массив параметров всех выпусков
     * @return array
     */
    public function getArchive()
    {
        $data = $this->issueRepository->getAllIssues();
   
        $issues = array();
        foreach($data as $row)
            if(array_key_exists($row['year'], $issues))
                    $issues[$row['year']][$row['journal_number']] = $row->attributesToArray();
            else
            {
                $issues[$row['year']]  = array();
                $issues[$row['year']][$row['journal_number']] = $row->attributesToArray();
            }

        return $issues;
    }

    /**
     * Создает новую статью, возвращает ее id
     * @param int $issue_id id выпуска, к которому она относится
     * @return int
     */
    public function createArticle(int $issue_id)
    {
        $article_id = $this->articleRepository->getNextArticleId();
        $this->articleRepository->create($article_id, $issue_id, 1);
        $this->articleRepository->create($article_id, $issue_id, 2);

        return $article_id;
    }

    /**
     * Редактирует данные существующей статьи
     * @param Request $request обновленные данные статьи
     * @param int $article_id article_id существующей статьи
     * @return void
     */
    public function editArticle(Request $request, int $article_id)
    {
        //path_pdf - его может и не быть
        //необходимо рассмотреть загрузку файла
        /*
        'submitted' => '',
            'approved' => '',
            'accepted' => '',
            'published' => '',
            'authors' => 'array',
        */
        $validate_data = $request->validate([
            'lang_id' => 'required|numeric',
            'is_published' => '',
            'doi_code' => '',
            'udk_code' => '',
            'title' => '',
            'pages' => 'required',
            'subject_id' => 'required|numeric',
            'submitted' => '',
            'approved' => '',
            'accepted' => '',
            'published' => '',
            'annotation' => '',
            'thanks' => '',
            'authors_contribution' => '',
            'quotation' => '',
            'references' => 'array',
            'keywords' => 'array',
            'text' => '',
            'path_pdf' => '',
            'article_file' => ''
        ]);

        $lang_id = $validate_data['lang_id'];

        $user = session()->get('user');
        if (empty($user))
            return redirect()->route('loginForm');
        else
            $validate_data['user_id'] = $user->user_id;

        if (isset($validate_data['article_file']) && isset($validate_data['path_pdf']))
        {
            $folder = array_slice(explode('/', $validate_data['path_pdf']), 0, -1);
            $file_name = array_slice(explode('/', $validate_data['path_pdf']), -1, 1);
            $request->file('article_file')->storeAs(
                $folder,
                $file_name
            );
        }
        unset($validate_data['article_file']);


//        foreach ($validate_data['authors'] as $author)
//        {
//            $author_id = $this->authorRepository->getNextAuthorId();
//            $new_author = $this->authorRepository->create($author_id, $article_id, $lang_id);
//            $this->authorRepository->editAuthor($author, $author_id, $lang_id);
//        }
//        unset($validate_data['authors']);
//
//        $this->addKeywords($article_id, $validate_data['keywords'], $validate_data['lang_id']);
//        unset($validate_data['keywords']);

        $validate_data['references'] = json_encode($validate_data['references'], JSON_UNESCAPED_UNICODE);
        $validate_data['keywords'] = json_encode($validate_data['keywords'], JSON_UNESCAPED_UNICODE);

        $this->articleRepository->editArticle($validate_data, $article_id, $lang_id);
    }

    /**
     * Созадает новый выпуск, возвращает его id
     * @param Request $request данные нового выпуска
     * @return int
     */
    public function createIssue(Request $request)
    {
        $request->validate([
            'journal_number' => 'required',
            'alt_number' => '',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'published' => '',
            'doi_code' => '',
            'annotation' => '',
            'path_cover' => '',
            'fullnumber_file' => '',
        ]);
        $new_issue = $this->issueRepository->create();

        return $new_issue->issue_id;
    }
    
    /**
     * Редактирует данные существующего выпуска
     * @param Request $request обновленные данные выпуска
     * @param int $id id существующей статьи
     * @return void
     */
    public function editIssue(Request $request, int $id)
    {
        $validate_data = $request->validate([
            'journal_number' => 'required',
            'alt_number' => '',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'published' => '',
            'doi_code' => '',
            'annotation' => '',
            'path_cover' => '',
            'path_pdf' => '',
            'fullnumber_file' => '',
        ]);


        $user = session()->get('user');

        if (empty($user))
            return redirect()->route('loginForm');
        else
            $validate_data['user_id'] = $user->user_id;


        if (isset($validate_data['fullnumber_file']))
        {
            if (isset($validate_data['alt_number']))
                $folder = 'public/archive/'.$validate_data['journal_number'].'('.$validate_data['alt_number'].')';
            else
                $folder = 'public/archive/'.$validate_data['journal_number'].'('.$validate_data['year'].')';

            $file_name = $validate_data['year'].'_'.$validate_data['journal_number'].'.pdf';
            $validate_data['fullnumber_file']->storeAs(
                $folder,
                $file_name
            );
        }
        unset($validate_data['fullnumber_file']);

        $this->issueRepository->editIssue($validate_data, $id);
    }

    /**
     * Создает нового автора, возвращает его id
     * @param int $article_id id статьи автора
     * @return int
     */
    public function createAuthor(int $article_id)
    {
        $author_id = $this->authorRepository->getNextAuthorId();
        $this->authorRepository->create($author_id, $article_id, 1);
        $this->authorRepository->create($author_id, $article_id, 2);

        return $author_id;
    }

    /**
     * Редактирует данные существующего автора
     * @param Request $request обновленные данные автора
     * @param int $author_id id автора
     * @return void
     */
    public function editAuthor(Request $request, int $author_id)
    {
        $validate_data = $request->validate([
            'lang_id' => 'required|numeric',
            'orcid_code' => '',
            'surname' => 'required',
            'initials' => '',
            'academic_degree' => '',
            'post' => '',
            'university' => '',
            'city' => '',
            'email' => ''
        ]);

        $lang_id = $validate_data['lang_id'];

        $user = session()->get('user');
        if (empty($user))
            return redirect()->route('loginForm');
        else
            $validate_data['user_id'] = $user->user_id;

        $this->authorRepository->editAuthor($validate_data, $author_id, $lang_id);
    }

    /**
     * Считывает данные из XML и загружает их в БД
     * @return void
     */
    public function parseXML(Request $request)
    {
        $validate_data = $request->validate([
            'journal_number' => 'required',
            'alt_number' => '',
            'year' => 'required|digits:4|integer|min:1900|max:'.(date('Y')+1),
            'xml_file' => 'required|mimes:xml',
            'fullnumber_file' => '',
            'article_files.*' => 'required|mimes:pdf',
        ]);

        if ($validate_data['alt_number'])
            $issue_folder = $validate_data['journal_number'].'('.$validate_data['alt_number'].')';
        else
            $issue_folder = $validate_data['journal_number'].'('.$validate_data['year'].')';


        $path_articles = 'public/archive/'.$issue_folder;

        foreach ($validate_data['article_files'] as $file)
        {
            $name = $file->getClientOriginalName();
            $file->storeAs($path_articles, $name);
        }

        $path_xml = 'xml/'.$issue_folder;

        if(!Storage::exists($path_xml))
            Storage::makeDirectory($path_xml);

        $xml_name = $validate_data['xml_file']->getClientOriginalName();
        $validate_data['xml_file']->storeAs($path_xml, $xml_name);
        $XML = Storage::disk('local')->get($path_xml.'/'.$xml_name);

        $journal = simplexml_load_string($XML);

        $issue_data = array();

        $issue_data['journal_number'] = (string) $journal->issue->number;
        $issue_data['alt_number'] = (string) $journal->issue->altNumber;

        $issue_data['year'] = substr($journal->issue->dateUni, 0, 4);
        //$issue_data['published'] = (string) $journal->issue->dateUni;

        $issue_data['doi_code'] = (string) $journal->issue->doi;

        if (isset($validate_data['fullnumber_file']))
            $issue_data['fullnumber_file'] = $validate_data['fullnumber_file'];

        $request_issue_data = new Request($issue_data);

        $issue_id = $this->createIssue($request_issue_data);
        $this->editIssue($request_issue_data, $issue_id);

        $current_subject = '';
        foreach ($journal->issue->articles->children() as $child)
        {
            $article_rus = array();
            $article_eng = array();

            $article_rus['lang_id'] = 1;
            $article_eng['lang_id'] = 2;

            if($child->getName() == 'section')
            {
                $current_subject = $child->secTitle[0];
                continue;
            }

            $i = 0;
            foreach ($child->authors->author as $author)
            {
                $article_rus['authors'][$i]  = array();
                $article_eng['authors'][$i]  = array();

                $article_rus['authors'][$i]['lang_id'] = 1;
                $article_eng['authors'][$i]['lang_id'] = 2;

                $article_rus['authors'][$i]['orcid_code'] = (string) $author->authorCodes->orcid;
                $article_eng['authors'][$i]['orcid_code'] = (string) $author->authorCodes->orcid;

                $article_rus['authors'][$i]['surname'] = (string) $author->individInfo[0]->surname;
                $article_eng['authors'][$i]['surname'] = (string) $author->individInfo[1]->surname;

                $article_rus['authors'][$i]['initials'] = (string) $author->individInfo[0]->initials;
                $article_eng['authors'][$i]['initials'] = (string) $author->individInfo[1]->initials;

                $article_rus['authors'][$i]['university'] = (string) $author->individInfo[0]->orgName;
                $article_eng['authors'][$i]['university'] = (string) $author->individInfo[1]->orgName;

                $article_rus['authors'][$i]['academic_degree'] = (string) $author->individInfo[0]->otherInfo;
                $article_eng['authors'][$i]['academic_degree'] = (string) $author->individInfo[1]->otherInfo;

                $article_rus['authors'][$i]['city'] = (string) $author->individInfo[0]->city;
                $article_eng['authors'][$i]['city'] = (string) $author->individInfo[1]->city;

                $article_rus['authors'][$i]['email'] = (string) $author->individInfo[0]->email;
                $article_eng['authors'][$i]['email'] = (string) $author->individInfo[1]->email;

                $i++;
            }

            $references = array();
            if ($child->references)
                foreach ($child->references->children() as $reference)
                    $references[] = (string) $reference->refInfo->text;

            $article_rus['references'] = $references;
            $article_eng['references'] = array();

//            foreach ($child->keywords as $keywords)
//            {
//                $article_rus['keywords'] = (array) $keywords->kwdGroup[0]->keyword;
//                $article_eng['keywords'] = (array) $keywords->kwdGroup[1]->keyword;
//            }

            $article_rus['keywords'] = (array) $child->keywords->kwdGroup[0]->keyword;
            $article_eng['keywords'] = (array) $child->keywords->kwdGroup[1]->keyword;

            $article_rus['doi_code'] = (string) $child->codes->doi;
            $article_eng['doi_code'] = (string) $child->codes->doi;

            $article_rus['udk_code'] = (string) $child->codes->udk;
            $article_eng['udk_code'] = (string) $child->codes->udk;

            $article_rus['title'] = (string) $child->artTitles->artTitle[0];
            $article_eng['title'] = (string) $child->artTitles->artTitle[1];

            $article_rus['pages'] = (string) $child->pages;
            $article_eng['pages'] = (string) $child->pages;

            $subject_id = $this->getSubjectId($current_subject);
            if ($subject_id)
                $article_rus['subject_id'] = $subject_id;
            else
            {
                $new_subject = $this->createSubject($current_subject);
                $article_rus['subject_id'] = $new_subject->subject_id;
            }
            $article_eng['subject_id'] = 1;

            $article_rus['submitted'] = date("Y-m-d H:i:s", strtotime((string) $child->dates->dateReceived));
            $article_eng['submitted'] = date("Y-m-d H:i:s", strtotime((string) $child->dates->dateReceived));
//
//            $article_rus['approved'] =
//            $article_eng['approved'] =
//
            $article_rus['accepted'] = date("Y-m-d H:i:s", strtotime((string) $child->dates->dateAccepted));
            $article_eng['accepted'] = date("Y-m-d H:i:s", strtotime((string) $child->dates->dateAccepted));
//
//            $article_rus['published'] =
//            $article_eng['published'] =

            $article_rus['annotation'] = (string) $child->abstracts->abstract[0];
            $article_eng['annotation'] = (string) $child->abstracts->abstract[1];

            $article_rus['thanks'] = (string) $child->thanks[0];
            $article_eng['thanks'] = (string) $child->thanks[1];

            $article_rus['authors_contribution'] = (string) $child->authors_contribution[0];
            $article_eng['authors_contribution'] = (string) $child->authors_contribution[1];

            $article_rus['quotation'] = (string) $child->quotation[0];
            $article_eng['quotation'] = (string) $child->quotation[1];

            $article_rus['text'] = (string) $child->text;

            if ($issue_data['alt_number'])
            {
                $article_rus['path_pdf'] = 'public/archive/'.$issue_data['journal_number'].'('.$issue_data['alt_number'].')/'.$child->pages.'.pdf';
                $article_eng['path_pdf'] = 'public/archive/'.$issue_data['journal_number'].'('.$issue_data['alt_number'].')/'.$child->pages.'.pdf';
            } else
            {
                $article_rus['path_pdf'] = 'public/archive/'.$issue_data['journal_number'].'('.$issue_data['year'].')/'.$child->pages.'.pdf';
                $article_eng['path_pdf'] = 'public/archive/'.$issue_data['journal_number'].'('.$issue_data['year'].')/'.$child->pages.'.pdf';
            }

            $article_rus['downloads'] = 0;
            $article_eng['downloads'] = 0;

            $article_rus['views'] = 0;
            $article_eng['views'] = 0;

            $article_id = $this->createArticle($issue_id);

            $this->editArticle(new Request($article_rus), $article_id);
            $this->editArticle(new Request($article_eng), $article_id);

            for($i = 0; $i < count($article_rus['authors']); $i++)
            {
                $author_id = $this->createAuthor($article_id);

                $this->editAuthor(new Request($article_rus['authors'][$i]), $author_id);
                $this->editAuthor(new Request($article_eng['authors'][$i]), $author_id);
            }
        }

    }

    /**
     * Получает на вход массив с результами поиска от API и формирует в ответ массив статей
     * @param $response
     * @return array
     */
    public function searchArticles($response)
    {
        $cats = array('text', 'author', 'keyword');
        $langs = array(1 => 'ru', 2 => 'en');
        $answer = array();
        
        foreach ($response as $res)
        {
            $doc = $res['doc'];
            $article_id = $doc['doc_id'];
            $this_cat = $cats[$doc['type']];

            if (!array_key_exists($this_cat, $answer))
                $answer[$this_cat] = array();

            // с точки зрения поиска авторы также являются документами,
            // поэтому author_id передается изперемнной $doc['doc_id']
            // документами вообще считаются строки в таблицах
            if ($this_cat == 'author')
            {
                $article_id = $this->articleRepository->getArticleIdByAuthor($doc['doc_id']);
                if ($article_id == -1)
                    continue;
            }

            try {
                $article = $this->getArticle($article_id, null, true);
            } catch (ModelNotFoundException $ex) {
                // если по какой-то причине статьи нет, не выбрасываем 404
                continue;
            }

            $answer[$this_cat][] = $article;
        }

        //dd($answer);
        return $answer;
    }

    public function getPopularArticles(int $num=5)
    {
        $lang = App::currentLocale();
        $all_articles = $this->articleRepository->getArticlesOrderByViews($lang, 40)->toarray();
        $rand_keys = array_rand($all_articles, $num);

        $articles = array();
        foreach ($rand_keys as $key)
            $articles[] = $all_articles[$key];

        return $articles;
    }

//    public function searchArticles2($response)
//    {
//        $cats = array('text', 'author', 'keyword');
//        $langs = array(1 => 'ru', 2 => 'en');
//
//        $answer = array();
//
//        foreach ($response as $res)
//        {
//            $doc = $res['doc'];
//            $article_id = $doc['doc_id'];
//            $this_cat = $cats[$doc['type']];
//
//            if (!array_key_exists($this_cat, $answer))
//                $answer[$this_cat] = array();
//
//            // с точки зрения поиска, авторы также являются документами,
//            // поэтому author_id передается из переменной $doc['doc_id']
//            // документами вообще считаются любые строки в таблицах
//            if ($this_cat == 'author')
//            {
//                $article_id = $this->articleRepository->getArticleIdByAuthor($doc['doc_id']);
//                if ($article_id == -1)
//                    continue;
//            }
//
//            try {
//                $article = $this->getArticle($article_id, $langs[$doc['lang_id']], true);
//            } catch (ModelNotFoundException $ex) {
//                // если по какой-то причине статьи нет, не выбрасываем 404
//                continue;
//            }
//
//            $answer[$this_cat][] = $article;
//        }
//
//        dd($answer);
//        return $answer;
//    }
}