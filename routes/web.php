<?php

use App\Providers\RouteProvider as Route;

Route::get('/', [App\Controllers\Frontend\HomeController::class, 'index']);
Route::get('/auth/login', [App\Controllers\Auth\LoginController::class, 'login']);

// ADMIN ROUTES
// Route::get('admin/dashboard',                 [App\Http\Controllers\Admin\DashboardController::class, 'index']);
// Route::get('admin/users',                     [App\Http\Controllers\Admin\UserController::class, 'index']);
// Route::get('admin/users/create',              [App\Http\Controllers\Admin\UserController::class, 'create']);
// Route::post('admin/users',                    [App\Http\Controllers\Admin\UserController::class, 'store']);
// Route::get('admin/users/:id',                 [App\Http\Controllers\Admin\UserController::class, 'show']);
// Route::get('admin/users/:id/edit',            [App\Http\Controllers\Admin\UserController::class, 'edit']);
// Route::put('admin/users/:id',                 [App\Http\Controllers\Admin\UserController::class, 'update']);
// Route::delete('admin/users/:id',              [App\Http\Controllers\Admin\UserController::class, 'destroy']);

// Route::resource('admin/users', App\Http\Controllers\Admin\UserController::class);

// Rutas de Errores
// Route::get('errors/404', [App\Http\Controllers\Errors\ErrorController::class, 'error404']);
// Route::get('errors/405', [App\Http\Controllers\Errors\ErrorController::class, 'error405']);
// Route::get('errors/500', [App\Http\Controllers\Errors\ErrorController::class, 'error500']);

Route::dispatch();