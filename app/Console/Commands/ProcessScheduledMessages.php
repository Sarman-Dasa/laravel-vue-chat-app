<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduledMessagesJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scheduled-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Scheduled messages command is being executed.');
        SendScheduledMessagesJob::dispatch();
        $this->info('Scheduled messages processing dispatched.');
    }
}
