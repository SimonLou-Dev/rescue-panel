<?php

namespace App\Http\Controllers;


use App\Models\BCList;
use App\Models\BCPatient;
use App\Models\BCPersonnel;
use App\Models\BCType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class BCController extends Controller
{

    public function getUserInfos(): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        return response()->json([
            'status'=>'OK',
            'bc'=>$user->bc_id
        ]);
    }

    public function getMainPage(): \Illuminate\Http\JsonResponse
    {
        $ActiveBc = BCList::where('ended', false)->get();
        $a = 0;
        while ($a < count($ActiveBc)){
            $ActiveBc[$a]->GetUser;
            $ActiveBc[$a]->GetType;
            $ActiveBc[$a]->GetPatients;
            $ActiveBc[$a]->GetPersonnel;
            $ActiveBc[$a]->patients = count($ActiveBc[$a]->GetPatients);
            $ActiveBc[$a]->secouristes = count($ActiveBc[$a]->GetPersonnel);
            $a++;
        }
        $EndedBC = BCList::where('ended', true)->get();
        $a = 0;
        while ($a < count($EndedBC)){
            $EndedBC[$a]->GetUser;
            $EndedBC[$a]->GetType;
            $EndedBC[$a]->GetPatients;
            $EndedBC[$a]->GetPersonnel;
            $EndedBC[$a]->patients = count($EndedBC[$a]->GetPatients);
            $EndedBC[$a]->secouristes = count($EndedBC[$a]->GetPersonnel);
            $a++;
        }
        $puTypes = BCType::all();
        return response()->json([
            "status"=>'OK',
            'active'=>$ActiveBc,
            'ended'=>$EndedBC,
            'types'=>$puTypes,
        ]);
    }

    public function getBCState(int $id){
        // a faire
    }

    public function getBCByid(int $id){
        // a faire
    }

    public function addBc(Request $request): \Illuminate\Http\JsonResponse
    {
        $type = $request->type;
        $place = $request->place;
        $bc = new BCList();
        $bc->starter_id = Auth::user()->id;
        $bc->place = $place;
        $bc->type_id = $type;
        $bc->save();
        $this->addPersonel($request, $bc->id);

        Http::post(env('WEBHOOK_PU'),[
            'embeds'=>[
                [
                    'title'=>'Black Code #' . $bc->id . ' en cours :',
                    'fields'=>[
                      [
                          'name'=>'type :',
                          'value'=>$bc->GetType->name,
                          'inline'=>true,
                      ],
                      [
                          'name'=>'lieux :',
                          'value'=>$request->place,
                          'inline'=>true,
                      ]
                    ],
                    'color'=>'10368531',
                    'footer'=> [
                        'text' => 'Information de : ' . Auth::user()->name
                    ]
                ]
            ]
        ]);

        return response()->json([
            'status'=>'OK',
            'bc_id'=>$bc->id,
        ],201);
    }

    public function endBc(int $id=9){
        $bc = BCList::where('id', $id)->firstOrFail();
        $bc->ended = true;
        $users = User::where('bc_id', $id)->get();
        foreach ($users as $user){
            $user->bc_id = null;
            $user->save();
        }

        $patients = $bc->GetPatients;
        $personnels = $bc->GetPersonnel;
        $start = date_create($bc->created_at);
        $end = new \DateTime();
        $interval = $start->diff($end);
        $formated = $interval->format('%H h %I min(s)');

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
                    'value'=>"nom | carte d'intetité | couleur de vètement",
                    'inline'=>false,
                ],
            ],
            'color'=>'10368531',
        ]);
        $a = 0;
        if($number > 31){
            $nbr = $number-1;
            $pages = ceil($number/30);
            $page = 1;
            while($a < $nbr){
                $b = 0;
                $msg = "";
                while($b < 31){
                    $item = $b +$a;
                    $msg = $msg . ' '. $patients[$item]->name . ' ' . ($patients[$item]->idcard ? ':white_check_mark:' : ':x:') . ' ' . $patients[$item]->GetColor . " \n";
                    $b++;
                }
                $embedpatient = [
                    'title'=>'Liste des patients '. $page .'/'.$pages,
                    'color'=>'10368531',
                    'description'=>$msg
                ];
                $page++;
                array_push($finalembedslist, $embedpatient);
                $a = $a+30;
            }
        }else{
            $msg = "";
            while ($a < $number){
                $msg = $msg . ' '. $patients[$a]->name . ' ' . ($patients[$a]->idcard ? ':white_check_mark:' : ':x:') . ' ' . $patients[$a]->GetColor . " \n";
                $a++;
            }
            array_push($finalembedslist,[
                'title'=>'Liste des patients 1/1',
                'color'=>'10368531',
                'description'=>$msg
            ]);
        }
        $a = 0;
        $msg = "";
        while ($a < count($personnels)){
            $msg = $msg . ', ' . $personnels[$a]->name;
            $a++;
        }

        array_push($finalembedslist,[
            'title'=>'Liste des patients 1/1',
            'color'=>'10368531',
            'description'=>$msg,
            'fields'=>[
                [
                    'name'=>'',
                    'value'=>'',
                    'inline'=>true,
                ],[
                    'name'=>'Lancé par :',
                    'value'=>$bc->GetUser,
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

        $req = Http::post(env('WEBHOOK_PU'),[
            'embeds'=>$finalembedslist,
        ]);

        dd($req);

    }

    public function addPersonel(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $bc = BCList::where('id', $id)->firstOrFail();
        $personnel = new BCPersonnel();
        $personnel->user_id = Auth::user()->id;
        $personnel->name = Auth::user()->name;
        $personnel->BC_id = $bc->id;
        $personnel->save();
        $user = User::where('id', Auth::user()->id)->first();
        $user->bc_id = $bc->id;
        $user->save();
        return response()->json(['status'=>'OK'],201);
    }

    public function removePersonnel(int $id){
        // a faire
    }

    public function addPatient(Request $request, int $id){
        // a faire
    }

    public function removePatient(int $id, int $patient_id){
        // a faire
    }

}
