<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\IssueService;
use App\Services\SectionService;
use App\Services\EditorService;
use App\Services\UserService;


class AdminController extends Controller
{
    protected $issue;
    protected $section;
    protected $editor;
    protected $user;

    public function __construct(IssueService $issue, SectionService $section, EditorService $editor, UserService $user)
    {
        $this->issue = $issue;
        $this->section = $section;
        $this->editor = $editor;
        $this->user = $user;
    }

    public function index()
    {
        return view('admin.main',
        [
            'title' => 'Добро пожаловать в панель администрирования'
        ]);
    }


    public function addXML()
    {
        return view('admin.pages.addxml' ,[
            'title' => 'Добавить выпуск XML',
            'log' => '']);
    }

    public function addXMLAction(Request $request)
    {
        $msg = 'Новый выпуск добавлен';

        try {
            $this->issue->parseXML($request);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect()->back()->with('msg', $msg);
    }


    public function getAdmins()
    {
        $users = $this->user->getAllUsers();

        return view('admin.pages.admins', [
            'users' => $users,
            'title' => 'Администраторы'
        ]);
    }

    public function addAdmin()
    {
        return view('admin.pages.editadmin', [
            'title' => 'Добавить администратора',
        ]);
    }

    public function addAdminAction(Request $request)
    {
        $msg = 'Новый администратор добавлен';

        //обработка checkbox-ов
        if (!isset($request['active']))
            $request['active'] = 0;

        try {
            $user_id = $this->user->createUser();
            $this->user->editUser($request, $user_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg);
        }

        return redirect('/admin/edit-admin/'.$user_id)->with('msg', $msg)->withInput();
    }

    public function editAdmin($user_id)
    {
        $user = $this->user->getUserById($user_id);

        return view('admin.pages.editadmin', [
            'user' => $user,
            'title' => 'Редактировать информацию об администраторе',
        ]);
    }

    public function editAdminAction(Request $request, $user_id)
    {
        $msg = 'Данные успешно изменены';

        //обработка checkbox-ов
        if (!isset($request['active']))
            $request['active'] = 0;

        try {
            $this->user->editUser($request, $user_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return back()->with('msg', $msg);
        }

       return redirect('/admin/edit-admin/'.$user_id)->with('msg', $msg);
    }


    public function getSections()
    {
        $sections = $this->section->getAllSections();

        return view('admin.pages.sections', [
            'sections' => $sections,
            'title' => 'Страницы'
        ]);
    }

    public function addSection()
    {
        $parents_rus = $this->section->getParents('ru');
        $parents_eng = $this->section->getParents('en');

        return view('admin.pages.editsection', [
            'parents_eng' => $parents_eng,
            'parents_rus' => $parents_rus,
            'title' => 'Добавить страницу',
        ]);
    }

    public function addSectionAction(Request $request)
    {
        $msg = 'Новая страница добавлена';

        $data = $request->toArray();

        //обработка checkbox-ов
        if (!isset($data['is_module']))
            $data['is_module'] = 0;

        if (!isset($data['section_rus']['active']))
            $data['section_rus']['active'] = 0;

        if (!isset($data['section_eng']['active']))
            $data['section_eng']['active'] = 0;

        if (!isset($data['section_rus']['show_in_menu']))
            $data['section_rus']['show_in_menu'] = 0;

        if (!isset($data['section_eng']['show_in_menu']))
            $data['section_eng']['show_in_menu'] = 0;

        $section_rus = $data['section_rus'];
        $section_rus['address'] = $data['address'];
        $section_rus['is_module'] = $data['is_module'];

        $section_eng = $data['section_eng'];
        $section_eng['address'] = $data['address'];
        $section_eng['is_module'] = $data['is_module'];

        try {
            $section_id = $this->section->createSection();

            $this->section->editSection(new Request($section_rus), $section_id);
            $this->section->editSection(new Request($section_eng), $section_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect('/admin/edit-section/'.$section_id)->with('msg', $msg)->withInput();
    }

    public function editSection($section_id)
    {
        $section_eng = $this->section->getSectionById($section_id, 'en');
        $section_rus = $this->section->getSectionById($section_id, 'ru');

        $parents_rus = $this->section->getParents('ru');
        $parents_eng = $this->section->getParents('en');

        return view('admin.pages.editsection', [
            'section_rus' => $section_rus,
            'section_eng' => $section_eng,
            'parents_eng' => $parents_eng,
            'parents_rus' => $parents_rus,
            'title' => 'Редактировать информацию о странице'
        ]);
    }

    public function editSectionAction(Request $request, $section_id)
    {
        $msg = 'Данные успешно изменены';

        $data = $request->toArray();

        //обработка checkbox-ов
        if (!isset($data['is_module']))
            $data['is_module'] = 0;

        if (!isset($data['section_rus']['active']))
            $data['section_rus']['active'] = 0;

        if (!isset($data['section_eng']['active']))
            $data['section_eng']['active'] = 0;

        if (!isset($data['section_rus']['show_in_menu']))
            $data['section_rus']['show_in_menu'] = 0;

        if (!isset($data['section_eng']['show_in_menu']))
            $data['section_eng']['show_in_menu'] = 0;

        $section_rus = $data['section_rus'];
        $section_rus['address'] = $data['address'];
        $section_rus['is_module'] = $data['is_module'];
//        $section_rus['lang_id'] = 1;

        $section_eng = $data['section_eng'];
        $section_eng['address'] = $data['address'];
        $section_eng['is_module'] = $data['is_module'];
//        $section_eng['lang_id'] = 2;

        try {
            $this->section->editSection(new Request($section_rus), $section_id);
            $this->section->editSection(new Request($section_eng), $section_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect()->back()->with('msg', $msg)->withInput();
    }


    public function getEditors()
    {
        $editors = $this->editor->getAllEditors();
        return view('admin.pages.editors', [
            'editors' => $editors,
            'title' => 'Редакторы'
        ]);
    }

    public function addEditor()
    {
        return view('admin.pages.editeditor', [
            'title' => 'Добавить редактора',
        ]);
    }

    public function addEditorAction(Request $request)
    {
        $msg = 'Новый редактор добавлен';

        //обработка checkbox-ов
        if (!isset($request['active']))
            $request['active'] = 0;

        if (!isset($request['is_main']))
            $request['is_main'] = 0;

        try {
            $editor_id = $this->editor->createEditor();
            $this->editor->editEditor($request, $editor_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect('/admin/edit-editor/'.$editor_id)->with('msg', $msg)->withInput();
    }

    public function editEditor($editor_id)
    {
        $editor_rus = $this->editor->getEditor($editor_id, 'ru');
        $editor_eng = $this->editor->getEditor($editor_id, 'en');

        return view('admin.pages.editeditor', [
            'editor_rus' => $editor_rus,
            'editor_eng' => $editor_eng,
            'title' => 'Редактировать информацию о редакторе'
        ]);
    }

    public function editEditorAction(Request $request, $editor_id)
    {
        $msg = 'Данные успешно изменены';

        //обработка checkbox-ов
        if (!isset($request['active']))
            $request['active'] = 0;

        if (!isset($request['is_main']))
            $request['is_main'] = 0;

        try {
            $this->editor->editEditor($request, $editor_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect()->back()->with('msg', $msg)->withInput();
    }


    public function getArticles($issue_id)
    {
        $articles = $this->issue->getArticles($issue_id);
        $output_articles = '';

        foreach($articles as $subject)
        {
            $output_articles .= '<b>'.$subject['subject_title'].'</b>';
            $output_articles .= '<ul>';
            foreach($subject['articles_list'] as $article)
            {
                $output_articles .=
                    '<li class="edit-item">'.
                    '<a href="/admin/edit-article/'.$article['article_id'].'"><img src="/public/images/icons/edit.svg" title="Редактировать"></a>&nbsp;'.
                    $article['title'].
                    '</li>';
            }

            $output_articles .= '</ul>';
        }

        return response()->json(array('list'=> $output_articles), 200);
    }

    public function addArticle($issue_id)
    {
        $subjects_rus = $this->issue->getAllSubjects(1);
        $subjects_eng = $this->issue->getAllSubjects(2);

        return view('admin.pages.editarticle', [
            'title' => 'Добавить статью',
            'subjects_rus' => $subjects_rus,
            'subjects_eng' => $subjects_eng,
            'issue_id' => $issue_id
            ]);
    }

    public function addArticleAction(Request $request, $issue_id)
    {
        $msg = 'Новая статья добавлена';
        try {
            $issue_id = $request['issue_id'];
            $article_id = $this->issue->createArticle($issue_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }
        try
        {
            if (is_array($request['authors_rus']))
            {
                for($i = 0; $i < count($request['authors_rus']); $i++)
                {
                    if ($request['authors_rus'][$i]['author_id'] == '')
                        $author_id = $this->issue->createAuthor($article_id);
                    else
                        $author_id = $request['authors_rus'][$i]['author_id'];

                    $this->issue->editAuthor(new Request($request['authors_rus'][$i]), $author_id);
                    $this->issue->editAuthor(new Request($request['authors_eng'][$i]), $author_id);
                }
            }

            $request['keywords'] = explode("\r\n", $request['keywords']);
            $request['references'] = explode("\r\n\r\n", $request['references']);

            $this->issue->editArticle($request, $article_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect('/admin/edit-article/'.$article_id)->with('msg', $msg)->withInput();
        }

        return redirect('/admin/edit-article/'.$article_id)->with('msg', $msg)->withInput();
    }

    public function editArticle($article_id)
    {
        $article_rus = $this->issue->getArticle($article_id, 'ru');
        $article_eng = $this->issue->getArticle($article_id, 'en');

        $subjects_rus = $this->issue->getAllSubjects(1);
        $subjects_eng = $this->issue->getAllSubjects(2);
      
        return view('admin.pages.editarticle', [
            'article_rus' => $article_rus,
            'article_eng' => $article_eng,
            'subjects_rus' => $subjects_rus,
            'subjects_eng' => $subjects_eng,
            'title' => 'Редактировать статью']);
    }

    public function editArticleAction(Request $request, int $article_id)
    {
        $msg = 'Данные успешно изменены';
    
        try
        {
            if (is_array($request['authors_rus']))
            {
                for($i = 0; $i < count($request['authors_rus']); $i++)
                {
                    if ($request['authors_rus'][$i]['author_id'] == '')
                        $author_id = $this->issue->createAuthor($article_id);
                    else
                        $author_id = $request['authors_rus'][$i]['author_id'];

                    $this->issue->editAuthor(new Request($request['authors_rus'][$i]), $author_id);
                    $this->issue->editAuthor(new Request($request['authors_eng'][$i]), $author_id);
                }
            }

            $request['keywords'] = explode("\r\n", $request['keywords']);
            $request['references'] = explode("\r\n\r\n", $request['references']);

            $this->issue->editArticle($request, $article_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect()->back()->with('msg', $msg)->withInput();
    }


    public function getIssues()
    {
        $issues = $this->issue->getArchive();
        return view('admin.pages.issues', [
            'archive' => $issues,
            'title' => 'Выпуски'
            ]);
    }

    public function addIssue()
    {
        return view('admin.pages.editissue', ['title' => 'Добавить выпуск']);
    }

    public function addIssueAction(Request $request)
    {
        $msg = 'Новый выпуск добавлен';
        try {
            $issue_id = $this->issue->createIssue($request);
            $this->issue->editIssue($request, $issue_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
            return redirect()->back()->with('msg', $msg)->withInput();
        }

        return redirect('/admin/edit-issue/'.$issue_id)->with('msg', $msg)->withInput();
    }

    public function editIssue($issue_id)
    {
        $issue = $this->issue->getIssueById($issue_id);

        return view('admin.pages.editissue', [
            'issue' => $issue,
            'title' => 'Редактировать выпуск']);
    }

    public function editIssueAction(Request $request, int $issue_id)
    {
        $msg = 'Данные успешно изменены';
        try {
            $this->issue->editIssue($request, $issue_id);
        } catch (\Exception $exception) {
            $msg = $exception->getMessage();
        }

        return redirect()->back()->with('msg', $msg);
    }

}
