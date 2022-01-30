<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use phpDocumentor\Reflection\Types\Integer;

class ProcessEmbedUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param mixed $webhook
     * @param array $embedscontent
     * @param mixed $model
     * @param array|null $messagecontent
     */
    public function __construct(
        private int $chanId,
        private int $msgId,
        private array  $embedscontent,
        private ?array $messagecontent = null
    ){
        $this->onQueue('discord');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $req = Http::withHeaders([
            'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
        ])->patch("https://discord.com/api/v9/channels/".$this->chanId."/messages/".$this->msgId,
            [
                'embeds'=>$this->embedscontent,
                'content'=>is_null($this->messagecontent) ? '' :  $this->messagecontent,
            ]
        );
    }
}
