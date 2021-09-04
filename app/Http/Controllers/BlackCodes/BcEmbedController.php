<?php

namespace App\Http\Controllers\BlackCodes;

use App\Http\Controllers\Controller;
use App\Models\BCList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BcEmbedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function generateBCEndedEmbed(string $formated, object $patients, object $personnels,BCList $bc){
        $number = count($patients);
        $finalembedslist = array();
        array_push($finalembedslist,[
            'title'=>'Fin du Black Code #' . $bc->id .' :',
            'fields'=>[
                [
                    'name'=>'lieux',
                    'value'=>$bc->place,
                    'inline'=>false,
                ],[
                    'name'=>'patients',
                    'value'=>count($patients),
                    'inline'=>true,
                ],[
                    'name'=>'secouristes',
                    'value'=>count($personnels),
                    'inline'=>true,
                ],[
                    'name'=>'Liste des patients',
                    'value'=>"nom | carte d'identité | couleur de vêtement",
                    'inline'=>false,
                ],
            ],
            'color'=>'10368531',
        ]);
        if($number != 0){
            if($number > 20){
                $finalembedslist = BcEmbedController::manyPatientEmbed($number, $patients, $finalembedslist);
            }else{
                array_push($finalembedslist,BcEmbedController::onePatientEmbed($patients, 1,1,0)[1]);
            }
        }
        $a = 0;
        $msg = "";
        while ($a < count($personnels)){
            if($a == 0){
                $msg = 'Secouristes : ' . $personnels[$a]->name;
            }else{
                $msg = $msg . ', ' . $personnels[$a]->name;
            }
            $a++;
        }

        array_push($finalembedslist,[
            'title'=>'---------------',
            'color'=>'10368531',
            'description'=>$msg,
            'fields'=>[
                [
                    'name'=>'Lancé par :',
                    'value'=>$bc->GetUser->name,
                    'inline'=>true,
                ],[
                    'name'=>'cloturé par :',
                    'value'=>Auth::user()->name,
                    'inline'=>true,
                ],[
                    'name'=>'durée :',
                    'value'=>$formated,
                    'inline'=>true,
                ]
            ]
        ]);

        Http::post(env('WEBHOOK_PU'),[
            'username'=> "BCFD - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>$finalembedslist,
        ]);
    }

    private static function manyPatientEmbed(int $number, object $patients, array $finalembedslist): array
    {
        $nbr = $number-1;
        $pages = (int) ceil($number/20);
        $page = 1;
        $a = 0;
        while($a < $nbr){
            $embed = BcEmbedController::onePatientEmbed($patients, $page, $pages, $a);
            array_push($finalembedslist,$embed[1]);
            $a = $embed[0];
            $page++;
        }
        return $finalembedslist;
    }

    private static function onePatientEmbed(object $patients,int $page,int $pages, $a): array
    {
        $embedpatient = array();
        $b = 0;
        $msg = "";
        $max = 0;
        if(count($patients) - $a > 20){
            $max = 20;
        }else{
            $max = count($patients) - $a;
        }

        while($b < $max){
            $item = $b +$a;
            $msg = $msg . ' '. $patients[$item]->name . ' ' . ($patients[$item]->idcard ? ':white_check_mark:' : ':x:') . ' ' . $patients[$item]->GetColor->name . " \n";
            $b++;
        }
        $embedpatient = [
            'title'=>'Liste des patients '. $page .'/'.$pages,
            'color'=>'10368531',
            'description'=>$msg
        ];
        return [$a+31, $embedpatient];
    }
}
