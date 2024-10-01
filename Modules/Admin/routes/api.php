<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Modules\Admin\Http\Controllers\ClassRoomController;
use Modules\Admin\Http\Controllers\SectionController;
use Modules\Admin\Http\Controllers\StudentController;
use Modules\Admin\Http\Controllers\SubjectController;
use Modules\Admin\Http\Controllers\TeacherController;

/*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register API routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | is assigned the "api" middleware group. Enjoy building your API!
    |
*/

// Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
//     Route::get('admin', fn (Request $request) => $request->user())->name('admin');
// });
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Routes for managing sections
    Route::post('/sections', [SectionController::class, 'store']);

    // Routes for managing classes
    Route::post('/classes', [ClassRoomController::class, 'store']);
    Route::post('/classes/{classId}/rooms', action: [ClassRoomController::class, 'addRoom']);

      // Routes for managing subjects
      Route::post('/subjects', [SubjectController::class, 'store']); 
      Route::get('/subjects', [SubjectController::class, 'index']);  

    // Routes for managing teachers
      Route::post('/teachers', [TeacherController::class, 'store']);
    Route::get('/teachers', [TeacherController::class, 'index']);
// Routes for managing students
    Route::post('/students', [StudentController::class, 'store']);
    Route::get('/students', [StudentController::class, 'index']);

});