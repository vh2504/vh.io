<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        //
        $users = User::all();

        return view('user.list', ['users'=>$users]);
    }

    public function edit($id)
    {
        //
        $data = User::find($id);
        return view('user.edit')->with('user',$data);
    }


    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name'=>'required',
            'phone' => 'required',
            'address' =>'required'
        ]);
        
        $user =  User::find($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();
        
        // return view('user.list')->with('users' , User::all());
         return redirect()->route('list', ['users'=>User::all()]);
    }

    public function delete( $id)
    {
        $user = User::find($id);
        $user->delete();
        // return view('user.list' )->with('users', User::all());
        return redirect()->route('list', ['users'=> User::all()]);
    }
}
