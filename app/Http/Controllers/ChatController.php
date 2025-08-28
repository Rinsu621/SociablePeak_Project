<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Message;
use App\Models\User;
use App\Models\Group;
use App\Models\GroupMessage;

use App\Models\UserDetail;
use App\Models\Friend;
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
        $users = User::where('id', '!=', $userId)->get();

// $friendIds = Friend::where(function ($query) use ($userId) {
//     $query->where('user_id', $userId)
//           ->orWhere('friend_id', $userId);
// })->where('status', 'accepted')
//   ->get()
//   ->map(function ($friend) use ($userId) {
//       return $friend->user_id == $userId ? $friend->friend_id : $friend->user_id;
//   });

// $users = User::whereIn('id', $friendIds)->get();

        // Get user's profile picture
        $profilePicture = Auth::user()->profilePicture;

        // Retrieve individual messages where friend_id or user_id is the logged-in user's ID
        $messages = Message::where('user_id', $userId)
                            ->orWhere('friend_id', $userId)
                            ->get()
                            ->groupBy(function($message) use ($userId) {
                                return $message->user_id == $userId ? $message->friend_id : $message->user_id;
                            });

        // Convert the created_at to Kathmandu timezone and sort each message group by created_at in descending order
        $sortedMessages = $messages->map(function ($group) {
            return $group->map(function ($message) {
                $message->converted_date = convertTimezone('Asia/Kathmandu', $message->created_at);
                $message->message = $this->decryptMessage($message->message); // Decrypt message before displaying
                return $message;
            });
        });

        // Get groups where the user is a member
        $groups = Group::whereHas('members', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })->get();

        // Get group messages for each group
        $groupMessages = $groups->map(function($group) {
            $messages = GroupMessage::where('group_id', $group->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $sortedGroupMessages = $messages->map(function($message) {
                $message->converted_date = convertTimezone('Asia/Kathmandu', $message->created_at);
                $message->message = $this->decryptMessage($message->message); // Decrypt message
                return $message;
            });


            return [
                'group_id' => $group->id,
                'group_name' => $group->name,
                'messages' => $sortedGroupMessages
            ];
        });



        // Fetch the friend details using the grouped friend IDs
        $friendIds = $sortedMessages->keys();
        $friends = User::whereIn('id', $friendIds)->with('profilePicture')->get()->keyBy('id');

        // Prepare individual chat data
        $individualChatData = $sortedMessages->map(function ($messages, $friendId) use ($friends) {
            $friendName = $friends[$friendId]->name ?? 'Unknown';
            $friendProfilePicture = $friends[$friendId]->profilePicture
                ? asset(Storage::url($friends[$friendId]->profilePicture->file_path))
                : asset('images/template/user/Noprofile.jpg');

            return [
                'type' => 'individual',
                'friend_id' => $friendId,
                'friend_name' => $friendName,
                'friend_profile_picture' => $friendProfilePicture,
                'conversations' => $messages->toArray(),
                'last_message_time' => $messages->max('created_at')
            ];
        });

        // Prepare group chat data
        // $groupChatData = $groupMessages->map(function ($groupData) {
        //     return [
        //         'type' => 'group',
        //         'group_id' => $groupData['group_id'],
        //         'group_name' => $groupData['group_name'],
        //         'group_profile_picture' => asset('images/template/user/11.png'), // Use existing image
        //         'conversations' => $groupData['messages']->toArray(),
        //         'last_message_time' => $groupData['messages']->max('created_at')
        //     ];
        // });

        $groupChatData = $groupMessages->map(function ($groupData) {
    $hasMessages = $groupData['messages']->isNotEmpty();
    return [
        'type' => 'group',
        'group_id' => $groupData['group_id'],
        'group_name' => $groupData['group_name'],
        'group_profile_picture' => asset('images/template/user/11.png'),
        'conversations' => $groupData['messages']->toArray(),
        'last_message_time' => $hasMessages
            ? $groupData['messages']->max('created_at')
            : \App\Models\Group::find($groupData['group_id'])->created_at
    ];
});


        // Combine individual and group chats and sort by last message time
        $allChats = $individualChatData->concat($groupChatData)->sortByDesc('last_message_time');

        // Pass the grouped messages to the view
        return view('chat.index',[
            'messages' => $allChats,
            'groups' => $groupMessages,
            'users' => $users,
            'profilePicture' => $profilePicture
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
            $message->message = $this->encryptMessage($validatedData['message']);
            $message->save();

            // Fetch the message just saved
            $savedMessage = Message::find($message->id);
            $savedMessage->message = $this->decryptMessage($savedMessage->message);

            // Convert the created_at timestamp to the 'Asia/Kathmandu' timezone
            $savedMessage->created_at = $savedMessage->created_at->timezone('Asia/Kathmandu');

            return response()->json(['message' => 'Message Sent','data' => $savedMessage ], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->validator->getMessageBag()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function sendGroupMessage(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'group_id' => 'required|integer',
                'message' => 'required|string'
            ]);

            // Save the group message
            $message = new GroupMessage();
            $message->user_id = auth()->id();
            $message->group_id = $validatedData['group_id'];
            $message->message = $this->encryptMessage($validatedData['message']);
            $message->save();

            // Fetch the saved message
            $savedMessage = GroupMessage::find($message->id);
            $savedMessage->message = $this->decryptMessage($savedMessage->message);

            // Convert the created_at timestamp to 'Asia/Kathmandu'
            $savedMessage->created_at = $savedMessage->created_at->timezone('Asia/Kathmandu');

            return response()->json(['message' => 'Message Sent', 'data' => $savedMessage], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => $e->validator->getMessageBag()], 500);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    protected function encryptMessage($message) {
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('app.encryption_key');
        //Generate a random IV(Initialization Vector)
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($encryptMethod));
        $encryptedMessage = openssl_encrypt($message, $encryptMethod, $secretKey, 0, $iv) . "::" . bin2hex($iv);
        return $encryptedMessage;
    }

    protected function decryptMessage($encryptedMessage) {
        $encryptMethod = "AES-256-CBC";
        $secretKey = config('app.encryption_key');

        if (strpos($encryptedMessage, "::") === false) {
            return $encryptedMessage; // Return as is if not properly encrypted
        }

        list($encryptedData, $iv) = explode("::", $encryptedMessage, 2);
        return openssl_decrypt($encryptedData, $encryptMethod, $secretKey, 0, hex2bin($iv));
    }
}
