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
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\IsTutorMiddleware;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [LoginController::class, 'login']); 
Route::post('/logout', [LoginController::class, 'logout']);



Route::middleware(['auth:sanctum', IsWorkerMiddleware::class, EnsureTokenIsValid::class])->group(function () {

    Route::post('/attendances', [AttendanceController::class, 'storeOrUpdateAttendance']);

    Route::delete('/attendances/{childId}/{date}', [AttendanceController::class, 'deleteAttendance']);

    Route::get('/attendances/section/{section_id}/date/{date}', [AttendanceController::class, 'getSectionAttendances']);
    Route::get('/children/section/{section_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySection'])->name('children.get_by_section');


    // Repas
    Route::post('/meals', [MealController::class, 'storeMeal']);

    Route::put('/meals/{mealId}', [MealController::class, 'updateMeal']);

    Route::delete('/meals/{mealId}', [MealController::class, 'deleteMeal']);
    Route::get('/meals/section/{section_id}/date/{date}', [MealController::class, 'getMealsBySectionAndDate']);
    Route::get('/meal-types', [MealController::class, 'getAllMealTypes']);
    Route::get('/meals/{mealId}', [MealController::class, 'getMeal']);

    // Changements de couches
    Route::post('/diaper-changes', [DiaperChangesController::class, 'storeDiaperChange']);

    Route::put('/diaper-changes/{diaperChangeId}', [DiaperChangesController::class, 'updateDiaperChange']);
    Route::delete('/diaper-changes/{id}', [DiaperChangesController::class, 'deleteDiaperChange']);
    Route::get('/diaper-changes/section/{section_id}/date/{date}', [DiaperChangesController::class, 'getDiaperChangesBySectionAndDate']);

    Route::get('/diaper-changes/{id}', [DiaperChangesController::class, 'getDiaperChange']);
    Route::get('/diaper-changes/section/{section_id}/date/{date}', [DiaperChangesController::class, 'getDiaperChangesBySectionAndDate']);

    Route::get('/diaper-changes/{id}', [DiaperChangesController::class, 'getDiaperChange']);




    // activities
    Route::post('/activities', [ActivityController::class, 'storeActivities']);

    Route::put('/activities/{activityId}', [ActivityController::class, 'updateActivity']);

    Route::delete('/activities/{activityId}', [ActivityController::class, 'deleteActivity']);

    Route::get('/activities/{activityId}', [ActivityController::class, 'getActivity']);

    // Route pour obtenir les activités des enfants par section et date
    Route::get('/activities/section/{section_id}/date/{date}', [ActivityController::class, 'getActivitiesBySectionAndDate']);
    //route pour charger les descriptions des activités
    Route::get('/activity-types', [ActivityController::class, 'getAllActivityTypes']);


    //naps
    Route::post('/naps', [NapController::class, 'storeNap'])->name('naps.store');
    Route::put('/naps/{napId}', [NapController::class, 'updateNap'])->name('naps.update');
    Route::delete('/naps/{napId}', [NapController::class, 'deleteNap'])->name('naps.delete');
    // Route pour obtenir les siestes par section et date
    Route::get('/naps/section/{sectionId}/date/{date}', [NapController::class, 'getNapsBySectionAndDate']);

    //naps details
    Route::get('/naps/{napId}', [NapController::class, 'getNap'])->name('naps.get');

    //photos
    Route::post('/photos', [PhotoController::class, 'storePhoto']);
    Route::put('/photos/{id}', [PhotoController::class, 'updatePhoto']);

    Route::delete('/photos/{id}', [PhotoController::class, 'deletePhoto']);
    // Route pour obtenir les photos des enfants par section et date
    Route::get('/photos/section/{section_id}/date/{date}', [PhotoController::class, 'getPhotosBySectionAndDate']);

    Route::get('/photos/{id}', [PhotoController::class, 'getPhoto']);


    //route pour obtenir les enfants d'une section
    Route::get('/children/section/{section_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySection'])->name('children.get_by_section');

    // Route pour obtenir les enfants présents d'une section pour une date donnée 
    Route::get('/children/section/{section_id}/date/{date}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySectionAndDate'])->name('children.get_by_section_and_date');


});





/**
 * 
 * ChildController
 * 
 */

Route::get('/child/{child_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChild'])->name('child.get');


/*
*
*EventController
*
*/
Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);

Route::get('/events/available/{userId}', [\App\Http\Controllers\Api\EventController::class, 'getAvailableEvents'])->name('events.available');


Route::middleware(['auth:sanctum', IsTutorMiddleware::class, EnsureTokenIsValid::class])->group(function () {
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
    Route::get('/children/user/{userId}/daily-journal/{date}', [DailyJournalController::class, 'showByUser']);
    
});