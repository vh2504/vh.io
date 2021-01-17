<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\UserController;
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
// Route::get('/friends/add/{friendId}', [UserControllerApi::class, 'addFriend']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});

Route::group(['middleware'=>'auth:api'],function(){
    Route::get('/get-user-by-token',  [UserControllerApi::class, 'get_user_token']);

    Route::get('/friends/add/{friendId}', [UserControllerApi::class, 'addFriend']);
    Route::get('/friends/accept/{friendId}', [UserControllerApi::class, 'acceptFriend']);
    Route::get('/friends/unfriend/{friendId}', [UserControllerApi::class, 'unfriend']);
    Route::get('/friends', [UserControllerApi::class, 'viewAllFriend']);
    Route::get('/friends/list-request', [UserControllerApi::class, 'viewAllRequest']);
});



Route::get('/users',  [UserControllerApi::class, 'index']);
Route::post('/users', [UserControllerApi::class, 'store']);
Route::get('/users/{id}',  [UserControllerApi::class, 'show']);
Route::put('/users/{id}',  [UserControllerApi::class, 'update']);
Route::delete('/users/{id}',  [UserControllerApi::class, 'destroy']);


Route::post('/register-user', [AuthController::class, 'register']);
Route::post('/login-user', [AuthController::class, 'login']);


Route::group(['prefix' => 'userr'], function () {
    Route::get('/{id}', [UserController::class, 'show']);
    Route::get('/', [UserController::class, 'index']);
    // Route::get('/paginate', [UserController::class, 'paginate']);
});
Route::get('user/paginate', [UserController::class, 'paginate']);

// login facebook with api
Route::get('/login/facebook', function () {
    return Socialite::driver('facebook')->redirect();
})->name('login.facebook');


Route::get('/logout', function() {
    return Auth::logout();
});


// Route::get('/login/facebook/callback', function(Request $request){

//     return $request->all();
// });


Route::post('/login/facebook/get-token', function(Request $request){
    return redirect()->away('https://graph.facebook.com/v9.0/oauth/access_token?client_id=209160270705633&redirect_uri=http://localhost:8000/login/facebook/callback&client_secret=5fb67454aa16ac75586e57ded3b11b45&code='.$request->code );
});

Route::post('/login-by-token-fb', [UserControllerApi::class, 'handleLoginToken']);

