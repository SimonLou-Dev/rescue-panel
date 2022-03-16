<?php

namespace App\Http\Controllers\Rapports;

use App\Enums\DiscordChannel;
use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LogsController;
use App\Jobs\ProcessEmbedPosting;
use App\Jobs\ProcesTestPoudrePDFGen;
use App\Jobs\ProcesTestPoudrePDFGenerator;
use App\Models\Intervention;
use App\Models\Patient;
use App\Models\TestPoudre;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Util\Test;
use TheCodingMachine\Gotenberg\Client;
use TheCodingMachine\Gotenberg\ClientException;
use TheCodingMachine\Gotenberg\DocumentFactory;
use TheCodingMachine\Gotenberg\FilesystemException;
use TheCodingMachine\Gotenberg\HTMLRequest;
use TheCodingMachine\Gotenberg\RequestException;

class PoudreTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function postTest(request $request){
        $this->authorize('create', TestPoudre::class);
        $request->validate([
            'name'=>['required','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'ddn'=>['required'],
            'tel'=>['tel'=> 'required','regex:/5{3}-\d\d/'],
            'liveplace'=>['required', 'string'],
            'place'=>["required", 'string'],
            'clothPresence'=>['boolean'],
            'skinPresence'=>['boolean'],
        ]);


        $test = new TestPoudre();
        $Patient = PatientController::PatientExist($request->name);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $request->name;
            $Patient->tel = $request->tel;
            $Patient->naissance = $request->ddn;
            $Patient->living_place = $request->liveplace;
            $Patient->save();
            event(new Notify('Nouveau patient créé',1));
        }else {
         $Patient->tel = $request->tel;
         $Patient->naissance = $request->ddn;
         $Patient->living_place = $request->liveplace;
         $Patient->save();
        }


        $test->patient_id = $Patient->id;
        $test->user_id = Auth::id();
        $test->lieux_prelevement = $request->place;
        $test->on_skin_positivity = $request->skinPresence;
        $test->on_clothes_positivity = $request->clothPresence;
        $service = \Session::get('service')[0];
        $test->service = $service;
        $test->save();

        $tester = User::where('id', Auth::id())->first();
        $path =  storage_path('app/public/test/poudre/') . "/pouder_".$test->id.'.pdf';
        $this->dispatch(new ProcesTestPoudrePDFGenerator($test, $path));

        $embed = [
            [
                'title'=>"Résultat d\'un tes de poudre {$service} :",
                'color'=>'1285790',
                'fields'=>[
                    [
                        'name'=>'Patient : ',
                        'value'=>$Patient->name,
                        'inline'=>true
                    ],[
                        'name'=>'Contact patient : ',
                        'value'=> $Patient->tel,
                        'inline'=>true
                    ],[
                        'name'=>'Date de naissance : ',
                        'value'=>date('d/m/Y', strtotime($request->DDN)),
                        'inline'=>true
                    ],[
                        'name'=>'Lieux de résidence : ',
                        'value'=>($Patient->living_place),
                        'inline'=>false
                    ],[
                        'name'=>'Reaction sur la peau : ',
                        'value'=>($request->peau ? 'Positif :white_check_mark: ': 'Négatif :x:'),
                        'inline'=>false
                    ],[
                        'name'=>'Reaction sur les vetements : ',
                        'value'=>($request->vetements ? 'Positif :white_check_mark: ' : 'Négatif :x:'),
                        'inline'=>false
                    ],[
                        'name'=>'Date et heure du test : ',
                        'value'=>date('d/m/Y à H:i'),
                        'inline'=>false
                    ],[
                        'name'=>'Lieux de prise en charge : ',
                        'value'=>$request->place,
                        'inline'=>true
                    ],[
                        'name'=>'Personnel en charge : ',
                        'value'=>$tester->name,
                        'inline'=>true
                    ],[
                        'name'=>'PDF',
                        'value'=>":link: [`PDF`](".env('APP_URL').'/storage/test/poudre/'.$test->id . ".pdf)"
                    ]
                ],
                'footer'=>[
                    'text' => "Service de Biologie ({$service})",
                ]
            ]
        ];
        \Discord::postMessage(DiscordChannel::Poudre, $embed, null);

        $logs = new LogsController();
        $logs->TestDePoudreLogging($test->id, $tester->id);

        event(new Notify('Test enregistré',1,Auth::user()->id));

        return response()->json(['status'=>'OK'],201);
    }

    public function exportTest($id){

        $test = TestPoudre::where('id', $id)->first();
        $user = $test->GetPersonnel;
        $path =  storage_path('app/public/test/poudre/') . "/pouder_".$test->id.'.pdf';

        if(!file_exists($path)){
            $pdf = Pdf::loadView('PDF.TDP',['test'=>$test, 'user'=>$user]);
            $pdf->save($path);
            return $pdf->stream();
        }

        return response()->file($path);
    }

    public function getAllTests(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->authorize('viewAny', TestPoudre::class);
        $tests = TestPoudre::search($request->query('query'))->paginate();
        foreach ($tests as $test) $test->GetPatient;
        return response()->json(['status'=>'OK', 'tests'=>$tests]);
    }
}
