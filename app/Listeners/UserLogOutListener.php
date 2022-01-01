<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;

class UserLogOutListener
{
    private string $connexionlog;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        public Request $request,
    )
    {
        $this->connexionlog = base_path('storage/logs/connexion.log');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        if(!\File::exists($this->connexionlog)){
            \File::put($this->connexionlog, '');
        }

        \File::append($this->connexionlog, '[' . date('d/m/Y H:i:s') . '] disconnected with id ' . $user->getAuthIdentifier() . ' at ' . $this->request->header('x-real-ip') . "\n");
    }
}
