<?php

namespace App\Http\Controllers\BlackCodes;

use App\Enums\DiscordChannel;
use App\Events\BlackCodeUpdated;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\Rapports\FacturesController;
use App\Http\Controllers\Rapports\PatientController;
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

    public function addPatient(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('ModifyPatient', BCList::class);
        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'blessure'=>['required'],
            'payed'=>['required']
        ]);



        $Patient = PatientController::PatientExist($request->name);
        $bc = BCList::where('id', $id)->first();




        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $request->name;
            $Patient->save();
        }
        if(BCPatient::where('BC_id',$bc->id)->where('patient_id', $Patient->id)->count() != 0){
            Notify::dispatch('Patient déja ajouté ',1,Auth::user()->id);
            return response()->json(['status'=>'OK'],201);
        }
        $rapport = new Rapport();
        $rapport->patient_id = $Patient->id;
        $rapport->started_at = $bc->created_at;
        $rapport->interType = 1;
        $rapport->transport = 1;
        $rapport->user_id = Auth::user()->id;
        $rapport->service = 'SAMS';
        $desc = Blessure::where('id', $request->blessure)->first();
        $rapport->description = $desc->name;
        $rapport->price = 700;
        $rapport->save();
        $BcP = new BCPatient();
        $BcP->name = $Patient->name;

        $BcP->patient_id = $Patient->id;
        $BcP->rapport_id = $rapport->id;
        $BcP->blessure_type = $request->blessure;
        if(isset($request->color)){
            $BcP->couleur = $request->color;
        }
        if(isset($request->carteid)){
            $BcP->idcard = (bool) $request->carteid;
        }
        $BcP->BC_id = $bc->id;
        $BcP->save();
        FacturesController::addFactureMethod($Patient, $request->payed, 700, Auth::user()->id,$rapport->id);
        Notify::broadcast('Patient ajouté',2 ,Auth::user()->id);
        BlackCodeUpdated::dispatch($bc->id);

        $logs = new LogsController();
        $logs->BCLogging('add Patient n° ' . $Patient->id, $bc->id, Auth::user()->id);

        return response()->json(['status'=>'OK'],201);
    }

    public function removePatient(string $patient_id): \Illuminate\Http\JsonResponse
    {
        $this->authorize('ModifyPatient', BCList::class);
        $bcp = BCPatient::where('id', (int) $patient_id)->first();
        if (!is_null($bcp->rapport_id)){
            $rapport = Rapport::where('id', $bcp->rapport_id)->first();
            $facture = Facture::where('id', $rapport->GetFacture->id)->first();
            \Discord::deleteMessage(DiscordChannel::Facture, $facture->discord_msg_id);
            $facture->delete();
            $rapport->delete();
        }


        $id = $bcp->GetBC->id;
        $bcp->delete();
        $logs = new LogsController();
        $logs->BCLogging('remove patient', $id, Auth::user()->id);
        Notify::dispatch('Patient supprimé ',1,Auth::user()->id);
        BlackCodeUpdated::dispatch($id);
        return response()->json(['status'=>'OK']);
    }

    public function generateListWithAllPatients(Request $request, string $from, string $to): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {

        $Bcs = BCList::where('ended', true)->where('created_at', '>', $from . ' 00:00:00')->where('created_at', '<', $to . ' 00:00:00')->orderBy('id', 'desc')->get();

        $patientsId = $this::getOrderedListOfPatientIdPresent($Bcs)[0];
        $arrayOrdered = $this::getOrderedListOfPatientIdPresent($Bcs)[1];

        $bcList = array();

        foreach ($patientsId as $pid){
            $bcList[$pid] = array();
        }
        $bcList = $this::fillBclist($Bcs, $patientsId);

        foreach ($Bcs as $bc) {
            $bc->GetPatients;
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

    private static function getOrderedListOfPatientIdPresent(BCList $Bcs): array
    {
        foreach ($Bcs as $bc){
            $patients = $bc->GetPatients;
            foreach ($patients as $patient){
                $idList[] = $patient->patient_id;
            }
        }
        $arrayOrdered = array_count_values($idList);
        arsort($arrayOrdered);
        return [array_keys($arrayOrdered), $arrayOrdered];
    }
    private static function fillBclist(BCList $Bcs,array $patientsId){
        foreach ($Bcs as $bc){
            $patients = $bc->GetPatients;
            foreach ($patients as $patient){
                foreach ($patientsId as $pid){
                    if($pid == $patient->patient_id){
                        $bcList[$pid][] = $bc->id;
                    }
                }
            }
        }
        return $bcList;
    }
}
