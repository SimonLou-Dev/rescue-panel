<?php

namespace App\Http\Controllers\Rapports;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Jobs\ProcessEmbedPosting;
use App\Jobs\ProcessRapportPDFGenerator;
use App\Models\Facture;
use App\Models\Factures;
use App\Models\Hospital;
use App\Models\HospitalList;
use App\Models\InterType;
use App\Models\Intervention;
use App\Models\Pathology;
use App\Models\Patient;
use App\Models\Rapport;
use App\Facades\Discord;
use Hoa\File\File;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class RapportController extends Controller
{

    private static string $SQLdateformat = 'Y/m/d H:i:s';

    public function __construct()
    {
       // $this->middleware('auth');
       // $this->middleware('access');
    }

    public function getforinter(Request $request): \Illuminate\Http\JsonResponse
    {
        $intertype = Intervention::where('service', Session::get('service'))->get();
        $broum = Hospital::where('service', Session::get('service'))->get();
        $patho = null;
        if(Session::get('service')[0] == 'OMC'){
            $patho = Pathology::all();
        }
        return response()->json(['status'=>'OK', 'intertype'=>$intertype, 'transport'=>$broum, 'pathology'=>$patho]);
    }

    public function addRapport(Request $request): \Illuminate\Http\JsonResponse
    {

        /*
         * name: name,
                startinter: interdate + ' '  + interhour,
                tel: tel,
                ddn: ddn,
                liveplace: liveplace,
                lieux: lieux,
                type: intertype,
                transport: transport,
                desc: desc,
                montant: montant,
                payed: payed,
                ata: ata,
         */

        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'startinter'=>['required', 'different:0'],
            'tel'=>['tel'=> 'required','regex:/5{3}-\d\d/'],
            'bloodgroup'=>['regex:/(A|B|AB|O)[+-]/'],
            'type'=>['required', 'different:0'],
            'transport'=>['required', 'different:0'],
            'desc'=>['required'],
            'payed'=>['required', 'boolean'],
            'montant'=>['required','integer'],
            'ata'=>['string']
        ]);

        if(Session::get('service')[0] === 'OMC'){

            $request->validate([
                'pathology'=>['different:0', 'integer']
            ]);
        }

        $Patient = PatientController::PatientExist($request->name);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $request->name;
        }
        if(isset($request->tel)){
            $Patient->tel = $request->tel;
        }
        if(isset($request->ddn)){
            $Patient->naissance  = $request->ddn;
        }
        if(isset($request->bloodgroup)){
            $Patient->blood_group  = $request->bloodgroup;
        }
        if(isset($request->liveplace)){
            $Patient->living_place = $request->liveplace;
        }



        $Patient->save();
        $patient_id = $Patient->id;
        $rapport = new Rapport();
        $rapport->patient_id = $patient_id;
        $rapport->started_at = $request->startinter;
        $rapport->interType= (int) $request->type;
        $rapport->transport= (int) $request->transport;
        $rapport->description = $request->desc;
        $rapport->price = (int) $request->montant;
        $rapport->user_id = Auth::user()->id;
        $rapport->ata = \TimeCalculate::stringToSec($request->ata);
        $rapport->service = Session::get('service')[0];
        if(isset($request->pathology)){
            $rapport->pathology_id = $request->pathology;
        }
        $rapport->save();
        FacturesController::addFactureMethod($Patient, $request->payed, $request->montant, Auth::user()->id, $rapport->id);
        $ata = $request->ata === '' ? 'non' : $request->ata;

        if($request->payed){
            $fact= 'Payée : ' . $request->montant;
        }else{
            $fact= 'Impayée : ' . $request->montant;
        }
        $service = Session::get('service')[0];
        $path =  "/public/RI/{$rapport->id}.pdf";
        $fields = [
            [
                'name'=>'Patient : ',
                'value'=>$Patient->name,
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
            ],
        ];//pathologie
        if($service == 'OMC'){
            array_push($fields, [
                'name'=>'pathologie : ',
                'value'=>$rapport->GetPathology->name,
                'inline'=>true
            ]);
        }
        array_push($fields, [
            'name'=>'Description : ',
            'value'=>$rapport->description,
            'inline'=>false
        ],[
            'name'=>'PDF',
            'value'=>":link: [`PDF`](".env('APP_URL').'/storage/RI/'.$rapport->id . ".pdf)"
        ]);

        $embed = [
            [
                'title'=>'Ajout d\'un rapport :',
                'color'=>'1285790',
                'fields'=>$fields,
                'footer'=>[
                    'text' => 'Rapport de : ' . Auth::user()->name . " ({$service})",
                ]
            ]
        ];
        \Discord::postMessage(DiscordChannel::RI, $embed, $rapport);
        $this->dispatch(new ProcessRapportPDFGenerator($rapport, $path));

        $logs = new LogsController();
        $logs->RapportLogging('create', $rapport->id, Auth::user()->id);

        event(new Notify('Rapport ajouté ! ',1, Auth::user()->id));

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
            event(new Notify('Il n\'y a pas de description',1,Auth::user()->id));
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
            $rapport->ATA_start = date($this::$SQLdateformat, strtotime($request->startdate . ' ' . $request->starttime));
            $rapport->ATA_end = date($this::$SQLdateformat, strtotime($request->enddate . ' ' . $request->endtime));
        }
        $facture->save();
        $rapport->save();
        $path = '/public/RI/'. $rapport->id . ".pdf";

        if(Storage::exists($path)){
            Storage::delete($path);
        }

        $this->dispatch(new ProcessRapportPDFGenerator($rapport, $path));

        $logs = new LogsController();
        $logs->RapportLogging('update', $rapport->id, Auth::user()->id);

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
