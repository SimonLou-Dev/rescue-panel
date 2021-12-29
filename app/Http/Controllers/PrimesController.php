<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Http\Controllers\Service\ServiceGetterController;
use App\Models\ModifyServiceReq;
use App\Models\Prime;
use App\Models\PrimeItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class PrimesController extends Controller
{
    public static function AddValidPrimesToUser(int $userId, int $primeId){
        $prime = new Prime();
        $prime->week_number = ServiceGetterController::getWeekNumber();
        $prime->user_id = $userId;
        $prime->item_id = $primeId;
        $prime->accepted = true;
        $prime->save();
        Http::post(env('WEBHOOK_MONEY'),[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
            'embeds'=>[
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
            ]
        ]);
    }

    public function addReqPrimes(request $request){

        $prime = new Prime();
        $prime->week_number = ServiceGetterController::getWeekNumber();
        $prime->user_id = Auth::user()->id;
        $prime->item_id = $request->primeid;
        $prime->save();
        event(new Notify('Demande ajoutée',1));
        return response()->json([],201);
    }

    public function acceptReqPrimes(request $request, int $primesId){
        $prime = Prime::where('id', $primesId)->first();
        $prime->accepted = true;
        $prime->save();

        event(new Notify('Prime acceptée',1));

        Http::post(env('WEBHOOK_MONEY'),[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
            'embeds'=>[
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
            ]
        ]);

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
        $primes = Prime::where('accepted', null)->orderBy('id','desc')->take(100)->get();
        if(count($primes) < 20){
            $primes = Prime::take(100)->orderBy('id','desc')->get();
        }
        foreach ($primes as $prime){
            $prime->getItem;
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
