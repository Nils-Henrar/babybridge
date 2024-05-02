<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ChildController;



Route::get('/', function () {
    return view('welcome');
});

//Admin Route
Route::middleware([\App\Http\Middleware\IsAdminMiddleware::class])->group(function () {

    //Section routes
    Route::get('admin/section', [SectionController::class, 'adminIndex'])->name('admin.section.index');
    Route::get('admin/section/create', [SectionController::class, 'create'])->name('admin.section.create');
    Route::post('admin/section', [SectionController::class, 'store'])->name('admin.section.store');
    Route::get('admin/section/{id}', [SectionController::class, 'show'])->name('admin.section.show');
    Route::get('admin/section/{id}/edit', [SectionController::class, 'edit'])->name('admin.section.edit');
    Route::put('admin/section/{id}', [SectionController::class, 'update'])->name('admin.section.update');
    Route::delete('admin/section/{id}', [SectionController::class, 'destroy'])->name('admin.section.destroy');

    //Worker routes
    Route::get('admin/worker', [WorkerController::class, 'index'])->name('admin.worker.index');
    Route::get('admin/worker/create', [WorkerController::class, 'create'])->name('admin.worker.create');
    Route::post('admin/worker', [WorkerController::class, 'store'])->name('admin.worker.store');
    Route::get('admin/worker/{id}', [WorkerController::class, 'show'])->name('admin.worker.show');
    Route::get('admin/worker/{id}/edit', [WorkerController::class, 'edit'])->name('admin.worker.edit');
    Route::put('admin/worker/{id}', [WorkerController::class, 'update'])->name('admin.worker.update');
    Route::delete('admin/worker/{id}', [WorkerController::class, 'destroy'])->name('admin.worker.destroy');


    //Child routes

    Route::get('admin/child', [ChildController::class, 'index'])->name('admin.child.index');
    Route::get('admin/child/create', [ChildController::class, 'create'])->name('admin.child.create');
    Route::post('admin/child', [ChildController::class, 'store'])->name('admin.child.store');
    Route::get('admin/child/{id}', [ChildController::class, 'show'])->name('admin.child.show');
    Route::get('admin/child/{id}/edit', [ChildController::class, 'edit'])->name('admin.child.edit');
    Route::put('admin/child/{id}', [ChildController::class, 'update'])->name('admin.child.update');
    Route::delete('admin/child/{id}', [ChildController::class, 'destroy'])->name('admin.child.destroy');
});


//Worker Route

Route::middleware([\App\Http\Middleware\IsWorkerMiddleware::class])->group(function () {

    //Section routes

    Route::get('worker/section', [SectionController::class, 'index'])->name('worker.section.index');
    Route::post('worker/section', [SectionController::class, 'store'])->name('worker.section.store');
    Route::get('worker/section/{id}', [SectionController::class, 'show'])->name('worker.section.show');

    Route::get('worker/worker', [WorkerController::class, 'index'])->name('worker.worker.index');
    Route::get('worker/worker/{id}', [WorkerController::class, 'show'])->name('worker.worker.show');
    Route::get('worker/worker/{id}/edit', [WorkerController::class, 'edit'])->name('worker.worker.edit');
    Route::put('worker/worker/{id}', [WorkerController::class, 'update'])->name('worker.worker.update');
    Route::delete('worker/worker/{id}', [WorkerController::class, 'destroy'])->name('worker.worker.destroy');
});



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
