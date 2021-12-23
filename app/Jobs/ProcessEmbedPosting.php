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
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private array  $webhooks,
        private array  $embedscontent,
        private ?array $messagecontent
    ){}

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


         foreach ($this->webhooks as $webhook){
            Http::post($webhook,[
                'username'=> "LSCoFD - MDT",
                'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
                'embeds'=>$this->embedscontent,
                'content'=>is_null($this->messagecontent) ? '' :  $this->messagecontent,
            ]);
        }


    }
}
