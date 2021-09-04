<?php

namespace App\Http\Controllers\BlackCodes;


use App\Events\Brodcaster;
use App\Events\Notify;
use App\Http\Controllers\Controller;
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
use App\PDFExporter\ServicePDFExporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use function GuzzleHttp\json_encode;


class BCController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

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
        $EndedBC = BCList::where('ended', true)->orderByDesc('id')->take(15)->get();
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
        foreach ($bc->GetPatients as $patient){
            $patient->GetColor;
        }
        if($bc->ended){
            $blessures = Blessure::withTrashed()->get();
            $color= CouleurVetement::withTrashed()->get();
        }else{
            $blessures = Blessure::all();
            $color = CouleurVetement::all();
        }
        return response()->json([
            'status'=>'OK',
            'bc'=>$bc,
            'colors'=>$color,
            'blessures'=>$blessures,
        ]);
    }

    public function addBc(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type'=>['required'],
            'place'=>['required'],
        ]);

        $type = $request->type;
        $place = $request->place;
        $bc = new BCList();
        $bc->starter_id = Auth::user()->id;
        $bc->place = $place;
        $bc->type_id = $type;
        $bc->save();
        $this->addPersonel((string)$bc->id);

        Http::post(env('WEBHOOK_PU'),[
            'username'=> "BCFD - Intranet",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/BCFD.png',
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

        event(new Brodcaster('Début du BC #'.$bc->id . ' à ' . $bc->place));

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
        BcEmbedController::generateBCEndedEmbed($formated, $patients, $personnels, $bc);
        event(new Brodcaster('Fin du BC #'.$bc->id));

        return response()->json(['status'=>'OK'],201);
    }

    public function generateRapport(string $id){
        $bc = BCList::where('id',$id)->first();
        $columns[] = ['prénom nom', 'couleur vêtement', 'date et heure d\'ajout'];
        foreach ($bc->GetPatients as $patient){
            $columns[] = [
                $patient->GetPatient->vorname . ' ' . $patient->GetPatient->name,
                CouleurVetement::withTrashed()->where('id', $patient->couleur)->first(),
                date('d/m/Y H:i', strtotime($patient->created_at))
            ];
        }

        $export = new ServicePDFExporter($columns);
        return Excel::download((object)$export, 'ListePatientsBc'. $id .'.xlsx');
    }
}
