<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//route pour events

Route::get('/events', [\App\Http\Controllers\Api\EventController::class, 'index']);
