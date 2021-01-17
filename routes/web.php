<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Relationship;


use Illuminate\Http\Request;
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


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Route::resource('manage', UserController::class);

Route::middleware('auth')->group(function(){
    Route::get('/manage',[UserController::class, 'list']);
    Route::get('/manage/list',[UserController::class, 'list'])->name('list');

    Route::get('/manage/edit/{id}',[UserController::class, 'edit']);
    Route::post('/manage/update/{id}',[UserController::class, 'update']);
    Route::get('/manage/delete/{id}',[UserController::class, 'delete']);

    Route::get('/friend/add/{friendId}', [UserController::class, 'addFriend']);
    Route::get('/friend/accept/{friendId}', [UserController::class, 'acceptFriend']);
    Route::get('/friend/cancle/{friendId}', [UserController::class, 'cancleFriend']);
    Route::get('/friend', [UserController::class, 'viewAllFriend']);
    Route::get('/friend/list-request', [UserController::class, 'viewAllRequest']);
});


//Login Facebook
Route::get('/login/facebook', function () {
    return Socialite::driver('facebook')->redirect();
})->name('login.facebook');

//Route::get('/login/facebook/callback', [UserController::class, 'handleLoginFacebook']);

Route::get('/login/facebook/callback', function(Request $request){
    if($request->has('code'))
      dd($request->input('code'));

     dd($request->all());

});

// Route::get('/login/facebook/callback', function(Request $request){
//      $code = $request->input('code');
//      echo $code;
//       redirect()->away('https://graph.facebook.com/v2.8/oauth/access_token?client_id=209160270705633&redirect_uri=http://localhost:8000/login/facebook/callback&client_secret=5fb67454aa16ac75586e57ded3b11b45&code='.$code);
// });

Route::get('/login/facebook/get-user', function(Request $request){
    return redirect()->away('https://graph.facebook.com/v9.0/oauth/access_token?client_id=209160270705633&redirect_uri=http://localhost:8000/login/facebook/callback&client_secret=5fb67454aa16ac75586e57ded3b11b45&code=AQBpJsme-tJlx9zTFvupxsVheUoAbIV2zqvOd62DNkV3naWcvgbbccCSTGMd2hmx-4PlipZjObMZfyqmGcqmoYxN9gaJfA2OiO5jfy6tGHff5rRkxgFI0iyd58V3Yvfh3-JtWCPteTNHP41hIgJgAOsfdpYJBAJgN3Mj2gC2x5MVckqL16njvNabkdiHXyMw3Z6lEeydfSrsI4dsGojAoX5DtlORdjcSvKnapJPSO1dzN70MHbY6CAvAcztXVpEHmzMw94bN9_dGVKsuAq3e0_wPRLA3ziwS3GJIe55Jbfml75WyqzfgFo-87Lw-VqXuOo8cDoYS-kEbdYGJmEE3t9lbaltzXRkkf-Wk42My3wlSiw');
});

Route::get('user', function () {
    $token = 'EAACZBOuui8ZBEBABG8lZA85ZCiVjlBkJ9IeC9xD3owF5mYg8Cq1wRCK0TcCdt8boEVRFbeBAbD0L9GwPrRkuR1KkXJ3vRX36OZBhiqlIfL4x322ViLLqmu09XMQYHqmWIJgXKysL8yU82RfqqITTnnZBKGPPQ8lKb5hcbZC9rs0fgZDZD';
    $user = Socialite::driver('facebook')->userFromToken($token); 
    dd($user);
});


/*
Route::get('/get-user-by-token-fb', function(){
    $token = 'EAACZBOuui8ZBEBAOs9PIQxK0qNZA6Q3VIlIpoVBSobbZCrNWJGmR3BYH1UFtz722Lgs6UHurGnOZAJ1I5HJGzDNmuSMzf2uyRoL8uS6jkZC4CZBwnbpxW2Lxsk145E2f3VihjMKZAzxcUJa7aR598CWmg2n8AFes5rc2dTyVpK14bQZDZD';
    $user = Socialite::driver('facebook')->userFromToken($token);
    dd($user);
});
*/

