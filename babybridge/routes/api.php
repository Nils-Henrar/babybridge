<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\MealController;
use App\Http\Controllers\Api\DiaperChangesController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\NapController;
use App\Http\Controllers\Api\DailyJournalController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Middleware\IsWorkerMiddleware;
use App\Http\Controllers\Auth\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [LoginController::class, 'login']);


Route::middleware(['auth:sanctum', IsWorkerMiddleware::class])->group(function () {
    // Mise à jour ou création d'une présence
Route::post('/attendances', [AttendanceController::class, 'storeOrUpdateAttendance']);

Route::delete('/attendances/{childId}/{date}', [AttendanceController::class, 'deleteAttendance']);


// Repas
Route::post('/meals', [MealController::class, 'storeMeal']);

Route::put('/meals/{mealId}', [MealController::class, 'updateMeal']);

Route::delete('/meals/{mealId}', [MealController::class, 'deleteMeal']);


// Changements de couches
Route::post('/diaper-changes', [DiaperChangesController::class, 'storeDiaperChange']);

Route::put('/diaper-changes/{diaperChangeId}', [DiaperChangesController::class, 'updateDiaperChange']);

Route::delete('/diaper-changes/{id}', [DiaperChangesController::class, 'deleteDiaperChange']);


// activities
Route::post('/activities', [ActivityController::class, 'storeActivities']);

Route::put('/activities/{activityId}', [ActivityController::class, 'updateActivity']);

Route::delete('/activities/{activityId}', [ActivityController::class, 'deleteActivity']);

Route::get('/activities/{activityId}', [ActivityController::class, 'getActivity']);


//naps
Route::post('/naps', [NapController::class, 'storeNap'])->name('naps.store');
Route::put('/naps/{napId}', [NapController::class, 'updateNap'])->name('naps.update');

Route::delete('/naps/{napId}', [NapController::class, 'deleteNap'])->name('naps.delete');


//photos
Route::post('/photos', [PhotoController::class, 'storePhoto']);
Route::put('/photos/{id}', [PhotoController::class, 'updatePhoto']);

Route::delete('/photos/{id}', [PhotoController::class, 'deletePhoto']);

});

/* 
*
*AttendanceController 
*
*/
// Récupération des présences pour une section pour une date donnée
Route::get('/attendances/section/{section_id}/date/{date}', [AttendanceController::class, 'getSectionAttendances']);


/*
*
*MealController 
*
*/
// Route pour récupérer les repas des enfants par section et date
Route::get('/meals/section/{section_id}/date/{date}', [MealController::class, 'getMealsBySectionAndDate']);

// Dans web.php ou api.php selon votre configuration
Route::get('/meal-types', [MealController::class, 'getAllMealTypes']);



Route::get('/meals/{mealId}', [MealController::class, 'getMeal']);


/*
*
*DiaperChangesController
*
*/
// Route pour récupérer les changements de couches des enfants par section et date
Route::get('/diaper-changes/section/{section_id}/date/{date}', [DiaperChangesController::class, 'getDiaperChangesBySectionAndDate']);



Route::get('/diaper-changes/{id}', [DiaperChangesController::class, 'getDiaperChange']);


/*
*
*ActivityController
*
*/

// Route pour obtenir les activités des enfants par section et date
Route::get('/activities/section/{section_id}/date/{date}', [ActivityController::class, 'getActivitiesBySectionAndDate']);


//route pour charger les descriptions des activités
Route::get('/activity-types', [ActivityController::class, 'getAllActivityTypes']);



/**
 * 
 * NapController
 * 
 */

// Route pour obtenir les siestes par section et date
Route::get('/naps/section/{sectionId}/date/{date}', [NapController::class, 'getNapsBySectionAndDate']);

//naps details
Route::get('/naps/{napId}', [NapController::class, 'getNap'])->name('naps.get');






/**
 * 
 * SectionController
 * 
 */

//route pour obtenir les enfants d'une section

// Route pour obtenir les photos des enfants par section et date
Route::get('/photos/section/{section_id}/date/{date}', [PhotoController::class, 'getPhotosBySectionAndDate']);

Route::get('/photos/{id}', [PhotoController::class, 'getPhoto']);
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

//route pour obtenir les enfants d'un useur(tuteur)

/*
*
*EventController
*
*/
Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);

Route::get('/events/available/{userId}', [\App\Http\Controllers\Api\EventController::class, 'getAvailableEvents'])->name('events.available');

/**
 * 
 * PaymentController
 * 
 */

Route::get('/tutor/payments', [PaymentController::class, 'index'])->name('tutor.payments');
Route::post('/tutor/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('tutor.payments.pay');
Route::get('/tutor/payments/{payment}/success', [PaymentController::class, 'success'])->name('tutor.payments.success');
Route::get('/tutor/payments/{payment}/cancel', [PaymentController::class, 'cancel'])->name('tutor.payments.cancel');

/**
 * 
 * DailyJournalController
 * 
 */

//  Route::get('children/{childId}/daily-journal/{date}', [DailyJournalController::class, 'show']);
//  Route::get('/children/user/{userId}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenByUser'])->name('children.get_by_user');

Route::get('/children/user/{userId}/daily-journal/{date}', [DailyJournalController::class, 'showByUser']);
 