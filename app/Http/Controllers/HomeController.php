<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function home()
    {
        if (Auth::check()) {
            return view('home.homepage');
        } else {
            return redirect()->route('login');
        }
    }
}
