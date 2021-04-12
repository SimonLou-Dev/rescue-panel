<?php

namespace App\Http\Controllers;

use App\Events\Notify;
use App\Models\DayService;
use App\Models\Service;
use App\Models\Services;
use App\Models\User;
use App\Models\WeekRemboursement;
use App\Models\WeekService;
use App\PDFExporter\ServicePDFExporter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;

class ServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('access');
    }

    public function getUserService(): \Illuminate\Http\JsonResponse
    {
        $date = $this::getWeekNumber();
        if($date == 1 ){
            $week = WeekService::where('week_number', '=', 1)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 2){
            $week = WeekService::where('week_number', '<=', 2)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else if($date == 3){
            $week = WeekService::where('week_number', '<=', 3)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }else{
            $date = $date -3;
            $week = WeekService::where('week_number', '>', $date)->orderBy('id','desc')->where('user_id', Auth::id())->get();
        }
        $userserivce= Service::where('user_id', Auth::user()->id)->orderBy('id','desc')->take(20)->get();
        return response()->json([
            'status'=>'ok',
            'week'=>$week,
            'services'=>$userserivce,
        ]);

    }

    public function getAllservice(int $semaine = NULL): \Illuminate\Http\JsonResponse
    {
        $max = $this::getWeekNumber();
        if($semaine){
            $date= (int) $semaine;
        }else{
            $date = $this::getWeekNumber();
        }
        $service = WeekService::where('week_number', $date)->orderBy('id','asc')->get();
        $a= 0;
        while($a < count($service)){
            $service[$a]->GetUser->GetGrade;
            $a++;
        }
        return response()->json([
            'service'=>$service,
            'maxweek'=>$max,
        ]);
    }

    public function setServiceByAdmin(Request $request, string $userid): \Illuminate\Http\JsonResponse
    {
        $user = User::where('id', $userid)->first();
        $this->setService($user, true);

        return response()->json(['status'=>'OK']);
    }

    public function addRows(): \Illuminate\Http\JsonResponse
    {
        $week =  date('W', time());
        $users = User::where('grade', '>', 1)->get();
        $dayservice = WeekService::where('week', $week)->get('user_id');
        $b = 0;
        $array = array();
        while($b < count($dayservice)){
            array_push($array, $dayservice[$b]->user_id);
            $b++;
        }
        $a = 0;
        $datas = array();
        while ($a < count($users)){
            if(!in_array($users[$a]->id, $array)){
                array_push($datas, ['week'=>$week, 'user_id'=>$users[$a]->id]);
            }
            $a++;
        }
        WeekService::insert($datas);
        return response()->json(['status'=>"OK"],201);
    }

    public function modifyTimeService(Request $request): \Illuminate\Http\JsonResponse
    {
        $name = (string) $request->name;
        $action = (int) $request->action;
        $time = (string) $request->time;

        $user =User::where('name', $name)->firstOrFail();
        $WeekService= WeekService::where('user_id', $user->id)->where('week_number', $this::getWeekNumber())->first();
        if($action === 1){
            $WeekService->total = $this::addTime($WeekService->total, $time.':00');
        }else{
            $WeekService->total = $this::removeTime($WeekService->total, $time.':00');
        }
        $WeekService->save();

        event(new Notify('Vous avez bien modifé le temps de service',1));
        return response()->json(['status'=>'OK'],201);
    }

    public static function setService(User $user, bool $admin): bool
    {
        if($user->service){
            $user->service = false;
            $user->save();
            $service = Service::where('user_id', $user->id)->whereNull('Total')->first();
            $start = date_create($service->started_at);
            $interval = $start->diff(date_create(date('Y-m-d H:i:s', time())));
            $diff = $interval->d*24 + $interval->h;
            $formated = $diff . ':' . $interval->format('%i:%S');
            $week =  ServiceController::getWeekNumber();
            $service->ended_at = date('H:I:s', time());
            $service->total = $formated;
            $service->save();

            $WeekService = WeekService::where('week_number', $week)->where('user_id', $user->id);
            if($WeekService->count() == 1){
                $WeekService = $WeekService->first();
                $total = $WeekService->total;
                $day = $WeekService[LayoutController::getdaystring()];
                $service = new ServiceController();
                $WeekService->total = $service->addTime($formated, $total);
                $WeekService[LayoutController::getdaystring()] = $service->addTime($formated, $day);
                $WeekService->save();

            }else{
                $WeekService = new WeekService();
                $WeekService->user_id = $user->id;
                $WeekService['week_number'] = $week;
                $WeekService[LayoutController::getdaystring()] = $formated;
                $WeekService->total = $formated;
                $WeekService->save();
            }
            $user->save();
            $formated = explode(':', $formated);
            if($admin){
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated[0]. ':' . $formated[1],
                            'color'=>'15158332',
                            'footer'=>[
                                'text'=>'stoppé par : ' . Auth::user()->name
                            ]
                        ]
                    ]
                ]);
            }else{
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Fin de service de ' . $user->name,
                            'description'=> 'temps de service : ' . $formated[0]. ':' . $formated[1],
                            'color'=>'15158332',
                        ]
                    ]
                ]);
            }
            return true;

        }else{
            $user->service= true;
            $service = new Service();
            $service->user_id = $user->id;
            $service->started_at = date('Y-m-d H:i:s',time());
            $service->save();
            $user->save();
            Auth::user()->service = true;
            if($admin){
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Prise de service de ' . $user->name,
                            'color'=>'3066993',
                            'footer'=>[
                                'text'=>'Mis en service par : ' . Auth::user()->name
                            ]
                        ]
                    ]
                ]);
            }else{
                Http::post(env('WEBHOOK_SERVICE'),[
                    'embeds'=>[
                        [
                            'title'=>'Prise de service de ' . $user->name,
                            'color'=>'3066993',
                        ]
                    ]
                ]);
            }
            return true;
        }
    }

    public static function addTime(string $base, string $toadd): string
    {
        $base = explode(':', $base);
        $base['h'] = (int) $base[0];
        $base['m'] = (int) $base[1];
        $base['s'] = (int)$base[2];
        $toadd = explode(':', $toadd);
        $toadd['h'] = (int) $toadd[0];
        $toadd['m'] = (int) $toadd[1];
        $toadd['s'] = (int) $toadd[2];


        $sec = (int) $base["s"] + (int) $toadd['s'];
        if($sec > 59){
            while ($sec > 59){
                $base['m']++;
                $sec = $sec - 60;
            }
            $toadd["s"] = $sec;
        }else{
            $toadd["s"] = $sec;
        }
        $min = (int) $base["m"] + (int) $toadd['m'];
        if($min > 59){
            while ($min > 59){
                $base['h']++;
                $min= $min - 60;
            }
            $toadd['m'] = $min;
        }else{
            $toadd['m'] = $min;
        }
        $toadd['h'] = (int) $base['h'] + (int) $toadd['h'];

        return $toadd['h'] . ':' . $toadd['m'] . ':'. $toadd['s'];
    }

    public static function removeTime(string $base, string $toremove): string
    {
        $base = explode(':', $base);
        $base['h'] = (int) $base[0];
        $base['m'] = (int) $base[1];
        $base['s'] = (int)$base[2];
        $toremove = explode(':', $toremove);
        $toremove['h'] = (int) $toremove[0];
        $toremove['m'] = (int) $toremove[1];
        $toremove['s'] = (int) $toremove[2];
        $baseSec = $base['h']*3600 + $base['m']*60 + $base['s'];
        $toremoveSec = $toremove['h']*3600 + $toremove['m'] *60 + $toremove['s'];
        $final = $baseSec - $toremoveSec;
        $first = $final / 3600;
        $firstR = $final % 3600;
        $seconde = $firstR / 60;
        $secondeR = $firstR % 60;

        $first = (string) ($first < 10 ? '0'. (int) $first :  (int)$first);
        $seconde = (string) ($seconde < 10 ? '0'. (int) $seconde :  (int) $seconde);
        $secondeR = (string) ($secondeR < 10 ? '0'. (int) $secondeR :  (int)$secondeR);

        return $first . ':' .  $seconde . ':'. $secondeR;
    }

    public static function getWeekNumber(): int
    {
        $day = LayoutController::getdaystring();
        $date = (int) date('W', time());
        if($day == 'dimanche'){
            return $date +1;
        }else{
            return $date;
        }
    }

    public function getWeekServiceExel(string $week = null){
        if(is_null($week)){
            $week = $this::getWeekNumber();
        }else{
            $week = (int) $week;
        }
        $users = User::where('grade_id', '>', 1)->orderByDesc('grade_id')->get();
        $services = WeekService::where('week_number', '=', '15')->get();
        $remboursements = WeekRemboursement::where('week_number',$week)->get();

        $column[] = array();
        $column[] = array('Membre','grade', 'Remboursements', 'dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'total');
        $userRemboursements = [];
        foreach ($remboursements as $remboursement){
            $userRemboursements[$remboursement->user_id] = $remboursement->total;
        }


        $userServices = array();
        foreach ($services as $service){
            $userServices[$service->user_id] = 0;
        }

        $servicesNumber = array();
        foreach ($services as $service){
            $servicesNumber[$service->user_id] = 0;
        }

        foreach ($users as $user){
            if(array_key_exists($user->id, $userServices) == true){
                $service = $services[array_search($user->id, $servicesNumber)];
                $column[] = [
                    'Membre'=> $service->GetUser->name,
                    'grade'=>$service->GetUser->GetGrade->name,
                    'Remboursements'=> array_key_exists($user->id, $userRemboursements) ? $userRemboursements[$user->id] :0,
                    'dimanche'=>$service->dimanche,
                    'lundi'=>$service->lundi,
                    'mardi'=>$service->mardi,
                    'mercredi'=>$service->mercredi,
                    'jeudi'=>$service->jeudi,
                    'vendredi'=>$service->vendredi,
                    'samedi'=>$service->samedi,
                    'total'=>$service->total,
                ];
            }else{
                $user = User::where('id', $user->id)->first();
                $column[] = [
                    'Membre'=> $user->name,
                    'grade'=>$user->GetGrade->name,
                    'Remboursements'=> array_key_exists($user->id, $userRemboursements) ? $userRemboursements[$user->id] :0,
                    'dimanche'=>0,
                    'lundi'=>0,
                    'mardi'=>0,
                    'mercredi'=>0,
                    'jeudi'=>0,
                    'vendredi'=>0,
                    'samedi'=>0,
                    'total'=>0,
                ];
            }
        }
        $export = new ServicePDFExporter($column);

        return Excel::download((object)$export, 'RemboursementsServicesSemaine'. $week .'.xlsx');


    }


}
