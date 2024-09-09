<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Traits\ManageFiles;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Events\MessageUpdated;
use App\Models\ChatAttachment;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Messaging\CloudMessage;

class ChatController extends Controller
{

    use ManageFiles;

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
        ->with(['sender', 'receiver','attachments'])
        ->orderBy('created_at', 'asc')->get();

        return $messages;
    }

    public function sendMessage(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
            'files.*' => 'nullable|mimes:jpg,jpeg,png',
        ]);

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->user_id,
            'message' => $request->message,
            'is_sent' => true,
        ]);

        $newAttachment = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $newAttachment[] = [
                    'file_path' => $this->uploadFile($file, 'uploads/chat_attachments'),
                    'file_name' => $file->getClientOriginalName(),
                ];
            }
        }

        if ($request->hasFile('audio')) {
            $file = $request->file('audio');
            $fileName = pathinfo($file ->getClientOriginalName(), PATHINFO_FILENAME);
            $uniqueFileName = $fileName . Str::random(3) . '.mp3';
            $newAttachment[] = [
                'file_path' => $this->uploadFile($file, 'uploads/chat_audio',true),
                'file_name' => $uniqueFileName,
                'is_audio_file' => true
            ];
        }

        $message->attachments()->createMany($newAttachment);


        broadcast(new MessageSent($message,'sent'))->toOthers();

        $token = $message->receiver->device_token;
        $this->sendPushNotification('New message in laravel-vue-app',$request->message,$token);
        return response()->json([
            'message' => $message->load('attachments')
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

    public function uploadAttachment(Request $request) {

        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,txt|max:2048',
        ]);
    }

    public function deleteFile($message_id,$file_id) {

        $chatMessage = ChatMessage::findOrFail($message_id);

        $file = $chatMessage->attachments()->where('id',$file_id)->first();
        if($file) {
            $this->deleteMessageFile($file->file_path);
            $file->delete();
        }

        $count = $chatMessage->attachments()->count();
        if($count == 0) {
            $chatMessage->delete();
            broadcast(new MessageSent($chatMessage,'deleted'));
        }
        else {
            broadcast(new MessageSent($chatMessage,'updated'));
        }

        return response()->json([
            'message' => $chatMessage->load('attachments')
        ]);

    }

    private function sendPushNotification($title,$message,$device_token)
    {
        if(!empty($device_token)){

            $firebaseServiceAccountPath = env('FIREBASE_SERVICE_ACCOUNT_PATH');
            $firebase = (new Factory)    
                ->withServiceAccount(storage_path($firebaseServiceAccountPath));

            $messaging = $firebase->createMessaging();
            try {
                $message = CloudMessage::withTarget('TOKEN', $device_token)
                    ->withNotification([
                        'title' => $title,
                        'body' => $message,
                        'icon' => '/chat-icon.png'
                    ]);
                $messaging->send($message);
            } catch (Exception $e) {
                Log::error('FCM entity not found: ' . $e->getMessage());
            }
        }
    }
}
