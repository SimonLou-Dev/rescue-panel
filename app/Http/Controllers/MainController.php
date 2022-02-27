<?php

namespace App\Http\Controllers;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Models\Actualities;
use App\Models\Annonce;
use App\Models\Annonces;
use App\Models\Params;
use App\Models\ServiceState;
use App\Models\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

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

    public function getDashboard(Request $request): \Illuminate\Http\JsonResponse
    {
        $annonces = Annonces::orderByDesc('id')->get();
        foreach ($annonces as $annonce){
            $annonce->content = Markdown::convertToHtml($annonce->content);
        }
        return response()->json(['status'=>'OK', 'annonces'=>$annonces]);
    }

    public function createAnnonce(Request $request){
        $this->authorize('post_annonces', User::class);
        $annonce = new Annonces();
        $annonce->service = Session::get('service')[0];
        $annonce->content = $request->text;
        $annonce->save();
        $embed = [
            [
                'title'=>'Nouvelle annonce :',
                'color'=>'1285790',
                'fields'=>[
                    'name'=>'Description : ',
                    'value'=>self::prepareForDiscord($request->text),
                    'inline'=>false
                ],
                'footer'=>[
                    'text' => 'Rapport de : ' . Auth::user()->name . " ()",
                ]
            ]
        ];

        if(Session::get('service')[0] === 'LSCoFD'){
            \Discord::postMessage(DiscordChannel::FireAnnonce, $embed, $annonce);
        }else{
            \Discord::postMessage(DiscordChannel::MedicAnnonce, $embed, $annonce);
        }
        Notify::dispatch('Annonce postée', 1, Auth::user()->id);
        return response()->json();
    }

    public function createActu(Request $request){
        $this->authorize('post_actualities', User::class);
        $annonce = new Actualities();
        $annonce->service = Session::get('service')[0];
        $annonce->content = $request->text;
        $annonce->save();

        Notify::dispatch('Actu postée', 1, Auth::user()->id);
        return response()->json();

    }


    public function getUtilsInfos(): \Illuminate\Http\JsonResponse
    {
        $infos = Params::where('type', 'utilsInfos'.Session::get('service')[0])->first();
        if(!isset($infos->value)){
            $infos = '';
        }else{
            $infos = $infos->value;
        }

        return response()->json(['status'=>'OK', 'infos'=>$infos]);
    } //TODO : mettre dans getDashbaord

    public function updateUtilsInfos(Request $request){
        $this->authorize('edit_infos_utils', User::class);
        $text = $request->text;
        $infos = Params::where('type', 'utilsInfos'.Session::get('service')[0])->first();
        if(!isset($infos)){
            $infos = new Params();
            $infos->type = 'utilsInfos'.Session::get('service')[0];
            $infos->value=$text;
        }else{
            $infos->value = $text;
        }

        $infos->save();
        Notify::dispatch('Infos mises à jour', 1, Auth::user()->id);
        return $this->getUtilsInfos();

    }

    private function prepareForDiscord(string $text){
        $text = str_replace(['<strong>','</strong>'],'**',$text);
        $text = str_replace(['<em>','</em>'],'*',$text);
        $text = str_replace(['<u>','</u>'],'__',$text);
        $text = str_replace(['<h1>','</h1>','<ul>','</ul>','<li>','</li>','</ol>','<ol>'],'',$text);
        $text = str_replace(['<p>'],'',$text);
        $text = str_replace(['</p>','<br>'],'',$text);

        return $text;

    }


}
