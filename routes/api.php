<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('users', \App\Http\Controllers\API\UserController::class);
    Route::resource('folders', \App\Http\Controllers\API\FolderController::class);
    Route::resource('cards', \App\Http\Controllers\API\CardController::class);
    Route::resource('tags', \App\Http\Controllers\API\TagController::class);
    Route::resource('reviews', \App\Http\Controllers\API\ReviewController::class);
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('logout', [RegisterController::class, 'logout']); // TODO
