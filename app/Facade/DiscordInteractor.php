<?php


namespace App\Facade;

use App\Enums\DiscordChannel;
use Illuminate\Database\Eloquent\Model;
use Psr\SimpleCache\CacheInterface;


class DiscordInteractor
{


    public function __construct(
        public CacheInterface $cache,
    ){}

    public function chanUpdate(DiscordChannel $channel, array $paths){


    }

    public function chanGet(DiscordChannel $channel){


    }

    public function getChannelInfos(DiscordChannel $channel){

    }

    public function postMessage(array|string|int $channels, Model $model, array $embed = null, ?array $msg = null){

    }

    public function getReaction(DiscordChannel $channel, int $msgid){

    }

    public function getReactors(DiscordChannel $channel, int $msgid){

    }

    public function updateMessage(array|string|int $channels, int $msgid, Model $model, array $embed = null, ?array $msg = null){

    }

    public function deleteMessage(DiscordChannel $channel, int $msgid, Model $model){

    }

    public function reactOnMessage(DiscordChannel $channel, string $react, int $msgid){

    }


}

