<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/users',  [UserControllerApi::class, 'index']);
Route::post('/users', [UserControllerApi::class, 'store']);
Route::get('/users/{id}',  [UserControllerApi::class, 'show']);
Route::put('/users/{id}',  [UserControllerApi::class, 'update']);
Route::delete('/users/{id}',  [UserControllerApi::class, 'destroy']);


Route::post('/register-user', [AuthController::class, 'register']);
Route::post('/login-user', [AuthController::class, 'login']);