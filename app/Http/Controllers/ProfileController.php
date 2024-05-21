<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\Post;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        //get posts of user
        $posts = Post::where('user_id',auth()->id())->orderBy('id','desc')->get();
        $friends = Friend::where('user_id',auth()->id())->with('friend')->with('friendDetail')->orderBy('id','desc')->get();
        // dd($friends->toArray());
        return view('profile.index',[
            'posts' => $posts,
            'friends' => $friends,
        ]);
    }
}
