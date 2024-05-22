<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChildMeal;
use App\Models\Section;
use Illuminate\Support\Facades\Validator;
use App\Models\Meal;
use Carbon\Carbon;

class MealController extends Controller
{
    public function getMealsBySectionAndDate($sectionId, $date)
    {
        $section = Section::with(['childSections.child.childMeals' => function ($query) use ($date) {
            // Assurez-vous que la date est correctement formatée et comparée
            $query->whereDate('meal_time', '=', $date)->with('meal');
        }])->findOrFail($sectionId);
    
        // Flatten the results to get a simple array of meals
        $meals = $section->childSections->map(function ($childSection) {
            return $childSection->child->childMeals;
        });
    
        return response()->json($meals);
    }

    // Méthode pour mettre à jour ou créer un repas
    public function storeMeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_ids' => 'required|array',
            'child_ids.*' => 'exists:children,id',
            'meal_time' => 'required|date_format:Y-m-d H:i:s',
            'meal_id' => 'required|integer|exists:meals,id',
            'quantity' => 'required',
            'notes' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        foreach ($request->child_ids as $childId) {
            ChildMeal::create([
                'child_id' => $childId,
                'meal_id' => $request->meal_id,
                'meal_time' => $request->meal_time,
                'quantity' => $request->quantity,
                'notes' => $request->notes
            ]);
        }
    
        return response()->json(['message' => 'Repas enregistré avec succès']);
    }
    
    public function updateMeal(Request $request, $mealId)
    {
        $validator = Validator::make($request->all(), [
            'meal_id' => 'required|integer|exists:meals,id',
            'meal_time' => 'required|date_format:Y-m-d H:i:s', // format '2021-12-31 12:00:00
            'quantity' => 'required',
            'notes' => 'nullable|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $meal = ChildMeal::findOrFail($mealId);
        $meal->update([
            'meal_time' => $request->meal_time,
            'quantity' => $request->quantity,
            'notes' => $request->notes
        ]);
    
        return response()->json(['message' => 'Repas mis à jour avec succès']);
    }

    public function getAllMealTypes()
    {
        $mealTypes = Meal::all();
    
        return response()->json($mealTypes);
    }

    public function deleteMeal($mealId)
    {
        $meal = ChildMeal::findOrFail($mealId);
        $meal->delete();
    
        return response()->json(['message' => 'Repas supprimé avec succès']);
    }

    public function getMeal($mealId)
    {
        $meal = ChildMeal::findOrFail($mealId);
    
        return response()->json($meal);
    }
}
