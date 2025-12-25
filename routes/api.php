<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TaskController;





   Route::post('/signup', [AuthController::class, 'signup']);
   Route::post('/login', [AuthController::class, 'login']);


   Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('lists', TodoListController::class);

    Route::apiResource('tasks', TaskController::class) ->except(['show']);

    Route::get('tasks-completed', [TaskController::class, 'completed']);
    Route::get('tasks-upcoming',  [TaskController::class, 'upcoming']);

});
