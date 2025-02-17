<?php

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

foreach (glob(__DIR__ . '/api/*.php') as $file) {
    require $file;
}
