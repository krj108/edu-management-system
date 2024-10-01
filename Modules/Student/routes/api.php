<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Teacher\Http\Controllers\LessonController;
use Modules\Student\Http\Controllers\TestController;
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
//     Route::get('student', fn (Request $request) => $request->user())->name('student');
// });
Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/lessons', [LessonController::class, 'index']);
    
    Route::get('/tests/takeTest', [TestController::class, 'index']); 
    
    });