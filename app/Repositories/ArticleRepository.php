<?php
namespace App\Repositories;

use App\Models\Article;


class ArticleRepository
{
    protected $article;

    /**
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Добавляет новую статью в БД
     * @param int $article_id id новой статьи
     * @param int $issue_id id выпуска
     * @param int $lang_id id языка
     * @return Article
     */
    public function create(int $article_id, int $issue_id, int $lang_id)
    {
        return $this->article->create([
            'article_id' => $article_id,
            'issue_id' => $issue_id,
            'lang_id' => $lang_id]);
    }

    /**
     * Редактирует данные существующей статьи
     * @param array $data данные для изменения
     * @param int $article_id article_id статьи
     * @param int $lang_id id языка
     * @return void
     */
    public function editArticle(array $data, int $article_id, int $lang_id)
    {
        $this->article->where('article_id', $article_id)
            ->where('lang_id', $lang_id)
            ->update($data);
    }

    /**
     * Возвращает коллекцию всех статей выпуска
     * @param string $lang код языка
     * @param int $issue_id id выпуска
     * @return mixed
     */
    public function getAllArticles(string $lang, int $issue_id)
    {
        // ->orderBy('subject_id', 'ASC')
        // возможно, что в выпусках разделы идут в разном порядке,
        // поэтому для сохранения нумерации используется только сортировка по id (они последовательные)
        return $this->article->join('languages', 'articles.lang_id', '=', 'languages.lang_id')
            ->join('subjects', 'articles.subject_id', '=', 'subjects.subject_id')
            ->select('articles.*', 'subjects.title as subject_title')
            ->where('languages.code', $lang)
            ->where('articles.issue_id', $issue_id)
            ->where('articles.is_published', 1)
            ->orderBy('article_id', 'ASC')
            ->get();
    }

    /**
     * Возвращает статью (модель)
     * @param string $lang код языка
     * @param int $article_id статьи
     * @return Article
     */
    public function getArticle(string $lang, int $article_id)
    {
        return $this->article->join('languages', 'articles.lang_id', '=', 'languages.lang_id')
            ->join('issues', 'articles.issue_id', '=', 'issues.issue_id')
            ->join('subjects', 'articles.subject_id', '=', 'subjects.subject_id')
            ->select('articles.*',
                'issues.alt_number',
                'issues.journal_number', 
                'issues.year',
                'subjects.title as subject_title',
                'languages.code as lang_code')
            ->where('languages.code', $lang)
            ->where('article_id', $article_id)
            ->firstOrFail();
    }

    public function getNextArticleId()
    {
        $last_article = $this->article->select('article_id')
            ->latest('article_id')
            ->first();

        if (!$last_article)
            return 1;
        else
            return ++$last_article->article_id;
    }

    public function updateViews(int $article_id, int $views)
    {
        $this->article->where('article_id', $article_id)->update(['views' => $views],
                                                                  ['timestamps' => false]);
    }

    /** Возвращает id статьи по id автора. Используется для поиска статей
     * @param int $author_id id автора
     * @return mixed
     */
    public function getArticleIdByAuthor(int $author_id)
    {
        $article = $this->article->join('authors', 'articles.article_id', '=', 'authors.article_id')
            ->select('articles.article_id')
            ->where('author_id', $author_id)
            ->first();

        if (!$article)
            return -1;
        else
            return $article->article_id;
    }

    /**
     * Возвращает $number статей, отсортированных по убыванию просмотров
     * @param string $lang код языка
     * @param int $number число статей
     * @return mixed
     */
    public function getArticlesOrderByViews(string $lang, int $number)
    {
        return $this->article->join('languages', 'articles.lang_id', '=', 'languages.lang_id')
            ->join('issues', 'articles.issue_id', '=', 'issues.issue_id')
            ->select('articles.*', 'issues.*')
            ->where('languages.code', $lang)
            ->where('articles.is_published', 1)
            ->orderBy('views', 'DESC')
            ->limit($number)
            ->get();
    }
}