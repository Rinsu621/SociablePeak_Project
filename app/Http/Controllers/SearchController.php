<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Friend;
use App\Models\UserDetail;
use App\Models\Report;
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FriendController;

class SearchController extends Controller
{
    protected $friendController;

    public function __construct(FriendController $friendController)
    {
        $this->friendController = $friendController;
    }

    public function search(Request $request)
    {
        if (Auth::check()) {
            $query = $request->input('query');

            // Perform search on the 'users' table
            $users = User::where('name', 'LIKE', "%{$query}%")
                          ->orWhere('email', 'LIKE', "%{$query}%")
                          ->with('profilePicture') // Assuming you have a profilePicture relationship defined in the User model
                          ->get();

            return view('search.usersearch', compact('users', 'query'));
        } else {
            return redirect()->route('login');
        }
    }

    public function show($id)
{
    $loggedInUserId = Auth::id();
    $user = User::findOrFail($id);

    $posts = Post::with(['images', 'likes.user', 'comments.user.profilePicture'])
                 ->where('user_id', $id)
                 ->orderBy('id', 'desc')
                 ->get();

    $postCount = $posts->count();

    $friends = Friend::where(function($query) use ($id) {
        $query->where('user_id', $id)
              ->orWhere('friend_id', $id);
    })
    ->where('status', 'accepted')
    ->orderBy('id', 'desc')
    ->get();

    $friendDetails = [];
    $friendsCount = $friends->count();

    foreach ($friends as $friend) {
        $friendUserId = ($friend->friend_id == $id) ? $friend->user_id : $friend->friend_id;
        $friendUser = User::find($friendUserId);
        $userDetails = UserDetail::where('user_id', $friendUserId)->first();
        $profilePicture = ProfilePicture::where('user_id', $friendUserId)->latest()->first();

        $friendDetails[] = [
            'user' => $friendUser ? $friendUser->toArray() : null,
            'user_details' => $userDetails ? $userDetails->toArray() : null,
            'profile_picture' => $profilePicture ? $profilePicture->file_path : null,
            'friend' => $friend->toArray()
        ];
    }

    $profilePicture = $user->profilePicture;

    // Check if the logged-in user is friends with the viewed user
    $areFriends = Friend::where(function ($query) use ($loggedInUserId, $id) {
        $query->where('user_id', $loggedInUserId)
              ->where('friend_id', $id)
              ->where('status', 'accepted');
    })->orWhere(function ($query) use ($loggedInUserId, $id) {
        $query->where('user_id', $id)
              ->where('friend_id', $loggedInUserId)
              ->where('status', 'accepted');
    })->exists();

    $receivedFriendRequest = Friend::where('user_id', $id)
                                   ->where('friend_id', $loggedInUserId)
                                   ->where('status', 'pending')
                                   ->first();

    $sentFriendRequest = Friend::where('user_id', $loggedInUserId)
                               ->where('friend_id', $id)
                               ->whereIn('status', ['pending', 'accepted'])
                               ->first();

    return view('search.profileview', [
        'user' => $user,
        'posts' => $posts,
        'postCount' => $postCount,
        'friends' => $friendDetails,
        'friendsCount' => $friendsCount,
        'profilePicture' => $profilePicture,
        'receivedFriendRequest' => $receivedFriendRequest,
        'sentFriendRequest' => $sentFriendRequest,
        'areFriends' => $areFriends
    ]);
}

public function reportUser(Request $request, $id)
{
    $request->validate([
        'reason' => 'required|string|max:255',
    ]);

    Report::create([
        'reporter_id' => Auth::id(),
        'reported_user_id' => $id,
        'reason' => $request->input('reason'),
    ]);

    return redirect()->back()->with('message', 'User reported successfully.');
}

}
