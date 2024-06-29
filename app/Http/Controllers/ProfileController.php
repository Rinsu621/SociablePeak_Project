<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
            'friendsCount' => $friendsCount
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

            // Save the new profile picture without deleting the old one
            ProfilePicture::create([
                'user_id' => $userId,
                'file_path' => $path
            ]);

            return redirect()->back()->with('message', 'Profile picture updated successfully.');
        }

        return redirect()->back()->withErrors('Invalid image file.');
    }
}
