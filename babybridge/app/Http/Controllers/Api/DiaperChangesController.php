<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiaperChange;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DiaperChangesController extends Controller
{
    // Récupérer les changements de couches par section et date
    public function getDiaperChangesBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections.child.diaperChanges' => function ($query) use ($date) {
            $query->whereDate('happened_at', $date);
        }])->findOrFail($sectionId); // Récupère les changements de couches des enfants de la section à la date donnée

        $diaperChanges = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->diaperChanges;
        });

        return response()->json($diaperChanges);
    }

    // Mettre à jour ou créer un changement de couche
    public function storeDiaperChange(Request $request)

    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|integer|exists:children,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'poop_consistency' => 'nullable|in:normal,soft,watery',
            'notes' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);
    
        $diaperChange = DiaperChange::create([
            'child_id' => $request->child_id,
            'happened_at' => $datetime,
            'poop_consistency' => $request->poop_consistency,
            'notes' => $request->notes
        ]);
    
        return response()->json([
            'message' => 'Diaper change saved successfully',
            'diaperChange' => $diaperChange
        ]);
    }


    public function updateDiaperChange(Request $request, $id)

    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|integer|exists:children,id',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'poop_consistency' => 'nullable|in:normal,soft,watery',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);

        $diaperChange = DiaperChange::findOrFail($id);
        $diaperChange->update([
            'happened_at' => $datetime,
            'poop_consistency' => $request->poop_consistency,
            'notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Diaper change updated successfully',
            'diaperChange' => $diaperChange
        ]);
    }

    function deleteDiaperChange($id)
    {
        $diaperChange = DiaperChange::find($id);

        if ($diaperChange) {
            $diaperChange->delete();
            return response()->json(['message' => 'Diaper change deleted successfully']);
        } else {
            return response()->json(['message' => 'Diaper change not found'], 404);
        }
    }

    public function getDiaperChange($id)
    {
        $diaperChange = DiaperChange::find($id);

        if ($diaperChange) {
            return response()->json($diaperChange);
        } else {
            return response()->json(['message' => 'Diaper change not found'], 404);
        }
    }

    
}
