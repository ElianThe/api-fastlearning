<?php

use App\Http\Controllers\API\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    //USERS
    Route::resource('users', \App\Http\Controllers\API\UserController::class);

    //FOLDERS
    Route::resource('folders', \App\Http\Controllers\API\FolderController::class);

    //CARDS
    Route::resource('cards', \App\Http\Controllers\API\CardController::class);
    Route::get('users/{id}/cards', [\App\Http\Controllers\API\CardController::class, 'indexByUser']);
    Route::get('users/{id}/cards-to-review', [\App\Http\Controllers\API\CardController::class, 'indexByUserAndReviews']);
    Route::get('learn-new-cards', [\App\Http\Controllers\API\CardController::class, 'learnNewCardsByUser']);

    Route::resource('tags', \App\Http\Controllers\API\TagController::class);

    Route::resource('reviews', \App\Http\Controllers\API\ReviewController::class);
    Route::post('updateDateReview', [\App\Http\Controllers\API\ReviewController::class, 'updateDateReview']);
});

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [RegisterController::class, 'login']);
Route::post('logout', [RegisterController::class, 'logout']);
