<?php

namespace App\Http\Controllers\BlackCodes;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rapports\FacturesController;
use App\Models\BCList;
use App\Models\BCPatient;
use App\Models\Blessure;
use App\Models\CouleurVetement;
use App\Models\Facture;
use App\Models\Patient;
use App\Models\Rapport;
use App\Exporter\ExelPrepareExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class BlesseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function addPatient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'carteid'=>['required'],
            'blessure'=>['required'],
            'color'=>['required'],
            'payed'=>['required']
        ]);


        $name = explode(" ", $request->name);
        $bc = BCList::where('id', $id)->first();
        $Patient = \App\Http\Controllers\Rapports\PatientController::PatientExist($name[1], $name[0]);
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
        $color = CouleurVetement::where('id', $request->color)->first();
        FacturesController::addFactureMethod($Patient, $request->payed, 700, Auth::user()->id,$rapport->id);
        event(new Notify('Patient ajouté ! ',1));

        if(!$request->correctid){
            event(new Notify('Ajout d\'une déclaration de falsification d\'identité ! ',1));
            Http::post(env('WEBHOOK_STAFF'),[
                'username'=> "LSCoFD- MDT",
                'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
                'embeds'=>[
                    [
                        'title'=>'Falsification d\'identité',
                        'color'=>'10368531',
                        'fields'=>[
                            [
                                'name'=>'nom donné au médecin :',
                                'value'=>$Patient->name,
                                'inline'=>true,
                            ],[
                                'name'=>'présentation d\'une id card :',
                                'value'=>($BcP->idcard ? 'oui' : 'non'),
                                'inline'=>true,
                            ],[
                                'name'=>'nom réel du personnage :',
                                'value'=>$request->realname,
                                'inline'=>false,
                            ],[
                                'name'=>'Couleur/groupe :',
                                'value'=>$color->name,
                                'inline'=>true,
                            ]
                        ],
                        'footer'=>[
                            'text'=>'Information de  : ' . Auth::user()->name
                        ]
                    ]
                ],
            ]);
        }

        return response()->json(['status'=>'OK'],201);
    }

    public function removePatient(string $patient_id): \Illuminate\Http\JsonResponse
    {
        $bcp = BCPatient::where('id', (int) $patient_id)->first();
        if (!is_null($bcp->rapport_id)){
            $rapport = Rapport::where('id', $bcp->rapport_id)->first();
            $facture = Facture::where('id', $rapport->GetFacture->id)->first();
            $facture->delete();
            $rapport->delete();
        }
        $bcp->delete();
        event(new Notify('Patient retiré (la page va se mettre à jour)! ',1));
        return response()->json(['status'=>'OK']);
    }

    public function generateListWithAllPatients(Request $request, string $from, string $to): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {

        $Bcs = BCList::where('ended', true)->where('created_at', '>', $from . ' 00:00:00')->where('created_at', '<', $to . ' 00:00:00')->orderBy('id', 'desc')->get();

        $idList = array();

        foreach ($Bcs as $bc){
            $patients = $bc->GetPatients;
            foreach ($patients as $patient){
                array_push($idList, $patient->patient_id);
            }
        }
        $arrayOrdered = array_count_values($idList);
        arsort($arrayOrdered);
        $patientsId = array_keys($arrayOrdered);

        $bcList = array();

        foreach ($patientsId as $pid){
            $bcList[$pid] = array();
        }

        foreach ($Bcs as $bc){
            $patients = $bc->GetPatients;
            foreach ($patients as $patient){
                foreach ($patientsId as $pid){
                    if($pid == $patient->patient_id){
                        array_push($bcList[$pid], $bc->id);
                    }
                }
            }
        }
        $columns[] = ['id patient','prénom nom', "nombre d’apparitions", 'liste des apparitions'];
        foreach ($patientsId as $patient_id){
            $patient = Patient::where('id', $patient_id)->first();

            $string = '';
            foreach ($bcList[$patient_id] as $pid){
                if(strlen($string) == 0){
                    $string = $pid;
                }else{
                    $string = $string . ', ' . $pid;
                }
            }

            $columns[] = [
                $patient_id,
                $patient->vorname . ' ' . $patient->name,
                $arrayOrdered[$patient_id],
                $string
            ];
        }

        $export = new ExelPrepareExporter($columns);
        return Excel::download((object)$export, 'listeDesPatientsDansLesBC.xlsx');
    }
}
