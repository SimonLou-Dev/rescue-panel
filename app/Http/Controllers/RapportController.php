<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\Facture;
use App\Models\Factures;
use App\Models\Hospital;
use App\Models\HospitalList;
use App\Models\InterType;
use App\Models\Intervention;
use App\Models\Patient;
use App\Models\Rapport;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use ParagonIE\Sodium\Core\Curve25519\H;



class RapportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function getforinter(Request $request): \Illuminate\Http\JsonResponse
    {
        $intertype = Intervention::all();
        $broum = Hospital::all();
        return response()->json(['status'=>'OK', 'intertype'=>$intertype, 'transport'=>$broum]);
    }

    public function addRapport(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'startinter'=>['required'],
            'type'=>['required'],
            'transport'=>['required'],
            'desc'=>['required'],
            'montant'=>['required'],
            'payed'=>['required'],
            'montant'=>['required','integer'],
            'tel'=>['integer']
        ]);

        $patientname = explode(' ', $request->name);
        $Patient = $this->PatientExist($patientname[1], $patientname[0]);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $patientname[1];
            $Patient->vorname = $patientname[0];
            $Patient->tel = $request->tel;
            $Patient->save();
        }
        $patient_id = $Patient->id;
        $rapport = new Rapport();
        $rapport->patient_id = $patient_id;
        $rapport->started_at = $request->startinter;
        $rapport->interType= (int) $request->type;
        $rapport->transport= (int) $request->transport;
        $rapport->description = $request->desc;
        $rapport->price = (int) $request->montant;
        $rapport->user_id = Auth::user()->id;
        $rapport->ATA_start = date('Y/m/d H:i:s', strtotime($request->startdate . ' ' . $request->starttime));
        $rapport->ATA_end = date('Y/m/d H:i:s', strtotime($request->enddate . ' ' . $request->endtime));
        $rapport->save();
        $this::addFactureMethod($Patient, $request->payed, $request->montant, Auth::user()->id, $rapport->id);
        if($rapport->ATA_start === $rapport->ATA_end){
            $ata = 'non ';
        }else{
            $ata = 'Du ' . date('Y/m/d H:i', strtotime($rapport->ATA_start)) . ' au ' . date('Y/m/d H:i', strtotime($rapport->ATA_end));
        }

        if($request->payed){
            $fact= 'Payée : ' . $request->montant;
        }else{
            $fact= 'Impayée : ' . $request->montant;
        }
        Http::post(env('WEBHOOK_RI'),[
            'username'=> "BCFD - Intranet",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Ajout d\'un rapport :',
                    'color'=>'1285790',
                    'fields'=>[
                        [
                            'name'=>'Patient : ',
                            'value'=>$patientname[0] . ' ' .$patientname[1],
                            'inline'=>true
                        ],[
                            'name'=>'Type d\'intervention : ',
                            'value'=> Intervention::where('id', $request->type)->first()->name,
                            'inline'=>true
                        ],[
                            'name'=>'Transport : ',
                            'value'=>$rapport->GetTransport->name,
                            'inline'=>true
                        ],[
                            'name'=>'ATA : ',
                            'value'=>$ata,
                            'inline'=>false
                        ],[
                            'name'=>'Facture : ',
                            'value'=>$fact.'$',
                            'inline'=>false
                        ],[
                            'name'=>"Debut de l'intervention : ",
                            'value'=>date('d/m/y H:I', strtotime($rapport->started_at)),
                            'inline'=>false
                        ]
                        ,[
                            'name'=>'Description : ',
                            'value'=>$rapport->description,
                            'inline'=>false
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Rapport de : ' . Auth::user()->name,
                    ]
                ]
            ]
        ]);
        event(new Notify('Rapport ajouté ! ',1));
        return response()->json([],201);
    }

    public function search(Request $request, string $text): \Illuminate\Http\JsonResponse
    {
        $text = explode(" ", $text);
        $prenom = $text[0];
        if(count($text) > 1){
            $nom = $text[1];
        }else{
            $nom = null;
        }
        $patient = Patient::where('vorname', 'LIKE', $prenom.'%')->orWhere('name', 'LIKE', '%'.$nom.'%')->take(6)->get();
        return response()->json(['status'=>'OK', 'list'=>$patient]);
    }

    public function getPatient(Request $request, string $text): \Illuminate\Http\JsonResponse
    {

        $text = explode(" ", $text);
        $prenom = $text[0];
        if(count($text) > 1){
            $nom = $text[1];
            $patient = $this->PatientExist($nom, $prenom);
            if(!is_null($patient)){
                $inter = Rapport::where('patient_id', $patient->id)->orderBy('id', 'desc')->get();

                return response()->json(['status'=>'OK', 'patient'=>$patient, 'inter'=>$inter]);
            }
        }
        return response()->json(['status'=>'erreur pas de patient']);
    }

    public function getInter(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $inter  = Rapport::where('id', $id)->first();
        $types = Intervention::all();
        $broum = Hospital::all();
        return response()->json(['status'=>'OK', 'rapport'=>$inter, 'types'=>$types,'broum'=>$broum]);
    }

    public function updateRapport(Request $request, int $id): \Illuminate\Http\JsonResponse
    {


        $rapport = Rapport::where('id', $id)->first();
        $facture = $rapport->GetFacture;
        $facture->price = (integer) $request->montant;
        $rapport->InterType= (integer) $request->type;
        $rapport->transport= (integer) $request->transport;
        $rapport->description = $request->desc;
        $rapport->price = (integer) $request->montant;
        if($request->starttime != '00:00'){
            $rapport->ATA_start = date('Y/m/d H:i:s', strtotime($request->startdate . ' ' . $request->starttime));
            $rapport->ATA_end = date('Y/m/d H:i:s', strtotime($request->enddate . ' ' . $request->endtime));
        }
        $facture->save();
        $rapport->save();
        event(new Notify('Rapport mis à jour',1));
        return response()->json(['status'=>'OK'],201);
    }

    public function getRapportById(string $id): \Illuminate\Http\JsonResponse
    {
        $id = (int) $id;
        $rapport = Rapport::where('id', $id)->first();
        $rapport->GetType;
        $rapport->GetTransport;
        $patient = $rapport->GetPatient;
        $transport = Hospital::all();
        $types = Intervention::all();
        $raportlist = Rapport::where('patient_id', $patient->id)->get();
        return response()->json(['status'=>'ok', 'rapport'=>$rapport, 'patient'=>$patient, 'rapportlist'=>$raportlist, 'broum'=>$transport, 'types'=>$types]);
    }

    public static function PatientExist(string $name, string $vorname): ?Patient{
        $patient = Patient::where('name', 'LIKE', $name)->where('vorname','LIKE', $vorname);
        if($patient->count() == 1){
            return $patient->first();
        }
        return null;
    }

    public function getAllimpaye(Request $request): \Illuminate\Http\JsonResponse
    {
        $impaye = Facture::where('payed', false)->orderBy('id', 'desc')->get();
        $size = count($impaye);
        $a = 0;
        while ($a < $size){
            $impaye[$a]->GetPatient;

            $a++;
        }
        return response()->json(['status'=>'OK', 'impaye'=>$impaye]);
    }

    public function paye(Request $request, int $id): \Illuminate\Http\JsonResponse
    {


        $facture = Facture::where('id', $id)->first();
        $facture->payed = true;
        $facture->save();
        Http::post(env('WEBHOOK_FACTURE'),[
            'username'=> "BCFD - Intranet",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Facture payée :',
                    'color'=>'13436400 ',
                    'fields'=>[
                        [
                            'name'=>'Patient : ',
                            'value'=>$facture->GetPatient->vorname . ' '.$facture->GetPatient->name,
                            'inline'=>true
                        ],[
                            'name'=>'Montant : ',
                            'value'=>$facture->price .'$',
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Confirmation de payement : ' . Auth::user()->name
                    ]
                ]
            ]
        ]);
        event(new Notify('Facture payée ! ',2));
        return response()->json(['status'=>'OK']);
    }

    public function addFacture(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'payed'=>['required'],
            'montant'=>['required','integer']
        ]);

        $name = explode(" ", $request->name);

        $Patient = $this->PatientExist($name[1], $name[0]);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $name[1];
            $Patient->vorname = $name[0];
            $Patient->tel = $request->tel;
            $Patient->save();
        }
        $this->addFactureMethod((object) $Patient,(bool) $request->payed, (int) $request->montant, (int) Auth::user()->id, null);
        return response()->json(['status'=>'OK'],201);
    }

    public function updatePatientInfos(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $patient = Patient::where('id', $id)->first();
        $patient->tel = $request->tel;
        $patient->name = $request->nom;
        $patient->vorname = $request->prenom;
        $patient->save();
        event(new Notify('Information mises à jour ! ',1));
        return response()->json(['status'=>'OK'],201);
    }

    public function makeRapportPdf(Request $request, int $id){
        $data = array();
        $rapport = Rapport::where('id', $id)->first();

        $pdf = \PDF::loadView('pdf.rapport', compact('rapport'))->setOptions(['isRemoteEnabled'=>true, 'isHtml5ParserEnabled'=>true, 'isPhpEnabled'=>true, 'debugPng'=>true, 'setBasePath'=>$_SERVER['DOCUMENT_ROOT'], 'chroot'=>public_path()]);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $name = 'patient_'.$id.'.pdf';
        return $pdf->stream($name);
    }

    public function makeImpayPdf(Request $request, string $from , string $to){
        //2021-01-05
        $impaye = Facture::where('payed', false)->where('created_at', '>', $from)->where('created_at', '<', $to)->orderBy('id', 'desc')->get();
        $a = 0;
        while ($a < count($impaye)){
            $impaye[$a]->patient;
            $a++;
        }

        $infos = ['from'=>date('d/m/Y', strtotime($from)),'to'=>date('d/m/Y', strtotime($to))];
        $data = ['infos'=>$infos, 'impaye'=>$impaye];


        $pdf = \PDF::loadView('pdf.factures', compact('data'))->setOptions(['isRemoteEnabled'=>true, 'isHtml5ParserEnabled'=>true, 'isPhpEnabled'=>true, 'debugPng'=>true, 'setBasePath'=>$_SERVER['DOCUMENT_ROOT'], 'chroot'=>public_path()]);
        $pdf->getDomPDF()->setHttpContext(
            stream_context_create([
                'ssl' => [
                    'allow_self_signed'=> TRUE,
                    'verify_peer' => FALSE,
                    'verify_peer_name' => FALSE,
                ]
            ])
        );
        $name = 'impaye_'.time().'.pdf';
        return $pdf->stream($name);
    }

    public static function addFactureMethod(object $patient, bool $payed, int $price, int $cofirm_id=null, int $rapport_id=null){
        $facture = new Facture();
        $facture->patient_id = $patient->id;
        $facture->payed = $payed;
        $facture->price = $price;
        if($payed){
            $facture->payement_confirm_id = $cofirm_id;
        }
        $facture->rapport_id = $rapport_id;
        $facture->save();
        if($facture->payed){
            $fact= 'Payée : ' . $facture->price .'$';
        }else{
            $fact= 'Impayée : ' . $facture->price .'$';
        }

        Http::post(env('WEBHOOK_FACTURE'),[
            'username'=> "BCFD - Intranet",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
            'embeds'=>[
                [
                    'title'=>'Nouvelle facture :',
                    'color'=>'13436400 ',
                    'fields'=>[
                        [
                            'name'=>'Patient : ',
                            'value'=>$patient->vorname. ' ' . $patient->name,
                            'inline'=>true
                        ],[
                            'name'=>'Facture : ',
                            'value'=>$fact,
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Ajoutée par : ' . Auth::user()->name
                    ]
                ]
            ]
        ]);
        event(new Notify('Facture de $'. $price .' ajoutée ! ',1));
    }

}
