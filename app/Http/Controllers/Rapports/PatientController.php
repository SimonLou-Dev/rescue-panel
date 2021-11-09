<?php

namespace App\Http\Controllers\Rapports;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Rapport;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
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
        $patient = Patient::where('vorname', 'LIKE', $prenom.'%')->where('name', 'LIKE', '%'.$nom.'%')->take(6)->get();
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

    public static function PatientExist(string $name, string $vorname): ?Patient{
        $patient = Patient::where('name', 'LIKE', $name)->where('vorname','LIKE', $vorname);
        if($patient->count() == 1){
            return $patient->first();
        }
        return null;
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
}
