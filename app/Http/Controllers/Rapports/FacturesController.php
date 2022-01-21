<?php

namespace App\Http\Controllers\Rapports;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessEmbedPosting;
use App\Models\Facture;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\LogsController;

class FacturesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public static function addFactureMethod(object $patient, bool $payed, int $price, int $cofirm_id=null, int $rapport_id=null){
        $facture = new Facture();
        $facture->patient_id = $patient->id;
        $facture->payed = $payed;
        $facture->price = $price;
        $facture->service = \Session::get('service')[0];
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
        $service = \Session::get('service')[0];
        $embed = [
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
                    'text' => 'Ajoutée par : ' . Auth::user()->name ." ({$service})"
                ]
            ]
        ];

        if($service === 'OMC'){
            \Discord::postMessage(DiscordChannel::MedicFacture,  $embed, $facture);
        }else{
            \Discord::postMessage(DiscordChannel::FireFacture,  $embed, $facture);
        }

        $logs = new LogsController();
        $logs->FactureLogging('create', $facture->id, Auth::user()->id);
        event(new Notify('Facture de $'. $price .' ajoutée ! ',1, Auth::user()->id));
    }

    public function addFacture(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'payed'=>['required'],
            'montant'=>['required','integer']
        ]);

        $name = explode(" ", $request->name);

        $Patient = PatientController::PatientExist($name[1], $name[0]);
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

    public function getAllimpaye(Request $request): \Illuminate\Http\JsonResponse
    {
        $impaye = Facture::where('payed', false)->orderBy('id', 'desc')->take(100)->get();
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
        $embed = [
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
        ];
        $this->dispatch(new ProcessEmbedPosting([env('WEBHOOK_FACTURE')], $embed, null));
        $logs = new LogsController();
        $logs->FactureLogging('paye', $facture->id, Auth::user()->id);
        event(new Notify('Facture payée ! ',2));
        return response()->json(['status'=>'OK']);
    }
}
