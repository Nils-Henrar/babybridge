<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Nap;
use Illuminate\Support\Facades\Validator;
use App\Models\Section;
use Illuminate\Support\Facades\Log;

class NapController extends Controller
{
    public function getNapsBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections.child.naps' => function ($query) use ($date) {
            $query->whereDate('started_at', '=', $date);
        }])->findOrFail($sectionId);

        $naps = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->naps;
        });

        return response()->json($naps);
    }

    public function storeNap(Request $request)
    {
        $request->validate([
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:children,id',
            'started_at' => 'required|date_format:Y-m-d H:i:s',
            'ended_at' => 'nullable|date_format:Y-m-d H:i:s',
            'quality' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);
    
        foreach ($request->child_ids as $childId) {
            Nap::create([
                'child_id' => $childId,
                'started_at' => $request->started_at,
                'ended_at' => $request->ended_at,
                'quality' => $request->quality,
                'notes' => $request->notes
            ]);
        }
    
        return response()->json(['message' => 'Sieste enregistrée avec succès']);
    }


    public function updateNap(Request $request, $napId)
    {

        Log::info('Received data:', $request->all());
        $request->validate([
            'nap_id' => 'required|integer|exists:naps,id',
            'started_at' => 'required|date_format:Y-m-d H:i:s',
            'ended_at' => 'nullable|date_format:Y-m-d H:i:s',
            'quality' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $nap = Nap::findOrFail($napId);
        $nap->update([
            'started_at' => $request->started_at,
            'ended_at' => $request->ended_at,
            'quality' => $request->quality,
            'notes' => $request->notes
        ]);

        return response()->json(['message' => 'Sieste mise à jour avec succès']);
    }

    public function getNap($napId)
    {
        $nap = Nap::findOrFail($napId);

        return response()->json($nap);
    }


    function deleteNap($napId)
    
    {
        $nap = Nap::findOrFail($napId);

        if($nap){
            $nap->delete();
            return response()->json(['message' => 'Sieste supprimée avec succès']);
        } else {
            return response()->json(['message' => 'Sieste non trouvée'], 404);
        }

    }

}    
