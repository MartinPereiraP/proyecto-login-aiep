<?php
// routes/web.php

use App\Providers\RouteProvider as Route;

Route::get('/', [App\Controllers\Frontend\HomeController::class, 'index']);

// Incluid atrchivo auth.php con las rutas de autenticación
require_once 'auth.php';

// ADMIN ROUTES
Route::get('admin/dashboard',                 [App\Controllers\Admin\DashboardController::class, 'index']);
// Route::get('admin/users',                     [App\Controllers\Admin\UserController::class, 'index']);
// Route::get('admin/users/create',              [App\Controllers\Admin\UserController::class, 'create']);
// Route::post('admin/users',                    [App\Controllers\Admin\UserController::class, 'store']);
// Route::get('admin/users/:id',                 [App\Controllers\Admin\UserController::class, 'show']);
// Route::get('admin/users/:id/edit',            [App\Controllers\Admin\UserController::class, 'edit']);
// Route::put('admin/users/:id',                 [App\Controllers\Admin\UserController::class, 'update']);
// Route::delete('admin/users/:id',              [App\Controllers\Admin\UserController::class, 'destroy']);

Route::dispatch();
