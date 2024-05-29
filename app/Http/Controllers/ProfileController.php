<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        //get posts of user
        $posts = Post::where('user_id',auth()->id())->orderBy('id','desc')->get();
        $friends = Friend::where('user_id',auth()->id())->orWhere('friend_id',auth()->id())->orderBy('id','desc')->get();
        $friendDetails = [];

        foreach ($friends as $friend) {
            if ($friend->friend_id == auth()->id()) {
                $userId = $friend->user_id;
            } else {
                $userId = $friend->friend_id;
            }

            $user = User::find($userId)->toArray();
            $userDetails = UserDetail::where('user_id', $userId)->first()->toArray();

            $friendDetails[] = [
                'user' => $user,
                'user_details' => $userDetails,
                'friend' => $friend->toArray()
            ];
        }
        // dd($friendDetails);
        return view('profile.index',[
            'posts' => $posts,
            'friends' => $friendDetails,
        ]);
    }
}
