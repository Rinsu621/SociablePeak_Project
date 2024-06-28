<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Friend;
use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $posts = Post::with('images', 'user') // Assuming you have relationships defined
                         ->whereIn('user_id', $friendIds)
                         ->orderBy('created_at', 'desc')
                         ->get();

            // Fetch the latest profile picture for the authenticated user
            $profilePicture = $user->profilePicture;

            return view('home.homepage', [
                'posts' => $posts,
                'profilePicture' => $profilePicture,
                'user' => $user
            ]);
        } else {
            return redirect()->route('login');
        }
    }
}
