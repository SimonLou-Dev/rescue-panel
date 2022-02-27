<?php

namespace App\Http\Controllers;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Jobs\ProcessEmbedPosting;
use App\Models\ModifyServiceReq;
use App\Models\Prime;
use App\Models\PrimeItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class PrimesController extends Controller
{
    public static function AddValidPrimesToUser(int $userId, int $primeId){
        $prime = new Prime();
        $prime->week_number = ServiceGetterController::getWeekNumber();
        $prime->user_id = 0;
        $prime->item_id = $primeId;
        $prime->accepted = true;
        $user = User::where('id',$userId)->first();
        $prime->service = ($user->medic ? 'SAMS' : 'LSCoFD');
        $prime->save();
        $embed = [
            [
                'title'=>'Prime validée :',
                'color'=>'65361',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name  . ' (' . Session::get('service')[0] . ')',
                        'inline'=>false
                    ],[
                        'name'=>'Montant : ',
                        'value'=>'$' .  $prime->montant,
                        'inline'=>true
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$prime->reason,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Validée par : MDT'
                ]
            ]
        ];
        if(Session::get('service')[0]=== 'LSCoFD'){
            \Discord::postMessage(DiscordChannel::FireRemboursement, $embed, $prime);
        }else{
            \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed, $prime);
        }

        $logs = new LogsController();
        $logs->DemandesLogging('addind accepted Prime ', 'prime', $primeId, Auth::user()->id);
    }

    public function addReqPrimes(request $request){

        $this->authorize('create', Prime::class);

        $request->validate([
            'reason'=>['string'],
            'montant'=>['integer']
        ]);

        $prime = new Prime();
        $prime->week_number = ServiceGetterController::getWeekNumber();
        $prime->user_id = Auth::user()->id;
        $prime->reason = $request->reason;
        $prime->montant = $request->montant;
        $prime->service =  Session::get('service')[0];
        $prime->save();
        $embed = [
            [
                'title'=>'demande de prime :',
                'color'=>'1285790',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name  . ' (' . Session::get('service')[0] . ')',
                        'inline'=>false
                    ],[
                        'name'=>'Montant : ',
                        'value'=>'$' .  $prime->montant,
                        'inline'=>true
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$prime->reason,
                        'inline'=>true
                    ]
                ],
            ]
        ];

        if(Session::get('service')[0]=== 'LSCoFD'){
            \Discord::postMessage(DiscordChannel::FireRemboursement, $embed, $prime);
        }else{
            \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed, $prime);
        }
        $logs = new LogsController();
        $logs->DemandesLogging('creating prime req', 'prime', $prime->id, Auth::user()->id);
        Notify::broadcast('Demande ajoutée',1, Auth::user()->id);
        return response()->json([],201);
    }

    public function acceptReqPrimes(request $request, int $primesId){
        $this->authorize('update', Prime::class);

        $prime = Prime::where('id', $primesId)->first();
        $prime->accepted = true;
        $prime->admin_id = Auth::user()->id;
        $prime->save();

        Notify::broadcast('Prime acceptée',1, Auth::user()->id);

        $embed = [
            [
                'title'=>'Prime validée :',
                'color'=>'65361',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name  . ' (' . Session::get('service')[0] . ')',
                        'inline'=>false
                    ],[
                        'name'=>'Montant : ',
                        'value'=>'$' .  $prime->montant,
                        'inline'=>true
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$prime->reason,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Validée par : ' . Auth::user()->name,
                ]
            ]
        ];

        if($prime->discord_msg_id){
            if(Session::get('service')[0]=== 'LSCoFD'){
                \Discord::updateMessage(DiscordChannel::FireRemboursement, $prime->discord_msg_id, $embed);
            }else{
                \Discord::updateMessage(DiscordChannel::MedicRemboursement, $prime->discord_msg_id, $embed);
            }
        }else{
            if(Session::get('service')[0]=== 'LSCoFD'){
                \Discord::postMessage(DiscordChannel::FireRemboursement, $embed, $prime);
            }else{
                \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed, $prime);
            }
        }
        $logs = new LogsController();
        $logs->DemandesLogging('accepting prime of user n°'.$prime->user_id , 'prime', $prime->id, Auth::user()->id);


        return response()->json([],201);
    }

    public function refuseReqPrimes(request $request, int $primesId){
        $this->authorize('update', Prime::class);
        $prime = Prime::where('id', $primesId)->first();
        $prime->accepted = false;
        $prime->admin_id = Auth::user()->id;
        $prime->save();

        Notify::broadcast('Prime refusée',1, Auth::user()->id);

        $embed = [
            [
                'title'=>'Prime refusée :',
                'color'=>'16711684',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name  . ' (' . Session::get('service')[0] . ')',
                        'inline'=>false
                    ],[
                        'name'=>'Montant : ',
                        'value'=>'$' .  $prime->montant,
                        'inline'=>true
                    ],[
                        'name'=>'Raison : ',
                        'value'=>$prime->reason,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Refusée par : ' . Auth::user()->name,
                ]
            ]
        ];

        if($prime->discord_msg_id){
            if(Session::get('service')[0]=== 'LSCoFD'){
                \Discord::updateMessage(DiscordChannel::FireRemboursement, $prime->discord_msg_id, $embed);
            }else{
                \Discord::updateMessage(DiscordChannel::MedicRemboursement, $prime->discord_msg_id, $embed);
            }
        }else{
            if(Session::get('service')[0]=== 'LSCoFD'){
                \Discord::postMessage(DiscordChannel::FireRemboursement, $embed, $prime);
            }else{
                \Discord::postMessage(DiscordChannel::MedicRemboursement, $embed, $prime);
            }
        }

        $logs = new LogsController();
        $logs->DemandesLogging('refuse prime of user n°'.$prime->user_id , 'prime', $prime->id, Auth::user()->id);

        return response()->json([],201);
    }

    public function gelAllReqPrimes(){
        $this->authorize('viewAny', Prime::class);
        $primes = Prime::where('accepted', null)->where('service', Session::get('service')[0]);
        if($primes->count() < 10){
            $primes = Prime::where('service', Session::get('service')[0])->get()->take(15);
        }else{
            $primes = $primes->get();
        }

        foreach ($primes as $prime){
            $prime->getUser;
            if($prime->admin_id >= 0){

                if($prime->admin_id!== 0){
                    $prime->GetAdmin;
                }
            }
        }
        return response()->json(['primes'=>$primes]);
    }

    public function getMyReqPrimes(){
        $this->authorize('viewMy', Prime::class);
        $primes = Prime::where('user_id', Auth::user()->id)->where('service', Session::get('service')[0])->get();
        return response()->json([
            'primes'=>$primes,
        ]);

    }

}
