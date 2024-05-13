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

class ActivityController extends Controller
{
    // Méthode pour récupérer les activités des enfants par section et date
    public function getActivitiesBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections' => function ($query) use ($date) {
            $query->whereHas('child.attendances', function ($q) use ($date) {
                $q->whereDate('attendance_date', $date); 
            });
        }, 'childSections.child.activityChildren' => function ($query) use ($date) {
            $query->whereDate('performed_at', $date)->with('activity');
        }])->findOrFail($sectionId);

        $activities = $section->childSections->flatMap(function ($childSection) {
            return $childSection->child->activityChildren;
        });

        return response()->json($activities);
    }

    // Méthode pour mettre à jour ou créer une activité pour un enfant
    public function storeOrUpdateActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|integer|exists:children,id',
            'activity_id' => 'required|integer|exists:activities,id',
            'time' => 'required', // Just the time
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL

        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);

        $activityChild = ActivityChild::updateOrCreate(
            [
                'child_id' => $request->child_id,
                'performed_at' => $datetime
                
            ],
            [
                'activity_id' => $request->activity_id,
            ]
        );

        return response()->json([
            'message' => 'Activity record saved successfully',
            'activityChild' => $activityChild
        ]);
    }

    // Méthode pour enregistrer une activité pour tous les enfants de la section qui sont présents dans la base de données
    public function storeActivityForPresentChildren(Request $request, $sectionId, $date)
{
    // Validation des données reçues
    $request->validate([
        'activity_id' => 'required|exists:activities,id',
        'time' => 'required', // Just the time
        'date' => 'required|date',
    ]);

    $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL

    $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);

    // Récupération des enfants présents dans la section à la date spécifiée
    $children = Child::whereHas('childSections', function ($query) use ($sectionId) {
        $query->where('section_id', $sectionId);
    })->whereHas('attendances', function ($query) use ($date) {
        $query->whereDate('attendance_date', $date);
    })->get();

    // Création de l'activité pour chaque enfant présent
    foreach ($children as $child) {
        ActivityChild::create([
            'child_id' => $child->id,
            'activity_id' => $request->activity_id,
            'performed_at' => $datetime
        ]);
    }

    return response()->json(['message' => 'Activity added successfully for all present children in the section']);
}

    // Méthode pour obtenir les descriptions des activités

    public function getAllActivityTypes()
    {
        $activityTypes = Activity::all();

        return response()->json($activityTypes);
    }
}
