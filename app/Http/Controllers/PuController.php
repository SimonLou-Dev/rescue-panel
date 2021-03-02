<?php

namespace App\Http\Controllers;

use App\Models\BlessuresTypes;
use App\Models\Factures;
use App\Models\Patient;
use App\Models\PatientsVetement;
use App\Models\PlanUrgence;
use App\Models\PlanUrgencePatient;
use App\Models\PlanUrgencePersonnel;
use App\Models\PUTypes;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class PuController extends Controller
{
    public function getInitialstate(): \Illuminate\Http\JsonResponse
    {
        $pu = PlanUrgence::where('ended', false);
        if($pu->count() == 1){
            return response()->json([
                'status'=>'OK',
                'state'=>true,
            ]);

        }else{
            return response()->json([
                'status'=>'OK',
                'state'=>false,
            ]);
        }

    }

    public function getInfos(Request $request, $activate): \Illuminate\Http\JsonResponse
    {
        if($activate == "true"){
            $pu = PlanUrgence::where('ended', false)->first();
            if(PlanUrgence::where('ended', false)->count()==0){
                return response()->json(['status'=>'pas ok']);
            }
            $starter = User::where('id', $pu->starter_id)->first();
            $patient = $pu->PUPatient;
            $personnel = $pu->PUPersonnel;
            $name = $pu->gettype->name;
            $vetements =PatientsVetement::all();
            $blessures = BlessuresTypes::all();
            return response()->json([
                'status'=>'OK',
                'plan'=>$pu,
                'patients'=>$patient,
                'personnels'=>$personnel,
                'name'=>$name,
                'startname'=>$starter->name,
                'blessures'=>$blessures,
                'vetements'=>$vetements,
            ]);

        }else{
            $lastpu = PlanUrgence::all()->sortByDesc('id')->first();
            if(PlanUrgence::all()->count() == 0){
                $patient = null;
                $personnel = null;
                $lastpu = null;

            }else{
                if(property_exists($lastpu, 'PUPatient')){
                    $patient = $lastpu->PUPatient;
                }else{
                    $patient = null;
                }


                $personnel = $lastpu->PUPersonnel;

            }

            $type = PUTypes::all();

            return response()->json([
                'status'=>'OK',
                'lastplan'=>$lastpu,
                'lastpatients'=>$patient,
                'lastpersonnels'=>$personnel,
                'types'=>$type,
            ]);
        }
    }

    public function setState(Request $request, $activate): \Illuminate\Http\JsonResponse
    {
        if($activate == "true"){
            $counter = PlanUrgence::where('ended', false)->count();
            if($counter != 0){
                return response()->json(['status'=>'pas OK']);
            }
            $pu = new PlanUrgence();
            $pupersonnel = new PlanUrgencePersonnel();
            $pu->starter_id = Auth::user()->id;
            $pu->type = (integer) $request->type;
            $pu->place = $request->place;
            $pu->Started_at = date('Y-m-d H:i:s', time());
            $pu->save();
            $pupersonnel->PU_ID = $pu->id;
            $pupersonnel->name = Auth::user()->name;
            $pupersonnel->userID = Auth::user()->id;
            $type = PUTypes::where('id', $request->type)->first();
            $pupersonnel->save();
            Http::post(env('WEBHOOK_PU'),[
                'embeds'=>[
                    [
                        'title'=>'Black Code ' . $type->name . ' en cours :',
                        'color'=>'10368531',
                        'description'=> 'Lieux : ' . $request->place,
                        'footer'=> [
                            'text' => 'Information de : ' . Auth::user()->name
                        ]
                    ]
                ]
            ]);


            return response()->json(['status'=>'OK'],201);
        }else{
            $pu = PlanUrgence::where('ended', false)->first();
            $pu->ended = true;
            $pu->save();
            $patientcounter = PlanUrgencePatient::where('PU_ID', $pu->id)->count();
            $medic = PlanUrgencePersonnel::where('PU_ID', $pu->id)->get();
            $patient = PlanUrgencePatient::where('PU_ID', $pu->id)->get();
            $b = 0;
            if($patientcounter != 0){
                $names = "";
                while($b < $patientcounter){
                    if($b == 0){
                        $names = $patient[$b]->patient_name . ' ['.$patient[$b]->vetements.']';
                    }else{
                        $names= $names . ', ' . $patient[$b]->patient_name. ' ['.$patient[$b]->vetements.']';
                    }
                    $b++;
                }
            }else{
                $names = 'vide';
            }

            $a = 0;
            $size = count($medic);
            $str = "";
            while($a < $size){
                if($a == 0){
                    $str = $medic[$a]['name'];
                }else{
                    $str = $str . ', ' . $medic[$a]['name'];
                }
                $a++;
            }

            $start = date_create($pu->created_at);
            $end = new \DateTime();
            $interval = $start->diff($end);
            $formated = $interval->format('%H h %I min(s)');

            Http::post(env('WEBHOOK_PU'),[
                'embeds'=>[
                    [
                        'title'=>'Fin de Black Code :',
                        'color'=>'10368531',
                        'fields'=>[
                            [
                                'name'=>$patientcounter .' personne(s) secourue(s) : ',
                                'value'=>$names,
                                'inline'=>false
                            ],[
                                'name'=>'Personnel engagé : ',
                                'value'=>$str,
                                'inline'=>false
                            ],[
                                'name'=>'durée : ',
                                'value'=>$formated,
                                'inline'=>true
                            ]
                        ]
                    ]
                ]
            ]);

            return response()->json(['status'=>'OK'],201);
        }
    }

    public function addPatient(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $pu = PlanUrgence::where('id', $id)->where('ended', true)->count();

        $Patient = $this->PatientExist($request->nom, $request->prenom);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $request->nom;
            $Patient->vorname = $request->prenom;
            $Patient->tel = 0;
            $Patient->save();

            $Patient = Patient::where('name', $request->nom)->where('vorname', $request->prenom)->first();
        }

        $patient_id = $Patient->id;
        $rapport = new Rapport();
        $rapport->patientID = $patient_id;
        $rapport->InterType = 1;
        $rapport->transport = 1;
        $desc = BlessuresTypes::where('id', $request->blessure)->first();
        $rapport->description = $desc->name;
        $rapport->prix = 700;
        $rapport->save();
        $puPatient = new PlanUrgencePatient();
        $puPatient->patient_name = $Patient->vorname . ' ' . $Patient->name;
        $puPatient->PU_ID = $id;
        $puPatient->rapport_id = $rapport->id;
        $wear = PatientsVetement::where('id', $request->vetement)->first();
        $puPatient->vetements = $wear->name;
        $puPatient->save();
        $facture = new Factures();
        $facture->patient_id = $patient_id;
        $facture->rapport_id = $rapport->id;
        $facture->payed = $request->payed;
        $facture->price = 700;
        $facture->save();
        if($pu == 0){
            return response()->json(['status'=>'plus de PU']);
        }
        return response()->json(['status'=>"OK"],201);
    }

    public function deletePatient(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if(Rapport::where('id', $id)->count() != 0){
            $rapport = Rapport::where('id', $id)->first();
            $facture = Factures::where('rapport_id', $id)->first();
            $pup = PlanUrgencePatient::where('rapport_id', $id)->first();
            $rapport->delete();
            $facture->delete();
            $pup->delete();
        }
        return response()->json(['status'=>'OK']);
    }

    private function PatientExist(string $name, string $vorname): ?Patient{
        $patient = Patient::where('name', 'LIKE', $name)->where('vorname','LIKE', $vorname);
        if($patient->count() == 1){
            return $patient->first();
        }
        return null;
    }

    public function isParticiping(): \Illuminate\Http\JsonResponse
    {
        if(PlanUrgence::where('ended',false)->count() ==0){
            $puperso = 0;
        }else{
            $pu = PlanUrgence::where('ended', false)->first();
            $puperso = PlanUrgencePersonnel::where('userId', Auth::id())->where('PU_ID', $pu->id)->count();
        }
        return response()->json(['status'=>'OK', 'p'=>$puperso]);
    }

    public function addParticipant(): \Illuminate\Http\JsonResponse
    {
        $pu = PlanUrgence::where('ended', false)->first();
        $PUP = PlanUrgencePersonnel::where('userID', Auth::id())->where('PU_ID',$pu->id)->count();
        if($PUP == 0){
            $perso = new PlanUrgencePersonnel();
            $perso->PU_id = $pu->id;
            $perso->userID=Auth::id();
            $perso->name=Auth::user()->name;
            $perso->save();
        }
        return response()->json(['stauts'=>"OK"]);
    }
}
