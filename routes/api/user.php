<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::delete('/delete-user/{user_id}', [UserController::class, 'deleteUser']);
    Route::get('/user-notifications', [UserController::class, 'getUserNotifications']);
});
