<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validateData = $request->validate([
            'name' => 'required',
            'email'=> 'required|unique:users',
            'password' => 'required',
            'phone' => 'required',
            'address' =>'required'
        ]);

        $validateData['password'] = Hash::make($request->password);

        $user = User::create($validateData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user'=>$user, 'access_token' => $accessToken]);
    }

    public function login(Request $request){
        $userLogin = $request->validate([
            'email'=> 'required|email',
            'password'=>'required'
        ]);
       
        if(!Auth::attempt($userLogin))
            return response(['mess' => 'login error'], 401);
  
        // $user = Auth::user();
        // $accessToken = $user->createToken('authToken')->accessToken;

        // return response(['user'=>$userLogin, 'access_token' => $accessToken]);
        
        return response(['user'=> Auth::user()]);
    }



}
