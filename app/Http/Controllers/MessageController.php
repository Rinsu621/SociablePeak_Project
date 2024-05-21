<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        // $loggedInUser = Auth::user();
        // $messages = Message::where('user_id', $loggedInUser->id)
        //     ->orWhere('friend_id', $loggedInUser->id)
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        // return view('messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'friend_id' => 'required|exists:users,id',
        //     'message' => 'required|string',
        // ]);

        // Message::create([
        //     'user_id' => Auth::id(),
        //     'friend_id' => $request->friend_id,
        //     'message' => $request->message,
        // ]);

        // return redirect()->route('messages.index');
    }
}
