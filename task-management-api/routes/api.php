<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

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

// routes/api.php
Route::post('/login', [AuthenticationController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::prefix('auth')->controller(AuthenticationController::class)->group(function() {
        Route::get('/user', 'user');
        Route::post('/logout', 'logout');
    });

    Route::prefix('tasks')->controller(TaskController::class)->group(function () {
        Route::get('/sidebar-dates', 'storedTasks');
    });    
    Route::resource('tasks', TaskController::class);

});