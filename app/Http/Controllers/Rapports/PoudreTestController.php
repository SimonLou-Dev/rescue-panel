<?php

namespace App\Http\Controllers\Rapports;

use App\Events\Notify;
use App\Http\Controllers\Controller;
use App\Jobs\ProcesTestPoudrePDFGen;
use App\Jobs\ProcesTestPoudrePDFGenerator;
use App\Models\Intervention;
use App\Models\Patient;
use App\Models\TestPoudre;
use App\Models\User;
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
        $this->middleware('access');
    }

    public function postTest(request $request){
        $request->validate([
            'patient'=>['required', 'string','regex:/[a-zA-Z.+_]+\s[a-zA-Z.+_]/'],
            'DDN'=>['required'],
            'tel'=>['required','numeric'],
            'lieux'=>['required', 'string'],
        ]);


        $test = new TestPoudre();
        $explode = explode(' ', $request->patient);
        $Patient = PatientController::PatientExist($explode[1], $explode[0]);
        if(is_null($Patient)) {
            $Patient = new Patient();
            $Patient->name = $explode[1];
            $Patient->vorname = $explode[0];
            $Patient->tel = $request->tel;
            $Patient->naissance = $request->DDN;
            $Patient->living_place = $request->lieux;
            $Patient->save();
            event(new Notify('Nouveau patient créé',1));
        }else {
         $Patient->tel = $request->tel;
         $Patient->naissance = $request->DDN;
         $Patient->living_place = $request->lieux;
         $Patient->save();
        }




        $test->patient_id = $Patient->id;
        $test->user_id = Auth::id();
        $test->lieux_prelevement = $request->lieux;
        $test->on_skin_positivity = $request->peau;
        $test->on_clothes_positivity = $request->vetements;
        $test->save();

        $tester = User::where('id', Auth::id())->first();
        $path = 'public/test/poudre/'. $test->id . ".pdf";
        $this->dispatch(new ProcesTestPoudrePDFGenerator($test, $path));

        Http::post(env('WEBHOOK_POUDRE'),[
            'username'=> env('service') . " - MDT",
            'avatar_url'=>'https://bcfd.simon-lou.com/assets/images/'. env('service'). '.png',
            'embeds'=>[
                [
                    'title'=>'Résultat d\'un tes de poudre du BCFD :',
                    'color'=>'1285790',
                    'fields'=>[
                        [
                            'name'=>'Patient : ',
                            'value'=>$explode[0] . ' ' .$explode[1],
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
                            'value'=>($request->liveplace ? 'BC':'LS'),
                            'inline'=>false
                        ],[
                            'name'=>'Reaction sur la peau : ',
                            'value'=>($request->peau ? 'Positif': 'Négatif'),
                            'inline'=>false
                        ],[
                            'name'=>'Reaction sur les vetements : ',
                            'value'=>($request->vetements ? 'Positif': 'Négatif'),
                            'inline'=>false
                        ],[
                            'name'=>'Date et heure du test : ',
                            'value'=>date('d/m/Y à H:i'),
                            'inline'=>false
                        ],[
                            'name'=>'Lieux de prise en charge : ',
                            'value'=>$request->lieux,
                            'inline'=>true
                        ],[
                            'name'=>'Personnel en charge : ',
                            'value'=>$tester->name,
                            'inline'=>true
                        ]
                    ],
                    'footer'=>[
                        'text' => 'Service de Biologie du BCFD ',
                    ]
                ]
            ]
        ]);

        event(new Notify('Test enregistré',1));

        return response()->json(['status'=>'OK'],201);
    }

    public function exportTest($id){

        $test = TestPoudre::where('id', $id)->first();
        $user = $test->GetPersonnel;
        $user = $user->name;
        $path = "public/test/poudre/pouder_". $test->id . ".pdf";







        if(!file_exists(Storage::path($path))){
            ob_start();
            require(base_path('resources/PDF/test/poudre.php'));
            $content = ob_get_clean();
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadHTML($content);
            $this->dispatch(new ProcesTestPoudrePDFGenerator($test, $path));
            return $pdf->stream();
        }else{
            return response()->file(Storage::path($path));
        }






        /*


        $index = DocumentFactory::makeFromString('poudre.html', $content);
        $assets = [
            DocumentFactory::makeFromPath('LONG_EMS_BC_2.png', base_path('/resources/PDF/test/LONG_EMS_BC_2.png')),
            DocumentFactory::makeFromPath('signature.png', base_path('/resources/PDF/test/signature.png'))
        ];



        $pdf = new HTMLRequest($index);

        $pdf->setAssets($assets);
        return dd($client->store($pdf, $path));
        try {

        } catch (ClientException | FilesystemException | RequestException | \Exception $e) {
            Log::critical($e);
        }

        return $client->post($pdf);*/

        return \response()->file($path);

    }

    public function getAllTests(): \Illuminate\Http\JsonResponse
    {
        $tests = TestPoudre::all()->take(100);
        $a = 0;
        while ($a < count($tests)){
            $tests[$a]->GetPatient;
            $a++;
        }
        return response()->json(['status'=>'OK', 'tests'=>$tests]);
    }
}
