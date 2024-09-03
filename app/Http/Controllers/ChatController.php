<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\MessageUpdated;
use App\Models\ChatMessage;
use App\Models\User;
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
        $messages = ChatMessage::where('is_sent', true)->where(function ($query) use ($id) {
            $query->where('receiver_id', $id)->where('sender_id', auth()->id());
        })->orWhere(function ($query) use ($id) {
            $query->where('receiver_id', auth()->id())->where('sender_id', $id);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'asc')->get();

        return $messages;
    }

    public function sendMessage(Request $request)
    {


        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
            'is_sent' => true,
        ]);

        broadcast(new MessageSent($message,'sent'))->toOthers();
        return response()->json([
            'message' => $message
        ]);
    }

    public function updateMessage(Request $request, $id) {

        $message = ChatMessage::findOrFail($id);

        $request['is_edited'] = 1;
        $message->update($request->only('message','is_edited'));

        broadcast(new MessageSent($message,'updated'))->toOthers();

        return response()->json([
            'message' => $message
        ]);

    }

    public function deleteMessage ($id) {

        $message = ChatMessage::findOrFail($id);

        broadcast(new MessageSent($message,'deleted'));

        $message->delete();

        return response()->json([
            'message' => $message
        ]);

    }

    public function sendScheduleMessage(Request $request) {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'send_at' => 'required|date|after:now',
        ]);

        $scheduledMessage = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
            'send_at' => $request->send_at,
        ]);

        return response()->json([
            'message' => 'Message scheduled successfully.',
            'scheduled_message' => $scheduledMessage
        ]);
    }
}
