<?php

use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RegisterController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('folders', \App\Http\Controllers\API\FolderController::class);
    Route::resource('folder-tree-folders', \App\Http\Controllers\API\FolderTreeFoldersController::class);
    Route::resource('cards', \App\Http\Controllers\API\CardController::class);
    Route::resource('tags', \App\Http\Controllers\API\TagController::class);
    Route::resource('reviews', \App\Http\Controllers\API\ReviewController::class);
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('logout', [RegisterController::class, 'logout']); // TODO
