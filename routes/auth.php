<?php
// routes/auth.php


use App\Providers\RouteProvider as Route;

Route::get('/auth/login', [App\Controllers\Auth\AuthController::class, 'login']);
Route::post('/auth/login', [App\Controllers\Auth\AuthController::class, 'authenticate']);
Route::get('/auth/logout', [App\Controllers\Auth\AuthController::class, 'logout']);
Route::get('/auth/register', [App\Controllers\Auth\AuthController::class, 'register']);
Route::post('/auth/register', [App\Controllers\Auth\AuthController::class, 'store']);
