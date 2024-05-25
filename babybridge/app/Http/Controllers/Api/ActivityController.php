<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityChild;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Activity;
use App\Models\Child;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    // Méthode pour récupérer les activités des enfants par section et date
    public function getActivitiesBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections' => function ($query) use ($date) {
            $query->whereHas('child.attendances', function ($q) use ($date) {
                $q->whereDate('attendance_date', $date); 
            }); // Récupère les enfants qui ont été présents à la section à la date donnée
        }, 'childSections.child.activityChildren' => function ($query) use ($date) {
            $query->whereDate('performed_at', $date)->with('activity');
        }])->findOrFail($sectionId); // Récupère les activités des enfants de la section à la date donnée

        $activities = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->activityChildren;
        });

        return response()->json($activities);
    }

    // Méthode pour mettre à jour ou créer une activité pour un enfant
    public function storeActivities(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:children,id',
            'activity_id' => 'required|integer|exists:activities,id',
            'performed_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        foreach ($request->child_ids as $childId) {
            ActivityChild::updateOrCreate(
                [
                    'child_id' => $childId,
                    'performed_at' => $request->performed_at,
                ],
                [
                    'activity_id' => $request->activity_id,
                ]
            );
        }

        return response()->json(['message' => 'Activités enregistrées avec succès']);
    }
    public function updateActivity(Request $request, $activityId)
    
    {
        Log::info('Received data:', $request->all());
        $request->validate([
            'activity_id' => 'required|integer|exists:activities,id',
            'performed_at' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $activityChild = ActivityChild::findOrFail($activityId);
        $activityChild->update([
            'activity_id' => $request->activity_id,
            'performed_at' => $request->performed_at,
        ]);

        return response()->json(['message' => 'Activité mise à jour avec succès']);
    }


    // Méthode pour obtenir les descriptions des activités

    public function getAllActivityTypes()
    {
        $activityTypes = Activity::all();

        return response()->json($activityTypes);
    }

    public function getActivity($activityId)
    {
        try {
            $activity = ActivityChild::findOrFail($activityId);
            return response()->json($activity);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve activity: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to retrieve activity: ' . $e->getMessage()], 404);
        }
    }

    public function deleteActivity($activityId)
    {
        $activity = ActivityChild::findOrFail($activityId);
        $activity->delete();

        return response()->json(['message' => 'Activité supprimée avec succès']);
    }
}
