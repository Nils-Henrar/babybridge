<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use Illuminate\Support\Str;
use App\Models\Type;
use App\Models\SectionType;


class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function adminIndex()
    {

        $sections = Section::all();

        return view('admin.section.index', [
            'sections' => $sections,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Display the form to create a new section

        return view('admin.section.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSectionRequest $request)
    {
        // Store a new section

        $section = new Section();

        $data = $request->validated();

        $section->name = $data['name'];
        // Generate a slug from the name
        $section->slug = Str::slug($data['name']);

        $section->save();

        return redirect()->route('admin.section.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Display the details of a section

        $section = Section::find($id);

        return view('admin.section.show', [
            'section' => $section,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //

        $section = Section::find($id);

        $types = Type::all();

        return view('admin.section.edit', [
            'section' => $section,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * 
     * @return \Illuminate\Http\Response
     * 
     * 
     */
    public function update(UpdateSectionRequest $request, string $id)
    {
        // Update a section

        $section = Section::find($id);

        $data = $request->validated();

        $section->name = $data['name'];

        $sectionType = $section->currentType();

        if ($data['type_id'] != $sectionType->type->id) {
            $sectionType->to = now();
            $sectionType->save();

            $newSectionType = new SectionType();
            $newSectionType->section_id = $section->id;
            $newSectionType->type_id = $data['type_id'];

            $newSectionType->save();
        }

        // Generate a slug from the name
        $section->slug = Str::slug($data['name']);

        $section->save();

        return redirect()->route('admin.section.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete a section

        $section = Section::find($id);

        $section->delete();

        return redirect()->route('admin.section.index');
    }
}
