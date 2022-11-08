<?php
namespace App\Repositories;

use App\Models\Editor;


class EditorRepository
{
    protected $editor;

    public function __construct(Editor $editor)
    {
        $this->editor = $editor;
    }

    public function getEditor(int $id, string $lang)
    {
        return $this->editor->join('languages', 'editors.lang_id', '=', 'languages.lang_id')
            ->select('editors.*')
            ->where('languages.code', $lang)
            ->where('editors.editor_id', $id)
            ->firstOrFail();
    }

    public function getAllEditors(string $lang)
    {
        return $this->editor->join('languages', 'editors.lang_id', '=', 'languages.lang_id')
            ->select('editors.*', 'languages.code as lang_code', 'languages.title as lang_title')
            ->where('languages.code', $lang)
            ->orderBy('editors.is_main', 'desc')
            ->orderBy('editors.surname', 'asc')
            ->get();
    }

    public function create(int $editor_id, int $lang_id)
    {
        return $this->editor->create([
            'editor_id' => $editor_id,
            'lang_id' => $lang_id]);
    }

    public function editEditor(array $data, int $editor_id, int $lang_id)
    {
        $this->editor->where('editor_id', $editor_id)
            ->where('lang_id', $lang_id)
            ->update($data);
    }

    public function getNextEditorId()
    {
        $last_editor = $this->editor->select('editor_id')
            ->latest('editor_id')
            ->first();

        if (!$last_editor)
            return 1;
        else
            return ++$last_editor->editor_id;
    }
}