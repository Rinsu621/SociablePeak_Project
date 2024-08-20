<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Friend;
use App\Models\Post;
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::with(['reporter.profilePicture', 'reportedUser.profilePicture'])->get();
        return view('admin.report', compact('reports'));
    }

    public function viewProfile($id)
    {
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
            $profilePicture = $friendUser->profilePicture;

            $friendDetails[] = [
                'user' => $friendUser ? $friendUser->toArray() : null,
                'profile_picture' => $profilePicture ? $profilePicture->file_path : null,
            ];
        }

        $profilePicture = $user->profilePicture;

        return view('admin.profile', [
            'user' => $user,
            'posts' => $posts,
            'postCount' => $postCount,
            'friends' => $friendDetails,
            'friendsCount' => $friendsCount,
            'profilePicture' => $profilePicture
        ]);
    }
    public function deleteAccount($id)
{
    $user = User::findOrFail($id);

    // Optionally, delete related data such as posts, comments, etc.
    // $user->posts()->delete();
    // $user->comments()->delete();

    // Delete the user
    $user->delete();

    // Redirect back to the reports page with a success message
    return redirect()->route('admin.reports.index')->with('success', 'User account deleted successfully.');
}

}
