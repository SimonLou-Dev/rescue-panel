<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class UserLogInListener
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
    public function handle(Login $event)
    {
        $user = $event->user;
        if(!\File::exists($this->connexionlog)){
            \File::put($this->connexionlog, '');
        }

        \File::append($this->connexionlog, '[' . date('d/m/Y H:i:s') . '] connected with id ' . $user->getAuthIdentifier() . ' at ' . $this->request->header('x-real-ip'). "\n");

    }
}
