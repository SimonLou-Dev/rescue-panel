<?php


namespace App\Facade;

use App\Enums\DiscordChannel;
use App\Http\Controllers\Discord\DiscordApiController;
use App\Jobs\ProcessEmbedPosting;
use App\Models\Params;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;
use Psr\SimpleCache\CacheInterface;


class DiscordInteractor
{


    public function __construct(
        public CacheInterface $cache,
    ){}

    public function chanUpdate(string $channel, string|int $path){
        $this->cache->set('webhook.'.$channel, $path);
        if(is_numeric($path))$path = (int) $path;
        else $path = (string) $path;

        if($this->verifyIfChanExist($channel)){
            $db = Params::where('type',$channel)->first();
            $db->value = $path;
            $db->save();
        }else{
            $db = new Params();
            $db->type = $channel;
            $db->value = $path;
            $db->save();
        }
        $embed= [
            [
                'title'=>'Mise à jour des channel',
                'color'=>'752251',
                'fields'=>[
                    [
                        'name'=>'Choisi pour : ',
                        'value'=> $channel,
                        'inline'=>false
                    ]
                ],
            ]
        ];
        self::postMessage($channel,$embed);
    }

    public function chanGet(string $channel):string|Integer
    {
        if($this->verifyIfChanExist($channel)){
            $cahedValue = $this->cache->get('webhook.'.$channel);

            if(is_int($cahedValue)) return (integer) $cahedValue;
            else return(string) $cahedValue;
        }
        return '';
    }

    public function getChannelInfos(string $channel){

    }

    public function postMessage(string $channel, array $embed = null, mixed $model =null, ?string $msg = null){
        $channel = $this->chanGet($channel);
        DiscordApiController::CallPostJobs($channel,$embed,$model,$msg);
    }

    public function getReaction(string $channel, Integer $msgid){

    }

    public function getReactors(string $channel, Integer $msgid){

    }

    public function updateMessage(string $channel, int $msgid, array $embed = null, ?array $msg = null){
        $channel = (int) $this->chanGet($channel);
        DiscordApiController::CallUpdateJobs($channel, $msgid,$embed,$msg);
    }

    public function deleteMessage(string $channel, int $msgid, mixed $model = null){
        $channel = (int) $this->chanGet($channel);
        if(!is_null($model)){
            $model->discord_msg_id = null;
            $model->save();
        }
        DiscordApiController::CallDeleteJobs($channel, $msgid);
    }

    public function reactOnMessage(string $channel, string $react, Integer $msgid){

    }

    private function verifyIfChanExist(string $channel):bool
    {
        $cache = $this->cache->has('webhook.' . $channel);
        $db = Params::where('type', $channel)->count() === 1;
        if($db && !$cache){
            $webhook = Params::where('type', $channel)->first();
            $this->cache->set('webhook.' . $channel, $webhook->value);
            return true;
        }
        if ($db && $cache) return true;

        return false;
    }

}

