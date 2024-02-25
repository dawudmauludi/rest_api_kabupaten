<?php

use App\Http\Controllers\AdminController;
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

Route::group(['prefix' => 'admin'], function(){
    Route::post('login', [AdminController::class, 'login']);
    Route::post('users', [AdminController::class, 'store']);
    Route::get('users', [AdminController::class, 'index']);
    Route::put('users/{id}', [AdminController::class, 'update']);
    Route::get('users/{id}', [AdminController::class, 'show']);
    Route::delete('users/{id}', [AdminController::class, 'destroy']);
});
Route::group(['prefix' => 'users'], function(){
    Route::post('login', [UserController::class, 'login']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('{id}/absen', [UserController::class, 'store']);
    Route::get('absen/{id}', [UserController::class, 'show']);
});


