<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessMessageDelete implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private int $chanId,
        private int $msgId,
    )
    {
        $this->onQueue('discord');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Http::withHeaders([
            'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
        ])->delete("https://discord.com/api/v9/channels/".$this->chanId."/messages/".$this->msgId);
    }
}
