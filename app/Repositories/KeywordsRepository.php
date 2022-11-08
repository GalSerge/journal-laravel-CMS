<?php
namespace App\Repositories;

use App\Models\Keyword;


class KeywordsRepository
{
    protected $keyword;

    /**
     * @param Keyword $keyword
     */
    public function __construct(Keyword $keyword)
    {
        $this->keyword = $keyword;
    }

    /**
     * Добавляет ключевые слова
     * @param array $keywords
     * @return void
     */
    public function add(array $keywords)
    {
        $this->keyword->insert($keywords);
    }

    /**
     * Возвращает коллекцию ключевых слов для статьи
     * @param string $lang
     * @param int $article_id
     * @return void
     */
    public function getKeywords(string $lang, int $article_id)
    {
        return $this->keyword->join('languages', 'keywords.lang_id', '=', 'languages.lang_id')
            ->select('keywords.*')
            ->where('languages.code', $lang)
            ->where('article_id', $article_id)
            ->get();
    }
}