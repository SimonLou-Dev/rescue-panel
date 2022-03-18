<?php

namespace App\Http\Controllers\Rapports;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Patient\PatientController;
use App\Jobs\ProcessEmbedPosting;
use App\Jobs\ProcessRapportPDFGenerator;
use App\Models\Facture;
use App\Models\Hospital;
use App\Models\Intervention;
use App\Models\Pathology;
use App\Models\Patient;
use App\Models\Rapport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


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
        if(Session::get('service')[0] == 'SAMS'){
            $patho = Pathology::all();
        }
        return response()->json(['status'=>'OK', 'intertype'=>$intertype, 'transport'=>$broum, 'pathology'=>$patho]);
    }

    public function addRapport(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize("create", Rapport::class);

        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'startinter'=>['required'],
            'tel'=>['tel'=> 'required','regex:/5{3}-\d\d/'],
            'type'=>['required','int', 'min:1'],
            'transport'=>['required','int', 'min:1'],
            'desc'=>['required'],
            'payed'=>['required', 'boolean'],
            'montant'=>['required','integer'],
            'ata'=>['string',new StringTime]
        ]);


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
        if(isset($request->bloodgroup) && $request->bloodgroup != ''){
            $request->validate(['bloodgroup'=>['regex:/(A|B|AB|O)[+-]/']]);
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
        if(isset($request->ata)){
            $rapport->ata = $request->ata;
        }
        $rapport->service = Session::get('service')[0];
        if(isset($request->pathology)){
            $rapport->pathology_id = $request->pathology;
        }
        $rapport->save();
        FacturesController::addFactureMethod($Patient, $request->payed, $request->montant, Auth::user()->id, $rapport->id);

        $path =  storage_path('app/public/RI/') . $rapport->id.'.pdf';
        $embed = $this::rapportEmbed($rapport);
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

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateRapport(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'startinter'=>['required'],
            'type'=>['required','int', 'min:1'],
            'transport'=>['required','int', 'min:1'],
            'desc'=>['required'],
            'payed'=>['required', 'boolean'],
            'montant'=>['required','integer'],
            'ata'=>['string', new StringTime]
        ]);


        $rapport = Rapport::where('id', $id)->first();
        $this->authorize("update", $rapport);
        $rapport->started_at = $request->startinter;
        $rapport->interType= (int) $request->type;
        $rapport->transport= (int) $request->transport;
        $rapport->description = $request->desc;
        $rapport->price = (int) $request->montant;
        $rapport->user_id = Auth::user()->id;
        if(isset($request->ata)){
            $rapport->ata = $request->ata;
        }
        if(isset($request->pathology) && $request->pathology != 0){
            $rapport->pathology_id = $request->pathology;
        }
        $rapport->save();
        FacturesController::updateFactureMethod($rapport->GetPatient, $rapport, $request->payed, $request->montant);

        $path =  storage_path('app/public/RI/') . $rapport->id.'.pdf';

        if(\Illuminate\Support\Facades\File::exists($path)){
           \Illuminate\Support\Facades\File::delete($path);
        }
        $this->dispatch(new ProcessRapportPDFGenerator($rapport, $path));

        $embed = $this::rapportEmbed($rapport);

        if(isset($rapport->discord_msg_id)){
            \Discord::updateMessage(DiscordChannel::RI, $rapport->discord_msg_id, $embed, null);
        }else{
            \Discord::postMessage(DiscordChannel::RI, $embed, $rapport);
        }


        $logs = new LogsController();
        $logs->RapportLogging('update', $rapport->id, Auth::user()->id);

        event(new Notify('Rapport mis à jour',1));
        return response()->json(['status'=>'OK'],201);
    }

    public function getPatientInter(string $patientId): \Illuminate\Http\JsonResponse
    {
        $patient = Patient::where('id',$patientId)->first();
        $rapports = $patient->GetRapports;
        $rapportsList = array();
        foreach ($rapports as $rapport){
            if (Gate::allows('view', $rapport)){
                $rapport->GetType;
                $rapport->GetFacture;
                $rapport->GetTransport;
                $rapportsList[$rapport->id] = $rapport;
            }
        }
        $rapportsList = collect($rapportsList);
        $transport = Hospital::withTrashed()->get();
        $types = Intervention::withTrashed()->get();
        $pathology = Pathology::withTrashed()->get();

        return response()->json(['status'=>'ok',
            'pathologys'=>$pathology,
            'patient'=>$patient,
            'rapportlist'=> $rapportsList,
            'broum'=>$transport,
            'types'=>$types]);
    }


    private static function rapportEmbed(Rapport $rapport):array
    {
        $ata = ($rapport->ata == '' ? 'non' : $rapport->ata );

        if($rapport->GetFacture->payed){
            $fact= 'Payée : ' . $rapport->GetFacture->price;
        }else{
            $fact= 'Impayée : ' . $rapport->GetFacture->price;
        }
        $service = Session::get('service')[0];
        $fields = [
            [
                'name'=>'Patient : ',
                'value'=>$rapport->GetPatient->name,
                'inline'=>true
            ],[
                'name'=>'Type d\'intervention : ',
                'value'=> $rapport->GetType->name,
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
        if($service == 'SAMS' && !is_null($rapport->pathology_id) && $rapport->pathology_id != 0){
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

        return $embed = [
            [
                'title'=>'Ajout d\'un rapport :',
                'color'=>'1285790',
                'fields'=>$fields,
                'footer'=>[
                    'text' => 'Rapport de : ' . Auth::user()->name . " (${service})",
                ]
            ]
        ];
    }
}
