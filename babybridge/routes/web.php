<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\ChildController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\PaymentController;





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

    //User routes
    Route::get('admin/user', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('admin/user/create', [UserController::class, 'create'])->name('admin.user.create');
    Route::post('admin/user', [UserController::class, 'invite'])->name('admin.user.invite');
    Route::get('admin/user/{id}', [UserController::class, 'show'])->name('admin.user.show');
    Route::get('admin/user/{id}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::put('admin/user/{id}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('admin/user/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');


    //Child routes

    Route::get('admin/child', [ChildController::class, 'adminIndex'])->name('admin.child.index');
    Route::get('admin/child/create', [ChildController::class, 'create'])->name('admin.child.create');
    Route::post('admin/child', [ChildController::class, 'store'])->name('admin.child.store');
    Route::get('admin/child/{id}', [ChildController::class, 'show'])->name('admin.child.show');
    Route::get('admin/child/{id}/edit', [ChildController::class, 'edit'])->name('admin.child.edit');
    Route::put('admin/child/{id}', [ChildController::class, 'update'])->name('admin.child.update');
    Route::delete('admin/child/{id}', [ChildController::class, 'destroy'])->name('admin.child.destroy');

    //Event routes

    Route::get('admin/event', [EventController::class, 'index'])->name('admin.event.index');
    Route::get('admin/event/create', [EventController::class, 'create'])->name('admin.event.create');
    Route::post('admin/event', [EventController::class, 'store'])->name('admin.event.store');
    Route::get('admin/event/{id}', [EventController::class, 'show'])->name('admin.event.show');
    Route::get('admin/event/{id}/edit', [EventController::class, 'edit'])->name('admin.event.edit');
    Route::put('admin/event/{id}', [EventController::class, 'update'])->name('admin.event.update');
    Route::delete('admin/event/{id}', [EventController::class, 'destroy'])->name('admin.event.destroy');

    //Calendar routes

    Route::get('admin/calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
});


//Worker Route

Route::middleware([\App\Http\Middleware\IsWorkerMiddleware::class])->group(function () {

    //Section routes

    Route::get('worker/section/children', [SectionController::class, 'showChildren'])->name('worker.section.children');

    //attendance routes
    Route::get('worker/section/attendance', [SectionController::class, 'createAttendance'])->name('worker.section.attendance');

    //meal routes

    Route::get('worker/section/meal', [SectionController::class, 'createMeal'])->name('worker.section.meal');

    //diaper routes

    Route::get('worker/section/diaper-change', [SectionController::class, 'createDiaper'])->name('worker.section.diaper');

    //activity routes

    Route::get('worker/section/activity', [SectionController::class, 'createActivity'])->name('worker.section.activity');


    //Photo routes

    Route::get('worker/section/photo', [SectionController::class, 'createPhoto'])->name('worker.section.photo');

    //Nap routes

    Route::get('worker/section/nap', [SectionController::class, 'createNap'])->name('worker.section.nap');

    //User routes

    Route::get('worker/profile', [UserController::class, 'workerProfile'])->name('worker.profile');


    //Child routes

    Route::get('worker/child', [ChildController::class, 'index'])->name('worker.child.index');
    Route::get('worker/child/{id}', [ChildController::class, 'profile'])->name('worker.child.profile');
});

//Tutor Route

Route::middleware([\App\Http\Middleware\IsTutorMiddleware::class])->group(function () {

    //journal 
    Route::get('tutor/children/daily-journal', [ChildController::class, 'dailyJournal'])->name('tutor.children.daily-journal');


    //payment 

    Route::get('/tutor/payment', [PaymentController::class, 'index'])->name('tutor.payment');

    Route::post('/tutor/payment/{payment}/pay', [PaymentController::class, 'pay'])->name('tutor.payment.pay');

    Route::get('/tutor/payment/{payment}/success', [PaymentController::class, 'success'])->name('tutor.payment.success');

    Route::get('/tutor/paymens/{payment}/cancel', [PaymentController::class, 'cancel'])->name('tutor.payment.cancel');


    //User 

    Route::get('/tutor/profile', [UserController::class, 'tutorProfile'])->name('tutor.profile');

    Route::get('/tutor/child/{childId}', [ChildController::class, 'profileForTutor'])->name('tutor.child.profile');
});


Route::get('/register/{token}', [UserController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register/complete', [UserController::class, 'completeRegistration'])->name('register.complete');




Auth::routes();

Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
});

// / ou /home
Route::get('/', function () {
    return view('PrimeCare.index');
});
