<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessEmbedPosting implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @param array|string $webhooks
     * @param array $embedscontent
     * @param array|null $messagecontent
     */
    public function __construct(
        private array|string|int $webhooks,
        private array  $embedscontent,
        private mixed $model = null,
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
                $this->EmbedPoster((string) $webhook, true);
            }
        }else{
            $this->EmbedPoster((string) $this->webhooks, false);
        }
    }

    private function EmbedPoster(string $webhook, bool $multiple = false){
        if(is_numeric($webhook)){
            $this::PostApi((int) $webhook, $this->embedscontent, $this->messagecontent, $multiple ? null : $this->model);
        }else{
            $this::PostWebhook((string) $webhook, $this->embedscontent, $this->messagecontent);
        }
    }

    private static function PostApi(int $channel, array $embed = null, array $content = null, mixed $model){
        //Post message https://discord.com/api/v9/channels/{chan id}/messages => content comme les embeds
        //=> response id (id msg), channel_id
        $req = Http::withHeaders([
            'Authorization'=> 'Bot '.env('DISCORD_BOT_TOKEN')
        ])->post("https://discord.com/api/v9/channels/".$channel."/messages",
        [
            'embeds'=>$embed,
            'content'=>is_null($content) ? '' :  $content,
        ]
        );

        if(!is_null($model)){
            $req = $req->json();
            $model->discord_msg_id = $req['id'];
            $model->save();
        }

    }

    private static function PostWebhook(string $webhook, ?array $embed = null, ?array $content = null){
        Http::post($webhook,[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
            'embeds'=>$embed,
            'content'=>is_null($content) ? '' :  $content,
        ]);
    }

}
