<?php
namespace App\Repositories;

use App\Models\Author;


class AuthorRepository
{
    protected $author;

    /**
     * @param Author $author
     */
    public function __construct(Author $author)
    {
        $this->author = $author;
    }

    /**
     * Добавляет в БД нового автора
     * @param int $author_id id нового автора
     * @param int $article_id id статьи автора
     * @param int $lang_id id языка
     * @return Author
     */
    public function create(int $author_id, int $article_id, int $lang_id)
    {
        return $this->author->create(['author_id' => $author_id,
            'article_id' => $article_id,
            'lang_id' => $lang_id]);
    }

    /**
     * Редактирует данные существующего автора
     * @param array $data данные ля изменения
     * @param int $author_id id автора
     * @param int $lang_id id языка
     * @return void
     */
    public function editAuthor(array $data, int $author_id, int $lang_id)
    {
        $this->author->where('author_id', $author_id)
            ->where('lang_id', $lang_id)
            ->update($data);
    }

    /**
     * Возвращает коллекцию авторов статьи
     * @param string $lang код языка
     * @param int $article_id id статьи
     * @return mixed
     */
    public function getArticleAuthors(string $lang, int $article_id)
    {
        return $this->author->join('languages', 'authors.lang_id', '=', 'languages.lang_id')
            ->select('authors.*')
            ->where('languages.code', $lang)
            ->where('authors.article_id', $article_id)
            ->get();
    }

    /**
     * Возвращает данные об авторе (модель)
     * @param string $lang код языка
     * @param int $author_id id автора
     * @return mixed
     */
    public function getAuthor(string $lang, int $author_id)
    {
        return $this->author->join('languages', 'authors.lang_id', '=', 'languages.lang_id')
            ->select('authors.*')
            ->where('languages.code', $lang)
            ->where('authors.author_id', $author_id)
            ->firstOrFail();
    }

    public function getNextAuthorId()
    {
        $last_author = $this->author->select('author_id')
            ->latest('author_id')
            ->first();

        if (!$last_author)
            return 1;
        else
            return ++$last_author->author_id;
    }
}