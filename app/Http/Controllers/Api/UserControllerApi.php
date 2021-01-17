<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\Relationship;
use App\Http\Resources\User as UserResource;
use App\Services\FriendService;
namespace App\Traits\Helper;

class UserControllerApi extends Controller
{

    public function handleLoginToken(Request $request){
        $token = $request->token;

        $user = Socialite::driver('facebook')->userFromToken($token);
        
        $email =  $user->getEmail();
        
        if(!empty($email)) {
            $userDB = User::where('email', $email)->exists();

            if(!$userDB) {
                $name = $user->getName();
                $newUser = User::create([
                    'email'=>$email,
                    'name' => $name,
                ]);
                $newUser->save();
                $accessToken = $newUser->createToken('authToken')->accessToken;

                $id =   User::where('email', $email)->value('id');

                if(Auth::loginUsingId($id))
                    return  response()->json(['user'=>Auth::user(), 'token'=> $accessToken], 201);
                else 
                    return response()->json(['message'=> 'login failed'], 401);
            }
            else{
                $id =   User::where('email', $email)->value('id');

                if(Auth::loginUsingId($id))
                    return  response()->json(Auth::user() , 200);
                else
                    return response()->json(['message'=> 'login failed'], 401);
            }
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
                $atoken = $user->createToken('Token')->accessToken;

                if(Auth::loginUsingId($id))
                     return  response()->json(['user'=>Auth::user(), 'token'=> $atoken], 201);
                else
                    return response()->json(['message'=> 'login failed'], 401);
            }
            else {
                if(Auth::loginUsingId($id))
                    return  response()->json(Auth::user(), 200);
                else
                    return response()->json(['message'=> 'login failed'], 401);
            }
        }
    
    }

    /**
     * add friend from app
     */
    public function addFriend(Request $request,$friendId ){     
        $user = $request->user();
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
                $user = Relationship::create([
                    'user_first_id' => $user->id,
                    'user_second_id' =>$friendId,
                    'status' => -2
                ]);
                $user->save();
            }
            return Helper::sendResponseWithoutData(true, 200, 'Add friend success');
        }
        else
            return Helper::sendResponseWithoutData(false, 400, 'Add friend failed');
    }

    /**
     * accept requesting friend 
     * @param int $friendId
     */
    public function acceptFriend( $friendId){
        $user = Auth::user();
        if(!$user)
            return Helper::sendResponseWithoutData(false, 400, 'Accept friend failed');
        $friendId = (int) $friendId;
        $result = FriendService::acceptFriend($friendId);

        if($result) 
            return Helper::sendResponseWithoutData(true, 200, 'Accept friend success');
            
        return Helper::sendResponseWithoutData(false, 400, 'Accept friend failed');
    }

    /**
     * unfriend with 1 friend
     * @param int $friendId
     */
    public function unfriend($friendId) {
        $user = Auth::user();

        $friendId = (int) $friendId;
        $result = FriendService::unfriend($friendId);

        if($result)
            return Helper::sendResponseWithoutData(true, 200, 'Unfriend success');
        else
            return Helper::sendResponseWithoutData(true, 200, 'Unfriend failed');
    }

    /**
     * view list friend
     */
    public function viewAllFriend() {
        $user = Auth::user();

        $result = FriendService::viewAllFriends($user);
        if(!empty($result))
            return Helper::SendResponse(true, $result, 200, 'Success');
        else    
            return Helper::SendResponse(true, '', 400, 'Failed');
    }

    /**
     * Xem tat ca loi moi ket ban
     */
    public function viewAllRequest() {
        $user = Auth::user();
        $result = FriendService::viewListRequest($user);

        if(!empty($result))
            return Helper::SendResponse(true, $result, 200, 'Success');
        else    
            return Helper::SendResponse(true, '', 400, 'Failed');
    }
}


// pending_first_accept        -1
// pending_second_accept       -2
// friends                      1






/*
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function get_user_token(Request $request){
        return response($request->user());
    }


    public function store(Request $request)
    {
        //
        return response()->json(User::create($request->all()));
    }

    public function show($id)
    {
        //
        return response()->json(User::find($id));
    }

    public function update(Request $request, $id)
    {
        return response()->json(User::where('id', $id)->update($request->all()));
    }

    public function destroy($id)
    {
        return response()->json(User::find($id)->delete());
    }
*/