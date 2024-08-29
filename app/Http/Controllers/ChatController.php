<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function viewChat(User $user) {
        return view('chat',[
            'user' => $user
        ]);
    }
    public function receiveMessages($id)
    {
        $user = User::findOrFail($id);
        $messages = ChatMessage::where(function ($query) use ($id) {
            $query->where('receiver_id', $id)->where('sender_id', auth()->id());
        })->orWhere(function ($query) use ($id) {
            $query->where('receiver_id', auth()->id())->where('sender_id', $id);
        })->get();

        return $messages;
    }

    public function sendMessage(Request $request)
    {
        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
        ]);

        // broadcast(new MessageSent($message))->toOthers();
        return response()->json([
            'message' => $message
        ]);
    }
}
