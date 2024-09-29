<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\StudentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

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


Route::middleware(['auth:sanctum'])->group(function () {

Route::get('/lessons', [LessonController::class, 'index']);  // عرض الدروس
});


Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {
  Route::post('/lessons', [LessonController::class, 'store']); // إضافة درس
});

