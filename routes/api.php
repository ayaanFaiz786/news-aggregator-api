<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::middleware('throttle:api-login')->post('login', [AuthController::class, 'login'])->name('login');
Route::middleware('throttle:api-login')->post('register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => ['throttle:api', 'auth:sanctum', 'XssSanitization']], function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    })->name('user');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('articles', [ArticleController::class, 'index'])->name('articles');
    Route::get('articles/{id}', [ArticleController::class, 'show'])->name('article');
    Route::get('personalized-feed', [ArticleController::class, 'personalizedFeed'])->name('personalized-feed');
    
    Route::get('preferences', [UserController::class, 'getPreferences'])->name('preferences');
    Route::post('preferences', [UserController::class, 'setPreferences'])->name('set-preferences');
});


