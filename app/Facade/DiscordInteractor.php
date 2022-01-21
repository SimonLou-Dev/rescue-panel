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

    public function chanUpdate(string $channel, string|Integer $path){
        $this->cache->set('webhook.'.$channel, $path);
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

    public function postMessage(string $channel, array $embed = null, mixed $model =null, ?array $msg = null){
        $channel = $this->chanGet($channel);
        DiscordApiController::CallJobs($channel,$embed,$model,$msg);
    }

    public function getReaction(string $channel, Integer $msgid){

    }

    public function getReactors(string $channel, Integer $msgid){

    }

    public function updateMessage(array|string|Integer $channels, Integer $msgid, Model $model, array $embed = null, ?array $msg = null){

    }

    public function deleteMessage(string $channel, Integer $msgid, Model $model){

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

