<?php

namespace App\Http\Controllers\Rapports;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessRapportPDFGenerator;
use App\Models\Facture;
use App\Models\Factures;
use App\Models\Hospital;
use App\Models\HospitalList;
use App\Models\InterType;
use App\Models\Intervention;
use App\Models\Patient;
use App\Models\Rapport;
use Hoa\File\File;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

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
        ]);

        if(isset($request->tel)){
            $request->validate([
                'tel'=>['numeric']
            ]);
        }

        $patientname = explode(' ', $request->name);
        $Patient = PatientController::PatientExist($patientname[1], $patientname[0]);
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
        FacturesController::addFactureMethod($Patient, $request->payed, $request->montant, Auth::user()->id, $rapport->id);
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
        $user = Auth::user()->name;

        Http::post(env('WEBHOOK_RI'),[
            'username'=> "LSCoFD - MDT",
            'avatar_url'=>'https://lscofd.simon-lou.com/assets/images/LSCoFD.png',
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
        $path = '/public/RI/'. $rapport->id . ".pdf";
        $this->dispatch(new ProcessRapportPDFGenerator($rapport, $path));

        event(new Notify('Rapport ajouté ! ',1));

        return response()->json([],201);
    }

    public function getInter(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $inter  = Rapport::where('id', $id)->first();
        $types = Intervention::withTrashed()->get();
        $broum = Hospital::withTrashed()->get();
        return response()->json(['status'=>'OK', 'rapport'=>$inter, 'types'=>$types,'broum'=>$broum]);
    }

    public function updateRapport(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        if($request->desc == '' ||$request->desc == null){
            event(new Notify('Il n\'y a pas de description'));
            return \response([],404);
        }


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
        $path = '/public/RI/'. $rapport->id . ".pdf";

        if(Storage::exists($path)){
            Storage::delete($path);
        }

        $this->dispatch(new ProcessRapportPDFGenerator($rapport, $path));

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
        $transport = Hospital::withTrashed()->get();
        $types = Intervention::withTrashed()->get();
        $raportlist = Rapport::where('patient_id', $patient->id)->get();
        return response()->json(['status'=>'ok', 'rapport'=>$rapport, 'patient'=>$patient, 'rapportlist'=>$raportlist, 'broum'=>$transport, 'types'=>$types]);
    }
}
