<?php

namespace App\Jobs;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendScheduledMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $messages = ChatMessage::where('send_at', '<=', now())
        ->where('is_sent', false)
        ->get();

        foreach ($messages as $message) {
            broadcast(new MessageSent($message, 'sent',true))->toOthers();
            $message->update(['is_sent' => true]);
        }
    }
}
