<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Friend;
use App\Models\User;
use App\Models\Ad;
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

            $likedCategories = Ad::whereHas('adLikes', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->pluck('category')
            ->unique();

            $similarAds = Ad::whereIn('category', $likedCategories)
            ->whereNotIn('id', $user->adLikes()->pluck('ad_id')) // Exclude already liked ads
            ->inRandomOrder()
            ->get();

            $randomAds = Ad::inRandomOrder() ->get();

            // $ads = Ad::with('adimages', 'adLikes.user', 'adLikes.business', 'comments.user','business')  // Include related models for likes and comments
            //      ->inRandomOrder()
            //      ->get();

            $ads = $similarAds->merge($randomAds)->unique('id');
            // Fetch friends' IDs
            $friendIds = Friend::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('friend_id', $userId);
            })->where('status', 'accepted')
              ->get()
              ->map(function($friend) use ($userId) {
                  return $friend->user_id == $userId ? $friend->friend_id : $friend->user_id;
              })
              ->toArray();



            // Add the user's own ID to see their posts too
            $friendIds[] = $userId;

            // Fetch posts from friends ordered by creation date in descending order
            $posts = Post::with('images', 'user', 'likes.user', 'comments.user.profilePicture') // Assuming you have relationships defined
                         ->whereIn('user_id', $friendIds)
                         ->orderBy('created_at', 'desc')
                         ->get();

            // Fetch the latest profile picture for the authenticated user
            $profilePicture = $user->profilePicture;

            // Fetch pending friend requests
            $friendRequests = Friend::where('friend_id', $userId)
                                    ->where('status', 'pending')
                                    ->with('user.profilePicture')
                                    ->orderBy('created_at', 'desc')
                                    ->limit(3)
                                    ->get();

             $suggestedFriends = $this->getFriendSuggestions($userId, $friendIds);
             $query = Ad::query();

            return view('home.homepage', [
                'posts' => $posts,
                'profilePicture' => $profilePicture,
                'user' => $user,
                'friendRequests' => $friendRequests,
                'suggestedFriends' => $suggestedFriends,
                'ads'=>$ads
            ]);
        } else {
            return redirect()->route('login');
        }
    }

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
        //above code retrieves your friends whose status is not pending
        // output will be 4,8,2

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
        //above code will retrieve at unique friends list of your friends
        // output will be 3,2,1,9,10

        // Step 3: Filter out the friends who are already your friends
        $myFriendsOfFriendsWhoAreNotMyFriends = array_diff($friendsOfMyFriends, $myFriends, [$userId]);
        //above code will pop your friends from the list, including your id from your friends friends list
        // [3,2,1,9,10] - [4,8,2,1] = [3,9,10]
        // dd($myFriendsOfFriendsWhoAreNotMyFriends);

        // Step 3: Fetch suggested friends with profile pictures
        return User::whereIn('id', $myFriendsOfFriendsWhoAreNotMyFriends)
                ->with('profilePicture')
                ->take(5) // Limit the suggestions to 5 users
                ->get();
}
}
