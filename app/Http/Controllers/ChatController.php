<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Message;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\ProfilePicture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    public function index(){
        // Get the logged-in user's ID
        $userId = Auth::id();

        // Retrieve messages where friend_id or user_id is the logged-in user's ID
        $messages = Message::where('user_id', $userId)
                            ->orWhere('friend_id', $userId)
                            // ->orderBy('created_at', 'desc')
                            ->get()
                            ->groupBy(function($message) use ($userId) {
                                return $message->user_id == $userId ? $message->friend_id : $message->user_id;
                            });

        // dd($messages->toArray());
        // Convert the created_at to Kathmandu timezone and sort each message group by created_at in descending order
        $sortedMessages = $messages->map(function ($group) {
            return $group->map(function ($message) {
                $message->converted_date = convertTimezone('Asia/Kathmandu', $message->created_at);
                return $message;
            });
        });

        // Fetch the friend details using the grouped friend IDs
        $friendIds = $sortedMessages->keys();
        $friends = User::whereIn('id', $friendIds)->with('profilePicture')->get()->keyBy('id');
        $friendDetails = UserDetail::whereIn('user_id', $friendIds)->get()->keyBy('user_id');

        $chatData = $sortedMessages->map(function ($messages, $friendId) use ($friends) {
            $friendName = $friends[$friendId]->name ?? 'Unknown';
            $friendProfilePicture = $friends[$friendId]->profilePicture
                ? asset(Storage::url($friends[$friendId]->profilePicture->file_path))
                : asset('images/template/user/11.png');


            return [
                'friend_id' => $friendId,
                'friend_name' => $friendName,
                'friend_profile_picture' => $friendProfilePicture,
                'conversations' => $messages->toArray()
            ];
        });

$userProfilePicture = Auth::user()->profilePicture ? Auth::user()->profilePicture->file_path : 'images/template/user/11.png';
        $userImage = asset('storage/' . $userProfilePicture);


        // Pass the grouped messages to the view
        return view('chat.index',[
            'messages' => $chatData,
            'userImage' => $userImage
        ]);
    }

    public function sendMessage(Request $request){
        try {
            $validatedData = $request->validate([
                'friend_id' => 'required|integer',
                'message' => 'required|string'
            ]);

            $message = new Message();
            $message->user_id = auth()->id();
            $message->friend_id = $validatedData['friend_id'];
            // $message->message = $validatedData['message'];
            $message->message = $this->encryptMessage($validatedData['message']);
            $message->save();

            // Fetch the message just saved
            $savedMessage = Message::find($message->id);
            $savedMessage->message = $this->decryptMessage($savedMessage->message);

            // Convert the created_at timestamp to the 'Asia/Kathmandu' timezone
            $savedMessage->created_at = $savedMessage->created_at->timezone('Asia/Kathmandu');

            return response()->json(['message' => 'Message Sent','data' => $savedMessage ], 200);
        } catch (ValidationException $e) {
            // If a validation error occurs, catch the ValidationException
            // and redirect back with the validation error messages
            return response()->json(['message' => $e->validator->getMessageBag()], 500);
        } catch (Exception $e) {
            // If any other type of exception occurs, catch it and
            // redirect back with the exception message
            return response()->json(['message' => $e->getMessage()], 500);
        }

    }

    protected function encryptMessage($message) {
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('app.encryption_key');
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryptMethod));
        $encryptedMessage = openssl_encrypt($message, $encryptMethod, $secretKey, 0, $iv) . "::" . bin2hex($iv);
        return $encryptedMessage;
    }

    protected function decryptMessage($encryptedMessage) {
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('app.encryption_key');
        list($encryptedData, $iv) = explode("::", $encryptedMessage, 2);
        return openssl_decrypt($encryptedData, $encryptMethod, $secretKey, 0, hex2bin($iv));
    }
}
