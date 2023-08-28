<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\BlogDataController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('user', 'getUser');
});
Route::resource('data', BlogDataController::class)->except('show', 'edit');
Route::resource('blogType', BlogTypeController::class)->except('show', 'edit');


Route::post('blog/{blogId}/like', [BlogDataController::class, 'addLike'])->middleware('auth:api');
Route::post('blog/{blogId}/dislike', [BlogDataController::class, 'addDislike'])->middleware('auth:api');
Route::post('blog/{blogId}/comment', [BlogDataController::class, 'addComment'])->middleware('auth:api');

