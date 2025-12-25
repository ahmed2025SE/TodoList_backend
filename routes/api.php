<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TodoListController;





   Route::post('/signup', [AuthController::class, 'signup']);
   Route::post('/login', [AuthController::class, 'login']);


   Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('lists', TodoListController::class);

});
