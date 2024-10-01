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

Route::middleware(['auth:sanctum', 'role:teacher'])->group(function () {

    Route::post('/lessons', [LessonController::class, 'store']);
  
    Route::post('/tests', [TestController::class, 'store']);  
    Route::get('/tests', [TestController::class, 'index']);  
    Route::post('/tests/{testId}/questions', [TestController::class, 'addQuestion']); 
  
  });
