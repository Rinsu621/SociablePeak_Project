<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Message;
use App\Models\User;
use App\Models\Image;
use App\Models\ScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function postStore(Request $request){
        // dd($request->all());
        try {
            // Retrieve the authenticated user's ID
            $userId = auth()->id();

            // Validate request data
            $request->validate([
                'description' => 'string',
                'image.*' => 'nullable|image|mimes:jpeg,jpg,png,gif',

            ]);

            // Retrieve data from the POST request
            // $data = [
            //     'user_id' => $userId,
            //     'description' => $request->input('description'),
            // ];

            //new
            $post = Post::create([
                'user_id' => $userId,
                'description' => $request->input('description'),
            ]);

            // if ($request->hasFile('image') && $request->file('image')->isValid()) {
            //     $imagePath = $request->file('image')->store('public/images/template/postimg', 'public');
            //     $data['image'] = $imagePath; // Storing the path in the database
            // }
            if ($request->hasFile('image')) {
                foreach ($request->file('image') as $image) {
                    $path = $image->store('public/images/postimg');
                    $post->images()->create([
                        'file_path' => $path,
                    ]);
                }
            }


            // $set_time = $request->input('set_time');
            // if(!empty($set_time)){
            //     $data['set_time'] = $set_time;
            //     $data['status'] = 0;
            //     ScheduledPost::create($data);
            // }else{
            //     Post::create($data);
            // }
            $set_time = $request->input('set_time');
        if (!empty($set_time)) {
            $post->set_time = $set_time;
            $post->status = 0; // Assuming status 0 is for 'scheduled'
            $post->save();
        }
        $userPostsCount = Post::where('user_id', $userId)->count();

            // Create or update user engagement data
            return redirect()->back()->with('message', 'Successfully Posted')->with('userPostsCount', $userPostsCount);
        } catch (ValidationException $e) {
            // If a validation error occurs, catch the ValidationException
            // and redirect back with the validation error messages
            return redirect()->back()->withErrors($e->getMessage())->withInput();

        } catch (Exception $e) {
            // If any other type of exception occurs, catch it and
            // redirect back with the exception message
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function likePost($id)
    {
        try {
            $userId = auth()->id();
            $post = Post::findOrFail($id);

            $like = Like::where('post_id', $id)->where('user_id', $userId)->first();

            $liked = false;
            if ($like) {
                $like->delete();
            } else {
                Like::create([
                    'post_id' => $id,
                    'user_id' => $userId,
                ]);
                $liked = true;


            }

            return response()->json(['success' => true, 'liked' => $liked]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function commentPost(Request $request, $id)
    {
        try {
            $userId = auth()->id();

            $request->validate([
                'comment' => 'required|string',
            ]);

            Comment::create([
                'post_id' => $id,
                'user_id' => $userId,
                'comment' => $request->input('comment'),
            ]);

            return redirect()->back()->with('message', 'Comment added.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    public function getPostEngagement()
    {
        try {
            // Group posts by month or any other criteria you need
            $postCounts = Post::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month');

            $labels = $postCounts->keys()->map(function($month) {
                return date('F', mktime(0, 0, 0, $month, 1));
            })->toArray();

            $postsData = $postCounts->values()->toArray();
            $posts = Post::with(['likes', 'comments', 'images'])
        ->withCount(['likes', 'comments'])
        ->orderByDesc('likes_count')
        ->orderByDesc('comments_count')
        ->get();

            return view('analytics.postEngagement', compact('labels', 'postsData', 'posts'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    public function getMostInteractedUsers()
    {
        try {
            // Get the logged-in user's ID
            $userId = Auth::id();

            // Calculate likes and comments count for each user
            $userInteractions = User::withCount(['posts as likes_count' => function ($query) {
                $query->select(\DB::raw('count(distinct likes.user_id)'))
                      ->join('likes', 'posts.id', '=', 'likes.post_id');
            }, 'posts as comments_count' => function ($query) {
                $query->select(\DB::raw('count(distinct comments.user_id)'))
                      ->join('comments', 'posts.id', '=', 'comments.post_id');
            }])->get();

            // Calculate chat messages count for each user
            $messageInteractions = Message::select('user_id', \DB::raw('count(*) as message_count'))
                ->where('friend_id', $userId)
                ->groupBy('user_id')
                ->pluck('message_count', 'user_id');

            // Combine likes, comments, and chat messages count to get total interactions
            foreach ($userInteractions as $user) {
                $user->total_interactions = $user->likes_count + $user->comments_count + ($messageInteractions[$user->id] ?? 0);
            }

            // Sort users by total interactions in descending order
            $mostInteractedUsers = $userInteractions->sortByDesc('total_interactions')->take(10);

            // Pass the data to the view
            return view('analytics.mostInteraction', compact('mostInteractedUsers'));
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

}
