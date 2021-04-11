<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\LieuxSurvol;
use App\Models\User;
use App\Models\Vol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class VolController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function getVolsList(int $page, string $pilote = null): \Illuminate\Http\JsonResponse
    {
        $page--;
        if($pilote == null || $pilote == 'null'){
            $vols = Vol::orderByDesc('id')->skip(20 * $page)->take(20)->get();
            $nbrVols = Vol::all()->count();
        }else{
            $pilote = User::where('pilote', true)->where('name', 'like', $pilote)->first();
            if($pilote == null){
                return response()->json([
                    'status'=>'ERROR',
                    'raison'=>'NO PILOTE'
                ]);
            }
            $vols = Vol::where('pilote_id', $pilote->id)->orderByDesc('id')->skip(20 * $page)->take(20)->get();
            $nbrVols = Vol::where('pilote_id', $pilote->id)->get()->count();
        }
        $a = 0;
        while ($a < count($vols)){
            $vols[$a]->GetLieux;
            $vols[$a]->GetUser;
            $a++;
        }

        $pages = intval(ceil(($nbrVols) / 20));
        $page++;
        $lieux = LieuxSurvol::all();

        return response()->json([
            'status'=>'OK',
            'datas'=>[
                'vols'=>$vols,
                'page'=>$page,
                'pages'=>$pages,
                'lieux'=>$lieux,
            ]
        ]);

    }

    public function addVol(Request $request): \Illuminate\Http\JsonResponse
    {

        $raison = $request->raison;
        $pilote_id = Auth::user()->id;
        $decollage = date_create();
        $vol = new Vol();
        $vol->decollage = $decollage;
        $vol->raison = $raison;
        $vol->pilote_id = $pilote_id;
        $vol->lieux_id = $request->lieux;
        $vol->save();

        event(new Notify('Votre vol est pris en compte',1));
        Http::post(env('WEBHOOK_VOLS'),[
            'embeds'=>[
                [
                    'title'=>'Helicoptère du BCFD déployé:',
                    'color'=>'13373531',
                    'fields'=>[
                        [
                            'name'=>'Secteur : ',
                            'value'=>$vol->GetLieux->name,
                            'inline'=>true
                        ],[
                            'name'=>'Motif : ',
                            'value'=>$vol->raison,
                            'inline'=>false
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Pilote : ' . Auth::user()->name,
                    ]
                ]
            ]
        ]);
        return response()->json(['status'=>'OK'],201);

    }

    public function seatchPilote(string $pilote): \Illuminate\Http\JsonResponse
    {
        $pilotes = User::where('name', 'LIKE', '%'.$pilote.'%')->take(5)->get();
        return response()->json(['status'=>'OK', 'datas'=>['users'=>$pilotes]]);
    }

}
