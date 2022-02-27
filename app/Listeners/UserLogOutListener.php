<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LogDb;

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

        $header = $this->request->header('x-real-ip');
        $ip = $header ?? $this->request->getClientIp();

        $logs = new LogDb();
        $logs->user_id = $user->getAuthIdentifier();
        $logs->action = 'authentifications';
        $logs->desc = 'disconnected at ' . $ip;
        $logs->save();

        \File::append($this->connexionlog, '[' . date('d/m/Y H:i:s') . '] disconnected with id ' . $user->getAuthIdentifier() . ' at ' . $ip . "\n");
    }
}
