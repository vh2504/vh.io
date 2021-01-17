<?php

namespace App\Services;

use App\Models\User;
use App\Models\Relationship;
use App\Http\Resources\User as UserResource;

class FriendService {

    public function __construct(){}

    /**
     * View all friend
     */
    public static function viewAllFriends(User $user){
        $result = [];
        $listFriend1 = $user->getFriendOne->where('status', 1);
        foreach($listFriend1 as $friend)
            $result[] = new UserResource($friend->getUserTwo);

        $listFriend2 = $user->getFriendTwo->where('status', 1);
        foreach($listFriend2 as $friend)
            $result[] = new UserResource($friend->getUserOne);
        
        return $result;
    }

    /**
     * @param App\Models\User $user
     * view list friend request
     */

    public static function viewListRequest(User $user){
        $result = [];

        $listFriend1 = $user->getFriendOne->where('status', -1);
        foreach($listFriend1 as $friend)
            $result[] = new UserResource($friend->getUserTwo);
        
        $listFriend2 = $user->getFriendTwo->where('status', -2);
        foreach($listFriend2 as $friend)
            $result[] = new UserResource($friend->getUserOne);
        
        return $result;
    }

    /**
     * @param int $friendId
     * unfriend a friend
     */

     public static function unfriend(int $friendId){
         $rs = 0;
        if($user->id < $friendId) {
            $rs = Relationship::where('user_first_id', $user->id)
                                ->where('user_second_id', $friendId)
                                ->where('status' , 1)
                                ->delete();
        }
        else
        {
            $rs = Relationship::where('user_second_id',$user->id )
                                ->where('user_first_id', $friendId)
                                ->where('status' , 1)
                                ->delete();
        }
        return $rs;
     }


     /**
      * @param int $friendId
      * accept friend with a friend request
      */
    public static function acceptFriend($friendId){
        $rs = 0;

        if($user->id < $friendId) {
            $rs = Relationship::where('user_first_id', $user->id)
                                ->where('user_second_id', $friendId)
                                ->where('status' , -1)
                                ->update(['status' => 1]);
        }
        else
        {
            $rs = Relationship::where('user_second_id',$user->id )
                                ->where('user_first_id', $friendId)
                                ->where('status' , -2)
                                ->update(['status' => 1]);
        }
        return $rs;
    }
}

?>