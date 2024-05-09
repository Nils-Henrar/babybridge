<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttendanceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//route pour events

Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);



//route pour les attendances

// Route::get('/attendances/date/{date}', [AttendanceController::class, 'getAttendancesByDate'])->name('attendances.get_by_date');
// Route::post('/attendance', [AttendanceController::class, 'storeAttendance'])->name('attendance.store');µ

// Récupération des présences pour une section pour une date donnée
Route::get('/attendances/section/{section_id}/date/{date}', [AttendanceController::class, 'getSectionAttendances']);

// Mise à jour ou création d'une présence
Route::post('/attendances', [AttendanceController::class, 'storeOrUpdateAttendance']);



//rotue pour enfants
Route::get('/children/section/{section_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChildrenBySection'])->name('children.get_by_section');
Route::get('/child/{child_id}', [\App\Http\Controllers\Api\ChildController::class, 'getChild'])->name('child.get');