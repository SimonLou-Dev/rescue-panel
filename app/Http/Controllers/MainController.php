<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Annonce;
use App\Models\Annonces;
use App\Models\Params;
use App\Models\ServiceState;
use App\Models\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{

    public function getInServices(Request $request): \Illuminate\Http\JsonResponse
    {
        $userInServie = User::where('service', true)->orderByDesc('grade_id')->get();
        $allStates = ServiceState::all();
        $userNumber = array();
        $userStates = array();

        foreach ($userInServie as $user){
            $user->getServiceState;
            if(!in_array($user->serviceState, $userNumber)){
                if($user->serviceState != null){
                    array_push($userNumber, $user->serviceState);
                    array_push($userStates, $user->getServiceState);
                }
            }

        }
        return response()->json(['status'=>'OK', 'users'=>$userInServie, 'states'=>$allStates, 'userStates'=>$userStates]);
    }

    public function getAnnonces(Request $request): \Illuminate\Http\JsonResponse
    {
        $annonces = Annonces::orderByDesc('id')->get();
        foreach ($annonces as $annonce){
            $annonce->content = Markdown::convertToHtml($annonce->content);
        }
        return response()->json(['status'=>'OK', 'annonces'=>$annonces]);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postBug(Request $request): \Illuminate\Http\JsonResponse
    {
        Http::post(env('WEBHOOK_BUGS'),[
            'embeds'=>[
                [
                    'title'=>'Nouveau BUG :',
                    'color'=>'1285790',
                    'description'=>$request->text,
                    'footer'=>[
                        'text' => 'Signalé par : ' . Auth::user()->name,
                    ]
                ]
            ]
        ]);
        return response()->json([],201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUtilsInfos(): \Illuminate\Http\JsonResponse
    {
        $infos = Params::where('type', 'utilsInfos')->first();
        if(!isset($infos->value)){
            $infos = '';
        }else{
            $infos = $infos->value;
        }

        return response()->json(['status'=>'OK', 'infos'=>$infos]);
    }

    public function updateUtilsInfos(Request $request){
        $text = $request->text;
        $infos = Params::where('type', 'utilsInfos')->first();
        if(!isset($infos)){
            $infos = new Params();
            $infos->type = 'utilsInfos';
            $infos->value=$text;
        }else{
            $infos->value = $text;
        }

        $infos->save();
        event(new Notify('Informations sauvegardées', 1));
        return response()->json(['status'=>"ok"],201);

    }


}
