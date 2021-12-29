<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessEmbedPosting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param array|string $webhooks
     * @param array $embedscontent
     * @param array|null $messagecontent
     */
    public function __construct(
        private array|string  $webhooks,
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
        if(is_array($this->webhooks)){
            foreach ($this->webhooks as $webhook){
                $this->EmbedPoster($webhook);
            }
        }else{
            $this->EmbedPoster($this->webhooks);
        }
    }

    private function EmbedPoster(string $webhook){
        Http::post($webhook,[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
            'embeds'=>$this->embedscontent,
            'content'=>is_null($this->messagecontent) ? '' :  $this->messagecontent,
        ]);
    }

}
