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
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class PuController extends Controller
{
    public function getInitialstate(): \Illuminate\Http\JsonResponse
    {
        $pu = BCList::where('ended', false);
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

    //re faire la méthode
    //event(new \App\Events\Notify('Information mises à jour ! ',1));
    public function setState(Request $request, $activate): \Illuminate\Http\JsonResponse
    {
        if($activate == "true"){
            $pu = new BCList();
            $pupersonnel = new BCPersonnel();
            $pu->starter_id = Auth::user()->id;
            $pu->type_id = (integer) $request->type;
            $pu->place = $request->place;
            $pu->started_at = date('Y-m-d H:i:s', time());
            $pu->starter_id = User::where('id', Auth::user()->id)->first();
            $pu->save();
            $pupersonnel->BC_id = $pu->id;
            $pupersonnel->name = Auth::user()->name;
            $pupersonnel->user_id = Auth::user()->id;
            $type = BCType::where('id', $request->type)->first();
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



            return response()->json(['status'=>'OK'],201);
        }
    }

    public function addPatient(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $pu = BCList::where('id', $id)->where('ended', true)->count();

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
        $rapport->patient_id = $patient_id;
        $rapport->interType = 1;
        $rapport->transport = 1;
        $desc = Blessure::where('id', $request->blessure)->first();
        $rapport->description = $desc->name;
        $rapport->price = 700;
        $rapport->save();
        $puPatient = new BCPatient();
        $puPatient->name = $Patient->vorname . ' ' . $Patient->name;
        $puPatient->BC_id = $id;
        $puPatient->rapport_id = $rapport->id;
        $wear = CouleurVetement::where('id', $request->vetement)->first();
        $puPatient->couleur = $wear->name;
        $puPatient->save();
        $facture = new Facture();
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

    public function deletePatient(Request $request,int $id): \Illuminate\Http\JsonResponse
    {
        if(Rapport::where('id', $id)->count() != 0){
            $rapport = Rapport::where('id', $id)->first();
            $facture = Facture::where('rapport_id', $id)->first();
            $pup = BCPatient::where('rapport_id', $id)->first();
            $rapport->delete();
            $facture->delete();
            $pup->delete();
            event(new \App\Events\Notify('Rapport supprimé',2));
        }else{
            event(new \App\Events\Notify('Impossiblie de trouver le rappot ',3));
        }

        return response()->json(['status'=>'OK']);
    }

    // a refaire
    private function PatientExist(string $name, string $vorname): ?Patient{
        $patient = Patient::where('name', 'LIKE', $name)->where('vorname','LIKE', $vorname);
        if($patient->count() == 1){
            return $patient->first();
        }
        return null;
    }

    // a refaire
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

    //a refaire
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
