<?php

namespace App\Http\Controllers\Rapports;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Models\Facture;
use App\Models\Patient;
use App\Models\Rapport;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
        //$this->middleware('access');
    }

    public function getImpaye(string $patientId){
        $patient = Patient::where('id', $patientId)->first();
        $req = Facture::where('payed', false)->where('patient_id', $patient->id);
        $count = $req->count();
        $montant = 0;
        if($count != 0){
            $factures = $req->get();
            foreach ($factures as $facture){
                $montant += $facture->price;
            }
        }
        return response()->json([
            'status'=>'OK',
            'number'=>$count,
            'montant'=>$montant
        ]);
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

    public function getPatient(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $patient = Patient::where('id',$id)->first();
        $req = Facture::where('payed', false)->where('patient_id', $patient->id);
        $count = $req->count();
        $montant = 0;
        if($count != 0){
            $factures = $req->get();
            foreach ($factures as $facture){
                $montant += $facture->price;
            }
        }

        return response()->json([
            'status'=>'OK',
            'number'=>$count,
            'montant'=>$montant,
            'patient'=>$patient,
        ]);

        return response()->json(['status'=>'erreur pas de patient']);
    }

    public static function PatientExist(string $name): ?Patient{
        $patient = Patient::where('name', 'LIKE', $name);
        if($patient->count() == 1){
            return $patient->first();
        }
        return null;
    }

    public function updatePatientInfos(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        \Gate::authorize('patient-edit', User::where('id', \Auth::user()->id)->first());
        $request->validate([
            'name'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'tel'=>['tel'=> 'required','regex:/5{3}-\d\d/'],

        ]);


        $patient = Patient::where('id', $id)->first();
        $patient->tel = $request->tel;
        $patient->name = $request->name;
        $patient->naissance  = $request->ddn;
        if(isset($request->bloodgroup) && $request->bloodgroup != ''){
            $request->validate(['bloodgroup'=>['regex:/(A|B|AB|O)[+-]/']]);
            $patient->blood_group  = $request->bloodgroup;
        }
        $patient->living_place = $request->liveplace;
        $patient->save();
        event(new Notify('Informations mise à jour ! ',1, \Auth::id()));
        return response()->json(['status'=>'OK'],201);
    }

    public function getAllPatientsSearcher(Request $request){
       $patients = Patient::search($request->query('query'))->paginate();
       $colors = ['#04cf26','#99cf04','#cf6d04','#cf0404'];

       foreach ($patients as $patient){

           $rapports = Rapport::where('id', $patient->id)->where('created_at', '>', date('d/m/Y H:i', strtotime(date('Y-m-d H:i:s'). ' -5 days')))->count();
           $number = 0;
           if($rapports > 3 && $rapports < 5){
               $number = 1;
           }else if($rapports >= 5 && $rapports < 7){
               $number = 2;
           }else if($rapports >= 7){
               $number = 3;
           }
           $patient->colorOfName = $colors[$number];

       }

        return response()->json([
            'patients'=>$patients
        ]);

    }
}
