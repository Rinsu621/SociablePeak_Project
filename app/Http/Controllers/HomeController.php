<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Friend;
use App\Models\User;
use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $user = Auth::user();

            // Fetch friends' IDs
            $friendIds = Friend::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('friend_id', $userId);
            })->where('status', 'accepted')
              ->get()
              ->pluck('user_id', 'friend_id')
              ->flatten()
              ->unique()
              ->toArray();

            // Add the user's own ID to see their posts too
            $friendIds[] = $userId;

            // Fetch posts from friends ordered by creation date in descending order
            $posts = Post::with('images', 'user','likes.user', 'comments.user.profilePicture') // Assuming you have relationships defined
                         ->whereIn('user_id', $friendIds)
                         ->orderBy('created_at', 'desc')
                         ->get();

            // Fetch the latest profile picture for the authenticated user
            $profilePicture = $user->profilePicture;

            $friendRequests = Friend::where('friend_id', $userId)
                                    ->where('status', 'pending')
                                    ->with('user.profilePicture')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(3)
                                    ->get();
             $suggestedFriends = $this->getFriendSuggestions($userId, $friendIds);
            //  dd($suggestedFriends->toArray());

            return view('home.homepage', [
                'posts' => $posts,
                'profilePicture' => $profilePicture,
                'user' => $user,
                'friendRequests' => $friendRequests,
                'suggestedFriends' => $suggestedFriends
            ]);
        } else {
            return redirect()->route('login');
        }
    }


    // Find mutual friends
    private function getFriendSuggestions($userId, $friendIds)
{
        $userId = Auth::id();

        // Get the user's friends
        $myFriends = Friend::where(function($query) use ($userId) {
                            $query->where('user_id', $userId)
                                ->orWhere('friend_id', $userId);
                        })
                        ->where('status', '!=', 'pending')
                        ->get()
                        ->map(function($friend) use ($userId) {
                            return $friend->user_id == $userId ? $friend->friend_id : $friend->user_id;
                        })
                        ->toArray();
        // dd($myFriends);
      // Step 2: Retrieve the list of friends of your friends
$friendsOfMyFriends = Friend::where(function($query) use ($myFriends) {
    $query->whereIn('user_id', $myFriends)
          ->orWhereIn('friend_id', $myFriends);
})
->where('status', '!=', 'pending')
->get()
->map(function($friend) use ($myFriends) {
    return in_array($friend->user_id, $myFriends) ? $friend->friend_id : $friend->user_id;
})
->unique()
->toArray();

// Step 3: Filter out the friends who are already your friends
$myFriendsOfFriendsWhoAreNotMyFriends = array_diff($friendsOfMyFriends, $myFriends, [$userId]);

// dd($myFriendsOfFriendsWhoAreNotMyFriends);
    // Step 3: Fetch suggested friends with profile pictures
    return User::whereIn('id', $myFriendsOfFriendsWhoAreNotMyFriends)
               ->with('profilePicture')
               ->take(5) // Limit the suggestions to 5 users
               ->get();
}
}

