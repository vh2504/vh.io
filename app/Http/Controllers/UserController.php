<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Relationship;
use Illuminate\Support\Facades\DB;

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


    public function addFriend($friendId ){
        $user = Auth::user();
        $friendId = (int) $friendId;

        if(User::find($friendId)){
            if($user->id > $friendId)
            {
                $user = Relationship::create([
                    'user_first_id' => $friendId,
                    'user_second_id' =>$user->id,
                    'status' => -1
                ]);
                $user->save();
            }
            else
            {
                Relationship::create([
                    'user_first_id' => $user->id,
                    'user_second_id' =>$friendId,
                    'status' => -2
                ]);
            }
        }
    }

    public function acceptFriend( $friendId){
        $user = Auth::user();

        $friendId = (int) $friendId;

        if($user->id < $friendId) {
            $fr = Relationship::where('user_first_id', $user->id)
                                ->where('user_second_id', $friendId)
                                ->where('status' , -1)
                                ->update(['status' => 1]);
        }
        else
        {
            $fr = Relationship::where('user_second_id',$user->id )
                                ->where('user_first_id', $friendId)
                                ->where('status' , -2)
                                ->update(['status' => 1]);
        }
    }

    public function cancleFriend($friendId) {
        $user = Auth::user();

        $friendId = (int) $friendId;

        if($user->id < $friendId) {
            $fr = Relationship::where('user_first_id', $user->id)
                                ->where('user_second_id', $friendId)
                                ->where('status' , 1)
                                ->delete();
        }
        else
        {
            $fr = Relationship::where('user_second_id',$user->id )
                                ->where('user_first_id', $friendId)
                                ->where('status' , 1)
                                ->delete();
        }
    }

    public function viewAllFriend() {
        $user = Auth::user();

        $list1 = DB::select('select u.id, u.name, u.email
                            from users as u
                                join
                                 (
                                  select r.user_second_id
                                  from users join relationships as r on users.id = r.user_first_id
                                  where status = 1 and users.id =' .$user->id. ' 
                                  ) as t
                                  on u.id = t.user_second_id
                        ');
      
        $list2 = DB::select('select u.id, u.name, u.email
                            from users as u
                                    join (
                                    select r.user_first_id
                                    from users join relationships as r on users.id = r.user_second_id
                                    where status = 1  and users.id =' .$user->id. '
                                    ) as t
                            on u.id = t.user_first_id
                        ');
        $list = array_merge($list1, $list2);
        return $list;

    }


    public function viewAllRequest() {
        $user = Auth::user();

        $list1 = DB::select('select u.id, u.name, u.email
                            from users as u
                                join
                                 (
                                  select r.user_second_id
                                  from users join relationships as r on users.id = r.user_first_id
                                  where status = -1 and users.id =' .$user->id. ' 
                                  ) as t
                                  on u.id = t.user_second_id
                        ');
      
        $list2 = DB::select('select u.id, u.name, u.email
                            from users as u
                                    join (
                                    select r.user_first_id
                                    from users join relationships as r on users.id = r.user_second_id
                                    where status = -2  and users.id =' .$user->id. '
                                    ) as t
                            on u.id = t.user_first_id
                        ');
        $list = array_merge($list1, $list2);
        return $list;
    }
}
/*
pending_first_accept        -1
pending_second_accept       -2
friends                      1

//




first_block                 -3
second_block                -4
block_both                  -5

*/