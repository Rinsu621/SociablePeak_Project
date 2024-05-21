<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function createFriends(){
        $loggedInUser = Auth::user();
        $users = User::where('id', '!=', $loggedInUser->id)->get();

        foreach ($users as $potentialFriend) {
            // Check if this friendship already exists
            $friendshipExists = Friend::where(function ($query) use ($loggedInUser, $potentialFriend) {
                $query->where('user_id', $loggedInUser->id)
                      ->where('friend_id', $potentialFriend->id);
            })
            // ->orWhere(function ($query) use ($loggedInUser, $potentialFriend) {
            //     $query->where('user_id', $potentialFriend->id)
            //           ->where('friend_id', $loggedInUser->id);
            // })
            ->exists();

            if (!$friendshipExists) {
                Friend::create([
                    'user_id' => $loggedInUser->id,
                    'friend_id' => $potentialFriend->id,
                ]);
            }
        }
        return redirect()->route('profile');
    }
}
