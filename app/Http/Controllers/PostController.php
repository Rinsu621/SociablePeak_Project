<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
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
                    // Create image model instance for each image
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
}
