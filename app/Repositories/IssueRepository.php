<?php
namespace App\Repositories;

use App\Models\Issue;


class IssueRepository
{
    protected $issue;

    /**
     * @param Issue $issue
     */
    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * Добавляет в БД новый выпуск
     * @return Issue
     */
    public function create()
    {
        return $this->issue->create();
    }

    /**
     * Редактирует существующий выпуск
     * @param array $data данные для редактирования
     * @param int $id id редактируемого выпуска
     * @return void
     */
    public function editIssue(array $data, int $id)
    {
        $this->issue->where('issue_id', $id)->update($data);
    }

    /**
     * Возвращет коллекцию всех выпусков
     * @return mixed
     */
    public function getAllIssues()
    {
        return $this->issue->select('issues.*')
            ->orderBy('year', 'DESC')
            ->orderBy('journal_number', 'ASC')
            ->get();
    }

    /**
     * Возвращает выпуск (модуль) по году и номеру в году
     * @param int $year год выпуска
     * @param int $journal_number номер выпуска в году
     * @return mixed
     */
    public function getIssue(int $year, int $journal_number)
    {
        return $this->issue->where('year', $year)
            ->where('journal_number', $journal_number)
            ->firstOrFail();
    }

    public function getIssueById(int $issue_id)
    {
        return $this->issue->where('issue_id', $issue_id)
            ->firstOrFail();
    }

    /**
     * Возвращает последний выпуск
     * @return mixed
     */
    public function getLastIssue()
    {
        return $this->issue->select('issues.*')
            ->orderBy('year', 'DESC')
            ->orderBy('journal_number', 'DESC')
            ->firstOrFail();
    }
}