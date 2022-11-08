<?php
namespace App\Services;

use App\Repositories\EditorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;


class editorService
{
    protected $editorRepository;

    /**
     * editorService constructor.
     * @param EditorRepository $editorRepository
     */
    public function __construct(EditorRepository $editorRepository)
    {
        $this->editorRepository = $editorRepository;
    }

    public function getEditor($id, $lang=null)
    {
        $editor = null;

        if (!$lang)
            $lang = App::currentLocale();

        $editor = $this->editorRepository->geteditor($id, $lang);
        $editor = $editor->toArray();

        return $editor;
    }

    public function getAllEditors()
    {
        $lang = App::currentLocale();
        $editors = $this->editorRepository->getAllEditors($lang);

        $editors_array = array('main' => null,
            'all' => array());

        foreach ($editors as $editor)
            if ($editor->is_main == 1)
                $editors_array['main'] = $editor->toArray();
            else
                $editors_array['all'][] = $editor->toArray();
            
        return $editors_array;
    }

    public function createEditor()
    {
        $editor_id = $this->editorRepository->getNextEditorId();

        $this->editorRepository->create($editor_id, 1);
        $this->editorRepository->create($editor_id, 2);

        return $editor_id;
    }

    public function editEditor(Request $request, $id)
    {
        $validate_data = $request->validate([
            'lang_id' => 'required',
            'active' => 'boolean',
            'is_main' => 'boolean',
            'surname' => 'required',
            'initials' => '',
            'path_img' => '',
            'academic_degree' => '',
            'country_city' => '',
            'post' => '',
            'university' => '',
            'scientific_interests' => '',
            'scientific_spec' => '',
            'rinc_code' => '',
            'orcid_code' => '',
            'reseacher_code' => '',
            'important_publics' => '',
            'contacts' => '',
            'grant_activities' => '',
            'expert_activities' => '',
            'more_information' => '',
            'path_img' => '',
            'file_img' => 'image'
        ]);

        $lang_id = $validate_data['lang_id'];

        $user = session()->get('user');
        if (empty($user))
            return redirect()->route('loginForm');
        else
            $validate_data['user_id'] = $user->user_id;

        if (isset($validate_data['file_img']))
        {
            $folder = 'public/images/editors';
//            $file_name = $validate_data['file_img']->getClientOriginalName();
            $file_name = $validate_data['file_img']->hashName();
            $validate_data['file_img']->storeAs(
                $folder,
                $file_name
            );
            $validate_data['path_img'] = str_replace('/storage/', '', $validate_data['path_img']);
            Storage::disk('public')->delete($validate_data['path_img']);
            $validate_data['path_img'] = '/storage/images/editors/'.$file_name;

            if ($lang_id == 1)
                $this->editorRepository->editEditor(['path_img' => $validate_data['path_img']], $id, 2);
            else
                $this->editorRepository->editEditor(['path_img' => $validate_data['path_img']], $id, 1);
        }
        unset($validate_data['file_img']);


        $this->editorRepository->editEditor($validate_data, $id, $lang_id);
    }
}