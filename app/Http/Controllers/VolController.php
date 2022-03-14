<?php

namespace App\Http\Controllers;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Facade\DiscordFacade;
use App\Jobs\ProcessEmbedPosting;
use App\Models\LieuxSurvol;
use App\Models\User;
use App\Models\Vol;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class VolController extends Controller
{



    public function getVolsList(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewAny', Vol::class);
        if(Vol::all()->count() === 0){
            $array = [];
        }else{
            $vols = Vol::search($request->query('query'))->get()->reverse();

            $queryPage = (int) $request->query('page');
            $readedPage = ($queryPage ?? 1) ;

            $forgetable = [];

            for($a = 0; $a < $vols->count(); $a++){
                $searchedItem = $vols[$a];
                if(!\Gate::allows('view',$searchedItem)){
                    array_push($forgetable, $a);
                }
            }
            foreach ($forgetable as $forget){
                $vols->forget($forget);
            }


            $finalList = $vols->skip(($readedPage-1)*15)->take(15);
            foreach ($finalList as $item){
                $item->GetLieux;
                $item->GetUser;
            }

            $url = $request->url() . '?query='.urlencode($request->query('query')).'&page=';
            $totalItem = $vols->count();
            $valueRounded = ceil($totalItem / 5);
            $maxPage = (int) ($valueRounded == 0 ? 1 : $valueRounded);
            //Creation of Paginate Searchable result
            $array = [
                'current_page'=>$readedPage,
                'last_page'=>$maxPage,
                'data'=> $finalList,
                'next_page_url' => ($readedPage === $maxPage ? null : $url.($readedPage+1)),
                'prev_page_url' => ($readedPage === 1 ? null : $url.($readedPage-1)),
                'total' => $totalItem,
            ];
        }
        //End Pagination


          return response()->json([
              'status'=>'OK',
              'vols'=> $array,
              'places'=>  LieuxSurvol::all(),
          ]);

    }

    public function addVol(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('create',Vol::class);
        $request->validate([
           'lieux'=>['required', 'int','min:1'],
           'reason'=>['required', 'string']
        ]);

        $raison = $request->reason;
        $pilote_id = Auth::user()->id;
        $vol = new Vol();
        $vol->decollage = date_create();
        $vol->raison = $raison;
        $vol->service = $request->session()->get('service')[0];
        $vol->pilote_id = $pilote_id;
        $vol->lieux_id = $request->lieux;
        $vol->save();

        event(new Notify('Votre vol est pris en compte',1, Auth::user()->id));

        $embeds = [
            [
                'title'=>'hélicoptère déployé ' . $vol->service,
                'color'=>'15158332',
                'fields'=>[
                    [
                        'name'=>'Secteur : ',
                        'value'=>$vol->GetLieux->name,
                        'inline'=>true
                    ],[
                        'name'=>'Motif : ',
                        'value'=>$vol->raison,
                        'inline'=>true
                    ]
                ],
                'footer'=>[
                    'text' => 'Pilote : ' . Auth::user()->name
                ]
            ]
        ];
        \Discord::postMessage(DiscordChannel::Vols, $embeds);

        return response()->json(['status'=>'OK'],201);

    }



}
