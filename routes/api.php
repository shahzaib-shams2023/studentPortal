<?php

use App\Http\Controllers\Api\ExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\WebApiController;
use App\Http\Controllers\Api\ExamApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| All routes here automatically get the "api" middleware group
| and return JSON responses instead of Blade views
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return response()->json(['message' => 'Laravel API Root']);
});

// -------- AUTH --------
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// -------- STUDENT SECTION --------
Route::get('/students', [StudentApiController::class, 'index']);
Route::get('/students/{id}', [StudentApiController::class, 'show']);
Route::post('/students', [StudentApiController::class, 'store']);
Route::put('/students/{id}', [StudentApiController::class, 'update']);
Route::delete('/students/{id}', [StudentApiController::class, 'destroy']);

// -------- EXAMS --------
Route::get('/exams', [ExamController::class, 'index']);
Route::get('/exams/{id}', [ExamController::class, 'show']);
Route::post('/exams/import', [ExamController::class, 'importExcel']);

// -------- WEBSITE --------
Route::get('/web/courses', [WebApiController::class, 'courses']);
Route::get('/web/events', [WebApiController::class, 'events']);
