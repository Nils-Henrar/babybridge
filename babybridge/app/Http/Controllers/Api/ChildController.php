<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Section;
use App\Http\Resources\ChildResource;

class ChildController extends Controller
{
    // les enfants de la section du worker

    public function getChildrenBySection($sectionId)
    {
        
        $section = Section::with(['currentChildren.child'])->find($sectionId);
        if (!$section) {
            return response()->json(['message' => 'Section not found'], 404);
        }

        $children = $section->currentChildren->map(function ($childSection) {
            return $childSection->child;
        });

        return response()->json($children);
    }

    public function getChild($childId)
    {
        $child = Child::find($childId);
        if (!$child) {
            return response()->json(['message' => 'Child not found'], 404);
        }

        return response()->json($child);
    }
}
