<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/user', function(Request $request) {
  return $request->user();
})->middleware('auth:sanctum');

Route::get('/free_post', [PostController::class, 'freePost'])->middleware(['auth:sanctum', 'ability:post:free,post:premium']);

Route::get('/premium_post', [PostController::class, 'premiumPost'])->middleware(['auth:sanctum,ability:post:free,post:premium']);

Route::get('/detail_post/{id}', [PostController::class, 'detailPost'])->middleware(['auth:sanctum', 'ability:post:free,post:premium']);

Route::post('/premium_user', [AuthController::class, 'premiumUser'])->middleware('auth:sanctum');