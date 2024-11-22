<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;


//REPORT CONTROLLER ROUTS
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-report', [ReportController::class, 'createReport']);
});

