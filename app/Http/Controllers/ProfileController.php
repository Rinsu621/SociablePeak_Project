<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\ProfilePicture;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;
use App\Models\Ad;
use App\Models\AdLike;
use App\Models\AdComment;
use App\Models\AdImage;
use App\Models\Follow;



class ProfileController extends Controller
{
    public function index()
    {

        // Get posts of user
        $userId = auth()->id();
        $user = Auth::user();
        // $posts = Post::where('user_id', $userId)->orderBy('id', 'desc')->get();
        $posts = Post::with(['images', 'likes.user', 'comments.user.profilePicture'])
        ->where('user_id', $userId)
        ->orderBy('id', 'desc')
        ->get();
        $postCount = $posts->count(); // Get the count of posts

        $friends = Friend::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('friend_id', $userId);
        })
        ->where('status', 'accepted')
        ->orderBy('id', 'desc')
        ->get();

        $friendDetails = [];
        $friendsCount = $friends->count();

        foreach ($friends as $friend) {
            $friendUserId = ($friend->friend_id == $userId) ? $friend->user_id : $friend->friend_id;
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

        // Get the latest profile picture
        $user = Auth::user();
        $profilePicture = $user->profilePicture;

       


        return view('profile.index', [
            'posts' => $posts,
            'friends' => $friendDetails,
            'postCount' => $postCount, // Pass the post count to the view
            'profilePicture' => $profilePicture,
            'friendsCount' => $friendsCount,
            'user'=>$user,

        ]);
    }

    public function updatePicture(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:2048',
    ]);

    $userId = auth()->id();

    if ($request->hasFile('image') && $request->file('image')->isValid()) {
        $path = $request->file('image')->store('public/images/profile');

        // Check if the file was uploaded correctly
        if ($path) {
            // Save the new profile picture without deleting the old one
            ProfilePicture::create([
                'user_id' => $userId,
                'file_path' => $path
            ]);

            return redirect()->back()->with('message', 'Profile picture updated successfully.');
        } else {
            return redirect()->back()->withErrors('Failed to upload image.');
        }
    }

    return redirect()->back()->withErrors('Invalid image file.');
}



public function show($id)
    {
        // Retrieve the business profile by ID
        $business = Business::findOrFail($id);
        $userAdsCount = Ad::where('business_id', $business->id)->count();
        $user = auth()->user();

        \Log::info("Business ID: " . $id);
        \Log::info("User ID: " . $id);

        $isFollowing = Follow::where('follower_id', $user->id)
        ->where('following_id', $business->id)
        ->exists();

        $ads = Ad::with('adimages','adLikes.user','adLikes.business')->where('business_id', $business->id)->latest()->get();

        $totalFollowers = Follow::where('following_id', $business->id)->count();

        $followers = Follow::where('following_id', $business->id)
        ->with('follower') // Assuming 'follower' relationship exists on the Follow model
        ->get()
        ->pluck('follower'); // Extract only the followers (User model)
        // Pass the business data to the view
        return view('business.businessprofile', compact('business','userAdsCount','user','ads','isFollowing','totalFollowers','followers'));
    }

    public function follow(Request $request, $businessId)
    {
        $user = auth()->user(); // Get the currently authenticated user
        \Log::info("User is trying to follow/unfollow business with ID: $businessId");
        // Check if the user is already following the business
        $existingFollow = Follow::where('follower_id', $user->id)
                                ->where('following_id', $businessId)
                                ->first();

        if ($existingFollow) {
            // Unfollow the business
            \Log::info("Unfollowing business with ID: $businessId");
            $existingFollow->delete();
            return redirect()->back()->with('status', 'Unfollowed successfully!');
        } else {
            \Log::info("Following business with ID: $businessId");
            // Follow the business
            Follow::create([
                'follower_id' => $user->id,
                'following_id' => $businessId
            ]);
            return redirect()->back()->with('status', 'Followed successfully!');
        }
    }

    public function viewProfile($userId)
    { $currentUser = Auth::user(); // Get the currently authenticated user (the viewer)
        $viewedUser = User::findOrFail($userId); // Get the user whose profile is being viewed

        // Log the profile view action for debugging
        \Log::info('Recording profile view: ', ['viewer_id' => $currentUser->id, 'viewed_id' => $viewedUser->id]);

        // Track profile views for other users viewing the profile
        if ($currentUser->id != $viewedUser->id) { // Avoid tracking when the user views their own profile
            // Check if the profile view has already been recorded
            $existingView = ProfileView::where('viewer_id', $currentUser->id)
                                       ->where('viewed_id', $viewedUser->id)
                                       ->exists();

            if (!$existingView) {
                // Record the profile view if not already recorded
                ProfileView::create([
                    'viewer_id' => $currentUser->id,   // User who viewed the profile
                    'viewed_id' => $viewedUser->id     // User whose profile is being viewed
                ]);
            }
        }

        // Fetch the number of people who have viewed this user's profile
        $viewsCount = ProfileView::where('viewed_id', $viewedUser->id)->count();

        // Get a list of users who have viewed this profile, along with their details
        $viewers = ProfileView::where('viewed_id', $viewedUser->id)
                              ->with('viewer')  // Eager load the viewer's user data
                              ->get()
                              ->pluck('viewer'); // Extract the viewer data

        // Return the view with profile view data
        return view('analytics.profileView', [
            'viewedUser' => $viewedUser,
            'viewsCount' => $viewsCount,
            'viewers' => $viewers,
            'user' => $currentUser
        ]);
    }

}
