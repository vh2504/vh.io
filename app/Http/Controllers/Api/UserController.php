<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return UserResource::collection(User::all());
    }
    
    public function paginate()
    {
        return UserResource::collection(User::paginate(2));
    }


    public function show($id)
    {
        // return new UserResource(User::findOrFail($id));
        return new UserResource(User::find($id));
    }


}
