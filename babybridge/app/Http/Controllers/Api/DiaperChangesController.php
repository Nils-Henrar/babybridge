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
        }])->findOrFail($sectionId);

        $diaperChanges = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->diaperChanges;
        });

        return response()->json($diaperChanges);
    }

    // Mettre à jour ou créer un changement de couche
    public function storeOrUpdateDiaperChange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|integer|exists:children,id',
            'date' => 'required|date',
            'time' => 'required', // Just the time
            'poop_consistency' => 'nullable|in:normal,soft,watery',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL
    
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);

        $diaperChange = DiaperChange::updateOrCreate( 
            [
                'child_id' => $request->child_id,
                'happened_at' => $datetime
            ],
            [
                'poop_consistency' => $request->poop_consistency,
                'notes' => $request->notes
            ]
        );

        return response()->json([
            'message' => 'Diaper change saved successfully',
            'diaperChange' => $diaperChange
        ]);
    }
}
