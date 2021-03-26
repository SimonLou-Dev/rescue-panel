<?php

namespace App\Http\Controllers;


use App\Models\BCList;
use App\Models\BCPatient;
use App\Models\BCPersonnel;
use App\Models\BCType;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\Facture;
use App\Models\Patient;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use function PHPUnit\Framework\isNull;

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
        $ActiveBc = BCList::where('ended', false)->orderByDesc('id')->get();
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
        $EndedBC = BCList::where('ended', true)->orderByDesc('id')->get();
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

    public function getBCState(string $id): \Illuminate\Http\JsonResponse
    {
        $id = (int) $id;
       $bc = BCList::where('id',$id)->first();
       return response()->json([
           'status'=>'OK',
           'ended'=>$bc->ended,
       ]);
    }

    public function getBCByid(string $id): \Illuminate\Http\JsonResponse
    {
        if($id == "undefined"){
            $id = User::where('id', Auth::user()->id)->first()->bc_id;
        }else{
            $id = (int) $id;
        }
        $bc = BCList::where('id', $id)->first();
        $bc->GetType;
        $bc->GetUser;
        $bc->GetPersonnel;
        $bc->GetPatients;
        $blessures = Blessure::all();
        $color= CouleurVetement::all();
        return response()->json([
            'stauts'=>'OK',
            'bc'=>$bc,
            'colors'=>$color,
            'blessures'=>$blessures,
        ]);
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
        $this->addPersonel($bc->id);

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

    public function endBc(int $id=9): \Illuminate\Http\JsonResponse
    {
        $bc = BCList::where('id', $id)->firstOrFail();
        $bc->ended = true;
        $bc->save();
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
        $this->generateBCEndedEmbed($formated, $patients, $personnels, $bc);


        return response()->json(['status'=>'OK'],201);
    }
    private function generateBCEndedEmbed(string $formated, object $patients, object $personnels,BCList $bc){
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
        if($number != 0){
            if($number > 31){
                $this->manyPatientEmbed($number, $patients);
            }else{
                array_push($finalembedslist,$this->onePatientEmbed($patients, 1,1,0)[1]);
            }
        }
        $a = 0;
        $msg = "";
        while ($a < count($personnels)){
            if($a == 0){
                $msg = $personnels[$a]->name;
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
            'embeds'=>$finalembedslist,
        ]);
    }
    private function manyPatientEmbed(int $number, object $patients){
        $nbr = $number-1;
        $pages = ceil($number/30);
        $page = 1;
        $a = 0;
        while($a < $nbr){
            $embed = $this->onePatientEmbed($patients, $page, $pages, $a);
            array_push($finalembedslist, $embed[1]);
            $page++;
            $a = $a+ $embed[0];
        }
    }
    private function onePatientEmbed(object $patients,int $page,int $pages, $a): array
    {
        $embedpatient = array();
        $b = 0;
        $msg = "";
        $max = 0;
        if(count($patients) > 31){
            $max = 31;
        }else{
            $max = count($patients);
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
        return [$a, $embedpatient];
    }

    public function addPersonel(string $id): \Illuminate\Http\JsonResponse
    {
        $id = (int) $id;
        $bc = BCList::where('id', $id)->firstOrFail();
        $personnel = BCPersonnel::where('BC_id', $id)->where('user_id', Auth::user()->id)->get()->count();
        if($personnel == 0){
            $personnel = new BCPersonnel();
            $personnel->user_id = Auth::user()->id;
            $personnel->name = Auth::user()->name;
            $personnel->BC_id = $bc->id;
            $personnel->save();
        }
        $user = User::where('id', Auth::user()->id)->first();
        $user->bc_id = $bc->id;
        $user->save();
        event(new \App\Events\Notify('Vous avez été affecté à ce BC ! ',2));
        return response()->json(['status'=>'OK'],201);
    }

    public function removePersonnel(int $id): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', Auth::user()->id)->first();
        $user->bc_id = null;
        $user->save();
        event(new \App\Events\Notify('Vous avez été désaffecté de ce BC ! ',2));
        return response()->json(['status'=>'OK'],202);
    }

    public function addPatient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $name = explode(" ", $request->name);
        $bc = BCList::where('id', $id)->first();
        $Patient = RapportController::PatientExist($name[1], $name[0]);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $name[1];
            $Patient->vorname = $name[0];
            $Patient->tel = 0;
            $Patient->save();
        }
        /*
         * name: this.state.name,
                    vorname: this.state.vorname,
                    color: this.state.color,
                    blessure: this.state.blessure,
                    payed: this.state.payed,
                    carteid: this.state.carteid,
         */
        $rapport = new Rapport();
        $rapport->patient_id = $Patient->id;
        $rapport->interType = 1;
        $rapport->transport = 1;
        $rapport->user_id = Auth::user()->id;
        $desc = Blessure::where('id', $request->blessure)->first();
        $rapport->description = $desc->name;
        $rapport->price = 700;
        $rapport->save();
        $BcP = new BCPatient();
        $BcP->idcard = (bool) $request->carteid;
        $BcP->patient_id = $Patient->id;
        $BcP->rapport_id = $rapport->id;
        $BcP->blessure_type = $request->blessure;
        $BcP->couleur = $request->color;
        $BcP->BC_id = $bc->id;
        $BcP->name = $Patient->vorname . ' ' .$Patient->name;
        $BcP->save();
        RapportController::addFactureMethod($Patient, $request->payed, 700, Auth::user()->id,$rapport->id);
        event(new \App\Events\Notify('Patient ajouté ! ',2));
        return response()->json(['status'=>'OK'],201);
    }

    public function removePatient(int $patient_id): \Illuminate\Http\JsonResponse
    {
        $bcp = BCPatient::where('id', $$patient_id)->first;
        if (!isNull($bcp->rapport_id)){
            $rapport = Rapport::where('id', $bcp->rapport_id)->first();
            $facture = Facture::where('id', $rapport->GetFacture->id)->first();
            $facture->delete();
            $rapport->delete();
        }
        $bcp->delete();
        event(new \App\Events\Notify('Patient retiré ! ',2));
        return response()->json(['status'=>'OK']);
    }

}
