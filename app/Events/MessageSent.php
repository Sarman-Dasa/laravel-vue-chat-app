<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $type;  // 'sent' or 'updated'
    public $isSheduleMsg;
    /**
     * Create a new event instance.
     */
    public function __construct(public ChatMessage $message, string $type, $isSheduleMsg = false)
    {
        $this->type = $type;
        $this->isSheduleMsg = $isSheduleMsg;
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel("chat.{$this->message->receiver_id}")
        ];
    
        if ($this->isSheduleMsg) {
            $channels[] = new PrivateChannel("chat.{$this->message->sender_id}");
        }
    
        return $channels;
    }
    

     /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message->toArray(),
            'type' => $this->type,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        // If you customize the broadcast name using the broadcastAs method, you should make sure to register your listener with a leading . character
        return 'MessageEvent';
    }
}
