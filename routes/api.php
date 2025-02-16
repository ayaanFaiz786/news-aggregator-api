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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum', 'XssSanitization']], function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);
    Route::get('personalized-feed', [ArticleController::class, 'personalizedFeed']);
    
    Route::get('preferences', [UserController::class, 'getPreferences']);
    Route::post('preferences', [UserController::class, 'setPreferences']);
});


