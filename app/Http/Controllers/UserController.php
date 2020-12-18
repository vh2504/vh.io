<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

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
        return redirect()->route('list', ['users'=> User::all()]);
    }

    public function handleLoginFacebook(){
        $user = Socialite::driver('facebook')->user();

        $email =  $user->getEmail();

        if(!empty($email)) {
            $userDB = User::where('email', $email)->exists();

            if(!$userDB) {
                $name = $user->getName();
                $user = User::create([
                    'email'=>$email,
                    'name' => $name,
                ]);
                $user->save();
            }
     
            $id =   User::where('email', $email)->value('id');
            if(Auth::loginUsingId($id))
                return view('dashboard');
        }
        else {
            $id = $user->getId();
            $idExists = User::where('id', $id)->exists();

            if(!$idExists) {
                $name = $user->getName();
                $user = User::create([
                    'id'=>$id,
                    'name' => $name,
                ]);
                $user->save();

                if(Auth::loginUsingId($id))
                    return view('dashboard');
            }
            else {
                if(Auth::loginUsingId($id))
                    return view('dashboard');
            }
        }
    }
}
