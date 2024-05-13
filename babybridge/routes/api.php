<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\DiaperChangesController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\PhotoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//route pour events

Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);


/* 
*
*AttendanceController 
*
*/
// Récupération des présences pour une section pour une date donnée
Route::get('/attendances/section/{section_id}/date/{date}', [AttendanceController::class, 'getSectionAttendances']);

// Mise à jour ou création d'une présence
Route::post('/attendances', [AttendanceController::class, 'storeOrUpdateAttendance']);


/*
*
*MealController 
*
*/
// Route pour récupérer les repas des enfants par section et date
Route::get('/meals/section/{section_id}/date/{date}', [MealController::class, 'getMealsBySectionAndDate']);

// Dans web.php ou api.php selon votre configuration
Route::get('/meal-types', [MealController::class, 'getAllMealTypes']);

// Mise à jour ou création d'un repas
Route::post('/meals', [MealController::class, 'storeOrUpdateMeal']);


/*
*
*DiaperChangesController
*
*/
// Route pour récupérer les changements de couches des enfants par section et date
Route::get('/diaper-changes/section/{section_id}/date/{date}', [DiaperChangesController::class, 'getDiaperChangesBySectionAndDate']);

// Route pour mettre à jour ou créer un changement de couche
Route::post('/diaper-changes', [DiaperChangesController::class, 'storeOrUpdateDiaperChange']);

/*
*
*ActivityController
*
*/

// Route pour obtenir les activités des enfants par section et date
Route::get('/activities/section/{section_id}/date/{date}', [ActivityController::class, 'getActivitiesBySectionAndDate']);

// Route pour enregistrer ou mettre à jour une activité pour un enfant
Route::post('/activities', [ActivityController::class, 'storeOrUpdateActivity']);

// Route pour enregistrer une activité pour tous les enfants présents d'une section pour une date donnée
Route::post('/activities/section/{section_id}/date/{date}', [ActivityController::class, 'storeActivityForPresentChildren']);


//route pour charger les descriptions des activités

Route::get('/activity-types', [ActivityController::class, 'getAllActivityTypes']);



/**
 * 
 * SectionController
 * 
 */

//route pour obtenir les enfants d'une section

// Route pour obtenir les photos des enfants par section et date
Route::get('/photos/section/{section_id}/date/{date}', [PhotoController::class, 'getPhotosBySectionAndDate']);

// Route pour enregistrer ou mettre à jour une photo
Route::post('/photos', [PhotoController::class, 'storeOrUpdatePhoto']);


/**
 * 
 * ChildController
 * 
 */
//route pour obtenir les enfants d'une section
Route::get('/children/section/{section_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySection'])->name('children.get_by_section');

// Route pour obtenir les enfants présents d'une section pour une date donnée 
Route::get('/children/section/{section_id}/date/{date}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySectionAndDate'])->name('children.get_by_section_and_date');

Route::get('/child/{child_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChild'])->name('child.get');