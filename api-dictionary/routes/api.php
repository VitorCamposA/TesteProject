<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WordController;
use Illuminate\Http\Request;
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


Route::get('/', function () {
    return response()->json([
        'message' => 'Fullstack Challenge 🏅 - Dictionary'
    ], 200);
});

// Public routes
Route::post('/auth/signup', [UserController::class, 'signup']);
Route::get('/auth/signin', [UserController::class, 'signin']);

// Protected routes
Route::middleware('check.token')->group(function () {
    Route::get('/entries/en/{word}', [WordController::class, 'getWordData']);
    Route::get('/entries/en', [WordController::class, 'getWords']);
    Route::post('/entries/en/{word}/favorite', [FavoriteController::class, 'addFavorite']);
    Route::delete('/entries/en/{word}/unfavorite', [FavoriteController::class, 'removeFavorite']);
    Route::get('/user/me/favorites', [FavoriteController::class, 'getUserFavorites'])->name('user.favorites');
    Route::get('/user/me/history', [UserController::class, 'getHistory']);
    Route::get('/user/me', [UserController::class, 'getUserData']);
});
