<?php

namespace App\Listeners;

use App\Models\LogDb;
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
        $header = $this->request->header('x-real-ip');
        $ip = $header ?? $this->request->getClientIp();

        $logs = new LogDb();
        $logs->user_id = $user->getAuthIdentifier();
        $logs->action = 'authentifications';
        $logs->desc = 'connected at ' . $ip;
        $logs->save();
        \File::append($this->connexionlog, '[' . date('d/m/Y H:i:s') . '] connected with id ' . $user->getAuthIdentifier() . ' at ' . $ip . "\n");

    }
}
