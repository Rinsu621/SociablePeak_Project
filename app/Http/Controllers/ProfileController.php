<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(){
        //get posts of user
        $posts = Post::where('user_id',auth()->id())->orderBy('id','desc')->get();
        return view('profile.index',[
            'posts' => $posts
        ]);
    }
}
