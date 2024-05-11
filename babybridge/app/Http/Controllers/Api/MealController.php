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
    public function storeOrUpdateMeal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'child_id' => 'required|integer|exists:children,id',
            'meal_id' => 'required|integer|exists:meals,id',
            'date' => 'required|date', // Just the date
            'time' => 'required', // Just the time
            'quantity' => 'required',
            'notes' => 'sometimes|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        

        $time = $request->time . ':00'; // ajouter les secondes pour le format de date MySQL
    
        $datetime = Carbon::createFromFormat('Y-m-d H:i:s', $request->date . ' ' . $time);
    
        $meal = ChildMeal::updateOrCreate(
            [
                'child_id' => $request->child_id,
                'meal_id' => $request->meal_id,
                'meal_time' => $datetime
            ],
            [
                'quantity' => $request->quantity,
                'notes' => $request->notes
            ]
        );
    
        return response()->json([
            'message' => 'Meal record saved successfully',
            'meal' => $meal
        ]);
    }

    public function getAllMealTypes()
    {
        $mealTypes = Meal::all();
    
        return response()->json($mealTypes);
    }
}
