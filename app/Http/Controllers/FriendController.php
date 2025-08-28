<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function createFriends()
    {
        $loggedInUser = Auth::user();
        $users = User::where('id', '!=', $loggedInUser->id)->get();

        foreach ($users as $potentialFriend) {
            // Check if this friendship already exists
            $friendshipExists = Friend::where(function ($query) use ($loggedInUser, $potentialFriend) {
                $query->where('user_id', $loggedInUser->id)
                      ->where('friend_id', $potentialFriend->id);
            })->exists();

            if (!$friendshipExists) {
                Friend::create([
                    'user_id' => $loggedInUser->id,
                    'friend_id' => $potentialFriend->id,
                    'status' => 'pending'
                ]);
            }
        }

        return redirect()->route('profile');
    }

    public function addFriend($id)
    {
        //Get Loggedin user
        $loggedInUserId = Auth::id();

        // Check if this friendship already exists
        $friendshipExists = Friend::where(function ($query) use ($loggedInUserId, $id) {
            $query->where('user_id', $loggedInUserId)
                  ->where('friend_id', $id);
        })->orWhere(function ($query) use ($loggedInUserId, $id) {
            $query->where('user_id', $id)
                  ->where('friend_id', $loggedInUserId);
        })->exists();

        if (!$friendshipExists) {
            Friend::create([
                'user_id' => $loggedInUserId,
                'friend_id' => $id,
                'status' => 'pending'
            ]);
        }

        return redirect()->back()->with('message', 'Friend request sent.');
    }

    public function checkIfFriends($userId)
    {
        $loggedInUserId = auth()->id();

        return Friend::where(function ($query) use ($loggedInUserId, $userId) {
            $query->where('user_id', $loggedInUserId)
                  ->where('friend_id', $userId);
        })->orWhere(function ($query) use ($loggedInUserId, $userId) {
            $query->where('user_id', $userId)
                  ->where('friend_id', $loggedInUserId);
        })->first();
    }

    public function acceptFriendRequest($id)
    {
        $loggedInUserId = Auth::id();
        $friendRequest = Friend::where('user_id', $id)
                               ->where('friend_id', $loggedInUserId)
                               ->where('status', 'pending')
                               ->first();

        if ($friendRequest) {
            $friendRequest->status = 'accepted';
            $friendRequest->save();
        }

        return redirect()->route('friend.friendrequest')->with('message', 'Friend request accepted.');
    }

    public function rejectFriendRequest($id)
    {
        $loggedInUserId = Auth::id();
        $friendRequest = Friend::where('user_id', $id)
                               ->where('friend_id', $loggedInUserId)
                               ->where('status', 'pending')
                               ->first();

        if ($friendRequest) {
            $friendRequest->delete();
        }

        return redirect()->route('friend.friendrequest')->with('message', 'Friend request rejected.');
    }

    public function unfriend($id)
    {
        $loggedInUserId = Auth::id();

        // Find the friendship record and delete it
        $friendship = Friend::where(function ($query) use ($loggedInUserId, $id) {
            $query->where('user_id', $loggedInUserId)
                  ->where('friend_id', $id);
        })->orWhere(function ($query) use ($loggedInUserId, $id) {
            $query->where('user_id', $id)
                  ->where('friend_id', $loggedInUserId);
        })->first();

        if ($friendship) {
            $friendship->delete();
        }

        return redirect()->back()->with('message', 'Friend removed successfully.');

    }

    public function friendRequest()
    {
        $loggedInUserId = Auth::id();
        $friendRequests = Friend::where('friend_id', $loggedInUserId)
                                ->where('status', 'pending')
                                ->with('user') // Assuming you have a relationship defined in the Friend model
                                ->get();

        return view('friend.friendrequest', ['friendRequests' => $friendRequests]);
    }

}
