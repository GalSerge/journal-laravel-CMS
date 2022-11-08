<?php
namespace App\Repositories;

use App\Models\Section;


class SectionRepository
{
    protected $section;

    public function __construct(Section $section)
    {
        $this->section = $section;
    }

    public function getById(int $id, string $lang)
    {
        return $this->section->join('languages', 'sections.lang_id', '=', 'languages.lang_id')
            ->select('sections.*')
            ->where('languages.code', $lang)
            ->where('sections.section_id', $id)
            ->firstOrFail();
    }

    public function getByParent(int $parent_id, string $lang)
    {
        return $this->section->join('languages', 'sections.lang_id', '=', 'languages.lang_id')
            ->select('sections.*')
            ->where('languages.code', $lang)
            ->where('sections.parent_id', $parent_id)
            ->get();
    }

    public function getSection(string $address, string $lang)
    {
        return $this->section->join('languages', 'sections.lang_id', '=', 'languages.lang_id')
            ->select('sections.*', 'languages.code as lang_code', 'languages.title as lang_title')
            ->where('languages.code', $lang)
            ->where('sections.address', $address)
            ->where('sections.active', '=', 1)
            ->firstOrFail();
    }

    public function getAllSections(string $lang)
    {
        return $this->section->join('languages', 'sections.lang_id', '=', 'languages.lang_id')
            ->select('sections.*', 'languages.code as lang_code', 'languages.title as lang_title')
            ->where('languages.code', $lang)
            ->orderBy('sections.parent_id')
            ->orderBy('sections.order')
            ->get();
    }

    public function isFreeAddress(string $address, int $section_id)
    {
        $matching_addresses = $this->section->where('address', $address)
            ->where('section_id', '<>', $section_id)
            ->get();

        if (count($matching_addresses) == 0)
            return True;
        else
            return False;
    }

    public function create(int $section_id, int $lang_id)
    {
        $section = new Section();

        $section->lang_id = $lang_id;
        $section->section_id = $section_id;
        $section->save();

        return $section;
    }

    public function editSection(array $data, int $id, int $lang_id)
    {
        $this->section->where('section_id', $id)
            ->where('lang_id', $lang_id)
            ->update($data);
    }

    public function isActiveSection(string $address, string $lang)
    {
        $test_section = $this->section->join('languages', 'sections.lang_id', '=', 'languages.lang_id')
            ->select('sections.section_id')
            ->where('languages.code', $lang)
            ->where('sections.address', $address)
            ->where('sections.active', '=', 1)
            ->first();

        if ($test_section)
            return True;
        else
            return False;
    }

    public function getNextSectionId()
    {
        $last_section = $this->section->select('section_id')
            ->latest('section_id')
            ->first();

        if (!$last_section)
            return 1;
        else
            return ++$last_section->section_id;
    }


}