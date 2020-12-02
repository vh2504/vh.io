<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//test
Route::get('/post', function(){
    return view('post.index');
});

Route::get('page/{id}/{name}', function ($id, $name) {
    return 'Hello ' . $id .$name;
});

Route::get('page', function () {
    return view('page.index', ["name"=> 'Laravel']);
});

Route::get('welcome', function(){
    return view('welcome');
});

//end test

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

// Route::resource('manage', UserController::class);
// Route::middleware('auth')->group(function(){
//     Route::get('manage',[UserController::class, 'list']);
//     Route::get('manage/list',[UserController::class, 'list'])->name('list');

//     Route::get('manage/edit/{id}',[UserController::class, 'edit']);
//     Route::post('manage/update/{id}',[UserController::class, 'update']);
//     Route::get('manage/delete/{id}',[UserController::class, 'delete']);
// });
