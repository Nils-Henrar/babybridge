<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Child;
use App\Models\Section;
use App\Http\Resources\ChildResource;
use App\Models\ChildTutor;
use Illuminate\Support\Facades\Log;

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
        }); // Récupère les enfants qui sont actuellement dans la section

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


    public function getChildrenBySectionAndDate($sectionId, $date)
    {
        try {
            $section = Section::with(['childSections.child' => function ($query) use ($date) {
                $query->whereHas('attendances', function ($q) use ($date) {
                    $q->whereDate('attendance_date', '=', $date);
                }); 
            }])->findOrFail($sectionId);

            $children = $section->childSections->map(function ($cs) {
                return $cs->child; 
            });

            return response()->json($children);
        } catch (\Exception $e) {
            Log::error('Failed to load children by section and date', [
                'sectionId' => $sectionId,
                'date' => $date,
                'error' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Server error'], 500);
        }
    }

    public function getChildrenByUser($userId)
    {
        $children = ChildTutor::where('user_id', $userId)->with('child')->get()->pluck('child');
        // Récupère les enfants associés à un utilisateur
        return response()->json($children);
    }
    
}
