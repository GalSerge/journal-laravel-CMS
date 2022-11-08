<?php
namespace App\Services;

use App\Repositories\SectionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class SectionService
{
    /**
     * @var SectionRepository
     */
    protected $sectionRepository;

    /**
     * SectionService constructor.
     * @param SectionRepository $sectionRepository
     */
    public function __construct(SectionRepository $sectionRepository)
    {
        $this->sectionRepository = $sectionRepository;
    }

    public function getSectionById(int $id, string $lang)
    {
        $page = $this->sectionRepository->getById($id, $lang);
        return $page->toArray();
    }

    public function getSection(string $address)
    {
        $lang = App::currentLocale();
        $section = $this->sectionRepository->getSection($address, $lang);
        return $section->toArray();
    }

    public function createSection()
    {
        $section_id = $this->sectionRepository->getNextSectionId();

        $this->sectionRepository->create($section_id, 1);
        $this->sectionRepository->create($section_id, 2);

        return $section_id;
    }

    public function editSection(Request $request, int $id)
    {
        $validate_data = $request->validate([
            'lang_id' => 'required',
            'active' => '',
            'show_in_menu' => '',
            'address' => '',
            'order' => '',
            'parent_id' => '',
            'is_module' => '',
            'title' => '',
            'description' => '',
            'text' => ''
        ]);

        $lang_id = $validate_data['lang_id'];

        $user = session()->get('user');
        if (empty($user))
            return redirect()->route('loginForm');
        else
            $validate_data['user_id'] = $user->user_id;

        if (!$this->sectionRepository->isFreeAddress($validate_data['address'], $id))
            return back()->withError('Страница с таким адресом уже существует')->withInput();

        $this->sectionRepository->editSection($validate_data, $id, $lang_id);
    }

    public function getAllSections()
    {
        $sections = $this->sectionRepository->getAllSections('ru');

        $sections_tree = array();

        foreach ($sections as $section)
            if ($section->parent_id == 0)
                $sections_tree[$section->section_id] = $section->toArray();
            else if (isset($sections_tree[$section->parent_id]))
            {
                if (!isset($sections_tree[$section->parent_id]['subpages']))
                    $sections_tree[$section->parent_id]['subpages'] = array();

                $sections_tree[$section->parent_id]['subpages'][$section->section_id] = $section->toArray();
            }

        return $sections_tree;
    }

    public function getParents(string $lang)
    {
        $sections = $this->sectionRepository->getByParent(0, $lang);
        $sections = $sections->toArray();

        if ($lang == 'ru')
            $title_main = 'Главная страница';
        else
            $title_main = 'Main page';

        $mainpage = [
            'section_id' => 0,
            'title' => $title_main
        ];

        array_unshift($sections, $mainpage);
        return $sections;
    }

    public function getSectionsForMenu()
    {
        $lang = App::currentLocale();
        $sections = $this->sectionRepository->getAllSections($lang);
        
        $menu_array = array();

        foreach ($sections as $section)
        {
            if ($section['active'] && $section['show_in_menu'])
                if ($section->parent_id == 0)
                    $menu_array[$section->section_id] = $section->toArray();
                else if (isset($menu_array[$section->parent_id]))
                {
                    if (!isset($menu_array[$section->parent_id]['subpages']))
                        $menu_array[$section->parent_id]['subpages'] = array();

                    $menu_array[$section->parent_id]['subpages'][$section->section_id] = $section->toArray();
                }
        }

        return $menu_array;
    }
}