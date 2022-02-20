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
        $prime->user_id = $userId;
        $prime->item_id = $primeId;
        $prime->accepted = true;
        $user = User::where('id',$userId)->first();
        $prime->service = ($user->medic ? 'SAMS' : 'LSCoFD');
        $prime->save();
/*
        $embed = [
            [
                'title'=>'Prime Validée :',
                'color'=>'1285790',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Prime : ',
                        'value'=>'$' .  $prime->getItem->montant . ' ' . $prime->getItem->name,
                        'inline'=>false
                    ]
                ],
                'footer'=>[
                    'text' => 'Validée par : MDT',
                ]
            ]
        ];
        dispatch(new ProcessEmbedPosting([env('WEBHOOK_MONEY')], $embed, null));*/
    }

    public function addReqPrimes(request $request){

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
                'title'=>'Demande de Prime :',
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
        Notify::broadcast('Demande ajoutée',1, Auth::user()->id);
        return response()->json([],201);
    }

    public function acceptReqPrimes(request $request, int $primesId){
        $prime = Prime::where('id', $primesId)->first();
        $prime->accepted = true;
        $prime->save();

        event(new Notify('Prime acceptée',1));

        $embed = [
            [
                'title'=>'Prime Validée :',
                'color'=>'1285790',
                'fields'=>[
                    [
                        'name'=>'Personnel : ',
                        'value'=>$prime->getUser->name,
                        'inline'=>false
                    ],[
                        'name'=>'Prime : ',
                        'value'=>'$' .  $prime->getItem->montant . ' ' . $prime->getItem->name,
                        'inline'=>false
                    ]
                ],
                'footer'=>[
                    'text' => 'Validée par : ' . Auth::user()->name,
                ]
            ]
        ];

        $this->dispatch(new ProcessEmbedPosting([env('WEBHOOK_MONEY')],$embed, null));


        return response()->json([],201);
    }

    public function refuseReqPrimes(request $request, int $primesId){
        $prime = Prime::where('id', $primesId)->first();
        $prime->accepted = false;
        $prime->save();

        event(new Notify('Prime refusée',1));

        return response()->json([],201);
    }

    public function gelAllReqPrimes(){
        $primes = Prime::where('service', Session::get('service')[0])->where('accepted', null)->orderBy('id','desc')->take(100)->get();
        if(count($primes) < 20){
            $primes = Prime::where('service', Session::get('service')[0])->take(100)->orderBy('id','desc')->get();
        }
        foreach ($primes as $prime){
            $prime->getUser;
        }
        return response()->json(['primes'=>$primes]);
    }

    public function getMyReqPrimes(){
        $primes = Prime::where('user_id', Auth::user()->id)->get();
        foreach ($primes as $prime){
            $prime->getItem;
        }
        $list = PrimeItem::where('id','!=','1')->get();
        return response()->json([
            'primes'=>$primes,
            'list'=>$list,
        ]);

    }

}
