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
    // Step 1: Get the IDs of friends of the user's friends who are not friends with the user
    // $friendsOfFriends = DB::table('friends as f1')
    //     ->join('friends as f2', 'f1.friend_id', '=', 'f2.user_id')
    //     ->where('f1.user_id', $userId)
    //     ->whereNotIn('f2.friend_id', $friendIds)
    //     ->where('f2.friend_id', '!=', $userId)
    //     ->select('f2.friend_id')
    //     ->distinct()
    //     ->pluck('f2.friend_id')
    //     ->toArray();

    // Step 2: Count the number of mutual friends between the user and these other users
    // $mutualFriendCounts = DB::table('friends as f1')
    //     ->join('friends as f2', function ($join) use ($userId) {
    //         $join->on('f1.friend_id', '=', 'f2.friend_id')
    //             ->where('f1.user_id', '!=', $userId);
    //     })
    //     ->where('f1.user_id', $userId)
    //     ->whereIn('f2.user_id', $friendsOfFriends)
    //     ->select('f2.user_id', DB::raw('count(f2.friend_id) as mutual_count'))
    //     ->groupBy('f2.user_id')
    //     ->having('mutual_count', '>', 2)
    //     ->pluck('f2.user_id')
    //     ->toArray();
        $userId = 3;

        // Get the user's friends
        $myFriends = Friend::where('user_id', $userId)
                            ->orWhere('friend_id', $userId)
                            ->get()
                            ->map(function($friend) use ($userId) {
                                return $friend->user_id == $userId ? $friend->friend_id : $friend->user_id;
                            })->toArray();
        
        // Get the friends of user's friends
        $friendsOfMyFriends = Friend::whereIn('user_id', $myFriends)
                                     ->orWhereIn('friend_id', $myFriends)
                                     ->where(function($query) use ($userId, $myFriends) {
                                         $query->whereNotIn('user_id', [$userId])
                                               ->whereNotIn('friend_id', [$userId])
                                               ->whereNotIn('user_id', $myFriends)
                                               ->whereNotIn('friend_id', $myFriends);
                                     })
                                     ->get()
                                     ->map(function($friend) use ($myFriends) {
                                         return in_array($friend->user_id, $myFriends) ? $friend->friend_id : $friend->user_id;
                                     })
                                     ->unique()
                                     ->values(); // Reset the keys
        // dd($friendsOfMyFriends);
    // Step 3: Fetch suggested friends with profile pictures
    return User::whereIn('id', $friendsOfMyFriends)
               ->with('profilePicture')
               ->take(5) // Limit the suggestions to 5 users
               ->get();
}
}

